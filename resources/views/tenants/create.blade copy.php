@extends('layouts.sass')

@section('title', 'Doctor Registration')

@section('content')
    <div class="container-fluid py-5 bg-light">
        <div class="container">
            <!-- Header -->
            <header class="mb-5 mt-5">
                <div class="row align-items-center bg-white py-4 px-3 shadow-sm rounded">

                    <div class="col text-center">
                        <h3 class="display-6 fw-bold text-dark mb-2">Doctor Registration</h3>
                        <p class="lead text-muted mb-0">Complete your professional profile in a few simple steps</p>
                    </div>
                </div>
            </header>

            <!-- Multi-step Progress Bar -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="progress" style="height: 10px;">
                                <div id="progressBar" class="progress-bar bg-primary" role="progressbar"
                                    style="width: 16.66%"></div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                @php
                                    $steps = [
                                        ['number' => 1, 'title' => 'Package', 'icon' => 'fas fa-box'],
                                        ['number' => 2, 'title' => 'Domain', 'icon' => 'fas fa-globe'],
                                        ['number' => 3, 'title' => 'Specialty', 'icon' => 'fas fa-stethoscope'],
                                        ['number' => 4, 'title' => 'Credentials', 'icon' => 'fas fa-id-card'],
                                        ['number' => 5, 'title' => 'Account', 'icon' => 'fas fa-user-circle'],
                                        ['number' => 6, 'title' => 'Payment', 'icon' => 'fas fa-credit-card'],
                                    ];
                                @endphp

                                @foreach ($steps as $step)
                                    <div class="text-center">
                                        <div class="step-circle mx-auto mb-2 rounded-circle border d-flex align-items-center justify-content-center
                                    {{ $step['number'] == 1 ? 'border-primary bg-primary text-white' : 'border-secondary text-secondary' }}"
                                            style="width: 50px; height: 50px;">
                                            <i class="{{ $step['icon'] }}"></i>
                                        </div>
                                        <p
                                            class="mb-0 fw-medium {{ $step['number'] == 1 ? 'text-primary' : 'text-secondary' }}">
                                            {{ $step['title'] }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <h5 class="alert-heading">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Please correct the following errors:
                    </h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Multi-step Form -->
            <form id="doctorRegistrationForm" method="post" action="{{ route('doctor.store') }}"
                enctype="multipart/form-data">

                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                @csrf

                <!-- Hidden Fields -->
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <input type="hidden" name="city" id="city">
                <input type="hidden" id="selectedBillingCycle" name="selected_billing_cycle" value="monthly">
                <input type="hidden" id="selectedCoupon" name="coupon_id" value="">
                <input type="hidden" id="finalPackagePrice" name="packageπ_price" value="0">
                <input type="hidden" id="finalDomainPrice" name="domain_price" value="0">
                <input type="hidden" id="finalDiscount" name="discount_amount" value="0">
                <input type="hidden" id="finalTotal" name="total_amount" value="0">
                <input type="hidden" id="selectedSubdomain" name="subdomain" value="">

                <!-- Step 1: Package Selection -->
                <div id="step1" class="form-step">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-box text-primary me-2"></i>
                                        Step 1: Select Package
                                    </h5>
                                    <p class="text-muted mb-0 mt-1">Choose the plan that fits your practice needs</p>
                                </div>
                                <span class="badge bg-primary rounded-pill">Required</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                @foreach ($packages as $index => $package)
                                    <div class="col-md-4">
                                        <label class="card h-100 border package-card" style="cursor: pointer;">
                                            <div class="card-body d-flex flex-column">
                                                <div class="form-check mb-3">
                                                    <input type="radio" name="package_id" value="{{ $package->id }}"
                                                        required class="form-check-input package-radio"
                                                        {{ old('package_id') == $package->id ? 'checked' : ($loop->first ? 'checked' : '') }}
                                                        data-price-monthly="{{ $package->price_monthly }}"
                                                        data-price-yearly="{{ $package->price_yearly }}">
                                                </div>

                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h5 class="card-title fw-bold mb-0">{{ $package->name }}</h5>
                                                    </div>
                                                    <p class="card-text text-muted">{{ $package->description }}</p>
                                                </div>

                                                <div class="mb-4 flex-grow-1">
                                                    @php
                                                        $features = explode(',', $package->features ?? '');
                                                    @endphp
                                                    @foreach (array_slice($features, 0, 4) as $feature)
                                                        <p class="mb-2">
                                                            <i class="fas fa-check text-success me-2"></i>
                                                            {{ trim($feature) }}
                                                        </p>
                                                    @endforeach
                                                </div>

                                                <div class="mt-auto">
                                                    <div class="mb-3">
                                                        <select name="billing_cycle_{{ $package->id }}"
                                                            class="form-select billing-cycle">
                                                            <option value="monthly">Monthly:
                                                                ${{ number_format($package->price_monthly, 2) }}/mo
                                                            </option>
                                                            <option value="yearly">Yearly:
                                                                ${{ number_format($package->price_yearly, 2) }}/yr</option>
                                                            <option value="free">Free Trial (14 days)</option>
                                                        </select>
                                                    </div>

                                                    <div class="text-center">
                                                        <h4 class="fw-bold mb-0 package-price">
                                                            ${{ number_format($package->price_monthly, 2) }}</h4>
                                                        <p class="text-muted mb-0 package-period">/month</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            @error('package_id')
                                <div class="alert alert-danger mt-3">{{ $message }}</div>
                            @enderror

                            <!-- Step Navigation -->
                            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                                <button type="button" onclick="nextStep(2)" class="btn btn-primary btn-lg px-5">
                                    Next Step <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Domain Selection -->
                <div id="step2" class="form-step d-none">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-globe text-primary me-2"></i>
                                        Step 2: Choose Your Domain
                                    </h5>
                                    <p class="text-muted mb-0 mt-1">Select your professional web address</p>
                                </div>
                                <span class="badge bg-primary rounded-pill">Required</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Domain Type Selection -->
                            <div class="mb-4">
                                <label class="form-label fw-medium mb-3">
                                    <i class="fas fa-sitemap text-primary me-2"></i>
                                    Choose Domain Type
                                </label>
                                <div class="row g-4">
                                    <!-- Subdomain Option (Default) -->
                                    <div class="col-md-4">
                                        <label class="card h-100 border domain-type-option" style="cursor: pointer;">
                                            <div class="position-relative">
                                                <div class="form-check position-absolute top-3 end-3">
                                                    <input type="radio" name="domain_type" value="subdomain" required
                                                        class="form-check-input domain-type-radio"
                                                        id="domain_type_subdomain" checked>
                                                </div>

                                                <span
                                                    class="position-absolute top-0 start-50 translate-middle badge bg-success">
                                                    FREE
                                                </span>
                                            </div>

                                            <div class="card-body text-center">
                                                <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                                                    style="width: 60px; height: 60px;">
                                                    <i class="fas fa-link text-success fa-lg"></i>
                                                </div>

                                                <h6 class="fw-bold">Subdomain</h6>
                                                <p class="text-muted small mb-3">Use our free subdomain service</p>

                                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                                    Free Forever
                                                </span>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- New Domain Option -->
                                    <div class="col-md-4">
                                        <label class="card h-100 border domain-type-option" style="cursor: pointer;">
                                            <div class="card-body text-center">
                                                <div class="form-check position-absolute top-3 end-3">
                                                    <input type="radio" name="domain_type" value="new" required
                                                        class="form-check-input domain-type-radio" id="domain_type_new">
                                                </div>

                                                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                                                    style="width: 60px; height: 60px;">
                                                    <i class="fas fa-plus text-primary fa-lg"></i>
                                                </div>

                                                <h6 class="fw-bold">New Domain</h6>
                                                <p class="text-muted small mb-3">Register a brand new domain</p>

                                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                    $14.99/year
                                                </span>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Existing Domain Option -->
                                    <div class="col-md-4">
                                        <label class="card h-100 border domain-type-option" style="cursor: pointer;">
                                            <div class="card-body text-center">
                                                <div class="form-check position-absolute top-3 end-3">
                                                    <input type="radio" name="domain_type" value="existing" required
                                                        class="form-check-input domain-type-radio"
                                                        id="domain_type_existing">
                                                </div>

                                                <div class="rounded-circle bg-purple bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                                                    style="width: 60px; height: 60px;">
                                                    <i class="fas fa-globe text-purple fa-lg"></i>
                                                </div>

                                                <h6 class="fw-bold">Existing Domain</h6>
                                                <p class="text-muted small mb-3">Connect your own domain</p>

                                                <span class="badge bg-purple bg-opacity-10 text-purple px-3 py-2">
                                                    No Registration Fee
                                                </span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Subdomain Input Section (Default Visible) -->
                            <div id="subdomainSection" class="mb-4">
                                <div class="mb-3">
                                    <label for="subdomain_name" class="form-label fw-medium">
                                        <i class="fas fa-link text-success me-2"></i>
                                        Choose Your Subdomain <span class="text-danger">*</span>
                                    </label>
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input type="text" id="subdomain_name" name="subdomain_name"
                                                    class="form-control" placeholder="yourname"
                                                    value="{{ old('subdomain_name', strtolower(str_replace(' ', '', old('name', '')))) }}">
                                                <span
                                                    class="input-group-text bg-light">.{{ config('app.domain', 'doctorsprofile.xyz') }}</span>
                                                <button type="button" onclick="checkSubdomainAvailability()"
                                                    class="btn btn-success" id="checkSubdomainBtn">
                                                    Check Availability
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" onclick="suggestSmartSubdomain()"
                                                class="btn btn-outline-success w-100">
                                                <i class="fas fa-lightbulb me-2"></i>
                                                Smart Suggest
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-text">
                                        Your website will be available at:
                                        <span id="subdomainPreview"
                                            class="fw-medium text-success">yourname.{{ config('app.domain', 'doctorsprofile.xyz') }}</span>
                                    </div>
                                    <div id="subdomainStatus" class="mt-2"></div>
                                </div>

                                <div class="alert alert-success">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-success bg-opacity-25 d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px;">
                                                <i class="fas fa-gift text-success fa-lg"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fw-bold mb-1">Free Subdomain Included!</h6>
                                            <p class="mb-0">No additional cost. Your subdomain is included with your
                                                package.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- New Domain Input Section (Hidden by Default) -->
                            <div id="newDomainSection" class="mb-4 d-none">
                                <div class="mb-3">
                                    <label for="new_domain_name" class="form-label fw-medium">
                                        <i class="fas fa-globe-americas text-primary me-2"></i>
                                        Choose Your Domain <span class="text-danger">*</span>
                                    </label>
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">www.</span>
                                                <input type="text" id="new_domain_name" name="new_domain_name"
                                                    class="form-control" placeholder="yourclinic"
                                                    value="{{ old('new_domain_name') }}">
                                                <button type="button" onclick="checkDomainAvailability()"
                                                    class="btn btn-primary" id="checkDomainBtn">
                                                    Check Availability
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <select id="new_domain_extension" name="new_domain_extension"
                                                class="form-select">
                                                <option value=".com">.com</option>
                                                <option value=".net">.net</option>
                                                <option value=".org">.org</option>
                                                <option value=".health">.health</option>
                                                <option value=".clinic">.clinic</option>
                                                <option value=".doctor">.doctor</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="newDomainStatus" class="mt-2"></div>
                                </div>

                                <div class="alert alert-info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Domain Registration Fee</h6>
                                            <p class="mb-0">One-time yearly registration</p>
                                        </div>
                                        <div class="text-end">
                                            <h4 class="fw-bold text-primary mb-0">$14.99</h4>
                                            <p class="text-muted mb-0">per year</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Existing Domain Input Section (Hidden by Default) -->
                            <div id="existingDomainSection" class="mb-4 d-none">
                                <div class="mb-3">
                                    <label for="existing_domain" class="form-label fw-medium">
                                        <i class="fas fa-external-link-alt text-purple me-2"></i>
                                        Your Existing Domain <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="existing_domain" name="existing_domain"
                                        class="form-control" placeholder="yourdomain.com"
                                        value="{{ old('existing_domain') }}">
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        After registration, you'll receive instructions to update your DNS settings.
                                    </div>
                                </div>

                                <div class="alert alert-purple">
                                    <h6 class="fw-bold mb-2">Domain Connection Instructions:</h6>
                                    <ol class="mb-0">
                                        <li>Complete your registration</li>
                                        <li>Check your email for DNS configuration details</li>
                                        <li>Update your domain's DNS settings with your registrar</li>
                                        <li>Allow 24-48 hours for propagation</li>
                                    </ol>
                                </div>
                            </div>

                            <!-- Step Navigation -->
                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                <button type="button" onclick="prevStep(1)"
                                    class="btn btn-outline-secondary btn-lg px-5">
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                <button type="button" onclick="nextStep(3)" class="btn btn-primary btn-lg px-5"
                                    id="step2NextBtn">
                                    Next Step <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Specialty & Location -->
                <div id="step3" class="form-step d-none">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-stethoscope text-primary me-2"></i>
                                        Step 3: Specialty & Location
                                    </h5>
                                    <p class="text-muted mb-0 mt-1">Tell us about your medical specialty and practice
                                        location</p>
                                </div>
                                <span class="badge bg-primary rounded-pill">Required</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <!-- Specialty -->
                                <div class="col-md-6">
                                    <label for="specialty" class="form-label fw-medium">
                                        <i class="fas fa-stethoscope text-primary me-2"></i>
                                        Medical Specialty
                                    </label>
                                    <select id="specialty" name="specialty" required class="form-select">
                                        <option value="">Select your specialty</option>
                                        <option value="Cardiology"
                                            {{ old('specialty') == 'Cardiology' ? 'selected' : '' }}>Cardiology</option>
                                        <option value="Neurology" {{ old('specialty') == 'Neurology' ? 'selected' : '' }}>
                                            Neurology</option>
                                        <option value="Pediatrics"
                                            {{ old('specialty') == 'Pediatrics' ? 'selected' : '' }}>Pediatrics</option>
                                        <option value="General" {{ old('specialty') == 'General' ? 'selected' : '' }}>
                                            General Practitioner</option>
                                        <option value="Dermatology"
                                            {{ old('specialty') == 'Dermatology' ? 'selected' : '' }}>Dermatology</option>
                                        <option value="Orthopedics"
                                            {{ old('specialty') == 'Orthopedics' ? 'selected' : '' }}>Orthopedics</option>
                                        <option value="Gynecology"
                                            {{ old('specialty') == 'Gynecology' ? 'selected' : '' }}>Gynecology</option>
                                        <option value="Psychiatry"
                                            {{ old('specialty') == 'Psychiatry' ? 'selected' : '' }}>Psychiatry</option>
                                        <option value="Other" {{ old('specialty') == 'Other' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                    @error('specialty')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Country -->
                                <div class="col-md-6">
                                    <label for="country" class="form-label fw-medium">
                                        <i class="fas fa-globe text-primary me-2"></i>
                                        Practice Country
                                    </label>
                                    <select id="country" name="country" required class="form-select">
                                        <option value="">Select country</option>
                                        <option value="Bangladesh" {{ old('country') == 'Bangladesh' ? 'selected' : '' }}>
                                            Bangladesh</option>
                                        <option value="USA" {{ old('country') == 'USA' ? 'selected' : '' }}>United
                                            States</option>
                                        <option value="UK" {{ old('country') == 'UK' ? 'selected' : '' }}>United
                                            Kingdom</option>
                                        <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada
                                        </option>
                                        <option value="Australia" {{ old('country') == 'Australia' ? 'selected' : '' }}>
                                            Australia</option>
                                        <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India
                                        </option>
                                        <option value="Pakistan" {{ old('country') == 'Pakistan' ? 'selected' : '' }}>
                                            Pakistan</option>
                                        <option value="Sri Lanka" {{ old('country') == 'Sri Lanka' ? 'selected' : '' }}>
                                            Sri Lanka</option>
                                        <option value="Nepal" {{ old('country') == 'Nepal' ? 'selected' : '' }}>Nepal
                                        </option>
                                        <option value="Bhutan" {{ old('country') == 'Bhutan' ? 'selected' : '' }}>Bhutan
                                        </option>
                                        <option value="Maldives" {{ old('country') == 'Maldives' ? 'selected' : '' }}>
                                            Maldives</option>
                                    </select>
                                    @error('country')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- BMDC Registration -->
                                <div id="bmdcGroup" class="col-12 d-none">
                                    <label for="reg_no" class="form-label fw-medium">
                                        <i class="fas fa-id-badge text-primary me-2"></i>
                                        BMDC Registration Number
                                    </label>
                                    <div class="input-group">
                                        <input type="text" id="reg_no" name="reg_no" class="form-control"
                                            placeholder="BMDC-XXXXX-XX" value="{{ old('reg_no') }}">
                                        <span class="input-group-text bg-warning bg-opacity-25">Required</span>
                                    </div>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Required for doctors practicing in Bangladesh
                                    </div>
                                    @error('reg_no')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Step Navigation -->
                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                <button type="button" onclick="prevStep(2)"
                                    class="btn btn-outline-secondary btn-lg px-5">
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                <button type="button" onclick="nextStep(4)" class="btn btn-primary btn-lg px-5">
                                    Next Step <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Identity & Credentials -->
                <div id="step4" class="form-step d-none">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-id-card text-primary me-2"></i>
                                        Step 4: Identity & Credentials
                                    </h5>
                                    <p class="text-muted mb-0 mt-1">Provide your professional information</p>
                                </div>
                                <span class="badge bg-primary rounded-pill">Required</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <!-- Full Name -->
                                <div class="col-12">
                                    <label for="name" class="form-label fw-medium">
                                        <i class="fas fa-user-md text-primary me-2"></i>
                                        Doctor's Full Name
                                    </label>
                                    <input type="text" id="name" name="name" required
                                        value="{{ old('name') }}" class="form-control" placeholder="Dr. Jane Smith">
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Qualifications -->
                                <div class="col-12">
                                    <label for="qualification" class="form-label fw-medium">
                                        <i class="fas fa-graduation-cap text-primary me-2"></i>
                                        Professional Qualifications
                                    </label>
                                    <input type="text" id="qualification" name="qualification" required
                                        value="{{ old('qualification') }}" class="form-control"
                                        placeholder="MBBS, FCPS (Cardiology), MD">
                                    <div class="form-text">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        List your degrees and specializations separated by commas
                                    </div>
                                    @error('qualification')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Step Navigation -->
                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                <button type="button" onclick="prevStep(3)"
                                    class="btn btn-outline-secondary btn-lg px-5">
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                <button type="button" onclick="nextStep(5)" class="btn btn-primary btn-lg px-5">
                                    Next Step <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Account Details -->
                <div id="step5" class="form-step d-none">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-user-circle text-primary me-2"></i>
                                        Step 5: Account Details
                                    </h5>
                                    <p class="text-muted mb-0 mt-1">Set up your login credentials</p>
                                </div>
                                <span class="badge bg-primary rounded-pill">Required</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <!-- Profile Photo -->
                                <div class="col-12">
                                    <label class="form-label fw-medium">
                                        <i class="fas fa-camera text-primary me-2"></i>
                                        Professional Photo
                                    </label>
                                    <div class="d-flex align-items-center gap-4">
                                        <div class="border rounded p-3 bg-light text-center"
                                            style="width: 150px; height: 150px;">
                                            <div id="photoPreview">
                                                <i class="fas fa-user text-muted fa-3x mb-2"></i>
                                                <p class="small text-muted mb-0">Preview</p>
                                            </div>
                                            <img id="photoPreviewImage" class="img-fluid rounded d-none" src=""
                                                alt="Preview">
                                        </div>
                                        <div class="flex-grow-1">
                                            <input type="file" id="photo" name="photo" accept="image/*"
                                                required class="form-control d-none" onchange="previewImage(this)">
                                            <label for="photo" class="btn btn-outline-primary">
                                                <i class="fas fa-upload me-2"></i> Upload Photo
                                            </label>
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Recommended: Square image, max 2MB, professional headshot
                                            </div>
                                        </div>
                                    </div>
                                    @error('photo')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-medium">
                                        <i class="fas fa-envelope text-primary me-2"></i>
                                        Email Address
                                    </label>
                                    <input type="email" id="email" name="email" required
                                        value="{{ old('email') }}" class="form-control"
                                        placeholder="doctor@example.com">
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-medium">
                                        <i class="fas fa-phone text-primary me-2"></i>
                                        Contact Number
                                    </label>
                                    <input type="tel" id="phone" name="phone" required
                                        value="{{ old('phone') }}" class="form-control" placeholder="+8801XXXXXXXXX">
                                    @error('phone')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-medium">
                                        <i class="fas fa-lock text-primary me-2"></i>
                                        Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" id="password" name="password" required
                                            class="form-control">
                                        <button type="button" onclick="togglePassword('password')"
                                            class="btn btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-medium">
                                        <i class="fas fa-lock text-primary me-2"></i>
                                        Confirm Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            required class="form-control">
                                        <button type="button" onclick="togglePassword('password_confirmation')"
                                            class="btn btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Step Navigation -->
                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                <button type="button" onclick="prevStep(4)"
                                    class="btn btn-outline-secondary btn-lg px-5">
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                <button type="button" onclick="nextStep(6)" class="btn btn-primary btn-lg px-5">
                                    Next Step <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

               <!-- Updated Step 6: Review & Payment -->
<div id="step6" class="form-step d-none">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-credit-card text-primary me-2"></i>
                        Step 6: Review & Payment
                    </h5>
                    <p class="text-muted mb-0 mt-1">Review your information and complete payment</p>
                </div>
                <span class="badge bg-success rounded-pill">Final Step</span>
            </div>
        </div>
        <div class="card-body">
            <!-- Payment Option -->
            <div class="mb-4">
                <label class="form-label fw-medium">
                    <i class="fas fa-money-check-alt text-primary me-2"></i>
                    Payment Option <span class="text-danger">*</span>
                </label>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="card h-100 border payment-option-card" style="cursor: pointer;">
                            <div class="card-body text-center">
                                <div class="form-check position-absolute top-3 end-3">
                                    <input type="radio" name="payment_option" value="online" required
                                           class="form-check-input payment-option-radio" id="payment_option_online" checked>
                                </div>
                                <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-globe text-success fa-lg"></i>
                                </div>
                                <h6 class="fw-bold">Online Payment</h6>
                                <p class="text-muted small">Pay now to activate immediately</p>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                    Instant Activation
                                </span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="card h-100 border payment-option-card" style="cursor: pointer;">
                            <div class="card-body text-center">
                                <div class="form-check position-absolute top-3 end-3">
                                    <input type="radio" name="payment_option" value="offline" required
                                           class="form-check-input payment-option-radio" id="payment_option_offline">
                                </div>
                                <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-building text-warning fa-lg"></i>
                                </div>
                                <h6 class="fw-bold">Offline Payment</h6>
                                <p class="text-muted small">Pay later via bank transfer</p>
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">
                                    Manual Approval
                                </span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Payment Method (Only shown for online payment) -->
            <div id="onlinePaymentMethods" class="mb-4">
                <label class="form-label fw-medium">
                    <i class="fas fa-credit-card text-primary me-2"></i>
                    Payment Method <span class="text-danger">*</span>
                </label>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="card h-100 border payment-method-card" style="cursor: pointer;">
                            <div class="card-body text-center">
                                <div class="form-check position-absolute top-3 end-3">
                                    <input type="radio" name="payment_method" value="paypal"
                                           class="form-check-input payment-method-radio" id="payment_method_paypal" checked>
                                </div>
                                <div class="mb-3">
                                    <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg"
                                         alt="PayPal" style="height: 30px;">
                                </div>
                                <h6 class="fw-bold">PayPal</h6>
                                <p class="text-muted small">Pay with PayPal account</p>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-3">
                        <label class="card h-100 border payment-method-card" style="cursor: pointer;">
                            <div class="card-body text-center">
                                <div class="form-check position-absolute top-3 end-3">
                                    <input type="radio" name="payment_method" value="sslcommerz"
                                           class="form-check-input payment-method-radio" id="payment_method_sslcommerz">
                                </div>
                                <div class="mb-3">
                                    <span class="badge bg-info text-white p-2">SSLCOMMERZ</span>
                                </div>
                                <h6 class="fw-bold">SSL e-Commerce</h6>
                                <p class="text-muted small">Local payment gateway</p>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-3">
                        <label class="card h-100 border payment-method-card" style="cursor: pointer;">
                            <div class="card-body text-center">
                                <div class="form-check position-absolute top-3 end-3">
                                    <input type="radio" name="payment_method" value="credit_card"
                                           class="form-check-input payment-method-radio" id="payment_method_credit_card">
                                </div>
                                <div class="mb-3">
                                    <i class="fas fa-credit-card fa-2x text-primary"></i>
                                </div>
                                <h6 class="fw-bold">Credit Card</h6>
                                <p class="text-muted small">Direct card payment</p>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-3">
                        <label class="card h-100 border payment-method-card" style="cursor: pointer;">
                            <div class="card-body text-center">
                                <div class="form-check position-absolute top-3 end-3">
                                    <input type="radio" name="payment_method" value="bank_transfer"
                                           class="form-check-input payment-method-radio" id="payment_method_bank_transfer">
                                </div>
                                <div class="mb-3">
                                    <i class="fas fa-university fa-2x text-purple"></i>
                                </div>
                                <h6 class="fw-bold">Bank Transfer</h6>
                                <p class="text-muted small">Manual bank transfer</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Credit Card Form (Only shown for credit card selection) -->
                <div id="creditCardForm" class="mt-4 d-none">
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-credit-card me-2"></i>Card Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="card_number" class="form-label">Card Number</label>
                                    <input type="text" id="card_number" name="card_number"
                                           class="form-control" placeholder="1234 5678 9012 3456">
                                </div>
                                <div class="col-md-3">
                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                    <input type="text" id="expiry_date" name="expiry_date"
                                           class="form-control" placeholder="MM/YY">
                                </div>
                                <div class="col-md-3">
                                    <label for="cvv" class="form-label">CVV</label>
                                    <input type="password" id="cvv" name="cvv"
                                           class="form-control" placeholder="123">
                                </div>
                                <div class="col-md-6">
                                    <label for="card_holder" class="form-label">Card Holder Name</label>
                                    <input type="text" id="card_holder" name="card_holder"
                                           class="form-control" placeholder="John Doe">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coupon Section -->
            <div class="mb-4">
                <label class="form-label fw-medium">
                    <i class="fas fa-tag text-primary me-2"></i>
                    Apply Coupon (Optional)
                </label>
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" id="coupon_code" name="coupon_code"
                                   class="form-control" placeholder="Enter coupon code">
                            <button type="button" onclick="applyCoupon()" class="btn btn-primary" id="applyCouponBtn">
                                Apply Coupon
                            </button>
                        </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Apply a valid coupon code to get discount on your purchase
                        </div>
                        <div id="couponStatus" class="mt-2"></div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" onclick="showAvailableCoupons()" class="btn btn-outline-primary w-100">
                            <i class="fas fa-gift me-2"></i>
                            View Available Coupons
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Content -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-file-alt me-2"></i>Registration Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <p class="mb-1"><strong>Package:</strong> <span id="summaryPackage">Basic Plan</span></p>
                                <p class="mb-1"><strong>Billing Cycle:</strong> <span id="summaryBilling">Monthly</span></p>
                                <p class="mb-1"><strong>Domain Type:</strong> <span id="summaryDomainType">Subdomain</span></p>
                                <p class="mb-1"><strong>Website URL:</strong> <span id="summaryDomainUrl">yourname.doctorsprofile.xyz</span></p>
                                <p class="mb-1"><strong>Specialty:</strong> <span id="summarySpecialty">Cardiology</span></p>
                                <p class="mb-1"><strong>Country:</strong> <span id="summaryCountry">USA</span></p>
                                <p class="mb-1"><strong>Payment Option:</strong> <span id="summaryPaymentOption">Online</span></p>
                                <p class="mb-1"><strong>Payment Method:</strong> <span id="summaryPaymentMethod">PayPal</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-money-bill me-2"></i>Payment Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Package Fee:</span>
                                    <span id="summaryPackageFee">$29.99</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Domain Fee:</span>
                                    <span id="summaryDomainFee">$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>Discount:</span>
                                    <span id="summaryDiscount">-$0.00</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total Amount:</span>
                                    <span id="summaryTotal">$29.99</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms & Conditions -->
            <div class="form-check mb-4">
                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                <label class="form-check-label" for="terms">
                    I agree to the <a href="#" data-bs-toggle="modal"
                    data-bs-target="#termsModal">Terms & Conditions</a> and <a href="#"
                    data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a>
                </label>
                @error('terms')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Step Navigation -->
            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                <button type="button" onclick="prevStep(5)"
                    class="btn btn-outline-secondary btn-lg px-5">
                    <i class="fas fa-arrow-left me-2"></i> Previous
                </button>
                <button type="submit" id="completeRegistrationBtn" class="btn btn-success btn-lg px-5">
                    <i class="fas fa-lock me-2"></i>
                    Complete Registration
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Available Coupons Modal -->
<div class="modal fade" id="couponsModal" tabindex="-1" aria-labelledby="couponsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="couponsModalLabel">
                    <i class="fas fa-gift text-primary me-2"></i>
                    Available Coupons
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="availableCouponsList" class="row g-3">
                    <!-- Coupons will be loaded here -->
                </div>
                <div id="couponsLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading available coupons...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


                <!-- Back to Home -->
                <div class="text-center mt-5">
                    <a href="{{ url('/') }}" class="text-decoration-none text-muted">
                        <i class="fas fa-arrow-left me-2"></i>
                        Return to Home
                    </a>
                </div>
        </div>
    </div>
    </form>
    <!-- Terms & Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms & Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Add your terms and conditions here -->
                    <p>By registering, you agree to our terms and conditions...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Add your privacy policy here -->
                    <p>Our privacy policy explains how we handle your data...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ========== MULTI-STEP FORM ==========
        let currentStep = 1;
        const totalSteps = 6;
        let subdomainAvailable = false;
        let domainAvailable = false;

        // Initialize form on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Form initialized - Current step:', currentStep);
            showStep(1);
            updateProgress();

            // Initialize package pricing
            document.querySelectorAll('.billing-cycle').forEach(select => {
                updateBillingCycle(select);
            });

            // Initialize BMDC field if needed
            const countrySelect = document.getElementById('country');
            if (countrySelect && countrySelect.value === 'Bangladesh') {
                document.getElementById('bmdcGroup').classList.remove('d-none');
            }

            // Setup domain type toggle
            setupDomainTypeToggle();

            // Auto-check subdomain on input
            document.getElementById('subdomain_name')?.addEventListener('input', function() {
                updateSubdomainPreview();
            });

            // Initialize domain preview
            updateSubdomainPreview();

            // Update summary on each step
            updateSummary();
        });

        // ========== STEP NAVIGATION ==========
        function updateProgress() {
            const progressBar = document.getElementById('progressBar');
            if (progressBar) {
                const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
                progressBar.style.width = `${progressPercentage}%`;
            }

            // Update step circles
            document.querySelectorAll('.step-circle').forEach((circle, index) => {
                const stepNumber = index + 1;
                if (stepNumber <= currentStep) {
                    circle.classList.remove('border-secondary', 'text-secondary');
                    circle.classList.add('border-primary', 'bg-primary', 'text-white');
                } else {
                    circle.classList.remove('border-primary', 'bg-primary', 'text-white');
                    circle.classList.add('border-secondary', 'text-secondary');
                }
            });
        }

        function nextStep(step) {
            console.log(`Moving to step ${step} from step ${currentStep}`);

            if (validateCurrentStep()) {
                hideAllSteps();
                currentStep = step;
                showStep(currentStep);
                updateProgress();
                updateSummary(); // Update summary when moving to next step
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                return true;
            }
            return false;
        }

        function prevStep(step) {
            hideAllSteps();
            currentStep = step;
            showStep(currentStep);
            updateProgress();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function hideAllSteps() {
            document.querySelectorAll('.form-step').forEach(step => {
                step.classList.add('d-none');
            });
        }

        function showStep(stepNumber) {
            const stepElement = document.getElementById(`step${stepNumber}`);
            if (stepElement) {
                stepElement.classList.remove('d-none');
                console.log(`Showing step ${stepNumber}`);
            }
        }

        // ========== FORM VALIDATION ==========
        function validateCurrentStep() {
            console.log(`Validating current step: ${currentStep}`);

            switch (currentStep) {
                case 1:
                    return validateStep1();
                case 2:
                    return validateStep2();
                case 3:
                    return validateStep3();
                case 4:
                    return validateStep4();
                case 5:
                    return validateStep5();
                case 6:
                    return validateStep6();
                default:
                    return true;
            }
        }

        function validateStep1() {
            console.log('Validating Step 1');

            const selectedPackage = document.querySelector('.package-radio:checked');
            if (!selectedPackage) {
                alert('⚠️ Please select a package to continue.');
                return false;
            }

            console.log('Step 1 validation passed');
            return true;
        }

        function validateStep2() {
            console.log('Validating Step 2');

            const domainType = document.querySelector('.domain-type-radio:checked');
            if (!domainType) {
                alert('⚠️ Please select a domain type.');
                return false;
            }

            const domainTypeValue = domainType.value;
            console.log(`Domain type selected: ${domainTypeValue}`);

            if (domainTypeValue === 'subdomain') {
                const subdomainInput = document.getElementById('subdomain_name');
                const subdomain = subdomainInput?.value.trim();

                if (!subdomain) {
                    alert('⚠️ Please enter a subdomain name.');
                    subdomainInput?.focus();
                    return false;
                }

                // Check subdomain format
                const subdomainRegex = /^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/i;
                if (!subdomainRegex.test(subdomain.toLowerCase())) {
                    alert('⚠️ Subdomain can only contain letters, numbers, and hyphens.');
                    subdomainInput?.focus();
                    return false;
                }

                // Check if availability was checked
                if (!subdomainAvailable) {
                    alert('⚠️ Please check subdomain availability before proceeding.');
                    document.getElementById('checkSubdomainBtn')?.click();
                    return false;
                }

                // Set the selected subdomain
                document.getElementById('selectedSubdomain').value = subdomain;
                console.log('Step 2 (subdomain) validation passed');
                return true;

            } else if (domainTypeValue === 'new') {
                const domainInput = document.getElementById('new_domain_name');
                const domain = domainInput?.value.trim();

                if (!domain) {
                    alert('⚠️ Please enter a domain name.');
                    domainInput?.focus();
                    return false;
                }

                const domainRegex = /^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/i;
                if (!domainRegex.test(domain.toLowerCase())) {
                    alert('⚠️ Domain name can only contain letters, numbers, and hyphens.');
                    domainInput?.focus();
                    return false;
                }

                if (!domainAvailable) {
                    alert('⚠️ Please check domain availability before proceeding.');
                    document.getElementById('checkDomainBtn')?.click();
                    return false;
                }

                console.log('Step 2 (new domain) validation passed');
                return true;

            } else if (domainTypeValue === 'existing') {
                const existingDomainInput = document.getElementById('existing_domain');
                const domain = existingDomainInput?.value.trim();

                if (!domain) {
                    alert('⚠️ Please enter your existing domain.');
                    existingDomainInput?.focus();
                    return false;
                }

                const domainRegex = /^(?!:\/\/)([a-zA-Z0-9-_]+\.)*[a-zA-Z0-9][a-zA-Z0-9-_]+\.[a-zA-Z]{2,11}?$/;
                if (!domainRegex.test(domain)) {
                    alert('⚠️ Please enter a valid domain name (e.g., yourdomain.com).');
                    existingDomainInput?.focus();
                    return false;
                }

                console.log('Step 2 (existing domain) validation passed');
                return true;
            }

            alert('⚠️ Please select a valid domain type.');
            return false;
        }

        function validateStep3() {
            const specialty = document.getElementById('specialty');
            const country = document.getElementById('country');

            if (!specialty?.value) {
                alert('⚠️ Please select your medical specialty.');
                specialty?.focus();
                return false;
            }

            if (!country?.value) {
                alert('⚠️ Please select your practice country.');
                country?.focus();
                return false;
            }

            if (country.value === 'Bangladesh') {
                const regNo = document.getElementById('reg_no');
                if (!regNo?.value.trim()) {
                    alert('⚠️ BMDC Registration Number is required for doctors in Bangladesh.');
                    regNo?.focus();
                    return false;
                }
            }

            return true;
        }

        function validateStep4() {
            const name = document.getElementById('name');
            const qualification = document.getElementById('qualification');

            if (!name?.value.trim()) {
                alert('⚠️ Please enter your full name.');
                name?.focus();
                return false;
            }

            if (!qualification?.value.trim()) {
                alert('⚠️ Please enter your qualifications.');
                qualification?.focus();
                return false;
            }

            return true;
        }

        function validateStep5() {
            const email = document.getElementById('email');
            const phone = document.getElementById('phone');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');
            const photo = document.getElementById('photo');

            // Email validation
            if (!email?.value.trim()) {
                alert('⚠️ Email address is required.');
                email?.focus();
                return false;
            }

            if (!isValidEmail(email.value)) {
                alert('⚠️ Please enter a valid email address.');
                email?.focus();
                return false;
            }

            // Phone validation
            if (!phone?.value.trim()) {
                alert('⚠️ Contact number is required.');
                phone?.focus();
                return false;
            }

            // Password validation
            if (!password?.value) {
                alert('⚠️ Password is required.');
                password?.focus();
                return false;
            }

            if (password.value.length < 8) {
                alert('⚠️ Password must be at least 8 characters.');
                password?.focus();
                return false;
            }

            if (password.value !== confirmPassword?.value) {
                alert('⚠️ Passwords do not match.');
                confirmPassword?.focus();
                return false;
            }

            // Photo validation
            if (!photo?.files.length) {
                alert('⚠️ Please upload your professional photo.');
                return false;
            }

            return true;
        }

        function validateStep6() {
            const terms = document.getElementById('terms');

            if (!terms?.checked) {
                alert('⚠️ You must agree to the Terms & Conditions and Privacy Policy.');
                terms?.focus();
                return false;
            }

            return true;
        }

        function isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // ========== DOMAIN TYPE TOGGLE ==========
        function setupDomainTypeToggle() {
            console.log('Setting up domain type toggle');

            const domainTypeRadios = document.querySelectorAll('.domain-type-radio');

            domainTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    console.log(`Domain type changed to: ${this.value}`);

                    // Show/hide sections based on selection
                    document.getElementById('subdomainSection')?.classList.add('d-none');
                    document.getElementById('newDomainSection')?.classList.add('d-none');
                    document.getElementById('existingDomainSection')?.classList.add('d-none');

                    if (this.value === 'subdomain') {
                        document.getElementById('subdomainSection')?.classList.remove('d-none');
                    } else if (this.value === 'new') {
                        document.getElementById('newDomainSection')?.classList.remove('d-none');
                    } else if (this.value === 'existing') {
                        document.getElementById('existingDomainSection')?.classList.remove('d-none');
                    }

                    // Update summary
                    updateSummary();
                });
            });

            // Initialize with default (subdomain)
            const defaultRadio = document.querySelector('.domain-type-radio[value="subdomain"]');
            if (defaultRadio) {
                defaultRadio.checked = true;
                document.getElementById('subdomainSection')?.classList.remove('d-none');
            }
        }

        // ========== SUBDOMAIN FUNCTIONS ==========
        function suggestSmartSubdomain() {
            const nameInput = document.getElementById('name');
            const specialtyInput = document.getElementById('specialty');
            const subdomainInput = document.getElementById('subdomain_name');

            if (!subdomainInput) return;

            let baseName = '';

            if (nameInput?.value.trim()) {
                const nameParts = nameInput.value.trim().toLowerCase().split(' ');
                if (nameParts.length >= 2) {
                    const firstName = nameParts[0];
                    const lastName = nameParts[nameParts.length - 1];

                    // Generate variations
                    const variations = [
                        `${firstName}${lastName}`,
                        `${firstName.charAt(0)}${lastName}`,
                        `${firstName}-${lastName}`,
                        `dr${firstName}`
                    ];

                    baseName = variations[Math.floor(Math.random() * variations.length)];
                } else {
                    baseName = nameParts[0].replace(/[^a-z0-9]/g, '').substring(0, 15);
                }

                // Add specialty if available
                if (specialtyInput?.value) {
                    const specialtyMap = {
                        'Cardiology': 'cardio',
                        'Neurology': 'neuro',
                        'Pediatrics': 'peds',
                        'General': 'gp',
                        'Dermatology': 'derma',
                        'Orthopedics': 'ortho',
                        'Gynecology': 'gyno',
                        'Psychiatry': 'psych'
                    };

                    const specialtyShort = specialtyMap[specialtyInput.value] ||
                        specialtyInput.value.toLowerCase().substring(0, 6);

                    baseName = `${baseName}${specialtyShort}`;
                }
            }

            if (!baseName) {
                baseName = 'dr' + Math.floor(Math.random() * 1000);
            }

            subdomainInput.value = baseName.toLowerCase();
            updateSubdomainPreview();
        }

        async function checkSubdomainAvailability() {
            const subdomainInput = document.getElementById('subdomain_name');
            const checkBtn = document.getElementById('checkSubdomainBtn');
            const statusDiv = document.getElementById('subdomainStatus');

            if (!subdomainInput || !checkBtn || !statusDiv) return;

            const subdomain = subdomainInput.value.trim().toLowerCase();

            if (!subdomain) {
                statusDiv.innerHTML = '<div class="alert alert-warning">Please enter a subdomain name</div>';
                subdomainAvailable = false;
                return;
            }

            // Validate format
            const subdomainRegex = /^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/;
            if (!subdomainRegex.test(subdomain)) {
                statusDiv.innerHTML =
                    '<div class="alert alert-danger">Subdomain can only contain letters, numbers, and hyphens</div>';
                subdomainAvailable = false;
                return;
            }

            checkBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Checking...';
            checkBtn.disabled = true;

            try {
                const response = await fetch('/check-subdomain', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        subdomain: subdomain
                    })
                });

                const data = await response.json();

                if (data.available) {
                    statusDiv.innerHTML = `<div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    Subdomain <strong>${subdomain}.{{ config('app.domain', 'doctorsprofile.xyz') }}</strong> is available!
                </div>`;
                    subdomainAvailable = true;
                } else {
                    let suggestionHtml = data.suggestion ?
                        ` Try: <strong>${data.suggestion}.{{ config('app.domain', 'doctorsprofile.xyz') }}</strong>` :
                        '';

                    statusDiv.innerHTML = `<div class="alert alert-danger">
                    <i class="fas fa-times-circle me-2"></i>
                    Subdomain <strong>${subdomain}.{{ config('app.domain', 'doctorsprofile.xyz') }}</strong> is already taken.
                    ${suggestionHtml}
                </div>`;
                    subdomainAvailable = false;

                    if (data.suggestion) {
                        setTimeout(() => {
                            subdomainInput.value = data.suggestion;
                            updateSubdomainPreview();
                        }, 1000);
                    }
                }
            } catch (error) {
                console.error('Error checking subdomain:', error);
                statusDiv.innerHTML =
                    '<div class="alert alert-danger">Error checking subdomain availability. Please try again.</div>';
                subdomainAvailable = false;
            } finally {
                checkBtn.innerHTML = 'Check Availability';
                checkBtn.disabled = false;
            }
        }

        function updateSubdomainPreview() {
            const subdomainInput = document.getElementById('subdomain_name');
            const preview = document.getElementById('subdomainPreview');

            if (subdomainInput && preview) {
                const subdomain = subdomainInput.value.trim().toLowerCase();
                if (subdomain) {
                    preview.textContent = `${subdomain}.{{ config('app.domain', 'doctorsprofile.xyz') }}`;
                } else {
                    preview.textContent = `yourname.{{ config('app.domain', 'doctorsprofile.xyz') }}`;
                }
            }
        }

        // ========== DOMAIN AVAILABILITY CHECK ==========
        async function checkDomainAvailability() {
            const domainInput = document.getElementById('new_domain_name');
            const extensionSelect = document.getElementById('new_domain_extension');
            const checkBtn = document.getElementById('checkDomainBtn');
            const statusDiv = document.getElementById('newDomainStatus');

            if (!domainInput || !checkBtn || !statusDiv) return;

            const domainName = domainInput.value.trim().toLowerCase();
            const extension = extensionSelect?.value || '.com';
            const fullDomain = domainName + extension;

            if (!domainName) {
                statusDiv.innerHTML = '<div class="alert alert-warning">Please enter a domain name</div>';
                domainAvailable = false;
                return;
            }

            // Validate format
            const domainRegex = /^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/;
            if (!domainRegex.test(domainName)) {
                statusDiv.innerHTML =
                    '<div class="alert alert-danger">Domain name can only contain letters, numbers, and hyphens</div>';
                domainAvailable = false;
                return;
            }

            checkBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Checking...';
            checkBtn.disabled = true;

            try {
                const response = await fetch('/check-domain', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        domain: fullDomain
                    })
                });

                const data = await response.json();

                if (data.available) {
                    statusDiv.innerHTML = `<div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    Domain <strong>${fullDomain}</strong> is available for registration!
                </div>`;
                    domainAvailable = true;
                } else {
                    let suggestionsHtml = '';
                    if (data.suggestions && data.suggestions.length > 0) {
                        suggestionsHtml = `<div class="mt-2">
                        <p class="mb-1">Alternative suggestions:</p>
                        <div class="d-flex flex-wrap gap-2">`;

                        data.suggestions.forEach(suggestion => {
                            suggestionsHtml +=
                                `<span class="badge bg-info cursor-pointer" onclick="useDomainSuggestion('${suggestion}')" style="cursor: pointer;">${suggestion}</span>`;
                        });

                        suggestionsHtml += `</div></div>`;
                    }

                    statusDiv.innerHTML = `<div class="alert alert-danger">
                    <i class="fas fa-times-circle me-2"></i>
                    Domain <strong>${fullDomain}</strong> is already registered.
                    ${suggestionsHtml}
                </div>`;
                    domainAvailable = false;
                }
            } catch (error) {
                console.error('Error checking domain:', error);
                statusDiv.innerHTML =
                    '<div class="alert alert-danger">Error checking domain availability. Please try again.</div>';
                domainAvailable = false;
            } finally {
                checkBtn.innerHTML = 'Check Availability';
                checkBtn.disabled = false;
            }
        }

        function useDomainSuggestion(domain) {
            const domainInput = document.getElementById('new_domain_name');
            const extensionSelect = document.getElementById('new_domain_extension');

            const parts = domain.split('.');
            if (parts.length >= 2) {
                const name = parts.slice(0, -1).join('');
                const extension = '.' + parts[parts.length - 1];

                domainInput.value = name;

                if (extensionSelect) {
                    const option = Array.from(extensionSelect.options).find(opt => opt.value === extension);
                    if (option) {
                        extensionSelect.value = extension;
                    }
                }

                setTimeout(() => checkDomainAvailability(), 500);
            }
        }

        // ========== SUMMARY UPDATE ==========
        function updateSummary() {
            // Package Summary
            const selectedPackage = document.querySelector('.package-radio:checked');
            if (selectedPackage) {
                const packageCard = selectedPackage.closest('.package-card');
                const packageName = packageCard?.querySelector('.card-title')?.textContent || 'Basic Plan';
                document.getElementById('summaryPackage').textContent = packageName;

                const billingCycle = packageCard?.querySelector('.billing-cycle')?.value || 'monthly';
                document.getElementById('summaryBilling').textContent = billingCycle.charAt(0).toUpperCase() + billingCycle
                    .slice(1);

                // Calculate package price
                let packagePrice = 0;
                if (billingCycle === 'yearly') {
                    packagePrice = parseFloat(selectedPackage.getAttribute('data-price-yearly'));
                } else if (billingCycle === 'monthly') {
                    packagePrice = parseFloat(selectedPackage.getAttribute('data-price-monthly'));
                }
                document.getElementById('summaryPackageFee').textContent = `$${packagePrice.toFixed(2)}`;
            }

            // Domain Summary
            const domainType = document.querySelector('.domain-type-radio:checked');
            if (domainType) {
                document.getElementById('summaryDomainType').textContent = domainType.value.charAt(0).toUpperCase() +
                    domainType.value.slice(1);

                let domainUrl = '';
                let domainPrice = 0;

                if (domainType.value === 'subdomain') {
                    const subdomain = document.getElementById('subdomain_name')?.value || 'yourname';
                    domainUrl = `${subdomain}.{{ config('app.domain', 'doctorsprofile.xyz') }}`;
                    domainPrice = 0;
                } else if (domainType.value === 'new') {
                    const domainName = document.getElementById('new_domain_name')?.value || 'yourclinic';
                    const extension = document.getElementById('new_domain_extension')?.value || '.com';
                    domainUrl = `${domainName}${extension}`;
                    domainPrice = 14.99;
                } else if (domainType.value === 'existing') {
                    domainUrl = document.getElementById('existing_domain')?.value || 'yourdomain.com';
                    domainPrice = 0;
                }

                document.getElementById('summaryDomainUrl').textContent = domainUrl;
                document.getElementById('summaryDomainFee').textContent = `$${domainPrice.toFixed(2)}`;
            }

            // Specialty & Country
            const specialty = document.getElementById('specialty')?.value || 'Not selected';
            const country = document.getElementById('country')?.value || 'Not selected';
            document.getElementById('summarySpecialty').textContent = specialty;
            document.getElementById('summaryCountry').textContent = country;

            // Calculate total
            const packageFee = parseFloat(document.getElementById('summaryPackageFee').textContent.replace('$', '') || 0);
            const domainFee = parseFloat(document.getElementById('summaryDomainFee').textContent.replace('$', '') || 0);
            const discount = 0; // You can add discount calculation later
            const total = packageFee + domainFee - discount;

            document.getElementById('summaryDiscount').textContent = `-$${discount.toFixed(2)}`;
            document.getElementById('summaryTotal').textContent = `$${total.toFixed(2)}`;

            // Update hidden fields
            document.getElementById('finalPackagePrice').value = packageFee;
            document.getElementById('finalDomainPrice').value = domainFee;
            document.getElementById('finalDiscount').value = discount;
            document.getElementById('finalTotal').value = total;
        }

        // ========== PACKAGE SELECTION ==========
        function updateBillingCycle(select) {
            if (!select) return;

            const packageCard = select.closest('.package-card');
            if (!packageCard) return;

            const radio = packageCard.querySelector('.package-radio');
            const priceElement = packageCard.querySelector('.package-price');
            const periodElement = packageCard.querySelector('.package-period');

            if (!radio || !priceElement || !periodElement) return;

            if (select.value === 'free') {
                priceElement.textContent = 'Free';
                periodElement.textContent = '14-day trial';
            } else if (select.value === 'yearly') {
                const yearlyPrice = radio.getAttribute('data-price-yearly');
                priceElement.textContent = `$${parseFloat(yearlyPrice).toFixed(2)}`;
                periodElement.textContent = '/year';
            } else {
                const monthlyPrice = radio.getAttribute('data-price-monthly');
                priceElement.textContent = `$${parseFloat(monthlyPrice).toFixed(2)}`;
                periodElement.textContent = '/month';
            }
        }

        // ========== UTILITY FUNCTIONS ==========
        function previewImage(input) {
            if (!input || !input.files || !input.files[0]) return;

            const preview = document.getElementById('photoPreviewImage');
            const placeholder = document.getElementById('photoPreview');

            const reader = new FileReader();
            reader.onload = function(e) {
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }

        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            if (!field) return;

            const button = field.parentNode.querySelector('button');
            const icon = button?.querySelector('i');

            if (field.type === 'password') {
                field.type = 'text';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            } else {
                field.type = 'password';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        }

        // ========== BMDC FIELD TOGGLE ==========
        document.getElementById('country')?.addEventListener('change', function() {
            const bmdcGroup = document.getElementById('bmdcGroup');
            const regNoInput = document.getElementById('reg_no');

            if (this.value === 'Bangladesh') {
                if (bmdcGroup) bmdcGroup.classList.remove('d-none');
                if (regNoInput) regNoInput.required = true;
            } else {
                if (bmdcGroup) bmdcGroup.classList.add('d-none');
                if (regNoInput) {
                    regNoInput.required = false;
                    regNoInput.value = '';
                }
            }
        });

        // ========== EVENT LISTENERS FOR SUMMARY UPDATE ==========
        // Update summary when package changes
        document.querySelectorAll('.package-radio, .billing-cycle').forEach(element => {
            element.addEventListener('change', updateSummary);
        });

        // Update summary when domain changes
        document.querySelectorAll(
            '.domain-type-radio, #subdomain_name, #new_domain_name, #new_domain_extension, #existing_domain').forEach(
            element => {
                element.addEventListener('input', updateSummary);
                element.addEventListener('change', updateSummary);
            });

        // Update summary when specialty or country changes
        document.querySelectorAll('#specialty, #country').forEach(element => {
            element.addEventListener('change', updateSummary);
        });
        // ========== COUPON FUNCTIONS ==========
let appliedCoupon = null;
let couponDiscount = 0;

async function applyCoupon() {
    const couponCode = document.getElementById('coupon_code')?.value.trim();
    const statusDiv = document.getElementById('couponStatus');
    const applyBtn = document.getElementById('applyCouponBtn');

    if (!couponCode) {
        statusDiv.innerHTML = '<div class="alert alert-warning">Please enter a coupon code</div>';
        return;
    }

    applyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Applying...';
    applyBtn.disabled = true;

    try {
        const subtotal = calculateSubtotal();
        const response = await fetch('/api/coupons/validate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                code: couponCode,
                amount: subtotal
            })
        });

        const data = await response.json();

        if (data.valid) {
            statusDiv.innerHTML = `<div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                ${data.message}
                <div class="mt-2">
                    <strong>Discount:</strong> $${data.discount_amount.toFixed(2)}
                </div>
            </div>`;

            appliedCoupon = data.coupon;
            couponDiscount = data.discount_amount;

            // Update selected coupon
            document.getElementById('selectedCoupon').value = data.coupon.id;

            // Update summary
            updateSummary();
        } else {
            statusDiv.innerHTML = `<div class="alert alert-danger">
                <i class="fas fa-times-circle me-2"></i>
                ${data.message}
            </div>`;

            appliedCoupon = null;
            couponDiscount = 0;
            document.getElementById('selectedCoupon').value = '';
        }
    } catch (error) {
        console.error('Error applying coupon:', error);
        statusDiv.innerHTML = '<div class="alert alert-danger">Error applying coupon. Please try again.</div>';
    } finally {
        applyBtn.innerHTML = 'Apply Coupon';
        applyBtn.disabled = false;
    }
}

function calculateSubtotal() {
    const packageFee = parseFloat(document.getElementById('summaryPackageFee').textContent.replace('$', '') || 0);
    const domainFee = parseFloat(document.getElementById('summaryDomainFee').textContent.replace('$', '') || 0);
    return packageFee + domainFee;
}

async function showAvailableCoupons() {
    const modal = new bootstrap.Modal(document.getElementById('couponsModal'));
    const listDiv = document.getElementById('availableCouponsList');
    const loadingDiv = document.getElementById('couponsLoading');

    modal.show();

    try {
        const subtotal = calculateSubtotal();
        const response = await fetch(`/api/coupons/available?amount=${subtotal}`);
        const coupons = await response.json();

        loadingDiv.classList.add('d-none');

        if (coupons.length === 0) {
            listDiv.innerHTML = '<div class="col-12"><div class="alert alert-info">No coupons available at the moment.</div></div>';
            return;
        }

        let html = '';
        coupons.forEach(coupon => {
            let discountText = '';
            if (coupon.type === 'percentage') {
                discountText = `${coupon.value}% off`;
            } else {
                discountText = `$${coupon.value} off`;
            }

            if (coupon.max_discount) {
                discountText += ` (max $${coupon.max_discount})`;
            }

            html += `
                <div class="col-md-6">
                    <div class="card h-100 border">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fw-bold mb-0">${coupon.code}</h6>
                                    <p class="text-muted small mb-0">${coupon.description}</p>
                                </div>
                                <span class="badge bg-success">${discountText}</span>
                            </div>
                            <div class="mt-2">
                                <p class="small text-muted mb-1">
                                    <i class="fas fa-dollar-sign me-1"></i>
                                    Min. purchase: $${coupon.min_amount}
                                </p>
                                <p class="small text-muted mb-1">
                                    <i class="fas fa-calendar me-1"></i>
                                    Valid until: ${new Date(coupon.expires_at).toLocaleDateString()}
                                </p>
                                <button type="button" onclick="useCoupon('${coupon.code}')"
                                        class="btn btn-sm btn-outline-primary w-100">
                                    Use This Coupon
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        listDiv.innerHTML = html;
    } catch (error) {
        console.error('Error loading coupons:', error);
        loadingDiv.innerHTML = '<div class="alert alert-danger">Error loading coupons. Please try again.</div>';
    }
}

function useCoupon(code) {
    document.getElementById('coupon_code').value = code;
    const modal = bootstrap.Modal.getInstance(document.getElementById('couponsModal'));
    modal.hide();

    setTimeout(() => {
        applyCoupon();
    }, 500);
}

// ========== PAYMENT OPTION TOGGLE ==========
function setupPaymentOptionToggle() {
    const paymentOptionRadios = document.querySelectorAll('.payment-option-radio');
    const paymentMethodRadios = document.querySelectorAll('.payment-method-radio');

    // Payment option change
    paymentOptionRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const onlineMethods = document.getElementById('onlinePaymentMethods');

            if (this.value === 'online') {
                onlineMethods.classList.remove('d-none');
                updatePaymentSummary('online', 'paypal');
            } else {
                onlineMethods.classList.add('d-none');
                updatePaymentSummary('offline', 'bank_transfer');
            }

            updateSummary();
        });
    });

    // Payment method change
    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const creditCardForm = document.getElementById('creditCardForm');

            if (this.value === 'credit_card') {
                creditCardForm.classList.remove('d-none');
            } else {
                creditCardForm.classList.add('d-none');
            }

            updatePaymentSummary('online', this.value);
        });
    });
}

function updatePaymentSummary(option, method) {
    document.getElementById('summaryPaymentOption').textContent = option.charAt(0).toUpperCase() + option.slice(1);
    document.getElementById('summaryPaymentMethod').textContent = method.split('_').map(word =>
        word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ');
}

// ========== UPDATED SUMMARY CALCULATION ==========
function updateSummary() {
    // ... existing code ...

    // Update total with coupon discount
    const subtotal = packageFee + domainFee;
    const discount = couponDiscount;
    const total = subtotal - discount;

    document.getElementById('summaryDiscount').textContent = `-$${discount.toFixed(2)}`;
    document.getElementById('summaryTotal').textContent = `$${total.toFixed(2)}`;

    // Update hidden fields
    document.getElementById('finalPackagePrice').value = packageFee;
    document.getElementById('finalDomainPrice').value = domainFee;
    document.getElementById('finalDiscount').value = discount;
    document.getElementById('finalTotal').value = total;
}

// ========== INITIALIZE ALL FUNCTIONS ==========
document.addEventListener('DOMContentLoaded', function() {
    // ... existing initialization code ...

    // Initialize payment option toggle
    setupPaymentOptionToggle();

    // Initialize coupon code input
    document.getElementById('coupon_code')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyCoupon();
        }
    });
});
    </script>
@endpush--}}
@extends('layouts.sass')

@section('title', 'Doctor Registration')

@section('content')
    <div class="container-fluid py-5 bg-light">
        <div class="container">
            <!-- Header -->
            <header class="mb-5 mt-5">
                <div class="row align-items-center bg-white py-4 px-3 shadow-sm rounded">
                    <div class="col text-center">
                        <h3 class="display-6 fw-bold text-dark mb-2">Doctor Registration</h3>
                        <p class="lead text-muted mb-0">Complete your professional profile in a few simple steps</p>
                    </div>
                </div>
            </header>

            <!-- Multi-step Progress Bar (4 Steps) -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="progress" style="height: 10px;">
                                <div id="progressBar" class="progress-bar bg-primary" role="progressbar"
                                    style="width: 25%"></div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                @php
                                    $steps = [
                                        ['number' => 1, 'title' => 'Package', 'icon' => 'fas fa-box'],
                                        ['number' => 2, 'title' => 'Domain', 'icon' => 'fas fa-globe'],
                                        ['number' => 3, 'title' => 'Account', 'icon' => 'fas fa-user-circle'],
                                        ['number' => 4, 'title' => 'Payment', 'icon' => 'fas fa-credit-card'],
                                    ];
                                @endphp

                                @foreach ($steps as $step)
                                    <div class="text-center">
                                        <div class="step-circle mx-auto mb-2 rounded-circle border d-flex align-items-center justify-content-center
                                    {{ $step['number'] == 1 ? 'border-primary bg-primary text-white' : 'border-secondary text-secondary' }}"
                                            style="width: 50px; height: 50px;">
                                            <i class="{{ $step['icon'] }}"></i>
                                        </div>
                                        <p
                                            class="mb-0 fw-medium {{ $step['number'] == 1 ? 'text-primary' : 'text-secondary' }}">
                                            {{ $step['title'] }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <h5 class="alert-heading">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Please correct the following errors:
                    </h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Multi-step Form (4 Steps) -->
            <form id="doctorRegistrationForm" method="post" action="{{ route('doctor.store') }}"
                enctype="multipart/form-data">
                @csrf

                <!-- Hidden Fields -->
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <input type="hidden" name="city" id="city">
                <!-- SINGLE billing_cycle field (not multiple) -->
                <input type="hidden" id="selectedBillingCycle" name="billing_cycle" value="monthly">
                <input type="hidden" id="selectedCoupon" name="coupon_id" value="">
                <input type="hidden" id="finalPackagePrice" name="package_price" value="0">
                <input type="hidden" id="finalDomainPrice" name="domain_price" value="0">
                <input type="hidden" id="finalDiscount" name="discount_amount" value="0">
                <input type="hidden" id="finalTotal" name="total_amount" value="0">
                <input type="hidden" id="selectedSubdomain" name="subdomain" value="">

                <!-- Step 1: Package Selection -->
                <div id="step1" class="form-step">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-box text-primary me-2"></i>
                                        Step 1: Select Package
                                    </h5>
                                    <p class="text-muted mb-0 mt-1">Choose the plan that fits your practice needs</p>
                                </div>
                                <span class="badge bg-primary rounded-pill">Required</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                @foreach ($packages as $index => $package)
                                    <div class="col-md-4">
                                        <label class="card h-100 border package-card" style="cursor: pointer;">
                                            <div class="card-body d-flex flex-column">
                                                <div class="form-check mb-3">
                                                    <input type="radio" name="package_id" value="{{ $package->id }}"
                                                        required class="form-check-input package-radio"
                                                        {{ old('package_id') == $package->id ? 'checked' : ($loop->first ? 'checked' : '') }}
                                                        data-price-monthly="{{ $package->price_monthly }}"
                                                        data-price-yearly="{{ $package->price_yearly }}"
                                                        id="package_{{ $package->id }}">
                                                </div>

                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h5 class="card-title fw-bold mb-0">{{ $package->name }}</h5>
                                                    </div>
                                                    <p class="card-text text-muted">{{ $package->description }}</p>
                                                </div>

                                                <div class="mb-4 flex-grow-1">
                                                    @php
                                                        $features = explode(',', $package->features ?? '');
                                                    @endphp
                                                    @foreach (array_slice($features, 0, 4) as $feature)
                                                        <p class="mb-2">
                                                            <i class="fas fa-check text-success me-2"></i>
                                                            {{ trim($feature) }}
                                                        </p>
                                                    @endforeach
                                                </div>

                                                <div class="mt-auto">
                                                    <div class="mb-3">
                                                        <!-- Remove name attribute to prevent submission of all selects -->
                                                        <select class="form-select billing-cycle-select"
                                                                data-package-id="{{ $package->id }}">
                                                            <option value="monthly">Monthly:
                                                                ${{ number_format($package->price_monthly, 2) }}/mo
                                                            </option>
                                                            <option value="yearly">Yearly:
                                                                ${{ number_format($package->price_yearly, 2) }}/yr</option>
                                                            <option value="free">Free Trial (14 days)</option>
                                                        </select>
                                                    </div>

                                                    <div class="text-center">
                                                        <h4 class="fw-bold mb-0 package-price">
                                                            ${{ number_format($package->price_monthly, 2) }}</h4>
                                                        <p class="text-muted mb-0 package-period">/month</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            @error('package_id')
                                <div class="alert alert-danger mt-3">{{ $message }}</div>
                            @enderror

                            <!-- Step Navigation -->
                            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                                <button type="button" onclick="nextStep(2)" class="btn btn-primary btn-lg px-5">
                                    Next Step <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Domain Selection -->
                <div id="step2" class="form-step d-none">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-globe text-primary me-2"></i>
                                        Step 2: Choose Your Domain
                                    </h5>
                                    <p class="text-muted mb-0 mt-1">Select your professional web address</p>
                                </div>
                                <span class="badge bg-primary rounded-pill">Required</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Domain Type Selection -->
                            <div class="mb-4">
                                <label class="form-label fw-medium mb-3">
                                    <i class="fas fa-sitemap text-primary me-2"></i>
                                    Choose Domain Type
                                </label>
                                <div class="row g-4">
                                    <!-- Subdomain Option (Default) -->
                                    <div class="col-md-4">
                                        <label class="card h-100 border domain-type-option" style="cursor: pointer;">
                                            <div class="position-relative">
                                                <div class="form-check position-absolute top-3 end-3">
                                                    <input type="radio" name="domain_type" value="subdomain" required
                                                        class="form-check-input domain-type-radio"
                                                        id="domain_type_subdomain" checked>
                                                </div>

                                                <span
                                                    class="position-absolute top-0 start-50 translate-middle badge bg-success">
                                                    FREE
                                                </span>
                                            </div>

                                            <div class="card-body text-center">
                                                <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                                                    style="width: 60px; height: 60px;">
                                                    <i class="fas fa-link text-success fa-lg"></i>
                                                </div>

                                                <h6 class="fw-bold">Subdomain</h6>
                                                <p class="text-muted small mb-3">Use our free subdomain service</p>

                                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                                    Free Forever
                                                </span>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- New Domain Option -->
                                    <div class="col-md-4">
                                        <label class="card h-100 border domain-type-option" style="cursor: pointer;">
                                            <div class="card-body text-center">
                                                <div class="form-check position-absolute top-3 end-3">
                                                    <input type="radio" name="domain_type" value="new" required
                                                        class="form-check-input domain-type-radio" id="domain_type_new">
                                                </div>

                                                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                                                    style="width: 60px; height: 60px;">
                                                    <i class="fas fa-plus text-primary fa-lg"></i>
                                                </div>

                                                <h6 class="fw-bold">New Domain</h6>
                                                <p class="text-muted small mb-3">Register a brand new domain</p>

                                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                    $14.99/year
                                                </span>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Existing Domain Option -->
                                    <div class="col-md-4">
                                        <label class="card h-100 border domain-type-option" style="cursor: pointer;">
                                            <div class="card-body text-center">
                                                <div class="form-check position-absolute top-3 end-3">
                                                    <input type="radio" name="domain_type" value="existing" required
                                                        class="form-check-input domain-type-radio"
                                                        id="domain_type_existing">
                                                </div>

                                                <div class="rounded-circle bg-purple bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                                                    style="width: 60px; height: 60px;">
                                                    <i class="fas fa-globe text-purple fa-lg"></i>
                                                </div>

                                                <h6 class="fw-bold">Existing Domain</h6>
                                                <p class="text-muted small mb-3">Connect your own domain</p>

                                                <span class="badge bg-purple bg-opacity-10 text-purple px-3 py-2">
                                                    No Registration Fee
                                                </span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Subdomain Input Section (Default Visible) -->
                            <div id="subdomainSection" class="mb-4">
                                <div class="mb-3">
                                    <label for="subdomain_name" class="form-label fw-medium">
                                        <i class="fas fa-link text-success me-2"></i>
                                        Choose Your Subdomain <span class="text-danger">*</span>
                                    </label>
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input type="text" id="subdomain_name" name="subdomain_name"
                                                    class="form-control" placeholder="yourname"
                                                    value="{{ old('subdomain_name') }}">
                                                <span
                                                    class="input-group-text bg-light">.{{ config('app.domain', 'doctorsprofile.xyz') }}</span>
                                                <button type="button" onclick="checkSubdomainAvailability()"
                                                    class="btn btn-success" id="checkSubdomainBtn">
                                                    Check Availability
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" onclick="suggestSmartSubdomain()"
                                                class="btn btn-outline-success w-100">
                                                <i class="fas fa-lightbulb me-2"></i>
                                                Smart Suggest
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-text">
                                        Your website will be available at:
                                        <span id="subdomainPreview"
                                            class="fw-medium text-success">yourname.{{ config('app.domain', 'doctorsprofile.xyz') }}</span>
                                    </div>
                                    <div id="subdomainStatus" class="mt-2"></div>
                                </div>

                                <div class="alert alert-success">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-success bg-opacity-25 d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px;">
                                                <i class="fas fa-gift text-success fa-lg"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fw-bold mb-1">Free Subdomain Included!</h6>
                                            <p class="mb-0">No additional cost. Your subdomain is included with your
                                                package.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- New Domain Input Section (Hidden by Default) -->
                            <div id="newDomainSection" class="mb-4 d-none">
                                <div class="mb-3">
                                    <label for="new_domain_name" class="form-label fw-medium">
                                        <i class="fas fa-globe-americas text-primary me-2"></i>
                                        Choose Your Domain <span class="text-danger">*</span>
                                    </label>
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">www.</span>
                                                <input type="text" id="new_domain_name" name="new_domain_name"
                                                    class="form-control" placeholder="yourclinic"
                                                    value="{{ old('new_domain_name') }}">
                                                <button type="button" onclick="checkDomainAvailability()"
                                                    class="btn btn-primary" id="checkDomainBtn">
                                                    Check Availability
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <select id="new_domain_extension" name="new_domain_extension"
                                                class="form-select">
                                                <option value=".com">.com</option>
                                                <option value=".net">.net</option>
                                                <option value=".org">.org</option>
                                                <option value=".health">.health</option>
                                                <option value=".clinic">.clinic</option>
                                                <option value=".doctor">.doctor</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="newDomainStatus" class="mt-2"></div>
                                </div>

                                <div class="alert alert-info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Domain Registration Fee</h6>
                                            <p class="mb-0">One-time yearly registration</p>
                                        </div>
                                        <div class="text-end">
                                            <h4 class="fw-bold text-primary mb-0">$14.99</h4>
                                            <p class="text-muted mb-0">per year</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Existing Domain Input Section (Hidden by Default) -->
                            <div id="existingDomainSection" class="mb-4 d-none">
                                <div class="mb-3">
                                    <label for="existing_domain" class="form-label fw-medium">
                                        <i class="fas fa-external-link-alt text-purple me-2"></i>
                                        Your Existing Domain <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="existing_domain" name="existing_domain"
                                        class="form-control" placeholder="yourdomain.com"
                                        value="{{ old('existing_domain') }}">
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        After registration, you'll receive instructions to update your DNS settings.
                                    </div>
                                </div>

                                <div class="alert alert-purple">
                                    <h6 class="fw-bold mb-2">Domain Connection Instructions:</h6>
                                    <ol class="mb-0">
                                        <li>Complete your registration</li>
                                        <li>Check your email for DNS configuration details</li>
                                        <li>Update your domain's DNS settings with your registrar</li>
                                        <li>Allow 24-48 hours for propagation</li>
                                    </ol>
                                </div>
                            </div>

                            <!-- Step Navigation -->
                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                <button type="button" onclick="prevStep(1)"
                                    class="btn btn-outline-secondary btn-lg px-5">
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                <button type="button" onclick="nextStep(3)" class="btn btn-primary btn-lg px-5"
                                    id="step2NextBtn">
                                    Next Step <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Account Details (Now Step 3 after removal) -->
                <div id="step3" class="form-step d-none">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-user-circle text-primary me-2"></i>
                                        Step 3: Account Details
                                    </h5>
                                    <p class="text-muted mb-0 mt-1">Set up your login credentials</p>
                                </div>
                                <span class="badge bg-primary rounded-pill">Required</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <!-- Profile Photo -->
                                <div class="col-12">
                                    <label class="form-label fw-medium">
                                        <i class="fas fa-camera text-primary me-2"></i>
                                        Professional Photo <span class="text-danger">*</span>
                                    </label>
                                    <div class="d-flex align-items-center gap-4">
                                        <div class="border rounded p-3 bg-light text-center position-relative"
                                            style="width: 150px; height: 150px;">
                                            <div id="photoPreview" class="h-100 d-flex flex-column align-items-center justify-content-center">
                                                <i class="fas fa-user text-muted fa-3x mb-2"></i>
                                                <p class="small text-muted mb-0">Preview</p>
                                            </div>
                                            <img id="photoPreviewImage" class="img-fluid rounded d-none position-absolute top-0 start-0 w-100 h-100" src="" alt="Preview" style="object-fit: cover;">
                                        </div>
                                        <div class="flex-grow-1">
                                            <input type="file" id="photo" name="photo" accept="image/*" required
                                                class="form-control" onchange="previewImage(this)">
                                            <div class="form-text mt-2">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Required: Square image, max 2MB, professional headshot (JPEG, PNG, GIF)
                                            </div>
                                            <div id="photoError" class="text-danger small mt-1 d-none"></div>
                                        </div>
                                    </div>
                                    @error('photo')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-medium">
                                        <i class="fas fa-envelope text-primary me-2"></i>
                                        Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" required
                                        value="{{ old('email') }}" class="form-control"
                                        placeholder="doctor@example.com">
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-medium">
                                        <i class="fas fa-phone text-primary me-2"></i>
                                        Contact Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" id="phone" name="phone" required
                                        value="{{ old('phone') }}" class="form-control" placeholder="+8801XXXXXXXXX">
                                    @error('phone')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-medium">
                                        <i class="fas fa-lock text-primary me-2"></i>
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" id="password" name="password" required
                                            class="form-control">
                                        <button type="button" onclick="togglePassword('password')"
                                            class="btn btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-medium">
                                        <i class="fas fa-lock text-primary me-2"></i>
                                        Confirm Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            required class="form-control">
                                        <button type="button" onclick="togglePassword('password_confirmation')"
                                            class="btn btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Step Navigation -->
                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                <button type="button" onclick="prevStep(2)"
                                    class="btn btn-outline-secondary btn-lg px-5">
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                <button type="button" onclick="nextStep(4)" class="btn btn-primary btn-lg px-5">
                                    Next Step <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Review & Payment (Now Step 4 after removal) -->
                <div id="step4" class="form-step d-none">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-credit-card text-primary me-2"></i>
                                        Step 4: Review & Payment
                                    </h5>
                                    <p class="text-muted mb-0 mt-1">Review your information and complete payment</p>
                                </div>
                                <span class="badge bg-success rounded-pill">Final Step</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Payment Option -->
                            <div class="mb-4">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-money-check-alt text-primary me-2"></i>
                                    Payment Option <span class="text-danger">*</span>
                                </label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="card h-100 border payment-option-card" style="cursor: pointer;">
                                            <div class="card-body text-center">
                                                <div class="form-check position-absolute top-3 end-3">
                                                    <input type="radio" name="payment_option" value="online" required
                                                           class="form-check-input payment-option-radio" id="payment_option_online" checked>
                                                </div>
                                                <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                                                     style="width: 60px; height: 60px;">
                                                    <i class="fas fa-globe text-success fa-lg"></i>
                                                </div>
                                                <h6 class="fw-bold">Online Payment</h6>
                                                <p class="text-muted small">Pay now to activate immediately</p>
                                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                                    Instant Activation
                                                </span>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="card h-100 border payment-option-card" style="cursor: pointer;">
                                            <div class="card-body text-center">
                                                <div class="form-check position-absolute top-3 end-3">
                                                    <input type="radio" name="payment_option" value="offline" required
                                                           class="form-check-input payment-option-radio" id="payment_option_offline">
                                                </div>
                                                <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                                                     style="width: 60px; height: 60px;">
                                                    <i class="fas fa-building text-warning fa-lg"></i>
                                                </div>
                                                <h6 class="fw-bold">Offline Payment</h6>
                                                <p class="text-muted small">Pay later via bank transfer</p>
                                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">
                                                    Manual Approval
                                                </span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Method (Only shown for online payment) -->
                            <div id="onlinePaymentMethods" class="mb-4">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-credit-card text-primary me-2"></i>
                                    Payment Method <span class="text-danger">*</span>
                                </label>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="card h-100 border payment-method-card" style="cursor: pointer;">
                                            <div class="card-body text-center">
                                                <div class="form-check position-absolute top-3 end-3">
                                                    <input type="radio" name="payment_method" value="paypal"
                                                           class="form-check-input payment-method-radio" id="payment_method_paypal" checked>
                                                </div>
                                                <div class="mb-3">
                                                    <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg"
                                                         alt="PayPal" style="height: 30px;">
                                                </div>
                                                <h6 class="fw-bold">PayPal</h6>
                                                <p class="text-muted small">Pay with PayPal account</p>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="card h-100 border payment-method-card" style="cursor: pointer;">
                                            <div class="card-body text-center">
                                                <div class="form-check position-absolute top-3 end-3">
                                                    <input type="radio" name="payment_method" value="sslcommerz"
                                                           class="form-check-input payment-method-radio" id="payment_method_sslcommerz">
                                                </div>
                                                <div class="mb-3">
                                                    <span class="badge bg-info text-white p-2">SSLCOMMERZ</span>
                                                </div>
                                                <h6 class="fw-bold">SSL e-Commerce</h6>
                                                <p class="text-muted small">Local payment gateway</p>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="card h-100 border payment-method-card" style="cursor: pointer;">
                                            <div class="card-body text-center">
                                                <div class="form-check position-absolute top-3 end-3">
                                                    <input type="radio" name="payment_method" value="credit_card"
                                                           class="form-check-input payment-method-radio" id="payment_method_credit_card">
                                                </div>
                                                <div class="mb-3">
                                                    <i class="fas fa-credit-card fa-2x text-primary"></i>
                                                </div>
                                                <h6 class="fw-bold">Credit Card</h6>
                                                <p class="text-muted small">Direct card payment</p>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="card h-100 border payment-method-card" style="cursor: pointer;">
                                            <div class="card-body text-center">
                                                <div class="form-check position-absolute top-3 end-3">
                                                    <input type="radio" name="payment_method" value="bank_transfer"
                                                           class="form-check-input payment-method-radio" id="payment_method_bank_transfer">
                                                </div>
                                                <div class="mb-3">
                                                    <i class="fas fa-university fa-2x text-purple"></i>
                                                </div>
                                                <h6 class="fw-bold">Bank Transfer</h6>
                                                <p class="text-muted small">Manual bank transfer</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Credit Card Form (Only shown for credit card selection) -->
                                <div id="creditCardForm" class="mt-4 d-none">
                                    <div class="card border">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-bold"><i class="fas fa-credit-card me-2"></i>Card Details</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="card_number" class="form-label">Card Number</label>
                                                    <input type="text" id="card_number" name="card_number"
                                                           class="form-control" placeholder="1234 5678 9012 3456">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                                    <input type="text" id="expiry_date" name="expiry_date"
                                                           class="form-control" placeholder="MM/YY">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="cvv" class="form-label">CVV</label>
                                                    <input type="password" id="cvv" name="cvv"
                                                           class="form-control" placeholder="123">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="card_holder" class="form-label">Card Holder Name</label>
                                                    <input type="text" id="card_holder" name="card_holder"
                                                           class="form-control" placeholder="John Doe">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Coupon Section -->
                            <div class="mb-4">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-tag text-primary me-2"></i>
                                    Apply Coupon (Optional)
                                </label>
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" id="coupon_code" name="coupon_code"
                                                   class="form-control" placeholder="Enter coupon code">
                                            <button type="button" onclick="applyCoupon()" class="btn btn-primary" id="applyCouponBtn">
                                                Apply Coupon
                                            </button>
                                        </div>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Apply a valid coupon code to get discount on your purchase
                                        </div>
                                        <div id="couponStatus" class="mt-2"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" onclick="showAvailableCoupons()" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-gift me-2"></i>
                                            View Available Coupons
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary Content -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card border">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-bold"><i class="fas fa-file-alt me-2"></i>Registration Summary</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <p class="mb-1"><strong>Package:</strong> <span id="summaryPackage">Basic Plan</span></p>
                                                <p class="mb-1"><strong>Billing Cycle:</strong> <span id="summaryBilling">Monthly</span></p>
                                                <p class="mb-1"><strong>Domain Type:</strong> <span id="summaryDomainType">Subdomain</span></p>
                                                <p class="mb-1"><strong>Website URL:</strong> <span id="summaryDomainUrl">yourname.doctorsprofile.xyz</span></p>
                                                <p class="mb-1"><strong>Email:</strong> <span id="summaryEmail">Not provided</span></p>
                                                <p class="mb-1"><strong>Phone:</strong> <span id="summaryPhone">Not provided</span></p>
                                                <p class="mb-1"><strong>Payment Option:</strong> <span id="summaryPaymentOption">Online</span></p>
                                                <p class="mb-1"><strong>Payment Method:</strong> <span id="summaryPaymentMethod">PayPal</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-bold"><i class="fas fa-money-bill me-2"></i>Payment Summary</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Package Fee:</span>
                                                    <span id="summaryPackageFee">$29.99</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Domain Fee:</span>
                                                    <span id="summaryDomainFee">$0.00</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2 text-success">
                                                    <span>Discount:</span>
                                                    <span id="summaryDiscount">-$0.00</span>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between fw-bold">
                                                    <span>Total Amount:</span>
                                                    <span id="summaryTotal">$29.99</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" data-bs-toggle="modal"
                                    data-bs-target="#termsModal">Terms & Conditions</a> and <a href="#"
                                    data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a>
                                </label>
                                @error('terms')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Step Navigation -->
                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                <button type="button" onclick="prevStep(3)"
                                    class="btn btn-outline-secondary btn-lg px-5">
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                <button type="submit" id="completeRegistrationBtn" class="btn btn-success btn-lg px-5">
                                    <i class="fas fa-lock me-2"></i>
                                    Complete Registration
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Back to Home -->
            <div class="text-center mt-5">
                <a href="{{ url('/') }}" class="text-decoration-none text-muted">
                    <i class="fas fa-arrow-left me-2"></i>
                    Return to Home
                </a>
            </div>
        </div>
    </div>

    <!-- Available Coupons Modal -->
    <div class="modal fade" id="couponsModal" tabindex="-1" aria-labelledby="couponsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="couponsModalLabel">
                        <i class="fas fa-gift text-primary me-2"></i>
                        Available Coupons
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="availableCouponsList" class="row g-3">
                        <!-- Coupons will be loaded here -->
                    </div>
                    <div id="couponsLoading" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading available coupons...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms & Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms & Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Add your terms and conditions here -->
                    <p>By registering, you agree to our terms and conditions...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Add your privacy policy here -->
                    <p>Our privacy policy explains how we handle your data...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // ========== MULTI-STEP FORM (4 STEPS) ==========
    let currentStep = 1;
    const totalSteps = 4;
    let subdomainAvailable = false;
    let domainAvailable = false;
    let appliedCoupon = null;
    let couponDiscount = 0;

    // Track completion status for each step
    const stepCompletion = {
        1: false,
        2: false,
        3: false,
        4: false
    };

    // Initialize form on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Form initialized - Current step:', currentStep);
        showStep(1);
        updateProgress();

        // Initialize package pricing
        initializePackageSelection();

        // Setup domain type toggle
        setupDomainTypeToggle();

        // Setup payment option toggle
        setupPaymentOptionToggle();

        // Auto-check subdomain on input
        document.getElementById('subdomain_name')?.addEventListener('input', function() {
            updateSubdomainPreview();
        });

        // Initialize domain preview
        updateSubdomainPreview();

        // Update summary on each step
        updateSummary();

        // Mark Step 1 as completed (package is required)
        stepCompletion[1] = true;
    });

    // ========== STEP NAVIGATION ==========
    function updateProgress() {
        const progressBar = document.getElementById('progressBar');
        if (progressBar) {
            const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
            progressBar.style.width = `${progressPercentage}%`;
        }

        // Update step circles
        document.querySelectorAll('.step-circle').forEach((circle, index) => {
            const stepNumber = index + 1;
            if (stepNumber <= currentStep) {
                circle.classList.remove('border-secondary', 'text-secondary');
                circle.classList.add('border-primary', 'bg-primary', 'text-white');
            } else {
                circle.classList.remove('border-primary', 'bg-primary', 'text-white');
                circle.classList.add('border-secondary', 'text-secondary');
            }
        });
    }

    function nextStep(step) {
        console.log(`Moving to step ${step} from step ${currentStep}`);

        if (validateCurrentStep()) {
            stepCompletion[currentStep] = true;
            hideAllSteps();
            currentStep = step;
            showStep(currentStep);
            updateProgress();
            updateSummary();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
            return true;
        }
        return false;
    }

    function prevStep(step) {
        hideAllSteps();
        currentStep = step;
        showStep(currentStep);
        updateProgress();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    function hideAllSteps() {
        document.querySelectorAll('.form-step').forEach(step => {
            step.classList.add('d-none');
        });
    }

    function showStep(stepNumber) {
        const stepElement = document.getElementById(`step${stepNumber}`);
        if (stepElement) {
            stepElement.classList.remove('d-none');
            console.log(`Showing step ${stepNumber}`);
        }
    }

    // ========== FORM VALIDATION ==========
    function validateCurrentStep() {
        console.log(`Validating current step: ${currentStep}`);

        switch (currentStep) {
            case 1:
                return validateStep1();
            case 2:
                return validateStep2();
            case 3:
                return validateStep3();
            case 4:
                return validateStep4();
            default:
                return true;
        }
    }

    function validateStep1() {
        console.log('Validating Step 1');

        const selectedPackage = document.querySelector('.package-radio:checked');
        if (!selectedPackage) {
            alert('⚠️ Please select a package to continue.');
            return false;
        }

        console.log('Step 1 validation passed');
        stepCompletion[1] = true;
        return true;
    }

    function validateStep2() {
        console.log('Validating Step 2');

        const domainType = document.querySelector('.domain-type-radio:checked');
        if (!domainType) {
            alert('⚠️ Please select a domain type.');
            return false;
        }

        const domainTypeValue = domainType.value;
        console.log(`Domain type selected: ${domainTypeValue}`);

        if (domainTypeValue === 'subdomain') {
            const subdomainInput = document.getElementById('subdomain_name');
            const subdomain = subdomainInput?.value.trim();

            if (!subdomain) {
                alert('⚠️ Please enter a subdomain name.');
                subdomainInput?.focus();
                return false;
            }

            // Check subdomain format
            const subdomainRegex = /^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/i;
            if (!subdomainRegex.test(subdomain.toLowerCase())) {
                alert('⚠️ Subdomain can only contain letters, numbers, and hyphens.');
                subdomainInput?.focus();
                return false;
            }

            // Don't require availability check for now - just mark as completed
            // if (!subdomainAvailable) {
            //     alert('⚠️ Please check subdomain availability before proceeding.');
            //     document.getElementById('checkSubdomainBtn')?.click();
            //     return false;
            // }

            console.log('Step 2 (subdomain) validation passed');
            stepCompletion[2] = true;
            return true;

        } else if (domainTypeValue === 'new') {
            const domainInput = document.getElementById('new_domain_name');
            const domain = domainInput?.value.trim();

            if (!domain) {
                alert('⚠️ Please enter a domain name.');
                domainInput?.focus();
                return false;
            }

            const domainRegex = /^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/i;
            if (!domainRegex.test(domain.toLowerCase())) {
                alert('⚠️ Domain name can only contain letters, numbers, and hyphens.');
                domainInput?.focus();
                return false;
            }

            // Don't require availability check for now
            // if (!domainAvailable) {
            //     alert('⚠️ Please check domain availability before proceeding.');
            //     document.getElementById('checkDomainBtn')?.click();
            //     return false;
            // }

            console.log('Step 2 (new domain) validation passed');
            stepCompletion[2] = true;
            return true;

        } else if (domainTypeValue === 'existing') {
            const existingDomainInput = document.getElementById('existing_domain');
            const domain = existingDomainInput?.value.trim();

            if (!domain) {
                alert('⚠️ Please enter your existing domain.');
                existingDomainInput?.focus();
                return false;
            }

            const domainRegex = /^(?!:\/\/)([a-zA-Z0-9-_]+\.)*[a-zA-Z0-9][a-zA-Z0-9-_]+\.[a-zA-Z]{2,11}?$/;
            if (!domainRegex.test(domain)) {
                alert('⚠️ Please enter a valid domain name (e.g., yourdomain.com).');
                existingDomainInput?.focus();
                return false;
            }

            console.log('Step 2 (existing domain) validation passed');
            stepCompletion[2] = true;
            return true;
        }

        alert('⚠️ Please select a valid domain type.');
        return false;
    }

    function validateStep3() {
        const email = document.getElementById('email');
        const phone = document.getElementById('phone');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const photo = document.getElementById('photo');

        // Email validation
        if (!email?.value.trim()) {
            alert('⚠️ Email address is required.');
            email?.focus();
            return false;
        }

        if (!isValidEmail(email.value)) {
            alert('⚠️ Please enter a valid email address.');
            email?.focus();
            return false;
        }

        // Phone validation
        if (!phone?.value.trim()) {
            alert('⚠️ Contact number is required.');
            phone?.focus();
            return false;
        }

        // Password validation
        if (!password?.value) {
            alert('⚠️ Password is required.');
            password?.focus();
            return false;
        }

        if (password.value.length < 8) {
            alert('⚠️ Password must be at least 8 characters.');
            password?.focus();
            return false;
        }

        if (password.value !== confirmPassword?.value) {
            alert('⚠️ Passwords do not match.');
            confirmPassword?.focus();
            return false;
        }

        // Photo validation (make optional for testing)
        if (!photo?.files.length) {
            // Comment this out for testing
            // alert('⚠️ Please upload your professional photo.');
            // return false;
            console.log('Photo not uploaded - optional for now');
        }

        console.log('Step 3 validation passed');
        stepCompletion[3] = true;
        return true;
    }

    function validateStep4() {
        const terms = document.getElementById('terms');

        if (!terms?.checked) {
            alert('⚠️ You must agree to the Terms & Conditions and Privacy Policy.');
            terms?.focus();
            return false;
        }

        console.log('Step 4 validation passed');
        stepCompletion[4] = true;
        return true;
    }

    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // ========== PACKAGE SELECTION - FIXED ==========
    function initializePackageSelection() {
        console.log('Initializing package selection...');

        // Get all billing cycle selects
        const billingSelects = document.querySelectorAll('.billing-cycle-select');

        billingSelects.forEach(select => {
            // Initialize each select display
            updateBillingCycleDisplay(select);

            // Add change event listener
            select.addEventListener('change', function() {
                updateBillingCycleDisplay(this);

                // Check if this package is selected
                const packageCard = this.closest('.package-card');
                const packageRadio = packageCard.querySelector('.package-radio');

                if (packageRadio && packageRadio.checked) {
                    // Update the hidden billing cycle field
                    document.getElementById('selectedBillingCycle').value = this.value;
                    updateSummary();
                }
            });
        });

        // Add change event to package radios
        document.querySelectorAll('.package-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                const packageCard = this.closest('.package-card');
                const billingSelect = packageCard.querySelector('.billing-cycle-select');

                if (billingSelect) {
                    // Update the hidden billing cycle field
                    document.getElementById('selectedBillingCycle').value = billingSelect.value;
                    updateSummary();
                }
            });
        });
    }

    function updateBillingCycleDisplay(select) {
        if (!select) return;

        const packageCard = select.closest('.package-card');
        if (!packageCard) return;

        const radio = packageCard.querySelector('.package-radio');
        const priceElement = packageCard.querySelector('.package-price');
        const periodElement = packageCard.querySelector('.package-period');

        if (!radio || !priceElement || !periodElement) return;

        if (select.value === 'free') {
            priceElement.textContent = 'Free';
            periodElement.textContent = '14-day trial';
        } else if (select.value === 'yearly') {
            const yearlyPrice = radio.getAttribute('data-price-yearly');
            priceElement.textContent = `$${parseFloat(yearlyPrice).toFixed(2)}`;
            periodElement.textContent = '/year';
        } else {
            const monthlyPrice = radio.getAttribute('data-price-monthly');
            priceElement.textContent = `$${parseFloat(monthlyPrice).toFixed(2)}`;
            periodElement.textContent = '/month';
        }
    }

    // ========== DOMAIN TYPE TOGGLE ==========
    function setupDomainTypeToggle() {
        console.log('Setting up domain type toggle');

        const domainTypeRadios = document.querySelectorAll('.domain-type-radio');

        domainTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                console.log(`Domain type changed to: ${this.value}`);

                // Show/hide sections based on selection
                document.getElementById('subdomainSection')?.classList.add('d-none');
                document.getElementById('newDomainSection')?.classList.add('d-none');
                document.getElementById('existingDomainSection')?.classList.add('d-none');

                if (this.value === 'subdomain') {
                    document.getElementById('subdomainSection')?.classList.remove('d-none');
                } else if (this.value === 'new') {
                    document.getElementById('newDomainSection')?.classList.remove('d-none');
                } else if (this.value === 'existing') {
                    document.getElementById('existingDomainSection')?.classList.remove('d-none');
                }

                // Update summary
                updateSummary();
            });
        });

        // Initialize with default (subdomain)
        const defaultRadio = document.querySelector('.domain-type-radio[value="subdomain"]');
        if (defaultRadio) {
            defaultRadio.checked = true;
            document.getElementById('subdomainSection')?.classList.remove('d-none');
        }
    }

    // ========== PAYMENT OPTION TOGGLE ==========
    function setupPaymentOptionToggle() {
        const paymentOptionRadios = document.querySelectorAll('.payment-option-radio');

        paymentOptionRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const onlineMethods = document.getElementById('onlinePaymentMethods');
                const creditCardForm = document.getElementById('creditCardForm');
                const paymentMethodRadios = document.querySelectorAll('.payment-method-radio');

                if (this.value === 'online') {
                    onlineMethods.classList.remove('d-none');
                    // Enable and check first payment method
                    paymentMethodRadios.forEach((radio, index) => {
                        radio.disabled = false;
                        if (index === 0) radio.checked = true;
                    });
                } else {
                    onlineMethods.classList.add('d-none');
                    creditCardForm.classList.add('d-none');
                    // Disable and uncheck payment method radios for offline
                    paymentMethodRadios.forEach(radio => {
                        radio.disabled = true;
                        radio.checked = false;
                    });
                }

                updateSummary();
            });
        });

        // Payment method change
        document.querySelectorAll('.payment-method-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                const creditCardForm = document.getElementById('creditCardForm');

                if (this.value === 'credit_card') {
                    creditCardForm.classList.remove('d-none');
                } else {
                    creditCardForm.classList.add('d-none');
                }

                updateSummary();
            });
        });
    }

    // ========== SUMMARY UPDATE ==========
    function updateSummary() {
        console.log('Updating summary...');

        // Package Summary
        const selectedPackage = document.querySelector('.package-radio:checked');
        let packagePrice = 0;
        let billingCycle = 'monthly';

        if (selectedPackage) {
            const packageCard = selectedPackage.closest('.package-card');
            const packageName = packageCard?.querySelector('.card-title')?.textContent || 'Basic Plan';
            document.getElementById('summaryPackage').textContent = packageName;

            // Get billing cycle from the hidden field (updated by package selection)
            billingCycle = document.getElementById('selectedBillingCycle').value || 'monthly';
            document.getElementById('summaryBilling').textContent = billingCycle.charAt(0).toUpperCase() + billingCycle.slice(1);

            // Calculate package price
            if (billingCycle === 'yearly') {
                packagePrice = parseFloat(selectedPackage.getAttribute('data-price-yearly'));
            } else if (billingCycle === 'monthly') {
                packagePrice = parseFloat(selectedPackage.getAttribute('data-price-monthly'));
            } else if (billingCycle === 'free') {
                packagePrice = 0;
            }
        }

        document.getElementById('summaryPackageFee').textContent = `$${packagePrice.toFixed(2)}`;

        // Domain Summary
        const domainType = document.querySelector('.domain-type-radio:checked');
        let domainPrice = 0;
        let domainUrl = 'yourname.doctorsprofile.xyz';

        if (domainType) {
            document.getElementById('summaryDomainType').textContent = domainType.value.charAt(0).toUpperCase() + domainType.value.slice(1);

            if (domainType.value === 'subdomain') {
                const subdomain = document.getElementById('subdomain_name')?.value || 'yourname';
                domainUrl = `${subdomain}.{{ config('app.domain', 'doctorsprofile.xyz') }}`;
                domainPrice = 0;
                // Set the selected subdomain in hidden field
                document.getElementById('selectedSubdomain').value = subdomain;
            } else if (domainType.value === 'new') {
                const domainName = document.getElementById('new_domain_name')?.value || 'yourclinic';
                const extension = document.getElementById('new_domain_extension')?.value || '.com';
                domainUrl = `${domainName}${extension}`;
                domainPrice = 14.99;
            } else if (domainType.value === 'existing') {
                const existingDomain = document.getElementById('existing_domain')?.value || 'yourdomain.com';
                domainUrl = existingDomain;
                domainPrice = 0;
            }
        }

        document.getElementById('summaryDomainUrl').textContent = domainUrl;
        document.getElementById('summaryDomainFee').textContent = `$${domainPrice.toFixed(2)}`;

        // Account Information
        const email = document.getElementById('email')?.value || 'Not provided';
        const phone = document.getElementById('phone')?.value || 'Not provided';
        document.getElementById('summaryEmail').textContent = email;
        document.getElementById('summaryPhone').textContent = phone;

        // Payment Summary
        const paymentOption = document.querySelector('.payment-option-radio:checked')?.value || 'online';
        document.getElementById('summaryPaymentOption').textContent = paymentOption.charAt(0).toUpperCase() + paymentOption.slice(1);

        let paymentMethod = 'paypal';
        if (paymentOption === 'online') {
            paymentMethod = document.querySelector('.payment-method-radio:checked')?.value || 'paypal';
        } else {
            paymentMethod = 'bank_transfer';
        }

        document.getElementById('summaryPaymentMethod').textContent = paymentMethod.split('_').map(word =>
            word.charAt(0).toUpperCase() + word.slice(1)
        ).join(' ');

        // Calculate total
        const subtotal = packagePrice + domainPrice;
        const discount = couponDiscount;
        const total = Math.max(0, subtotal - discount);

        document.getElementById('summaryDiscount').textContent = `-$${discount.toFixed(2)}`;
        document.getElementById('summaryTotal').textContent = `$${total.toFixed(2)}`;

        // Update ALL hidden fields
        document.getElementById('finalPackagePrice').value = packagePrice;
        document.getElementById('finalDomainPrice').value = domainPrice;
        document.getElementById('finalDiscount').value = discount;
        document.getElementById('finalTotal').value = total;

        console.log('Updated hidden fields:', {
            package_price: packagePrice,
            domain_price: domainPrice,
            discount_amount: discount,
            total_amount: total,
            billing_cycle: billingCycle
        });

        // Update step completion status based on filled fields
        updateStepCompletionStatus();
    }

    function updateStepCompletionStatus() {
        // Step 1: Package selected
        const packageSelected = document.querySelector('.package-radio:checked');
        stepCompletion[1] = !!packageSelected;

        // Step 2: Domain type selected and required fields filled
        const domainType = document.querySelector('.domain-type-radio:checked');
        if (domainType) {
            if (domainType.value === 'subdomain') {
                const subdomain = document.getElementById('subdomain_name')?.value.trim();
                stepCompletion[2] = !!subdomain;
            } else if (domainType.value === 'new') {
                const domainName = document.getElementById('new_domain_name')?.value.trim();
                stepCompletion[2] = !!domainName;
            } else if (domainType.value === 'existing') {
                const existingDomain = document.getElementById('existing_domain')?.value.trim();
                stepCompletion[2] = !!existingDomain;
            }
        }

        // Step 3: Account details filled
        const email = document.getElementById('email')?.value.trim();
        const phone = document.getElementById('phone')?.value.trim();
        const password = document.getElementById('password')?.value;
        const confirmPassword = document.getElementById('password_confirmation')?.value;
        stepCompletion[3] = !!email && !!phone && !!password && !!confirmPassword && password === confirmPassword;

        // Step 4: Terms accepted
        const terms = document.getElementById('terms')?.checked;
        stepCompletion[4] = !!terms;

        console.log('Step completion status:', stepCompletion);
    }

    // ========== FORM SUBMISSION ==========
    document.getElementById('doctorRegistrationForm')?.addEventListener('submit', function(e) {
        console.log('Form submission initiated...');

        // Check if all steps are completed
        const allStepsCompleted = Object.values(stepCompletion).every(step => step === true);

        if (!allStepsCompleted) {
            e.preventDefault();

            // Find the first incomplete step
            let incompleteStep = 1;
            for (let i = 1; i <= 4; i++) {
                if (!stepCompletion[i]) {
                    incompleteStep = i;
                    break;
                }
            }

            // Show that step and alert user
            hideAllSteps();
            showStep(incompleteStep);
            currentStep = incompleteStep;
            updateProgress();

            alert(`⚠️ Please complete Step ${incompleteStep} before submitting.`);
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return false;
        }

        // Force update of all hidden fields before submission
        updateSummary();

        // Show loading state
        const submitBtn = document.getElementById('completeRegistrationBtn');
        if (submitBtn) {
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
            submitBtn.disabled = true;
        }

        console.log('All steps completed, submitting form...');
        return true;
    });

    // ========== UTILITY FUNCTIONS ==========
    function previewImage(input) {
        if (!input || !input.files || !input.files[0]) return;

        const preview = document.getElementById('photoPreviewImage');
        const placeholder = document.getElementById('photoPreview');
        const errorDiv = document.getElementById('photoError');

        // Reset error
        if (errorDiv) {
            errorDiv.classList.add('d-none');
            errorDiv.textContent = '';
        }

        const file = input.files[0];

        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            errorDiv.textContent = 'File size must be less than 2MB';
            errorDiv.classList.remove('d-none');
            input.value = '';
            return;
        }

        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            errorDiv.textContent = 'Please upload a valid image file (JPEG, PNG, GIF)';
            errorDiv.classList.remove('d-none');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            if (placeholder) {
                placeholder.classList.add('d-none');
            }
        };
        reader.onerror = function() {
            errorDiv.textContent = 'Error reading file. Please try again.';
            errorDiv.classList.remove('d-none');
            input.value = '';
        };
        reader.readAsDataURL(file);
    }

    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        const button = field.parentNode.querySelector('button');
        const icon = button?.querySelector('i');

        if (field.type === 'password') {
            field.type = 'text';
            if (icon) {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        } else {
            field.type = 'password';
            if (icon) {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    }

    // ========== EVENT LISTENERS ==========
    document.querySelectorAll('.package-radio, .billing-cycle-select').forEach(element => {
        element.addEventListener('change', function() {
            updateSummary();
            stepCompletion[1] = true;
        });
    });

    document.querySelectorAll(
        '.domain-type-radio, #subdomain_name, #new_domain_name, #new_domain_extension, #existing_domain').forEach(
        element => {
            element.addEventListener('input', updateSummary);
            element.addEventListener('change', updateSummary);
        });

    document.querySelectorAll('#email, #phone, #password, #password_confirmation').forEach(element => {
        element.addEventListener('input', updateSummary);
    });

    document.querySelectorAll('.payment-option-radio, .payment-method-radio, #terms').forEach(element => {
        element.addEventListener('change', updateSummary);
    });
</script>
@endpush
