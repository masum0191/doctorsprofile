@extends('layouts.admin')

@section('title', 'Patient Management')

@section('content')
    <style>
        :root {
            --primary: #318069;
            --primary-light: rgba(49, 128, 105, 0.1);
            --primary-dark: #2a6d5a;
            --primary-soft: rgba(49, 128, 105, 0.05);
            --primary-hover: rgba(49, 128, 105, 0.15);
            --primary-border: rgba(49, 128, 105, 0.15);
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --purple: #8b5cf6;
            --indigo: #6366f1;
        }

        /* Enhanced Cards */
        .dashboard-card {
            border: 1px solid var(--primary-border);
            border-radius: 12px;
            background: white;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .dashboard-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(49, 128, 105, 0.15);
            border-color: var(--primary);
        }

        .card-header-primary {
            background: var(--primary-soft);
            border-bottom: 2px solid var(--primary-light);
            font-weight: 600;
            padding: 1rem 1.25rem;
        }

        .card-header-primary h5 {
            font-size: 15px !important;
        }

        /* Stats Cards */
        .stats-card {
            border: 1px solid var(--primary-border);
            border-radius: 12px;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .stats-card-primary {
            border-left: 4px solid var(--primary);
            background: linear-gradient(135deg, #ffffff 0%, var(--primary-soft) 100%);
        }

        .stats-card-purple {
            border-left: 4px solid var(--purple);
            background: linear-gradient(135deg, #ffffff 0%, rgba(139, 92, 246, 0.05) 100%);
        }

        .stats-card-indigo {
            border-left: 4px solid var(--indigo);
            background: linear-gradient(135deg, #ffffff 0%, rgba(99, 102, 241, 0.05) 100%);
        }

        .stats-card-success {
            border-left: 4px solid var(--success);
            background: linear-gradient(135deg, #ffffff 0%, rgba(16, 185, 129, 0.05) 100%);
        }

        .stats-card-warning {
            border-left: 4px solid var(--warning);
            background: linear-gradient(135deg, #ffffff 0%, rgba(245, 158, 11, 0.05) 100%);
        }

        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(49, 128, 105, 0.15);
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

        .stats-icon-wrapper-purple {
            background: rgba(139, 92, 246, 0.1);
            color: var(--purple);
        }

        .stats-icon-wrapper-indigo {
            background: rgba(99, 102, 241, 0.1);
            color: var(--indigo);
        }

        .stats-icon-wrapper-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .stats-icon-wrapper-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        /* Active Filters Badge */
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background: var(--primary-soft);
            border-radius: 8px;
            border: 1px solid var(--primary-border);
        }

        .filter-badge {
            background: white;
            border: 1px solid var(--primary);
            color: var(--primary);
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-badge .close-btn {
            background: none;
            border: none;
            color: var(--primary);
            font-size: .9rem;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }

        .filter-badge .close-btn:hover {
            color: var(--primary-dark);
        }

        /* Table Styling */
        .patients-table-card {
            border: 1px solid var(--primary-border);
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

        /* Patient Avatar */
        .patient-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
            font-size: 1rem;
            margin-right: 0.75rem;
            box-shadow: 0 4px 10px rgba(49, 128, 105, 0.2);
        }

        .patient-avatar-indigo {
            background: linear-gradient(135deg, var(--indigo) 0%, #4f46e5 100%);
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.2);
        }

        .patient-avatar-purple {
            background: linear-gradient(135deg, var(--purple) 0%, #7c3aed 100%);
            box-shadow: 0 4px 10px rgba(139, 92, 246, 0.2);
        }

        /* Badges */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .badge-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #92400e;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .badge-confirmed {
            background: rgba(59, 130, 246, 0.1);
            color: #1e40af;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .badge-completed {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .badge-cancelled {
            background: rgba(239, 68, 68, 0.1);
            color: #991b1b;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* Action Buttons */
        .btn-primary-outline {
            border: 1px solid var(--primary);
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

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e5e7eb;
            background: white;
            color: #6b7280;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .action-btn:hover {
            background: var(--primary-soft);
            color: var(--primary);
            border-color: var(--primary);
        }

        /* Empty State */
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


        /* Progress Indicators */
        .progress-track {
            height: 6px;
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
        }

        /* Time Badge */
        .time-badge {
            background: var(--primary-light);
            color: var(--primary);
            padding: 0.2rem 0.65rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Search Box */
        .search-box {
            position: relative;
            max-width: 300px;
        }

        .search-box .form-control {
            padding-left: 2.5rem;
            border-radius: 10px;
            border: 1.5px solid var(--primary-border);
            transition: all 0.3s ease;
        }

        .search-box .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        }

        .search-box .search-icon {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        /* Patient ID Badge */
        .patient-id-badge {
            background: var(--primary-soft);
            color: var(--primary);
            padding: 0.2rem 0.65rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Contact Info */
        .contact-info {
            font-size: 0.8125rem;
        }

        .contact-info .icon {
            width: 20px;
            color: var(--primary);
            opacity: 0.7;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .patients-table {
                font-size: 0.875rem;
            }

            .stats-card {
                margin-bottom: 1rem;
            }

            .search-box {
                max-width: 100%;
            }
        }

        /* Quick Actions */
        .quick-action-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .quick-action-btn-prescription {
            background: rgba(139, 92, 246, 0.1);
            color: var(--purple);
            border-color: rgba(139, 92, 246, 0.2);
        }

        .quick-action-btn-prescription:hover {
            background: var(--purple);
            color: white;
            border-color: var(--purple);
        }

        .quick-action-btn-appointment {
            background: rgba(99, 102, 241, 0.1);
            color: var(--indigo);
            border-color: rgba(99, 102, 241, 0.2);
        }

        .quick-action-btn-appointment:hover {
            background: var(--indigo);
            color: white;
            border-color: var(--indigo);
        }
    </style>

    <div class="pb-3">
        {{-- HEADER --}}
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h4 fw-bold text-dark">Patient Management</h1>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <form method="GET" class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Search patients..."
                        class="form-control" />
                </form>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                    <i class="fas fa-plus me-2"></i> Add Patient
                </button>


                <a href="{{ url('admin/add-new-prescriptions') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Add Pascription
                </a>
            </div>
        </div>

        {{-- STATS CARDS --}}
        <div class="row mb-2">
            <div class="col-xl-3 col-md-4 col-6 mb-3">
                <div class="stats-card stats-card-primary">
                    <div class="p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="small mb-1">Total Patients</div>
                                <div class="h3 mb-0 fw-bold">{{ $stats['total'] ?? $patients->total() }}</div>
                                <div class="progress-track">
                                    <div class="progress-fill" style="width: 100%"></div>
                                </div>
                            </div>
                            <div class="stats-icon-wrapper">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-4 col-6 mb-3">
                <div class="stats-card stats-card-primary">
                    <div class="p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="small mb-1">Today's Visits</div>
                                <div class="h3 mb-0 fw-bold">{{ $stats['today'] ?? '0' }}</div>
                                <div class="progress-track">
                                    <div class="progress-fill" style="width: 60%"></div>
                                </div>
                            </div>
                            <div class="stats-icon-wrapper">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-4 col-6 mb-3">
                <div class="stats-card stats-card-primary">
                    <div class="p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="small mb-1">Completed</div>
                                <div class="h3 mb-0 fw-bold">{{ $stats['completed'] ?? '0' }}</div>
                                <div class="progress-track">
                                    <div class="progress-fill" style="width: 85%"></div>
                                </div>
                            </div>
                            <div class="stats-icon-wrapper">
                                <i class="fas fa-check-double"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-4 col-6 mb-3">
                <div class="stats-card stats-card-primary">
                    <div class="p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="small mb-1">Follow-ups</div>
                                <div class="h3 mb-0 fw-bold">{{ $stats['followup'] ?? '0' }}</div>
                                <div class="progress-track">
                                    <div class="progress-fill" style="width: 45%"></div>
                                </div>
                            </div>
                            <div class="stats-icon-wrapper">
                                <i class="fas fa-redo"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PATIENTS TABLE --}}
        <div class="patients-table-card">
            <div class="card-header-primary d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-user-injured me-2"></i>
                    Patients List
                </h5>
                @if ($patients->total())
                    <span class="badge bg-primary rounded-pill">
                        {{ $patients->total() }} patients
                    </span>
                @endif
            </div>

            <div class="card-body p-0">
                @if ($patients->count() > 0)
                    <div class="table-responsive scrollbar-primary" style="max-height: 600px; overflow-y: auto;">
                        <table class="table patients-table">
                            <thead>
                                <tr>
                                    <th class="ps-4">PATIENT</th>
                                    <th>CONTACT INFO</th>
                                    <th>LAST VISIT</th>
                                    <th>STATUS</th>
                                    <th>VISITS</th>
                                    <th class="text-end pe-4">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($patients as $patient)
                                    @php
                                        $lastAppt = $patient->patientAppointments->first();
                                        $avatarColors = ['primary', 'indigo', 'purple'];
                                        $avatarColor = $avatarColors[$loop->index % count($avatarColors)];
                                    @endphp
                                    <tr>
                                        {{-- Patient Column --}}
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <!-- <div class="patient-avatar patient-avatar-{{ $avatarColor }}">
                                                                {{ strtoupper(substr($patient->name, 0, 1)) }}
                                                            </div> -->
                                                <div>
                                                    <div class="fw-semibold">
                                                        {{ $patient->name }}
                                                    </div>
                                                    <div class="patient-id-badge">

                                                        ID: {{ $patient->id }}
                                                    </div>
                                                    <small class="text-muted d-block mt-1">
                                                        {{ optional($patient->created_at)->format('M d, Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Contact Info Column --}}
                                        <td>
                                            <div class="contact-info">
                                                @if ($patient->mobile)
                                                    <div class="d-flex align-items-center mb-1">
                                                        <i class="fas fa-phone-alt icon me-2"></i>
                                                        <span>{{ $patient->mobile }}</span>
                                                    </div>
                                                @endif
                                                @if ($patient->email)
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-envelope icon me-2"></i>
                                                        <span class="text-truncate" style="max-width: 180px;">
                                                            {{ $patient->email }}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if (!$patient->mobile && !$patient->email)
                                                    <span class="text-muted small">No contact info</span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Last Visit Column --}}
                                        <td>
                                            @if ($lastAppt)
                                                <div>
                                                    <div class="fw-semibold">
                                                        {{ optional($lastAppt->appointment_date)->format('M d, Y') }}
                                                    </div>
                                                    <span class="time-badge mt-2">
                                                        <i class="fas fa-clock"></i>
                                                        {{ \Carbon\Carbon::parse($lastAppt->appointment_time)->format('h:i A') }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-muted small">No visits yet</span>
                                            @endif
                                        </td>

                                        {{-- Status Column --}}
                                        <td>
                                            @if ($lastAppt)
                                                @php
                                                    $status = $lastAppt->status;
                                                    $map = [
                                                        'pending' => [
                                                            'class' => 'badge-pending',
                                                            'icon' => 'fas fa-clock',
                                                        ],
                                                        'confirmed' => [
                                                            'class' => 'badge-confirmed',
                                                            'icon' => 'fas fa-check-circle',
                                                        ],
                                                        'completed' => [
                                                            'class' => 'badge-completed',
                                                            'icon' => 'fas fa-check-double',
                                                        ],
                                                        'cancelled' => [
                                                            'class' => 'badge-cancelled',
                                                            'icon' => 'fas fa-times-circle',
                                                        ],
                                                    ];
                                                    $cfg = $map[$status] ?? [
                                                        'class' => 'badge-light',
                                                        'icon' => 'fas fa-question-circle',
                                                    ];
                                                @endphp
                                                <span class="status-badge {{ $cfg['class'] }}">
                                                    <i class="{{ $cfg['icon'] }}"></i>
                                                    {{ ucfirst($status) }}
                                                </span>
                                            @else
                                                <span class="status-badge badge-pending">
                                                    <i class="fas fa-user-clock"></i>
                                                    New Patient
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Visits Column --}}
                                        <td>
                                            <div class="text-center">
                                                <div class="h4 mb-0 fw-bold text-primary">
                                                    {{ $patient->total_appointments ?? 0 }}
                                                </div>
                                                <small class="text-muted">visits</small>
                                            </div>
                                        </td>

                                        {{-- Actions Column --}}
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                {{-- <a href="{{ route('admin.patients.prescriptions.create', $patient->id) }}"
                                               class="quick-action-btn quick-action-btn-prescription"
                                               title="Add Prescription">
                                                Prescription
                                            </a>

                                            <a href="#"
                                               class="quick-action-btn quick-action-btn-appointment"
                                               title="New Appointment">
                                                Appointment
                                            </a> --}}

                                                <div class="dropdown">
                                                    <button class="action-btn dropdown-toggle" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false"
                                                        title="More Actions">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.patients.profile', $patient->id) }}">
                                                                <i class="fas fa-eye text-primary me-2"></i> View Profile
                                                            </a>

                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.patients.history', $patient->id) }}">
                                                                <i class="fas fa-history text-info me-2"></i> View History
                                                            </a>
                                                        </li>
                                                        {{-- <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.patients.records', $patient->id) }}">
                                                                <i class="fas fa-notes-medical text-success me-2"></i>
                                                                Medical Records
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a class="dropdown-item" href="#">
                                                                <i
                                                                    class="fas fa-prescription-bottle-alt text-purple me-2"></i>
                                                                Prescriptions History
                                                            </a>
                                                        </li> --}}
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editPatientModal{{ $patient->id }}">
                                                                <i class="fas fa-edit text-warning me-2"></i>
                                                                Edit Patient
                                                            </a>

                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="editPatientModal{{ $patient->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.patients.update', $patient->id) }}">
    @csrf
    @method('PUT')

    <div class="modal-header">
        <h5 class="modal-title">Edit Patient</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">
        <div class="row g-3">

            {{-- Basic Info --}}
            <div class="col-md-6">
                <label class="form-label">Patient Name *</label>
                <input type="text" name="name" class="form-control"
                       value="{{ $patient->name }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Age</label>
                <input type="text" name="age" class="form-control"
                       value="{{ $patient->age }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Mobile *</label>
                <input type="text" name="mobile" class="form-control"
                       value="{{ $patient->mobile }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ $patient->email }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Gender *</label>
                <select name="gender" class="form-select" required>
                    <option value="">Select</option>
                    <option value="male" {{ $patient->gender === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $patient->gender === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ $patient->gender === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Address *</label>
                <input type="text" name="address" class="form-control"
                       value="{{ $patient->address }}" required>
            </div>

            {{-- Vitality --}}
            <div class="col-md-12">
                <label class="form-label">Vitality</label>
                <input type="text" name="vitality" class="form-control"
                       value="{{ $patient->vitality }}">
            </div>

            {{-- Basic Details --}}
            <div class="col-md-4">
                <label class="form-label">Blood Group</label>
                <input type="text" name="basic_details[blood_group]" class="form-control"
                       value="{{ $patient->basic_details['blood_group'] ?? '' }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Height (cm)</label>
                <input type="number" step="0.01" name="basic_details[height]" class="form-control"
                       value="{{ $patient->basic_details['height'] ?? '' }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Weight (kg)</label>
                <input type="number" step="0.01" name="basic_details[weight]" class="form-control"
                       value="{{ $patient->basic_details['weight'] ?? '' }}">
            </div>

            {{-- Emergency Contact --}}
            <div class="col-md-12">
                <hr>
                <h6>Emergency Contact</h6>
            </div>

            <div class="col-md-4">
                <label class="form-label">Name</label>
                <input type="text" name="emergency_contact[name]" class="form-control"
                       value="{{ $patient->emergency_contact['name'] ?? '' }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Relationship</label>
                <input type="text" name="emergency_contact[relationship]" class="form-control"
                       value="{{ $patient->emergency_contact['relationship'] ?? '' }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Contact</label>
                <input type="text" name="emergency_contact[contact]" class="form-control"
                       value="{{ $patient->emergency_contact['contact'] ?? '' }}">
            </div>

            {{-- Medical History --}}
            <div class="col-md-12">
                <label class="form-label">Medical History</label>
                <textarea name="medical_history" class="form-control" rows="3">{{ $patient->medical_history }}</textarea>
            </div>

        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Update Patient</button>
    </div>
</form>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    @if ($patients->hasPages())
                        <div class="card-header-primary border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Showing {{ $patients->firstItem() }} to {{ $patients->lastItem() }} of
                                    {{ $patients->total() }} patients
                                </div>
                                <div>
                                    {{ $patients->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-user-injured"></i>
                        </div>
                        <h6 class="fw-semibold mb-2">No Patients Found</h6>
                        <p class="text-muted mb-3">
                            @if (request()->has('q'))
                                No patients match your search criteria. Try a different search term.
                            @else
                                There are no patients in the system yet. Add your first patient!
                            @endif
                        </p>
                        @if (request()->has('q'))
                            <a href="{{ route('admin.patients.index') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-times me-2"></i> Clear Search
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="addPatientModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.patients.store') }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Add Patient</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">

                            {{-- Basic Info --}}
                            <div class="col-md-6">
                                <label class="form-label">Patient Name *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Age</label>
                                <input type="text" name="age" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Mobile *</label>
                                <input type="text" name="mobile" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Gender *</label>
                                <select name="gender" class="form-select" required>
                                    <option value="">Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Address *</label>
                                <input type="text" name="address" class="form-control" required>
                            </div>

                            {{-- Vitality --}}
                            <div class="col-md-12">
                                <label class="form-label">Vitality</label>
                                <input type="text" name="vitality" class="form-control">
                            </div>

                            {{-- Basic Details --}}
                            <div class="col-md-4">
                                <label class="form-label">Blood Group</label>
                                <input type="text" name="basic_details[blood_group]" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Height (cm)</label>
                                <input type="number" step="0.01" name="basic_details[height]" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Weight (kg)</label>
                                <input type="number" step="0.01" name="basic_details[weight]" class="form-control">
                            </div>

                            {{-- Emergency Contact --}}
                            <div class="col-md-12">
                                <hr>
                                <h6>Emergency Contact</h6>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Name</label>
                                <input type="text" name="emergency_contact[name]" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Relationship</label>
                                <input type="text" name="emergency_contact[relationship]" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Contact</label>
                                <input type="text" name="emergency_contact[contact]" class="form-control">
                            </div>

                            {{-- Medical History --}}
                            <div class="col-md-12">
                                <label class="form-label">Medical History</label>
                                <textarea name="medical_history" class="form-control" rows="3"></textarea>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Patient</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Progress bar animations
            setTimeout(() => {
                const progressBars = document.querySelectorAll('.progress-fill');
                progressBars.forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 100);
                });
            }, 300);

            // Search form submission with loading indicator
            const searchForm = document.querySelector('.search-box form');
            if (searchForm) {
                const searchInput = searchForm.querySelector('input[name="q"]');
                const searchIcon = searchForm.querySelector('.search-icon');

                searchForm.addEventListener('submit', function(e) {
                    if (searchInput.value.trim()) {
                        searchIcon.className = 'search-icon fas fa-spinner fa-spin';
                    }
                });

                // Reset icon when input is cleared
                searchInput.addEventListener('input', function() {
                    if (!this.value.trim()) {
                        searchIcon.className = 'search-icon fas fa-search';
                    }
                });
            }

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
