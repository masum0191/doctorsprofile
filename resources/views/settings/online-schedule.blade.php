@extends('layouts.admin')
@section('title','Online Schedule Settings')

@section('content')
<form method="POST" action="{{ route('admin.settings.online-schedule.update') }}">
@csrf

<div class="card shadow-sm border-0">
    <div class="card-header quick-action-btn text-white">
        <h5 class="mb-0">Online Consultation Schedule</h5>
    </div>

    <div class="card-body">

        {{-- ENABLE --}}
        <div class="form-check form-switch mb-4">
            <input class="form-check-input" type="checkbox" name="enabled" value="1"
                {{ data_get($settings,'enabled') ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold">
                Enable Online Booking
            </label>
        </div>

        {{-- BASIC CONFIG --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Timezone</label>
                <input type="text" name="timezone" class="form-control"
                       value="{{ data_get($settings,'timezone','Asia/Dhaka') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Slot Duration (minutes)</label>
                <input type="number" name="slot_duration" class="form-control"
                       value="{{ data_get($settings,'slot_duration',30) }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Buffer Minutes</label>
                <input type="number" name="buffer_minutes" class="form-control"
                       value="{{ data_get($settings,'buffer_minutes',10) }}">
            </div>
        </div>

        {{-- DAYS --}}
        <h6 class="fw-bold mb-3">Weekly Availability</h6>

        @php
            $days = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
            $schedule = data_get($settings,'working_days',[]);
        @endphp

        @foreach($days as $day)
        @php
            $dayData = $schedule[$day] ?? ['enabled'=>false,'slots'=>[]];
        @endphp

        <div class="border rounded p-3 mb-3">
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox"
                       name="working_days[{{ $day }}][enabled]"
                       {{ data_get($dayData,'enabled') ? 'checked' : '' }}>
                <label class="form-check-label text-capitalize fw-semibold">
                    {{ $day }}
                </label>
            </div>

            {{-- TIME SLOTS --}}
            <div class="row g-2">
                @foreach(data_get($dayData,'slots',[]) as $i => $slot)
                    <div class="col-md-3">
                        <input type="time"
                               name="working_days[{{ $day }}][slots][{{ $i }}][from]"
                               value="{{ data_get($slot,'from') }}"
                               class="form-control">
                    </div>
                    <div class="col-md-3">
                        <input type="time"
                               name="working_days[{{ $day }}][slots][{{ $i }}][to]"
                               value="{{ data_get($slot,'to') }}"
                               class="form-control">
                    </div>
                @endforeach

                {{-- NEW SLOT --}}
                <div class="col-md-3">
                    <input type="time"
                           name="working_days[{{ $day }}][slots][][from]"
                           class="form-control">
                </div>
                <div class="col-md-3">
                    <input type="time"
                           name="working_days[{{ $day }}][slots][][to]"
                           class="form-control">
                </div>
            </div>
        </div>
        @endforeach

        {{-- MEETING PROVIDER --}}
        <div class="row g-3 mt-4">
            <div class="col-md-6">
                <label class="form-label">Meeting Provider</label>
                <select name="meeting_provider" class="form-select">
                    <option value="zoom" {{ data_get($settings,'meeting_provider')=='zoom'?'selected':'' }}>Zoom</option>
                    <option value="google_meet" {{ data_get($settings,'meeting_provider')=='google_meet'?'selected':'' }}>Google Meet</option>
                </select>
            </div>

            <div class="col-md-6 d-flex align-items-center">
                <div class="form-check mt-4">
                    <input class="form-check-input" type="checkbox"
                           name="auto_generate_meeting" value="1"
                           {{ data_get($settings,'auto_generate_meeting') ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold">
                        Auto-generate Meeting Link
                    </label>
                </div>
            </div>
        </div>

    </div>

    {{-- FOOTER --}}
    <div class="card-footer text-end">
        <button class="btn btn-primary px-4">
            <i class="ri-save-3-line me-1"></i> Save Schedule
        </button>
    </div>
</div>
</form>
@endsection
