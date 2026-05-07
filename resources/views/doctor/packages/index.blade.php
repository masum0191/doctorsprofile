@extends('layouts.admin')
@section('title', 'Packages')

@section('content')
@php
    $pricingContext = app(\App\Services\PricingService::class)->contextForRequest(request(), optional(auth()->user())->country);
@endphp

<h4 class="mb-4">Available Packages</h4>

@if($subscription && $isActive)
<div class="alert alert-info">
    <strong>Current Plan:</strong> {{ $subscription->package->name }} |
    Expires: {{ $subscription->ends_at->format('d M Y') }}
</div>
@endif

<div class="row">

@foreach ($packages as $package)

<div class="col-md-4 mb-4">
    <div class="card h-100 shadow-sm">

        <div class="card-body text-center">

            <h4 class="fw-bold">{{ $package->name }}</h4>

            <p class="mt-3">
                <strong>Monthly:</strong> {{ $pricingContext['currency_symbol'] }}{{ number_format($package->price_monthly * $pricingContext['exchange_rate'], 2) }} <br>
                <strong>Yearly:</strong> {{ $pricingContext['currency_symbol'] }}{{ number_format($package->price_yearly * $pricingContext['exchange_rate'], 2) }}
            </p>

            {{-- CURRENT PLAN --}}
            {{-- @if ($isActive && $currentPackageId == $package->id)
                <span class="badge bg-success">Current Plan</span>

            @else --}}

                {{-- FREE PACKAGE USED CHECK --}}
                @if ($package->slug == 'free' && $hasUsedFree)
                    <button class="btn btn-secondary mt-2" disabled>
                        Free Already Used
                    </button>

                @else
                    <form method="POST" action="{{ route('package.upgrade.process') }}">
                        @csrf

                        <input type="hidden" name="package_id" value="{{ $package->id }}">

                        <select name="billing_cycle" class="form-control mb-2" required>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>

                        <button class="btn btn-primary w-100">
                            {{ $isActive ? 'Upgrade' : 'Activate' }}
                        </button>
                    </form>
                {{-- @endif --}}

            @endif

        </div>

    </div>
</div>

@endforeach

</div>

@endsection
