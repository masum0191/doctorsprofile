<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\CampaignRecipient;
use App\Models\MarketingSetting;
use App\Models\MessageTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendCampaignWhatsAppJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $campaignId;
    public int $recipientId;

    public function __construct(int $campaignId, int $recipientId)
    {
        $this->campaignId = $campaignId;
        $this->recipientId = $recipientId;
    }

    public function handle()
    {
        $campaign = Campaign::findOrFail($this->campaignId);
        $rec = CampaignRecipient::with('contact')->findOrFail($this->recipientId);

        if ($rec->status !== 'queued') return;

        $contact = $rec->contact;
        if (!$contact || $contact->do_not_contact || !$contact->opt_in_whatsapp || empty($contact->whatsapp)) {
            $rec->update(['status'=>'skipped','error_message'=>'Contact not eligible']);
            return;
        }

        $setting = MarketingSetting::first();
        if (!$setting || empty($setting->whatsapp_token) || empty($setting->whatsapp_phone_number_id)) {
            $rec->update(['status'=>'failed','error_message'=>'WhatsApp API not configured']);
            return;
        }

        $tpl = $campaign->template_id ? MessageTemplate::find($campaign->template_id) : null;
        $body = $campaign->body ?: ($tpl?->body ?? '');

        $body = $this->renderVariables($body, $contact);

        // Example using Meta Cloud API "text message" (only works in-session; template sending needs template payload)
        // For production: store template_name + language in template.meta and send template payload.
        try {
            $url = "https://graph.facebook.com/v20.0/{$setting->whatsapp_phone_number_id}/messages";

            $resp = Http::withToken($setting->whatsapp_token)->post($url, [
                "messaging_product" => "whatsapp",
                "to" => $contact->whatsapp,
                "type" => "text",
                "text" => ["body" => $body]
            ]);

            if (!$resp->successful()) {
                $rec->update(['status'=>'failed','error_message'=>substr($resp->body(),0,250)]);
                return;
            }

            $rec->update([
                'status'=>'sent',
                'sent_at'=>now(),
                'provider_message_id' => data_get($resp->json(), 'messages.0.id')
            ]);

            $contact->update(['last_contacted_at'=>now()]);

        } catch (\Throwable $e) {
            $rec->update(['status'=>'failed','error_message'=>substr($e->getMessage(),0,250)]);
        }
    }

    private function renderVariables(string $text, $contact): string
    {
        $map = [
            '{name}' => $contact->name,
            '{city}' => $contact->city,
            '{specialty}' => $contact->specialty,
            '{bmdc_no}' => $contact->bmdc_no,
        ];

        return str_replace(array_keys($map), array_values($map), $text);
    }
}
