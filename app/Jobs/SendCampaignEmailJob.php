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
use Illuminate\Support\Facades\Mail;

class SendCampaignEmailJob implements ShouldQueue
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
        if (!$contact || $contact->do_not_contact || !$contact->opt_in_email || empty($contact->email)) {
            $rec->update(['status'=>'skipped','error_message'=>'Contact not eligible']);
            return;
        }

        // Load template
        $tpl = $campaign->template_id ? MessageTemplate::find($campaign->template_id) : null;
        $subject = $campaign->subject ?: ($tpl?->subject ?? 'Message');
        $body = $campaign->body ?: ($tpl?->body ?? '');

        // Replace variables
        $body = $this->renderVariables($body, $contact);

        try {
            Mail::raw($body, function($m) use ($contact, $subject){
                $m->to($contact->email)->subject($subject);
            });

            $rec->update([
                'status'=>'sent',
                'sent_at'=>now()
            ]);

            $contact->update(['last_contacted_at'=>now()]);

        } catch (\Throwable $e) {
            $rec->update([
                'status'=>'failed',
                'error_message'=>substr($e->getMessage(),0,250)
            ]);
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
