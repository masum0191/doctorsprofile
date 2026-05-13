@extends('layouts.supperadmin')

@section('title', 'Campaign Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Campaign Management</h1>
            <p class="text-muted mb-0">Plan, schedule, and monitor outbound marketing campaigns.</p>
        </div>
        <div>
            <a href="{{ route('superadmin.marketing.campaigns.create') }}" class="btn btn-primary">New Campaign</a>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Total Campaigns</div>
                    <div class="h3 mb-0">{{ $campaignStats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Drafts</div>
                    <div class="h3 mb-0">{{ $campaignStats['draft'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Scheduled</div>
                    <div class="h3 mb-0">{{ $campaignStats['scheduled'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Running</div>
                    <div class="h3 mb-0">{{ $campaignStats['running'] }}</div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="GET" class="mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search by name or id">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Any status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="running" {{ request('status') == 'running' ? 'selected' : '' }}>Running</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="channel" class="form-select">
                    <option value="">Any channel</option>
                    <option value="email" {{ request('channel') == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="whatsapp" {{ request('channel') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-secondary" type="submit">Filter</button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px">#</th>
                    <th>Name</th>
                    <th style="width: 120px">Channel</th>
                    <th style="width: 110px">Status</th>
                    <th style="width: 150px">Schedule</th>
                    <th style="width: 150px" class="text-end">Recipients</th>
                    <th style="width: 160px" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $campaign)
                    <tr>
                        <td>{{ $campaign->id }}</td>
                        <td>
                            <a href="{{ route('superadmin.marketing.campaigns.show', $campaign) }}">
                                {{ $campaign->name }}
                            </a>
                            @if($campaign->subject)
                                <div class="text-muted small">{{ Str::limit($campaign->subject, 80) }}</div>
                            @endif
                        </td>
                        <td class="text-capitalize">{{ $campaign->channel ?? '-' }}</td>
                        <td>
                            @switch($campaign->status)
                                @case('running')
                                    <span class="badge bg-success">Running</span>
                                    @break
                                @case('scheduled')
                                    <span class="badge bg-warning text-dark">Scheduled</span>
                                    @break
                                @case('sent')
                                    <span class="badge bg-secondary">Sent</span>
                                    @break
                                @default
                                    <span class="badge bg-light text-dark">Draft</span>
                            @endswitch
                        </td>
                        <td>
                            <div class="small">
                                @if($campaign->scheduled_at)
                                    Scheduled {{ $campaign->scheduled_at->format('Y-m-d H:i') }}
                                @elseif($campaign->started_at)
                                    Started {{ $campaign->started_at->format('Y-m-d H:i') }}
                                @else
                                    Not scheduled
                                @endif
                            </div>
                        </td>
                        <td class="text-end">
                            {{ data_get($campaign->totals_json, 'total', 0) }}
                        </td>
                        <td class="text-end">
                            <a href="{{ route('superadmin.marketing.campaigns.edit', $campaign) }}" class="btn btn-sm btn-outline-primary">Edit</a>

                            <form action="{{ route('superadmin.marketing.campaigns.destroy', $campaign) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this campaign? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            No campaigns found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <div class="text-muted">
            Showing {{ $campaigns->firstItem() ?? 0 }} to {{ $campaigns->lastItem() ?? 0 }} of {{ $campaigns->total() ?? 0 }}
        </div>
        <div>
            {{ $campaigns->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
