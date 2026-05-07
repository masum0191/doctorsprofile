@extends('layouts.admin')
@section('title','Billing Report')

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

    /* Back Button */
    .btn-back {
        background: white;
        color: var(--primary);
        border: 1px solid var(--primary);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-back:hover {
        background: var(--primary);
        color: white;
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

    /* Report Container */
    .report-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 1024px) {
        .report-container {
            grid-template-columns: 1fr;
        }
    }

    /* Table Cards */
    .table-card {
        background: white;
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        height: fit-content;
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
        background: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        border: 1px solid var(--primary-light);
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
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        white-space: nowrap;
        text-align: center;
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

    /* Payment Status Badges */
    .badge-paid {
        background: rgba(16, 185, 129, 0.1);
        color: #065f46;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .badge-payment-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #92400e;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .badge-failed {
        background: rgba(239, 68, 68, 0.1);
        color: #991b1b;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .badge-refunded {
        background: rgba(156, 163, 175, 0.1);
        color: #374151;
        border: 1px solid rgba(156, 163, 175, 0.2);
    }

    /* Amount Cells */
    .amount-cell {
        text-align: right;
        font-family: 'SF Mono', Monaco, 'Cascadia Mono', monospace;
    }

    .amount-value {
        font-weight: 600;
        color: #111827;
        font-size: 0.875rem;
    }

    /* Empty State */
    .empty-state {
        padding: 3rem 2rem;
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

    /* Period Indicator */
    .period-indicator {
        background: var(--primary-soft);
        border: 1px solid var(--primary-light);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #374151;
        font-size: 0.875rem;
    }

    .period-indicator i {
        color: var(--primary);
    }

    /* Summary Cards */
    .summary-card {
        background: white;
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-bottom: 1rem;
    }

    .summary-card:last-child {
        margin-bottom: 0;
    }

    .summary-header {
        background: var(--primary-soft);
        padding: 1rem;
        border-bottom: 1px solid var(--primary-light);
    }

    .summary-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .summary-table {
        width: 100%;
        border-collapse: collapse;
    }

    .summary-table th,
    .summary-table td {
        padding: 0.75rem 1rem;
        text-align: left;
        border-bottom: 1px solid #f3f4f6;
    }

    .summary-table th {
        font-weight: 500;
        color: #6b7280;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: var(--primary-soft);
    }

    .summary-table tr:last-child td {
        border-bottom: none;
    }

    .summary-table tbody tr:hover {
        background: var(--primary-soft);
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

    /* Modal Styling */
    .filter-modal .modal-dialog {
        max-width: 400px;
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
        
        .table-card {
            overflow-x: auto;
        }
        
        .custom-table {
            min-width: 600px;
        }
    }
</style>

<div class="">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-chart-bar me-2"></i>
                    Billing Report
                </h1>
              
            </div>
            <div class="d-flex gap-2">
              
                <button type="button" class="btn-filter" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-calendar-alt"></i>
                    Change Period
                </button>
            </div>
        </div>
    </div>

    <!-- Period Indicator -->
    @if($from || $to)
    <div class="period-indicator">
        <i class="fas fa-calendar-week"></i>
        Showing report for period:
        @if($from)
            <strong>{{ \Carbon\Carbon::parse($from)->format('M d, Y') }}</strong>
        @endif
        @if($from && $to) to @endif
        @if($to)
            <strong>{{ \Carbon\Carbon::parse($to)->format('M d, Y') }}</strong>
        @endif
        @if(!$from && !$to)
            All time
        @endif
    </div>
    @endif



    <!-- Report Content -->
    <div class="report-container">
        <!-- Daily Summary -->
        <div class="table-card">
            <div class="table-header">
                <h3 class="table-title">
                    <i class="fas fa-calendar-day me-2"></i>
                    Daily Summary
                </h3>
                <span class="table-count">
                    {{ $daily->count() }} {{ Str::plural('day', $daily->count()) }}
                </span>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th class="text-end">Appointments</th>
                            <th class="text-end">Total Amount</th>
                            <th class="text-end">Paid</th>
                            <th class="text-end">Unpaid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daily as $row)
                        <tr>
                            <td>
                                <div class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($row->appointment_date)->format('d M Y') }}
                                </div>
                                <div class="text-muted small">
                                    {{ \Carbon\Carbon::parse($row->appointment_date)->format('l') }}
                                </div>
                            </td>
                            <td class="text-end">
                                <span class="badge bg-primary-light text-primary rounded-pill px-2 py-1">
                                    {{ number_format($row->total_appointments) }}
                                </span>
                            </td>
                            <td class="amount-cell">
                                <div class="amount-value">
                                    ৳ {{ number_format($row->total_amount, 2) }}
                                </div>
                            </td>
                            <td class="amount-cell">
                                <div class="amount-value text-success">
                                    ৳ {{ number_format($row->paid_amount, 2) }}
                                </div>
                            </td>
                            <td class="amount-cell">
                                <div class="amount-value text-warning">
                                    ৳ {{ number_format($row->unpaid_amount, 2) }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-calendar-day"></i>
                                    </div>
                                    <h5>No daily data found</h5>
                                    <p>Try adjusting your date range</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Side Summaries -->
        <div>
            <!-- By Appointment Status -->
            <div class="summary-card">
                <div class="summary-header">
                    <h4 class="summary-title">
                        <i class="fas fa-clipboard-list me-2"></i>
                        By Appointment Status
                    </h4>
                </div>
                <div class="table-responsive">
                    <table class="summary-table">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th class="text-end">Count</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($byStatus as $row)
                            <tr>
                                <td>
                                    <span class="status-badge 
                                        @if($row->status == 'pending') badge-pending
                                        @elseif($row->status == 'confirmed') badge-confirmed
                                        @elseif($row->status == 'completed') badge-completed
                                        @elseif($row->status == 'cancelled') badge-cancelled
                                        @endif
                                    ">
                                        {{ $row->status ? \Illuminate\Support\Str::headline($row->status) : 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-end fw-semibold">
                                    {{ number_format($row->total_appointments) }}
                                </td>
                                <td class="text-end fw-semibold">
                                    ৳ {{ number_format($row->total_amount, 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">
                                    No status data available
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- By Payment Status -->
            <div class="summary-card">
                <div class="summary-header">
                    <h4 class="summary-title">
                        <i class="fas fa-credit-card me-2"></i>
                        By Payment Status
                    </h4>
                </div>
                <div class="table-responsive">
                    <table class="summary-table">
                        <thead>
                            <tr>
                                <th>Payment Status</th>
                                <th class="text-end">Count</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($byPaymentStatus as $row)
                            <tr>
                                <td>
                                    <span class="status-badge 
                                        @if($row->payment_status == 'paid') badge-paid
                                        @elseif($row->payment_status == 'pending') badge-payment-pending
                                        @elseif($row->payment_status == 'failed') badge-failed
                                        @elseif($row->payment_status == 'refunded') badge-refunded
                                        @endif
                                    ">
                                        {{ $row->payment_status ? \Illuminate\Support\Str::headline($row->payment_status) : 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-end fw-semibold">
                                    {{ number_format($row->total_appointments) }}
                                </td>
                                <td class="text-end fw-semibold">
                                    ৳ {{ number_format($row->total_amount, 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">
                                    No payment data available
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

<!-- Filter Modal -->
<div class="modal fade filter-modal" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Select Report Period
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('admin.billing.report') }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Date Range</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="date" 
                                       name="from" 
                                       value="{{ $from }}" 
                                       class="form-control" 
                                       placeholder="From date">
                            </div>
                            <div class="col-6">
                                <input type="date" 
                                       name="to" 
                                       value="{{ $to }}" 
                                       class="form-control" 
                                       placeholder="To date">
                            </div>
                        </div>
                        <div class="mt-2 text-muted small">
                            Leave empty for "All time"
                        </div>
                    </div>

                    <!-- Quick Date Presets -->
                    <div class="form-group">
                        <label class="form-label">Quick Periods</label>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn-secondary btn-sm preset-btn" data-days="7">
                                Last 7 days
                            </button>
                            <button type="button" class="btn-secondary btn-sm preset-btn" data-days="30">
                                Last 30 days
                            </button>
                            <button type="button" class="btn-secondary btn-sm preset-btn" data-month="this">
                                This Month
                            </button>
                            <button type="button" class="btn-secondary btn-sm preset-btn" data-month="last">
                                Last Month
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <a href="{{ route('admin.billing.report') }}" class="btn-secondary">
                        Reset
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-chart-bar me-2"></i>
                        Generate Report
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
        
        // Set default dates if not set
        const fromInput = document.querySelector('input[name="from"]');
        const toInput = document.querySelector('input[name="to"]');
        
        // Quick date presets
        document.querySelectorAll('.preset-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const days = this.getAttribute('data-days');
                const month = this.getAttribute('data-month');
                const today = new Date();
                
                if (days) {
                    // Last X days
                    const fromDate = new Date(today);
                    fromDate.setDate(fromDate.getDate() - parseInt(days));
                    
                    fromInput.value = fromDate.toISOString().split('T')[0];
                    toInput.value = today.toISOString().split('T')[0];
                } else if (month === 'this') {
                    // This month
                    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                    
                    fromInput.value = firstDay.toISOString().split('T')[0];
                    toInput.value = lastDay.toISOString().split('T')[0];
                } else if (month === 'last') {
                    // Last month
                    const firstDay = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    const lastDay = new Date(today.getFullYear(), today.getMonth(), 0);
                    
                    fromInput.value = firstDay.toISOString().split('T')[0];
                    toInput.value = lastDay.toISOString().split('T')[0];
                }
            });
        });
        
        // Animate stats cards on load
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.transform = 'translateY(0)';
                card.style.opacity = '1';
            }, index * 100);
        });
        
        // Keyboard shortcut for filter modal (Ctrl/Cmd + P)
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                filterModal.show();
            }
        });
        
        // Highlight today's date in daily summary
        const today = new Date().toISOString().split('T')[0];
        document.querySelectorAll('.custom-table tbody tr').forEach(row => {
            const dateCell = row.querySelector('td:first-child');
            if (dateCell && dateCell.textContent.includes(new Date().getDate().toString())) {
                row.style.background = 'rgba(49, 128, 105, 0.05)';
                row.style.borderLeft = '3px solid var(--primary)';
            }
        });
    });
</script>
@endsection