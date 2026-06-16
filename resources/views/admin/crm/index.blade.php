@extends('layouts.supperadmin')

@section('title', 'CRM Admin')
@section('page-title', 'CRM Admin')
@section('page-subtitle', 'Leads, audience contacts, and campaign activity')

@section('content')
<style>
    .crm-shell {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .crm-header {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        align-items: flex-start;
    }

    .crm-title h4 {
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.25rem;
    }

    .crm-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .crm-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }

    .crm-stat {
        height: 100%;
        padding: 1rem;
    }

    .crm-stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex: 0 0 auto;
    }

    .crm-stat-label {
        color: #64748b;
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .crm-stat-value {
        color: #111827;
        font-size: 1.7rem;
        font-weight: 750;
        line-height: 1;
        margin-top: 0.35rem;
    }

    .crm-stat-note {
        color: #64748b;
        font-size: 0.8rem;
        margin-top: 0.45rem;
    }

    .crm-panel-header {
        padding: 0.9rem 1rem;
        border-bottom: 1px solid #eef2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .crm-panel-title {
        font-weight: 700;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .crm-panel-body {
        padding: 1rem;
    }

    .crm-bar {
        height: 8px;
        border-radius: 999px;
        background: #e5e7eb;
        overflow: hidden;
    }

    .crm-bar-fill {
        height: 100%;
        border-radius: inherit;
    }

    .crm-list-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        padding: 0.85rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .crm-list-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .crm-avatar {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #e8f3ef;
        color: #318069;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex: 0 0 auto;
    }

    .crm-status {
        border-radius: 999px;
        padding: 0.25rem 0.6rem;
        font-size: 0.72rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .crm-status-new {
        background: #eef2ff;
        color: #4338ca;
    }

    .crm-status-contacted {
        background: #fff7ed;
        color: #c2410c;
    }

    .crm-status-converted {
        background: #ecfdf5;
        color: #047857;
    }

    .crm-status-default {
        background: #f1f5f9;
        color: #475569;
    }

    .crm-mini-link {
        color: #318069;
        font-weight: 700;
        font-size: 0.82rem;
    }

    .crm-empty {
        color: #64748b;
        text-align: center;
        padding: 2rem 1rem;
    }

    .crm-empty i {
        color: #94a3b8;
        font-size: 2rem;
        display: block;
        margin-bottom: 0.5rem;
    }

    .crm-module {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.85rem;
        border: 1px solid #eef2f7;
        border-radius: 8px;
        color: #111827;
        transition: all 0.2s ease;
    }

    .crm-module:hover {
        border-color: rgba(49, 128, 105, 0.35);
        background: rgba(49, 128, 105, 0.04);
        color: #111827;
    }

    @media (max-width: 768px) {
        .crm-header {
            flex-direction: column;
        }

        .crm-actions {
            justify-content: flex-start;
            width: 100%;
        }

        .crm-actions .btn {
            flex: 1 1 160px;
        }

        .crm-list-row {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<div class="crm-shell">
    <div class="crm-header">
        <div class="crm-title">
            <h4><i class="ri-shake-hands-line text-primary me-2"></i>CRM Admin</h4>
            <p class="text-muted mb-0">Lead pipeline, outreach audience, and campaign health in one place.</p>
        </div>
        <div class="crm-actions">
            <a href="{{ route('superadmin.leads.index') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="ri-user-add-line"></i>
                Leads
            </a>
            <a href="{{ route('superadmin.marketing.contacts.index') }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-2">
                <i class="ri-contacts-book-line"></i>
                Contacts
            </a>
            <a href="{{ route('superadmin.marketing.campaigns.create') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="ri-megaphone-line"></i>
                New Campaign
            </a>
        </div>
    </div>

    @if($unavailableTables->isNotEmpty())
        <div class="alert alert-warning mb-0">
            <i class="ri-alert-line me-1"></i>
            Some CRM tables are not available yet: {{ $unavailableTables->implode(', ') }}.
        </div>
    @endif

    <div class="row g-3">
        <div class="col-xl-3 col-md-6">
            <div class="crm-card crm-stat">
                <div class="d-flex justify-content-between gap-3">
                    <div>
                        <div class="crm-stat-label">Total Leads</div>
                        <div class="crm-stat-value">{{ number_format($leadStats['total']) }}</div>
                        <div class="crm-stat-note">{{ number_format($leadStats['conversion_rate'], 1) }}% converted</div>
                    </div>
                    <div class="crm-stat-icon" style="background:#eef2ff;color:#4338ca;">
                        <i class="ri-user-search-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="crm-card crm-stat">
                <div class="d-flex justify-content-between gap-3">
                    <div>
                        <div class="crm-stat-label">Audience Contacts</div>
                        <div class="crm-stat-value">{{ number_format($contactStats['total']) }}</div>
                        <div class="crm-stat-note">{{ number_format($contactStats['contactable']) }} contactable</div>
                    </div>
                    <div class="crm-stat-icon" style="background:#ecfdf5;color:#047857;">
                        <i class="ri-contacts-book-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="crm-card crm-stat">
                <div class="d-flex justify-content-between gap-3">
                    <div>
                        <div class="crm-stat-label">Active Campaigns</div>
                        <div class="crm-stat-value">{{ number_format($campaignStats['running'] + $campaignStats['scheduled']) }}</div>
                        <div class="crm-stat-note">{{ number_format($campaignStats['total']) }} total campaigns</div>
                    </div>
                    <div class="crm-stat-icon" style="background:#fff7ed;color:#c2410c;">
                        <i class="ri-broadcast-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="crm-card crm-stat">
                <div class="d-flex justify-content-between gap-3">
                    <div>
                        <div class="crm-stat-label">CRM Assets</div>
                        <div class="crm-stat-value">{{ number_format($assetStats['segments'] + $assetStats['templates']) }}</div>
                        <div class="crm-stat-note">{{ $assetStats['segments'] }} segments, {{ $assetStats['templates'] }} templates</div>
                    </div>
                    <div class="crm-stat-icon" style="background:#f0f9ff;color:#0369a1;">
                        <i class="ri-stack-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-7">
            <div class="crm-card h-100">
                <div class="crm-panel-header">
                    <div class="crm-panel-title">
                        <i class="ri-filter-3-line text-primary"></i>
                        Lead Pipeline
                    </div>
                    <a href="{{ route('superadmin.leads.index') }}" class="crm-mini-link">Open leads</a>
                </div>
                <div class="crm-panel-body">
                    @php
                        $pipelineRows = [
                            ['label' => 'New', 'value' => $leadStats['new'], 'color' => '#4f46e5'],
                            ['label' => 'Contacted', 'value' => $leadStats['contacted'], 'color' => '#f97316'],
                            ['label' => 'Converted', 'value' => $leadStats['converted'], 'color' => '#10b981'],
                        ];
                    @endphp

                    @foreach($pipelineRows as $row)
                        @php
                            $percentage = $leadStats['total'] > 0 ? round(($row['value'] / $leadStats['total']) * 100) : 0;
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="fw-semibold">{{ $row['label'] }}</div>
                                <div class="text-muted small">{{ number_format($row['value']) }} leads · {{ $percentage }}%</div>
                            </div>
                            <div class="crm-bar">
                                <div class="crm-bar-fill" style="width: {{ $percentage }}%; background: {{ $row['color'] }};"></div>
                            </div>
                        </div>
                    @endforeach

                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <div class="border rounded-2 p-3 h-100">
                                <div class="fw-semibold mb-3">Lead Sources</div>
                                @forelse($sourceBreakdown as $source)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted">{{ $source['label'] }}</span>
                                        <span class="fw-semibold">{{ number_format($source['total']) }}</span>
                                    </div>
                                @empty
                                    <div class="text-muted small">No source data</div>
                                @endforelse
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-2 p-3 h-100">
                                <div class="fw-semibold mb-3">Audience Channels</div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted"><i class="ri-mail-line me-1"></i>Email opt-ins</span>
                                    <span class="fw-semibold">{{ number_format($contactStats['email_opt_in']) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted"><i class="ri-whatsapp-line me-1"></i>WhatsApp opt-ins</span>
                                    <span class="fw-semibold">{{ number_format($contactStats['whatsapp_opt_in']) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted"><i class="ri-forbid-2-line me-1"></i>Do not contact</span>
                                    <span class="fw-semibold">{{ number_format($contactStats['do_not_contact']) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="crm-card h-100">
                <div class="crm-panel-header">
                    <div class="crm-panel-title">
                        <i class="ri-links-line text-primary"></i>
                        CRM Modules
                    </div>
                </div>
                <div class="crm-panel-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('superadmin.leads.index') }}" class="crm-module">
                            <span><i class="ri-user-search-line text-primary me-2"></i>Lead Management</span>
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                        <a href="{{ route('superadmin.marketing.contacts.index') }}" class="crm-module">
                            <span><i class="ri-contacts-book-line text-primary me-2"></i>Audience Contacts</span>
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                        <a href="{{ route('superadmin.marketing.segments.index') }}" class="crm-module">
                            <span><i class="ri-node-tree text-primary me-2"></i>Audience Segments</span>
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                        <a href="{{ route('superadmin.marketing.templates.index') }}" class="crm-module">
                            <span><i class="ri-file-list-3-line text-primary me-2"></i>Message Templates</span>
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                        <a href="{{ route('superadmin.marketing.campaigns.index') }}" class="crm-module">
                            <span><i class="ri-megaphone-line text-primary me-2"></i>Campaign Management</span>
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                    </div>

                    <div class="row g-2 mt-3">
                        <div class="col-6">
                            <div class="border rounded-2 p-3">
                                <div class="text-muted small">Recipients</div>
                                <div class="h5 fw-bold mb-0">{{ number_format($campaignStats['recipients']) }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded-2 p-3">
                                <div class="text-muted small">Failed Sends</div>
                                <div class="h5 fw-bold mb-0">{{ number_format($campaignStats['failed']) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 mt-1">
                        <div class="col-6">
                            <div class="border rounded-2 p-3">
                                <div class="text-muted small">Delivered</div>
                                <div class="h5 fw-bold mb-0">{{ number_format($campaignStats['delivered']) }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded-2 p-3">
                                <div class="text-muted small">Opened</div>
                                <div class="h5 fw-bold mb-0">{{ number_format($campaignStats['opened']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-4">
            <div class="crm-card h-100">
                <div class="crm-panel-header">
                    <div class="crm-panel-title">
                        <i class="ri-time-line text-primary"></i>
                        Recent Leads
                    </div>
                    <a href="{{ route('superadmin.leads.index') }}" class="crm-mini-link">View all</a>
                </div>
                <div class="crm-panel-body">
                    @forelse($recentLeads as $lead)
                        @php
                            $statusClass = in_array($lead->status, ['new', 'contacted', 'converted'], true)
                                ? 'crm-status-' . $lead->status
                                : 'crm-status-default';
                        @endphp
                        <div class="crm-list-row">
                            <div class="d-flex align-items-center gap-2">
                                <div class="crm-avatar">{{ strtoupper(substr($lead->name, 0, 1)) }}</div>
                                <div>
                                    <div class="fw-semibold">{{ $lead->name }}</div>
                                    <div class="text-muted small">{{ $lead->phone ?? $lead->email ?? 'No contact detail' }}</div>
                                </div>
                            </div>
                            <span class="crm-status {{ $statusClass }}">{{ ucfirst($lead->status) }}</span>
                        </div>
                    @empty
                        <div class="crm-empty">
                            <i class="ri-user-search-line"></i>
                            No recent leads
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="crm-card h-100">
                <div class="crm-panel-header">
                    <div class="crm-panel-title">
                        <i class="ri-group-line text-primary"></i>
                        Recent Contacts
                    </div>
                    <a href="{{ route('superadmin.marketing.contacts.index') }}" class="crm-mini-link">View all</a>
                </div>
                <div class="crm-panel-body">
                    @forelse($recentContacts as $contact)
                        <div class="crm-list-row">
                            <div class="d-flex align-items-center gap-2">
                                <div class="crm-avatar">{{ strtoupper(substr($contact->name, 0, 1)) }}</div>
                                <div>
                                    <div class="fw-semibold">{{ $contact->name }}</div>
                                    <div class="text-muted small">{{ $contact->specialty ?? $contact->city ?? 'No segment detail' }}</div>
                                </div>
                            </div>
                            <span class="text-muted small">{{ $contact->source ?? 'Manual' }}</span>
                        </div>
                    @empty
                        <div class="crm-empty">
                            <i class="ri-contacts-book-line"></i>
                            No recent contacts
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="crm-card h-100">
                <div class="crm-panel-header">
                    <div class="crm-panel-title">
                        <i class="ri-megaphone-line text-primary"></i>
                        Recent Campaigns
                    </div>
                    <a href="{{ route('superadmin.marketing.campaigns.index') }}" class="crm-mini-link">View all</a>
                </div>
                <div class="crm-panel-body">
                    @forelse($recentCampaigns as $campaign)
                        <div class="crm-list-row">
                            <div>
                                <div class="fw-semibold">{{ $campaign->name }}</div>
                                <div class="text-muted small text-capitalize">
                                    {{ $campaign->channel ?? 'Channel' }} · {{ $campaign->status ?? 'draft' }}
                                </div>
                            </div>
                            <span class="text-muted small">
                                {{ number_format(data_get($campaign->totals_json, 'total', 0)) }} recipients
                            </span>
                        </div>
                    @empty
                        <div class="crm-empty">
                            <i class="ri-broadcast-line"></i>
                            No recent campaigns
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-6">
            <div class="crm-card">
                <div class="crm-panel-header">
                    <div class="crm-panel-title">
                        <i class="ri-stethoscope-line text-primary"></i>
                        Top Specialties
                    </div>
                </div>
                <div class="crm-panel-body">
                    @forelse($topSpecialties as $specialty)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">{{ $specialty['label'] }}</span>
                            <span class="fw-semibold">{{ number_format($specialty['total']) }}</span>
                        </div>
                    @empty
                        <div class="text-muted small">No specialty data</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="crm-card">
                <div class="crm-panel-header">
                    <div class="crm-panel-title">
                        <i class="ri-map-pin-line text-primary"></i>
                        Top Cities
                    </div>
                </div>
                <div class="crm-panel-body">
                    @forelse($topCities as $city)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">{{ $city['label'] }}</span>
                            <span class="fw-semibold">{{ number_format($city['total']) }}</span>
                        </div>
                    @empty
                        <div class="text-muted small">No city data</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
