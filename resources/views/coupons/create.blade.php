@extends('layouts.supperadmin')
@section('title', 'Create Coupon')

@section('content')
<style>
    .form-section {
        background: #fff;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e5e7eb
    }
    
    .coupon-prev{
         background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e5e7eb
    }
    .form-footer{
         background: #fff;
        border-radius: 12px;
        padding: 1rem;
        border: 1px solid #e5e7eb
    }
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.375rem;
    }
    
    .form-hint {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    
    .preview-section {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 2rem;
    }
    
    .preview-coupon {
        background: linear-gradient(135deg, #318069 0%, #2a6d5a 100%);
        color: white;
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
        letter-spacing: 1px;
        box-shadow: 0 4px 12px rgba(49, 128, 105, 0.2);
        margin-bottom: 1rem;
    }
    
    .preview-details {
        background: #f8fafc;
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1rem;
    }
    
    .preview-label {
        font-size: 0.75rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }
    
    .preview-value {
        font-weight: 500;
        color: #374151;
    }
    
    .quick-tips {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 1px solid #bae6fd;
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 2rem;
    }
    
    .tip-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    
    .tip-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #318069;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        flex-shrink: 0;
    }
</style>

<div class="pb-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold">Create New Coupon</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Form Column -->
        <div class="col-lg-8">
            <form method="POST" action="{{ route('coupons.store') }}" id="couponForm">
                @csrf
                
                <!-- Coupon Basics Section -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-tag"></i>
                        Coupon Basics
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Coupon Code *</label>
                                <input type="text" 
                                       name="code" 
                                       class="form-control" 
                                       value="{{ old('code') }}" 
                                       placeholder="e.g., SAVE10"
                                       required>
                                <div class="form-hint">Unique code users will enter at checkout</div>
                                @error('code')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Type *</label>
                                <select name="type" class="form-select" required onchange="toggleMaxDiscount()">
                                    <option value="fixed" {{ old('type') == 'percent' ? '' : 'selected' }}>Fixed Amount</option>
                                    <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Percentage</option>
                                </select>
                                @error('type')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Discount Value *</label>
                                <div class="input-group">
                                    <input type="number" 
                                           step="0.01" 
                                           min="0.01" 
                                           name="value" 
                                           class="form-control" 
                                           value="{{ old('value') }}" 
                                           required>
                                    <span class="input-group-text" id="valueSuffix">৳</span>
                                </div>
                                <div class="form-hint" id="valueHint">
                                    Fixed amount in ৳
                                </div>
                                @error('value')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Maximum Discount</label>
                                <input type="number" 
                                       step="0.01" 
                                       min="0" 
                                       name="max_discount" 
                                       class="form-control"
                                       value="{{ old('max_discount') }}"
                                       placeholder="e.g., 1000"
                                       id="maxDiscountInput"
                                       disabled>
                                <div class="form-hint">Maximum discount amount for percentage coupons</div>
                                @error('max_discount')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Requirements & Limits Section -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-sliders-h"></i>
                        Requirements & Limits
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Minimum Order Amount</label>
                                <div class="input-group">
                                    <input type="number" 
                                           step="0.01" 
                                           min="0" 
                                           name="min_amount" 
                                           class="form-control"
                                           value="{{ old('min_amount') }}"
                                           placeholder="e.g., 500">
                                    <span class="input-group-text">৳</span>
                                </div>
                                <div class="form-hint">Minimum order value to use this coupon</div>
                                @error('min_amount')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Total Usage Limit</label>
                                <input type="number" 
                                       min="1" 
                                       name="usage_limit" 
                                       class="form-control"
                                       value="{{ old('usage_limit') }}"
                                       placeholder="e.g., 100">
                                <div class="form-hint">Leave empty for unlimited usage</div>
                                @error('usage_limit')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Per User Limit</label>
                                <input type="number" 
                                       min="1" 
                                       name="usage_limit_per_user" 
                                       class="form-control"
                                       value="{{ old('usage_limit_per_user') }}"
                                       placeholder="e.g., 1">
                                <div class="form-hint">How many times each user can use this coupon</div>
                                @error('usage_limit_per_user')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <div class="mt-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="is_active" 
                                               value="1"
                                               id="is_active" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium" for="is_active">
                                            Active Coupon
                                        </label>
                                    </div>
                                </div>
                                @error('is_active')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Schedule Section -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-calendar-alt"></i>
                        Schedule & Validity
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Start Date & Time</label>
                                <input type="datetime-local" 
                                       name="starts_at" 
                                       class="form-control"
                                       value="{{ old('starts_at') }}">
                                <div class="form-hint">Leave empty for immediate activation</div>
                                @error('starts_at')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Expiry Date & Time</label>
                                <input type="datetime-local" 
                                       name="expires_at" 
                                       class="form-control"
                                       value="{{ old('expires_at') }}">
                                <div class="form-hint">Leave empty for no expiration</div>
                                @error('expires_at')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Description Section -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-align-left"></i>
                        Description
                    </div>
                    <div class="form-group">
                        <label class="form-label">Internal Notes (Optional)</label>
                        <textarea name="note" 
                                  class="form-control" 
                                  rows="3"
                                  placeholder="Add internal notes about this coupon...">{{ old('note') }}</textarea>
                        <div class="form-hint">For internal reference only, not visible to customers</div>
                        @error('note')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
    
                <!-- Form Actions -->
                <div class="form-footer d-flex justify-content-between align-items-center border-top">
                    <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Coupon
                    </button>
                </div>
            </form>
           
        </div>

        <!-- Preview & Tips Column -->
        <div class="col-lg-4">
            <!-- Preview Card -->
            <div class="coupon-prev">
                   <h6 class="fw-bold mb-3">
                        Coupon Preview
                    </h6>
                    
                    <div class="preview-coupon" id="previewCode">
                        {{ old('code') ?: 'COUPONCODE' }}
                    </div>
                    
                    <div class="preview-details">
                        <div class="row">
                            <div class="col-6">
                                <div class="preview-label">Type</div>
                                <div class="preview-value" id="previewType">
                                    Fixed
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="preview-label">Value</div>
                                <div class="preview-value" id="previewValue">
                                    {{ old('value') ?: '0' }}৳
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <div class="preview-label">Minimum Order</div>
                            <div class="preview-value" id="previewMin">
                                {{ old('min_amount') ? number_format(old('min_amount'), 2) . ' ৳' : 'None' }}
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <div class="preview-label">Max Discount</div>
                            <div class="preview-value" id="previewMax">
                                N/A
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <div class="preview-label">Usage Limit</div>
                            <div class="preview-value" id="previewUsage">
                                {{ old('usage_limit') ?: 'Unlimited' }}
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <div class="preview-label">Status</div>
                            <div class="preview-value">
                                <span class="badge bg-success">Active</span>
                            </div>
                        </div>
                    </div>
            </div>
            
            <!-- Quick Tips Card -->
            <div class="quick-tips">
                <h6 class="fw-bold mb-3">
                    <i class="fas fa-lightbulb me-2"></i>
                    Quick Tips
                </h6>
                
                <div class="tip-item">
                    <div class="tip-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div>
                        <div class="fw-medium small">Use Clear Codes</div>
                        <div class="text-muted small">Create codes that are easy to remember like "SAVE20" or "SUMMER25"</div>
                    </div>
                </div>
                
                <div class="tip-item">
                    <div class="tip-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <div class="fw-medium small">Set Usage Limits</div>
                        <div class="text-muted small">Limit total uses to control discount budget</div>
                    </div>
                </div>
                
                <div class="tip-item">
                    <div class="tip-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="fw-medium small">Schedule Wisely</div>
                        <div class="text-muted small">Plan coupon activation around promotions and events</div>
                    </div>
                </div>
                
                <div class="tip-item">
                    <div class="tip-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <div class="fw-medium small">Minimum Order Value</div>
                        <div class="text-muted small">Prevent abuse by setting a reasonable minimum amount</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('couponForm');
    const typeSelect = form.querySelector('select[name="type"]');
    const valueInput = form.querySelector('input[name="value"]');
    const codeInput = form.querySelector('input[name="code"]');
    const minAmountInput = form.querySelector('input[name="min_amount"]');
    const maxDiscountInput = document.getElementById('maxDiscountInput');
    const usageLimitInput = form.querySelector('input[name="usage_limit"]');
    
    // Update preview in real-time
    function updatePreview() {
        document.getElementById('previewCode').textContent = codeInput.value || 'COUPONCODE';
        document.getElementById('previewType').textContent = typeSelect.value === 'percent' ? 'Percentage' : 'Fixed';
        document.getElementById('previewValue').textContent = 
            (valueInput.value || '0') + (typeSelect.value === 'percent' ? '%' : '৳');
        document.getElementById('previewMin').textContent = 
            minAmountInput.value ? parseFloat(minAmountInput.value).toFixed(2) + ' ৳' : 'None';
        document.getElementById('previewMax').textContent = 
            typeSelect.value === 'percent' && maxDiscountInput.value ? 
            parseFloat(maxDiscountInput.value).toFixed(2) + ' ৳' : 'N/A';
        document.getElementById('previewUsage').textContent = 
            usageLimitInput.value ? usageLimitInput.value : 'Unlimited';
    }
    
    // Toggle max discount field
    function toggleMaxDiscount() {
        const isPercent = typeSelect.value === 'percent';
        const valueSuffix = document.getElementById('valueSuffix');
        const valueHint = document.getElementById('valueHint');
        
        valueSuffix.textContent = isPercent ? '%' : '৳';
        valueHint.textContent = isPercent ? 
            'Percentage discount (e.g., 10 for 10%)' : 
            'Fixed amount in ৳';
        
        maxDiscountInput.disabled = !isPercent;
        if (!isPercent) {
            maxDiscountInput.value = '';
        }
        
        updatePreview();
    }
    
    // Initialize
    toggleMaxDiscount();
    
    // Add event listeners for real-time preview
    [codeInput, valueInput, minAmountInput, maxDiscountInput, usageLimitInput].forEach(input => {
        input.addEventListener('input', updatePreview);
    });
    
    typeSelect.addEventListener('change', function() {
        toggleMaxDiscount();
        updatePreview();
    });
    
    // Auto-generate coupon code if empty
    codeInput.addEventListener('blur', function() {
        if (!this.value.trim()) {
            const prefixes = ['SAVE', 'OFF', 'DISCOUNT', 'DEAL', 'PROMO'];
            const randomPrefix = prefixes[Math.floor(Math.random() * prefixes.length)];
            const randomNumber = Math.floor(10 + Math.random() * 90);
            this.value = randomPrefix + randomNumber;
            updatePreview();
        }
    });
    
    // Validate form before submission
    form.addEventListener('submit', function(e) {
        const codeValue = codeInput.value.trim();
        const value = parseFloat(valueInput.value);
        
        if (!codeValue) {
            e.preventDefault();
            alert('Please enter a coupon code');
            codeInput.focus();
            return;
        }
        
        if (isNaN(value) || value <= 0) {
            e.preventDefault();
            alert('Please enter a valid discount value');
            valueInput.focus();
            return;
        }
        
        if (typeSelect.value === 'percent' && value > 100) {
            e.preventDefault();
            alert('Percentage discount cannot exceed 100%');
            valueInput.focus();
            return;
        }
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Creating...';
        submitBtn.disabled = true;
        
        // Re-enable after 5 seconds if submission fails
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
});
</script>
@endsection