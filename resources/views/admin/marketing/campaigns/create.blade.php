@extends('layouts.supperadmin')
@section('title','Create Campaign')

@section('content')
<div class="container-fluid px-4">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between py-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Create Campaign</h1>
            <p class="text-muted mb-0">
                Send targeted Email or WhatsApp messages to your audience.
            </p>
        </div>
        <span class="badge bg-light text-primary border">
            Marketing Module
        </span>
    </div>

    {{-- Card --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-semibold">
                <i class="ri-megaphone-line me-1 text-primary"></i>
                Campaign Details
            </h6>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('superadmin.marketing.campaigns.store') }}">
                @csrf

                <div class="row g-4">

                    {{-- Channel --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Channel <span class="text-danger">*</span></label>
                        <select name="channel" class="form-select" required>
                            <option value="email">📧 Email</option>
                            <option value="whatsapp">💬 WhatsApp</option>
                        </select>
                        <small class="text-muted">Choose communication medium</small>
                    </div>

                    {{-- Campaign Name --}}
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Campaign Name <span class="text-danger">*</span></label>
                        <input name="name" class="form-control" placeholder="e.g. Doctor Awareness Campaign" required>
                    </div>

                    {{-- Segment --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Target Segment</label>
                        <select name="segment_id" class="form-select">
                            <option value="">All Eligible Contacts</option>
                            @foreach($segments as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Audience group selection</small>
                    </div>

                    {{-- Template --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Message Template</label>
                        <select name="template_id" class="form-select">
                            <option value="">No Template</option>
                            @foreach($templates as $t)
                                <option value="{{ $t->id }}">
                                    [{{ ucfirst($t->channel) }}] {{ $t->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Optional predefined content</small>
                    </div>

                    {{-- Subject --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email Subject</label>
                        <input name="subject" class="form-control"
                               placeholder="Only applicable for Email campaigns">
                    </div>

                    {{-- Schedule --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Schedule Campaign</label>
                        <input type="datetime-local" name="scheduled_at" class="form-control">
                        <small class="text-muted">Leave empty to send immediately</small>
                    </div>

                    {{-- Body --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Message Body
                        </label>
                        <textarea name="body" rows="6" class="form-control"
                                  placeholder="Supports dynamic variables: {name}, {city}, {specialty}"></textarea>
                        <small class="text-muted">
                            This will override the selected template (if any)
                        </small>
                    </div>

                    {{-- Action --}}
                    <div class="col-12 d-flex justify-content-end pt-3 border-top">
                        <button class="btn btn-primary px-4">
                            <i class="ri-send-plane-fill me-1"></i>
                            Create Campaign
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection
