@extends('layouts.admin')
@section('title','Appointment Confirmation')

@section('content')
<div class="container py-4">

    <div class="row justify-content-center">
        <div class="col-lg-6">

            {{-- Success Card --}}
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                {{-- Header --}}
                <div class="bg-success text-white text-center py-4">
                    <i class="ri-check-double-line" style="font-size:3.5rem;"></i>
                    <h4 class="mt-2 mb-1 fw-bold">Appointment Confirmed</h4>
                    <p class="mb-0 opacity-75">
                        Thank you! Your appointment has been booked successfully.
                    </p>
                </div>

                {{-- Body --}}
                <div class="card-body p-4">

                    {{-- Appointment Number --}}
                    <div class="text-center mb-4">
                        <span class="badge bg-primary fs-6 px-3 py-2">
                            Appointment No: {{ $appointment->appointment_number }}
                        </span>
                    </div>

                    {{-- Details --}}
                    <div class="border rounded-3 p-3 bg-light">

                        <div class="row mb-2">
                            <div class="col-5 text-muted">Date</div>
                            <div class="col-7 fw-semibold">
                                {{ optional($appointment->appointment_date)->format('d M Y') }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-5 text-muted">Time</div>
                            <div class="col-7 fw-semibold">
                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-5 text-muted">Chamber</div>
                            <div class="col-7 fw-semibold">
                                {{ $appointment->chamber->name ?? 'Online Consultation' }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-5 text-muted">Consultation</div>
                            <div class="col-7 fw-semibold text-capitalize">
                                {{ $appointment->consultation_type }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-5 text-muted">Service</div>
                            <div class="col-7 fw-semibold">
                                {{ ucfirst($appointment->service_type) }}
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-5 text-muted">Fees</div>
                            <div class="col-7 fw-bold text-success">
                                {{ number_format($appointment->amount, 2) }} {{ $appointment->currency }}
                            </div>
                        </div>

                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('admin.dashboard') }}"
                           class="btn btn-outline-secondary w-100">
                            <i class="ri-home-4-line me-1"></i> Dashboard
                        </a>

                        <button onclick="window.print()"
                                class="btn btn-primary w-100">
                            <i class="ri-printer-line me-1"></i> Print
                        </button>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="text-center py-3 small text-muted bg-white">
                    Please arrive 10 minutes before your scheduled time.
                </div>

            </div>

        </div>
    </div>

</div>

{{-- Print Styles --}}
<style>
@media print {
    body * {
        visibility: hidden;
    }
    .card, .card * {
        visibility: visible;
    }
    .card {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none !important;
    }
    button, a {
        display: none !important;
    }
}
</style>
@endsection
