@extends('layouts.admin')

@section('title', 'Doctor Dashboard')

@section('content')
    <style>

        :root {
            --primary: #318069;
            --primary-light: rgba(49, 128, 105, 0.1);
            --primary-dark: #2a6d5a;
            --primary-soft: rgba(49, 128, 105, 0.05);
            --primary-hover: rgba(49, 128, 105, 0.15);
        }


        .dashboard-stats-card {
            border: 1px solid rgba(49, 128, 105, 0.15);
            border-radius: 12px;
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            background: white;
        }

        .dashboard-stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(49, 128, 105, 0.15);
            border-color: var(--primary);
        }

        .stats-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            background: var(--primary-light);
            color: var(--primary);
        }

        .stats-card-primary {
            border-left: 4px solid var(--primary);
            background: linear-gradient(135deg, #ffffff 0%, var(--primary-soft) 100%);
        }

        /* Enhanced Quick Actions */
        .quick-action-card {
            border: 1px solid rgba(49, 128, 105, 0.15);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            background: white;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .quick-action-card:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(49, 128, 105, 0.15);
        }

        .quick-action-card:hover .quick-action-icon {
            transform: scale(1.1);
        }

        .quick-action-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 1rem;
            background: var(--primary-light);
            color: var(--primary);
            transition: all 0.3s ease;
        }



        /* Enhanced Today's Appointments */
        .today-appointments-card {
            border: 1px solid rgba(49, 128, 105, 0.15);
            border-radius: 12px;
            background: white;
            overflow: hidden;
        }

        .appointment-item-card {
            border: 1px solid rgba(49, 128, 105, 0.1);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
            background: white;
        }

        .appointment-item-card:hover {
            border-color: var(--primary);
            background: var(--primary-soft);
            transform: translateX(3px);
        }

        .appointment-item-card:last-child {
            margin-bottom: 0;
        }

        .patient-avatar-circle {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--primary);
            font-size: 1.25rem;
        }

        .time-badge {
            background: var(--primary-light);
            color: var(--primary);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.7rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 500;
        }

        .status-confirmed {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #92400e;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .status-completed {
            background: rgba(59, 130, 246, 0.1);
            color: #1e40af;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        /* Enhanced Patients Table */
        .patients-table-card {
            border: 1px solid rgba(49, 128, 105, 0.15);
            border-radius: 12px;
            background: white;
            overflow: hidden;
        }

        .patients-table {
            --bs-table-bg: transparent;
            margin-bottom: 0;
        }

        .patients-table thead th {
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--primary-light);
            padding-top: 1rem;
            padding-bottom: 1rem;
            background: var(--primary-soft);
        }

        .patients-table tbody tr {
            border-bottom: 1px solid var(--primary-soft);
            transition: all 0.2s ease;
        }

        .patients-table tbody tr:hover {
            background: var(--primary-soft);
        }

        .patients-table tbody tr:last-child {
            border-bottom: none;
        }

        .patients-table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }

        .patient-avatar-small {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--primary);
            margin-right: 0.75rem;
        }

        /* Enhanced Analytics Section */
        .analytics-card {
            border: 1px solid rgba(49, 128, 105, 0.15);
            border-radius: 12px;
            background: white;
            overflow: hidden;
        }

        .progress-track {
            height: 10px;
            background: var(--primary-light);
            border-radius: 10px;
            overflow: hidden;
            margin: 0.5rem 0;
        }

        .progress-fill {
            height: 100%;
            border-radius: 10px;
            background: var(--primary);
            transition: width 1s ease;
            position: relative;
            overflow: hidden;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, 0.4),
                    transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .metric-card {
            border: 1px solid rgba(49, 128, 105, 0.1);
            border-radius: 10px;
            padding: 1.5rem;
            background: white;
            transition: all 0.3s ease;
        }

        .metric-card:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(49, 128, 105, 0.1);
        }

        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        /* Modal Enhancement */
        .primary-modal .modal-content {
            border-radius: 12px;
            border: 1px solid rgba(49, 128, 105, 0.15);
            box-shadow: 0 10px 30px rgba(49, 128, 105, 0.15);
        }

        .primary-modal .modal-header {
            background: var(--primary);
            color: white;
            border-radius: 12px 12px 0 0;
            border-bottom: none;
            padding: 1.25rem 1.5rem;
        }

        .primary-modal .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .primary-modal .modal-body {
            padding: 1.5rem;
        }

        .primary-modal .modal-footer {
            border-top: 1px solid var(--primary-light);
            padding: 1.25rem 1.5rem;
            background: var(--primary-soft);
        }

        /* Primary Buttons */
        .btn-primary-outline {
            /* border: 1px solid var(--primary); */
            color: var(--primary);
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-primary-outline:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .btn-primary-soft {
            background: var(--primary-light);
            color: var(--primary);
            border: 1px solid rgba(49, 128, 105, 0.2);
            transition: all 0.3s ease;
        }

        .btn-primary-soft:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Card Headers */
        .card-header-primary {
            background: var(--primary-soft);
            border-bottom: 2px solid var(--primary-light);
            font-weight: 600;
            padding: 1rem 1.25rem;
        }

        .card-header-primary h5 {
            font-size: 15px !important;
        }

        .card-header-primary .badge {
            background: var(--primary);
            color: white;
        }

        /* Empty States */
        .empty-state {
            padding: 3rem 2rem;
            text-align: center;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--primary);
            margin: 0 auto 1.5rem;
        }

        /* Scrollbar */
        .scrollbar-primary::-webkit-scrollbar {
            width: 6px;
        }

        .scrollbar-primary::-webkit-scrollbar-track {
            background: var(--primary-light);
            border-radius: 3px;
        }

        .scrollbar-primary::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 3px;
        }

        .scrollbar-primary::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        @media (max-width: 768px) {

        .stats-icon-wrapper {
                    display : none;
                }
        }


    </style>

    <!-- Stats Overview -->
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6 col-6 mb-2">
            <div class="dashboard-stats-card stats-card-primary">
                <div class="p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small mb-1">Upcoming <span class="d-none d-md-inline">Appointments</span></div>
                            <div class="h3 mb-0 fw-bold">{{ $upcoming ?? 0 }}</div>
                            <div class="small mt-2">
                                <i class="fas fa-arrow-up me-1"></i> 12% this week
                            </div>
                        </div>
                        <div class="stats-icon-wrapper">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-6 mb-2">
            <div class="dashboard-stats-card stats-card-primary">
                <div class="p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small mb-1">Pending Requests</div>
                            <div class="h3 mb-0 fw-bold">{{ $pending ?? 0 }}</div>
                            <div class="small mt-2">
                                <i class="fas fa-clock me-1"></i> Needs attention
                            </div>
                        </div>
                        <div class="stats-icon-wrapper">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-6 mb-2">
            <div class="dashboard-stats-card stats-card-primary">
                <div class="p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small mb-1">Total Patients</div>
                            <div class="h3 mb-0 fw-bold">{{ number_format($patients ?? 0) }}</div>
                            <div class="small mt-2">
                                <i class="fas fa-user-plus me-1"></i> 8% growth
                            </div>
                        </div>
                        <div class="stats-icon-wrapper">
                            <i class="fas fa-user-injured"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-6 mb-2">
            <div class="dashboard-stats-card stats-card-primary">
                <div class="p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small mb-1">Monthly Revenue</div>
                            <div class="h3 mb-0 fw-bold">৳ {{ number_format($revenue ?? 0, 0) }}</div>
                            <div class="small mt-2">
                                <i class="fas fa-arrow-up me-1"></i> 15% increase
                            </div>
                        </div>
                        <div class="stats-icon-wrapper">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row mb-2">
        <!-- Quick Actions Section -->
        <div class="col-lg-8 mb-4">
            <div class="today-appointments-card">
                <div class="card-header-primary">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="quick-action-card">
                                <div class="quick-action-icon">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <h6 class="fw-semibold mb-2">New Booking</h6>
                                <p class="text-muted small mb-3">Schedule appointment</p>
                                <button type="button" class="btn btn-primary w-100" onclick="openBookingModal({{ $doctor->id ?? 'null' }}, {{ json_encode($chambers ?? []) }})">
                                    <span class="d-none d-md-inline-block">Book</span> Appointment
                                </button>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="/admin/add-new-prescriptions">
                                <div class="quick-action-card">
                                    <div class="quick-action-icon">
                                        <i class="fas fa-prescription"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-2">Prescription</h6>
                                    <p class="text-muted small mb-3">Write prescription</p>
                                    <button class="btn btn-primary-soft w-100">
                                        Write
                                    </button>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <div class="quick-action-card">
                                <div class="quick-action-icon">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <h6 class="fw-semibold mb-2">Generate Invoice</h6>
                                <p class="text-muted small mb-3">Create new bill</p>
                                <a href="{{ url('admin/invoices') }}" class="btn btn-primary-soft w-100">
                                    Create
                                </a>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="quick-action-card">
                                <div class="quick-action-icon">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <h6 class="fw-semibold mb-2">Visit Site</h6>
                                <p class="text-muted small mb-3">Open your public website</p>
                                <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary-soft w-100">
                                    Open Site
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="col-lg-4 mb-4">
            <div class="today-appointments-card h-100">
                <div class="card-header-primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-day me-2"></i>
                            Today's Appointments
                        </h5>
                    </div>
                    <small class="mt-3">{{ now()->format('l, F j, Y') }}</small>
                </div>
                <div class="card-body p-0">
                    <div class="scrollbar-primary" style="max-height: 400px; overflow-y: auto; padding: 1rem;">
                        @php
                            $todayAppointments = $appointments->filter(function ($appointment) {
                                return optional($appointment->appointment_date)->isToday();
                            });
                        @endphp

                        @forelse($todayAppointments as $appointment)
                            <div class="appointment-item-card">
                                <div class="d-flex align-items-start gap-3">
                                    <!-- <div class="patient-avatar-circle">
                                                    {{ substr($appointment->patient_full_name ?? (optional($appointment->patient)->name ?? 'P'), 0, 1) }}
                                                </div> -->
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="fw-semibold mb-1">
                                                    {{ $appointment->patient_full_name ?? (optional($appointment->patient)->name ?? 'Patient') }}
                                                </h6>
                                                @if ($appointment->patient_phone || optional($appointment->patient)->phone)
                                                    <small class="text-muted">
                                                        {{ $appointment->patient_phone ?? optional($appointment->patient)->phone }}
                                                    </small>
                                                @endif
                                            </div>
                                            <span class="status-indicator status-{{ $appointment->status }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </div>
                                        <div class="">
                                            <span class="time-badge">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $appointment->appointment_time ?? '--:--' }}
                                            </span>
                                            <div
                                                class="border-top pt-2 mt-3 d-flex justify-content-end align-items-center gap-2">
                                                <a href="{{url('admin/appointments/'.$appointment->id)}}" class="btn btn-sm btn-primary px-3">
                                                    View
                                                </a>
                                                {{-- <button class="btn btn-sm btn-primary px-3">
                                                    Check In
                                                </button> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <h6 class="fw-semibold mb-2">No appointments today</h6>
                                <p class="text-muted mb-3">All clear for the day</p>
                                <button class="btn btn-primary" onclick="openBookingModal()">
                                    <i class="fas fa-plus me-1"></i> Schedule Now
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Patients -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="patients-table-card">
            <div class="card-header-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-injured me-2"></i>
                        Recent Patients
                    </h5>
                    <a href="{{url('admin/patients')}}" class="text-decoration-none text-primary">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table patients-table">
                        <thead>
                            <tr>
                                <th class="ps-4">PATIENT</th>
                                <th class="d-none d-md-table-cell">CONTACT</th>
                                <th class="d-none d-lg-table-cell">LAST VISIT</th>
                                <th class="d-none d-lg-table-cell">UPCOMING</th>
                                <th class="text-end pe-4">ACTIONS</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($recentPatients as $patient)
                                <tr>
                                    <!-- PATIENT COLUMN -->
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="patient-avatar-small">
                                                {{ substr($patient->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">
                                                    {{ $patient->name }}
                                                </div>
                                                <small class="text-muted">
                                                    ID: PT{{ str_pad($patient->id, 4, '0', STR_PAD_LEFT) }}
                                                </small>

                                                <!-- Mobile contact -->
                                                <div class="d-md-none small text-muted mt-1">
                                                    <i class="fas fa-phone me-1"></i>
                                                    {{ $patient->phone ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- CONTACT COLUMN -->
                                    <td class="d-none d-md-table-cell">
                                        <div class="small">
                                            <div class="mb-1">
                                                <i class="fas fa-phone text-muted me-2"></i>
                                                {{ $patient->phone ?? 'N/A' }}
                                            </div>
                                            @if ($patient->email)
                                                <div>
                                                    <i class="fas fa-envelope text-muted me-2"></i>
                                                    <span class="text-truncate d-inline-block"
                                                        style="max-width: 150px;">
                                                        {{ $patient->email }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- LAST VISIT COLUMN -->
                                    <td class="d-none d-lg-table-cell">
                                        @php
                                            $lastAppt = $patient->appointments()
                                                ->orderByDesc('appointment_date')
                                                ->orderByDesc('appointment_time')
                                                ->first();
                                        @endphp
                                        <div class="fw-medium">
                                            {{ optional(optional($lastAppt)->appointment_date)->format('M d, Y') ?? 'Never' }}
                                        </div>
                                        <small class="text-muted">Last visit</small>
                                    </td>

                                    <!-- UPCOMING COLUMN -->
                                    <td class="d-none d-lg-table-cell">
                                        @php
                                            $upcomingAppt = $patient->appointments()
                                                ->where('appointment_date', '>=', now()->format('Y-m-d'))
                                                ->orderBy('appointment_date')
                                                ->first();
                                        @endphp

                                        @if ($upcomingAppt)
                                            <div class="fw-medium text-primary">
                                                {{ optional($upcomingAppt->appointment_date)->format('M d') }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $upcomingAppt->appointment_time }}
                                            </small>
                                        @else
                                            <span class="badge bg-light text-muted">None</span>
                                        @endif
                                    </td>

                                    <!-- ACTIONS COLUMN -->
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{url('admin/patients/'.$patient->id.'/profile/')}}"
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                                <span class="d-none d-sm-inline"> View</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state text-center py-5">
                                            <div class="empty-state-icon mb-3">
                                                <i class="fas fa-user-injured text-muted"></i>
                                            </div>
                                            <h6 class="fw-semibold mb-2">No recent patients</h6>
                                            <p class="text-muted mb-3">
                                                Start by adding your first patient
                                            </p>
                                            <button class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i> Add Patient
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>


    <!-- Analytics Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="analytics-card">
                <div class="card-header-primary">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Monthly Analytics
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="metric-card h-100">
                                <div class="metric-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-semibold">Appointments</span>
                                        <span class="fw-bold text-primary">+18%</span>
                                    </div>
                                    <div class="progress-track">
                                        <div class="progress-fill" style="width: 75%"></div>
                                    </div>
                                    <small class="text-muted">{{ $appointments->count() }} total this month</small>
                                </div>

                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-semibold">Revenue</span>
                                        <span class="fw-bold text-primary">+12%</span>
                                    </div>
                                    <div class="progress-track">
                                        <div class="progress-fill" style="width: 60%"></div>
                                    </div>
                                    <small class="text-muted">৳ {{ number_format($revenue ?? 0, 0) }} earned</small>
                                </div>

                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-semibold">Patient Satisfaction</span>
                                        <span class="fw-bold text-primary">94%</span>
                                    </div>
                                    <div class="progress-track">
                                        <div class="progress-fill" style="width: 94%"></div>
                                    </div>
                                    <small class="text-muted">Based on 42 reviews</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="metric-card">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="fw-semibold mb-0">Top Services This Month</h6>
                                    <span class="badge bg-primary">All Services</span>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="text-center">
                                            <div class="metric-icon mx-auto mb-2">
                                                <i class="fas fa-stethoscope"></i>
                                            </div>
                                            <div class="h4 fw-bold mb-1">42</div>
                                            <small class="text-muted">General Checkup</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center">
                                            <div class="metric-icon mx-auto mb-2">
                                                <i class="fas fa-redo"></i>
                                            </div>
                                            <div class="h4 fw-bold mb-1">28</div>
                                            <small class="text-muted">Follow-up</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center">
                                            <div class="metric-icon mx-auto mb-2">
                                                <i class="fas fa-video"></i>
                                            </div>
                                            <div class="h4 fw-bold mb-1">15</div>
                                            <small class="text-muted">Telemedicine</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const chamber = document.getElementById('chamberSelect');
            const date = document.getElementById('appointmentDate');
            const slots = document.getElementById('slotSelect');

            function loadSlots() {
                if (!chamber.value || !date.value) return;

                slots.innerHTML = '<option>Loading...</option>';

                fetch(`/chambers/${chamber.value}/slots/${date.value}`)
                    .then(res => res.json())
                    .then(data => {
                        slots.innerHTML = '';

                        if (!data.slots.length) {
                            slots.innerHTML = '<option>No slots available</option>';
                            return;
                        }

                        data.slots.forEach(slot => {
                            const option = document.createElement('option');
                            option.value = slot.start; // H:i:s
                            option.textContent = slot.label ?? slot.start;
                            slots.appendChild(option);
                        });
                    });
            }

            chamber.addEventListener('change', loadSlots);
            date.addEventListener('change', loadSlots);

        });
    </script>
<script>
document.getElementById('bookingForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerText = 'Booking...';

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        },
        body: new FormData(form)
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw data;
        return data;
    })
    .then(data => {

       const successHtml = `
<div class="alert alert-success">
    <strong>Appointment Booked!</strong><br>
    Appointment No: ${data.appointment.appointment_number}<br>
    Date: ${data.appointment.appointment_date.split('T')[0]}<br>
    Time: ${data.appointment.appointment_time}
</div>
`;

document.querySelector('.modal-body').innerHTML = successHtml;

setTimeout(() => {
window.location.reload();
 }, 2000);


    })
    .catch(error => {

        if (error.errors) {
            let messages = '';
            Object.values(error.errors).forEach(err => {
                messages += err[0] + '\n';
            });
            alert(messages);
        } else {
            alert(error.message || 'Something went wrong');
        }

    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerText = 'Book Appointment';
    });
});
</script>

@endsection
