@extends('layouts.supperadmin')
@section('title', 'Payment Gateway Settings')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1 text-gray-800">
            <i class="ri-bank-card-line text-primary me-2"></i>
            Payment Gateway Settings
        </h4>
        <p class="text-muted mb-0 small">
            Configure default registration gateways and country-based overrides.
        </p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Please fix the highlighted fields.</strong>
    </div>
@endif

@php
    $ssl = $payment['sslcommerz'] ?? [];
    $stripe = $payment['stripe'] ?? [];
    $countryGateways = $payment['country_gateways'] ?? [];
    $stripeConfigured = filled($stripe['key'] ?? null) && filled($stripe['secret'] ?? null);
    $sslcommerzConfigured = filled($ssl['store_id'] ?? null) && filled($ssl['store_password'] ?? $ssl['secret'] ?? null);
@endphp

<form method="POST" action="{{ route('superadmin.payment.settings.update') }}">
    @csrf

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-semibold">
                        <i class="ri-secure-payment-line text-primary me-2"></i>
                        SSLCommerz
                    </h6>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="sslcommerz_enabled" value="1"
                            @checked($ssl['enabled'] ?? false)>
                        <label class="form-check-label fw-semibold">Enable SSLCommerz</label>
                    </div>

                    @if (($ssl['enabled'] ?? false) && !$sslcommerzConfigured)
                        <div class="alert alert-warning small">
                            SSLCommerz is enabled, but Store ID or Store Password is missing.
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Store ID</label>
                        <input class="form-control" name="sslcommerz_store_id"
                            value="{{ old('sslcommerz_store_id', $ssl['store_id'] ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Store Password</label>
                        <input type="password" class="form-control" name="sslcommerz_store_password"
                            autocomplete="new-password"
                            placeholder="{{ filled($ssl['store_password'] ?? $ssl['secret'] ?? null) ? 'Saved - leave blank to keep current password' : 'Enter SSLCommerz store password' }}">
                        @if (filled($ssl['store_password'] ?? $ssl['secret'] ?? null))
                            <small class="text-success d-block mt-1">Store password is saved.</small>
                        @endif
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="sslcommerz_test_mode" value="1"
                            @checked($ssl['test_mode'] ?? false)>
                        <label class="form-check-label fw-semibold">Sandbox/Test Mode</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-semibold">
                        <i class="ri-bank-card-line text-primary me-2"></i>
                        Stripe
                    </h6>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="stripe_enabled" value="1"
                            @checked($stripe['enabled'] ?? false)>
                        <label class="form-check-label fw-semibold">Enable Stripe</label>
                    </div>

                    @if (($stripe['enabled'] ?? false) && !$stripeConfigured)
                        <div class="alert alert-warning small">
                            Stripe is enabled, but Publishable Key or Secret Key is missing.
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Publishable Key</label>
                        <input class="form-control @error('stripe_key') is-invalid @enderror" name="stripe_key"
                            autocomplete="off"
                            value="{{ old('stripe_key', $stripe['key'] ?? '') }}">
                        @error('stripe_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Secret Key</label>
                        <input type="password" class="form-control @error('stripe_secret') is-invalid @enderror"
                            name="stripe_secret"
                            autocomplete="new-password"
                            placeholder="{{ filled($stripe['secret'] ?? null) ? 'Saved - leave blank to keep current secret key' : 'Enter Stripe secret key' }}">
                        @error('stripe_secret')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if (filled($stripe['secret'] ?? null))
                            <small class="text-success d-block mt-1">Secret key is saved.</small>
                        @endif
                        <small class="text-muted d-block mt-1">
                            Add your Stripe publishable key and secret key here for international doctor registration payments.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-semibold">
                        <i class="ri-global-line text-primary me-2"></i>
                        Country Based Gateways
                    </h6>
                </div>
                <div class="card-body">
                    <label class="form-label fw-semibold">Country Gateway Map</label>
                    <textarea class="form-control" name="country_gateways" rows="6"
                        placeholder="Bangladesh=sslcommerz&#10;India=stripe&#10;Nepal=stripe">{{ old('country_gateways', collect($countryGateways)->map(fn($gateway, $country) => $country . '=' . $gateway)->implode("\n")) }}</textarea>
                    <small class="text-muted d-block mt-2">
                        One country per line. Default rule: Bangladesh uses SSLCommerz, all other countries use Stripe unless overridden here.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-3 mt-4">
        <a href="{{ route('superadmin.company.settings') }}" class="btn btn-light px-4">Company Settings</a>
        <button type="submit" class="btn btn-primary px-4">
            <i class="ri-save-3-line me-1"></i>
            Save Payment Settings
        </button>
    </div>
</form>
@endsection
