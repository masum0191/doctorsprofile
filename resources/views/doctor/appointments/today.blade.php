@extends('layouts.admin')

@section('title', "Today's Appointments")

@section('content')
<style>
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.08);
        --primary-dark: #2a6d5a;
        --primary-soft: rgba(49, 128, 105, 0.04);
        --primary-border: rgba(49, 128, 105, 0.15);
        --silver-bg: #f8fafc;
        --silver-dark: #f1f5f9;
        --silver-border: #e2e8f0;
        --card-bg: #ffffff;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --success: #10b981;
        --warning: #f59e0b;
        --info: #3b82f6;
        --danger: #ef4444;
    }

    /* Professional Header */
    .page-header {
        margin-bottom: 1.5rem;
    }

    .page-header .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .page-subtitle {
        color: var(--text-secondary);
        font-size: 0.95rem;
        margin: 0;
    }

    /* Time Banner */
    .time-banner {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .time-banner::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
        border-radius: 50%;
        transform: translate(40px, -40px);
    }

    .time-display {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .current-time {
        font-size: 2rem;
        font-weight: 700;
        color: white;
        line-height: 1;
        margin: 0;
    }

    .current-date {
        color: rgba(255,255,255,0.85);
        font-size: 0.95rem;
        margin: 0.25rem 0 0 0;
    }

    .live-indicator {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .live-dot {
        width: 8px;
        height: 8px;
        background: #22c55e;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    .stats-badges {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .stat-badge {
        background: rgba(255,255,255,0.15);
        color: white;
        border: 1px solid rgba(255,255,255,0.3);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--card-bg);
        border-radius: 12px;
        border: 1px solid var(--silver-border);
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }

    .stat-card.pending {
        border-left: 4px solid var(--warning);
    }

    .stat-card.confirmed {
        border-left: 4px solid var(--info);
    }

    .stat-card.completed {
        border-left: 4px solid var(--success);
    }

    .stat-card.total {
        border-left: 4px solid var(--primary);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .stat-icon {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .stat-card.pending .stat-icon {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .stat-card.confirmed .stat-icon {
        background: rgba(59, 130, 246, 0.1);
        color: var(--info);
    }

    .stat-card.completed .stat-icon {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .stat-card.total .stat-icon {
        background: var(--primary-soft);
        color: var(--primary);
    }

    .progress-track {
        height: 6px;
        background: var(--silver-bg);
        border-radius: 3px;
        overflow: hidden;
        margin-top: 1rem;
    }

    .progress-bar {
        height: 100%;
        border-radius: 3px;
        transition: width 1s ease;
    }

    .stat-card.pending .progress-bar {
        background: var(--warning);
    }

    .stat-card.confirmed .progress-bar {
        background: var(--info);
    }

    .stat-card.completed .progress-bar {
        background: var(--success);
    }

    .stat-card.total .progress-bar {
        background: var(--primary);
    }

    /* Main Content Layout */
    .content-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
    }

    @media (max-width: 992px) {
        .content-layout {
            grid-template-columns: 1fr;
        }
    }

    /* Schedule Card */
    .schedule-card {
        background: var(--card-bg);
        border-radius: 12px;
        border: 1px solid var(--silver-border);
        overflow: hidden;
    }

    .schedule-header {
        background: var(--silver-bg);
        border-bottom: 1px solid var(--silver-border);
        padding: 1.25rem 1.5rem;
    }

    .schedule-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 0;
    }

    .schedule-title h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .schedule-title h3 i {
        color: var(--primary);
    }

    .schedule-count {
        background: var(--primary-soft);
        color: var(--primary);
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        gap: 1.5rem;
    }

    /* Appointment Table */
    .appointment-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .appointment-table thead th {
        background: var(--silver-bg);
        padding: 1rem 1.25rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--silver-border);
        text-align: left;
    }

    .appointment-table thead th:first-child {
        padding-left: 1.5rem;
        border-radius: 12px 0 0 0;
    }

    .appointment-table thead th:last-child {
        padding-right: 1.5rem;
        border-radius: 0 12px 0 0;
    }

    .appointment-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid var(--silver-border);
    }

    .appointment-table tbody tr:last-child {
        border-bottom: none;
    }

    .appointment-table tbody tr:hover {
        background: var(--primary-soft);
    }

    .appointment-table tbody td {
        padding: 1.25rem 1.25rem;
        vertical-align: middle;
    }

    .appointment-table tbody td:first-child {
        padding-left: 1.5rem;
    }

    .appointment-table tbody td:last-child {
        padding-right: 1.5rem;
    }

    /* Time Cell */
    .time-cell {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .time-indicator {
        width: 4px;
        height: 40px;
        background: var(--silver-border);
        border-radius: 2px;
        position: relative;
    }

    .time-indicator.upcoming {
        background: linear-gradient(to bottom, var(--info), var(--info-light));
    }

    .time-indicator.ongoing {
        background: linear-gradient(to bottom, var(--success), var(--success-light));
    }

    .time-indicator.passed {
        background: var(--silver-border);
    }

    .time-display-group {
        flex: 1;
    }

    .appointment-time {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .time-status {
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin-top: 0.25rem;
    }

    /* Patient Cell */
    .patient-cell {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .patient-avatar {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.95rem;
        flex-shrink: 0;
    }

    .patient-info {
        flex: 1;
    }

    .patient-name {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .patient-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    /* Status Badges */
    .status-badge {
        padding: 0.4rem 0.85rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .status-confirmed {
        background: rgba(59, 130, 246, 0.1);
        color: var(--info);
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .status-completed {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    /* Type Badge */
    .type-badge {
        padding: 0.4rem 0.85rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        background: var(--silver-bg);
        color: var(--text-secondary);
    }

    .type-online {
        background: rgba(59, 130, 246, 0.1);
        color: var(--info);
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    /* Action Buttons */
    .action-group {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--silver-bg);
        color: var(--text-secondary);
        border: 1px solid var(--silver-border);
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        transform: translateY(-1px);
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
        background: var(--silver-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: var(--text-muted);
        font-size: 2rem;
    }

    /* Sidebar Cards */
    .sidebar-card {
        background: var(--card-bg);
        border-radius: 12px;
        border: 1px solid var(--silver-border);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .sidebar-card-header {
        background: var(--silver-bg);
        border-bottom: 1px solid var(--silver-border);
        padding: 1.25rem 1.5rem;
    }

    .sidebar-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .sidebar-card-title i {
        color: var(--primary);
    }

    /* Quick Actions */
   
    /* Next Appointments */
    .next-appointments-list {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .next-appointment-item {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--silver-border);
        transition: all 0.2s ease;
    }

    .next-appointment-item:hover {
        background: var(--primary-soft);
    }

    .next-appointment-item:last-child {
        border-bottom: none;
    }

    .next-appointment-time {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .next-appointment-patient {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .next-appointment-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    /* Progress Chart */
    .progress-chart-container {
        padding: 1.5rem;
        text-align: center;
    }

    .progress-chart {
        width: 120px;
        height: 120px;
        margin: 0 auto 1.5rem;
        position: relative;
    }

    .progress-chart-circle {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: conic-gradient(var(--primary) 0% var(--percentage, 50%), var(--silver-bg) var(--percentage, 50%) 100%);
        position: relative;
    }

    .progress-chart-circle::before {
        content: '';
        position: absolute;
        top: 8px;
        left: 8px;
        right: 8px;
        bottom: 8px;
        background: var(--card-bg);
        border-radius: 50%;
    }

    .progress-chart-value {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
    }

    .progress-stats {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-top: 1rem;
    }

    .progress-stat {
        text-align: center;
    }

    .progress-stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .progress-stat-label {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header .header-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .time-banner .row {
            flex-direction: column;
            gap: 1rem;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .appointment-table {
            display: block;
            overflow-x: auto;
        }
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="container-fluid">
        <div class="header-content">
            <div>
                <h1 class="page-title">Today's Appointments</h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-list me-2"></i> All Appointments
                </a>
                <a href="{{ route('admin.appointments.calendar') }}" class="btn btn-primary">
                    <i class="fas fa-calendar-alt me-2"></i> Calendar View
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Time Banner -->
    <div class="time-banner">
        <div class="row align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="time-display">
                    
                    <div>
                        <h2 class="current-time" id="currentTime">{{ now()->format('g:i A') }}</h2>
                        <p class="current-date">{{ now()->format('l, F j, Y') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stats-badges justify-content-md-end">
                    <span class="stat-badge">
                        <i class="fas fa-clock me-1"></i>
                        {{ $appointments->where('status', 'pending')->count() }} Pending
                    </span>
                    <span class="stat-badge">
                        <i class="fas fa-check-circle me-1"></i>
                        {{ $appointments->where('status', 'confirmed')->count() }} Confirmed
                    </span>
                    <span class="stat-badge">
                        <i class="fas fa-check-double me-1"></i>
                        {{ $completedToday }} Completed
                    </span>
                </div>
            </div>
        </div>
    </div>


    <!-- Main Content Layout -->
    <div class="content-layout">
        <!-- Left Column: Schedule -->
        <div class="schedule-container">
            <div class="schedule-card">
                <div class="schedule-header">
                    <div class="schedule-title">
                        <h3>
                            <i class="fas fa-calendar-day"></i>
                            Today's Schedule
                        </h3>
                        <span class="schedule-count">
                           <span> {{ $appointments->count() }} Ongoing </span> 
                           <span>{{ $completedToday }} Completed</span>
                        </span>
                    </div>
                </div>
                
                @if($appointments->count() > 0)
                    <div class="table-responsive">
                        <table class="appointment-table">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Patient</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $appointment)
                                    @php
                                        $dateTimeString = $appointment->appointment_date->format('Y-m-d') . 'T' . $appointment->appointment_time;
                                        $appointmentTime = \Carbon\Carbon::parse($dateTimeString);
                                        $now = now();
                                        $timeDiff = $appointmentTime->diffInMinutes($now, false);
                                        
                                        // Determine time status
                                        if ($timeDiff < -15) {
                                            $timeStatus = 'upcoming';
                                        } elseif ($timeDiff >= -15 && $timeDiff <= 30) {
                                            $timeStatus = 'ongoing';
                                        } else {
                                            $timeStatus = 'passed';
                                        }
                                    @endphp
                                    
                                    <tr>
                                        <td>
                                            <div class="time-cell">
                                                <div class="time-indicator {{ $timeStatus }}"></div>
                                                <div class="time-display-group">
                                                    <div class="appointment-time">
                                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                                                    </div>
                                                    <div class="time-status">
                                                        @if($timeDiff < 0)
                                                            In {{ abs($timeDiff) }} min
                                                        @else
                                                            Started {{ $timeDiff }} min ago
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="patient-cell">
                                                <div class="patient-avatar">
                                                    {{ strtoupper(substr($appointment->patient_first_name, 0, 1)) }}{{ strtoupper(substr($appointment->patient_last_name, 0, 1)) }}
                                                </div>
                                                <div class="patient-info">
                                                    <div class="patient-name">
                                                        {{ $appointment->patient_first_name }} {{ $appointment->patient_last_name }}
                                                    </div>
                                                    <div class="patient-meta">
                                                        <span>#{{ $appointment->appointment_number ?? $appointment->id }}</span>
                                                        @if($appointment->chamber)
                                                            <span><i class="fas fa-map-marker-alt me-1"></i>{{ $appointment->chamber->name }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="type-badge {{ $appointment->consultation_type == 'online' ? 'type-online' : '' }}">
                                                <i class="fas {{ $appointment->consultation_type == 'online' ? 'fa-video' : 'fa-stethoscope' }} me-1"></i>
                                                {{ ucfirst($appointment->consultation_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ $appointment->status }}">
                                                <i class="fas 
                                                    @if($appointment->status == 'pending') fa-clock
                                                    @elseif($appointment->status == 'confirmed') fa-check-circle
                                                    @else fa-check-double @endif
                                                    me-1"></i>
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="action-group">
                                                <a href="{{ route('admin.appointments.show', $appointment) }}" 
                                                   class="action-btn"
                                                   title="View details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($appointment->status == 'pending')
                                                    <button class="action-btn"
                                                            onclick="updateStatus({{ $appointment->id }}, 'confirmed')"
                                                            title="Confirm appointment">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                
                                                @if(in_array($appointment->status, ['pending', 'confirmed']))
                                                    <button class="action-btn"
                                                            onclick="updateStatus({{ $appointment->id }}, 'completed')"
                                                            title="Mark as completed">
                                                        <i class="fas fa-check-double"></i>
                                                    </button>
                                                    
                                                    <button class="action-btn"
                                                            onclick="showRescheduleModal({{ $appointment->id }})"
                                                            title="Reschedule">
                                                        <i class="fas fa-calendar-edit"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <h4 class="fw-semibold mb-3">No appointments today</h4>
                        <p class="text-muted mb-4">You have no scheduled appointments for today.</p>
                        <a href="{{ route('admin.appointments.index') }}" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i> View all appointments
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Sidebar -->
        <div class="sidebar-container">
            <!-- Next Appointments -->
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <h4 class="sidebar-card-title">
                        <i class="fas fa-arrow-right"></i>
                        Next Appointments
                    </h4>
                </div>
                @php
                    $nextAppointments = $appointments
                        ->where('status', '!=', 'completed')
                        ->sortBy('appointment_time')
                        ->take(3);
                @endphp
                
                @if($nextAppointments->count() > 0)
                    <ul class="next-appointments-list">
                        @foreach($nextAppointments as $appointment)
                            <li class="next-appointment-item">
                                <div class="next-appointment-time">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                                </div>
                                <div class="next-appointment-patient">
                                    {{ $appointment->patient_first_name }} {{ $appointment->patient_last_name }}
                                </div>
                                <div class="next-appointment-meta">
                                    <span>
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $appointment->chamber->name ?? 'Chamber' }}
                                    </span>
                                    <span class="status-badge status-{{ $appointment->status }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-double fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">All appointments completed</p>
                    </div>
                @endif
            </div>

            <!-- Today's Progress -->
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <h4 class="sidebar-card-title">
                        <i class="fas fa-chart-line"></i>
                        Today's Progress
                    </h4>
                </div>
                <div class="progress-chart-container">
                    @php
                        $totalToday = $appointments->count() + $completedToday;
                        $completionRate = $totalToday > 0 ? round(($completedToday / $totalToday) * 100) : 0;
                    @endphp
                    
                    <div class="progress-chart">
                        <div class="progress-chart-circle" style="--percentage: {{ $completionRate }}%"></div>
                        <div class="progress-chart-value">{{ $completionRate }}%</div>
                    </div>
                    
                    <div class="progress-stats">
                        <div class="progress-stat">
                            <div class="progress-stat-value text-success">{{ $completedToday }}</div>
                            <div class="progress-stat-label">Completed</div>
                        </div>
                        <div class="progress-stat">
                            <div class="progress-stat-value text-primary">{{ $appointments->count() }}</div>
                            <div class="progress-stat-label">Remaining</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reschedule Modal -->
<div class="modal fade" id="rescheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-primary text-white rounded-top-3">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-edit me-2"></i> Reschedule Appointment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="rescheduleForm">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="appointment_id" id="reschedule_appointment_id">
                    
                    <div class="mb-4">
                        <div class="form-label fw-semibold mb-2">Current Schedule</div>
                        <div class="alert alert-primary bg-opacity-10 border-primary border-opacity-25">
                            <div id="currentAppointmentDetails"></div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">New Date *</label>
                            <input type="date" class="form-control" 
                                   id="appointment_date"
                                   name="appointment_date" 
                                   min="{{ date('Y-m-d') }}" 
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">New Time *</label>
                            <input type="time" class="form-control" 
                                   id="appointment_time"
                                   name="appointment_time" 
                                   required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Reason (Optional)</label>
                            <textarea class="form-control" 
                                      id="reason"
                                      name="reason" 
                                      rows="3"
                                      placeholder="Reason for rescheduling..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-calendar-check me-2"></i> Reschedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Update current time
function updateCurrentTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
    document.getElementById('currentTime').textContent = timeString;
}
setInterval(updateCurrentTime, 60000);

// Update status function
function updateStatus(appointmentId, status) {
    const statusText = status.charAt(0).toUpperCase() + status.slice(1);
    
    Swal.fire({
        title: `Mark as ${statusText}?`,
        text: 'Are you sure you want to update this appointment status?',
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
                    location.reload();
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
}

// Confirm all pending
function markAllConfirmed() {
    Swal.fire({
        title: 'Confirm All Pending?',
        text: 'This will confirm all pending appointments for today.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#318069',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, confirm all',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (!result.isConfirmed) return;

        fetch('{{ route("admin.appointment.today") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ action: 'confirm_all' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Confirmed!',
                    text: data.message,
                    confirmButtonColor: '#318069'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to confirm all',
                    confirmButtonColor: '#ef4444'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to confirm all',
                confirmButtonColor: '#ef4444'
            });
        });
    });
}

// Show reschedule modal
function showRescheduleModal(appointmentId) {
    const appointment = @json($appointments->firstWhere('id', ':id') ?? []);
    const placeholder = appointmentId;
    
    // Find appointment in current appointments list
    const appointmentRow = document.querySelector(`tr[data-appointment-id="${appointmentId}"]`);
    if (appointmentRow) {
        const patientName = appointmentRow.querySelector('.patient-name').textContent;
        const appointmentTime = appointmentRow.querySelector('.appointment-time').textContent;
        
        document.getElementById('currentAppointmentDetails').innerHTML = `
            <strong>${patientName}</strong><br>
            <small>${appointmentTime}</small>
        `;
    }
    
    document.getElementById('reschedule_appointment_id').value = appointmentId;
    const modal = new bootstrap.Modal(document.getElementById('rescheduleModal'));
    modal.show();
}

// Reschedule form submission
document.getElementById('rescheduleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const appointmentId = document.getElementById('reschedule_appointment_id').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    submitBtn.disabled = true;
    
    fetch(`/admin/appointments/${appointmentId}/reschedule`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        if (data.success) {
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('rescheduleModal')).hide();
            
            // Show success
            Swal.fire({
                icon: 'success',
                title: 'Rescheduled!',
                text: data.message,
                confirmButtonColor: '#318069'
            }).then(() => {
                location.reload();
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
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to reschedule appointment',
            confirmButtonColor: '#ef4444'
        });
    });
});

// Refresh page
function refreshPage() {
    location.reload();
}

// Auto-refresh every 5 minutes (optional)
// setTimeout(refreshPage, 300000);

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Update time every minute
    setInterval(updateCurrentTime, 60000);
});
</script>
@endsection