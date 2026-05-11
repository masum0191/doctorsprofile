@extends('layouts.forntend')
@section('title', $doctor->name . '- ' . ($setting->site_name ?? 'Medical Practice'))
@section('content')
<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 20px;
}

.booking-modal {
    background: white;
    border-radius: 12px;
    width: 95%;
    max-width: 800px;
    max-height: 90vh;
    overflow: auto;
    display: flex;
    flex-direction: column;
}

.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
}

.close-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #64748b;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.close-btn:hover {
    color: #374151;
}

.progress-bar {
    display: flex;
    padding: 20px 24px;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
}

.step {
    display: flex;
    align-items: center;
    flex: 1;
    opacity: 0.5;
}

.step.active {
    opacity: 1;
}

.step-number {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 600;
    margin-right: 8px;
}

.step.active .step-number {
    background: #3b82f6;
    color: white;
}

.step-title {
    font-size: 14px;
    font-weight: 500;
    color: #374151;
}

.form-content {
    flex: 1;
    overflow-y: auto;
    padding: 24px;
}

.form-step {
    display: none;
}

.form-step.active {
    display: block;
}

.service-selection {
    display: grid;
    gap: 12px;
}

.service-option {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px;
    cursor: pointer;
    transition: all 0.2s;
}

.service-option:hover {
    border-color: #3b82f6;
}

.service-option input:checked + div {
    border-color: #3b82f6;
    background: #f0f9ff;
}

.service-option h4 {
    margin: 0 0 4px 0;
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
}

.service-option p {
    margin: 0 0 8px 0;
    font-size: 14px;
    color: #64748b;
}

.service-option small {
    font-size: 12px;
}

.chambers-mini {
    display: grid;
    gap: 12px;
}

.mini-card {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 0;
    cursor: pointer;
    transition: all 0.2s;
}

.mini-card:hover {
    border-color: #3b82f6;
}

.mini-card input:checked + .mini-body {
    border-color: #3b82f6;
    background: #f0f9ff;
}

.mini-body {
    padding: 16px;
}

.mini-body h4 {
    margin: 0 0 4px 0;
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
}

.mini-body p {
    margin: 0 0 8px 0;
    font-size: 14px;
    color: #64748b;
}

.chamber-details {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
}

.fees {
    color: #059669;
    font-weight: 600;
}

.type {
    color: #6b7280;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group[style*="grid-column:span 2"] {
    grid-column: span 2;
}

.form-label {
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 4px;
}

.form-input {
    padding: 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-check {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    font-size: 14px;
    color: #374151;
}

.form-check input {
    margin-top: 2px;
}

.confirmation-summary {
    background: #f8fafc;
    border-radius: 8px;
    padding: 20px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #e2e8f0;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-item .label {
    font-weight: 500;
    color: #64748b;
}

.summary-item .value {
    font-weight: 600;
    color: #1f2937;
}

.modal-footer {
    padding: 20px 24px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.btn {
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover:not(:disabled) {
    background: #4b5563;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: #2563eb;
}

.btn-success {
    background: #059669;
    color: white;
}

.btn-success:hover:not(:disabled) {
    background: #047857;
}

.alert {
    padding: 12px 16px;
    border-radius: 6px;
    background: #fef3c7;
    border: 1px solid #f59e0b;
    color: #92400e;
}

.alert-warning {
    background: #fef3c7;
    border-color: #f59e0b;
    color: #92400e;
}

.muted {
    text-align: center;
    padding: 40px 20px;
    color: #64748b;
}

.muted i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.muted p {
    margin: 0;
    font-size: 14px;
}

/* Date Time Picker Styles */
.date-time-picker {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 20px;
}

.calendar-section {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 15px;
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.calendar-nav {
    background: none;
    border: none;
    font-size: 16px;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
}

.calendar-nav:hover {
    background: #f1f5f9;
}

.month-year {
    font-weight: 600;
    color: #374151;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
}

.day-name {
    text-align: center;
    font-weight: 600;
    color: #64748b;
    padding: 8px;
    font-size: 12px;
}

.calendar-day {
    text-align: center;
    padding: 8px;
    border-radius: 6px;
    cursor: pointer;
    border: 1px solid transparent;
    font-size: 14px;
}

.calendar-day:hover:not(.disabled):not(.selected) {
    background: #f1f5f9;
}

.calendar-day.selected {
    background: #3b82f6;
    color: white;
}

.calendar-day.disabled {
    color: #cbd5e1;
    cursor: not-allowed;
}

.calendar-day.has-slots {
    border-color: #10b981;
}

.time-slots-container {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 20px;
    max-height: 300px;
    overflow-y: auto;
}

.time-slots {
    display: grid;
    gap: 8px;
}

.time-slot {
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    cursor: pointer;
    text-align: center;
    font-size: 14px;
    transition: all 0.2s;
}

.time-slot:hover {
    background: #f1f5f9;
}

.time-slot.selected {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.time-slot.disabled {
    background: #f8fafc;
    color: #cbd5e1;
    cursor: not-allowed;
}

.form-error {
    color: #dc2626;
    font-size: 14px;
    margin-top: 8px;
    display: none;
}

.form-error.show {
    display: block;
}

.success-message {
    text-align: center;
    padding: 40px 20px;
}

.success-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
}

.appointment-details {
    background: #f8fafc;
    padding: 15px;
    border-radius: 8px;
    margin: 20px 0;
    text-align: left;
}

.terms-agreement {
    background: #f8fafc;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #3b82f6;
}

@media (max-width: 768px) {
    .modal-overlay {
        padding: 10px;
    }

    .booking-modal {
        width: 100%;
        height: 100vh;
        max-height: 100vh;
        border-radius: 0;
    }

    .date-time-picker {
        grid-template-columns: 1fr;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group[style*="grid-column:span 2"] {
        grid-column: span 1;
    }

    .progress-bar {
        padding: 16px;
        overflow-x: auto;
    }

    .step-title {
        font-size: 12px;
    }
}
</style>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center bg-gradient-to-br from-cyan-50 via-white to-teal-50">
    <div class="absolute inset-0 z-0 opacity-15 bg-cover bg-center"
        style="background-image: url('{{ @$doctor->profile->hero_image ? url($doctor->profile->hero_image) : 'https://img.freepik.com/free-photo/blur-hospital_1203-7972.jpg' }}')">
    </div>
    <div class="container mx-auto px-6 lg:px-12 py-20 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-8">
                <div class="inline-block px-4 py-2 bg-cyan-100 text-cyan-700 rounded-full text-sm font-medium">
                    <i class="ri-stethoscope-line mr-2"></i>{{ $doctor->profile->tagline ?? 'Professional Medical Care' }}
                </div>
                <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight custom-font">
                    {{ $doctor->profile->headline ?? 'Your Health, Our Priority' }}
                </h1>
                <p class="text-lg text-gray-600 leading-relaxed">{{ $doctor->profile->about_short ?? 'Comprehensive healthcare services with years of experience.' }}</p>
                @if($canBookAppointments)
                <div class="flex flex-wrap gap-4">
                    <button class="px-6 py-3 bg-cyan-600 text-white rounded-lg font-semibold hover:bg-cyan-700 transition-all shadow-lg hover:shadow-xl whitespace-nowrap cursor-pointer launch-btn" type="button" data-open>
    <i class="ri-calendar-check-line mr-2"></i>Book Appointment
</button>
                </div>
                @endif
                <div class="grid grid-cols-3 gap-6 pt-8 border-t border-gray-200">
                    <div>
                        <div class="text-3xl font-bold text-cyan-600">{{ $doctor->profile->years_experience ?? '15' }}+</div>
                        <div class="text-sm text-gray-600 mt-1">Years Experience</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-cyan-600">{{ $doctor->profile->patients_count ?? '5000' }}+</div>
                        <div class="text-sm text-gray-600 mt-1">Happy Patients</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-cyan-600">{{ $doctor->profile->satisfaction_rate ?? '98' }}%</div>
                        <div class="text-sm text-gray-600 mt-1">Satisfaction Rate</div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                    <img alt="{{ $doctor->name }}" class="w-full h-auto object-cover object-top"
                        src="{{ $doctor->photo ? url($doctor->photo) : 'https://img.freepik.com/free-photo/female-doctor-hospital_23-2148827760.jpg' }}">
                </div>
                <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-xl shadow-xl">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="ri-shield-check-fill text-2xl text-green-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Board Certified</div>
                            <div class="text-sm text-gray-600">Licensed Physician</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- MODAL --}}
<div class="modal-overlay" data-overlay style="display: none;">
    <div class="booking-modal">
        <header class="modal-header">
            <h2>Book an Appointment with Dr. {{ $doctor->name }}</h2>
            <button class="close-btn" type="button" data-close>&times;</button>
        </header>

        <div class="progress-bar" data-steps>
            <div class="step active" data-step-indicator="1">
                <div class="step-number">1</div>
                <div class="step-title">Consultation</div>
            </div>
            <div class="step" data-step-indicator="2">
                <div class="step-number">2</div>
                <div class="step-title">Chamber</div>
            </div>
            <div class="step" data-step-indicator="3">
                <div class="step-number">3</div>
                <div class="step-title">Service</div>
            </div>
            <div class="step" data-step-indicator="4">
                <div class="step-number">4</div>
                <div class="step-title">Date & Time</div>
            </div>
            <div class="step" data-step-indicator="5">
                <div class="step-number">5</div>
                <div class="step-title">Your Details</div>
            </div>
            <div class="step" data-step-indicator="6">
                <div class="step-number">6</div>
                <div class="step-title">Confirm</div>
            </div>
        </div>

        <form id="appointment-form" data-form method="POST" action="{{ route('appointments.store') }}">
            @csrf
            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

            <div class="form-content">
                {{-- STEP 1: Consultation Type --}}
                <div class="form-step active" data-pane="1">
                    <h4>Consultation Type</h4>
                    <div class="service-selection" style="margin-top:20px">
                        <label class="service-option">
                            <input type="radio" name="consultation_type" value="online" required>
                            <div>
                                <h4>Online</h4>
                                <p>Video visit</p>
                                <small class="text-success" data-online-status>
                                    @if($doctor->accepts_virtual_visits)
                                        <i class="ri-check-line"></i> Available
                                    @else
                                        <i class="ri-close-line"></i> Not Available
                                    @endif
                                </small>
                            </div>
                        </label>
                        <label class="service-option">
                            <input type="radio" name="consultation_type" value="offline" required>
                            <div>
                                <h4>Offline (In person)</h4>
                                <p>Visit at chamber</p>
                                <small class="text-success"><i class="ri-check-line"></i> Available</small>
                            </div>
                        </label>
                    </div>
                    <div class="form-error" data-error="consultation_type"></div>
                </div>

                {{-- STEP 2: Chamber Selection --}}
                <div class="form-step" data-pane="2">
                    <h4>Select a Chamber</h4>
                    <div class="chambers-mini" style="margin-top:12px" data-chambers-container>
                        @foreach ($chambers->where('is_active', true) as $chamber)
                            <label class="mini-card chamber-card" data-chamber-id="{{ $chamber->id }}">
                                <input type="radio" name="chamber_id" value="{{ $chamber->id }}" required
           data-fees="{{ $chamber->fees }}"
           data-availability-url="{{ route('chambers.slots', ['chamber' => $chamber->id, 'date' => '__DATE__']) }}">
                                <div class="mini-body">
                                    <h4>{{ $chamber->name }}</h4>
                                    <p>{{ $chamber->address }}, {{ $chamber->city }}</p>
                                    <div class="chamber-details">
                                        <small class="fees">Fees: ৳{{ number_format($chamber->fees) }}</small>
                                        <small class="type">{{ ucfirst($chamber->type) }} Schedule</small>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <div class="form-error" data-error="chamber_id"></div>

                    @if($chambers->where('is_active', true)->isEmpty())
                        <div class="alert alert-warning">
                            <i class="ri-information-line"></i> No active chambers available for booking.
                        </div>
                    @endif
                </div>

                {{-- STEP 3: Service Type --}}
                <div class="form-step" data-pane="3">
                    <h4>What is the reason for your visit?</h4>
                    <div class="service-selection" style="margin-top:20px">
                        @foreach([
                            'New Patient Visit' => 'First consultation',
                            'Annual Physical' => 'Yearly check-up',
                            'Follow-up Visit' => 'Ongoing care',
                            'Acute Illness' => 'Cold/flu, etc.',
                            'Chronic Condition' => 'Diabetes, hypertension, etc.',
                            'Medication Review' => 'Prescription refill',
                            'Vaccination' => 'Immunization',
                            'Other' => 'Specify in notes'
                        ] as $service => $description)
                        <label class="service-option">
                            <input type="radio" name="service_type" value="{{ $service }}" required>
                            <div>
                                <h4>{{ $service }}</h4>
                                <p>{{ $description }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    <div class="form-error" data-error="service_type"></div>
                </div>

                {{-- STEP 4: Date & Time --}}
                <div class="form-step" data-pane="4">
                    <div class="date-time-picker">
                        <div class="calendar-section">
                            <div class="calendar-header">
                                <button type="button" class="calendar-nav" data-cal-prev>&lt;</button>
                                <span class="month-year" data-month>—</span>
                                <button type="button" class="calendar-nav" data-cal-next>&gt;</button>
                            </div>
                            <div class="calendar-grid" data-cal>
                                <div class="day-name">Su</div>
                                <div class="day-name">Mo</div>
                                <div class="day-name">Tu</div>
                                <div class="day-name">We</div>
                                <div class="day-name">Th</div>
                                <div class="day-name">Fr</div>
                                <div class="day-name">Sa</div>
                                {{-- days injected by JS --}}
                            </div>
                        </div>
                        <div class="time-slots-container" data-slots>
                            <div class="muted" data-slots-placeholder>
                                <i class="ri-calendar-line"></i>
                                <p>Select a date to see available time slots</p>
                            </div>
                            <div class="time-slots" data-time-slots style="display: none;"></div>
                        </div>
                    </div>
                    <input type="hidden" name="appointment_date" data-appointment-date>
                    <input type="hidden" name="appointment_time" data-appointment-time>
                    <div class="form-error" data-error="appointment_date"></div>
                    <div class="form-error" data-error="appointment_time"></div>
                </div>

                {{-- STEP 5: Patient Details --}}
                <div class="form-step" data-pane="5">
                    <h4>Please provide your information</h4>
                    <div class="form-grid" style="margin-top:20px">
                        <div class="form-group">
                            <label class="form-label">First Name *</label>
                            <input type="text" name="patient_first_name" class="form-input" required
                                   value="{{ auth()->check() ? auth()->user()->name : '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="patient_last_name" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email *</label>
                            <input type="email" name="patient_email" class="form-input" required
                                   value="{{ auth()->check() ? auth()->user()->email : '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone *</label>
                            <input type="tel" name="patient_phone" class="form-input" required
                                   value="{{ auth()->check() ? auth()->user()->phone : '' }}">
                        </div>
                        <div class="form-group" style="grid-column:span 2">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="patient_dob" class="form-input"
                                   max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group" style="grid-column:span 2">
                            <label class="form-label">Reason for visit (optional)</label>
                            <textarea name="notes" class="form-input" rows="3"
                                      placeholder="Please describe your symptoms or reason for visit..."></textarea>
                        </div>
                    </div>
                    <div class="form-errors" data-step-errors></div>
                </div>

                {{-- STEP 6: Confirmation --}}
                <div class="form-step" data-pane="6">
                    <h4>Review your appointment</h4>
                    <div class="confirmation-summary" style="margin-top:20px">
                        <div class="summary-item">
                            <span class="label">Doctor</span>
                            <span class="value">Dr. {{ $doctor->name }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Consultation Type</span>
                            <span class="value" data-sum-consultation>—</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Chamber</span>
                            <span class="value" data-sum-chamber>—</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Service</span>
                            <span class="value" data-sum-service>—</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Date & Time</span>
                            <span class="value" data-sum-dt>—</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Fees</span>
                            <span class="value" data-sum-fees>—</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Patient</span>
                            <span class="value" data-sum-patient>—</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Contact</span>
                            <span class="value" data-sum-contact>—</span>
                        </div>
                    </div>

                    <div class="terms-agreement" style="margin-top: 20px;">
                        <label class="form-check">
                            <input type="checkbox" name="terms_agreed" required>
                            <span>I agree to the <a href="#" target="_blank">terms and conditions</a> and understand that I may be charged a fee for no-shows or late cancellations.</span>
                        </label>
                    </div>
                </div>

                {{-- STEP 7: Success --}}
                <div class="form-step" data-pane="7">
                    <div class="success-message">
                        <svg class="success-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle cx="26" cy="26" r="25" fill="#48BB78" opacity=".2" />
                            <path fill="#48BB78" d="M20.5,38.2L9.7,27.4l2.1-2.1l8.7,8.7l17-17l2.1,2.1L22.6,38.2C22,38.8,21.1,38.8,20.5,38.2z" />
                        </svg>
                        <h3>Appointment Confirmed!</h3>
                        <p>You'll receive an email confirmation shortly.</p>
                        <div class="appointment-details" data-success-details></div>
                        <button type="button" class="btn btn-primary" data-close>Close</button>
                    </div>
                </div>
            </div>

            <footer class="modal-footer" data-footer>
                <button type="button" class="btn btn-secondary" data-prev disabled>Previous</button>
                <button type="button" class="btn btn-primary" data-next>Next</button>
                <button type="submit" class="btn btn-success" data-submit style="display: none;">Confirm Booking</button>
            </footer>
        </form>
    </div>
</div>
<!-- About Section -->
<section id="about" class="py-20 bg-white">
    <div class="container mx-auto px-6 lg:px-12">
        <div class="text-center mb-16">
            <div class="inline-block px-4 py-2 bg-cyan-100 text-cyan-700 rounded-full text-sm font-medium mb-4">
                About Dr. {{ explode(' ', $doctor->name)[0] ?? 'Doctor' }}
            </div>
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4 custom-font">
                {{ $doctor->profile->subheadline ?? 'Dedicated to Your Wellbeing' }}
            </h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">{{ $doctor->profile->tagline ?? 'With years of medical practice, combining expertise with compassionate care.' }}</p>
        </div>

        <!-- Professional Background -->
        <div class="grid lg:grid-cols-2 gap-12 items-center mb-20">
            <div>
                <img alt="Dr. {{ $doctor->name }} with patient"
                    class="rounded-2xl shadow-xl w-full h-auto object-cover object-top"
                    src="{{ $doctor->galleries->where('category', 'care')->first()->image_url ?? 'https://readdy.ai/api/search-image?query=Professional%20doctor%20consulting%20with%20patient' }}">
            </div>
            <div class="space-y-6">
                <h3 class="text-3xl font-bold text-gray-900">Professional Background</h3>
                <p class="text-gray-600 leading-relaxed">{{ $doctor->profile->about_long ?? 'Board-certified physician with comprehensive medical experience.' }}</p>

                @if($doctor->qualification)
                <p class="text-gray-600 leading-relaxed">Dr. {{ $doctor->name }} is a {{ $doctor->qualification }} specializing in internal medicine and cardiology.</p>
                @endif

                @if($doctor->reg_no)
                <p class="text-gray-600 leading-relaxed">Registration Number: {{ $doctor->reg_no }}</p>
                @endif
            </div>
        </div>

        <!-- Education & Training -->
        @if($doctor->educations->count() > 0)
        <div class="mb-20">
            <div class="text-center mb-12">
                <h3 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2 custom-font">Education & Training</h3>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Comprehensive medical education from renowned institutions</p>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
                @foreach($doctor->educations as $education)
                <div class="bg-white border-2 border-gray-100 p-6 rounded-xl hover:border-cyan-200 hover:shadow-lg transition-all cursor-pointer">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-teal-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="ri-graduation-cap-fill text-2xl text-white"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm text-cyan-600 font-semibold mb-1">
                                {{ $education->start_year }}@if($education->end_year)-{{ $education->end_year }}@endif
                            </div>
                            <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $education->degree }}</h4>
                            <p class="text-gray-700 font-medium mb-1">{{ $education->institution }}</p>
                            @if($education->city)
                            <p class="text-gray-500 text-sm mb-2">{{ $education->city }}{{ $education->country ? ', ' . $education->country : '' }}</p>
                            @endif
                            @if($education->description)
                            <p class="text-gray-600 text-sm">{{ $education->description }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Certifications -->
        @if($doctor->certifications->count() > 0)
        <div class="mb-20">
            <div class="text-center mb-12">
                <h3 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2 custom-font">Board Certifications & Credentials</h3>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Maintaining the highest standards of medical excellence</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($doctor->certifications as $certification)
                <div class="bg-gradient-to-br from-cyan-50 to-teal-50 p-6 rounded-xl hover:shadow-lg transition-all cursor-pointer">
                    <div class="w-14 h-14 bg-cyan-600 rounded-lg flex items-center justify-center mb-4">
                        <i class="ri-award-fill text-2xl text-white"></i>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-sm text-cyan-600 font-semibold">{{ $certification->year }}</span>
                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                            {{ ucfirst($certification->status) }}
                        </span>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 mb-2">{{ $certification->title }}</h4>
                    <p class="text-gray-600 text-sm">{{ $certification->organization }}</p>
                    @if($certification->description)
                    <p class="text-gray-600 text-sm mt-2">{{ $certification->description }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Professional Affiliations -->
        @if($doctor->affiliations->count() > 0)
        <div class="mb-20">
            <div class="text-center mb-12">
                <h3 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2 custom-font">Professional Affiliations</h3>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Proud member of leading medical institutions and organizations</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($doctor->affiliations as $affiliation)
                <div class="bg-white border-2 border-gray-100 p-6 rounded-xl hover:border-teal-200 hover:shadow-lg transition-all cursor-pointer">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            @if($affiliation->type == 'hospital')
                            <i class="ri-hospital-line text-xl text-teal-600"></i>
                            @elseif($affiliation->type == 'organization')
                            <i class="ri-building-line text-xl text-teal-600"></i>
                            @else
                            <i class="ri-team-fill text-xl text-teal-600"></i>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="text-xs text-teal-600 font-semibold mb-2 uppercase">
                                {{ ucfirst($affiliation->type) }} Affiliation
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $affiliation->name }}</h4>
                            @if($affiliation->position)
                            <p class="text-gray-600 text-sm">{{ $affiliation->position }}</p>
                            @endif
                            @if($affiliation->description)
                            <p class="text-gray-600 text-sm mt-2">{{ $affiliation->description }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Areas of Expertise -->
        @if($showServices && $doctor->services->count() > 0)
        <div class="bg-gray-50 rounded-2xl p-8 lg:p-12">
            <h3 class="text-3xl font-bold text-gray-900 mb-8 text-center">Areas of Expertise</h3>
            <div class="grid md:grid-cols-2 gap-4">
                @foreach($doctor->services as $service)
                <div class="flex items-center gap-3 bg-white p-4 rounded-lg">
                    <div class="w-8 h-8 bg-cyan-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="ri-check-line text-cyan-600"></i>
                    </div>
                    <span class="text-gray-700 font-medium">{{ $service->title }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Specialties Section -->
@if($showProfessionalProfile && $doctor->specialties->count() > 0)
<section id="specialties" class="py-20 bg-gradient-to-br from-cyan-50 to-teal-50">
    <div class="container mx-auto px-6 lg:px-12">
        <div class="text-center mb-16">
            <div class="inline-block px-4 py-2 bg-cyan-600 text-white rounded-full text-sm font-medium mb-4">
                Medical Specialties
            </div>
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4 custom-font">Areas of Specialization</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Providing expert medical care across multiple specialties with a patient-centered approach</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($doctor->specialties as $specialty)
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer group hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-teal-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="{{ $specialty->icon ?? 'ri-heart-pulse-fill' }} text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $specialty->name }}</h3>
                <p class="text-gray-600 leading-relaxed mb-4">{{ $specialty->description ?? 'Comprehensive medical care and treatment.' }}</p>
                @if($specialty->patients_treated)
                <div class="flex items-center gap-2 text-cyan-600 font-semibold">
                    <i class="ri-user-line"></i>
                    <span>{{ number_format($specialty->patients_treated) }}+ Patients Treated</span>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Experience & Timeline -->
@if($showProfessionalProfile && $doctor->experiences->count() > 0)
<section id="experience" class="py-20 bg-white">
    <div class="container mx-auto px-6 lg:px-12">
        <div class="text-center mb-16">
            <div class="inline-block px-4 py-2 bg-teal-100 text-teal-700 rounded-full text-sm font-medium mb-4">
                Professional Journey
            </div>
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4 custom-font">Experience & Achievements</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">A proven track record of excellence in medical practice and patient care</p>
        </div>

        <!-- Stats -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            <div class="bg-gradient-to-br from-cyan-500 to-teal-500 rounded-2xl p-8 text-center text-white">
                <div class="text-5xl font-bold mb-2">{{ $doctor->profile->years_experience ?? '15' }}+</div>
                <div class="text-cyan-100 font-medium">Years Experience</div>
            </div>
            <div class="bg-gradient-to-br from-cyan-500 to-teal-500 rounded-2xl p-8 text-center text-white">
                <div class="text-5xl font-bold mb-2">{{ $doctor->profile->patients_count ?? '12,000' }}+</div>
                <div class="text-cyan-100 font-medium">Patients Treated</div>
            </div>
            <div class="bg-gradient-to-br from-cyan-500 to-teal-500 rounded-2xl p-8 text-center text-white">
                <div class="text-5xl font-bold mb-2">{{ $doctor->certifications->count() ?? '25' }}+</div>
                <div class="text-cyan-100 font-medium">Certifications</div>
            </div>
            <div class="bg-gradient-to-br from-cyan-500 to-teal-500 rounded-2xl p-8 text-center text-white">
                <div class="text-5xl font-bold mb-2">{{ $doctor->profile->satisfaction_rate ?? '98' }}%</div>
                <div class="text-cyan-100 font-medium">Patient Satisfaction</div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="relative">
            <div class="absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-cyan-200 to-teal-200 hidden lg:block"></div>
            <div class="space-y-12">
                @foreach($doctor->experiences as $index => $experience)
                <div class="flex flex-col lg:flex-row gap-8 items-center {{ $index % 2 == 0 ? '' : 'lg:flex-row-reverse' }}">
                    <div class="flex-1 {{ $index % 2 == 0 ? 'lg:text-right' : 'lg:text-left' }}">
                        <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                            <div class="text-cyan-600 font-bold text-lg mb-2">
                                {{ $experience->start_year }}@if($experience->end_year)-{{ $experience->end_year }}@endif
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $experience->title }}</h3>
                            <div class="text-teal-600 font-semibold mb-3">{{ $experience->organization }}</div>
                            @if($experience->description)
                            <p class="text-gray-600">{{ $experience->description }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-teal-500 rounded-full flex items-center justify-center shadow-lg">
                            <i class="ri-briefcase-fill text-2xl text-white"></i>
                        </div>
                    </div>
                    <div class="flex-1 hidden lg:block"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- Online Chamber Section -->
@if($showServices && $doctor->accepts_virtual_visits && $doctor->telemedicinePlatforms->count() > 0)
<section id="online-chamber" class="py-20 bg-gradient-to-br from-cyan-50 via-white to-teal-50">
    <div class="container mx-auto px-6 lg:px-12">
        <div class="text-center mb-16">
            <div class="inline-block px-4 py-2 bg-cyan-100 text-cyan-700 rounded-full text-sm font-medium mb-4">
                Telemedicine Services
            </div>
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-3 custom-font">Online Chamber & Virtual Care</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Experience quality healthcare from the comfort of your home with our comprehensive telemedicine services</p>
        </div>

        <!-- Supported Platforms -->
        <div class="bg-white p-6 rounded-xl border-2 border-cyan-100 mb-8">
            <h4 class="text-lg font-bold text-gray-900 mb-4">Supported Platforms</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($doctor->telemedicinePlatforms->where('active', true) as $platform)
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: {{ $platform->color ?? '#3b82f6' }}">
                        <i class="{{ $platform->icon }} text-white"></i>
                    </div>
                    <span class="text-gray-700 font-medium">{{ $platform->name }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Services Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all cursor-pointer border-2 border-transparent hover:border-cyan-200">
                <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-teal-500 rounded-xl flex items-center justify-center mb-4">
                    <i class="ri-video-chat-fill text-3xl text-white"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-900 mb-3">Video Consultations</h4>
                <p class="text-gray-600 mb-4 text-sm leading-relaxed">Face-to-face virtual appointments from the comfort of your home</p>
            </div>
            <!-- Add other service cards similarly -->
        </div>
    </div>
</section>
@endif

<!-- Gallery Section -->
@if($showContent && $doctor->galleries->count() > 0)
<section id="gallery" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6 lg:px-12">
        <div class="text-center mb-12">
            <div class="inline-block px-4 py-2 bg-teal-100 text-teal-700 rounded-full text-sm font-medium mb-4">
                Photo Gallery
            </div>
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4 custom-font">Our Clinic & Facilities</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Take a virtual tour of our modern medical facility</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($doctor->galleries as $gallery)
            <div class="group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer hover:shadow-2xl transition-all duration-300">
                <img alt="{{ $gallery->title }}"
                    class="w-full h-64 object-cover object-top group-hover:scale-110 transition-transform duration-500"
                    src="{{ url($gallery->image_url) }}">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <div class="text-cyan-400 text-sm font-semibold mb-1">{{ ucfirst($gallery->category) }}</div>
                        <h3 class="text-white text-xl font-bold">{{ $gallery->title }}</h3>
                        @if($gallery->caption)
                        <p class="text-gray-300 text-sm mt-1">{{ $gallery->caption }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Testimonials Section -->
@if($showContent && $doctor->testimonials->count() > 0)
<section class="py-20 bg-gradient-to-br from-gray-50 to-cyan-50">
    <div class="container mx-auto px-6 lg:px-12">
        <div class="text-center mb-16">
            <div class="inline-block px-4 py-2 bg-cyan-100 text-cyan-700 rounded-full text-sm font-medium mb-4">
                Patient Testimonials
            </div>
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4 custom-font">What Our Patients Say</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">Don't just take our word for it. Here's what our patients have to say about their experience.</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($doctor->testimonials as $testimonial)
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer">
                <div class="flex items-center gap-4 mb-6">
                    @if($testimonial->photo)
                    <img alt="{{ $testimonial->patient_name }}" class="w-16 h-16 rounded-full object-cover object-top" src="{{ url($testimonial->photo) }}">
                    @else
                    <div class="w-16 h-16 bg-cyan-100 rounded-full flex items-center justify-center">
                        <i class="ri-user-fill text-2xl text-cyan-600"></i>
                    </div>
                    @endif
                    <div>
                        <h4 class="font-bold text-gray-900">{{ $testimonial->patient_name }}</h4>
                        @if($testimonial->since)
                        <p class="text-sm text-gray-600">Patient since {{ $testimonial->since }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex gap-1 mb-4">
                    @for($i = 1; $i <= 5; $i++)
                    <i class="ri-star-fill {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                    @endfor
                </div>
                <p class="text-gray-600 leading-relaxed">"{{ $testimonial->content }}"</p>
                @if($testimonial->verified)
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-cyan-600">
                        <i class="ri-verified-badge-fill"></i>
                        <span class="text-sm font-medium">Verified Patient</span>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- FAQs Section -->
@if($showContent && $doctor->faqs->count() > 0)
<section class="py-20 bg-white">
    <div class="container mx-auto px-6 lg:px-12">
        <div class="text-center mb-16">
            <div class="inline-block px-4 py-2 bg-cyan-100 text-cyan-700 rounded-full text-sm font-medium mb-4">
                FAQ
            </div>
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4 custom-font">Frequently Asked Questions</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Find answers to common questions about our practice, appointments, and services.</p>
        </div>
        <div class="max-w-4xl mx-auto space-y-4">
            @foreach($doctor->faqs as $faq)
            <details class="bg-gradient-to-br from-gray-50 to-cyan-50 rounded-xl overflow-hidden group cursor-pointer">
                <summary class="px-8 py-6 font-semibold text-gray-900 text-lg cursor-pointer hover:text-cyan-600 transition-colors flex items-center justify-between">
                    <span>{{ $faq->question }}</span>
                    <i class="ri-add-line text-2xl group-open:rotate-45 transition-transform"></i>
                </summary>
                <div class="px-8 pb-6">
                    <p class="text-gray-600 leading-relaxed">{{ $faq->answer }}</p>
                </div>
            </details>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
