@extends('layouts.supperadmin')
@section('title','Campaign Details')

@section('content')
<div class="container-fluid px-4">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between py-4 gap-3">
        <div>
            <h1 class="h4 fw-bold mb-1">{{ $campaign->name }}</h1>
            <div class="text-muted small">
                Channel:
                <span class="badge bg-light text-primary border">
                    {{ ucfirst($campaign->channel) }}
                </span>
                Status:
                <span class="badge
                    @if($campaign->status === 'sent') bg-success
                    @elseif($campaign->status === 'scheduled') bg-warning text-dark
                    @else bg-secondary @endif">
                    {{ ucfirst($campaign->status) }}
                </span>
            </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex gap-2">
            <form method="POST" action="{{ route('superadmin.marketing.campaigns.build',$campaign) }}">
                @csrf
                <button class="btn btn-outline-primary">
                    <i class="ri-group-line me-1"></i>
                    Build Recipients
                </button>
            </form>

            <form method="POST" action="{{ route('superadmin.marketing.campaigns.start',$campaign) }}">
                @csrf
                <button class="btn btn-primary">
                    <i class="ri-play-circle-line me-1"></i>
                    Start Campaign
                </button>
            </form>
        </div>
    </div>

    {{-- Recipients Card --}}
    <div class="card border-0 shadow-sm">

        {{-- Card Header --}}
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-semibold">
                <i class="ri-user-received-line text-primary me-1"></i>
                Campaign Recipients
            </h6>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light small text-uppercase">
                    <tr>
                        <th>Contact</th>
                        <th>Channel</th>
                        <th>Status</th>
                        <th>Sent At</th>
                        <th>Error</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($recipients as $r)
                    <tr>
                        <td>
                            <div class="fw-semibold">
                                {{ $r->contact?->name ?? '—' }}
                            </div>
                            <div class="small text-muted">
                                {{ $r->contact?->email ?? $r->contact?->whatsapp ?? '—' }}
                            </div>
                        </td>

                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ ucfirst($campaign->channel) }}
                            </span>
                        </td>

                        <td>
                            <span class="badge
                                @if($r->status === 'sent') bg-success
                                @elseif($r->status === 'failed') bg-danger
                                @elseif($r->status === 'processing') bg-warning text-dark
                                @else bg-secondary @endif">
                                {{ ucfirst($r->status) }}
                            </span>
                        </td>

                        <td class="small text-muted">
                            {{ $r->sent_at?->format('Y-m-d H:i') ?? '—' }}
                        </td>

                        <td class="small text-danger">
                            {{ $r->error_message ?? '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="ri-inbox-line fs-3 d-block mb-2"></i>
                            No recipients found.<br>
                            Click <b>“Build Recipients”</b> to generate audience.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="card-footer bg-white border-top d-flex justify-content-end">
            {{ $recipients->links() }}
        </div>
    </div>
</div>
@endsection
