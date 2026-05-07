@extends('layouts.supperadmin')
@section('title','Edit Campaign')

@section('content')
<div class="container-fluid px-4">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between py-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Edit Campaign</h1>
            <p class="text-muted mb-0">
                Update campaign details, schedule, and content.
            </p>
        </div>
        <a href="{{ route('superadmin.marketing.campaigns.index') }}"
           class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i>
            Back to Campaigns
        </a>
    </div>

    <form action="{{ route('superadmin.marketing.campaigns.update', $campaign->id) }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm">

            {{-- Card Header --}}
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-semibold">
                    <i class="ri-edit-box-line text-primary me-1"></i>
                    Campaign Information
                </h6>
            </div>

            {{-- Card Body --}}
            <div class="card-body">
                <div class="row g-4">

                    {{-- Campaign Name --}}
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">
                            Campaign Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name', $campaign->name) }}"
                               required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Campaign Status</label>
                        <select name="status" class="form-select">
                            <option value="draft" {{ old('status', $campaign->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="scheduled" {{ old('status', $campaign->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="sent" {{ old('status', $campaign->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                        </select>
                        @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Subject --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email Subject</label>
                        <input type="text"
                               name="subject"
                               class="form-control"
                               value="{{ old('subject', $campaign->subject) }}"
                               placeholder="Only for email campaigns">
                        @error('subject') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- From Email --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">From Email</label>
                        <input type="email"
                               name="from_email"
                               class="form-control"
                               value="{{ old('from_email', $campaign->from_email) }}">
                        @error('from_email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Template --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Message Template</label>
                        <select name="template_id" class="form-select">
                            <option value="">No Template</option>
                            @foreach($templates ?? [] as $template)
                                <option value="{{ $template->id }}"
                                    {{ old('template_id', $campaign->template_id) == $template->id ? 'selected' : '' }}>
                                    {{ $template->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('template_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Segment --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Recipient Segment</label>
                        <select name="segment_id" class="form-select">
                            <option value="">All Eligible Contacts</option>
                            @foreach($segments ?? [] as $segment)
                                <option value="{{ $segment->id }}"
                                    {{ old('segment_id', $campaign->segment_id) == $segment->id ? 'selected' : '' }}>
                                    {{ $segment->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('segment_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Schedule --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Schedule Send Time</label>
                        <input type="datetime-local"
                               name="scheduled_at"
                               class="form-control"
                               value="{{ old('scheduled_at', optional($campaign->scheduled_at)->format('Y-m-d\TH:i')) }}">
                        <small class="text-muted">Leave empty to send immediately</small>
                        @error('scheduled_at') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Content --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Campaign Content</label>
                        <textarea name="content"
                                  rows="8"
                                  class="form-control"
                                  placeholder="Supports variables like {name}, {city}, {specialty}">
                            {{ old('content', $campaign->content) }}
                        </textarea>
                        @error('content') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Image --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Header Image (Optional)</label>
                        <input type="file" name="image" class="form-control">
                        @if($campaign->image)
                            <div class="mt-2">
                                <img src="{{ asset($campaign->image) }}"
                                     class="rounded border"
                                     style="max-height:120px;">
                            </div>
                        @endif
                        @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="card-footer bg-white border-top d-flex justify-content-end gap-2">
                <a href="{{ route('superadmin.marketing.campaigns.index') }}"
                   class="btn btn-light">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="ri-save-3-line me-1"></i>
                    Update Campaign
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
