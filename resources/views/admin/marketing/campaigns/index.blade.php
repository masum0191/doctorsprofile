@extends('layouts.supperadmin')

@section('title', 'Campaigns')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Campaigns</h1>
        <div>
            <a href="{{ route('superadmin.marketing.campaigns.create') }}" class="btn btn-primary">New Campaign</a>
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
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>Paused</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">Any type</option>
                    <option value="email" {{ request('type') == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="social" {{ request('type') == 'social' ? 'selected' : '' }}>Social</option>
                    <option value="ads" {{ request('type') == 'ads' ? 'selected' : '' }}>Ads</option>
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
                    <th style="width: 120px">Type</th>
                    <th style="width: 110px">Status</th>
                    <th style="width: 140px">Start - End</th>
                    <th style="width: 120px" class="text-end">Budget</th>
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
                            @if($campaign->notes)
                                <div class="text-muted small">{{ Str::limit($campaign->notes, 80) }}</div>
                            @endif
                        </td>
                        <td class="text-capitalize">{{ $campaign->type ?? '-' }}</td>
                        <td>
                            @switch($campaign->status)
                                @case('active')
                                    <span class="badge bg-success">Active</span>
                                    @break
                                @case('paused')
                                    <span class="badge bg-warning text-dark">Paused</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-secondary">Completed</span>
                                    @break
                                @default
                                    <span class="badge bg-light text-dark">Draft</span>
                            @endswitch
                        </td>
                        <td>
                            <div class="small">
                                {{ optional($campaign->start_at)->format('Y-m-d') ?? '-' }}
                                &ndash;
                                {{ optional($campaign->end_at)->format('Y-m-d') ?? '-' }}
                            </div>
                        </td>
                        <td class="text-end">
                            @if(!is_null($campaign->budget))
                                {{ number_format($campaign->budget, 2) }}
                            @else
                                -
                            @endif
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