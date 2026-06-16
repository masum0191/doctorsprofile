<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignRecipient;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\MessageTemplate;
use App\Models\Segment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class CrmController extends Controller
{
    public function index()
    {
        $tables = [
            'leads' => Schema::hasTable('leads'),
            'contacts' => Schema::hasTable('contacts'),
            'campaigns' => Schema::hasTable('campaigns'),
            'campaign_recipients' => Schema::hasTable('campaign_recipients'),
            'segments' => Schema::hasTable('segments'),
            'message_templates' => Schema::hasTable('message_templates'),
        ];

        $leadStats = [
            'total' => 0,
            'new' => 0,
            'contacted' => 0,
            'converted' => 0,
            'conversion_rate' => 0,
        ];
        $recentLeads = collect();
        $sourceBreakdown = collect();

        if ($tables['leads']) {
            $leadStats['total'] = Lead::count();
            $leadStats['new'] = Lead::where('status', 'new')->count();
            $leadStats['contacted'] = Lead::where('status', 'contacted')->count();
            $leadStats['converted'] = Lead::where('status', 'converted')->count();
            $leadStats['conversion_rate'] = $leadStats['total'] > 0
                ? round(($leadStats['converted'] / $leadStats['total']) * 100, 1)
                : 0;

            $recentLeads = Lead::latest('id')->limit(6)->get();
            $sourceBreakdown = Lead::select('source')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('source')
                ->orderByDesc('total')
                ->limit(5)
                ->get()
                ->map(fn (Lead $lead) => [
                    'label' => $lead->source ?: 'Unknown',
                    'total' => (int) $lead->total,
                ]);
        }

        $contactStats = [
            'total' => 0,
            'email_opt_in' => 0,
            'whatsapp_opt_in' => 0,
            'contactable' => 0,
            'do_not_contact' => 0,
        ];
        $recentContacts = collect();
        $topSpecialties = collect();
        $topCities = collect();

        if ($tables['contacts']) {
            $contacts = $this->activeContactsQuery();

            $contactStats['total'] = (clone $contacts)->count();
            $contactStats['email_opt_in'] = $this->countContactsByBooleanColumn($contacts, 'opt_in_email');
            $contactStats['whatsapp_opt_in'] = $this->countContactsByBooleanColumn($contacts, 'opt_in_whatsapp');
            $contactStats['do_not_contact'] = $this->countContactsByBooleanColumn(Contact::query(), 'do_not_contact');

            if ($this->hasColumn('contacts', 'opt_in_email') && $this->hasColumn('contacts', 'opt_in_whatsapp')) {
                $contactStats['contactable'] = (clone $contacts)
                    ->where(function (Builder $query) {
                        $query->where('opt_in_email', 1)
                            ->orWhere('opt_in_whatsapp', 1);
                    })
                    ->count();
            }

            $recentContacts = (clone $contacts)->latest('id')->limit(6)->get();
            $topSpecialties = $this->topContactColumn('specialty');
            $topCities = $this->topContactColumn('city');
        }

        $campaignStats = [
            'total' => 0,
            'draft' => 0,
            'scheduled' => 0,
            'running' => 0,
            'sent' => 0,
            'recipients' => 0,
            'delivered' => 0,
            'opened' => 0,
            'failed' => 0,
        ];
        $recentCampaigns = collect();

        if ($tables['campaigns']) {
            $campaignStats['total'] = Campaign::count();
            $campaignStats['draft'] = Campaign::where('status', 'draft')->count();
            $campaignStats['scheduled'] = Campaign::where('status', 'scheduled')->count();
            $campaignStats['running'] = Campaign::where('status', 'running')->count();
            $campaignStats['sent'] = Campaign::where('status', 'sent')->count();
            $recentCampaigns = Campaign::latest('id')->limit(5)->get();
        }

        if ($tables['campaign_recipients']) {
            $campaignStats['recipients'] = CampaignRecipient::count();
            $campaignStats['delivered'] = CampaignRecipient::where('status', 'delivered')->count();
            $campaignStats['opened'] = CampaignRecipient::where('status', 'opened')->count();
            $campaignStats['failed'] = CampaignRecipient::where('status', 'failed')->count();
        }

        $assetStats = [
            'segments' => $tables['segments'] ? Segment::count() : 0,
            'templates' => $tables['message_templates'] ? MessageTemplate::count() : 0,
        ];

        $unavailableTables = collect($tables)
            ->filter(fn (bool $exists) => ! $exists)
            ->keys()
            ->values();

        return view('admin.crm.index', compact(
            'assetStats',
            'campaignStats',
            'contactStats',
            'leadStats',
            'recentCampaigns',
            'recentContacts',
            'recentLeads',
            'sourceBreakdown',
            'topCities',
            'topSpecialties',
            'unavailableTables'
        ));
    }

    private function activeContactsQuery(): Builder
    {
        $query = Contact::query();

        if ($this->hasColumn('contacts', 'status')) {
            $query->where('status', 'active');
        }

        return $query;
    }

    private function countContactsByBooleanColumn(Builder $baseQuery, string $column): int
    {
        if (! $this->hasColumn('contacts', $column)) {
            return 0;
        }

        return (clone $baseQuery)->where($column, 1)->count();
    }

    private function topContactColumn(string $column)
    {
        if (! $this->hasColumn('contacts', $column)) {
            return collect();
        }

        return $this->activeContactsQuery()
            ->whereNotNull($column)
            ->where($column, '!=', '')
            ->select($column)
            ->selectRaw('COUNT(*) as total')
            ->groupBy($column)
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn (Contact $contact) => [
                'label' => $contact->{$column},
                'total' => (int) $contact->total,
            ]);
    }

    private function hasColumn(string $table, string $column): bool
    {
        return Schema::hasTable($table) && Schema::hasColumn($table, $column);
    }
}
