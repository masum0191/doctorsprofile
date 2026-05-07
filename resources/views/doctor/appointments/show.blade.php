@extends('layouts.admin')

@section('title', 'Appointment Details - ' . $appointment->appointment_number)

@section('content')
<style>
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.1);
        --primary-dark: #2a6d5a;
        --primary-soft: rgba(49, 128, 105, 0.05);
        --primary-hover: rgba(49, 128, 105, 0.15);
        --primary-border: rgba(49, 128, 105, 0.15);
        --silver: #f8fafc;
        --silver-dark: #f1f5f9;
        --silver-border: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --text-light: #94a3b8;
    }

    /* Compact Top Header - Professional White Theme */
    .compact-header {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--silver-border);
        box-shadow: 0 2px 12px rgba(15, 23, 42, 0.04);
        position: relative;
        overflow: hidden;
    }

    /* Header Content */
    .header-content { 
        display: flex;
        align-items: flex-start;
        gap: 1.25rem;
        position: relative;
    }

    .avatar-compact {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.25rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(49, 128, 105, 0.15);
        position: relative;
        overflow: hidden;
    }

    .avatar-compact::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
    }

    .patient-info {
        flex: 1;
        min-width: 0;
    }

    .patient-name {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
        line-height: 1.3;
    }

    .appointment-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .meta-item i {
        color: var(--primary);
        font-size: 0.9rem;
    }

    /* Status Badge - Compact */
    .status-badge-compact {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid;
        position: relative;
        overflow: hidden;
    }

    .status-badge-compact::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: currentColor;
        opacity: 0.08;
    }

    .status-pending {
        color: #f59e0b;
        border-color: rgba(245, 158, 11, 0.3);
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), transparent);
    }

    .status-confirmed {
        color: #318069;
        border-color: rgba(49, 128, 105, 0.3);
        background: linear-gradient(135deg, rgba(49, 128, 105, 0.05), transparent);
    }

    .status-completed {
        color: #10b981;
        border-color: rgba(16, 185, 129, 0.3);
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), transparent);
    }

    .status-cancelled {
        color: #ef4444;
        border-color: rgba(239, 68, 68, 0.3);
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.05), transparent);
    }

    /* Header Actions */
    .header-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-shrink: 0;
    }

    .btn-header {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: 1px solid var(--silver-border);
        background: white;
        color: var(--text-dark);
    }

    .btn-header:hover {
        border-color: var(--primary);
        color: var(--primary);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(49, 128, 105, 0.1);
    }

    .btn-header-primary {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .btn-header-primary:hover {
        background: var(--primary-dark);
        color: white;
        border-color: var(--primary-dark);
        box-shadow: 0 4px 12px rgba(49, 128, 105, 0.2);
    }

    /* Compact Info Grid - Silverish Theme */
    .compact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        padding: 1.25rem;
    }

    .info-item-compact {
        padding: 1rem;
        background: var(--silver);
        border-radius: 12px;
        border: 1px solid var(--silver-border);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .info-item-compact:hover {
        border-color: var(--primary-border);
        background: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
    }

    .info-label-compact {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-label-compact i {
        color: var(--primary);
        font-size: 0.9rem;
    }

    .info-value-compact {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-dark);
        line-height: 1.4;
    }

    .info-subvalue {
        font-size: 0.85rem;
        color: var(--text-light);
        margin-top: 0.25rem;
    }

    /* Card Header - Silverish Theme */
    .card-header-silver {
        background: linear-gradient(135deg, var(--silver), var(--silver-dark));
        border-bottom: 1px solid var(--silver-border);
        padding: .9rem 1rem;
        border-radius: 16px 16px 0 0;
        position: relative;
    }

    .card-header-silver::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--primary-border), transparent);
        opacity: 0.5;
    }

    .card-title-silver {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-title-silver i {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    /* Timeline - Compact */
    .timeline-compact {
        position: relative;
        padding-left: 7px;
    }

    .timeline-compact::before {
        content: "";
        position: absolute;
        left: 0.65rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--primary-light);
        border-radius: 2px;
    }

    .timeline-item-compact {
        position: relative;
        margin-bottom: 1rem;
        padding-left: 1.25rem;
    }

    .timeline-item-compact:last-child {
        margin-bottom: 0;
    }

    .timeline-marker-compact {
        position: absolute;
        left: -0.2rem;
        top: 0;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        border: 3px solid white;
        z-index: 2;
        box-shadow: 0 0 0 2px white;
    }

    .timeline-item-compact.completed .timeline-marker-compact {
        background: var(--primary);
        border-color: white;
    }

    .timeline-item-compact.pending .timeline-marker-compact {
        background: var(--silver);
        border: 2px solid var(--primary-light);
    }

    .timeline-content-compact {
        padding: 0.75rem;
        background: var(--silver);
        border-radius: 10px;
        border: 1px solid var(--silver-border);
        transition: all 0.3s ease;
    }

    .timeline-item-compact:hover .timeline-content-compact {
        border-color: var(--primary-border);
        background: white;
        transform: translateX(4px);
    }

    .timeline-title-compact {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .timeline-time-compact {
        font-size: 0.8rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Fee Display - Compact */
    .fee-display-compact {
        background: linear-gradient(135deg, var(--silver), white);
        border: 1px solid var(--primary-border);
        border-radius: 12px;
        padding: 1rem;
        margin: 1rem 1.25rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .fee-display-compact::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, var(--primary-soft), transparent);
        opacity: 0.3;
    }

    .fee-amount-compact {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
        line-height: 1;
        margin-bottom: 0.25rem;
        position: relative;
    }

    /* Notes Section - Compact */
    .notes-section-compact {
        margin: 1rem 1.25rem;
        padding: 1rem;
        background: linear-gradient(135deg, var(--silver), white);
        border-left: 3px solid var(--primary);
        border-radius: 0 10px 10px 0;
        position: relative;
    }

    .notes-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .notes-content-compact {
        font-size: 0.9rem;
        line-height: 1.5;
        color: var(--text-dark);
        margin: 0;
    }

    /* Action Buttons - Compact */
    .action-buttons-compact {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--silver-border);
        background: var(--silver);
    }

    .btn-action-compact {
        padding: 0.75rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: 1px solid var(--silver-border);
        background: white;
        color: var(--text-dark);
    }

    .btn-action-compact:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .btn-confirm {
        color: #318069;
        border-color: rgba(49, 128, 105, 0.3);
        background: linear-gradient(135deg, rgba(49, 128, 105, 0.05), white);
    }

    .btn-confirm:hover {
        background: #318069;
        color: white;
    }

    .btn-complete {
        color: #10b981;
        border-color: rgba(16, 185, 129, 0.3);
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), white);
    }

    .btn-complete:hover {
        background: #10b981;
        color: white;
    }

    .btn-cancel {
        color: #ef4444;
        border-color: rgba(239, 68, 68, 0.3);
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.05), white);
    }

    .btn-cancel:hover {
        background: #ef4444;
        color: white;
    }

    .btn-reschedule {
        color: #3b82f6;
        border-color: rgba(59, 130, 246, 0.3);
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), white);
    }

    .btn-reschedule:hover {
        background: #3b82f6;
        color: white;
    }

    /* Card - Compact */
    .card-compact {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--silver-border);
        margin-bottom: 1.5rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.02);
        transition: all 0.3s ease;
    }

    .card-compact:hover {
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
        transform: translateY(-1px);
    }

    /* Modal - Compact */
    .modal-compact .modal-content {
        border-radius: 16px;
        border: 1px solid var(--silver-border);
        overflow: hidden;
    }

    .modal-compact .modal-header {
        background: linear-gradient(135deg, var(--silver), var(--silver-dark));
        border-bottom: 1px solid var(--silver-border);
        padding: 1rem 1.25rem;
    }

    .modal-compact .modal-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }

    .modal-compact .modal-body {
        padding: 1.25rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 1rem;
        }
        
        .header-actions {
            width: 100%;
            justify-content: flex-end;
        }
        
        .compact-grid {
            grid-template-columns: 1fr;
        }
        
        .action-buttons-compact {
            grid-template-columns: 1fr;
        }
        
        .avatar-compact {
            width: 50px;
            height: 50px;
            font-size: 1rem;
        }
        
        .patient-name {
            font-size: 1.2rem;
        }
    }
</style>

<!-- Compact Header -->
<div class="compact-header">
    <div class="header-content">
        <div class="avatar-compact">
            {{ strtoupper(substr($appointment->patient_first_name, 0, 1)) }}{{ strtoupper(substr($appointment->patient_last_name, 0, 1)) }}
        </div>
        
        <div class="patient-info">
            <h2 class="patient-name">
                {{ $appointment->patient_first_name }} {{ $appointment->patient_last_name }}
                <span class="status-badge-compact status-{{ $appointment->status }} ms-3">
                    <i class="fas fa-circle" style="font-size: 6px;"></i>
                    {{ strtoupper($appointment->status) }}
                </span>
            </h2>
            
            <div class="appointment-meta mt-3">
                <div class="meta-item">
                    <i class="fas fa-hashtag"></i>
                    <span>#{{ $appointment->appointment_number ?? $appointment->id }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ $appointment->appointment_date->format('M d, Y') }}</span>
                </div>
               
                <div class="meta-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $appointment->chamber->name ?? 'No Chamber' }}</span>
                </div>
                @if($appointment->amount)
                <div class="meta-item">
                    <i class="fas fa-money-bill-wave"></i>
                    <span class="fw-semibold text-primary">৳{{ number_format($appointment->amount, 2) }}</span>
                </div>
                @endif
            </div>
        </div>
        
        <div class="header-actions">
            <a href="{{ route('admin.appointments.index') }}" class="btn-header">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
            <a href="{{ route('admin.appointment.today') }}" class="btn-header btn-header-primary">
                <i class="fas fa-calendar-day me-2"></i>Today's View
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Patient & Appointment Details -->
    <div class="col-lg-8">
        <!-- Patient Information Card -->
        <div class="card-compact">
            <div class="card-header-silver">
                <h3 class="card-title-silver">
                    <i class="fas fa-user-injured"></i>
                    Patient Information
                </h3>
            </div>
            
            <div class="compact-grid">
                <div class="info-item-compact">
                    <div class="info-label-compact">
                        <i class="fas fa-user"></i>
                        Full Name
                    </div>
                    <div class="info-value-compact">
                        {{ $appointment->patient_first_name }} {{ $appointment->patient_last_name }}
                    </div>
                </div>

                <div class="info-item-compact">
                    <div class="info-label-compact">
                        <i class="fas fa-envelope"></i>
                        Email Address
                    </div>
                    <div class="info-value-compact">
                        {{ $appointment->patient_email ?: 'Not provided' }}
                    </div>
                </div>

                <div class="info-item-compact">
                    <div class="info-label-compact">
                        <i class="fas fa-phone"></i>
                        Phone Number
                    </div>
                    <div class="info-value-compact">
                        {{ $appointment->patient_phone ?: 'Not provided' }}
                    </div>
                </div>

                <div class="info-item-compact">
                    <div class="info-label-compact">
                        <i class="fas fa-birthday-cake"></i>
                        Date of Birth
                    </div>
                    <div class="info-value-compact">
                        {{ $appointment->patient_dob ? $appointment->patient_dob->format('M d, Y') : 'Not provided' }}
                        @if($appointment->patient_dob)
                        <div class="info-subvalue">
                            ({{ $appointment->patient_dob->age }} years old)
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointment Details Card -->
        <div class="card-compact">
            <div class="card-header-silver">
                <h3 class="card-title-silver">
                    <i class="fas fa-calendar-alt"></i>
                    Appointment Details
                </h3>
            </div>
            
            <div class="compact-grid">
                <div class="info-item-compact">
                    <div class="info-label-compact">
                        <i class="fas fa-calendar-check"></i>
                        Date & Time
                    </div>
                    <div class="info-value-compact">
                        {{ $appointment->appointment_date->format('l, F j, Y') }}
                        <div class="info-subvalue">
                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                        </div>
                    </div>
                </div>

                <div class="info-item-compact">
                    <div class="info-label-compact">
                        <i class="fas fa-hospital"></i>
                        Chamber
                    </div>
                    <div class="info-value-compact">
                        {{ $appointment->chamber->name ?? 'Not specified' }}
                        @if($appointment->chamber && $appointment->chamber->address)
                        <div class="info-subvalue">
                            {{ $appointment->chamber->address }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="info-item-compact">
                    <div class="info-label-compact">
                        <i class="fas fa-stethoscope"></i>
                        Consultation Type
                    </div>
                    <div class="info-value-compact">
                        <span class="badge rounded-pill 
                            {{ $appointment->consultation_type == 'online' 
                                ? 'bg-info bg-opacity-10 text-info border-1 border-info border-opacity-25' 
                                : 'bg-primary bg-opacity-10 text-primary border-1 border-primary border-opacity-25' }}">
                            <i class="fas 
                                {{ $appointment->consultation_type == 'online' 
                                    ? 'fa-video' 
                                    : 'fa-user-md' }} me-1"></i>
                            {{ ucfirst($appointment->consultation_type) }}
                        </span>
                    </div>
                </div>

                <div class="info-item-compact">
                    <div class="info-label-compact">
                        <i class="fas fa-tag"></i>
                        Service Type
                    </div>
                    <div class="info-value-compact">
                        {{ $appointment->service_type ?: 'General Consultation' }}
                    </div>
                </div>
            </div>

            <!-- Fee Display -->
            <div class="fee-display-compact">
                <div class="info-label-compact mb-2">Consultation Fee</div>
                <div class="fee-amount-compact">
                    ৳{{ number_format($appointment->amount ?? 0, 2) }}
                </div>
                <small class="text-muted">
                    {{ $appointment->currency ?: 'BDT' }}
                    @if($appointment->payment_status)
                        • Payment: <span class="fw-semibold">{{ ucfirst($appointment->payment_status) }}</span>
                    @endif
                </small>
            </div>

            <!-- Notes Section -->
            @if($appointment->notes)
            <div class="notes-section-compact">
                <div class="notes-label">
                    <i class="fas fa-sticky-note"></i>
                    Patient Notes
                </div>
                <p class="notes-content-compact">
                    {{ $appointment->notes }}
                </p>
            </div>
            @endif
        </div>

        <!-- Action Buttons Card -->
        <div class="card-compact">
            <div class="card-header-silver">
                <h3 class="card-title-silver">
                    <i class="fas fa-bolt"></i>
                    Quick Actions
                </h3>
            </div>
            
            <div class="action-buttons-compact">
                @if(in_array($appointment->status, ['pending', 'confirmed']))
                    @if($appointment->status == 'pending')
                    <button type="button" class="btn-action-compact btn-confirm" onclick="updateStatus('confirmed')">
                        <i class="fas fa-check-circle"></i>
                        Confirm
                    </button>
                    @endif

                    <button type="button" class="btn-action-compact btn-complete" onclick="updateStatus('completed')">
                        <i class="fas fa-check-double"></i>
                        Complete
                    </button>

                    <button type="button" class="btn-action-compact btn-reschedule" onclick="showRescheduleModal()">
                        <i class="fas fa-calendar-edit"></i>
                        Reschedule
                    </button>

                    <button type="button" class="btn-action-compact btn-cancel" onclick="updateStatus('cancelled')">
                        <i class="fas fa-times-circle"></i>
                        Cancel
                    </button>
                @else
                    <div class="col-12 text-center py-3">
                        <div class="bg-light rounded-3 p-3 border">
                            <i class="fas fa-info-circle me-2 text-muted"></i>
                            <span class="fw-semibold">Appointment is {{ ucfirst($appointment->status) }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Timeline -->
    <div class="col-lg-4">
        <!-- Timeline Card -->
        <div class="card-compact">
            <div class="card-header-silver">
                <h3 class="card-title-silver">
                    <i class="fas fa-history"></i>
                    Appointment Timeline
                </h3>
            </div>
            
            <div class="py-4 px-3">
                <div class="timeline-compact">
                    {{-- CREATED --}}
                    <div class="timeline-item-compact completed">
                        <div class="timeline-marker-compact"></div>
                        <div class="timeline-content-compact">
                            <div class="timeline-title-compact">Appointment Created</div>
                            <div class="timeline-time-compact">
                                <i class="far fa-clock"></i>
                                {{ $appointment->created_at->format('M j, Y g:i A') }}
                            </div>
                        </div>
                    </div>
                    
                    {{-- CONFIRMED --}}
                    @if($appointment->confirmed_at)
                        <div class="timeline-item-compact completed">
                            <div class="timeline-marker-compact"></div>
                            <div class="timeline-content-compact">
                                <div class="timeline-title-compact">Appointment Confirmed</div>
                                <div class="timeline-time-compact">
                                    <i class="far fa-clock"></i>
                                    {{ $appointment->confirmed_at->format('M j, Y g:i A') }}
                                </div>
                            </div>
                        </div>
                    @elseif($appointment->status === 'pending')
                        <div class="timeline-item-compact pending">
                            <div class="timeline-marker-compact"></div>
                            <div class="timeline-content-compact">
                                <div class="timeline-title-compact">Awaiting Confirmation</div>
                                <div class="timeline-time-compact">
                                    <i class="fas fa-hourglass-half"></i>
                                    Pending
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    {{-- COMPLETED --}}
                    @if($appointment->completed_at)
                        <div class="timeline-item-compact completed">
                            <div class="timeline-marker-compact"></div>
                            <div class="timeline-content-compact">
                                <div class="timeline-title-compact">Appointment Completed</div>
                                <div class="timeline-time-compact">
                                    <i class="far fa-clock"></i>
                                    {{ $appointment->completed_at->format('M j, Y g:i A') }}
                                </div>
                            </div>
                        </div>
                    @elseif(in_array($appointment->status, ['pending', 'confirmed']))
                        <div class="timeline-item-compact pending">
                            <div class="timeline-marker-compact"></div>
                            <div class="timeline-content-compact">
                                <div class="timeline-title-compact">Scheduled Appointment</div>
                                <div class="timeline-time-compact">
                                    <i class="far fa-clock"></i>
                                    {{ $appointment->appointment_date->format('M j, Y') }}
                                    at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    {{-- CANCELLED --}}
                    @if($appointment->status === 'cancelled' && $appointment->cancelled_at)
                        <div class="timeline-item-compact completed">
                            <div class="timeline-marker-compact"></div>
                            <div class="timeline-content-compact">
                                <div class="timeline-title-compact">Appointment Cancelled</div>
                                <div class="timeline-time-compact">
                                    <i class="far fa-clock"></i>
                                    {{ $appointment->cancelled_at->format('M j, Y g:i A') }}
                                </div>
                                @if($appointment->cancellation_reason)
                                <div class="mt-1">
                                    <small class="text-muted">
                                        Reason: {{ $appointment->cancellation_reason }}
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Doctor Info Card -->
        @if($appointment->doctor)
        <div class="card-compact">
            <div class="card-header-silver">
                <h3 class="card-title-silver">
                    <i class="fas fa-user-md"></i>
                    Assigned Doctor
                </h3>
            </div>
            
            <div class="p-4">
                <div class="info-item-compact mb-3">
                    <div class="info-label-compact">Doctor Name</div>
                    <div class="info-value-compact">{{ $appointment->doctor->name }}</div>
                </div>
                
                @if($appointment->doctor->specialization)
                <div class="info-item-compact">
                    <div class="info-label-compact">Specialization</div>
                    <div class="info-value-compact">{{ $appointment->doctor->specialization }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Reschedule Modal -->
<div class="modal fade modal-compact" id="rescheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-edit me-2"></i>Reschedule Appointment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rescheduleForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-primary bg-opacity-10 border-primary border-opacity-25 mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Select a new date and time for this appointment
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">New Date *</label>
                            <input type="date" 
                                   class="form-control border-primary" 
                                   id="appointment_date"
                                   name="appointment_date" 
                                   min="{{ date('Y-m-d') }}" 
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">New Time *</label>
                            <input type="time" 
                                   class="form-control border-primary" 
                                   id="appointment_time"
                                   name="appointment_time" 
                                   required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Reason (Optional)</label>
                            <textarea class="form-control border-primary" 
                                      id="reason" 
                                      name="reason" 
                                      rows="2"
                                      placeholder="Provide a reason for rescheduling..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-calendar-check me-2"></i>Reschedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default reschedule date to original date
    const rescheduleDateInput = document.querySelector('#rescheduleModal input[name="appointment_date"]');
    const rescheduleTimeInput = document.querySelector('#rescheduleModal input[name="appointment_time"]');
    
    if (rescheduleDateInput && rescheduleTimeInput) {
        rescheduleDateInput.value = '{{ $appointment->appointment_date->format("Y-m-d") }}';
        rescheduleTimeInput.value = '{{ $appointment->appointment_time }}';
    }
});

function updateStatus(status) {
    const statusText = status.charAt(0).toUpperCase() + status.slice(1);
    
    Swal.fire({
        title: `Update to ${statusText}?`,
        html: `Change appointment status to <strong>${statusText}</strong>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#318069',
        cancelButtonColor: '#6b7280',
        confirmButtonText: `Update to ${statusText}`,
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (!result.isConfirmed) return;
        
        $.ajax({
            url: '{{ route("admin.appointments.updateStatus", $appointment) }}',
            method: 'PUT',
            data: { status },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Updating...',
                    html: 'Updating appointment status',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    confirmButtonColor: '#318069'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to update status',
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    });
}

function showRescheduleModal() {
    const modal = new bootstrap.Modal(document.getElementById('rescheduleModal'));
    modal.show();
}

// Handle reschedule form submission
$('#rescheduleForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: '{{ route("admin.appointments.reschedule", $appointment) }}',
        method: 'PUT',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $('#rescheduleForm button[type="submit"]')
                .prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...');
        },
        success: function(response) {
            $('#rescheduleModal').modal('hide');
            
            Swal.fire({
                icon: 'success',
                title: 'Rescheduled!',
                text: response.message,
                confirmButtonColor: '#318069'
            }).then(() => {
                location.reload();
            });
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON?.message || 'Failed to reschedule appointment',
                confirmButtonColor: '#dc2626'
            });
            
            $('#rescheduleForm button[type="submit"]')
                .prop('disabled', false)
                .html('<i class="fas fa-calendar-check me-2"></i>Reschedule');
        }
    });
});
</script>
@endsection