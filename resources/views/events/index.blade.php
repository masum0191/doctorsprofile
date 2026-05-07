@extends('layouts.admin')
@section('title','Events')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-semibold mb-1">Events</h4>
        <p class="text-muted mb-0">Manage events, galleries & status</p>
    </div>
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
        <i class="ri-calendar-event-line me-1"></i> Create Event
    </a>
</div>

{{-- FILTER --}}
<form method="GET" class="card mb-4 shadow-sm border-0">
    <div class="card-body row g-3 align-items-end">

        <div class="col-md-4">
            <label class="form-label">Event Title</label>
            <input type="text" name="title"
                   value="{{ request('title') }}"
                   class="form-control"
                   placeholder="Search title">
        </div>

        <div class="col-md-2">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All</option>
                <option value="1" @selected(request('status')==='1')>Active</option>
                <option value="0" @selected(request('status')==='0')>Inactive</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">From Date</label>
            <input type="date" name="from_date"
                   value="{{ request('from_date') }}"
                   class="form-control">
        </div>

        <div class="col-md-2">
            <label class="form-label">To Date</label>
            <input type="date" name="to_date"
                   value="{{ request('to_date') }}"
                   class="form-control">
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button class="btn btn-primary w-100">
                <i class="ri-filter-3-line"></i>
            </button>
            <a href="{{ route('admin.events.index') }}"
               class="btn btn-light w-100">
                Reset
            </a>
        </div>

    </div>
</form>

{{-- TABLE --}}
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Event</th>
                    <th>Gallery</th>
                    <th>Publish Date</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($events as $event)
                <tr>
                    <td>
                        <strong>{{ $event->title }}</strong><br>
                        <small class="text-muted">{{ $event->venue }}</small>
                    </td>

                    <td>
                        @if($event->image_gallery)
                            <span class="badge bg-info">
                                {{ count($event->image_gallery) }} images
                            </span>
                        @else
                            —
                        @endif
                    </td>

<td>{{ $event->publish_date?->format('d M Y') ?? '-' }}</td>

                    <td>
                        <span class="badge {{ $event->status ? 'bg-success' : 'bg-secondary' }}">
                            {{ $event->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>

                    <td class="text-end">
                        <a href="{{ route('admin.events.edit',$event) }}"
                           class="btn btn-sm btn-outline-warning">
                            <i class="ri-edit-line"></i>
                        </a>

                        <form method="POST"
                              action="{{ route('admin.events.destroy',$event) }}"
                              class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Delete event?')">
                                <i class="ri-delete-bin-6-line"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        No events found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $events->links() }}
</div>

@endsection
