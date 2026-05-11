@extends('layouts.supperadmin')
@section('title', 'Subscriptions')

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
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
    }

    /* Filter Card */
    .filter-card {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: white;
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

    .status-active {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .status-expired {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .status-cancelled {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
    }

    /* Package Badge */
    .package-badge {
        background: var(--primary-light);
        color: var(--primary);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-block;
    }

    /* Billing Badge */
    .billing-badge {
        background: #f3f4f6;
        color: #4b5563;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-block;
    }

    .billing-yearly {
        background: rgba(139, 92, 246, 0.1);
        color: #7c3aed;
    }

    .billing-monthly {
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
    }

    /* Action Buttons */
    .action-btn {
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 500;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        border: none;
        cursor: pointer;
    }

    .action-btn.approve {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .action-btn.approve:hover {
        background: #10b981;
        color: white;
    }

    .action-btn.extend {
        background: rgba(49, 128, 105, 0.1);
        color: #318069;
    }

    .action-btn.extend:hover {
        background: #318069;
        color: white;
    }

    .action-btn.cancel {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .action-btn.cancel:hover {
        background: #ef4444;
        color: white;
    }

    .action-btn.mail {
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
    }

    .action-btn.mail:hover {
        background: #3b82f6;
        color: white;
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

    /* Modal Enhancement */
    .modal-content {
        border-radius: 16px;
        border: 1px solid #e5e7eb;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 1.25rem 1.5rem;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    /* Amount Styling */
    .amount {
        font-weight: 700;
        color: #1f2937;
        font-size: 0.9rem;
    }

    /* Doctor Cell */
    .doctor-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .doctor-avatar {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 0.9rem;
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

    /* Responsive */
    @media (max-width: 768px) {
        .stats-card {
            margin-bottom: 1rem;
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
        
        .action-buttons {
            flex-wrap: wrap;
            justify-content: flex-end;
        }
    }
</style>

{{-- Page Header --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-3">
    <div>
        <h4 class="fw-bold mb-1 text-gray-800">
            <i class="ri-subscribe-line text-primary me-2"></i>
            Subscription Management
        </h4>
    </div>
</div>

{{-- Stats Cards --}}
<!-- <div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stats-title mb-1">Total</p>
                    <h3 class="stats-value mb-0">{{ $subscriptions->total() }}</h3>
                </div>
                <div class="stats-icon">
                    <i class="ri-subscribe-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stats-title mb-1">Active</p>
                    <h3 class="stats-value mb-0">{{ $subscriptions->where('status', 'active')->count() }}</h3>
                </div>
                <div class="stats-icon">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stats-title mb-1">Pending</p>
                    <h3 class="stats-value mb-0">{{ $subscriptions->where('status', 'pending')->count() }}</h3>
                </div>
                <div class="stats-icon">
                    <i class="ri-time-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stats-title mb-1">Expired</p>
                    <h3 class="stats-value mb-0">{{ $subscriptions->where('status', 'expired')->count() }}</h3>
                </div>
                <div class="stats-icon">
                    <i class="ri-alert-line"></i>
                </div>
            </div>
        </div>
    </div>
</div> -->

{{-- Filter Card --}}
<div class="filter-card mb-4">
    <div class="card-body p-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">
                    <i class="ri-filter-3-line me-1"></i> Status
                </label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>✅ Active</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>❌ Expired</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>🚫 Cancelled</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">
                    <i class="ri-price-tag-3-line me-1"></i> Package
                </label>
                <select name="package" class="form-select">
                    <option value="">All Packages</option>
                    @foreach ($packages as $pkg)
                        <option value="{{ $pkg->id }}" {{ request('package') == $pkg->id ? 'selected' : '' }}>
                            {{ $pkg->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">
                    <i class="ri-calendar-line me-1"></i> From Date
                </label>
                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label">
                    <i class="ri-calendar-line me-1"></i> To Date
                </label>
                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
            </div>

            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-search-line me-1"></i> Apply Filter
                    </button>
                    <a href="{{ route('superadmin.subscriptions.index') }}" class="btn btn-light">
                        <i class="ri-refresh-line"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Subscriptions Table --}}
<div class="card border-0 shadow-sm">
 

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="data-table table mb-0">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Package</th>
                        <th>Billing</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Expires</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($subscriptions as $sub)
                    <tr>
                        <td data-label="Doctor">
                            <div class="doctor-info">
                                <div class="doctor-avatar">
                                    <i class="ri-user-line"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-gray-800">{{ $sub->doctor->name }}</div>
                                    <small class="text-muted">{{ $sub->doctor->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td data-label="Package">
                            <span class="package-badge">
                                <i class="ri-price-tag-3-line me-1" style="font-size: 0.7rem;"></i>
                                {{ $sub->package->name }}
                            </span>
                        </td>
                        <td data-label="Billing">
                            <span class="billing-badge billing-{{ $sub->billing_cycle }}">
                                <i class="ri-{{ $sub->billing_cycle == 'yearly' ? 'calendar-line' : 'calendar-month-line' }} me-1"></i>
                                {{ ucfirst($sub->billing_cycle) }}
                            </span>
                        </td>
                        <td data-label="Amount">
                            <span class="amount">
                                {{ number_format($sub->billing_cycle == 'yearly' ? $sub->package->price_yearly : $sub->package->price_monthly, 2) }} $
                            </span>
                        </td>
                        <td data-label="Status">
                            <span class="status-badge status-{{ $sub->status }}">
                                <i class="ri-{{ $sub->status == 'active' ? 'checkbox-circle-fill' : ($sub->status == 'pending' ? 'time-fill' : 'close-circle-fill') }}" style="font-size: 0.6rem;"></i>
                                {{ ucfirst($sub->status) }}
                            </span>
                            @if($sub->status == 'active' && \Carbon\Carbon::parse($sub->ends_at)->diffInDays(now()) <= 7)
                                <span class="badge bg-warning text-dark ms-1" style="font-size: 0.6rem;">
                                    Expiring soon
                                </span>
                            @endif
                        </td>
                        <td data-label="Expires On">
                            <div>
                                <div class="fw-medium">{{ \Carbon\Carbon::parse($sub->ends_at)->format('d M Y') }}</div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($sub->ends_at)->diffForHumans() }}</small>
                            </div>
                        </td>
                        <td data-label="Actions" class="text-end">
                            <div class="d-flex gap-1 justify-content-end flex-wrap">
                                {{-- Approve --}}
                                @if ($sub->status == 'pending')
                                    <form method="POST" action="{{ route('superadmin.subscriptions.approve', $sub->id) }}" class="d-inline">
                                        @csrf
                                        <button class="action-btn approve" title="Approve Subscription">
                                            <i class="ri-check-line"></i>
                                            Approve
                                        </button>
                                    </form>
                                @endif

                                {{-- Extend --}}
                                @if ($sub->status == 'active')
                                    <form method="POST" action="{{ route('superadmin.subscriptions.extend', $sub->id) }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="days" value="30">
                                        <button class="action-btn extend" title="Extend by 30 Days">
                                            <i class="ri-add-line"></i>
                                            +30 Days
                                        </button>
                                    </form>
                                @endif

                                {{-- Cancel --}}
                                <form method="POST" action="{{ route('superadmin.subscriptions.cancel', $sub->id) }}" class="d-inline">
                                    @csrf
                                    <button class="action-btn cancel" title="Cancel Subscription" onclick="return confirm('Cancel this subscription?')">
                                        <i class="ri-close-line"></i>
                                        Cancel
                                    </button>
                                </form>

                                {{-- Send Mail --}}
                                <button class="action-btn mail" data-bs-toggle="modal" data-bs-target="#mailModal{{ $sub->id }}" title="Send Email">
                                    <i class="ri-mail-send-line"></i>
                                    Mail
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- MAIL MODAL --}}
                    <div class="modal fade" id="mailModal{{ $sub->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('superadmin.subscriptions.sendMail', $sub->id) }}">
                                    @csrf
                                    <div class="modal-header">
                                        <div>
                                            <h5 class="modal-title mb-0">Send Email to Doctor</h5>
                                            <small class="opacity-75">To: {{ $sub->doctor->email }}</small>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <label class="form-label">Message Content</label>
                                        <textarea name="message" class="form-control" rows="6" 
                                            placeholder="Write your message here...&#10;&#10;Example:&#10;Dear Dr. {{ $sub->doctor->name }},&#10;&#10;Your subscription to {{ $sub->package->name }} package is about to expire on {{ \Carbon\Carbon::parse($sub->ends_at)->format('d M Y') }}. Please renew to continue enjoying our services.&#10;&#10;Best regards,&#10;Admin Team" required></textarea>
                                        <small class="text-muted mt-2 d-block">
                                            <i class="ri-information-line"></i> This email will be sent to the doctor's registered email address
                                        </small>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-mail-send-line me-1"></i> Send Email
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="ri-subscribe-line"></i>
                                </div>
                                <h5 class="fw-semibold text-gray-800 mb-2">No Subscriptions Found</h5>
                                <p class="text-muted mb-0">No subscription records match your filter criteria</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($subscriptions->total() > 0)
    <div class="card-footer bg-white border-top p-3">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
            <div class="text-muted small">
                Showing {{ $subscriptions->firstItem() ?? 0 }} to {{ $subscriptions->lastItem() ?? 0 }} of {{ $subscriptions->total() }} subscriptions
            </div>
            <div>
                {{ $subscriptions->appends(request()->query())->links('pagination::bootstrap-5') }}
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