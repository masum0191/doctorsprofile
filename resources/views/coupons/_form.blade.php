@php
    $isEdit = isset($coupon);
@endphp

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label fw-semibold">Code</label>
        <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code ?? '') }}" placeholder="SAVE10" required>
        @error('code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Type</label>
        <select name="type" class="form-select" required>
            <option value="fixed" {{ old('type', $coupon->type ?? 'fixed')=='fixed'?'selected':'' }}>Fixed</option>
            <option value="percent" {{ old('type', $coupon->type ?? '')=='percent'?'selected':'' }}>Percent</option>
        </select>
        @error('type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Value</label>
        <input type="number" step="0.01" min="0.01" name="value" class="form-control"
               value="{{ old('value', $coupon->value ?? '') }}" required>
        <div class="small text-muted mt-1">Fixed: amount in USD. Percent: % value.</div>
        @error('value')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Min Amount (optional)</label>
        <input type="number" step="0.01" min="0" name="min_amount" class="form-control"
               value="{{ old('min_amount', $coupon->min_amount ?? '') }}">
        @error('min_amount')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Max Discount (optional)</label>
        <input type="number" step="0.01" min="0" name="max_discount" class="form-control"
               value="{{ old('max_discount', $coupon->max_discount ?? '') }}">
        <div class="small text-muted mt-1">Used for percent coupons cap.</div>
        @error('max_discount')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Usage Limit (optional)</label>
        <input type="number" min="1" name="usage_limit" class="form-control"
               value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}">
        @error('usage_limit')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Usage Limit / User (optional)</label>
        <input type="number" min="1" name="usage_limit_per_user" class="form-control"
               value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user ?? '') }}">
        @error('usage_limit_per_user')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Starts At (optional)</label>
        <input type="datetime-local" name="starts_at" class="form-control"
               value="{{ old('starts_at', isset($coupon->starts_at) ? $coupon->starts_at->format('Y-m-d\TH:i') : '') }}">
        @error('starts_at')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Expires At (optional)</label>
        <input type="datetime-local" name="expires_at" class="form-control"
               value="{{ old('expires_at', isset($coupon->expires_at) ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}">
        @error('expires_at')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Note (optional)</label>
        <textarea name="note" class="form-control" rows="3">{{ old('note', $coupon->note ?? '') }}</textarea>
        @error('note')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                   id="is_active" {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="is_active">Active</label>
        </div>
        @error('is_active')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
</div>
