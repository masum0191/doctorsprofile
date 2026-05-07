@extends('layouts.admin')

@section('title', 'Appointment Management')

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

    .stats-card:hover {
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
    .appointments-table-card {
        border: 1px solid var(--primary-border);
        border-radius: 12px;
        background: white;
        overflow: hidden;
    }

    .appointments-table {
        --bs-table-bg: transparent;
        margin-bottom: 0;
    }

    .appointments-table thead th {
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

    .appointments-table tbody tr {
        border-bottom: 1px solid var(--primary-soft);
        transition: all 0.2s ease;
    }

    .appointments-table tbody tr:hover {
        background: var(--primary-soft);
    }

    .appointments-table tbody tr:last-child {
        border-bottom: none;
    }

    .appointments-table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }

    /* Patient Avatar */
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

    /* Badges */
    .badge{
        padding: 5px 10px;
    }
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

    .action-btn-group {
        display: flex;
        justify-content : end;
        gap: 0.5rem;
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

    /* Modal Enhancement */
    .primary-modal .modal-content {
        border-radius: 12px;
        border: 1px solid var(--primary-border);
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

    /* Responsive */
    @media (max-width: 768px) {
        .appointments-table {
            font-size: 0.875rem;
        }

        .action-btn-group {
            flex-wrap: wrap;
        }
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
        margin-top : 5px;
    }

    /* Quick Filter Styles */
    .filter-btn {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .filter-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }

    .filter-btn .badge {
        background: white;
        color: var(--primary);
        font-size: 0.75rem;
    }

    /* Filter Modal Styles */
    .filter-group {
        position: relative;
        margin-bottom: 1rem;
    }

    .filter-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .filter-select {
        width: 100%;
        padding: 0.625rem 1rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.875rem;
        background: white;
        transition: all 0.2s ease;
    }

    .filter-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    .date-range-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
</style>

<div class="pb-3">
    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h4 fw-bold text-dark">Appointment Management</h1>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="filter-btn" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter me-2"></i> Filter
                @if(request()->hasAny(['status', 'date_range', 'type', 'chamber', 'search', 'from_date', 'to_date']))
                    <span class="badge rounded-pill">{{ $activeFilterCount ?? 0 }}</span>
                @endif
            </button>
            <a href="{{ route('admin.appointments.calendar') }}" class="btn btn-primary-outline">
                <i class="fas fa-calendar-alt me-2"></i> Calendar View
            </a>
            <a href="{{ route('admin.appointment.today') }}" class="btn btn-primary">
                <i class="fas fa-calendar-day me-2"></i> Today's Appointments
            </a>
        </div>
    </div>

    {{-- ACTIVE FILTERS DISPLAY --}}
    @php
        $activeFilters = [];
        if(request('status') && request('status') != 'all') $activeFilters[] = ['name' => 'Status', 'value' => ucfirst(request('status')), 'key' => 'status'];
        if(request('type')) $activeFilters[] = ['name' => 'Type', 'value' => ucfirst(request('type')), 'key' => 'type'];
        if(request('chamber') && isset($chambers)) {
            $chamberName = $chambers->where('id', request('chamber'))->first()->name ?? request('chamber');
            $activeFilters[] = ['name' => 'Chamber', 'value' => $chamberName, 'key' => 'chamber'];
        }
        if(request('search')) $activeFilters[] = ['name' => 'Search', 'value' => request('search'), 'key' => 'search'];
        if(request('from_date') || request('to_date')) {
            $dateRange = '';
            if(request('from_date')) $dateRange .= 'From: ' . request('from_date');
            if(request('to_date')) $dateRange .= ($dateRange ? ' - ' : '') . 'To: ' . request('to_date');
            $activeFilters[] = ['name' => 'Date Range', 'value' => $dateRange, 'key' => 'date_range'];
        }
    @endphp

    @if(count($activeFilters) > 0)
        <div class="active-filters mb-4">
            <div class="d-flex align-items-center justify-content-between w-100">
                <div class="d-flex align-items-center gap-2">
                    <small class="text-muted">Active Filters:</small>
                </div>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-sm btn-primary-outline">
                    <i class="fas fa-times me-1"></i> Clear All
                </a>
            </div>
            <div class="d-flex flex-wrap gap-2 mt-2">
                @foreach($activeFilters as $filter)
                    <span class="filter-badge">
                        {{ $filter['name'] }}: {{ $filter['value'] }}
                       <a href="{{ route('admin.appointments.index', request()->except($filter['key'])) }}"
   class="close-btn ms-1" title="Remove filter">
    <i class="fas fa-times"></i>
</a>
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- STATS CARDS --}}
    <div class="row mb-2">
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="stats-card stats-card-primary">
                <div class="p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small mb-1">Total</div>
                            <div class="h3 mb-0 fw-bold">{{ $stats['total'] }}</div>
                            <div class="progress-track">
                                <div class="progress-fill" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="stats-card stats-card-primary">
                <div class="p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small mb-1">Pending</div>
                            <div class="h3 mb-0 fw-bold">{{ $stats['pending'] }}</div>
                            <div class="progress-track">
                                <div class="progress-fill"
                                     style="width: {{ $stats['total'] ? ($stats['pending'] / $stats['total']) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="stats-card stats-card-primary">
                <div class="p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small mb-1">Confirmed</div>
                            <div class="h3 mb-0 fw-bold">{{ $stats['confirmed'] }}</div>
                            <div class="progress-track">
                                <div class="progress-fill"
                                     style="width: {{ $stats['total'] ? ($stats['confirmed'] / $stats['total']) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="stats-card stats-card-primary">
                <div class="p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small mb-1">Completed</div>
                            <div class="h3 mb-0 fw-bold">{{ $stats['completed'] }}</div>
                            <div class="progress-track">
                                <div class="progress-fill"
                                     style="width: {{ $stats['total'] ? ($stats['completed'] / $stats['total']) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="stats-card stats-card-primary">
                <div class="p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small mb-1">Cancelled</div>
                            <div class="h3 mb-0 fw-bold">{{ $stats['cancelled'] }}</div>
                            <div class="progress-track">
                                <div class="progress-fill"
                                     style="width: {{ $stats['total'] ? ($stats['cancelled'] / $stats['total']) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="stats-card stats-card-primary">
                <div class="p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small mb-1">Today</div>
                            <div class="h3 mb-0 fw-bold">{{ $stats['today'] }}</div>
                            <div class="progress-track">
                                <div class="progress-fill"
                                     style="width: {{ $stats['today'] > 0 ? 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- APPOINTMENTS TABLE --}}
    <div class="appointments-table-card">
        <div class="card-header-primary d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-calendar-alt me-2"></i>
                Appointments List
            </h5>
            @if($appointments->total())
                <span class="badge bg-primary rounded-pill">
                    {{ $appointments->total() }} appointments
                </span>
            @endif
        </div>

        <div class="card-body p-0">
            @if($appointments->count() > 0)
                <div class="table-responsive scrollbar-primary" style="max-height: 600px; overflow-y: auto;">
                    <table class="table appointments-table">
                        <thead>
                            <tr>
                                <th class="ps-4">PATIENT</th>
                                <th>DATE & TIME</th>
                                <th>TYPE</th>
                                <th>CHAMBER</th>
                                <th>FEES</th>
                                <th>STATUS</th>
                                <th class="text-end pe-4">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                                <tr>
                                    {{-- Patient Column --}}
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <div class="fw-semibold">
                                                    {{ $appointment->patient_first_name }} {{ $appointment->patient_last_name }}
                                                </div>
                                                <small class="text-muted">
                                                    ID: {{ $appointment->appointment_number ?? $appointment->id }}
                                                </small>
                                                <div class="text-muted small mt-1">
                                                    Call: {{ $appointment->patient_phone }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Date & Time Column --}}
                                    <td>
                                        <div>
                                            <div class="fw-semibold">
                                                {{ $appointment->appointment_date->format('M d, Y') }}
                                            </div>
                                            <span class="time-badge">
                                                <i class="fas fa-clock"></i>
                                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Type Column --}}
                                    <td>
                                        <span class="badge rounded-pill {{ $appointment->consultation_type == 'online' ? 'bg-info-subtle text-info' : 'bg-warning-subtle text-warning' }}">
                                            <i class="fas {{ $appointment->consultation_type == 'online' ? 'fa-video' : 'fa-user-md' }} me-1"></i>
                                            {{ ucfirst($appointment->consultation_type) }}
                                        </span>
                                    </td>

                                    {{-- Chamber Column --}}
                                    <td>
                                        @if($appointment->chamber)
                                            <span class="badge bg-light text-dark border rounded-pill">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ \Illuminate\Support\Str::limit($appointment->chamber->name, 15) }}
                                            </span>
                                        @else
                                            <span class="text-muted small">N/A</span>
                                        @endif
                                    </td>

                                    {{-- Fees Column --}}
                                    <td>
                                        @if($appointment->amount)
                                            <span class="fw-semibold text-success">
                                                ৳{{ number_format($appointment->amount, 2) }}
                                            </span>
                                        @else
                                            <span class="text-muted small">N/A</span>
                                        @endif
                                    </td>

                                    {{-- Status Column --}}
                                    <td>
                                        @php
                                            $status = $appointment->status;
                                            $map = [
                                                'pending' => ['class' => 'badge-pending', 'icon' => 'fas fa-clock'],
                                                'confirmed' => ['class' => 'badge-confirmed', 'icon' => 'fas fa-check-circle'],
                                                'completed' => ['class' => 'badge-completed', 'icon' => 'fas fa-check-double'],
                                                'cancelled' => ['class' => 'badge-cancelled', 'icon' => 'fas fa-times-circle'],
                                            ];
                                            $cfg = $map[$status] ?? ['class' => 'badge-light', 'icon' => 'fas fa-question-circle'];
                                        @endphp
                                        <span class="status-badge {{ $cfg['class'] }}">
                                            <i class="{{ $cfg['icon'] }}"></i>
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>

                                    {{-- Actions Column --}}
                                    <td class="text-end pe-4">
                                        <div class="action-btn-group">
                                            <a href="{{ route('admin.appointments.show', $appointment) }}"
                                               class="action-btn"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if(in_array($appointment->status, ['pending', 'confirmed']))
                                                <button class="action-btn"
                                                        onclick="showRescheduleModal({{ $appointment->id }})"
                                                        title="Reschedule">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </button>

                                                <div class="dropdown">
                                                    <button class="action-btn dropdown-toggle"
                                                            type="button"
                                                            data-bs-toggle="dropdown"
                                                            aria-expanded="false"
                                                            title="More Actions">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                                        <li>
                                                            <a class="dropdown-item" href="#"
                                                               onclick="updateStatus({{ $appointment->id }}, 'confirmed')">
                                                                <i class="fas fa-check text-success me-2"></i>
                                                                Confirm Appointment
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#"
                                                               onclick="updateStatus({{ $appointment->id }}, 'completed')">
                                                                <i class="fas fa-check-double text-primary me-2"></i>
                                                                Mark as Completed
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#"
                                                               onclick="updateStatus({{ $appointment->id }}, 'cancelled')">
                                                                <i class="fas fa-times me-2"></i>
                                                                Cancel Appointment
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($appointments->hasPages())
                    <div class="card-header-primary border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $appointments->firstItem() }} to {{ $appointments->lastItem() }} of {{ $appointments->total() }} appointments
                            </div>
                            <div>
                                {{ $appointments->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h6 class="fw-semibold mb-2">No Appointments Found</h6>
                    <p class="text-muted mb-3">
                        @if(request()->hasAny(['status', 'date_range', 'type', 'chamber', 'search']))
                            Try adjusting your filters or search criteria.
                        @else
                            There are no appointments scheduled yet.
                        @endif
                    </p>
                    @if(request()->hasAny(['status', 'date_range', 'type', 'chamber', 'search']))
                        <a href="{{ route('admin.appointments.index') }}"
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-times me-2"></i> Clear Filters
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

{{-- FILTER MODAL --}}
<div class="modal fade primary-modal" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-filter me-2"></i>
                    Filter Appointments
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('admin.appointments.index') }}" id="filterForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="filter-group">
                                <label class="filter-label">Status</label>
                                <select name="status" class="filter-select">
                                    <option value="all">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="filter-group">
                                <label class="filter-label">Date Range</label>
                                <select name="date_range" class="filter-select" id="date-range-select">
                                    <option value="">All Dates</option>
                                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                                    <option value="tomorrow" {{ request('date_range') == 'tomorrow' ? 'selected' : '' }}>Tomorrow</option>
                                    <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>This Week</option>
                                    <option value="next_week" {{ request('date_range') == 'next_week' ? 'selected' : '' }}>Next Week</option>
                                    <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                    <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="filter-group">
                                <label class="filter-label">Consultation Type</label>
                                <select name="type" class="filter-select">
                                    <option value="">All Types</option>
                                    <option value="online" {{ request('type') == 'online' ? 'selected' : '' }}>Online</option>
                                    <option value="in_person" {{ request('type') == 'in_person' ? 'selected' : '' }}>In Person</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="filter-group">
                                <label class="filter-label">Chamber</label>
                                <select name="chamber" class="filter-select">
                                    <option value="">All Chambers</option>
                                    @foreach($chambers ?? [] as $chamber)
                                        <option value="{{ $chamber->id }}" {{ request('chamber') == $chamber->id ? 'selected' : '' }}>
                                            {{ $chamber->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="filter-group">
                                <label class="filter-label">Search</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text"
                                           name="search"
                                           class="form-control border-start-0"
                                           placeholder="Search by patient name, phone, email or appointment ID..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="filter-group">
                                <label class="filter-label">Custom Date Range</label>
                                <div class="date-range-group">
                                    <div>
                                        <label class="filter-label">From Date</label>
                                        <input type="date"
                                               name="from_date"
                                               class="filter-select"
                                               value="{{ request('from_date') }}"
                                               id="from-date">
                                    </div>
                                    <div>
                                        <label class="filter-label">To Date</label>
                                        <input type="date"
                                               name="to_date"
                                               class="filter-select"
                                               value="{{ request('to_date') }}"
                                               id="to-date">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary-outline" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-primary-outline">
                        Clear All
                    </a>
                    <button type="submit" class="btn btn-primary" id="applyFilterBtn">
                        <i class="fas fa-filter me-2"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- RESCHEDULE MODAL --}}
<div class="modal fade primary-modal" id="rescheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-alt me-2"></i> Reschedule Appointment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rescheduleForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="appointment_id" id="reschedule_appointment_id">

                    <div class="alert alert-info bg-light border-info mb-3">
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
                            <label class="form-label fw-semibold">Reason for Rescheduling</label>
                            <textarea class="form-control border-primary"
                                      id="reason"
                                      name="reason"
                                      rows="3"
                                      placeholder="Optional: Provide a reason for rescheduling..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary-outline" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-calendar-check me-2"></i> Reschedule Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Date range handler for filter modal
    const dateRangeSelect = document.getElementById('date-range-select');
    const fromDateInput = document.getElementById('from-date');
    const toDateInput = document.getElementById('to-date');

    if (dateRangeSelect) {
        dateRangeSelect.addEventListener('change', function() {
            const today = new Date();
            let fromDate = '', toDate = '';

            switch(this.value) {
                case 'today':
                    fromDate = today.toISOString().split('T')[0];
                    toDate = fromDate;
                    break;
                case 'tomorrow':
                    const tomorrow = new Date(today);
                    tomorrow.setDate(today.getDate() + 1);
                    fromDate = tomorrow.toISOString().split('T')[0];
                    toDate = fromDate;
                    break;
                case 'this_week':
                    const firstDay = new Date(today.setDate(today.getDate() - today.getDay()));
                    const lastDay = new Date(firstDay);
                    lastDay.setDate(firstDay.getDate() + 6);
                    fromDate = firstDay.toISOString().split('T')[0];
                    toDate = lastDay.toISOString().split('T')[0];
                    break;
                case 'next_week':
                    const nextWeekStart = new Date(today);
                    nextWeekStart.setDate(today.getDate() + (7 - today.getDay()));
                    const nextWeekEnd = new Date(nextWeekStart);
                    nextWeekEnd.setDate(nextWeekStart.getDate() + 6);
                    fromDate = nextWeekStart.toISOString().split('T')[0];
                    toDate = nextWeekEnd.toISOString().split('T')[0];
                    break;
                case 'this_month':
                    fromDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                    toDate = new Date(today.getFullYear(), today.getMonth() + 1, 0).toISOString().split('T')[0];
                    break;
                case 'custom':
                    // Leave empty for custom input
                    break;
            }

            if (fromDateInput) fromDateInput.value = fromDate;
            if (toDateInput) toDateInput.value = toDate;
        });

        // Trigger change if date_range is already set
        if (dateRangeSelect.value) {
            dateRangeSelect.dispatchEvent(new Event('change'));
        }
    }

    // Filter form submission with modal close
    const filterForm = document.getElementById('filterForm');
    const applyFilterBtn = document.getElementById('applyFilterBtn');

    if (filterForm && applyFilterBtn) {
        applyFilterBtn.addEventListener('click', function(e) {
            e.preventDefault();

            // Get the modal instance
            const filterModal = bootstrap.Modal.getInstance(document.getElementById('filterModal'));

            // Submit the form
            filterForm.submit();

            // Close the modal
            if (filterModal) {
                filterModal.hide();
            }
        });
    }

    // Reschedule modal
    window.showRescheduleModal = function(appointmentId) {
        const modal = new bootstrap.Modal(document.getElementById('rescheduleModal'));
        document.getElementById('reschedule_appointment_id').value = appointmentId;

        // Set minimum date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('appointment_date').min = tomorrow.toISOString().split('T')[0];

        modal.show();
    };

    // Status update function
    window.updateStatus = function(appointmentId, status) {
        const statusText = status.charAt(0).toUpperCase() + status.slice(1);

        Swal.fire({
            title: `Change to ${statusText}?`,
            text: 'Are you sure you want to update the appointment status?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#318069',
            cancelButtonColor: '#6b7280',
            confirmButtonText: `Yes, mark as ${statusText}`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (!result.isConfirmed) return;

            fetch(`/admin/appointments/${appointmentId}/status`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: data.message,
                        confirmButtonColor: '#318069'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to update status',
                        confirmButtonColor: '#ef4444'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update status',
                    confirmButtonColor: '#ef4444'
                });
            });
        });
    };

    // Reschedule form submission
    const rescheduleForm = document.getElementById('rescheduleForm');
    if (rescheduleForm) {
        rescheduleForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const appointmentId = document.getElementById('reschedule_appointment_id').value;
            const formData = new FormData(this);

            fetch(`/admin/appointments/${appointmentId}/reschedule`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('rescheduleModal'));
                    modal.hide();

                    Swal.fire({
                        icon: 'success',
                        title: 'Rescheduled!',
                        text: data.message,
                        confirmButtonColor: '#318069'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to reschedule appointment',
                        confirmButtonColor: '#ef4444'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to reschedule appointment',
                    confirmButtonColor: '#ef4444'
                });
            });
        });
    }

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

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
