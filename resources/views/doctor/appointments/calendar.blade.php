@extends('layouts.admin')

@section('title', 'Appointment Calendar')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
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
        --secondary: #6c757d;
    }

    /* Professional Calendar Container */
    .calendar-container {
        border: 1px solid var(--primary-border);
        border-radius: 16px;
        background: white;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .calendar-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        padding: 1.5rem 1.75rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .calendar-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .calendar-title h2 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
        color: white;
    }

    .calendar-title .current-date {
        background: rgba(255, 255, 255, 0.15);
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.9rem;
        backdrop-filter: blur(10px);
    }

    /* Calendar Navigation */
    .calendar-nav {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .nav-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .nav-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
    }

    .view-switcher {
        display: flex;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 4px;
        gap: 2px;
    }

    .view-btn {
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        border: none;
        background: transparent;
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .view-btn:hover {
        color: white;
        background: rgba(255, 255, 255, 0.1);
    }

    .view-btn.active {
        background: white;
        color: var(--primary);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.75rem;
    }

    .btn-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .btn-icon:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
    }

    .btn-primary-solid {
        background: white;
        color: var(--primary);
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        border: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-primary-solid:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Stats Overview */
    .stats-overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        padding: 1.5rem;
        background: var(--primary-soft);
        border-bottom: 1px solid var(--primary-border);
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: white;
        border-radius: 12px;
        border: 1px solid var(--primary-border);
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(49, 128, 105, 0.1);
        border-color: var(--primary);
    }

    .stat-icon {
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

    .stat-content {
        flex: 1;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }

    /* Enhanced FullCalendar Styling */
    .fc {
        --fc-border-color: var(--primary-border);
        --fc-button-bg-color: white;
        --fc-button-border-color: #e5e7eb;
        --fc-button-text-color: #374151;
        --fc-button-hover-bg-color: var(--primary);
        --fc-button-hover-border-color: var(--primary);
        --fc-button-active-bg-color: var(--primary);
        --fc-button-active-border-color: var(--primary);
        --fc-neutral-bg-color: #f8fafc;
        --fc-today-bg-color: var(--primary-light);
        --fc-event-bg-color: var(--primary);
        --fc-event-border-color: var(--primary);
        --fc-page-bg-color: white;
    }

    .fc .fc-toolbar {
        padding: 1.25rem 1.75rem;
        margin: 0;
        border-bottom: 1px solid var(--primary-border);
        background: white;
    }

    .fc .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
    }

    .fc .fc-button {
        border-radius: 8px;
        border: 1.5px solid #e5e7eb;
        background: white;
        color: #374151;
        font-weight: 500;
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        text-transform: capitalize;
        transition: all 0.2s ease;
        box-shadow: none !important;
    }

    .fc .fc-button:hover {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        transform: translateY(-1px);
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    /* Calendar Grid Enhancements */
    .fc .fc-daygrid-day {
        border: 1px solid var(--primary-border);
        transition: all 0.2s ease;
        background: white;
        cursor: pointer;
        position: relative;
    }

    .fc .fc-daygrid-day:hover {
        background: var(--primary-soft);
    }

    .fc .fc-daygrid-day-frame {
        min-height: 120px;
        padding: 0.75rem;
        position: relative;
    }

    .fc .fc-daygrid-day-number {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: 500;
        color: #6b7280;
        z-index: 2;
    }

    .fc .fc-day-today .fc-daygrid-day-number {
        background: var(--primary);
        color: white;
        border-radius: 50%;
        font-weight: 600;
    }

    .fc .fc-day-other .fc-daygrid-day-top {
        opacity: 0.4;
    }

    /* Appointment dot indicators */
    .appointment-indicator {
        position: absolute;
        bottom: 8px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 2px;
        flex-wrap: wrap;
        justify-content: center;
        max-width: 80%;
    }

    .appointment-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin: 1px;
    }

    .dot-pending {
        background-color: #f59e0b;
    }

    .dot-confirmed {
        background-color: #3b82f6;
    }

    .dot-completed {
        background-color: #10b981;
    }

    .dot-cancelled {
        background-color: #ef4444;
    }

    /* Week View Enhancements */
    .fc .fc-timegrid-slot {
        height: 60px !important;
        border-bottom: 1px solid var(--primary-border);
    }

    .fc .fc-timegrid-axis {
        border-right: 1px solid var(--primary-border);
        background: var(--primary-soft);
    }

    .fc .fc-timegrid-col {
        border-right: 1px solid var(--primary-border);
    }

    .fc .fc-timegrid-col.fc-day-today {
        background: var(--primary-soft);
    }

    /* Day View Enhancements */
    .fc .fc-timegrid-slots td {
        border-bottom: 1px solid var(--primary-border);
    }

    /* Event Styling - Professional */
    .fc .fc-event {
        border: none;
        border-radius: 8px;
        margin: 2px 4px;
        cursor: pointer;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
        border-left: 4px solid;
    }

    .fc .fc-event:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    /* Event Status Colors */
    .fc-event-confirmed {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        border-left-color: #1d4ed8;
    }

    .fc-event-pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-left-color: #d97706;
    }

    .fc-event-completed {
        background: linear-gradient(135deg, #10b981, #059669);
        border-left-color: #059669;
    }

    .fc-event-cancelled {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border-left-color: #dc2626;
    }

    /* Online Consultation Mark */
    .fc-event-online::after {
        content: '';
        position: absolute;
        top: 8px;
        right: 8px;
        width: 8px;
        height: 8px;
        background: #0ea5e9;
        border-radius: 50%;
        border: 2px solid white;
    }

    /* Event Content */
    .fc-event-content {
        padding: 0.75rem;
        position: relative;
        z-index: 1;
    }

    .fc-event-title {
        font-weight: 600;
        font-size: 0.8rem;
        line-height: 1.2;
        margin-bottom: 4px;
        color: white;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .fc-event-time {
        font-size: 0.7rem;
        opacity: 0.9;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
    }

    .fc-event-type {
        position: absolute;
        bottom: 8px;
        right: 8px;
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.65rem;
        color: white;
        backdrop-filter: blur(4px);
    }

    /* More Events Indicator */
    .fc-daygrid-more-link {
        background: var(--primary-light);
        color: var(--primary);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-top: 4px;
        display: inline-block;
        transition: all 0.2s ease;
    }

    .fc-daygrid-more-link:hover {
        background: var(--primary);
        color: white;
    }

    /* Appointment Details Panel */
    .appointment-panel {
        position: fixed;
        top: 0;
        right: -400px;
        width: 400px;
        height: 100vh;
        background: white;
        box-shadow: -4px 0 20px rgba(0, 0, 0, 0.1);
        transition: right 0.3s ease;
        z-index: 1050;
        overflow-y: auto;
    }

    .appointment-panel.open {
        right: 0;
    }

    .appointment-panel-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        padding: 1.5rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .appointment-panel-body {
        padding: 1.5rem;
    }

    /* Date Appointments Panel */
    .date-appointments-header {
        background: linear-gradient(135deg, var(--info) 0%, #2563eb 100%);
        padding: 1.5rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .date-appointments-body {
        padding: 1.5rem;
    }

    /* Overlay */
    .panel-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1040;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .panel-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    /* Quick Stats */
    .quick-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-top: 1rem;
    }

    .quick-stat {
        text-align: center;
        padding: 1rem;
        background: white;
        border-radius: 12px;
        border: 1px solid var(--primary-border);
        transition: all 0.3s ease;
    }

    .quick-stat:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(49, 128, 105, 0.1);
        border-color: var(--primary);
    }

    .quick-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .quick-stat-label {
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    /* Time Slots in Week/Day View */
    .fc .fc-timegrid-slot-label {
        font-size: 0.75rem;
        color: #6b7280;
        font-weight: 500;
        padding: 0.5rem;
        border-right: 1px solid var(--primary-border);
    }

    /* Appointment List in Date View */
    .appointment-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .appointment-list-item {
        border-bottom: 1px solid var(--primary-border);
        padding: 1rem 0;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .appointment-list-item:hover {
        background: var(--primary-soft);
        padding-left: 0.5rem;
        padding-right: 0.5rem;
        margin: 0 -0.5rem;
        border-radius: 8px;
    }

    .appointment-list-item:last-child {
        border-bottom: none;
    }

    .appointment-time {
        font-size: 0.875rem;
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .appointment-patient {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .appointment-status {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-top: 0.25rem;
    }

    .appointment-status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #92400e;
    }

    .appointment-status-confirmed {
        background: rgba(59, 130, 246, 0.1);
        color: #1e40af;
    }

    .appointment-status-completed {
        background: rgba(16, 185, 129, 0.1);
        color: #065f46;
    }

    .appointment-status-cancelled {
        background: rgba(239, 68, 68, 0.1);
        color: #991b1b;
    }

    .appointment-type {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-left: 0.5rem;
    }

    .appointment-type-online {
        background: rgba(59, 130, 246, 0.1);
        color: #1e40af;
    }

    .appointment-type-inperson {
        background: rgba(245, 158, 11, 0.1);
        color: #92400e;
    }

    /* Empty state */
    .empty-appointments {
        text-align: center;
        padding: 3rem 1rem;
        color: #6b7280;
    }

    .empty-appointments i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .calendar-header {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }
        
        .calendar-title {
            justify-content: space-between;
        }
        
        .action-buttons {
            justify-content: center;
        }
        
        .appointment-panel {
            width: 100%;
            right: -100%;
        }
    }

    @media (max-width: 768px) {
        .stats-overview {
            grid-template-columns: 1fr;
        }
        
        .view-switcher {
            flex-wrap: wrap;
        }
        
        .view-btn {
            flex: 1;
            justify-content: center;
        }
        
        .fc .fc-toolbar {
            flex-direction: column;
            gap: 1rem;
        }
        
        .quick-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Animation for events */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fc-event {
        animation: fadeInUp 0.3s ease-out;
    }

    /* Custom Scrollbar */
    .fc-scroller::-webkit-scrollbar {
        width: 6px;
    }

    .fc-scroller::-webkit-scrollbar-track {
        background: var(--primary-light);
        border-radius: 3px;
    }

    .fc-scroller::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 3px;
    }

    /* Loading State */
    .calendar-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 400px;
        background: var(--primary-soft);
        border-radius: 0 0 16px 16px;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid var(--primary-light);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Day cell hover effect */
    .fc-daygrid-day:hover .appointment-indicator .appointment-dot {
        transform: scale(1.2);
        transition: transform 0.2s ease;
    }
</style>

<div class="pb-4">
    {{-- CALENDAR CONTAINER --}}
    <div class="calendar-container">
        {{-- CALENDAR HEADER --}}
        <div class="calendar-header">
            <div class="calendar-title">
                <div>
                    <h2><i class="fas fa-calendar-alt me-3"></i>Appointment Calendar</h2>
                    <div class="current-date mt-2">
                        <i class="fas fa-clock me-2"></i>
                        <span id="current-date-display">{{ now()->format('F d, Y') }}</span>
                    </div>
                </div>
                <div class="quick-stats">
                    <div class="quick-stat">
                        <div class="quick-stat-value">{{ $todayAppointments }}</div>
                        <div class="quick-stat-label">Today</div>
                    </div>
                    <div class="quick-stat">
                        <div class="quick-stat-value">{{ $pendingAppointments }}</div>
                        <div class="quick-stat-label">Pending</div>
                    </div>
                    <div class="quick-stat">
                        <div class="quick-stat-value">{{ $confirmedAppointments }}</div>
                        <div class="quick-stat-label">Confirmed</div>
                    </div>
                    <div class="quick-stat">
                        <div class="quick-stat-value">{{ $thisWeekAppointments }}</div>
                        <div class="quick-stat-label">This Week</div>
                    </div>
                </div>
            </div>
            
            <div class="calendar-nav">
                <div class="view-switcher">
                    <button class="view-btn active" data-view="dayGridMonth">
                        <i class="fas fa-calendar-alt"></i> Month
                    </button>
                    <button class="view-btn" data-view="timeGridWeek">
                        <i class="fas fa-calendar-week"></i> Week
                    </button>
                    <button class="view-btn" data-view="timeGridDay">
                        <i class="fas fa-calendar-day"></i> Day
                    </button>
                </div>
                
                <div class="action-buttons">
                    <button class="nav-btn" onclick="window.calendar.prev()">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="btn-primary-solid" onclick="window.calendar.today()">
                        <i class="fas fa-calendar-day"></i> Today
                    </button>
                    <button class="nav-btn" onclick="window.calendar.next()">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn-primary-solid">
                        <i class="fas fa-list me-2"></i> List View
                    </a>
                </div>
            </div>
        </div>

        {{-- CALENDAR --}}
        <div class="mt-1">
            <div id="calendar"></div>
        </div>
    </div>
</div>

{{-- APPOINTMENT DETAILS PANEL --}}
<div class="appointment-panel" id="appointmentPanel">
    <div class="appointment-panel-header">
        <h5 class="mb-0">Appointment Details</h5>
        <button type="button" class="btn-close btn-close-white" onclick="closeAppointmentPanel()"></button>
    </div>
    <div class="appointment-panel-body" id="appointmentDetails">
        {{-- Dynamic content will be loaded here --}}
    </div>
</div>

{{-- DATE APPOINTMENTS PANEL --}}
<div class="appointment-panel" id="dateAppointmentsPanel">
    <div class="date-appointments-header">
        <h5 class="mb-0" id="datePanelTitle">Appointments for </h5>
        <button type="button" class="btn-close btn-close-white" onclick="closeDateAppointmentsPanel()"></button>
    </div>
    <div class="date-appointments-body" id="dateAppointmentsContent">
        {{-- Dynamic content will be loaded here --}}
    </div>
</div>

{{-- OVERLAY --}}
<div class="panel-overlay" id="panelOverlay" onclick="closeAllPanels()"></div>

{{-- APPOINTMENT DETAILS TEMPLATE --}}
<template id="appointmentTemplate">
    <div class="appointment-details">
        <div class="patient-info mb-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="patient-avatar" style="width: 60px; height: 60px; background: var(--primary-light); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--primary); font-weight: 600;">
                    <span id="patientInitials"></span>
                </div>
                <div>
                    <h4 id="patientName" class="mb-1"></h4>
                    <div class="text-muted" id="patientContact"></div>
                </div>
            </div>
        </div>

        <div class="appointment-info mb-4">
            <div class="row g-3">
                <div class="col-6">
                    <label class="form-label text-muted small mb-1">Date</label>
                    <div class="fw-semibold" id="appointmentDate"></div>
                </div>
                <div class="col-6">
                    <label class="form-label text-muted small mb-1">Time</label>
                    <div class="fw-semibold" id="appointmentTime"></div>
                </div>
                <div class="col-6">
                    <label class="form-label text-muted small mb-1">Duration</label>
                    <div class="fw-semibold" id="appointmentDuration">30 min</div>
                </div>
                <div class="col-6">
                    <label class="form-label text-muted small mb-1">Type</label>
                    <div class="fw-semibold">
                        <span class="badge" id="consultationType"></span>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label text-muted small mb-1">Chamber</label>
                    <div class="fw-semibold" id="chamberName"></div>
                </div>
                <div class="col-12">
                    <label class="form-label text-muted small mb-1">Appointment ID</label>
                    <div class="fw-semibold" id="appointmentNumber"></div>
                </div>
            </div>
        </div>

        <div class="status-info mb-4">
            <label class="form-label text-muted small mb-1">Status</label>
            <div class="d-flex align-items-center gap-2">
                <span class="status-badge" id="appointmentStatus"></span>
            </div>
        </div>

        <div class="notes mb-4">
            <label class="form-label text-muted small mb-1">Notes</label>
            <div class="card bg-light border-0 p-3">
                <p class="mb-0" id="appointmentNotes">No notes available.</p>
            </div>
        </div>

        <div class="action-buttons">
            <div class="d-grid gap-2">
                <a href="#" class="btn btn-primary" id="viewDetailsBtn">
                    <i class="fas fa-eye me-2"></i> View Full Details
                </a>
                <button class="btn btn-outline-primary" id="rescheduleBtn">
                    <i class="fas fa-calendar-alt me-2"></i> Reschedule
                </button>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-success" id="confirmBtn">
                        <i class="fas fa-check me-2"></i> Confirm
                    </button>
                    <button type="button" class="btn btn-outline-danger" id="cancelBtn">
                        <i class="fas fa-times me-2"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

{{-- DATE APPOINTMENTS TEMPLATE --}}
<template id="dateAppointmentsTemplate">
    <div class="date-appointments">
        <div class="appointment-count mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1">Total Appointments: <span id="totalAppointments" class="fw-bold"></span></h6>
                    <small class="text-muted" id="dateDisplay"></small>
                </div>
                <a href="" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>
            </div>
        </div>
        
        <div id="appointmentsListContainer">
            <!-- Appointments list will be loaded here -->
        </div>
        
        <div id="noAppointmentsMessage" class="empty-appointments" style="display: none;">
            <i class="fas fa-calendar-times"></i>
            <h6 class="mb-2">No Appointments</h6>
            <p class="text-muted mb-0">No appointments scheduled for this date.</p>
            <a href="" class="btn btn-primary mt-3">
                <i class="fas fa-plus me-2"></i> Schedule Appointment
            </a>
        </div>
    </div>
</template>

{{-- APPOINTMENT ITEM TEMPLATE --}}
<template id="appointmentItemTemplate">
    <li class="appointment-list-item" data-appointment-id="">
        <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1">
                <div class="appointment-time">
                    <i class="fas fa-clock me-2"></i>
                    <span id="appointmentTime"></span>
                </div>
                <div class="appointment-patient fw-semibold" id="patientName"></div>
                <div class="appointment-meta mt-2">
                    <span class="appointment-status" id="appointmentStatus"></span>
                    <span class="appointment-type" id="appointmentType"></span>
                </div>
                <div class="text-muted small mt-2" id="appointmentNotes"></div>
            </div>
            <div>
                <button class="btn btn-sm btn-outline-primary view-details-btn">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
    </li>
</template>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentAppointmentId = null;
    let currentSelectedDate = null;
    let allAppointments = @json($appointments);
    
    // Prepare appointments data for calendar
    const calendarEvents = allAppointments.map(appointment => {
        const start = new Date(appointment.appointment_date + 'T' + appointment.appointment_time);
        const end = new Date(start.getTime() + 30 * 60000); // 30 minutes duration
        
        return {
            id: appointment.id,
            title: `${appointment.patient_first_name} ${appointment.patient_last_name}`,
            start: start,
            end: end,
            extendedProps: {
                patient: {
                    firstName: appointment.patient_first_name,
                    lastName: appointment.patient_last_name,
                    phone: appointment.patient_phone,
                    email: appointment.patient_email
                },
                status: appointment.status,
                consultationType: appointment.consultation_type,
                chamber: appointment.chamber?.name || 'Not specified',
                amount: appointment.amount,
                notes: appointment.notes || 'No notes',
                appointmentNumber: appointment.appointment_number || appointment.id,
                date: appointment.appointment_date,
                time: appointment.appointment_time,
                doctor: appointment.doctor?.name || 'Not assigned'
            },
            className: `fc-event-${appointment.status} ${appointment.consultation_type === 'online' ? 'fc-event-online' : ''}`,
            backgroundColor: getStatusColor(appointment.status),
            borderColor: getStatusBorderColor(appointment.status),
            textColor: '#ffffff'
        };
    });

    // Initialize calendar
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: false,
        height: 'auto',
        nowIndicator: true,
        navLinks: true,
        editable: false,
        selectable: false,
        dayMaxEvents: 4,
        slotMinTime: '08:00:00',
        slotMaxTime: '20:00:00',
        events: calendarEvents,
        
        // Date click handler - show appointments for clicked date
        dateClick: function(info) {
            showDateAppointments(info.dateStr);
        },
        
        // Event rendering
        eventContent: function(arg) {
            const time = arg.event.start.toLocaleTimeString([], { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            
            const typeIcon = arg.event.extendedProps.consultationType === 'online' 
                ? '<i class="fas fa-video me-1"></i>' 
                : '<i class="fas fa-user-md me-1"></i>';
            
            return {
                html: `
                    <div class="fc-event-content">
                        <div class="fc-event-title">
                            ${typeIcon}${arg.event.title}
                        </div>
                        <div class="fc-event-time">${time}</div>
                        ${arg.event.extendedProps.consultationType === 'online' 
                            ? '<div class="fc-event-type">Online</div>' 
                            : ''}
                    </div>
                `
            };
        },
        
        // Event click handler
        eventClick: function(info) {
            showAppointmentDetails(info.event);
        },
        
        // View change handler
        datesSet: function(info) {
            updateCurrentDateDisplay(info.view.currentStart, info.view.type);
            updateDateIndicators();
        },
        
        // Day cell render - add appointment indicators
        dayCellDidMount: function(info) {
            addAppointmentIndicators(info.el, info.date);
        },
        
        // Event mouseover
        eventMouseEnter: function(info) {
            info.el.style.zIndex = 1000;
            info.el.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.2)';
        },
        
        // Event mouseout
        eventMouseLeave: function(info) {
            info.el.style.zIndex = '';
            info.el.style.boxShadow = '';
        }
    });

    calendar.render();
    window.calendar = calendar;

    // Add appointment indicators to date cells
    function addAppointmentIndicators(cellEl, date) {
        const appointmentsForDate = getAppointmentsForDate(date);
        
        if (appointmentsForDate.length > 0) {
            // Remove existing indicator if any
            const existingIndicator = cellEl.querySelector('.appointment-indicator');
            if (existingIndicator) {
                existingIndicator.remove();
            }
            
            // Create new indicator
            const indicator = document.createElement('div');
            indicator.className = 'appointment-indicator';
            
            // Add dots for different status types
            const statusCounts = {};
            appointmentsForDate.forEach(appointment => {
                const status = appointment.status;
                statusCounts[status] = (statusCounts[status] || 0) + 1;
            });
            
            // Create dots (max 5)
            let dotCount = 0;
            Object.entries(statusCounts).forEach(([status, count]) => {
                for (let i = 0; i < Math.min(count, 2); i++) {
                    if (dotCount < 5) {
                        const dot = document.createElement('div');
                        dot.className = `appointment-dot dot-${status}`;
                        dot.title = `${count} ${status} appointment${count > 1 ? 's' : ''}`;
                        indicator.appendChild(dot);
                        dotCount++;
                    }
                }
            });
            
            // Add tooltip for exact count
            if (appointmentsForDate.length > 5) {
                indicator.title = `${appointmentsForDate.length} appointments`;
            }
            
            const dayFrame = cellEl.querySelector('.fc-daygrid-day-frame');
            if (dayFrame) {
                dayFrame.appendChild(indicator);
            }
        }
    }

    // Update indicators for all visible dates
    function updateDateIndicators() {
        const calendarApi = calendar;
        const currentView = calendarApi.view;
        
        if (currentView.type === 'dayGridMonth') {
            const dates = calendarApi.getEvents().map(event => event.start);
            const uniqueDates = [...new Set(dates.map(d => d.toDateString()))];
            
            // Re-add indicators for all visible cells
            document.querySelectorAll('.fc-daygrid-day').forEach(cell => {
                const dateStr = cell.getAttribute('data-date');
                if (dateStr) {
                    const date = new Date(dateStr);
                    addAppointmentIndicators(cell, date);
                }
            });
        }
    }

    // Get appointments for a specific date
    function getAppointmentsForDate(date) {
        const dateStr = date.toISOString().split('T')[0];
        return allAppointments.filter(appointment => 
            appointment.appointment_date === dateStr
        );
    }

    // Show appointments for a specific date
    function showDateAppointments(dateStr) {
        currentSelectedDate = dateStr;
        const date = new Date(dateStr);
        const appointments = getAppointmentsForDate(date);
        
        const template = document.getElementById('dateAppointmentsTemplate').content.cloneNode(true);
        
        // Update title
        document.getElementById('datePanelTitle').textContent = 
            `Appointments for ${date.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            })}`;
        
        // Update count
        document.getElementById('totalAppointments').textContent = appointments.length;
        document.getElementById('dateDisplay').textContent = 
            date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        
        const listContainer = template.querySelector('#appointmentsListContainer');
        const noAppointmentsMsg = template.querySelector('#noAppointmentsMessage');
        
        if (appointments.length > 0) {
            // Create list
            const ul = document.createElement('ul');
            ul.className = 'appointment-list';
            
            appointments.forEach(appointment => {
                const itemTemplate = document.getElementById('appointmentItemTemplate').content.cloneNode(true);
                const item = itemTemplate.querySelector('.appointment-list-item');
                
                item.setAttribute('data-appointment-id', appointment.id);
                
                // Set time
                const time = new Date('2000-01-01T' + appointment.appointment_time);
                item.querySelector('#appointmentTime').textContent = 
                    time.toLocaleTimeString('en-US', { 
                        hour: '2-digit', 
                        minute: '2-digit' 
                    });
                
                // Set patient name
                item.querySelector('#patientName').textContent = 
                    `${appointment.patient_first_name} ${appointment.patient_last_name}`;
                
                // Set status
                const statusEl = item.querySelector('#appointmentStatus');
                statusEl.textContent = appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1);
                statusEl.className = `appointment-status appointment-status-${appointment.status}`;
                
                // Set type
                const typeEl = item.querySelector('#appointmentType');
                typeEl.textContent = appointment.consultation_type === 'online' ? 'Online' : 'In-person';
                typeEl.className = `appointment-type appointment-type-${appointment.consultation_type === 'online' ? 'online' : 'inperson'}`;
                
                // Set notes if available
                if (appointment.notes) {
                    item.querySelector('#appointmentNotes').textContent = 
                        appointment.notes.length > 50 ? appointment.notes.substring(0, 50) + '...' : appointment.notes;
                } else {
                    item.querySelector('#appointmentNotes').style.display = 'none';
                }
                
                // Add click handler to view details button
                const viewBtn = item.querySelector('.view-details-btn');
                viewBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const appointmentId = parseInt(item.getAttribute('data-appointment-id'));
                    showAppointmentDetailsById(appointmentId);
                });
                
                // Add click handler to entire item
                item.addEventListener('click', function() {
                    const appointmentId = parseInt(this.getAttribute('data-appointment-id'));
                    showAppointmentDetailsById(appointmentId);
                });
                
                ul.appendChild(item);
            });
            
            listContainer.appendChild(ul);
            noAppointmentsMsg.style.display = 'none';
        } else {
            listContainer.style.display = 'none';
            noAppointmentsMsg.style.display = 'block';
        }
        
        // Load content into panel
        document.getElementById('dateAppointmentsContent').innerHTML = '';
        document.getElementById('dateAppointmentsContent').appendChild(template);
        
        // Show panel
        document.getElementById('dateAppointmentsPanel').classList.add('open');
        document.getElementById('panelOverlay').classList.add('show');
        document.getElementById('appointmentPanel').classList.remove('open');
    }

    // Show appointment details by ID
    function showAppointmentDetailsById(appointmentId) {
        const appointment = allAppointments.find(a => a.id == appointmentId);
        if (!appointment) return;
        
        // Create event-like object
        const eventObj = {
            id: appointment.id,
            extendedProps: {
                patient: {
                    firstName: appointment.patient_first_name,
                    lastName: appointment.patient_last_name,
                    phone: appointment.patient_phone,
                    email: appointment.patient_email
                },
                status: appointment.status,
                consultationType: appointment.consultation_type,
                chamber: appointment.chamber?.name || 'Not specified',
                amount: appointment.amount,
                notes: appointment.notes || 'No notes available',
                appointmentNumber: appointment.appointment_number || appointment.id,
                date: appointment.appointment_date,
                time: appointment.appointment_time,
                doctor: appointment.doctor?.name || 'Not assigned'
            }
        };
        
        showAppointmentDetails(eventObj);
    }

    // Show appointment details
    function showAppointmentDetails(event) {
        const appointment = event.extendedProps;
        const template = document.getElementById('appointmentTemplate').content.cloneNode(true);
        
        // Populate data
        const initials = appointment.patient.firstName.charAt(0) + appointment.patient.lastName.charAt(0);
        template.querySelector('#patientInitials').textContent = initials;
        template.querySelector('#patientName').textContent = 
            `${appointment.patient.firstName} ${appointment.patient.lastName}`;
        template.querySelector('#patientContact').textContent = 
            appointment.patient.phone || appointment.patient.email || 'No contact info';
        
        const appointmentDate = new Date(appointment.date);
        template.querySelector('#appointmentDate').textContent = 
            appointmentDate.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        
        const time = new Date('2000-01-01T' + appointment.time);
        template.querySelector('#appointmentTime').textContent = 
            time.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
        
        // Status badge
        const statusBadge = template.querySelector('#appointmentStatus');
        const statusClass = getStatusBadgeClass(appointment.status);
        const statusIcon = getStatusIcon(appointment.status);
        statusBadge.className = `badge ${statusClass} py-2 px-3`;
        statusBadge.innerHTML = `<i class="fas ${statusIcon} me-2"></i>${appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1)}`;
        
        // Consultation type
        const isOnline = appointment.consultationType === 'online';
        template.querySelector('#consultationType').textContent = 
            isOnline ? 'Online' : 'In-person';
        template.querySelector('#consultationType').className = 
            isOnline ? 'badge bg-info py-2 px-3' : 'badge bg-warning py-2 px-3';
        
        // Other details
        template.querySelector('#chamberName').textContent = appointment.chamber;
        template.querySelector('#appointmentNumber').textContent = appointment.appointmentNumber;
        template.querySelector('#appointmentNotes').textContent = appointment.notes;
        
        // Set up action buttons
        template.querySelector('#viewDetailsBtn').href = `/admin/appointments/${event.id}`;
        template.querySelector('#rescheduleBtn').onclick = () => rescheduleAppointment(event.id);
        template.querySelector('#confirmBtn').onclick = () => updateAppointmentStatus(event.id, 'confirmed');
        template.querySelector('#cancelBtn').onclick = () => updateAppointmentStatus(event.id, 'cancelled');
        
        // Show/hide action buttons based on status
        if (appointment.status === 'completed' || appointment.status === 'cancelled') {
            template.querySelector('.btn-group').style.display = 'none';
        }
        
        // Load content into panel
        document.getElementById('appointmentDetails').innerHTML = '';
        document.getElementById('appointmentDetails').appendChild(template);
        
        // Show panel and hide date panel
        document.getElementById('appointmentPanel').classList.add('open');
        document.getElementById('dateAppointmentsPanel').classList.remove('open');
        document.getElementById('panelOverlay').classList.add('show');
        
        currentAppointmentId = event.id;
    }

    // Update current date display
    function updateCurrentDateDisplay(date, viewType) {
        const dateDisplay = document.getElementById('current-date-display');
        let displayText = '';
        
        switch(viewType) {
            case 'dayGridMonth':
                displayText = date.toLocaleDateString('en-US', { 
                    month: 'long', 
                    year: 'numeric' 
                });
                break;
            case 'timeGridWeek':
                const weekStart = new Date(date);
                const weekEnd = new Date(date);
                weekEnd.setDate(weekEnd.getDate() + 6);
                displayText = `${weekStart.toLocaleDateString('en-US', { 
                    month: 'short', 
                    day: 'numeric' 
                })} - ${weekEnd.toLocaleDateString('en-US', { 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric' 
                })}`;
                break;
            case 'timeGridDay':
                displayText = date.toLocaleDateString('en-US', { 
                    weekday: 'long', 
                    month: 'long', 
                    day: 'numeric', 
                    year: 'numeric' 
                });
                break;
        }
        
        dateDisplay.textContent = displayText;
    }

    // View switcher functionality
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const view = this.dataset.view;
            
            // Update active button
            document.querySelectorAll('.view-btn').forEach(b => {
                b.classList.remove('active');
            });
            this.classList.add('active');
            
            // Change calendar view
            calendar.changeView(view);
        });
    });

    // Close appointment panel
    window.closeAppointmentPanel = function() {
        document.getElementById('appointmentPanel').classList.remove('open');
        document.getElementById('panelOverlay').classList.remove('show');
        currentAppointmentId = null;
    };

    // Close date appointments panel
    window.closeDateAppointmentsPanel = function() {
        document.getElementById('dateAppointmentsPanel').classList.remove('open');
        document.getElementById('panelOverlay').classList.remove('show');
        currentSelectedDate = null;
    };

    // Close all panels
    window.closeAllPanels = function() {
        closeAppointmentPanel();
        closeDateAppointmentsPanel();
    };

    // Status helper functions
    function getStatusColor(status) {
        const colors = {
            'pending': '#f59e0b',
            'confirmed': '#3b82f6',
            'completed': '#10b981',
            'cancelled': '#ef4444'
        };
        return colors[status] || '#6b7280';
    }

    function getStatusBorderColor(status) {
        const colors = {
            'pending': '#d97706',
            'confirmed': '#1d4ed8',
            'completed': '#059669',
            'cancelled': '#dc2626'
        };
        return colors[status] || '#4b5563';
    }

    function getStatusIcon(status) {
        const icons = {
            'pending': 'fa-clock',
            'confirmed': 'fa-check-circle',
            'completed': 'fa-check-double',
            'cancelled': 'fa-times-circle'
        };
        return icons[status] || 'fa-question-circle';
    }

    function getStatusBadgeClass(status) {
        const classes = {
            'pending': 'bg-warning text-dark',
            'confirmed': 'bg-info',
            'completed': 'bg-success',
            'cancelled': 'bg-danger'
        };
        return classes[status] || 'bg-secondary';
    }

    // Appointment actions
    function rescheduleAppointment(appointmentId) {
        window.location.href = `/admin/appointments/${appointmentId}/edit`;
    }

    function updateAppointmentStatus(appointmentId, status) {
        const statusText = status.charAt(0).toUpperCase() + status.slice(1);
        if (confirm(`Are you sure you want to mark this appointment as ${statusText}?`)) {
            fetch(`/admin/appointments/${appointmentId}/status`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update local data
                    const appointmentIndex = allAppointments.findIndex(a => a.id == appointmentId);
                    if (appointmentIndex !== -1) {
                        allAppointments[appointmentIndex].status = status;
                    }
                    
                    // Refresh calendar events
                    calendar.refetchEvents();
                    
                    // Update indicators
                    updateDateIndicators();
                    
                    closeAppointmentPanel();
                    
                    // Show success message
                    showNotification('Status updated successfully!', 'success');
                } else {
                    showNotification(data.message || 'Failed to update status', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to update status', 'error');
            });
        }
    }

    function showNotification(message, type = 'success') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllPanels();
        }
        
        if (e.key === 'ArrowLeft' && e.ctrlKey) {
            calendar.prev();
            e.preventDefault();
        }
        
        if (e.key === 'ArrowRight' && e.ctrlKey) {
            calendar.next();
            e.preventDefault();
        }
        
        if (e.key === 't' && e.ctrlKey) {
            calendar.today();
            e.preventDefault();
        }
    });

    // Initial date display
    updateCurrentDateDisplay(new Date(), 'dayGridMonth');
    
    // Initial indicators
    setTimeout(() => {
        updateDateIndicators();
    }, 500);
});
</script>
@endsection