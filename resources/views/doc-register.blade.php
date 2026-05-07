@extends('layouts.sass')
@section('title', 'Find Doctors Nearby')


@section('content')

 <style>
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background-color: #f9fafb;
        }
        .step-content {
            display: none;
        }
        .step-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .domain-option.active {
            background-color: white;
            color: #318069;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .field-error {
            border-color: #ef4444 !important;
        }
        .field-success {
            border-color: #10b981 !important;
        }
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
        .success-message {
            color: #10b981;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
        .input-container {
            position: relative;
        }
        .validation-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            display: none;
        }
        .validation-icon.error {
            color: #ef4444;
        }
        .validation-icon.success {
            color: #10b981;
        }
        .password-strength-meter {
            margin-top: 8px;
            display: none;
        }
        .strength-bars {
            display: flex;
            gap: 4px;
            margin-bottom: 6px;
        }
        .strength-bar {
            height: 4px;
            flex: 1;
            background-color: #e5e7eb;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        .strength-bar.active {
            background-color: currentColor;
        }
        .strength-bar.weak.active { color: #ef4444; background-color: #ef4444; }
        .strength-bar.fair.active { color: #f59e0b; background-color: #f59e0b; }
        .strength-bar.good.active { color: #10b981; background-color: #10b981; }
        .strength-bar.strong.active { color: #059669; background-color: #059669; }
        .strength-text {
            font-size: 0.75rem;
            color: #6b7280;
            display: flex;
            justify-content: space-between;
        }
        .strength-label {
            font-weight: 500;
        }
        .criteria-list {
            margin-top: 8px;
            padding-left: 20px;
            list-style: none;
        }
        .criteria-item {
            font-size: 0.75rem;
            color: #6b7280;
            margin-bottom: 4px;
            position: relative;
        }
        .criteria-item:before {
            content: "✗";
            position: absolute;
            left: -20px;
            color: #d1d5db;
        }
        .criteria-item.valid:before {
            content: "✓";
            color: #10b981;
        }
        .password-requirements {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            margin-top: 12px;
            display: none;
        }
        .password-requirements.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        .payment-option.active {
            background-color: white;
            color: #318069;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .payment-method-card {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: white;
        }
        .payment-method-card:hover {
            border-color: #318069;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(49, 128, 105, 0.1);
        }
        .payment-method-card.selected {
            border-color: #318069;
            background-color: #f0f9f7;
        }
        .ssl-payment-form {
            max-width: 400px;
            margin: 0 auto;
        }
        .ssl-field {
            margin-bottom: 16px;
        }
        .ssl-logo {
            height: 40px;
            object-fit: contain;
        }
        .offline-instructions {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 24px;
        }
        .bank-details {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-top: 16px;
        }
        .transaction-id-input {
            max-width: 300px;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>


  <!-- Main Content Area -->
    <div id="main-content" class="flex-1 pt-28 pb-12">
        
        <!-- Step 1: Domain Setup -->
        <div id="step1" class="step-content active">
            <div class="max-w-6xl mx-auto px-6">
                <div>
                    <div class="text-center mb-12">
                        <div class="inline-flex items-center gap-2 bg-[#318069]/10 rounded-full px-5 py-2 mb-4">
                            <i class="ri-global-line text-[#318069] w-5 h-5 flex items-center justify-center"></i>
                            <span class="text-sm font-semibold text-[#318069]">Step 1 of 4</span>
                        </div>
                        <h1 class="text-4xl md:text-4xl font-bold text-gray-900 mb-2">Setup Your Domain</h1>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Choose how you want to set up your professional profile domain</p>
                    </div>
                    
                    <!-- Domain Options Tabs -->
                    <div class="max-w-3xl mx-auto mb-8">
                        <div class="flex gap-2 bg-gray-100 p-2 rounded-xl">
                            <button id="free-domain-tab" class="domain-option active flex-1 py-3 px-4 rounded-lg font-semibold transition-all whitespace-nowrap" onclick="switchDomainTab('free')">
                                <i class="ri-link text-lg w-5 h-5 inline-flex items-center justify-center mr-2"></i>Free Subdomain
                            </button>
                            <button id="register-domain-tab" class="domain-option flex-1 py-3 px-4 rounded-lg font-semibold transition-all whitespace-nowrap" onclick="switchDomainTab('register')">
                                <i class="ri-add-circle-line text-lg w-5 h-5 inline-flex items-center justify-center mr-2"></i>Register New Domain
                            </button>
                            <button id="existing-domain-tab" class="domain-option flex-1 py-3 px-4 rounded-lg font-semibold transition-all whitespace-nowrap" onclick="switchDomainTab('existing')">
                                <i class="ri-external-link-line text-lg w-5 h-5 inline-flex items-center justify-center mr-2"></i>Use Existing Domain
                            </button>
                        </div>
                    </div>
                    
                    <!-- Free Subdomain Content -->
                    <div id="free-domain-content" class="domain-content">
                        <div class="max-w-3xl mx-auto">
                            <div class="bg-white border-2 border-gray-200 rounded-2xl p-8 shadow-lg">
                                <div>
                                    <div class="text-center mb-8">
                                        <div class="w-20 h-20 bg-[#318069]/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="ri-link text-4xl text-[#318069] w-10 h-10 flex items-center justify-center"></i>
                                        </div>
                                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Choose Your Subdomain</h2>
                                        <p class="text-gray-600">Get started instantly with a free subdomain</p>
                                    </div>
                                    <div class="space-y-6">
                                        <div class="form-group">
                                            <label class="block text-sm font-semibold text-gray-900 mb-2">Your Subdomain</label>
                                            <div class="flex items-center gap-2">
                                                <input id="subdomain-input" placeholder="yourname" class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base" type="text" value="" autocomplete="off" oninput="validateSubdomain()">
                                                <span class="text-gray-600 font-medium">.doctordirectory.com</span>
                                            </div>
                                            <div id="subdomain-error" class="error-message">Please enter a valid subdomain (3-63 characters, letters, numbers, and hyphens only)</div>
                                            <p id="subdomain-preview-container" class="mt-2 text-sm text-gray-600 hidden">
                                                Your profile will be available at: 
                                                <span id="subdomain-preview" class="font-semibold text-[#318069]"></span>
                                            </p>
                                        </div>
                                        <div class="bg-[#318069]/5 border border-[#318069]/20 rounded-xl p-4">
                                            <div class="flex items-start gap-3">
                                                <i class="ri-information-line text-[#318069] text-xl w-5 h-5 flex items-center justify-center mt-0.5"></i>
                                                <div class="text-sm text-gray-700">
                                                    <p class="font-semibold mb-1">Free subdomain includes:</p>
                                                    <ul class="space-y-1 ml-4 list-disc">
                                                        <li>Instant setup - no waiting</li>
                                                        <li>SSL certificate included</li>
                                                        <li>Can upgrade to custom domain later</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-4 mt-8 pt-6 border-t-2 border-gray-200">
                                    <button onclick="window.history.back()" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all whitespace-nowrap">
                                        <i class="ri-arrow-left-line w-5 h-5 inline-flex items-center justify-center mr-2"></i>Back
                                    </button>
                                    <button id="free-domain-continue" onclick="goToStep(2)" class="flex-1 bg-gray-300 text-white px-6 py-3 rounded-lg font-semibold transition-all whitespace-nowrap cursor-not-allowed" disabled>
                                        Continue to Registration
                                        <i class="ri-arrow-right-line w-5 h-5 inline-flex items-center justify-center ml-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Register New Domain Content -->
                    <div id="register-domain-content" class="domain-content hidden">
                        <div class="max-w-3xl mx-auto">
                            <div class="bg-white border-2 border-gray-200 rounded-2xl p-8 shadow-lg">
                                <div>
                                    <div class="text-center mb-8">
                                        <div class="w-20 h-20 bg-[#318069]/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="ri-search-line text-4xl text-[#318069] w-10 h-10 flex items-center justify-center"></i>
                                        </div>
                                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Domain Availability Check</h2>
                                        <p class="text-gray-600">Search for your perfect domain name</p>
                                    </div>
                                    <div class="space-y-6">
                                        <div class="form-group">
                                            <label class="block text-sm font-semibold text-gray-900 mb-2">Domain Name</label>
                                            <div class="flex gap-3">
                                                <input id="domain-search-input" placeholder="yourdomain.com" class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base" type="text" value="" autocomplete="off" oninput="validateDomainFormat()">
                                                <button onclick="checkDomainAvailability()" class="bg-[#318069] hover:bg-[#276854] text-white px-6 py-3 rounded-lg font-semibold transition-all whitespace-nowrap">
                                                    Check Availability
                                                </button>
                                            </div>
                                            <div id="domain-format-error" class="error-message">Please enter a valid domain name (e.g., example.com)</div>
                                        </div>
                                        <div id="domain-availability-result" class="hidden"></div>
                                    </div>
                                </div>
                                <div class="flex gap-4 mt-8 pt-6 border-t-2 border-gray-200">
                                    <button onclick="window.history.back()" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all whitespace-nowrap">
                                        <i class="ri-arrow-left-line w-5 h-5 inline-flex items-center justify-center mr-2"></i>Back
                                    </button>
                                    <button id="register-domain-continue" onclick="goToStep(2)" class="flex-1 bg-gray-300 text-white px-6 py-3 rounded-lg font-semibold transition-all whitespace-nowrap" disabled>
                                        Continue to Registration
                                        <i class="ri-arrow-right-line w-5 h-5 inline-flex items-center justify-center ml-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Existing Domain Content -->
                    <div id="existing-domain-content" class="domain-content hidden">
                        <div class="max-w-3xl mx-auto">
                            <div class="bg-white border-2 border-gray-200 rounded-2xl p-8 shadow-lg">
                                <div>
                                    <div class="text-center mb-8">
                                        <div class="w-20 h-20 bg-[#318069]/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="ri-shield-check-line text-4xl text-[#318069] w-10 h-10 flex items-center justify-center"></i>
                                        </div>
                                        <h2 class="text-2xl font-bold text-gray-900 mb-2">DNS Verification</h2>
                                        <p class="text-gray-600">Connect your existing domain</p>
                                    </div>
                                    <div class="space-y-6">
                                        <div class="form-group">
                                            <label class="block text-sm font-semibold text-gray-900 mb-2">Your Domain</label>
                                            <input id="existing-domain-input" placeholder="yourdomain.com" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base" type="text" value="" autocomplete="off" oninput="validateExistingDomain()">
                                            <div id="existing-domain-error" class="error-message">Please enter a valid domain name</div>
                                        </div>
                                        <div id="dns-status" class="border-2 rounded-xl p-6 bg-amber-50 border-amber-200">
                                            <div class="flex items-start gap-3 mb-4">
                                                <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                    <i class="ri-time-line text-amber-600 text-xl w-5 h-5 flex items-center justify-center"></i>
                                                </div>
                                                <div>
                                                    <h3 class="font-bold text-amber-900 mb-1">Waiting for DNS Verification</h3>
                                                    <p class="text-sm text-amber-700">Please add the following DNS records to your domain provider</p>
                                                </div>
                                            </div>
                                            <div class="bg-white rounded-lg p-4 space-y-3">
                                                <div class="grid grid-cols-4 gap-3 text-xs font-semibold text-gray-600 pb-2 border-b">
                                                    <div>Type</div>
                                                    <div>Name</div>
                                                    <div class="col-span-2">Value</div>
                                                </div>
                                                <div class="grid grid-cols-4 gap-3 text-sm">
                                                    <div class="font-mono bg-gray-50 px-2 py-1 rounded">A</div>
                                                    <div class="font-mono bg-gray-50 px-2 py-1 rounded">@</div>
                                                    <div class="col-span-2 font-mono bg-gray-50 px-2 py-1 rounded">192.168.1.1</div>
                                                </div>
                                                <div class="grid grid-cols-4 gap-3 text-sm">
                                                    <div class="font-mono bg-gray-50 px-2 py-1 rounded">CNAME</div>
                                                    <div class="font-mono bg-gray-50 px-2 py-1 rounded">www</div>
                                                    <div class="col-span-2 font-mono bg-gray-50 px-2 py-1 rounded">doctordirectory.com</div>
                                                </div>
                                            </div>
                                            <div class="mt-4 flex items-center justify-between">
                                                <span class="text-sm text-amber-700">DNS propagation may take up to 48 hours</span>
                                                <button onclick="verifyDNS()" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all whitespace-nowrap">
                                                    Verify Now
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-4 mt-8 pt-6 border-t-2 border-gray-200">
                                    <button onclick="window.history.back()" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all whitespace-nowrap">
                                        <i class="ri-arrow-left-line w-5 h-5 inline-flex items-center justify-center mr-2"></i>Back
                                    </button>
                                    <button id="existing-domain-continue" onclick="goToStep(2)" class="flex-1 bg-gray-300 text-white px-6 py-3 rounded-lg font-semibold transition-all whitespace-nowrap" disabled>
                                        Continue to Registration
                                        <i class="ri-arrow-right-line w-5 h-5 inline-flex items-center justify-center ml-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Step 2: Registration -->
        <div id="step2" class="step-content">
            <div class="max-w-6xl mx-auto px-6">
                <div>
                    <div class="text-center mb-12">
                        <div class="inline-flex items-center gap-2 bg-[#318069]/10 rounded-full px-5 py-2 mb-4">
                            <i class="ri-user-add-line text-[#318069] w-5 h-5 flex items-center justify-center"></i>
                            <span class="text-sm font-semibold text-[#318069]">Step 2 of 4</span>
                        </div>
                        <h1 class="text-4xl md:text-4xl font-bold text-gray-900 mb-4">Super Admin Registration</h1>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Create your main administrator account to manage your profile</p>
                    </div>
                    
                    <div class="max-w-3xl mx-auto mb-8">
                        <div class="bg-gradient-to-r from-[#318069]/10 to-[#FFC107]/10 border-2 border-[#318069]/20 rounded-xl p-6">
                            <div class="flex items-center justify-between flex-wrap gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Selected Package</p>
                                    <p class="text-xl font-bold text-gray-900 capitalize">standard</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Selected Domain</p>
                                    <p id="selected-domain" class="text-xl font-bold text-[#318069]">yourname.doctordirectory.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="max-w-3xl mx-auto">
                        <form id="registration-form" class="bg-white border-2 border-gray-200 rounded-2xl p-8 shadow-lg" onsubmit="event.preventDefault(); submitRegistration();">
                            <div class="space-y-6">
                                <!-- Full Name Field -->
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Full Name <span class="text-red-500">*</span></label>
                                    <div class="input-container">
                                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                            <i class="ri-user-line text-gray-400 text-xl w-5 h-5 flex items-center justify-center"></i>
                                        </div>
                                        <input id="full-name" placeholder="Dr. John Smith" class="w-full pl-12 pr-10 py-3 border-2 rounded-lg focus:outline-none text-base border-gray-300 focus:border-[#318069]" type="text" value="" oninput="validateFullName()">
                                        <div id="full-name-icon" class="validation-icon"></div>
                                    </div>
                                    <div id="full-name-error" class="error-message">Please enter your full name (minimum 2 characters)</div>
                                    <div id="full-name-success" class="success-message">✓ Valid name</div>
                                </div>
                                
                                <!-- Email Field -->
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Email Address <span class="text-red-500">*</span></label>
                                    <div class="input-container">
                                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                            <i class="ri-mail-line text-gray-400 text-xl w-5 h-5 flex items-center justify-center"></i>
                                        </div>
                                        <input id="email" placeholder="doctor@example.com" class="w-full pl-12 pr-10 py-3 border-2 rounded-lg focus:outline-none text-base border-gray-300 focus:border-[#318069]" type="email" value="" oninput="validateEmail()">
                                        <div id="email-icon" class="validation-icon"></div>
                                    </div>
                                    <div id="email-error" class="error-message">Please enter a valid email address</div>
                                    <div id="email-success" class="success-message">✓ Valid email</div>
                                </div>
                                
                                <!-- Phone Field -->
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Phone Number <span class="text-red-500">*</span></label>
                                    <div class="input-container">
                                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                            <i class="ri-phone-line text-gray-400 text-xl w-5 h-5 flex items-center justify-center"></i>
                                        </div>
                                        <input id="phone" placeholder="+1 (555) 123-4567" class="w-full pl-12 pr-10 py-3 border-2 rounded-lg focus:outline-none text-base border-gray-300 focus:border-[#318069]" type="tel" value="" oninput="validatePhone()">
                                        <div id="phone-icon" class="validation-icon"></div>
                                    </div>
                                    <div id="phone-error" class="error-message">Please enter a valid phone number (minimum 10 digits)</div>
                                    <div id="phone-success" class="success-message">✓ Valid phone number</div>
                                </div>
                                
                                <!-- Password Field -->
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Password <span class="text-red-500">*</span></label>
                                    <div class="input-container">
                                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                            <i class="ri-lock-line text-gray-400 text-xl w-5 h-5 flex items-center justify-center"></i>
                                        </div>
                                        <input id="password" placeholder="••••••••" class="w-full pl-12 pr-10 py-3 border-2 rounded-lg focus:outline-none text-base border-gray-300 focus:border-[#318069]" type="password" value="" oninput="validatePassword()" onfocus="showPasswordRequirements()" onblur="hidePasswordRequirements()">
                                        <button type="button" onclick="togglePassword('password')" class="absolute right-10 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer">
                                            <i class="ri-eye-line text-xl w-5 h-5 flex items-center justify-center"></i>
                                        </button>
                                        <div id="password-icon" class="validation-icon"></div>
                                    </div>
                                    <div id="password-error" class="error-message">Password must be at least 8 characters with letters and numbers</div>
                                    <div id="password-success" class="success-message">✓ Strong password</div>
                                    
                                    <!-- Enhanced Password Strength Meter -->
                                    <div id="password-strength-meter" class="password-strength-meter">
                                        <div class="strength-bars">
                                            <div class="strength-bar weak" id="strength-bar-1"></div>
                                            <div class="strength-bar fair" id="strength-bar-2"></div>
                                            <div class="strength-bar good" id="strength-bar-3"></div>
                                            <div class="strength-bar strong" id="strength-bar-4"></div>
                                        </div>
                                        <div class="strength-text">
                                            <span class="strength-label" id="strength-label">Password strength</span>
                                            <span id="strength-percentage">0%</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Password Requirements -->
                                    <div id="password-requirements" class="password-requirements">
                                        <p class="text-sm font-semibold text-gray-700 mb-2">Password must contain:</p>
                                        <ul class="criteria-list">
                                            <li class="criteria-item" id="criteria-length">At least 8 characters</li>
                                            <li class="criteria-item" id="criteria-lowercase">One lowercase letter</li>
                                            <li class="criteria-item" id="criteria-uppercase">One uppercase letter</li>
                                            <li class="criteria-item" id="criteria-number">One number</li>
                                            <li class="criteria-item" id="criteria-special">One special character (!@#$%^&*)</li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <!-- Confirm Password Field -->
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                                    <div class="input-container">
                                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                            <i class="ri-lock-line text-gray-400 text-xl w-5 h-5 flex items-center justify-center"></i>
                                        </div>
                                        <input id="confirm-password" placeholder="••••••••" class="w-full pl-12 pr-10 py-3 border-2 rounded-lg focus:outline-none text-base border-gray-300 focus:border-[#318069]" type="password" value="" oninput="validateConfirmPassword()">
                                        <button type="button" onclick="togglePassword('confirm-password')" class="absolute right-10 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer">
                                            <i class="ri-eye-line text-xl w-5 h-5 flex items-center justify-center"></i>
                                        </button>
                                        <div id="confirm-password-icon" class="validation-icon"></div>
                                    </div>
                                    <div id="confirm-password-error" class="error-message">Passwords do not match</div>
                                    <div id="confirm-password-success" class="success-message">✓ Passwords match</div>
                                </div>
                                
                                <!-- Terms Checkbox -->
                                <div class="bg-gray-50 rounded-lg p-4 form-group">
                                    <label class="flex items-start gap-3 cursor-pointer">
                                        <input id="terms" class="mt-1 w-5 h-5 text-[#318069] border-gray-300 rounded focus:ring-[#318069] cursor-pointer" type="checkbox" onchange="validateTerms()">
                                        <span class="text-sm text-gray-700">
                                            I agree to the <a href="#" class="text-[#318069] font-semibold hover:underline">Terms of Service</a> and 
                                            <a href="#" class="text-[#318069] font-semibold hover:underline">Privacy Policy</a>
                                        </span>
                                    </label>
                                    <div id="terms-error" class="error-message mt-2">You must agree to the terms</div>
                                </div>
                            </div>
                            
                            <div class="flex gap-4 mt-8 pt-6 border-t-2 border-gray-200">
                                <button type="button" onclick="goToStep(1)" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all whitespace-nowrap">
                                    <i class="ri-arrow-left-line w-5 h-5 inline-flex items-center justify-center mr-2"></i>Back
                                </button>
                                <button id="complete-registration-btn" type="button" onclick="validateAndSubmitForm()" class="flex-1 bg-gray-300 text-white px-6 py-3 rounded-lg font-semibold transition-all whitespace-nowrap cursor-not-allowed" disabled>
                                    Continue to Payment  
                                    <i class="ri-arrow-right-line w-5 h-5 inline-flex items-center justify-center ml-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Step 3: Payment -->
        <div id="step3" class="step-content">
            <div class="max-w-6xl mx-auto px-6">
                <div>
                    <div class="text-center mb-12">
                        <div class="inline-flex items-center gap-2 bg-[#318069]/10 rounded-full px-5 py-2 mb-4">
                            <i class="ri-bank-card-line text-[#318069] w-5 h-5 flex items-center justify-center"></i>
                            <span class="text-sm font-semibold text-[#318069]">Step 3 of 4</span>
                        </div>
                        <h1 class="text-4xl md:text-4xl font-bold text-gray-900 mb-2">Complete Your Payment</h1>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Choose your preferred payment method to activate your account</p>
                    </div>
                    
                    <div class="max-w-5xl mx-auto mb-8">
                        <div class="bg-gradient-to-r from-[#318069]/10 to-[#FFC107]/10 border-2 border-[#318069]/20 rounded-xl p-6">
                            <div class="flex items-center justify-between flex-wrap gap-6">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Order Summary</p>
                                    <p class="text-xl font-bold text-gray-900">Standard Package</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Billing Cycle</p>
                                    <p class="text-lg font-bold text-gray-900">Monthly Subscription</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600 mb-1">Total Amount Due</p>
                                    <p class="text-3xl font-bold text-[#318069]">$49.00</p>
                                    <p class="text-sm text-gray-600 mt-1">+ applicable taxes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                  
                    <!-- Online Payment Content -->
                    <div id="online-payment-content" class="payment-content">
                        <div class="max-w-3xl mx-auto">
                            <div class="bg-white border-2 border-gray-200 rounded-2xl p-8 shadow-lg">
                                <!-- Two Payment Cards -->
                                    <div class="max-w-2xl mx-auto">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                            <!-- Online Payment Button -->
                                            <button onclick="selectPayment('online')" class="payment-method-card hover:scale-[1.02] transition-transform duration-200">
                                                <div class="flex flex-col items-center text-center p-4">
                                                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-6">
                                                        <i class="ri-shield-check-line text-4xl text-green-600"></i>
                                                    </div>
                                                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Online Payment</h3>
                                                    <p class="text-gray-600">Pay with card or digital wallet</p>
                                                </div>
                                            </button>
                                            
                                            <!-- Offline Payment Button -->
                                            <button onclick="selectPayment('offline')" class="payment-method-card hover:scale-[1.02] transition-transform duration-200">
                                                <div class="flex flex-col items-center text-center p-4">
                                                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                                                        <i class="ri-bank-line text-4xl text-blue-600"></i>
                                                    </div>
                                                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Offline Payment</h3>
                                                    <p class="text-gray-600">Bank transfer or cash deposit</p>
                                                </div>
                                            </button>
                                        </div>
                                    </div> 
                                
                                <div class="flex gap-4 mt-8 pt-6 border-t-2 border-gray-200">
                                    <button type="button" onclick="goToStep(2)" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all whitespace-nowrap">
                                        <i class="ri-arrow-left-line w-5 h-5 inline-flex items-center justify-center mr-2"></i>Back
                                    </button>
                                    <button  type="button" id="continue-btn"  class="flex-1 bg-[#318069] hover:bg-[#276854] text-white px-6 py-3 rounded-lg font-semibold transition-all whitespace-nowrap shadow-lg hover:shadow-xl" disabled>
                                        <i class="ri-lock-line w-5 h-5 inline-flex items-center justify-center mr-2"></i>
                                        Pay $49.00 Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>


        <!-- Step 4: Success -->
        <div id="step4" class="step-content">
            <div class="max-w-6xl mx-auto px-6">
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white border-2 border-gray-200 rounded-2xl p-12 shadow-lg text-center">
                        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="ri-mail-check-line text-5xl text-green-600 w-12 h-12 flex items-center justify-center"></i>
                        </div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-4">Thank You for Registering!</h1>
                        <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                            We've sent a verification email to your inbox.<br>
                            Please verify your email to continue and log in to your account.
                        </p>
                        
                        <div class="bg-[#318069]/5 border border-[#318069]/20 rounded-xl p-6 mb-8 text-left">
                            <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                                <i class="ri-information-line text-[#318069] w-5 h-5 flex items-center justify-center"></i>
                                Next Steps:
                            </h3>
                            <ol class="space-y-2 text-sm text-gray-700 ml-7 list-decimal">
                                <li>Check your email inbox (and spam folder)</li>
                                <li>Click the verification link in the email</li>
                                <li>Return to this page and log in</li>
                                <li>Complete your doctor profile setup</li>
                            </ol>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <button onclick="window.location.href = '/login'" class="bg-[#318069] hover:bg-[#276854] text-white px-8 py-4 rounded-xl font-bold transition-all hover:shadow-xl whitespace-nowrap shadow-lg">
                                Go to Login Page
                                <i class="ri-arrow-right-line w-5 h-5 inline-flex items-center justify-center ml-2"></i>
                            </button>
                            <button onclick="resendVerificationEmail()" class="bg-white hover:bg-gray-50 text-[#318069] border-2 border-[#318069] px-8 py-4 rounded-xl font-bold transition-all hover:shadow-lg whitespace-nowrap">
                                <i class="ri-mail-send-line w-5 h-5 inline-flex items-center justify-center mr-2"></i>
                                Resend Email
                            </button>
                        </div>
                        
                        <p class="text-sm text-gray-500 mt-8">
                            Didn't receive the email? Check your spam folder or<br>
                            <a href="#" class="text-[#318069] font-semibold hover:underline">contact our support team</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global state
        const state = {
            currentStep: 1,
            domainOption: 'free',
            subdomain: '',
            domainVerified: false,
            registrationData: {},
            formValid: false,
            fieldStates: {
                'full-name': { valid: false, touched: false },
                'email': { valid: false, touched: false },
                'phone': { valid: false, touched: false },
                'password': { valid: false, touched: false },
                'confirm-password': { valid: false, touched: false },
                'terms': { valid: false, touched: false }
            }
        };

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            validateSubdomain();
        });

        // Step Navigation
        function goToStep(step) {
            if (step < 1 || step > 3) return;
            
            // Update domain preview if going to step 2
            if (step === 2) {
                updateSelectedDomain();
            }
            
            // Hide all steps
            document.querySelectorAll('.step-content').forEach(el => {
                el.classList.remove('active');
            });
            
            // Show selected step
            document.getElementById(`step${step}`).classList.add('active');
            
            // Update state
            state.currentStep = step;
        }

        // Domain Tab Switching
        function switchDomainTab(option) {
            state.domainOption = option;
            
            // Update tab buttons
            document.querySelectorAll('.domain-option').forEach(btn => {
                btn.classList.remove('active');
            });
            document.getElementById(`${option}-domain-tab`).classList.add('active');
            
            // Show/hide content
            document.querySelectorAll('.domain-content').forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(`${option}-domain-content`).classList.remove('hidden');
            
            // Reset validation
            if (option === 'free') {
                validateSubdomain();
            } else if (option === 'register') {
                validateRegisterDomain();
            } else if (option === 'existing') {
                validateExistingDomain();
            }
        }

        // Validate Subdomain Input
        function validateSubdomain() {
            const input = document.getElementById('subdomain-input');
            const continueBtn = document.getElementById('free-domain-continue');
            const previewContainer = document.getElementById('subdomain-preview-container');
            const preview = document.getElementById('subdomain-preview');
            const errorElement = document.getElementById('subdomain-error');
            
            const value = input.value.trim();
            
            if (value.length === 0) {
                // Empty field - hide preview, disable button, hide error
                previewContainer.classList.add('hidden');
                continueBtn.disabled = true;
                continueBtn.classList.remove('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
                continueBtn.classList.add('bg-gray-300', 'cursor-not-allowed');
                errorElement.style.display = 'none';
                input.classList.remove('field-error', 'field-success');
                return false;
            }
            
            // Validate subdomain format
            const subdomainRegex = /^[a-z0-9]([a-z0-9-]*[a-z0-9])?$/i;
            const isValid = subdomainRegex.test(value) && value.length >= 3 && value.length <= 63;
            
            if (isValid) {
                // Valid subdomain
                state.subdomain = value;
                preview.textContent = `${state.subdomain}.doctordirectory.com`;
                previewContainer.classList.remove('hidden');
                
                continueBtn.disabled = false;
                continueBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
                continueBtn.classList.add('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
                
                errorElement.style.display = 'none';
                input.classList.remove('field-error');
                input.classList.add('field-success');
                return true;
            } else {
                // Invalid subdomain
                previewContainer.classList.add('hidden');
                
                continueBtn.disabled = true;
                continueBtn.classList.remove('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
                continueBtn.classList.add('bg-gray-300', 'cursor-not-allowed');
                
                errorElement.style.display = 'block';
                input.classList.remove('field-success');
                input.classList.add('field-error');
                return false;
            }
        }

        // Validate Domain Format
        function validateDomainFormat() {
            const input = document.getElementById('domain-search-input');
            const errorElement = document.getElementById('domain-format-error');
            const value = input.value.trim();
            
            if (value.length === 0) {
                errorElement.style.display = 'none';
                input.classList.remove('field-error', 'field-success');
                return false;
            }
            
            // Basic domain validation
            const domainRegex = /^(?!:\/\/)([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/;
            const isValid = domainRegex.test(value);
            
            if (!isValid) {
                errorElement.style.display = 'block';
                input.classList.remove('field-success');
                input.classList.add('field-error');
                return false;
            } else {
                errorElement.style.display = 'none';
                input.classList.remove('field-error');
                input.classList.add('field-success');
                return true;
            }
        }

        // Validate Existing Domain
        function validateExistingDomain() {
            const input = document.getElementById('existing-domain-input');
            const errorElement = document.getElementById('existing-domain-error');
            const value = input.value.trim();
            
            if (value.length === 0) {
                errorElement.style.display = 'none';
                input.classList.remove('field-error', 'field-success');
                return false;
            }
            
            // Basic domain validation
            const domainRegex = /^(?!:\/\/)([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/;
            const isValid = domainRegex.test(value);
            
            if (!isValid) {
                errorElement.style.display = 'block';
                input.classList.remove('field-success');
                input.classList.add('field-error');
                return false;
            } else {
                errorElement.style.display = 'none';
                input.classList.remove('field-error');
                input.classList.add('field-success');
                return true;
            }
        }

        // Check Domain Availability
        function checkDomainAvailability() {
            // First validate domain format
            if (!validateDomainFormat()) {
                return;
            }
            
            const input = document.getElementById('domain-search-input');
            const domain = input.value.trim();
            const resultDiv = document.getElementById('domain-availability-result');
            const continueBtn = document.getElementById('register-domain-continue');
            
            // Simulate API call
            showDomainResult('Checking availability...', 'loading');
            
            setTimeout(() => {
                // Mock result - random availability
                const isAvailable = Math.random() > 0.5;
                
                if (isAvailable) {
                    showDomainResult(`
                        <div class="border-2 rounded-xl p-6 bg-green-50 border-green-200">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="ri-check-line text-green-600 text-2xl w-6 h-6 flex items-center justify-center"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg text-green-900">Domain Available!</h3>
                                    <p class="text-sm text-green-700">${domain} is available for registration.</p>
                                </div>
                            </div>
                        </div>
                    `, 'success');
                    state.domainVerified = true;
                    state.selectedDomain = domain;
                    continueBtn.disabled = false;
                    continueBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
                    continueBtn.classList.add('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
                } else {
                    showDomainResult(`
                        <div class="border-2 rounded-xl p-6 bg-red-50 border-red-200">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                    <i class="ri-close-line text-red-600 text-2xl w-6 h-6 flex items-center justify-center"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg text-red-900">Domain Not Available</h3>
                                    <p class="text-sm text-red-700">${domain} is already taken. Try another name.</p>
                                </div>
                            </div>
                        </div>
                    `, 'error');
                    state.domainVerified = false;
                    continueBtn.disabled = true;
                    continueBtn.classList.remove('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
                    continueBtn.classList.add('bg-gray-300', 'cursor-not-allowed');
                }
            }, 1000);
        }

        function showDomainResult(message, type) {
            const resultDiv = document.getElementById('domain-availability-result');
            if (type === 'loading') {
                resultDiv.innerHTML = `
                    <div class="border-2 rounded-xl p-6 bg-blue-50 border-blue-200">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="ri-loader-4-line animate-spin text-blue-600 text-2xl w-6 h-6 flex items-center justify-center"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-blue-900">Checking...</h3>
                                <p class="text-sm text-blue-700">${message}</p>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                resultDiv.innerHTML = message;
            }
            resultDiv.classList.remove('hidden');
        }

        // DNS Verification
        function verifyDNS() {
            // First validate domain format
            if (!validateExistingDomain()) {
                return;
            }
            
            const dnsStatus = document.getElementById('dns-status');
            const continueBtn = document.getElementById('existing-domain-continue');
            const domainInput = document.getElementById('existing-domain-input');
            const domain = domainInput.value.trim();
            
            // Simulate verification
            dnsStatus.innerHTML = `
                <div class="border-2 rounded-xl p-6 bg-blue-50 border-blue-200">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="ri-loader-4-line animate-spin text-blue-600 text-2xl w-6 h-6 flex items-center justify-center"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-blue-900">Verifying DNS...</h3>
                            <p class="text-sm text-blue-700">Please wait while we verify your DNS records.</p>
                        </div>
                    </div>
                </div>
            `;
            
            setTimeout(() => {
                // Mock success
                dnsStatus.innerHTML = `
                    <div class="border-2 rounded-xl p-6 bg-green-50 border-green-200">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="ri-check-line text-green-600 text-2xl w-6 h-6 flex items-center justify-center"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-green-900">DNS Verified Successfully!</h3>
                                <p class="text-sm text-green-700">Your domain is now connected</p>
                            </div>
                        </div>
                    </div>
                `;
                state.domainVerified = true;
                state.selectedDomain = domain;
                continueBtn.disabled = false;
                continueBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
                continueBtn.classList.add('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
            }, 2000);
        }

        // Update Selected Domain for Step 2
        function updateSelectedDomain() {
            let domainDisplay;
            
            if (state.domainOption === 'free' && state.subdomain) {
                domainDisplay = `${state.subdomain}.doctordirectory.com`;
            } else if (state.domainOption === 'register' && state.selectedDomain) {
                domainDisplay = state.selectedDomain;
            } else if (state.domainOption === 'existing' && state.selectedDomain) {
                domainDisplay = state.selectedDomain;
            } else {
                domainDisplay = 'yourname.doctordirectory.com';
            }
            
            document.getElementById('selected-domain').textContent = domainDisplay;
        }

        // Toggle Password Visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('ri-eye-line');
                icon.classList.add('ri-eye-off-line');
            } else {
                field.type = 'password';
                icon.classList.remove('ri-eye-off-line');
                icon.classList.add('ri-eye-line');
            }
            
            // Re-validate password fields after toggle
            if (fieldId === 'password') {
                validatePassword();
                validateConfirmPassword();
            } else if (fieldId === 'confirm-password') {
                validateConfirmPassword();
            }
        }

        // Show password requirements
        function showPasswordRequirements() {
            document.getElementById('password-requirements').classList.add('show');
        }

        // Hide password requirements
        function hidePasswordRequirements() {
            const password = document.getElementById('password').value;
            if (password.length === 0) {
                document.getElementById('password-requirements').classList.remove('show');
            }
        }

        // FORM VALIDATION FUNCTIONS

        // Validate Full Name
        function validateFullName() {
            const input = document.getElementById('full-name');
            const errorElement = document.getElementById('full-name-error');
            const successElement = document.getElementById('full-name-success');
            const icon = document.getElementById('full-name-icon');
            const value = input.value.trim();
            
            state.fieldStates['full-name'].touched = true;
            
            if (value.length === 0) {
                showError(input, errorElement, successElement, icon, 'Please enter your full name');
                state.fieldStates['full-name'].valid = false;
                return false;
            } else if (value.length < 2) {
                showError(input, errorElement, successElement, icon, 'Name must be at least 2 characters');
                state.fieldStates['full-name'].valid = false;
                return false;
            } else {
                showSuccess(input, errorElement, successElement, icon);
                state.fieldStates['full-name'].valid = true;
                validateRegistrationButton();
                return true;
            }
        }

        // Validate Email
        function validateEmail() {
            const input = document.getElementById('email');
            const errorElement = document.getElementById('email-error');
            const successElement = document.getElementById('email-success');
            const icon = document.getElementById('email-icon');
            const value = input.value.trim();
            
            state.fieldStates['email'].touched = true;
            
            if (value.length === 0) {
                showError(input, errorElement, successElement, icon, 'Please enter your email address');
                state.fieldStates['email'].valid = false;
                return false;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                showError(input, errorElement, successElement, icon, 'Please enter a valid email address');
                state.fieldStates['email'].valid = false;
                return false;
            } else {
                showSuccess(input, errorElement, successElement, icon);
                state.fieldStates['email'].valid = true;
                validateRegistrationButton();
                return true;
            }
        }

        // Validate Phone
        function validatePhone() {
            const input = document.getElementById('phone');
            const errorElement = document.getElementById('phone-error');
            const successElement = document.getElementById('phone-success');
            const icon = document.getElementById('phone-icon');
            const value = input.value.trim();
            
            state.fieldStates['phone'].touched = true;
            
            if (value.length === 0) {
                showError(input, errorElement, successElement, icon, 'Please enter your phone number');
                state.fieldStates['phone'].valid = false;
                return false;
            }
            
            // Extract digits for validation
            const digits = value.replace(/\D/g, '');
            if (digits.length < 10) {
                showError(input, errorElement, successElement, icon, 'Phone number must have at least 10 digits');
                state.fieldStates['phone'].valid = false;
                return false;
            } else {
                showSuccess(input, errorElement, successElement, icon);
                state.fieldStates['phone'].valid = true;
                validateRegistrationButton();
                return true;
            }
        }

        // Validate Password with enhanced strength meter
        function validatePassword() {
            const input = document.getElementById('password');
            const errorElement = document.getElementById('password-error');
            const successElement = document.getElementById('password-success');
            const icon = document.getElementById('password-icon');
            const value = input.value;
            
            state.fieldStates['password'].touched = true;
            
            // Show/hide password strength meter
            const strengthMeter = document.getElementById('password-strength-meter');
            if (value.length > 0) {
                strengthMeter.style.display = 'block';
                document.getElementById('password-requirements').classList.add('show');
            } else {
                strengthMeter.style.display = 'none';
            }
            
            if (value.length === 0) {
                showError(input, errorElement, successElement, icon, 'Please enter a password');
                state.fieldStates['password'].valid = false;
                
                // Also validate confirm password
                validateConfirmPassword();
                return false;
            }
            
            // Check password criteria
            const hasMinLength = value.length >= 8;
            const hasLowerCase = /[a-z]/.test(value);
            const hasUpperCase = /[A-Z]/.test(value);
            const hasNumber = /[0-9]/.test(value);
            const hasSpecialChar = /[!@#$%^&*]/.test(value);
            
            // Update criteria indicators
            document.getElementById('criteria-length').classList.toggle('valid', hasMinLength);
            document.getElementById('criteria-lowercase').classList.toggle('valid', hasLowerCase);
            document.getElementById('criteria-uppercase').classList.toggle('valid', hasUpperCase);
            document.getElementById('criteria-number').classList.toggle('valid', hasNumber);
            document.getElementById('criteria-special').classList.toggle('valid', hasSpecialChar);
            
            // Calculate password strength score
            let score = 0;
            if (hasMinLength) score++;
            if (hasLowerCase) score++;
            if (hasUpperCase) score++;
            if (hasNumber) score++;
            if (hasSpecialChar) score++;
            
            // Update strength bars
            const bars = document.querySelectorAll('.strength-bar');
            bars.forEach((bar, index) => {
                bar.classList.remove('active');
                if (index < score) {
                    bar.classList.add('active');
                }
            });
            
            // Update strength label and percentage
            const strengthLabel = document.getElementById('strength-label');
            const strengthPercentage = document.getElementById('strength-percentage');
            const percentage = (score / 5) * 100;
            
            strengthPercentage.textContent = `${percentage}%`;
            
            let strengthText = 'Very Weak';
            let isValid = false;
            
            if (score <= 1) {
                strengthText = 'Very Weak';
                strengthLabel.textContent = strengthText;
                strengthLabel.style.color = '#ef4444';
                showError(input, errorElement, successElement, icon, 'Password is too weak');
                state.fieldStates['password'].valid = false;
            } else if (score === 2) {
                strengthText = 'Weak';
                strengthLabel.textContent = strengthText;
                strengthLabel.style.color = '#f59e0b';
                showError(input, errorElement, successElement, icon, 'Password is weak');
                state.fieldStates['password'].valid = false;
            } else if (score === 3) {
                strengthText = 'Good';
                strengthLabel.textContent = strengthText;
                strengthLabel.style.color = '#10b981';
                showSuccess(input, errorElement, successElement, icon);
                state.fieldStates['password'].valid = true;
                isValid = true;
            } else if (score === 4) {
                strengthText = 'Strong';
                strengthLabel.textContent = strengthText;
                strengthLabel.style.color = '#059669';
                showSuccess(input, errorElement, successElement, icon);
                state.fieldStates['password'].valid = true;
                isValid = true;
            } else if (score === 5) {
                strengthText = 'Very Strong';
                strengthLabel.textContent = strengthText;
                strengthLabel.style.color = '#047857';
                showSuccess(input, errorElement, successElement, icon);
                state.fieldStates['password'].valid = true;
                isValid = true;
            }
            
            // Also validate confirm password
            validateConfirmPassword();
            validateRegistrationButton();
            return isValid;
        }

        // Validate Confirm Password
        function validateConfirmPassword() {
            const input = document.getElementById('confirm-password');
            const errorElement = document.getElementById('confirm-password-error');
            const successElement = document.getElementById('confirm-password-success');
            const icon = document.getElementById('confirm-password-icon');
            const password = document.getElementById('password').value;
            const value = input.value;
            
            state.fieldStates['confirm-password'].touched = true;
            
            if (value.length === 0) {
                showError(input, errorElement, successElement, icon, 'Please confirm your password');
                state.fieldStates['confirm-password'].valid = false;
                validateRegistrationButton();
                return false;
            }
            
            if (value !== password) {
                showError(input, errorElement, successElement, icon, 'Passwords do not match');
                state.fieldStates['confirm-password'].valid = false;
                validateRegistrationButton();
                return false;
            } else {
                showSuccess(input, errorElement, successElement, icon);
                state.fieldStates['confirm-password'].valid = true;
                validateRegistrationButton();
                return true;
            }
        }

        // Validate Terms
        function validateTerms() {
            const input = document.getElementById('terms');
            const errorElement = document.getElementById('terms-error');
            
            state.fieldStates['terms'].touched = true;
            
            if (!input.checked) {
                errorElement.style.display = 'block';
                state.fieldStates['terms'].valid = false;
                validateRegistrationButton();
                return false;
            } else {
                errorElement.style.display = 'none';
                state.fieldStates['terms'].valid = true;
                validateRegistrationButton();
                return true;
            }
        }

        // Show error state
        function showError(input, errorElement, successElement, icon, message) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
            if (successElement) successElement.style.display = 'none';
            input.classList.remove('field-success');
            input.classList.add('field-error');
            
            if (icon) {
                icon.className = 'validation-icon error';
                icon.innerHTML = '<i class="ri-close-line text-xl w-5 h-5 flex items-center justify-center"></i>';
                icon.style.display = 'block';
            }
        }

        // Show success state
        function showSuccess(input, errorElement, successElement, icon) {
            errorElement.style.display = 'none';
            if (successElement) successElement.style.display = 'block';
            input.classList.remove('field-error');
            input.classList.add('field-success');
            
            if (icon) {
                icon.className = 'validation-icon success';
                icon.innerHTML = '<i class="ri-check-line text-xl w-5 h-5 flex items-center justify-center"></i>';
                icon.style.display = 'block';
            }
        }

        // Validate registration button
        function validateRegistrationButton() {
            const allValid = Object.values(state.fieldStates).every(field => field.valid);
            const completeBtn = document.getElementById('complete-registration-btn');
            
            if (allValid) {
                completeBtn.disabled = false;
                completeBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
                completeBtn.classList.add('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
                state.formValid = true;
            } else {
                completeBtn.disabled = true;
                completeBtn.classList.remove('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
                completeBtn.classList.add('bg-gray-300', 'cursor-not-allowed');
                state.formValid = false;
            }
        }

        // Validate and submit form
        function validateAndSubmitForm() {
            // Validate all fields
            const validations = [
                validateFullName(),
                validateEmail(),
                validatePhone(),
                validatePassword(),
                validateConfirmPassword(),
                validateTerms()
            ];
            
            const allValid = validations.every(result => result === true);
            
            if (!allValid) {
                // Mark all fields as touched to show errors
                Object.keys(state.fieldStates).forEach(field => {
                    state.fieldStates[field].touched = true;
                });
                
                // Re-run validations to show errors
                validateFullName();
                validateEmail();
                validatePhone();
                validatePassword();
                validateConfirmPassword();
                validateTerms();
                
                return;
            }
            
            submitRegistration();
        }


           let selectedPaymentMethod = null;

            function selectPayment(method) {
                selectedPaymentMethod = method;
                
                // Remove selected class from all cards
                document.querySelectorAll('.payment-method-card').forEach(card => {
                    card.classList.remove('selected', 'border-[#318069]', 'bg-[#f0f9f7]');
                });
                
                // Add selected class to clicked card
                event.currentTarget.classList.add('selected', 'border-[#318069]', 'bg-[#f0f9f7]');
                
                // Enable continue button
                const continueBtn = document.getElementById('continue-btn');
                continueBtn.disabled = false;
                continueBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
                continueBtn.classList.add('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
                continueBtn.onclick = function() { goToStep(4); };
            }

            // Update the goToStep function to allow step 4
            function goToStep(step) {
                if (step < 1 || step > 4) return; // Change from 3 to 4
                
                // Update domain preview if going to step 2
                if (step === 2) {
                    updateSelectedDomain();
                }
                
                // Hide all steps
                document.querySelectorAll('.step-content').forEach(el => {
                    el.classList.remove('active');
                });
                
                // Show selected step
                document.getElementById(`step${step}`).classList.add('active');
                
                // Update state
                state.currentStep = step;
            }


        // Submit Registration
                function submitRegistration() {
                    if (!state.formValid) {
                        validateAndSubmitForm();
                        return;
                    }
                    
                    const fullName = document.getElementById('full-name').value.trim();
                    const email = document.getElementById('email').value.trim();
                    const phone = document.getElementById('phone').value.trim();
                    
                    // Save registration data
                    state.registrationData = {
                        fullName,
                        email,
                        phone,
                        domain: document.getElementById('selected-domain').textContent
                    };
                    
                    // Show loading
                    const completeBtn = document.getElementById('complete-registration-btn');
                    const originalText = completeBtn.innerHTML;
                    completeBtn.innerHTML = '<i class="ri-loader-4-line animate-spin w-5 h-5 inline-flex items-center justify-center mr-2"></i>Processing...';
                    completeBtn.disabled = true;
                    
                    // Simulate API call
                    setTimeout(() => {
                        goToStep(3); // Change from 3 to 4 (go to payment instead of success)
                        completeBtn.innerHTML = originalText;
                    }, 1500);
                }

        // Resend Verification Email
        function resendVerificationEmail() {
            alert('Verification email has been resent to ' + (state.registrationData.email || 'your email address') + '!');
        }


    </script>


@endsection
