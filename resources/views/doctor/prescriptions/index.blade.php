@extends('layouts.admin')

@section('title', 'Prescription Management')

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
        font-size: 18px !important;
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

    /* Table Styling */
    .prescriptions-table-card {
        border: 1px solid var(--primary-border);
        border-radius: 12px;
        background: white;
        overflow: hidden;
    }

    .prescriptions-table {
        --bs-table-bg: transparent;
        margin-bottom: 0;
    }

    .prescriptions-table thead th {
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

    .prescriptions-table tbody tr {
        border-bottom: 1px solid var(--primary-soft);
        transition: all 0.2s ease;
    }

    .prescriptions-table tbody tr:hover {
        background: var(--primary-soft);
    }

    .prescriptions-table tbody tr:last-child {
        border-bottom: none;
    }

    .prescriptions-table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }

    /* Patient Avatar */
    .patient-avatar-small {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: white;
        font-size: 0.875rem;
        margin-right: 0.75rem;
        box-shadow: 0 4px 10px rgba(49, 128, 105, 0.2);
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

    .badge-active {
        background: rgba(139, 92, 246, 0.1);
        color: #5b21b6;
        border: 1px solid rgba(139, 92, 246, 0.2);
    }

    .badge-expired {
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

    .quick-action-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .quick-action-btn-view {
        background: rgba(59, 130, 246, 0.1);
        color: var(--info);
        border-color: rgba(59, 130, 246, 0.2);
    }

    .quick-action-btn-view:hover {
        background: var(--info);
        color: white;
        border-color: var(--info);
    }

    .quick-action-btn-print {
        background: rgba(139, 92, 246, 0.1);
        color: var(--purple);
        border-color: rgba(139, 92, 246, 0.2);
    }

    .quick-action-btn-print:hover {
        background: var(--purple);
        color: white;
        border-color: var(--purple);
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

    /* Medical Info */
    .medical-info {
        font-size: 0.8125rem;
        position: relative;
    }

    .medical-info .icon {
        width: 20px;
        color: var(--primary);
        opacity: 0.7;
    }

    .complaint-preview, .diagnosis-preview {
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .complaint-preview:hover, .diagnosis-preview:hover {
        overflow: visible;
        white-space: normal;
        background: white;
        padding: 4px;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        position: absolute;
        z-index: 10;
    }

    /* Flash Message */
    .flash-message {
        border-radius: 10px;
        border: 1px solid rgba(16, 185, 129, 0.2);
        background: rgba(16, 185, 129, 0.05);
        color: #065f46;
        padding: 0.875rem 1rem;
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .flash-message i {
        font-size: 1.125rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .prescriptions-table {
            font-size: 0.875rem;
        }

        .stats-card {
            margin-bottom: 1rem;
        }

        .search-box {
            max-width: 100%;
        }

        .complaint-preview, .diagnosis-preview {
            max-width: 150px;
        }
    }


</style>

<div class="main-container">
    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h4 fw-bold text-dark">Prescription Management</h1>
            <p class="text-muted mb-0">History of all prescriptions issued to patients</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <form method="GET" class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search prescriptions..."
                    class="form-control"
                />
            </form>

             <a href="{{url('admin/add-new-prescriptions')}}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> New Prescription
            </a>
        </div>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('ok'))
        <div class="flash-message">
            <i class="fas fa-check-circle text-success"></i>
            {{ session('ok') }}
        </div>
    @endif


    {{-- PRESCRIPTIONS TABLE --}}
    <div class="prescriptions-table-card">
        <div class="card-header-primary d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-file-prescription me-2"></i>
                Prescription History
            </h5>
            @if($prescriptions->total())
                <span class="badge bg-primary rounded-pill">
                    {{ $prescriptions->total() }} prescriptions
                </span>
            @endif
        </div>

        <div class="card-body p-0">
            @if($prescriptions->count() > 0)
                <div class="table-responsive scrollbar-primary" style="max-height: 600px; overflow-y: auto;">
                    <table class="table prescriptions-table">
                        <thead>
                            <tr>
                                <th class="ps-4">DATE</th>
                                <th>PATIENT</th>
                                <th>APPOINTMENT</th>
                                <th>CHIEF COMPLAINT</th>
                                <th>DIAGNOSIS</th>
                                <th>STATUS</th>
                                <th class="text-end pe-4">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prescriptions as $rx)
                                @php
                                    $patient = $rx->patient;
                                    $appointment = $rx->appointment;
                                    $avatarColors = ['primary', 'indigo', 'purple'];
                                    $avatarColor = $avatarColors[$loop->index % count($avatarColors)];
                                @endphp
                                <tr>
                                    {{-- Date Column --}}
                                    <td class="ps-4">
                                        <div>
                                            <div class="fw-semibold">
                                                {{ optional($rx->prescribed_date)->format('M d, Y') }}
                                            </div>
                                            <small class="text-muted">
                                                {{ optional($rx->created_at)->format('h:i A') }}
                                            </small>
                                        </div>
                                    </td>

                                    {{-- Patient Column --}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="patient-avatar-small">
                                                {{ strtoupper(substr($patient->name ?? '?', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">
                                                    {{ $patient->name ?? 'Unknown' }}
                                                </div>
                                                <div class="text-muted small">
                                                    DOB: {{ optional($patient->date_of_birth)->format('M d, Y') ?? 'N/A' }}
                                                </div>
                                                @if($patient->mobile)
                                                    <div class="text-muted small">

                                                        {{ $patient->mobile }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Appointment Column --}}
                                    <td>
                                        @if($appointment)
                                            <div>
                                                <span class="badge rounded-pill bg-light text-dark border">
                                                    #{{ $appointment->id }}
                                                </span>
                                                <div class="text-muted small mt-1">
                                                    {{ optional($appointment->appointment_date)->format('M d') }}
                                                </div>
                                                <span class="time-badge">
                                                    <i class="fas fa-clock"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-muted small">No appointment</span>
                                        @endif
                                    </td>

                                    {{-- Chief Complaint Column --}}
                                    <td>
                                        @if($rx->chief_complaint)
                                            <div class="complaint-preview" title="{{ $rx->chief_complaint }}">
                                                {{ \Illuminate\Support\Str::limit($rx->chief_complaint, 50) }}
                                            </div>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>

                                    {{-- Diagnosis Column --}}
                                    <td>
                                        @if($rx->diagnosis)
                                            <div class="diagnosis-preview" title="{{ $rx->diagnosis }}">
                                                {{ \Illuminate\Support\Str::limit($rx->diagnosis, 60) }}
                                            </div>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>

                                    {{-- Status Column --}}
                                    <td>
                                        @php
                                            $status = $rx->status;
                                            $map = [
                                                'active' => ['class' => 'badge-active', 'icon' => 'fas fa-check-circle'],
                                                'pending' => ['class' => 'badge-pending', 'icon' => 'fas fa-clock'],
                                                'completed' => ['class' => 'badge-completed', 'icon' => 'fas fa-check-double'],
                                                'expired' => ['class' => 'badge-expired', 'icon' => 'fas fa-times-circle'],
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
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.patients.prescriptions.show', $rx->id) }}"
                                               class="quick-action-btn quick-action-btn-view"
                                               title="View Prescription">
                                                <i class="fas fa-eye me-1"></i>
                                            </a>

                                            <!-- <a href=""
                                               class="quick-action-btn quick-action-btn-print"
                                               title="Print Prescription" target="_blank">
                                                <i class="fas fa-print me-1"></i>
                                                Print
                                            </a> -->

                                            {{-- <div class="dropdown">
                                                <button class="action-btn dropdown-toggle"
                                                        type="button"
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false"
                                                        title="More Actions">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="fas fa-edit text-primary me-2"></i>
                                                            Edit Prescription
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="fas fa-copy text-info me-2"></i>
                                                            Duplicate
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="#">
                                                            <i class="fas fa-trash-alt me-2"></i>
                                                            Delete Prescription
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div> --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($prescriptions->hasPages())
                    <div class="card-header-primary border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $prescriptions->firstItem() }} to {{ $prescriptions->lastItem() }} of {{ $prescriptions->total() }} prescriptions
                            </div>
                            <div>
                                {{ $prescriptions->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-file-prescription"></i>
                    </div>
                    <h6 class="fw-semibold mb-2">No Prescriptions Found</h6>
                    <p class="text-muted mb-3">
                        @if(request()->has('q'))
                            No prescriptions match your search criteria. Try a different search term.
                        @else
                            There are no prescriptions in the system yet. Create your first prescription!
                        @endif
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        @if(request()->has('q'))
                            <a href="{{ route('admin.patients.prescriptions.index') }}"
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-times me-2"></i> Clear Search
                            </a>
                        @endif
                        <a href=""
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i> Create Prescription
                        </a>
                    </div>
                </div>
            @endif
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
    const searchForm = document.querySelector('.search-box');
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
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-hide flash message after 5 seconds
    const flashMessage = document.querySelector('.flash-message');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.transition = 'opacity 0.5s ease';
            flashMessage.style.opacity = '0';
            setTimeout(() => {
                flashMessage.style.display = 'none';
            }, 500);
        }, 5000);
    }
});
</script>
@endsection
