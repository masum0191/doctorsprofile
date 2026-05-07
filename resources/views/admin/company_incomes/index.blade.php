@extends('layouts.supperadmin')

@section('title', 'Company Income Report')

@section('content')

<style>
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.1);
        --primary-dark: #276854;
        --primary-soft: rgba(49, 128, 105, 0.05);
    }

    /* Stats Cards */
    .stats-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(49, 128, 105, 0.1);
        border-color: var(--primary);
    }

    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1.5rem;
    }

    .stats-title {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        font-weight: 600;
    }

    .stats-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.2;
    }

    /* Filter Card */
    .filter-card {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: white;
        transition: all 0.2s ease;
    }

    .filter-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Table Styles */
    .data-table {
        border-radius: 12px;
        overflow: hidden;
    }

    .data-table thead th {
        background: #f8fafc;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 1rem;
        border-bottom: 2px solid var(--primary-light);
    }

    .data-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #e5e7eb;
    }

    .data-table tbody tr:hover {
        background: var(--primary-soft);
    }

    .data-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        color: #1f2937;
        font-size: 0.875rem;
    }

    /* Status Badges */
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .status-paid {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .status-failed {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    /* Reference Badge */
    .ref-badge {
        background: #f3f4f6;
        color: #4b5563;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        font-family: monospace;
        display: inline-block;
    }

    /* Amount Styling */
    .amount {
        font-weight: 700;
        color: #1f2937;
    }

    .profit {
        font-weight: 600;
        color: var(--primary);
    }

    /* Status Select */
    .status-select {
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s ease;
        background: white;
    }

    .status-select:hover {
        border-color: var(--primary);
    }

    .status-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(49, 128, 105, 0.1);
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
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
        margin: 0 auto 1.5rem;
    }

    .empty-state-icon i {
        font-size: 2.5rem;
        color: var(--primary);
    }

    /* Form Controls */
    .form-label {
        font-weight: 600;
        font-size: 0.75rem;
        color: #374151;
        margin-bottom: 0.375rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-card {
            margin-bottom: 1rem;
        }
        
        .stats-value {
            font-size: 1.25rem;
        }
        
        .data-table thead {
            display: none;
        }
        
        .data-table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem;
        }
        
        .data-table tbody td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem;
            border: none;
            border-bottom: 1px dashed #e5e7eb;
        }
        
        .data-table tbody td:last-child {
            border-bottom: none;
        }
        
        .data-table tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--primary);
            margin-right: 1rem;
            font-size: 0.8rem;
        }
    }
</style>

{{-- Page Header --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1 text-gray-800">
            <i class="ri-bar-chart-2-line text-primary me-2"></i>
            Company Income Report
        </h4>
        <p class="text-muted mb-0 small">Track and analyze company revenue and profits</p>
    </div>
    <button class="btn btn-outline-primary" onclick="window.print()">
        <i class="ri-printer-line me-1"></i>
        Print Report
    </button>
</div>

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-4">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stats-title mb-1">Total Revenue</p>
                    <h3 class="stats-value mb-0">{{ number_format($totalAmount, 2) }} ৳</h3>
                    <small class="text-muted">Gross income from all sources</small>
                </div>
                <div class="stats-icon">
                    <i class="ri-money-dollar-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stats-title mb-1">Company Profit</p>
                    <h3 class="stats-value mb-0">{{ number_format($totalCompanyProfit, 2) }} ৳</h3>
                    <small class="text-muted">Net profit after deductions</small>
                </div>
                <div class="stats-icon">
                    <i class="ri-bar-chart-2-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stats-title mb-1">Total Transactions</p>
                    <h3 class="stats-value mb-0">{{ $incomes->total() }}</h3>
                    <small class="text-muted">Total payment records</small>
                </div>
                <div class="stats-icon">
                    <i class="ri-file-list-3-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter Card --}}
<div class="filter-card mb-4">
    <div class="card-body p-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">
                    <i class="ri-calendar-line me-1"></i> From Date
                </label>
                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    <i class="ri-calendar-line me-1"></i> To Date
                </label>
                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    <i class="ri-filter-3-line me-1"></i> Payment Status
                </label>
                <select name="payment_status" class="form-select">
                    <option value="">All Status</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>✅ Paid</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>❌ Failed</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-search-line me-1"></i> Apply Filter
                    </button>
                    <a href="" class="btn btn-light">
                        <i class="ri-refresh-line"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Transactions Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom p-3">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-semibold">
                <i class="ri-history-line text-primary me-1"></i>
                Transaction History
            </h6>
            <span class="badge bg-primary-soft text-primary px-3 py-1 rounded-pill">
                {{ $incomes->total() }} Records
            </span>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="data-table table mb-0">
                <thead>
                    <tr>
                        <th style="width: 120px">Reference No</th>
                        <th>Doctor</th>
                        <th>Patient</th>
                        <th style="width: 100px">Amount</th>
                        <th style="width: 120px">Company Profit</th>
                        <th style="width: 100px">Status</th>
                        <th style="width: 120px">Update Status</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($incomes as $income)
                    <tr>
                        <td data-label="Reference No">
                            <span class="ref-badge">#{{ $income->reference_no }}</span>
                        </td>
                        <td data-label="Doctor">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle p-1" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; background: var(--primary-light);">
                                    <i class="ri-user-line text-primary" style="font-size: 0.8rem;"></i>
                                </div>
                                <span class="fw-medium">{{ $income->doctor->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td data-label="Patient">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-user-heart-line text-muted"></i>
                                <span>{{ $income->patient_name }}</span>
                            </div>
                        </td>
                        <td data-label="Amount">
                            <span class="amount">{{ number_format($income->amount, 2) }} ৳</span>
                        </td>
                        <td data-label="Company Profit">
                            <span class="profit">{{ number_format($income->company_profit, 2) }} ৳</span>
                        </td>
                        <td data-label="Status">
                            <span class="status-badge status-{{ $income->payment_status }}">
                                <i class="ri-checkbox-circle-fill" style="font-size: 0.6rem;"></i>
                                {{ ucfirst($income->payment_status) }}
                            </span>
                        </td>
                        <td data-label="Update Status">
                            <form method="POST" action="{{ route('superadmin.company.income.status', $income->id) }}" class="d-inline">
                                @csrf
                                <select name="payment_status" class="status-select" onchange="this.form.submit()">
                                    <option value="pending" {{ $income->payment_status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="paid" {{ $income->payment_status == 'paid' ? 'selected' : '' }}>✅ Paid</option>
                                    <option value="failed" {{ $income->payment_status == 'failed' ? 'selected' : '' }}>❌ Failed</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="ri-bar-chart-2-line"></i>
                                </div>
                                <h5 class="fw-semibold text-gray-800 mb-2">No Transactions Found</h5>
                                <p class="text-muted mb-0">No income records match your filter criteria</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($incomes->total() > 0)
    <div class="card-footer bg-white border-top p-3">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
            <div class="text-muted small">
                Showing {{ $incomes->firstItem() ?? 0 }} to {{ $incomes->lastItem() ?? 0 }} of {{ $incomes->total() }} transactions
            </div>
            <div>
                {{ $incomes->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    // Add data-label attributes for responsive table
    document.addEventListener('DOMContentLoaded', function() {
        const tableHeaders = document.querySelectorAll('.data-table thead th');
        const tableRows = document.querySelectorAll('.data-table tbody tr');
        
        tableRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            cells.forEach((cell, index) => {
                if (tableHeaders[index]) {
                    const headerText = tableHeaders[index].textContent.trim();
                    cell.setAttribute('data-label', headerText);
                }
            });
        });
    });
</script>
@endpush