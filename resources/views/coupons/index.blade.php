@extends('layouts.supperadmin')
@section('title', 'Coupons Management')

@section('content')
<style>
    /* Coupon-specific enhancements */
    .coupon-code {
        background: linear-gradient(135deg, #318069 0%, #2a6d5a 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        letter-spacing: 1px;
        box-shadow: 0 2px 8px rgba(49, 128, 105, 0.2);
        display: inline-block;
    }
    
    .coupon-value {
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .coupon-stats {
        background: #fff;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #318069;
    }
    
    .usage-progress {
        height: 6px;
        background-color: #e5e7eb;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 0.5rem;
    }
    
    .usage-progress-bar {
        height: 100%;
        background: linear-gradient(135deg, #318069 0%, #2a6d5a 100%);
        border-radius: 3px;
    }
    
    .validity-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .quick-actions {
        display: flex;
        gap: 0.5rem;
    }
</style>

<div class="pb-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold">Coupons Management</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('coupons.create') }}" class="btn btn-primary btn-sm px-3 py-2">
                <i class="fas fa-plus me-2"></i>Create Coupon
            </a>
            
        </div>
    </div>

    <!-- Quick Stats Bar -->
    <div class="coupon-stats">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-4">
                    <div>
                        <div class="text-muted small mb-1">Total Coupons</div>
                        <div class="h4 fw-bold mb-0">{{ $coupons->total() }}</div>
                    </div>
                    <div class="vr"></div>
                    <div>
                        <div class="text-muted small mb-1">Active</div>
                        <div class="h4 fw-bold mb-0 text-success">{{ $coupons->where('is_active', true)->count() }}</div>
                    </div>
                    <div class="vr"></div>
                    <div>
                        <div class="text-muted small mb-1">Expired</div>
                        @php
                            $now = now();
                            $expiredCount = $coupons->filter(function($coupon) use ($now) {
                                return $coupon->expires_at && $coupon->expires_at < $now;
                            })->count();
                        @endphp
                        <div class="h4 fw-bold mb-0 text-warning">{{ $expiredCount }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="quick-actions justify-content-end">
                    <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    @if(request()->hasAny(['status','type','valid','search']))
                <a href="{{ route('coupons.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-redo"></i>
                </a>
            @endif
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i> Export</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if ($coupons->isEmpty())
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-ticket-alt fa-3x text-gray-300 mb-3"></i>
                        <h5 class="text-gray-600 mb-2">No Coupons Found</h5>
                        <p class="text-gray-400 mb-3">Start by creating your first discount coupon</p>
                        <a href="{{ route('coupons.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Create Coupon
                        </a>
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">COUPON DETAILS</th>
                                <th>DISCOUNT</th>
                                <th>USAGE</th>
                                <th>VALIDITY</th>
                                <th>STATUS</th>
                                <th class="text-end pe-4">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                                @php
                                    $now = now();
                                    $validNow = $coupon->is_active
                                        && (!$coupon->starts_at || $coupon->starts_at <= $now)
                                        && (!$coupon->expires_at || $coupon->expires_at >= $now)
                                        && ($coupon->usage_limit === null || $coupon->used_count < $coupon->usage_limit);
                                    
                                    // Calculate usage percentage
                                    $usagePercentage = $coupon->usage_limit ? 
                                        min(100, ($coupon->used_count / $coupon->usage_limit) * 100) : 0;
                                @endphp
                                <tr class="border-bottom">
                                    <td class="ps-3">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="coupon-code">
                                                {{ $coupon->code }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold mb-1">
                                                    {{ $coupon->note ? Str::limit($coupon->note, 40) : 'No description' }}
                                                </div>
                                                <div class="small text-muted">
                                                    Created: {{ $coupon->created_at->format('M d, Y') }}
                                                    @if($coupon->usage_limit_per_user)
                                                        · {{ $coupon->usage_limit_per_user }} per user
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="coupon-value">
                                            @if($coupon->type === 'percent')
                                                {{ rtrim(rtrim(number_format($coupon->value, 2), '0'), '.') }}%
                                            @else
                                                ৳{{ number_format($coupon->value, 2) }}
                                            @endif
                                        </div>
                                        @if($coupon->type === 'percent' && $coupon->max_discount)
                                            <div class="text-muted small">
                                                Max: ৳{{ number_format($coupon->max_discount, 2) }}
                                            </div>
                                        @endif
                                        @if($coupon->min_amount)
                                            <div class="text-muted small">
                                                Min: ৳{{ number_format($coupon->min_amount, 2) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="fw-medium">
                                                {{ $coupon->used_count }}
                                                @if($coupon->usage_limit)
                                                    / {{ $coupon->usage_limit }}
                                                @else
                                                    / ∞
                                                @endif
                                            </div>
                                            @if($coupon->usage_limit)
                                                <div class="usage-progress">
                                                    <div class="usage-progress-bar" style="width: {{ $usagePercentage }}%"></div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            @if($coupon->expires_at)
                                                <div class="text-muted mb-1">
                                                    {{ $coupon->expires_at->format('M d, Y') }}
                                                </div>
                                            @endif
                                            <div class="mt-1">
                                                @if($validNow)
                                                    <span class="validity-badge bg-success bg-opacity-10 text-success">
                                                        <i class="fas fa-check-circle"></i> Valid
                                                    </span>
                                                @elseif($coupon->expires_at && $coupon->expires_at < $now)
                                                    <span class="validity-badge bg-warning bg-opacity-10 text-warning">
                                                        <i class="fas fa-clock"></i> Expired
                                                    </span>
                                                @else
                                                    <span class="validity-badge bg-secondary bg-opacity-10 text-secondary">
                                                        <i class="fas fa-ban"></i> Inactive
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($coupon->is_active)
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <!-- <a href="{{ route('coupons.show', $coupon) }}" class="act-btn upload-btn" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a> -->
                                            <a href="{{ route('coupons.edit', $coupon) }}" class="act-btn edit-btn" title="Edit">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <form action="{{ route('coupons.toggle', $coupon) }}" method="POST" class="d-inline">
                                                @csrf @method('PUT')
                                                @if($coupon->is_active)
                                                    <button type="submit" class="act-btn feature-btn" title="Deactivate">
                                                        <i class="fas fa-pause"></i>
                                                    </button>
                                                @else
                                                    <button type="submit" class="act-btn feature-btn" title="Activate">
                                                        <i class="fas fa-play"></i>
                                                    </button>
                                                @endif
                                            </form>
                                            <button type="button"
                                                    class="act-btn delete-btn"
                                                    onclick="confirmCouponDelete('{{ $coupon->id }}', '{{ addslashes($coupon->code) }}')"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        
        @if(!$coupons->isEmpty())
        <div class="card-footer border-top bg-white py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing <span class="fw-semibold">{{ $coupons->firstItem() }}–{{ $coupons->lastItem() }}</span> 
                    of <span class="fw-semibold">{{ $coupons->total() }}</span> coupons
                </div>
                @if($coupons->hasPages())
                <div>
                    {{ $coupons->links('vendor.pagination.bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom p-3">
                <h5 class="modal-title fw-semibold">
                    <i class="fas fa-filter me-2"></i>Filter Coupons
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('coupons.index') }}">
                <div class="modal-body p-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                                <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Type</label>
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="fixed" {{ request('type')=='fixed'?'selected':'' }}>Fixed Amount</option>
                                <option value="percent" {{ request('type')=='percent'?'selected':'' }}>Percentage</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Validity</label>
                            <select name="valid" class="form-select">
                                <option value="">All</option>
                                <option value="valid_now" {{ request('valid')=='valid_now'?'selected':'' }}>Currently Valid</option>
                                <option value="expired" {{ request('valid')=='expired'?'selected':'' }}>Expired</option>
                                <option value="upcoming" {{ request('valid')=='upcoming'?'selected':'' }}>Scheduled</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="form-control"
                                   placeholder="Coupon code or note...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCouponModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="deleteCouponForm" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" id="actualCouponId">
                <div class="modal-header border-bottom p-3">
                    <h5 class="modal-title text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Delete Coupon
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="alert alert-warning border-0 py-2 mb-3">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        This action cannot be undone.
                    </div>

                    <p id="deleteCouponText" class="mb-3"></p>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">
                            Type <code id="couponCodeDisplay" class="text-danger">COUPON-CODE</code> to confirm:
                        </label>
                        <input type="text"
                               id="couponCodeInput"
                               class="form-control"
                               placeholder="Enter coupon code"
                               autocomplete="off">
                        <div class="invalid-feedback small" id="couponError">Coupon code does not match.</div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit"
                            class="btn btn-danger"
                            id="deleteCouponButton"
                            disabled>
                        <i class="fas fa-trash me-1"></i> Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize delete modal
    const deleteCouponModal = new bootstrap.Modal(document.getElementById('deleteCouponModal'));
    
    // Delete confirmation functionality
    const couponCodeInput = document.getElementById('couponCodeInput');
    const deleteCouponButton = document.getElementById('deleteCouponButton');
    const couponError = document.getElementById('couponError');
    const couponCodeDisplay = document.getElementById('couponCodeDisplay');

    // Global confirmCouponDelete function
    window.confirmCouponDelete = function(id, code) {
        const form = document.getElementById('deleteCouponForm');
        form.setAttribute('action', `/coupons/${id}`);
        document.getElementById('actualCouponId').value = id;

        // Update modal text
        document.getElementById('deleteCouponText').innerHTML =
            `Are you sure you want to delete coupon <strong>${code}</strong>?`;

        couponCodeDisplay.textContent = code;

        // Reset inputs
        couponCodeInput.value = '';
        couponCodeInput.classList.remove('is-invalid', 'is-valid');
        deleteCouponButton.disabled = true;
        couponError.style.display = 'none';

        // Show modal
        deleteCouponModal.show();

        // Focus on code input
        setTimeout(() => {
            couponCodeInput.focus();
        }, 300);
    };

    // Validate coupon code input
    function validateCouponDeleteForm() {
        const enteredCode = couponCodeInput.value.trim();
        const actualCode = couponCodeDisplay.textContent.trim();

        let isValid = true;
        let errorMessage = '';

        if (enteredCode === '') {
            couponCodeInput.classList.remove('is-invalid', 'is-valid');
            isValid = false;
        } else if (enteredCode !== actualCode) {
            couponCodeInput.classList.remove('is-valid');
            couponCodeInput.classList.add('is-invalid');
            errorMessage = `Code must be exactly "${actualCode}"`;
            isValid = false;
        } else {
            couponCodeInput.classList.remove('is-invalid');
            couponCodeInput.classList.add('is-valid');
        }

        if (errorMessage) {
            couponError.textContent = errorMessage;
            couponError.style.display = 'block';
        } else {
            couponError.style.display = 'none';
        }

        deleteCouponButton.disabled = !isValid;
        return isValid;
    }

    // Event listeners
    couponCodeInput.addEventListener('input', validateCouponDeleteForm);

    document.getElementById('deleteCouponForm').addEventListener('submit', function(e) {
        const enteredCode = couponCodeInput.value.trim();
        const actualCode = couponCodeDisplay.textContent.trim();

        if (enteredCode !== actualCode) {
            e.preventDefault();
            validateCouponDeleteForm();
            couponCodeInput.focus();
        }
    });
});
</script>
@endsection