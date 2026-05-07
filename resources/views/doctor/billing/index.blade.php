@extends('layouts.admin')
@section('title','Billing & Payments')

@section('content')
<style>
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.1);
        --primary-dark: #2a6d5a;
        --primary-soft: rgba(49, 128, 105, 0.05);
        --primary-hover: rgba(49, 128, 105, 0.15);
    }

    /* Page Header */
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border-left: 4px solid var(--primary);
    }

    .header-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
    }

    .header-subtitle {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    /* Filter Button */
    .btn-filter {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-filter:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(49, 128, 105, 0.2);
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(49, 128, 105, 0.15);
        border-color: var(--primary);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
        border-radius: 4px 0 0 4px;
    }

    .stat-title {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
    }

    .stat-subtitle {
        font-size: 0.75rem;
        color: #9ca3af;
    }

    /* Paid Stat Specific */
    .stat-paid .stat-value {
        color: #059669;
    }

    .stat-paid::before {
        background: #059669;
    }

    /* Unpaid Stat Specific */
    .stat-unpaid .stat-value {
        color: #dc2626;
    }

    .stat-unpaid::before {
        background: #dc2626;
    }

    /* Table Container */
    .table-container {
        background: white;
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .table-header {
        background: var(--primary-soft);
        padding: 1rem 1.5rem;
        border-bottom: 2px solid var(--primary-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-title {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .table-count {
        font-size: 0.875rem;
        color: #6b7280;
    }

    /* Table Styles */
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .custom-table thead th {
        background: var(--primary-soft);
        color: #374151;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 1rem;
        border-bottom: 1px solid var(--primary-light);
        text-align: left;
    }

    .custom-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s ease;
    }

    .custom-table tbody tr:hover {
        background: var(--primary-soft);
    }

    .custom-table tbody tr:last-child {
        border-bottom: none;
    }

    .custom-table tbody td {
        padding: 1rem 1rem;
        color: #374151;
        font-size: 0.875rem;
        vertical-align: middle;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        white-space: nowrap;
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #92400e;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .status-confirmed {
        background: rgba(16, 185, 129, 0.1);
        color: #065f46;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .status-completed {
        background: rgba(59, 130, 246, 0.1);
        color: #1e40af;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .status-cancelled {
        background: rgba(239, 68, 68, 0.1);
        color: #991b1b;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    /* Payment Status Badges */
    .payment-paid {
        background: rgba(16, 185, 129, 0.1);
        color: #065f46;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .payment-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #92400e;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .payment-failed {
        background: rgba(239, 68, 68, 0.1);
        color: #991b1b;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .payment-refunded {
        background: rgba(156, 163, 175, 0.1);
        color: #374151;
        border: 1px solid rgba(156, 163, 175, 0.2);
    }

    /* Consultation Type Badges */
    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        white-space: nowrap;
    }

    .type-in_person {
        background: rgba(139, 92, 246, 0.1);
        color: #5b21b6;
        border: 1px solid rgba(139, 92, 246, 0.2);
    }

    .type-telemedicine {
        background: rgba(16, 185, 129, 0.1);
        color: #065f46;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .type-follow_up {
        background: rgba(249, 115, 22, 0.1);
        color: #9a3412;
        border: 1px solid rgba(249, 115, 22, 0.2);
    }

    /* Patient Info */
    .patient-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .patient-avatar {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: var(--primary-light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
        flex-shrink: 0;
    }

    .patient-details h6 {
        margin: 0;
        font-size: 0.875rem;
        font-weight: 500;
        color: #111827;
    }

    .patient-details p {
        margin: 0.25rem 0 0;
        font-size: 0.75rem;
        color: #6b7280;
    }

    /* Amount Column */
    .amount-cell {
        text-align: right;
        font-family: 'SF Mono', Monaco, 'Cascadia Mono', monospace;
    }

    .amount-value {
        font-weight: 600;
        color: #111827;
        font-size: 0.875rem;
    }

    .amount-currency {
        font-size: 0.75rem;
        color: #6b7280;
        margin-left: 0.25rem;
    }

    .payment-method {
        font-size: 0.75rem;
        color: #9ca3af;
        margin-top: 0.25rem;
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: var(--primary);
        font-size: 1.5rem;
    }

    .empty-state h5 {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #6b7280;
        font-size: 0.875rem;
        max-width: 300px;
        margin: 0 auto 1.5rem;
    }

    /* Pagination */
    .pagination-container {
        padding: 1.5rem;
        border-top: 1px solid #f3f4f6;
        background: white;
    }

    /* Modal Styling */
    .filter-modal .modal-dialog {
        max-width: 500px;
    }

    .filter-modal .modal-content {
        border-radius: 12px;
        border: 1px solid rgba(49, 128, 105, 0.15);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .filter-modal .modal-header {
        background: var(--primary);
        color: white;
        border-radius: 12px 12px 0 0;
        border-bottom: none;
        padding: 1rem 1.5rem;
    }

    .filter-modal .modal-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-modal .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .filter-modal .btn-close:hover {
        opacity: 1;
    }

    .filter-modal .modal-body {
        padding: 1.5rem;
    }

    .filter-modal .modal-footer {
        border-top: 1px solid #f3f4f6;
        padding: 1rem 1.5rem;
        background: var(--primary-soft);
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-weight: 500;
        color: #374151;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #111827;
        transition: all 0.2s ease;
        background: white;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    .form-select {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #111827;
        background: white;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    /* Buttons */
    .btn-primary {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(49, 128, 105, 0.2);
    }

    .btn-secondary {
        background: white;
        color: #374151;
        border: 1px solid #d1d5db;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: var(--primary);
        color: var(--primary);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .table-container {
            overflow-x: auto;
        }

        .custom-table {
            min-width: 800px;
        }
    }
</style>

<div class="">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-receipt me-2"></i>
                    Billing & Payments
                </h1>
            </div>
            <button type="button" class="btn-filter" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter"></i>
                Filter Records
            </button>
        </div>
    </div>



    <!-- Billing Table -->
    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">
                <i class="fas fa-file-invoice"></i>
                Billing Details
            </h3>
            <div class="table-count">
                Showing {{ $appointments->firstItem() ?? 0 }} - {{ $appointments->lastItem() ?? 0 }} of {{ $appointments->total() }}
            </div>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Patient</th>
                        <th>Chamber</th>
                        <th>Type</th>
                        <th class="text-end">Amount</th>
                        <th class="text-center">Payment</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                    <tr>
                        <td>
                            <div class="fw-semibold">
                                {{ optional($appointment->appointment_date)->format('d M Y') }}
                            </div>
                            <div class="text-muted small">
                                {{ $appointment->appointment_time }}
                            </div>
                        </td>

                        <td>
                            <div class="patient-info">
                                <div class="patient-avatar">
                                    @php
                                        $name = $appointment->full_name ?? trim(($appointment->patient_first_name ?? '').' '.($appointment->patient_last_name ?? ''));
                                        $initial = strtoupper(substr($name, 0, 1));
                                    @endphp
                                    {{ $initial }}
                                </div>
                                <div class="patient-details">
                                    <h6>{{ $name }}</h6>
                                    <p>{{ $appointment->patient_phone ?? $appointment->patient_email ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="fw-medium">
                                {{ $appointment->chamber->name ?? '-' }}
                            </div>
                            @if($appointment->service_type)
                            <div class="text-muted small mt-1">
                                {{ $appointment->service_type }}
                            </div>
                            @endif
                        </td>

                        <td>
                            @php
                                $type = $appointment->consultation_type ?? null;
                            @endphp
                            @if($type)
                                <span class="type-badge type-{{ $type }}">
                                    <i class="fas
                                        @if($type == 'in_person') fa-user-md
                                        @elseif($type == 'telemedicine') fa-video
                                        @elseif($type == 'follow_up') fa-redo
                                        @endif
                                    "></i>
                                    {{ \Illuminate\Support\Str::headline($type) }}
                                </span>
                            @else
                                <span class="type-badge">
                                    <i class="fas fa-question"></i>
                                    N/A
                                </span>
                            @endif
                        </td>

                        <td class="amount-cell">
                            <div class="amount-value">
                                ৳ {{ number_format($appointment->amount ?? 0, 2) }}
                                @if($appointment->currency && $appointment->currency != 'BDT')
                                    <span class="amount-currency">{{ $appointment->currency }}</span>
                                @endif
                            </div>
                            @if($appointment->payment_method)
                            <div class="payment-method">
                                <i class="fas
                                    @if($appointment->payment_method == 'cash') fa-money-bill
                                    @elseif($appointment->payment_method == 'card') fa-credit-card
                                    @elseif($appointment->payment_method == 'online') fa-globe
                                    @endif
                                "></i>
                                {{ \Illuminate\Support\Str::headline($appointment->payment_method) }}
                            </div>
                            @endif
                        </td>

                        <td class="text-center">
                            @php
                                $paymentStatus = $appointment->payment_status;
                            @endphp
                            <span class="status-badge payment-{{ $paymentStatus ?? 'none' }}">
                                @if($paymentStatus == 'paid')
                                    <i class="fas fa-check-circle"></i>
                                @elseif($paymentStatus == 'pending')
                                    <i class="fas fa-clock"></i>
                                @elseif($paymentStatus == 'failed')
                                    <i class="fas fa-times-circle"></i>
                                @elseif($paymentStatus == 'refunded')
                                    <i class="fas fa-undo"></i>
                                @endif
                                {{ $paymentStatus ? \Illuminate\Support\Str::headline($paymentStatus) : 'N/A' }}
                            </span>
                        </td>

                        <td class="text-center">
                            @php
                                $status = $appointment->status;
                            @endphp
                            <span class="status-badge status-{{ $status ?? 'none' }}">
                                @if($status == 'confirmed')
                                    <i class="fas fa-check"></i>
                                @elseif($status == 'pending')
                                    <i class="fas fa-clock"></i>
                                @elseif($status == 'completed')
                                    <i class="fas fa-check-double"></i>
                                @elseif($status == 'cancelled')
                                    <i class="fas fa-times"></i>
                                @endif
                                {{ \Illuminate\Support\Str::headline($status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <h5>No billing records found</h5>
                                <p>Try adjusting your filters or add new appointments</p>
                                <button type="button" class="btn-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                    <i class="fas fa-filter me-2"></i>
                                    Adjust Filters
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($appointments->hasPages())
        <div class="pagination-container">
            {{ $appointments->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade filter-modal" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-filter me-2"></i>
                    Filter Billing Records
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('admin.billing.index') }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Date Range</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="date"
                                       name="from"
                                       value="{{ $filters['from'] ?? '' }}"
                                       class="form-control"
                                       placeholder="From date">
                            </div>
                            <div class="col-6">
                                <input type="date"
                                       name="to"
                                       value="{{ $filters['to'] ?? '' }}"
                                       class="form-control"
                                       placeholder="To date">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Appointment Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
                            <option value="confirmed" @selected(($filters['status'] ?? '') === 'confirmed')>Confirmed</option>
                            <option value="completed" @selected(($filters['status'] ?? '') === 'completed')>Completed</option>
                            <option value="cancelled" @selected(($filters['status'] ?? '') === 'cancelled')>Cancelled</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-select">
                            <option value="">All Payment Status</option>
                            <option value="paid" @selected(($filters['payment_status'] ?? '') === 'paid')>Paid</option>
                            <option value="pending" @selected(($filters['payment_status'] ?? '') === 'pending')>Pending</option>
                            <option value="failed" @selected(($filters['payment_status'] ?? '') === 'failed')>Failed</option>
                            <option value="refunded" @selected(($filters['payment_status'] ?? '') === 'refunded')>Refunded</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Consultation Type</label>
                        <select name="consultation_type" class="form-select">
                            <option value="">All Types</option>
                            <option value="in_person" @selected(($filters['consultation_type'] ?? '') === 'in_person')>In-person Visit</option>
                            <option value="telemedicine" @selected(($filters['consultation_type'] ?? '') === 'telemedicine')>Telemedicine</option>
                            <option value="follow_up" @selected(($filters['consultation_type'] ?? '') === 'follow_up')>Follow-up</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <a href="{{ route('admin.billing.index') }}" class="btn-secondary">
                        Reset
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-check me-2"></i>
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize filter modal
        const filterModal = new bootstrap.Modal(document.getElementById('filterModal'));

        // Set today as default "to" date if not set
        const toDateInput = document.querySelector('input[name="to"]');
        if (toDateInput && !toDateInput.value) {
            toDateInput.value = new Date().toISOString().split('T')[0];
        }

        // Set 30 days ago as default "from" date if not set
        const fromDateInput = document.querySelector('input[name="from"]');
        if (fromDateInput && !fromDateInput.value) {
            const thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
            fromDateInput.value = thirtyDaysAgo.toISOString().split('T')[0];
        }

        // Keyboard shortcut for filter modal (Ctrl/Cmd + F)
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                filterModal.show();
            }
        });

        // Animate stats cards on load
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.transform = 'translateY(0)';
                card.style.opacity = '1';
            }, index * 100);
        });
    });
</script>
@endsection
