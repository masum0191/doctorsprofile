@php
    $isEdit = isset($managedUser);
    $selectedRole = old('role', $managedUser->role ?? 'user');
    $selectedTenant = old('tenant_id', $managedUser->tenant_id ?? '');
    $selectedStatus = (string) old('status', isset($managedUser) ? (int) $managedUser->status : 1);
@endphp

@csrf
@if($isEdit)
    @method('PUT')
@endif

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $managedUser->name ?? '') }}"
               class="form-control @error('name') is-invalid @enderror" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
        <input type="email" name="email" value="{{ old('email', $managedUser->email ?? '') }}"
               class="form-control @error('email') is-invalid @enderror" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Mobile</label>
        <input type="text" name="mobile" value="{{ old('mobile', $managedUser->mobile ?? '') }}"
               class="form-control @error('mobile') is-invalid @enderror">
        @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            @foreach($roles as $value => $label)
                <option value="{{ $value }}" @selected($selectedRole === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Linked Tenant</label>
        <select name="tenant_id" class="form-select @error('tenant_id') is-invalid @enderror">
            <option value="">No tenant linked</option>
            @foreach($tenants as $tenant)
                <option value="{{ $tenant->id }}" @selected((string) $selectedTenant === (string) $tenant->id)>
                    {{ data_get($tenant->data, 'name') ?? $tenant->id }}
                </option>
            @endforeach
        </select>
        <div class="form-text">Use this only for doctor tenant owner accounts.</div>
        @error('tenant_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            <option value="1" @selected($selectedStatus === '1')>Active</option>
            <option value="0" @selected($selectedStatus === '0')>Inactive</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Password @unless($isEdit)<span class="text-danger">*</span>@endunless</label>
        <input type="password" name="password"
               class="form-control @error('password') is-invalid @enderror"
               @unless($isEdit) required @endunless>
        <div class="form-text">{{ $isEdit ? 'Leave blank to keep the current password.' : 'Minimum 8 characters.' }}</div>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Confirm Password @unless($isEdit)<span class="text-danger">*</span>@endunless</label>
        <input type="password" name="password_confirmation" class="form-control" @unless($isEdit) required @endunless>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('superadmin.users.index') }}" class="btn btn-light border">Cancel</a>
    <button type="submit" class="btn btn-primary">
        <i class="ri-save-line me-1"></i>{{ $isEdit ? 'Update User' : 'Create User' }}
    </button>
</div>
