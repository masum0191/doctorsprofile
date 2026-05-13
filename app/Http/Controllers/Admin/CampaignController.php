<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendCampaignEmailJob;
use App\Jobs\SendCampaignWhatsAppJob;
use App\Models\Campaign;
use App\Models\CampaignRecipient;
use App\Models\Contact;
use App\Models\Segment;
use App\Models\MessageTemplate;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(Request $request) {
        $query = Campaign::query();

        if ($request->filled('q')) {
            $search = trim((string) $request->q);
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");

                if (ctype_digit($search)) {
                    $builder->orWhere('id', (int) $search);
                }
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        $campaigns = $query->orderByDesc('id')->paginate(20)->withQueryString();
        $campaignStats = [
            'total' => Campaign::count(),
            'draft' => Campaign::where('status', 'draft')->count(),
            'scheduled' => Campaign::where('status', 'scheduled')->count(),
            'running' => Campaign::where('status', 'running')->count(),
        ];

        return view('admin.marketing.campaigns.index', compact('campaigns', 'campaignStats'));
    }

    public function create() {
        $segments = Segment::orderBy('name')->get();
        $templates = MessageTemplate::where('is_active',1)->orderBy('name')->get();
        return view('admin.marketing.campaigns.create', compact('segments','templates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'channel'=>'required|in:email,whatsapp',
            'name'=>'required|string|max:150',
            'segment_id'=>'nullable|exists:segments,id',
            'template_id'=>'nullable|exists:message_templates,id',
            'subject'=>'nullable|string|max:190',
            'body'=>'nullable|string',
            'scheduled_at'=>'nullable|date',
        ]);

        $campaign = Campaign::create([
            'channel'=>$data['channel'],
            'name'=>$data['name'],
            'segment_id'=>$data['segment_id'] ?? null,
            'template_id'=>$data['template_id'] ?? null,
            'subject'=>$data['subject'] ?? null,
            'body'=>$data['body'] ?? null,
            'scheduled_at'=>$data['scheduled_at'] ?? null,
            'status'=> $data['scheduled_at'] ? 'scheduled' : 'draft',
            'totals_json'=> ['total'=>0,'sent'=>0,'failed'=>0,'delivered'=>0,'opened'=>0],
        ]);

        return redirect()->route('superadmin.marketing.campaigns.show',$campaign)->with('success','Campaign created');
    }

    public function show(Campaign $campaign)
    {
        $campaign->load(['segment','template']);
        $recipients = $campaign->recipients()->with('contact')->orderByDesc('id')->paginate(25);
        return view('admin.marketing.campaigns.show', compact('campaign','recipients'));
    }
    // edit function
    public function edit(Campaign $campaign)
    {
        $segments = Segment::orderBy('name')->get();
        $templates = MessageTemplate::where('is_active',1)->orderBy('name')->get();
        return view('admin.marketing.campaigns.edit', compact('campaign','segments','templates'));
    } 
    public function update(Request $request, Campaign $campaign)
    {
        // prevent edits while running
        if ($campaign->status === 'running') {
            return back()->with('error', 'Cannot modify a running campaign');
        }

        $data = $request->validate([
            'channel' => 'required|in:email,whatsapp',
            'name' => 'required|string|max:150',
            'segment_id' => 'nullable|exists:segments,id',
            'template_id' => 'nullable|exists:message_templates,id',
            'subject' => 'nullable|string|max:190',
            'body' => 'nullable|string',
            'scheduled_at' => 'nullable|date',
        ]);

        $originalChannel = $campaign->channel;
        $originalSegment = $campaign->segment_id;
        $originalTemplate = $campaign->template_id;

        $scheduledAt = $data['scheduled_at'] ?? null;
        $status = $scheduledAt
            ? (\Carbon\Carbon::parse($scheduledAt)->isFuture() ? 'scheduled' : 'draft')
            : 'draft';

        $campaign->update([
            'channel' => $data['channel'],
            'name' => $data['name'],
            'segment_id' => $data['segment_id'] ?? null,
            'template_id' => $data['template_id'] ?? null,
            'subject' => $data['subject'] ?? null,
            'body' => $data['body'] ?? null,
            'scheduled_at' => $scheduledAt,
            'status' => $status,
        ]);

        // If key targeting attributes changed, clear recipients and reset totals
        if (
            $originalChannel !== $data['channel']
            || ($originalSegment !== ($data['segment_id'] ?? null))
            || ($originalTemplate !== ($data['template_id'] ?? null))
        ) {
            CampaignRecipient::where('campaign_id', $campaign->id)->delete();
            $campaign->update([
                'totals_json' => ['total' => 0, 'sent' => 0, 'failed' => 0, 'delivered' => 0, 'opened' => 0],
            ]);
        }

        return redirect()->route('superadmin.marketing.campaigns.show', $campaign)->with('success', 'Campaign updated');
    }

    public function buildRecipients(Campaign $campaign)
    {
        $segmentRules = $campaign->segment?->rules ?? [];

        $q = Contact::query()->where('status','active')->where('do_not_contact',0);

        if ($campaign->channel === 'email') {
            $q->whereNotNull('email')->where('opt_in_email',1);
        } else {
            $q->whereNotNull('whatsapp')->where('opt_in_whatsapp',1);
        }

        // Apply rules (simple keys)
        foreach ($segmentRules as $key => $value) {
            if ($value === null || $value === '') continue;
            if (in_array($key, ['city','specialty'])) $q->where($key, $value);
            if ($key === 'bmdc_no') $q->where('bmdc_no','like',"%{$value}%");
        }

        $contacts = $q->get(['id']);

        $created = 0;
        foreach ($contacts as $c) {
            $row = CampaignRecipient::firstOrCreate([
                'campaign_id'=>$campaign->id,
                'contact_id'=>$c->id
            ],[
                'status'=>'queued',
                'queued_at'=>now()
            ]);
            if ($row->wasRecentlyCreated) $created++;
        }

        $totals = $campaign->totals_json ?? [];
        $totals['total'] = (int) CampaignRecipient::where('campaign_id',$campaign->id)->count();
        $campaign->update(['totals_json'=>$totals]);

        return back()->with('success',"Recipients built. Added: {$created}, Total: {$totals['total']}");
    }

    public function start(Campaign $campaign)
    {
        $campaign->update([
            'status' => 'running',
            'started_at' => now()
        ]);

        // Dispatch sending jobs in chunks
        CampaignRecipient::where('campaign_id',$campaign->id)
            ->where('status','queued')
            ->orderBy('id')
            ->chunkById(200, function($rows) use ($campaign){
                foreach ($rows as $r) {
                    if ($campaign->channel === 'email') {
                        SendCampaignEmailJob::dispatch($campaign->id, $r->id)->onQueue('marketing');
                    } else {
                        SendCampaignWhatsAppJob::dispatch($campaign->id, $r->id)->onQueue('marketing');
                    }
                }
            });

        return back()->with('success','Campaign started and queued for sending');
    }
}
