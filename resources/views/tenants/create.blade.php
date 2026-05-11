@extends('layouts.sass')

@section('title', 'Doctor Registration')

@section('content')
    <div class="flex-1 pt-20 sm:pt-24 md:pt-28 pb-8 sm:pb-12 px-4 sm:px-6">
        <div class="max-w-6xl mx-auto">



            <!-- Step 1: Package Selection -->
            <div id="step1" class="step-content active">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center gap-2 bg-[#318069]/10 rounded-full px-4 sm:px-5 py-1.5 sm:py-2 mb-3 sm:mb-4">
                        <i class="ri-box-3-line text-[#318069] text-sm sm:text-base"></i>
                        <span class="text-xs sm:text-sm font-semibold text-[#318069]">Step 1 of 4</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2 px-2">Choose Your Package</h1>
                    <p class="text-sm sm:text-base text-gray-600 max-w-2xl mx-auto px-4">Select the perfect plan for your practice. You can upgrade or downgrade anytime.</p>
                </div>

                <!-- Billing Toggle - Responsive -->
                <div class="flex justify-center mb-8 sm:mb-12 md:mb-16 px-4">
                    <div class="bg-gray-100 rounded-full p-1 inline-flex w-full sm:w-auto">
                        <button type="button" id="billing-toggle-monthly"
                            class="billing-toggle-btn px-4 sm:px-6 py-2 sm:py-2.5 rounded-full font-semibold text-sm sm:text-base text-gray-700 transition-all active w-full sm:w-auto"
                            data-cycle="monthly">
                            Monthly Billing
                        </button>
                        <button type="button" id="billing-toggle-yearly"
                            class="billing-toggle-btn px-4 sm:px-6 py-2 sm:py-2.5 rounded-full font-semibold text-sm sm:text-base text-gray-700 transition-all w-full sm:w-auto"
                            data-cycle="yearly">
                            Yearly Billing <span class="text-xs text-green-600 ml-1 hidden sm:inline">(Save 20%)</span>
                        </button>
                    </div>
                </div>

                <form id="doctorRegistrationForm" method="post" action="{{ route('doctor.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <!-- Hidden Fields -->
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                    <input type="hidden" name="city" id="city">
                    <input type="hidden" id="selectedBillingCycle" name="selected_billing_cycle" value="monthly">
                    <input type="hidden" id="selectedCoupon" name="coupon_id" value="">
                    <input type="hidden" id="finalPackagePrice" name="package_price" value="0">
                    <input type="hidden" id="finalDomainPrice" name="domain_price" value="0">
                    <input type="hidden" id="finalDiscount" name="discount_amount" value="0">
                    <input type="hidden" id="finalTotal" name="total_amount" value="0">
                    <input type="hidden" id="selectedSubdomain" name="subdomain" value="">
                    <input type="hidden" id="selectedPackageId" name="package_id" value="">
                    <input type="hidden" id="isFreePackage" name="is_free_package" value="0">

                    <!-- Package Cards - Fully Responsive Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-10 lg:gap-8 max-w-6xl mx-auto mb-6 sm:mb-8">
                        @foreach ($packages as $index => $package)
                            <div class="relative bg-white rounded-xl sm:rounded-2xl border-2 transition-all hover:shadow-2xl
                        {{ $index == 1 ? 'border-[#318069] shadow-xl scale-105 z-10' : 'border-gray-200 hover:border-[#318069]/50' }}
                        flex flex-col h-full package-card"
                                data-package-id="{{ $package->id }}"
                                data-package-free="{{ $package->price_monthly == 0 ? 'true' : 'false' }}"
                                data-package-price-monthly="{{ $package->price_monthly }}"
                                data-package-price-yearly="{{ $package->price_yearly }}"
                                data-package-features='@json($package->featureMap())'>

                                @if ($index == 1)
                                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 w-full px-4">
                                        <div class="bg-[#FFC107] text-gray-900 px-3 sm:px-6 py-1 sm:py-1.5 rounded-full text-xs sm:text-sm font-bold shadow-lg inline-block mx-auto">
                                            Most Popular
                                        </div>
                                    </div>
                                @endif

                                <div class="p-4 sm:p-6 md:p-8 flex flex-col flex-1 {{ $index == 1 ? 'pt-6 sm:pt-8' : '' }}">
                                    <div class="mb-4 sm:mb-6 md:mb-8">
                                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 text-center mb-3 sm:mb-5">{{ $package->name }}</h3>
                                        <div class="flex items-end justify-center gap-1 flex-wrap">
                                            <span class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 package-price"
                                                data-monthly="{{ $package->price_monthly }}"
                                                data-yearly="{{ $package->price_yearly }}">
                                                <span class="currency-symbol">{{ $pricingContext['currency_symbol'] }}</span>
                                                <span class="price-amount">{{ number_format(round($package->price_monthly * ($pricingContext['exchange_rate'] ?? 1)), 0) }}</span>
                                            </span>
                                            <span class="text-gray-600 mb-1 sm:mb-2 package-period text-sm sm:text-base">/month</span>
                                        </div>
                                        @if ($package->price_monthly > 0)
                                            <div class="mt-2 sm:mt-4 text-center text-xs sm:text-sm text-gray-500">
                                                <i class="ri-checkbox-circle-line text-[#318069] mr-1"></i>
                                                <span class="billing-savings text-xs sm:text-sm"
                                                    data-yearly-savings="{{ $package->price_monthly > 0 ? round((1 - $package->price_yearly / ($package->price_monthly * 12)) * 100) : 0 }}%">
                                                    Save {{ $package->price_monthly > 0 ? round((1 - $package->price_yearly / ($package->price_monthly * 12)) * 100) : 0 }}%
                                                    <span class="hidden sm:inline">with yearly billing</span>
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="space-y-2 sm:space-y-3 mb-4 sm:mb-6 md:mb-8 flex-1">
                                        @php
                                            libxml_use_internal_errors(true);
                                            $dom = new DOMDocument();
                                            $dom->loadHTML(mb_convert_encoding($package->description, 'HTML-ENTITIES', 'UTF-8'));
                                            $lis = $dom->getElementsByTagName('li');
                                            $features = [];
                                            foreach ($lis as $li) {
                                                $features[] = trim($li->textContent);
                                            }
                                        @endphp

                                        @foreach (array_slice($features, 0, 10) as $feature)
                                            <div class="flex items-start gap-2 sm:gap-3">
                                                <div class="w-4 h-4 sm:w-5 sm:h-5 bg-[#318069]/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                                    <i class="ri-check-line text-[#318069] text-xs sm:text-sm"></i>
                                                </div>
                                                <span class="text-xs sm:text-sm text-gray-700 line-clamp-2">
                                                    {{ $feature }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="pt-2 sm:pt-4 mt-auto">
                                        <button type="button" onclick="selectPackage({{ $package->id }})"
                                            class="package-select-btn w-full py-3 sm:py-4 rounded-xl font-bold transition-all whitespace-nowrap text-sm sm:text-base
                                    {{ $index == 1 ? 'bg-[#318069] hover:bg-[#276854] text-white shadow-lg hover:shadow-xl' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                                            <span class="package-btn-text">Select {{ $package->name }}</span>
                                            <i class="ri-arrow-right-line ml-1 sm:ml-2 package-btn-icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('package_id')
                        <div class="max-w-6xl mx-auto mb-6 sm:mb-8 px-4">
                            <div class="border-2 border-red-200 bg-red-50 rounded-xl p-3 sm:p-4">
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <i class="ri-close-circle-line text-red-600 text-lg sm:text-xl"></i>
                                    <span class="text-sm sm:text-base text-red-700">{{ $message }}</span>
                                </div>
                            </div>
                        </div>
                    @enderror
            </div>

            <!-- Step 2: Domain Setup - Fully Responsive -->
            <div id="step2" class="step-content hidden">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-6 ">
                        <div class="inline-flex items-center gap-2 bg-[#318069]/10 rounded-full px-4 sm:px-5 py-1.5 sm:py-2 mb-3 sm:mb-4">
                            <i class="ri-global-line text-[#318069] text-sm sm:text-base"></i>
                            <span class="text-xs sm:text-sm font-semibold text-[#318069]">Step 2 of 4</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2">Setup Your Domain</h1>
                    </div>

                    <!-- Domain Tabs - Responsive -->
                    <div class="max-w-3xl mx-auto mb-6 sm:mb-8">
                        <div class="flex flex-wrap gap-2 bg-gray-100 p-1.5 sm:p-2 rounded-xl">
                            <button type="button" id="free-domain-tab"
                                class="domain-option active flex-1 py-2 sm:py-3 px-2 sm:px-4 rounded-lg font-semibold text-xs sm:text-base transition-all whitespace-nowrap bg-white text-[#318069] shadow-lg"
                                onclick="switchDomainTab('free')">
                                <i class="ri-link text-base sm:text-lg mr-1 sm:mr-2"></i> <span class="hidden sm:inline">Free</span> Subdomain
                            </button>
                            <button type="button" id="register-domain-tab"
                                class="domain-option flex-1 py-2 sm:py-3 px-2 sm:px-4 rounded-lg font-semibold text-xs sm:text-base transition-all whitespace-nowrap text-gray-700"
                                onclick="switchDomainTab('register')">
                                <i class="ri-add-circle-line text-base sm:text-lg mr-1 sm:mr-2"></i>Register New
                            </button>
                            <button type="button" id="existing-domain-tab"
                                class="domain-option flex-1 py-2 sm:py-3 px-2 sm:px-4 rounded-lg font-semibold text-xs sm:text-base transition-all whitespace-nowrap text-gray-700"
                                onclick="switchDomainTab('existing')">
                                <i class="ri-external-link-line text-base sm:text-lg mr-1 sm:mr-2"></i>Use Existing
                            </button>
                        </div>
                    </div>

                    <!-- Free Subdomain Content - Responsive -->
                    <div id="free-domain-content" class="domain-content">
                        <div class="max-w-3xl mx-auto">
                            <div class="bg-white border-2 border-gray-200 rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 shadow-lg">
                                <div>
                                    <div class="text-center mb-4 sm:mb-6 md:mb-8">
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[#318069]/10 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                            <i class="ri-link text-2xl sm:text-4xl text-[#318069]"></i>
                                        </div>
                                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1 sm:mb-2">Choose Your Subdomain</h2>
                                    </div>

                                    <div class="space-y-4 sm:space-y-6">
                                        <div class="form-group">
                                            <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-1 sm:mb-2">Your Subdomain <span class="text-red-500">*</span></label>
                                            <div class="flex flex-col sm:flex-row items-stretch gap-2 sm:gap-3">
                                                <div class="flex-1 relative">
                                                    <input type="text" id="subdomain_name" name="subdomain_name"
                                                        placeholder="yourname"
                                                        class="w-full px-3 sm:px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-sm sm:text-base pr-20 sm:pr-32"
                                                        value="{{ old('subdomain_name') }}" oninput="validateSubdomain()"
                                                        autocomplete="off">
                                                    <div class="absolute right-2 sm:right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                                        <span class="text-xs sm:text-sm text-gray-600 font-medium">.{{ config('app.domain', 'doctorsprofile.xyz') }} </span>
                                                    </div>
                                                </div>
                                                <div class="flex gap-2">
                                                    <button type="button" onclick="suggestSubdomain()"
                                                        class="px-3 sm:px-4 py-2 sm:py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-50 transition-all whitespace-nowrap">
                                                        <i class="ri-lightbulb-line mr-1"></i><span>Suggest</span>
                                                    </button>
                                                    <button type="button" onclick="checkSubdomainAvailability()"
                                                        class="btn btn-success px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold text-sm bg-[#318069] hover:bg-[#276854] text-white transition-all"
                                                        id="checkSubdomainBtn">
                                                        Check
                                                    </button>
                                                </div>
                                            </div>
                                            <div id="subdomain-error" class="error-message mt-1 sm:mt-2 hidden text-xs sm:text-sm">
                                                Please enter a valid subdomain (3-63 characters, letters, numbers, and hyphens only)
                                            </div>
                                            <div id="subdomain-preview-container" class="mt-2 sm:mt-3 p-2 sm:p-3 bg-gray-50 rounded-lg hidden">
                                                <p class="text-xs sm:text-sm text-gray-700 mb-1">Your website will be available at:</p>
                                                <p id="subdomain-preview" class="font-semibold text-[#318069] text-sm sm:text-base break-all"></p>
                                            </div>
                                            <div id="subdomainStatus" class="mt-2 sm:mt-3"></div>
                                        </div>

                                        <div class="bg-[#318069]/5 border border-[#318069]/20 rounded-xl p-3 sm:p-4">
                                            <div class="flex items-start gap-2 sm:gap-3">
                                                <i class="ri-information-line text-[#318069] text-lg sm:text-xl flex-shrink-0 mt-0.5"></i>
                                                <div class="text-xs sm:text-sm text-gray-700">
                                                    <p class="font-semibold mb-1">Free subdomain includes:</p>
                                                    <ul class="space-y-1 ml-4 list-disc">
                                                        <li>Instant setup - no waiting</li>
                                                        <li>SSL certificate included</li>
                                                        <li class="hidden sm:list-item">Can upgrade to custom domain later</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step Navigation - Responsive -->
                                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t-2 border-gray-200">
                                    <button type="button" onclick="prevStep(1)"
                                        class="px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold text-sm sm:text-base hover:bg-gray-50 transition-all whitespace-nowrap order-2 sm:order-1">
                                        <i class="ri-arrow-left-line mr-1 sm:mr-2"></i>Back
                                    </button>
                                    <button type="button" onclick="nextStep(3)" id="step2-continue"
                                        class="flex-1 bg-gray-300 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition-all cursor-not-allowed order-1 sm:order-2"
                                        disabled>
                                        Continue to Account
                                        <i class="ri-arrow-right-line ml-1 sm:ml-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- New Domain Content - Responsive -->
                    <div id="register-domain-content" class="domain-content hidden">
                        <div class="max-w-3xl mx-auto">
                            <div class="bg-white border-2 border-gray-200 rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 shadow-lg">
                                <div>
                                    <div class="text-center mb-4 sm:mb-6 md:mb-8">
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[#318069]/10 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                            <i class="ri-search-line text-2xl sm:text-4xl text-[#318069]"></i>
                                        </div>
                                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1 sm:mb-2">Domain Availability Check</h2>
                                    </div>

                                    <div class="space-y-4 sm:space-y-6">
                                        <div class="form-group">
                                            <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-1 sm:mb-2">Domain Name <span class="text-red-500">*</span></label>
                                            <div class="flex flex-col gap-3">
                                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                                    <div class="flex-1 relative">
                                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">www.</span>
                                                        <input type="text" id="new_domain_name" name="new_domain_name"
                                                            placeholder="yourclinic"
                                                            class="w-full pl-12 pr-3 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-sm sm:text-base"
                                                            value="{{ old('new_domain_name') }}"
                                                            oninput="validateDomainFormat()" autocomplete="off">
                                                    </div>
                                                    <div class="flex gap-2">
                                                        <select id="new_domain_extension" name="new_domain_extension"
                                                            class="form-select border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-sm sm:text-base px-2 sm:px-4 py-2 sm:py-3">
                                                            <option value=".com">.com</option>
                                                            <option value=".net">.net</option>
                                                            <option value=".org">.org</option>
                                                            <option value=".health">.health</option>
                                                            <option value=".clinic">.clinic</option>
                                                            <option value=".doctor">.doctor</option>
                                                        </select>
                                                        <button type="button" onclick="checkDomainAvailability()"
                                                            class="bg-[#318069] hover:bg-[#276854] text-white px-3 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold text-sm transition-all whitespace-nowrap"
                                                            id="checkDomainBtn">
                                                            Check
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="domain-format-error" class="error-message mt-1 hidden text-xs sm:text-sm">
                                                    Please enter a valid domain name (e.g., example)
                                                </div>
                                                <div id="newDomainStatus" class="mt-2"></div>
                                            </div>
                                        </div>

                                        <div id="domain-suggestions" class="hidden">
                                            <h4 class="font-semibold text-sm sm:text-base text-gray-900 mb-2 sm:mb-3">Alternative Suggestions:</h4>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2" id="suggestions-list">
                                                <!-- Suggestions will be populated here -->
                                            </div>
                                        </div>

                                        <div class="border-2 border-blue-200 bg-blue-50 rounded-xl p-3 sm:p-6">
                                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
                                                <div>
                                                    <h6 class="font-bold text-base sm:text-lg mb-1 flex items-center gap-2">
                                                        <i class="ri-information-line text-blue-600"></i>
                                                        Domain Registration Fee
                                                    </h6>
                                                    <p class="text-xs sm:text-sm text-gray-700">One-time yearly registration includes:</p>
                                                    <ul class="text-xs text-gray-600 mt-2 ml-4 list-disc">
                                                        <li>Domain registration for 1 year</li>
                                                        <li class="hidden sm:list-item">DNS management</li>
                                                        <li class="hidden sm:list-item">WHOIS privacy protection</li>
                                                    </ul>
                                                </div>
                                                <div class="text-right w-full sm:w-auto">
                                                    <h4 class="font-bold text-[#318069] text-xl sm:text-2xl mb-0 domain-registration-fee" id="domain-price-display">
                                                        {{ $pricingContext['currency_symbol'] }}{{ number_format(round((($pricingContext['domain_prices_usd']['.com'] ?? 14.99) * ($pricingContext['exchange_rate'] ?? 1))), 0) }}
                                                    </h4>
                                                    <p class="text-xs text-gray-600">per year</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step Navigation - Responsive -->
                                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t-2 border-gray-200">
                                    <button type="button" onclick="prevStep(1)"
                                        class="px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold text-sm sm:text-base hover:bg-gray-50 transition-all whitespace-nowrap order-2 sm:order-1">
                                        <i class="ri-arrow-left-line mr-1 sm:mr-2"></i>Back
                                    </button>
                                    <button type="button" onclick="nextStep(3)" id="register-domain-continue"
                                        class="flex-1 bg-gray-300 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition-all cursor-not-allowed order-1 sm:order-2"
                                        disabled>
                                        Continue to Account
                                        <i class="ri-arrow-right-line ml-1 sm:ml-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Existing Domain Content - Responsive -->
                    <div id="existing-domain-content" class="domain-content hidden">
                        <div class="max-w-3xl mx-auto ">
                            <div class="bg-white border-2 border-gray-200 rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 shadow-lg">
                                <div>
                                    <div class="text-center mb-4 sm:mb-6 md:mb-8">
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[#318069]/10 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                            <i class="ri-shield-check-line text-2xl sm:text-4xl text-[#318069]"></i>
                                        </div>
                                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1 sm:mb-2">DNS Verification</h2>
                                        <p class="text-sm sm:text-base text-gray-600">Connect your existing domain</p>
                                    </div>

                                    <div class="space-y-4 sm:space-y-6">
                                        <div class="form-group">
                                            <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-1 sm:mb-2">Your Domain <span class="text-red-500">*</span></label>
                                            <div class="relative">
                                                <input type="text" id="existing_domain" name="existing_domain"
                                                    placeholder="yourdomain.com"
                                                    class="w-full px-3 sm:px-4 py-3 sm:py-4 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-sm sm:text-base pr-24 sm:pr-32"
                                                    value="{{ old('existing_domain') }}"
                                                    oninput="validateExistingDomain()" autocomplete="off">
                                                <button type="button" onclick="verifyDNS()"
                                                    class="absolute right-1 sm:right-2 top-1/2 transform -translate-y-1/2 bg-[#318069] hover:bg-[#276854] text-white px-2 sm:px-4 py-1 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold transition-all">
                                                    Verify DNS
                                                </button>
                                            </div>
                                            <div id="existing-domain-error" class="error-message mt-1 hidden text-xs sm:text-sm">
                                                Please enter a valid domain name
                                            </div>
                                            <div class="form-text mt-1 sm:mt-2 text-xs sm:text-sm text-gray-600">
                                                <i class="ri-information-line mr-1"></i>
                                                After registration, you'll receive instructions to update your DNS settings.
                                            </div>
                                        </div>

                                        <div id="dns-status" class="border-2 rounded-xl p-3 sm:p-6 bg-gray-50 border-gray-200">
                                            <div class="flex items-start gap-2 sm:gap-3 mb-3 sm:mb-4">
                                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                    <i class="ri-time-line text-gray-600 text-base sm:text-xl"></i>
                                                </div>
                                                <div>
                                                    <h3 class="font-bold text-sm sm:text-base text-gray-900 mb-1">DNS Configuration Required</h3>
                                                    <p class="text-xs sm:text-sm text-gray-700">Please add the following DNS records</p>
                                                </div>
                                            </div>

                                            <div class="bg-white rounded-lg p-2 sm:p-4 space-y-2 overflow-x-auto">
                                                <div class="grid grid-cols-4 gap-1 sm:gap-3 text-xs font-semibold text-gray-600 pb-2 border-b min-w-[400px] sm:min-w-0">
                                                    <div>Type</div>
                                                    <div>Name</div>
                                                    <div class="col-span-2">Value</div>
                                                </div>
                                                <div class="grid grid-cols-4 gap-1 sm:gap-3 text-xs sm:text-sm min-w-[400px] sm:min-w-0">
                                                    <div class="font-mono bg-gray-50 px-1 sm:px-2 py-1 rounded">A</div>
                                                    <div class="font-mono bg-gray-50 px-1 sm:px-2 py-1 rounded">@</div>
                                                    <div class="col-span-2 font-mono bg-gray-50 px-1 sm:px-2 py-1 rounded break-all">192.168.1.1</div>
                                                </div>
                                                <div class="grid grid-cols-4 gap-1 sm:gap-3 text-xs sm:text-sm min-w-[400px] sm:min-w-0">
                                                    <div class="font-mono bg-gray-50 px-1 sm:px-2 py-1 rounded">CNAME</div>
                                                    <div class="font-mono bg-gray-50 px-1 sm:px-2 py-1 rounded">www</div>
                                                    <div class="col-span-2 font-mono bg-gray-50 px-1 sm:px-2 py-1 rounded break-all">{{ config('app.domain', 'doctorsprofile.xyz') }}</div>
                                                </div>
                                            </div>

                                            <div class="mt-3 sm:mt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                                                <span class="text-xs text-gray-700">DNS propagation may take up to 48 hours</span>
                                                <div id="dns-verification-status" class="text-xs sm:text-sm font-semibold"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step Navigation - Responsive -->
                                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t-2 border-gray-200">
                                    <button type="button" onclick="prevStep(1)"
                                        class="px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold text-sm sm:text-base hover:bg-gray-50 transition-all whitespace-nowrap order-2 sm:order-1">
                                        <i class="ri-arrow-left-line mr-1 sm:mr-2"></i>Back
                                    </button>
                                    <button type="button" onclick="nextStep(3)" id="existing-domain-continue"
                                        class="flex-1 bg-gray-300 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition-all cursor-not-allowed order-1 sm:order-2"
                                        disabled>
                                        Continue to Account
                                        <i class="ri-arrow-right-line ml-1 sm:ml-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="domain_type" id="domain_type" value="free">
                </div>
            </div>

            <!-- Step 3: Account Details - Fully Responsive -->
            <div id="step3" class="step-content hidden">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-6 sm:mb-8 md:mb-12">
                        <div class="inline-flex items-center gap-2 bg-[#318069]/10 rounded-full px-4 sm:px-5 py-1.5 sm:py-2 mb-3 sm:mb-4">
                            <i class="ri-user-add-line text-[#318069] text-sm sm:text-base"></i>
                            <span class="text-xs sm:text-sm font-semibold text-[#318069]">Step 3 of 4</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2">Account Details</h1>
                        <p class="text-sm sm:text-base text-gray-600 max-w-2xl mx-auto px-4">Create your main administrator account to manage your profile</p>
                    </div>

                    <!-- Summary Card - Responsive -->
                    <div class="max-w-3xl mx-auto mb-6 sm:mb-8">
                        <div class="bg-gradient-to-r from-[#318069]/10 to-[#FFC107]/10 border-2 border-[#318069]/20 rounded-xl p-3 sm:p-4 md:p-6">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
                                <div class="w-full sm:w-auto">
                                    <p class="text-xs text-gray-600 mb-1">Selected Package</p>
                                    <p id="selected-package-name" class="text-base sm:text-lg md:text-xl font-bold text-gray-900 capitalize break-words">No Package Selected</p>
                                </div>
                                <div class="w-full sm:w-auto">
                                    <p class="text-xs text-gray-600 mb-1">Selected Domain</p>
                                    <p id="selected-domain" class="text-sm sm:text-base md:text-xl font-bold text-[#318069] break-all">yourname.{{ config('app.domain', 'doctorsprofile.xyz') }}</p>
                                </div>
                                <div class="w-full sm:w-auto text-left sm:text-right">
                                    <p class="text-xs text-gray-600 mb-1">Total Amount</p>
                                    <p id="package-total-amount" class="text-lg sm:text-xl md:text-2xl font-bold text-[#318069]">
                                        {{ $pricingContext['currency_symbol'] }}0
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Form - Responsive -->
                    <div class="max-w-3xl mx-auto">
                        <div class="bg-white border-2 border-gray-200 rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 shadow-lg">
                            <div class="space-y-4 sm:space-y-6">
                                <!-- Full Name -->
                                <div class="form-group">
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-1 sm:mb-2">Full Name <span class="text-red-500">*</span></label>
                                    <div class="input-container relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                            <i class="ri-user-line text-gray-400 text-base sm:text-lg"></i>
                                        </div>
                                        <input type="text" id="name" name="name" required
                                            value="{{ old('name') }}"
                                            class="w-full pl-9 sm:pl-12 pr-8 sm:pr-10 py-2 sm:py-3 border-2 rounded-lg focus:outline-none text-sm sm:text-base border-gray-300 focus:border-[#318069]"
                                            placeholder="Dr. Jane Smith" oninput="validateField('name')">
                                        <div id="name-icon" class="validation-icon absolute right-2 sm:right-3 top-1/2 transform -translate-y-1/2"></div>
                                    </div>
                                    <div id="name-error" class="error-message mt-1 hidden text-xs sm:text-sm">Please enter your full name (minimum 2 characters)</div>
                                    @error('name')
                                        <div class="text-danger small mt-1 text-xs sm:text-sm">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-1 sm:mb-2">Email Address <span class="text-red-500">*</span></label>
                                    <div class="input-container relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                            <i class="ri-mail-line text-gray-400 text-base sm:text-lg"></i>
                                        </div>
                                        <input type="email" id="email" name="email" required
                                            value="{{ old('email') }}"
                                            class="w-full pl-9 sm:pl-12 pr-8 sm:pr-10 py-2 sm:py-3 border-2 rounded-lg focus:outline-none text-sm sm:text-base border-gray-300 focus:border-[#318069]"
                                            placeholder="doctor@example.com" oninput="validateField('email')">
                                        <div id="email-icon" class="validation-icon absolute right-2 sm:right-3 top-1/2 transform -translate-y-1/2"></div>
                                    </div>
                                    <div id="email-error" class="error-message mt-1 hidden text-xs sm:text-sm">Please enter a valid email address</div>
                                    @error('email')
                                        <div class="text-danger small mt-1 text-xs sm:text-sm">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="form-group">
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-1 sm:mb-2">Phone Number <span class="text-red-500">*</span></label>
                                    <div class="input-container relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                            <i class="ri-phone-line text-gray-400 text-base sm:text-lg"></i>
                                        </div>
                                        <input type="tel" id="phone" name="phone" required
                                            value="{{ old('phone') }}"
                                            class="w-full pl-9 sm:pl-12 pr-8 sm:pr-10 py-2 sm:py-3 border-2 rounded-lg focus:outline-none text-sm sm:text-base border-gray-300 focus:border-[#318069]"
                                            placeholder="+8801XXXXXXXXX" oninput="validateField('phone')">
                                        <div id="phone-icon" class="validation-icon absolute right-2 sm:right-3 top-1/2 transform -translate-y-1/2"></div>
                                    </div>
                                    <div id="phone-error" class="error-message mt-1 hidden text-xs sm:text-sm">Please enter a valid phone number (minimum 10 digits)</div>
                                    @error('phone')
                                        <div class="text-danger small mt-1 text-xs sm:text-sm">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Profile Photo - Responsive -->
                                <div class="form-group">
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-1 sm:mb-2">Professional Photo <span class="text-red-500">*</span></label>
                                    <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6">
                                        <div class="border-2 border-gray-300 rounded-xl p-3 sm:p-4 bg-gray-50 text-center mx-auto sm:mx-0" style="width: 120px; height: 120px; sm:width: 150px; sm:height: 150px;">
                                            <div id="photoPreview" class="h-full flex flex-col items-center justify-center cursor-pointer">
                                                <i class="ri-user-line text-gray-400 text-2xl sm:text-4xl mb-1 sm:mb-2"></i>
                                                <p class="text-xs text-gray-500">Preview</p>
                                                <p class="text-xs text-gray-400 mt-1">Click to upload</p>
                                            </div>
                                            <img id="photoPreviewImage" class="img-fluid rounded-xl w-full h-full object-cover hidden" src="" alt="Preview">
                                        </div>
                                        <div class="flex-grow-1 w-full sm:w-auto">
                                            <input type="file" id="photo" name="photo" accept="image/*" required class="form-control hidden" onchange="previewImage(this)">
                                            <div class="space-y-2 sm:space-y-4">
                                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                                    <label for="photo"
                                                        class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2 sm:py-3 border-2 border-[#318069] text-[#318069] rounded-lg font-semibold text-sm hover:bg-[#318069] hover:text-white transition-all cursor-pointer">
                                                        <i class="ri-upload-cloud-line"></i> Upload Photo
                                                    </label>
                                                    <button type="button"
                                                        onclick="document.getElementById('photo').value=''; document.getElementById('photoPreviewImage').classList.add('hidden'); document.getElementById('photoPreview').classList.remove('hidden');"
                                                        class="px-3 sm:px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-xs sm:text-sm hover:bg-gray-50">
                                                        Remove
                                                    </button>
                                                </div>
                                                <div class="text-xs text-gray-600 space-y-1">
                                                    <p><i class="ri-information-line mr-1"></i>Recommended: Square image, max 2MB</p>
                                                    <p class="hidden sm:block"><i class="ri-information-line mr-1"></i>Professional headshot recommended</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="photo-error" class="error-message mt-1 hidden text-xs sm:text-sm">Please upload a professional photo</div>
                                    @error('photo')
                                        <div class="text-danger small mt-1 text-xs sm:text-sm">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="form-group">
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-1 sm:mb-2">Password <span class="text-red-500">*</span></label>
                                    <div class="input-container relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                            <i class="ri-lock-line text-gray-400 text-base sm:text-lg"></i>
                                        </div>
                                        <input type="password" id="password" name="password" required
                                            class="w-full pl-9 sm:pl-12 pr-16 sm:pr-20 py-2 sm:py-3 border-2 rounded-lg focus:outline-none text-sm sm:text-base border-gray-300 focus:border-[#318069]"
                                            placeholder="••••••••" oninput="validatePassword()"
                                            onfocus="showPasswordRequirements()" onblur="hidePasswordRequirements()">
                                        <button type="button" onclick="togglePassword('password')"
                                            class="absolute right-2 sm:right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer">
                                            <i class="ri-eye-line text-base sm:text-lg"></i>
                                        </button>
                                    </div>
                                    <div id="password-error" class="error-message mt-1 hidden text-xs sm:text-sm">Password must be at least 8 characters with letters and numbers</div>

                                    <!-- Password Strength Meter - Responsive -->
                                    <div id="password-strength-meter" class="password-strength-meter mt-2 sm:mt-3 hidden">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-xs sm:text-sm font-medium text-gray-700">Password strength:</span>
                                            <span id="strength-label" class="text-xs sm:text-sm font-semibold">Very Weak</span>
                                        </div>
                                        <div class="strength-bars flex gap-1">
                                            <div class="strength-bar weak h-1 sm:h-1.5 flex-1 bg-gray-200 rounded" id="strength-bar-1"></div>
                                            <div class="strength-bar fair h-1 sm:h-1.5 flex-1 bg-gray-200 rounded" id="strength-bar-2"></div>
                                            <div class="strength-bar good h-1 sm:h-1.5 flex-1 bg-gray-200 rounded" id="strength-bar-3"></div>
                                            <div class="strength-bar strong h-1 sm:h-1.5 flex-1 bg-gray-200 rounded" id="strength-bar-4"></div>
                                        </div>
                                    </div>

                                    <!-- Password Requirements - Responsive -->
                                    <div id="password-requirements" class="password-requirements hidden mt-2 sm:mt-3 p-2 sm:p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                        <p class="text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Password must contain:</p>
                                        <ul class="criteria-list grid grid-cols-1 sm:grid-cols-2 gap-1 text-xs">
                                            <li class="criteria-item" id="criteria-length">At least 8 characters</li>
                                            <li class="criteria-item" id="criteria-uppercase">One uppercase letter</li>
                                            <li class="criteria-item" id="criteria-number">One number</li>
                                            <li class="criteria-item" id="criteria-special">One special character (!@#$%^&*)</li>
                                        </ul>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-1 text-xs sm:text-sm">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="form-group">
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-1 sm:mb-2">Confirm Password <span class="text-red-500">*</span></label>
                                    <div class="input-container relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                            <i class="ri-lock-line text-gray-400 text-base sm:text-lg"></i>
                                        </div>
                                        <input type="password" id="password_confirmation" name="password_confirmation" required
                                            class="w-full pl-9 sm:pl-12 pr-16 sm:pr-20 py-2 sm:py-3 border-2 rounded-lg focus:outline-none text-sm sm:text-base border-gray-300 focus:border-[#318069]"
                                            placeholder="••••••••" oninput="validateConfirmPassword()">
                                        <button type="button" onclick="togglePassword('password_confirmation')"
                                            class="absolute right-2 sm:right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer">
                                            <i class="ri-eye-line text-base sm:text-lg"></i>
                                        </button>
                                    </div>
                                    <div id="confirm-password-error" class="error-message mt-1 hidden text-xs sm:text-sm">Passwords do not match</div>
                                </div>

                                <!-- Terms & Conditions - Responsive -->
                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 form-group">
                                    <label class="flex items-start gap-2 sm:gap-3 cursor-pointer">
                                        <input id="terms" name="terms"
                                            class="mt-0.5 w-4 h-4 sm:w-5 sm:h-5 text-[#318069] border-gray-300 rounded focus:ring-[#318069] cursor-pointer flex-shrink-0"
                                            type="checkbox" onchange="validateTerms()">
                                        <span class="text-xs sm:text-sm text-gray-700">
                                            I agree to the <a href="#" onclick="showTermsModal()" class="text-[#318069] font-semibold hover:underline">Terms of Service</a>
                                            and <a href="#" onclick="showPrivacyModal()" class="text-[#318069] font-semibold hover:underline">Privacy Policy</a>
                                        </span>
                                    </label>
                                    <div id="terms-error" class="error-message mt-1 hidden text-xs sm:text-sm">You must agree to the terms</div>
                                    @error('terms')
                                        <div class="text-danger small mt-1 text-xs sm:text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Step Navigation - Responsive -->
                            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t-2 border-gray-200">
                                <button type="button" onclick="prevStep(2)"
                                    class="px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold text-sm sm:text-base hover:bg-gray-50 transition-all whitespace-nowrap order-2 sm:order-1">
                                    <i class="ri-arrow-left-line mr-1 sm:mr-2"></i>Back
                                </button>
                                <button type="button" onclick="handleStep3Continue()" id="step3-continue"
                                    class="flex-1 bg-gray-300 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition-all cursor-not-allowed order-1 sm:order-2"
                                    disabled>
                                    <span id="step3-continue-text">Continue to Payment</span>
                                    <i class="ri-arrow-right-line ml-1 sm:ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Payment - Fully Responsive -->
            <div id="step4" class="step-content hidden">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-6 sm:mb-8 md:mb-12">
                        <div class="inline-flex items-center gap-2 bg-[#318069]/10 rounded-full px-4 sm:px-5 py-1.5 sm:py-2 mb-3 sm:mb-4">
                            <i class="ri-bank-card-line text-[#318069] text-sm sm:text-base"></i>
                            <span class="text-xs sm:text-sm font-semibold text-[#318069]">Step 4 of 4</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2">Complete Your Payment</h1>
                        <p class="text-sm sm:text-base text-gray-600 max-w-2xl mx-auto px-4">Choose your preferred payment method to activate your account</p>
                    </div>

                    <!-- Order Summary Card - Responsive -->
                    <div class="max-w-5xl mx-auto mb-6 sm:mb-8">
                        <div class="bg-gradient-to-r from-[#318069]/10 to-[#FFC107]/10 border-2 border-[#318069]/20 rounded-xl p-3 sm:p-4 md:p-6">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-6">
                                <div class="w-full sm:w-auto">
                                    <p class="text-xs text-gray-600 mb-1">Order Summary</p>
                                    <p id="summaryPackage" class="text-base sm:text-lg md:text-xl font-bold text-gray-900 break-words">No Package Selected</p>
                                </div>
                                <div class="w-full sm:w-auto">
                                    <p class="text-xs text-gray-600 mb-1">Billing Cycle</p>
                                    <p id="summaryBilling" class="text-sm sm:text-base font-bold text-gray-900">Monthly Subscription</p>
                                </div>
                                <div class="w-full sm:w-auto text-left sm:text-right">
                                    <p class="text-xs text-gray-600 mb-1">Total Amount Due</p>
                                    <p id="summaryTotal" class="text-xl sm:text-2xl md:text-3xl font-bold text-[#318069]">
                                        {{ $pricingContext['currency_symbol'] }}0
                                    </p>
                                    <p class="text-xs text-gray-600 mt-1">+ applicable taxes</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form - Responsive -->
                    <div class="max-w-3xl mx-auto">
                        <div class="bg-white border-2 border-gray-200 rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 shadow-lg">
                            <!-- Payment Option Selection - Responsive -->
                            <div class="mb-4 sm:mb-6 md:mb-8">
                                <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2 sm:mb-3">Select Payment Method</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                                    <button type="button" onclick="selectPaymentMethod('sslcommerz')"
                                        class="payment-method-card flex flex-col items-center text-center p-4 sm:p-6 border-2 border-gray-200 rounded-xl hover:border-[#318069] hover:bg-[#318069]/5 transition-all">
                                        <div class="mb-2 sm:mb-3">
                                            <div class="bg-blue-600 text-white px-3 sm:px-4 py-1 sm:py-2 rounded-lg text-xs sm:text-sm font-bold">
                                                SSLCOMMERZ
                                            </div>
                                        </div>
                                        <h6 class="font-bold text-gray-900 text-xs sm:text-sm">Local Payment</h6>
                                        <p class="text-gray-600 text-xs mt-1 hidden sm:block">Credit/Debit Cards, Mobile Banking</p>
                                    </button>
                                </div>

                                <!-- SSLCOMMERZ Instructions - Responsive -->
                                <div id="sslInstructions" class="mt-4 sm:mt-6 hidden">
                                    <div class="border-2 border-blue-200 bg-blue-50 rounded-xl p-3 sm:p-6">
                                        <div class="flex items-start gap-2 sm:gap-3">
                                            <i class="ri-information-line text-blue-600 text-lg sm:text-xl flex-shrink-0"></i>
                                            <div class="flex-1">
                                                <h6 class="font-bold text-sm sm:text-base text-gray-900 mb-1 sm:mb-2">SSLCOMMERZ Payment Process</h6>
                                                <p class="text-xs sm:text-sm text-gray-700 mb-2 sm:mb-3">You will be redirected to SSLCOMMERZ secure payment gateway.</p>
                                                <div class="bg-white p-2 sm:p-4 rounded-lg space-y-1 sm:space-y-2">
                                                    <div class="flex items-center gap-2 sm:gap-3">
                                                        <div class="w-5 h-5 sm:w-6 sm:h-6 bg-[#318069] text-white rounded-full flex items-center justify-center text-[10px] sm:text-xs flex-shrink-0">1</div>
                                                        <p class="text-xs sm:text-sm text-gray-700">Click "Complete Registration" below</p>
                                                    </div>
                                                    <div class="flex items-center gap-2 sm:gap-3">
                                                        <div class="w-5 h-5 sm:w-6 sm:h-6 bg-[#318069] text-white rounded-full flex items-center justify-center text-[10px] sm:text-xs flex-shrink-0">2</div>
                                                        <p class="text-xs sm:text-sm text-gray-700">You'll be redirected to SSLCOMMERZ</p>
                                                    </div>
                                                    <div class="flex items-center gap-2 sm:gap-3">
                                                        <div class="w-5 h-5 sm:w-6 sm:h-6 bg-[#318069] text-white rounded-full flex items-center justify-center text-[10px] sm:text-xs flex-shrink-0">3</div>
                                                        <p class="text-xs sm:text-sm text-gray-700">Choose your payment method</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden Payment Fields -->
                            <input type="hidden" name="payment_option" id="payment_option" value="offline">
                            <input type="hidden" name="payment_method" id="payment_method" value="offline">

                            <!-- Coupon Section - Responsive -->
                            <div class="mb-4 sm:mb-6 md:mb-8">
                                <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2 sm:mb-3">
                                    <i class="ri-coupon-line text-[#318069] mr-1 sm:mr-2"></i>
                                    Apply Coupon (Optional)
                                </label>
                                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                                    <div class="flex-1 flex gap-2">
                                        <input type="text" id="coupon_code" name="coupon_code"
                                            class="flex-1 px-3 sm:px-4 py-2 sm:py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-sm sm:text-base"
                                            placeholder="Enter coupon code">
                                        <button type="button" onclick="applyCoupon()"
                                            class="bg-[#318069] hover:bg-[#276854] text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition-all whitespace-nowrap"
                                            id="applyCouponBtn">
                                            Apply
                                        </button>
                                    </div>
                                    <div>
                                        <button type="button" onclick="showAvailableCoupons()"
                                            class="w-full px-4 sm:px-6 py-2 sm:py-3 border-2 border-[#318069] text-[#318069] rounded-lg font-semibold text-sm sm:text-base hover:bg-[#318069] hover:text-white transition-all">
                                            <i class="ri-gift-line mr-1 sm:mr-2"></i>
                                            View Coupons
                                        </button>
                                    </div>
                                </div>
                                <div id="couponStatus" class="mt-2"></div>
                            </div>

                            <!-- Order Summary Details - Responsive -->
                            <div class="mb-4 sm:mb-6 md:mb-8">
                                <h6 class="font-bold text-sm sm:text-base text-gray-900 mb-2 sm:mb-3">Order Summary</h6>
                                <div class="space-y-2 sm:space-y-3 text-xs sm:text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Package Fee:</span>
                                        <span id="summaryPackageFee" class="font-semibold">
                                            {{ $pricingContext['currency_symbol'] }}0
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Domain Fee:</span>
                                        <span id="summaryDomainFee" class="font-semibold">
                                            {{ $pricingContext['currency_symbol'] }}0
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-green-600">
                                        <span>Discount:</span>
                                        <span id="summaryDiscount">- {{ $pricingContext['currency_symbol'] }}0</span>
                                    </div>
                                    <div class="border-t pt-2 sm:pt-3 mt-1 sm:mt-2">
                                        <div class="flex justify-between font-bold text-sm sm:text-base">
                                            <span>Total Amount:</span>
                                            <span id="summaryTotalAmount">
                                                {{ $pricingContext['currency_symbol'] }}0
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step Navigation - Responsive -->
                            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t-2 border-gray-200">
                                <button type="button" onclick="prevStep(3)"
                                    class="px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold text-sm sm:text-base hover:bg-gray-50 transition-all whitespace-nowrap order-2 sm:order-1">
                                    <i class="ri-arrow-left-line mr-1 sm:mr-2"></i>Back
                                </button>
                                <button type="submit" id="complete-registration-btn"
                                    class="flex-1 bg-[#318069] hover:bg-[#276854] text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition-all shadow-lg hover:shadow-xl order-1 sm:order-2">
                                    <i class="ri-lock-line mr-1 sm:mr-2"></i>
                                    Complete Registration
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>

            <!-- Back to Home - Responsive -->
            <div class="text-center mt-6 sm:mt-8">
                <a href="{{ url('/') }}"
                    class="text-sm sm:text-base text-gray-600 hover:text-[#318069] transition-all inline-flex items-center gap-1 sm:gap-2">
                    <i class="ri-arrow-left-line"></i>
                    Return to Home
                </a>
            </div>
        </div>
    </div>

    <!-- Available Coupons Modal - Responsive -->
    <div id="couponsModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="couponsModalLabel" aria-hidden="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-[#318069]/10 sm:mx-0">
                            <i class="ri-gift-line text-[#318069] text-lg sm:text-xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-base sm:text-lg leading-6 font-bold text-gray-900" id="couponsModalLabel">
                                Available Coupons
                            </h3>
                            <div class="mt-3 sm:mt-4">
                                <div id="availableCouponsList" class="grid grid-cols-1 gap-3 sm:gap-4">
                                    <!-- Coupons will be loaded here -->
                                </div>
                                <div id="couponsLoading" class="text-center py-3 sm:py-4">
                                    <div class="inline-block animate-spin rounded-full h-6 w-6 sm:h-8 sm:w-8 border-t-2 border-b-2 border-[#318069]"></div>
                                    <p class="mt-2 text-xs sm:text-sm text-gray-600">Loading available coupons...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="hideModal('couponsModal')"
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#318069] sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms & Conditions Modal - Responsive -->
    <div id="termsModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-[#318069]/10 sm:mx-0">
                            <i class="ri-file-text-line text-[#318069] text-lg sm:text-xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-base sm:text-lg leading-6 font-bold text-gray-900" id="termsModalLabel">
                                Terms & Conditions
                            </h3>
                            <div class="mt-3 sm:mt-4">
                                <div class="text-xs sm:text-sm text-gray-700 space-y-2 sm:space-y-4 max-h-60 sm:max-h-96 overflow-y-auto pr-2">
                                    <p>By registering, you agree to our terms and conditions...</p>
                                    <p>1. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                    <p>2. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    <p>3. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
                                    <p>4. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.</p>
                                    <p>5. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="hideModal('termsModal')"
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#318069] sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal - Responsive -->
    <div id="privacyModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-[#318069]/10 sm:mx-0">
                            <i class="ri-shield-keyhole-line text-[#318069] text-lg sm:text-xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-base sm:text-lg leading-6 font-bold text-gray-900" id="privacyModalLabel">
                                Privacy Policy
                            </h3>
                            <div class="mt-3 sm:mt-4">
                                <div class="text-xs sm:text-sm text-gray-700 space-y-2 sm:space-y-4 max-h-60 sm:max-h-96 overflow-y-auto pr-2">
                                    <p>Our privacy policy explains how we handle your data...</p>
                                    <p>1. We collect personal information to provide our services.</p>
                                    <p>2. Your data is stored securely and never shared without consent.</p>
                                    <p>3. You have the right to access, modify, or delete your data.</p>
                                    <p>4. We use cookies to improve user experience.</p>
                                    <p>5. For any privacy concerns, contact our data protection officer.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="hideModal('privacyModal')"
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#318069] sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .validation-icon{
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
        .domain-option.active,
        .payment-option-tab.active,
        .billing-toggle-btn.active {
            background-color: white;
            color: #318069;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .payment-method-card.selected {
            border-color: #318069 !important;
            background-color: #f0f9f7;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .success-message {
            color: #10b981;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .input-container {
            position: relative;
        }

        .validation-icon {
            display: none;
        }

        .validation-icon.error {
            color: #ef4444;
        }

        .validation-icon.success {
            color: #10b981;
        }

        .strength-bar.active {
            background-color: currentColor;
        }

        .strength-bar.weak.active {
            background-color: #ef4444;
        }

        .strength-bar.fair.active {
            background-color: #f59e0b;
        }

        .strength-bar.good.active {
            background-color: #10b981;
        }

        .strength-bar.strong.active {
            background-color: #059669;
        }

        .criteria-item {
            font-size: 0.75rem;
            color: #6b7280;
            margin-bottom: 4px;
            position: relative;
            padding-left: 20px;
        }

        .criteria-item:before {
            content: "✗";
            position: absolute;
            left: 0;
            color: #d1d5db;
        }

        .criteria-item.valid:before {
            content: "✓";
            color: #10b981;
        }

        .domain-suggestion {
            background-color: #f0f9f7;
            border: 1px solid #318069;
            border-radius: 8px;
            padding: 8px 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.75rem;
        }

        .domain-suggestion:hover {
            background-color: #318069;
            color: white;
        }

        @media (min-width: 640px) {
            .domain-suggestion {
                font-size: 0.875rem;
            }
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .package-card {
                margin-bottom: 1rem;
            }

            .package-card.scale-105 {
                transform: scale(1.02);
            }

            .strength-bars {
                margin: 0.5rem 0;
            }

            .criteria-list {
                grid-template-columns: 1fr;
            }
        }

        /* Touch-friendly improvements */
        @media (hover: none) and (pointer: coarse) {
            button,
            .cursor-pointer,
            a,
            select,
            input[type="checkbox"] {
                min-height: 44px;
            }

            input, select {
                font-size: 16px;
            }
        }
    </style>
@endsection

@push('scripts')
<script>
    // ========== GLOBAL VARIABLES FROM PHP ==========
    const BASE_CURRENCY = 'USD';
    const DISPLAY_CURRENCY = '{{ $pricingContext['currency_code'] ?? 'USD' }}';
    const CURRENCY_SYMBOL = @json($pricingContext['currency_symbol'] ?? '$');
    const EXCHANGE_RATE = {{ (float) ($pricingContext['exchange_rate'] ?? 1) }};
    const DOMAIN_PRICING_USD = @json($pricingContext['domain_prices_usd'] ?? ['.com' => 14.99]);
    const APP_DOMAIN = '{{ config('app.domain', 'doctorsprofile.xyz') }}';

    // ========== CURRENCY FORMATTING HELPER ==========
    function formatPrice(price, showDecimals = false) {
        const symbol = typeof CURRENCY_SYMBOL !== 'undefined' ? CURRENCY_SYMBOL : '$';
        const convertedPrice = parseFloat(price || 0) * EXCHANGE_RATE;

        let formattedPrice;
        if (showDecimals) {
            formattedPrice = convertedPrice.toFixed(2);
        } else {
            formattedPrice = Math.round(convertedPrice).toString();
            formattedPrice = formattedPrice.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        if (['$', '£', '¥', 'C$', 'A$', 'AED '].includes(symbol)) {
            return symbol + formattedPrice;
        }

        return symbol + ' ' + formattedPrice;
    }

    function getSelectedDomainPriceUsd(extension = '.com') {
        const normalized = extension && extension.startsWith('.') ? extension.toLowerCase() : `.${String(extension || 'com').toLowerCase()}`;
        return parseFloat(DOMAIN_PRICING_USD[normalized] ?? DOMAIN_PRICING_USD['.com'] ?? 14.99);
    }

    function legacyFormatPrice(price, showDecimals = false) {
        // Get currency symbol from global variable
        const symbol = typeof CURRENCY_SYMBOL !== 'undefined' ? CURRENCY_SYMBOL : '৳';

        // Format the number
        let formattedPrice;
        if (showDecimals) {
            formattedPrice = parseFloat(price).toFixed(2);
        } else {
            formattedPrice = Math.round(price).toString();
            // Add thousand separators
            formattedPrice = formattedPrice.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Different formatting for different currencies
        switch(symbol) {
            case '$': // Dollar - no space
            case '£': // Pound - no space
            case '¥': // Yen - no space
                return symbol + formattedPrice;
            case '€': // Euro - space after
            case '₹': // Rupee - space after
            case '৳': // Taka - space after
                return symbol + ' ' + formattedPrice;
            default:
                return symbol + ' ' + formattedPrice;
        }
    }

    // ========== MULTI-STEP FORM (4 STEPS) ==========
    let currentStep = 1;
    const totalSteps = 4;
    let subdomainAvailable = false;
    let domainAvailable = false;
    let appliedCoupon = null;
    let couponDiscount = 0;
    let selectedPaymentMethod = '';
    let selectedPackageId = null;
    let selectedDomainType = 'subdomain'; // Database value
    let selectedBillingCycle = 'monthly';
    let isFreePackage = false;
    let totalAmount = 0;

    // Field states for validation
    const fieldStates = {
        'name': { valid: false, touched: false },
        'email': { valid: false, touched: false },
        'phone': { valid: false, touched: false },
        'password': { valid: false, touched: false },
        'password_confirmation': { valid: false, touched: false },
        'terms': { valid: false, touched: false },
        'photo': { valid: false, touched: false }
    };

    // Initialize form on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Form initialized - Current step:', currentStep);
        console.log('Currency Symbol:', CURRENCY_SYMBOL);
        console.log('App Domain:', APP_DOMAIN);

        // Initialize billing toggle
        setupBillingToggle();

        // Initialize summary
        updateSummary();

        // Initialize domain validation
        validateSubdomain();

        // Initialize photo preview click
        const photoPreview = document.getElementById('photoPreview');
        if (photoPreview) {
            photoPreview.addEventListener('click', function() {
                const photoInput = document.getElementById('photo');
                if (photoInput) photoInput.click();
            });
        }

        // Initialize geolocation (with delay to ensure DOM is ready)
        setTimeout(initGeolocation, 500);

        // Load available coupons
        loadAvailableCoupons();

        // Initialize payment method for free packages
        initializePaymentMethod();

        // Set active domain tab and hidden field
        const domainTypeField = document.getElementById('domain_type');
        if (domainTypeField) {
            domainTypeField.value = 'subdomain'; // Set to database expected value
        }

        // Update all prices with currency symbol
        updatePackagePrices();
        updateDomainRegistrationFee();
    });

    // ========== INITIALIZE PAYMENT METHOD ==========
    function initializePaymentMethod() {
        const paymentMethodField = document.getElementById('payment_method');
        const paymentOptionField = document.getElementById('payment_option');

        if (paymentMethodField) {
            paymentMethodField.value = 'offline';
            selectedPaymentMethod = 'offline';
        }

        if (paymentOptionField) {
            paymentOptionField.value = 'offline';
        }

        console.log('Initialized payment method:', selectedPaymentMethod);
    }

    // ========== UPDATE DOMAIN REGISTRATION FEE ==========
    function updateDomainRegistrationFee() {
        const extensionSelect = document.getElementById('new_domain_extension');
        const selectedExtension = extensionSelect?.value || '.com';
        const baseDomainPrice = getSelectedDomainPriceUsd(selectedExtension);
        const domainFeeElements = document.querySelectorAll('.domain-registration-fee');
        domainFeeElements.forEach(element => {
            element.textContent = formatPrice(baseDomainPrice);
        });
    }

    // ========== GEOLOCATION FUNCTIONS ==========
    function initGeolocation() {
        console.log('Initializing geolocation...');

        const latField = document.getElementById('latitude');
        const lonField = document.getElementById('longitude');
        const cityField = document.getElementById('city');

        if (!latField || !lonField) {
            console.error('Geolocation fields not found in DOM');
            return;
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function success(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    console.log('Geolocation successful:', lat, lon);

                    if (latField) latField.value = lat;
                    if (lonField) lonField.value = lon;

                    getCityFromCoordinates(lat, lon);
                },
                function error(error) {
                    console.error("Geolocation error:", error.code, error.message);

                    if (latField) latField.value = '';
                    if (lonField) lonField.value = '';
                    if (cityField) cityField.value = '';
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        } else {
            console.log("Geolocation not supported by this browser.");
            if (latField) latField.value = '';
            if (lonField) lonField.value = '';
            if (cityField) cityField.value = '';
        }
    }

    async function getCityFromCoordinates(lat, lon) {
        try {
            const cityField = document.getElementById('city');
            if (!cityField) {
                console.log('City field not found');
                return;
            }

            const response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`);
            const data = await response.json();

            if (data && data.address) {
                const city = data.address.city || data.address.town || data.address.village || data.address.county || '';
                if (city && cityField) {
                    cityField.value = city;
                    console.log('City detected:', city);
                }
            }
        } catch (error) {
            console.error('Error getting city from coordinates:', error);
        }
    }

    // ========== STEP NAVIGATION ==========
    function nextStep(step) {
        console.log(`Moving to step ${step} from step ${currentStep}`);

        if (validateCurrentStep()) {
            hideAllSteps();
            currentStep = step;
            showStep(currentStep);
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
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    function hideAllSteps() {
        document.querySelectorAll('.step-content').forEach(step => {
            step.classList.remove('active');
            step.classList.add('hidden');
        });
    }

    function showStep(stepNumber) {
        const stepElement = document.getElementById(`step${stepNumber}`);
        if (stepElement) {
            stepElement.classList.remove('hidden');
            stepElement.classList.add('active');
            console.log(`Showing step ${stepNumber}`);

            if (stepNumber === 4) {
                updatePaymentUI();
            }
        }
    }

    // ========== BILLING TOGGLE ==========
    function setupBillingToggle() {
        const monthlyBtn = document.getElementById('billing-toggle-monthly');
        const yearlyBtn = document.getElementById('billing-toggle-yearly');

        if (!monthlyBtn || !yearlyBtn) {
            console.log('Billing toggle buttons not found');
            return;
        }

        monthlyBtn.addEventListener('click', function() {
            selectBillingCycle('monthly');
        });

        yearlyBtn.addEventListener('click', function() {
            selectBillingCycle('yearly');
        });
    }

    function selectBillingCycle(cycle) {
        selectedBillingCycle = cycle;

        const billingCycleField = document.getElementById('selectedBillingCycle');
        if (billingCycleField) billingCycleField.value = cycle;

        document.querySelectorAll('.billing-toggle-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-[#318069]', 'text-white');
            btn.classList.add('text-gray-700');
        });

        const activeBtn = document.getElementById(`billing-toggle-${cycle}`);
        if (activeBtn) {
            activeBtn.classList.add('active', 'bg-[#318069]', 'text-white');
            activeBtn.classList.remove('text-gray-700');
        }

        updatePackagePrices();
        updateSummary();
    }

    function updatePackagePrices() {
        document.querySelectorAll('.package-card').forEach(card => {
            const priceElement = card.querySelector('.package-price');
            const periodElement = card.querySelector('.package-period');
            const savingsElement = card.querySelector('.billing-savings');
            const currencySpan = card.querySelector('.currency-symbol');
            const amountSpan = card.querySelector('.price-amount');

            if (priceElement && periodElement && amountSpan) {
                const monthlyPrice = parseFloat(priceElement.dataset.monthly);
                const yearlyPrice = parseFloat(priceElement.dataset.yearly);
                const rawPrice = selectedBillingCycle === 'yearly' ? yearlyPrice : monthlyPrice;
                const convertedPrice = Math.round(rawPrice * EXCHANGE_RATE);

                if (currencySpan) {
                    currencySpan.textContent = CURRENCY_SYMBOL;
                }

                amountSpan.textContent = convertedPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                periodElement.textContent = selectedBillingCycle === 'yearly' ? '/year' : '/month';

                if (savingsElement) {
                    if (selectedBillingCycle === 'yearly') {
                        savingsElement.classList.add('hidden');
                    } else {
                        savingsElement.classList.remove('hidden');
                    }
                }
            }
        });
    }

    // ========== DOMAIN TYPE TOGGLE ==========
    function selectedPackageHasFeature(feature) {
        if (!selectedPackageId) return false;

        const selectedCard = document.querySelector(`[data-package-id="${selectedPackageId}"]`);
        if (!selectedCard) return false;

        try {
            const features = JSON.parse(selectedCard.dataset.packageFeatures || '{}');
            return !!features[feature];
        } catch (error) {
            console.error('Package feature parse failed:', error);
            return false;
        }
    }

    function switchDomainTab(option) {
        if ((option === 'register' || option === 'existing') && !selectedPackageHasFeature('custom_domain')) {
            alert('The selected package supports subdomain only. Please choose Standard or Premium for custom domain support.');
            option = 'free';
        }

        // Map the UI option to the database expected value
        let dbValue = option;
        if (option === 'free') {
            dbValue = 'subdomain'; // Map 'free' to 'subdomain' for database
        } else if (option === 'register') {
            dbValue = 'new'; // Map 'register' to 'new' for database
        } // 'existing' stays as 'existing'
        
        selectedDomainType = dbValue; // Store the database value

        const domainTypeField = document.getElementById('domain_type');
        if (domainTypeField) {
            domainTypeField.value = dbValue; // Set to database expected value
            console.log('Domain type set to:', dbValue);
        }

        // Update tab buttons UI (keep using 'free', 'register', 'existing' for UI)
        document.querySelectorAll('.domain-option').forEach(btn => {
            btn.classList.remove('active', 'bg-white', 'text-[#318069]', 'shadow-lg');
            btn.classList.add('text-gray-700');
        });

        const activeTab = document.getElementById(`${option}-domain-tab`);
        if (activeTab) {
            activeTab.classList.add('active', 'bg-white', 'text-[#318069]', 'shadow-lg');
            activeTab.classList.remove('text-gray-700');
        }

        // Show/hide content based on UI option
        document.querySelectorAll('.domain-content').forEach(content => {
            content.classList.add('hidden');
        });

        const activeContent = document.getElementById(`${option}-domain-content`);
        if (activeContent) {
            activeContent.classList.remove('hidden');
            console.log('Showing domain content for:', option);
        }

        resetDomainContinueButtons();

        // Reset validation based on UI option
        if (option === 'free') {
            subdomainAvailable = false;
            validateSubdomain();
        } else if (option === 'register') {
            domainAvailable = false;
            validateDomainFormat();
        } else if (option === 'existing') {
            domainAvailable = false;
            validateExistingDomain();
        }

        updateSummary();
    }

    function resetDomainContinueButtons() {
        const continueBtns = [
            'step2-continue',
            'register-domain-continue',
            'existing-domain-continue'
        ];

        continueBtns.forEach(btnId => {
            const btn = document.getElementById(btnId);
            if (btn) {
                btn.disabled = true;
                btn.classList.remove('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
                btn.classList.add('bg-gray-300', 'cursor-not-allowed');
            }
        });
    }

    // ========== SUBDOMAIN FUNCTIONS ==========
function validateSubdomain() {
    const input = document.getElementById('subdomain_name');
    const continueBtn = document.getElementById('step2-continue');
    const errorElement = document.getElementById('subdomain-error');
    const previewContainer = document.getElementById('subdomain-preview-container');
    const preview = document.getElementById('subdomain-preview');

    if (!input) return false;

    const value = input.value.trim();

    if (value.length === 0) {
        if (errorElement) errorElement.classList.add('hidden');
        if (previewContainer) previewContainer.classList.add('hidden');
        input.classList.remove('border-red-500', 'border-green-500');
        input.classList.add('border-gray-300');
        if (continueBtn) {
            continueBtn.disabled = true;
            continueBtn.classList.remove('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
            continueBtn.classList.add('bg-gray-300', 'cursor-not-allowed');
        }
        return false;
    }

    const subdomainRegex = /^[a-z0-9]([a-z0-9-]*[a-z0-9])?$/i;
    const isValid = subdomainRegex.test(value) && value.length >= 3 && value.length <= 63;

    if (isValid) {
        if (errorElement) errorElement.classList.add('hidden');
        if (previewContainer) previewContainer.classList.remove('hidden');
        input.classList.remove('border-red-500', 'border-gray-300');
        input.classList.add('border-green-500');
        if (preview) preview.textContent = value + '.' + APP_DOMAIN;

        if (continueBtn) {
            continueBtn.disabled = !subdomainAvailable;
            if (subdomainAvailable) {
                continueBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
                continueBtn.classList.add('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
            }
        }
        return true;
    } else {
        if (errorElement) errorElement.classList.remove('hidden');
        if (previewContainer) previewContainer.classList.add('hidden');
        input.classList.remove('border-green-500', 'border-gray-300');
        input.classList.add('border-red-500');
        if (continueBtn) {
            continueBtn.disabled = true;
            continueBtn.classList.remove('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
            continueBtn.classList.add('bg-gray-300', 'cursor-not-allowed');
        }
        return false;
    }
}

function suggestSubdomain() {
    const nameInput = document.getElementById('name');
    const subdomainInput = document.getElementById('subdomain_name');

    if (!subdomainInput) return;

    let suggestedName = '';

    if (nameInput && nameInput.value.trim()) {
        const name = nameInput.value.trim().toLowerCase();
        const nameParts = name.split(' ');

        if (nameParts.length >= 2) {
            suggestedName = nameParts[0] + nameParts[nameParts.length - 1].charAt(0);
        } else if (nameParts.length === 1) {
            suggestedName = nameParts[0];
        }
    } else {
        const prefixes = ['dr', 'doc', 'med', 'health', 'care'];
        const suffixes = ['clinic', 'practice', 'medical', 'health', 'care'];
        const prefix = prefixes[Math.floor(Math.random() * prefixes.length)];
        const suffix = suffixes[Math.floor(Math.random() * suffixes.length)];
        const randomNum = Math.floor(Math.random() * 99) + 1;
        suggestedName = `${prefix}${suffix}${randomNum}`;
    }

    suggestedName = suggestedName
        .toLowerCase()
        .replace(/[^a-z0-9]/g, '')
        .substring(0, 20);

    subdomainInput.value = suggestedName;
    validateSubdomain();
}

async function checkSubdomainAvailability() {
    const subdomainInput = document.getElementById('subdomain_name');
    const checkBtn = document.getElementById('checkSubdomainBtn');
    const statusDiv = document.getElementById('subdomainStatus');
    const continueBtn = document.getElementById('step2-continue');

    if (!subdomainInput || !checkBtn || !statusDiv) return;

    const subdomain = subdomainInput.value.trim().toLowerCase();

    if (!subdomain) {
        statusDiv.innerHTML =
            '<div class="border-2 border-amber-200 bg-amber-50 rounded-xl p-4"><div class="flex items-center gap-3"><i class="ri-alert-line text-amber-600 text-xl"></i><div><h3 class="font-bold text-amber-900">Please enter a subdomain name</h3></div></div></div>';
        subdomainAvailable = false;
        return;
    }

    // Validate format before checking
    const subdomainRegex = /^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/;
    if (!subdomainRegex.test(subdomain)) {
        statusDiv.innerHTML =
            '<div class="border-2 border-red-200 bg-red-50 rounded-xl p-4"><div class="flex items-center gap-3"><i class="ri-close-line text-red-600 text-xl"></i><div><h3 class="font-bold text-red-900">Invalid Format</h3><p class="text-sm text-red-700">Subdomain can only contain letters, numbers, and hyphens</p></div></div></div>';
        subdomainAvailable = false;
        return;
    }

    checkBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Checking...';
    checkBtn.disabled = true;

    try {
        // Get CSRF token
       // const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        const response = await fetch('{{ url("api/v1/check-subdomain") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
               // 'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                subdomain: subdomain
            })
        });

        const result = await response.json();

        if (result.available) {
            statusDiv.innerHTML = `<div class="border-2 border-green-200 bg-green-50 rounded-xl p-4">
                <div class="flex items-center gap-3">
                    <i class="ri-check-line text-green-600 text-xl"></i>
                    <div>
                        <h3 class="font-bold text-green-900">Subdomain Available!</h3>
                        <p class="text-sm text-green-700">${result.fullDomain || (subdomain + '.' + APP_DOMAIN)} is available!</p>
                    </div>
                </div>
            </div>`;
            subdomainAvailable = true;

            if (continueBtn) {
                continueBtn.disabled = false;
                continueBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
                continueBtn.classList.add('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
            }

            const selectedSubdomainField = document.getElementById('selectedSubdomain');
            if (selectedSubdomainField) {
                selectedSubdomainField.value = result.fullDomain || (subdomain + '.' + APP_DOMAIN);
            }
        } else {
            // Check if there's a suggestion from the API
            if (result.suggestion) {
                statusDiv.innerHTML = `<div class="border-2 border-red-200 bg-red-50 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <i class="ri-close-line text-red-600 text-xl"></i>
                        <div>
                            <h3 class="font-bold text-red-900">Subdomain Not Available</h3>
                            <p class="text-sm text-red-700 mb-2">${result.fullDomain || (subdomain + '.' + APP_DOMAIN)} is already taken.</p>
                            <div class="mt-3">
                                <p class="text-sm font-semibold text-gray-700 mb-2">Try this suggestion:</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="domain-suggestion" onclick="useDomainSuggestion('${result.suggestion}')">${result.suggestion}.${APP_DOMAIN}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            } else {
                // Generate local suggestions as fallback
                const suggestions = [
                    subdomain + Math.floor(Math.random() * 99) + 1,
                    subdomain + '-clinic',
                    subdomain + '-med',
                    'dr' + subdomain
                ];

                let suggestionsHtml = suggestions.map(s =>
                    `<span class="domain-suggestion" onclick="useDomainSuggestion('${s}')">${s}.${APP_DOMAIN}</span>`
                ).join('');

                statusDiv.innerHTML = `<div class="border-2 border-red-200 bg-red-50 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <i class="ri-close-line text-red-600 text-xl"></i>
                        <div>
                            <h3 class="font-bold text-red-900">Subdomain Not Available</h3>
                            <p class="text-sm text-red-700 mb-2">${result.fullDomain || (subdomain + '.' + APP_DOMAIN)} is already taken.</p>
                            <div class="mt-3">
                                <p class="text-sm font-semibold text-gray-700 mb-2">Try these suggestions:</p>
                                <div class="flex flex-wrap gap-2">
                                    ${suggestionsHtml}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            }
            subdomainAvailable = false;
        }
    } catch (error) {
        console.error('Error checking subdomain:', error);
        statusDiv.innerHTML =
            '<div class="border-2 border-red-200 bg-red-50 rounded-xl p-4"><div class="flex items-center gap-3"><i class="ri-close-line text-red-600 text-xl"></i><div><h3 class="font-bold text-red-900">Error</h3><p class="text-sm text-red-700">Error checking subdomain availability. Please try again.</p></div></div></div>';
        subdomainAvailable = false;
    } finally {
        checkBtn.innerHTML = 'Check';
        checkBtn.disabled = false;
    }
}

// ========== DOMAIN AVAILABILITY CHECK ==========
function validateDomainFormat() {
    const input = document.getElementById('new_domain_name');
    const errorElement = document.getElementById('domain-format-error');

    if (!input) return false;

    const value = input.value.trim();

    if (value.length === 0) {
        if (errorElement) errorElement.classList.add('hidden');
        input.classList.remove('border-red-500', 'border-green-500');
        input.classList.add('border-gray-300');
        return false;
    }

    const domainRegex = /^[a-z0-9]([a-z0-9-]*[a-z0-9])?$/i;
    const isValid = domainRegex.test(value);

    if (!isValid) {
        if (errorElement) errorElement.classList.remove('hidden');
        input.classList.remove('border-green-500', 'border-gray-300');
        input.classList.add('border-red-500');
        return false;
    } else {
        if (errorElement) errorElement.classList.add('hidden');
        input.classList.remove('border-red-500', 'border-gray-300');
        input.classList.add('border-green-500');
        return true;
    }
}

async function checkDomainAvailability() {
    const domainInput = document.getElementById('new_domain_name');
    const extensionSelect = document.getElementById('new_domain_extension');
    const checkBtn = document.getElementById('checkDomainBtn');
    const statusDiv = document.getElementById('newDomainStatus');
    const suggestionsDiv = document.getElementById('domain-suggestions');
    const continueBtn = document.getElementById('register-domain-continue');

    if (!domainInput || !checkBtn || !statusDiv) return;

    const domainName = domainInput.value.trim().toLowerCase();
    const extension = extensionSelect?.value || '.com';
    const fullDomain = domainName + extension;

    if (!domainName) {
        statusDiv.innerHTML =
            '<div class="border-2 border-amber-200 bg-amber-50 rounded-xl p-4"><div class="flex items-center gap-3"><i class="ri-alert-line text-amber-600 text-xl"></i><div><h3 class="font-bold text-amber-900">Please enter a domain name</h3></div></div></div>';
        domainAvailable = false;
        return;
    }

    // Validate format
    const domainRegex = /^[a-z0-9]([a-z0-9-]*[a-z0-9])?$/i;
    if (!domainRegex.test(domainName)) {
        statusDiv.innerHTML =
            '<div class="border-2 border-red-200 bg-red-50 rounded-xl p-4"><div class="flex items-center gap-3"><i class="ri-close-line text-red-600 text-xl"></i><div><h3 class="font-bold text-red-900">Invalid Format</h3><p class="text-sm text-red-700">Domain name can only contain letters, numbers, and hyphens</p></div></div></div>';
        domainAvailable = false;
        return;
    }

    checkBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Checking...';
    checkBtn.disabled = true;

    try {
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        const response = await fetch('{{ url("api/v1/check-domain") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                domain: fullDomain
            })
        });

        const result = await response.json();

        if (result.available) {
            statusDiv.innerHTML = `<div class="border-2 border-green-200 bg-green-50 rounded-xl p-4">
                <div class="flex items-center gap-3">
                    <i class="ri-check-line text-green-600 text-xl"></i>
                    <div>
                        <h3 class="font-bold text-green-900">Domain Available!</h3>
                        <p class="text-sm text-green-700">${result.domain || fullDomain} is available for registration!</p>
                        <div class="mt-3 flex items-center gap-2">
                            <span class="text-lg font-bold text-[#318069]">${formatPrice(result.domain_price_usd ?? getSelectedDomainPriceUsd(extension))}/year</span>
                            <span class="text-sm text-gray-600">+ ICANN fee</span>
                        </div>
                    </div>
                </div>
            </div>`;
            if (suggestionsDiv) suggestionsDiv.classList.add('hidden');
            domainAvailable = true;

            if (continueBtn) {
                continueBtn.disabled = false;
                continueBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
                continueBtn.classList.add('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
            }

            const selectedSubdomainField = document.getElementById('selectedSubdomain');
            if (selectedSubdomainField) {
                selectedSubdomainField.value = result.domain || fullDomain;
            }
        } else {
            // Use suggestions from API or generate local ones
            let suggestionsHtml = '';
            
            if (result.suggestions && result.suggestions.length > 0) {
                suggestionsHtml = result.suggestions.map(domain => {
                    // Extract just the name without extension for the suggestion
                    const parts = domain.split('.');
                    const name = parts[0];
                    const ext = '.' + parts.slice(1).join('.');
                    return `<div class="border border-gray-200 rounded-lg p-4 hover:border-[#318069] hover:bg-gray-50 transition-all cursor-pointer" onclick="selectDomainSuggestion('${domain}', ${result.domain_price_usd ?? getSelectedDomainPriceUsd(ext)})">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-900">${domain}</p>
                                <p class="text-sm text-gray-600">Available</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-[#318069]">${formatPrice(result.domain_price_usd ?? getSelectedDomainPriceUsd(ext))}</p>
                                <p class="text-xs text-gray-600">/year</p>
                            </div>
                        </div>
                    </div>`;
                }).join('');
            } else {
                // Generate local suggestions as fallback
                const alternatives = [
                    { name: domainName + Math.floor(Math.random() * 99) + 1, price: 14.99 },
                    { name: domainName + '-clinic', price: 14.99 },
                    { name: domainName + '-med', price: 19.99 },
                    { name: domainName + 'health', price: 24.99 }
                ];

                suggestionsHtml = alternatives.map(alt => `
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-[#318069] hover:bg-gray-50 transition-all cursor-pointer" onclick="selectDomainSuggestion('${alt.name}${extension}', ${alt.price})">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-900">${alt.name}${extension}</p>
                                <p class="text-sm text-gray-600">Available</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-[#318069]">${formatPrice(alt.price)}</p>
                                <p class="text-xs text-gray-600">/year</p>
                            </div>
                        </div>
                    </div>
                `).join('');
            }

            statusDiv.innerHTML = `<div class="border-2 border-red-200 bg-red-50 rounded-xl p-4">
                <div class="flex items-center gap-3">
                    <i class="ri-close-line text-red-600 text-xl"></i>
                    <div>
                        <h3 class="font-bold text-red-900">Domain Not Available</h3>
                        <p class="text-sm text-red-700">${result.domain || fullDomain} is already registered.</p>
                    </div>
                </div>
            </div>`;

            if (suggestionsDiv) {
                suggestionsDiv.classList.remove('hidden');
                const suggestionsList = document.getElementById('suggestions-list');
                if (suggestionsList) suggestionsList.innerHTML = suggestionsHtml;
            }
            domainAvailable = false;
        }
    } catch (error) {
        console.error('Error checking domain:', error);
        statusDiv.innerHTML =
            '<div class="border-2 border-red-200 bg-red-50 rounded-xl p-4"><div class="flex items-center gap-3"><i class="ri-close-line text-red-600 text-xl"></i><div><h3 class="font-bold text-red-900">Error</h3><p class="text-sm text-red-700">Error checking domain availability. Please try again.</p></div></div></div>';
        domainAvailable = false;
    } finally {
        checkBtn.innerHTML = 'Check';
        checkBtn.disabled = false;
    }
}

function selectDomainSuggestion(domain, price) {
    const parts = domain.split('.');
    const name = parts[0];
    const extension = '.' + parts.slice(1).join('.');

    const domainInput = document.getElementById('new_domain_name');
    const extensionSelect = document.getElementById('new_domain_extension');

    if (domainInput) domainInput.value = name;
    if (extensionSelect) extensionSelect.value = extension;

    const statusDiv = document.getElementById('newDomainStatus');
    if (statusDiv) {
        statusDiv.innerHTML = `
            <div class="border-2 border-green-200 bg-green-50 rounded-xl p-4">
                <div class="flex items-center gap-3">
                    <i class="ri-check-line text-green-600 text-xl"></i>
                    <div>
                        <h3 class="font-bold text-green-900">Domain Selected!</h3>
                        <p class="text-sm text-green-700">${domain} has been selected.</p>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="font-bold text-[#318069]">${formatPrice(price)}/year</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    const suggestionsDiv = document.getElementById('domain-suggestions');
    if (suggestionsDiv) suggestionsDiv.classList.add('hidden');

    domainAvailable = true;

    const continueBtn = document.getElementById('register-domain-continue');
    if (continueBtn) {
        continueBtn.disabled = false;
        continueBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
        continueBtn.classList.add('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
    }

    const selectedSubdomainField = document.getElementById('selectedSubdomain');
    if (selectedSubdomainField) {
        selectedSubdomainField.value = domain;
    }
}

function useDomainSuggestion(suggestion) {
    const subdomainInput = document.getElementById('subdomain_name');
    if (subdomainInput) {
        subdomainInput.value = suggestion;
        validateSubdomain();
        // Optionally auto-check availability
        checkSubdomainAvailability();
    }
}

    function verifyDNS() {
        const domainInput = document.getElementById('existing_domain');
        const statusDiv = document.getElementById('dns-verification-status');
        const continueBtn = document.getElementById('existing-domain-continue');

        if (!domainInput) return;

        const domain = domainInput.value.trim();

        if (!domain) {
            alert('Please enter a domain name first.');
            return;
        }

        if (statusDiv) {
            statusDiv.innerHTML =
                '<span class="text-amber-600"><i class="ri-loader-4-line animate-spin mr-1"></i>Verifying...</span>';
        }

        setTimeout(() => {
            if (statusDiv) {
                statusDiv.innerHTML =
                    '<span class="text-green-600"><i class="ri-check-line mr-1"></i>DNS Verified</span>';
            }

            domainAvailable = true;

            if (continueBtn) {
                continueBtn.disabled = false;
                continueBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
                continueBtn.classList.add('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
            }

            const selectedSubdomainField = document.getElementById('selectedSubdomain');
            if (selectedSubdomainField) {
                selectedSubdomainField.value = domain;
            }
        }, 2000);
    }

    // ========== PACKAGE SELECTION ==========
    function selectPackage(packageId) {
        selectedPackageId = packageId;

        const packageIdField = document.getElementById('selectedPackageId');
        if (packageIdField) packageIdField.value = packageId;

        const selectedCard = document.querySelector(`[data-package-id="${packageId}"]`);
        if (!selectedCard) {
            console.error('Package card not found:', packageId);
            return;
        }

        const monthlyPrice = parseFloat(selectedCard.dataset.packagePriceMonthly || 0);
        const yearlyPrice = parseFloat(selectedCard.dataset.packagePriceYearly || 0);

        isFreePackage = (monthlyPrice === 0 && yearlyPrice === 0);

        const isFreePackageField = document.getElementById('isFreePackage');
        if (isFreePackageField) isFreePackageField.value = isFreePackage ? '1' : '0';

        console.log(`Selected package ${packageId}, isFreePackage: ${isFreePackage}`);

        if (!selectedPackageHasFeature('custom_domain') && selectedDomainType !== 'subdomain') {
            switchDomainTab('free');
        }

        document.querySelectorAll('.package-card').forEach(card => {
            card.classList.remove('border-[#318069]', 'shadow-xl', 'scale-105');
            card.classList.add('border-gray-200');

            const btn = card.querySelector('.package-select-btn');
            if (btn) {
                btn.classList.remove('bg-[#318069]', 'hover:bg-[#276854]', 'text-white', 'shadow-lg', 'hover:shadow-xl');
                btn.classList.add('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');

                const btnText = btn.querySelector('.package-btn-text');
                const btnIcon = btn.querySelector('.package-btn-icon');
                if (btnText) btnText.textContent = 'Select ' + (card.querySelector('h3')?.textContent || '');
                if (btnIcon) {
                    btnIcon.classList.remove('ri-check-line');
                    btnIcon.classList.add('ri-arrow-right-line');
                }
            }
        });

        if (selectedCard) {
            selectedCard.classList.remove('border-gray-200');
            selectedCard.classList.add('border-[#318069]', 'shadow-xl', 'scale-105');

            const btn = selectedCard.querySelector('.package-select-btn');
            if (btn) {
                btn.classList.remove('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
                btn.classList.add('bg-[#318069]', 'hover:bg-[#276854]', 'text-white', 'shadow-lg', 'hover:shadow-xl');

                const btnText = btn.querySelector('.package-btn-text');
                const btnIcon = btn.querySelector('.package-btn-icon');
                if (btnText) btnText.textContent = 'Selected';
                if (btnIcon) {
                    btnIcon.classList.remove('ri-arrow-right-line');
                    btnIcon.classList.add('ri-check-line');
                }
            }
        }

        updateSummary();

        if (isFreePackage) {
            const monthlyBtn = document.getElementById('billing-toggle-monthly');
            const yearlyBtn = document.getElementById('billing-toggle-yearly');

            if (monthlyBtn) {
                monthlyBtn.disabled = true;
                monthlyBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }

            if (yearlyBtn) {
                yearlyBtn.disabled = true;
                yearlyBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }

            const paymentMethodField = document.getElementById('payment_method');
            const paymentOptionField = document.getElementById('payment_option');

            if (paymentMethodField) {
                paymentMethodField.value = 'offline';
                selectedPaymentMethod = 'offline';
            }

            if (paymentOptionField) {
                paymentOptionField.value = 'offline';
            }

            nextStep(2);
        } else {
            const monthlyBtn = document.getElementById('billing-toggle-monthly');
            const yearlyBtn = document.getElementById('billing-toggle-yearly');

            if (monthlyBtn) {
                monthlyBtn.disabled = false;
                monthlyBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }

            if (yearlyBtn) {
                yearlyBtn.disabled = false;
                yearlyBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }

            const paymentMethodField = document.getElementById('payment_method');
            const paymentOptionField = document.getElementById('payment_option');

            if (paymentMethodField) {
                paymentMethodField.value = 'sslcommerz';
                selectedPaymentMethod = 'sslcommerz';
            }

            if (paymentOptionField) {
                paymentOptionField.value = 'online';
            }

            nextStep(2);
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

        if (!selectedPackageId) {
            alert('⚠️ Please select a package to continue.');
            return false;
        }

        return true;
    }

    function validateStep2() {
        console.log('Validating Step 2');

        const domainType = document.getElementById('domain_type');
        if (!domainType) return false;

        const domainTypeValue = domainType.value;
        console.log(`Domain type selected: ${domainTypeValue}`);

        // Check against database expected values: 'subdomain', 'new', 'existing'
        if (domainTypeValue === 'subdomain') {
            const subdomainInput = document.getElementById('subdomain_name');
            const subdomain = subdomainInput?.value.trim();

            if (!subdomain) {
                alert('⚠️ Please enter a subdomain name.');
                subdomainInput?.focus();
                return false;
            }

            const subdomainRegex = /^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/i;
            if (!subdomainRegex.test(subdomain.toLowerCase())) {
                alert('⚠️ Subdomain can only contain letters, numbers, and hyphens.');
                subdomainInput?.focus();
                return false;
            }

            if (!subdomainAvailable) {
                alert('⚠️ Please check subdomain availability before proceeding.');
                const checkBtn = document.getElementById('checkSubdomainBtn');
                if (checkBtn) checkBtn.click();
                return false;
            }

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
                const checkBtn = document.getElementById('checkDomainBtn');
                if (checkBtn) checkBtn.click();
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
        const requiredFields = ['name', 'email', 'phone', 'password', 'password_confirmation', 'terms'];
        let allValid = true;

        requiredFields.forEach(field => {
            if (field === 'terms') {
                if (!validateTerms()) allValid = false;
            } else if (field === 'password_confirmation') {
                if (!validateConfirmPassword()) allValid = false;
            } else if (field === 'password') {
                if (!validatePassword()) allValid = false;
            } else {
                if (!validateField(field)) allValid = false;
            }
        });

        const photoInput = document.getElementById('photo');
        if (!photoInput || !photoInput.files.length) {
            const photoError = document.getElementById('photo-error');
            if (photoError) photoError.classList.remove('hidden');
            fieldStates.photo.valid = false;
            allValid = false;
        } else {
            const photoError = document.getElementById('photo-error');
            if (photoError) photoError.classList.add('hidden');
            fieldStates.photo.valid = true;
        }

        if (!allValid) {
            const firstError = document.querySelector('.error-message:not(.hidden)');
            if (firstError) {
                firstError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
            return false;
        }

        return true;
    }

    function validateStep4() {
        console.log('Validating Step 4 - isFreePackage:', isFreePackage, 'totalAmount:', totalAmount);

        if (isFreePackage || totalAmount <= 0) {
            console.log('Free/zero amount package detected, setting payment method to offline');

            const paymentMethodField = document.getElementById('payment_method');
            const paymentOptionField = document.getElementById('payment_option');

            if (paymentMethodField) {
                paymentMethodField.value = 'offline';
                selectedPaymentMethod = 'offline';
            }

            if (paymentOptionField) {
                paymentOptionField.value = 'offline';
            }

            return true;
        }

        const paymentMethodField = document.getElementById('payment_method');
        if (!paymentMethodField || !paymentMethodField.value) {
            alert('⚠️ Please select a payment method.');
            return false;
        }

        selectedPaymentMethod = paymentMethodField.value;

        if (selectedPaymentMethod !== 'sslcommerz') {
            alert('⚠️ Only SSLCommerz payment is available for paid packages.');
            return false;
        }

        return true;
    }

    // ========== FORM FIELD VALIDATION ==========
    function validateField(fieldId) {
        const input = document.getElementById(fieldId);
        const errorElement = document.getElementById(`${fieldId}-error`);
        const icon = document.getElementById(`${fieldId}-icon`);

        if (!input) return false;

        const value = input.value.trim();

        fieldStates[fieldId].touched = true;

        if (value.length === 0) {
            showError(input, errorElement, icon, 'This field is required');
            fieldStates[fieldId].valid = false;
            validateStep3Button();
            return false;
        }

        let isValid = false;
        let errorMessage = '';

        switch (fieldId) {
            case 'name':
                isValid = value.length >= 2;
                errorMessage = 'Name must be at least 2 characters';
                break;
            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                isValid = emailRegex.test(value);
                errorMessage = 'Please enter a valid email address';
                break;
            case 'phone':
                const digits = value.replace(/\D/g, '');
                isValid = digits.length >= 10;
                errorMessage = 'Phone number must have at least 10 digits';
                break;
        }

        if (!isValid) {
            showError(input, errorElement, icon, errorMessage);
            fieldStates[fieldId].valid = false;
            validateStep3Button();
            return false;
        } else {
            showSuccess(input, errorElement, icon);
            fieldStates[fieldId].valid = true;
            validateStep3Button();
            return true;
        }
    }

    function validatePassword() {
        const input = document.getElementById('password');
        const errorElement = document.getElementById('password-error');
        const icon = document.getElementById('password-icon');

        if (!input) return false;

        const value = input.value;

        fieldStates.password.touched = true;

        const strengthMeter = document.getElementById('password-strength-meter');
        if (value.length > 0) {
            if (strengthMeter) strengthMeter.classList.remove('hidden');
            const requirements = document.getElementById('password-requirements');
            if (requirements) requirements.classList.remove('hidden');
        } else {
            if (strengthMeter) strengthMeter.classList.add('hidden');
        }

        if (value.length === 0) {
            showError(input, errorElement, icon, 'Please enter a password');
            fieldStates.password.valid = false;
            validateConfirmPassword();
            validateStep3Button();
            return false;
        }

        const hasMinLength = value.length >= 8;
        const hasUpperCase = /[A-Z]/.test(value);
        const hasNumber = /[0-9]/.test(value);
        const hasSpecialChar = /[!@#$%^&*]/.test(value);

        const criteriaIds = ['criteria-length', 'criteria-uppercase', 'criteria-number', 'criteria-special'];
        const criteriaResults = [hasMinLength, hasUpperCase, hasNumber, hasSpecialChar];

        criteriaIds.forEach((id, index) => {
            const element = document.getElementById(id);
            if (element) {
                element.classList.toggle('valid', criteriaResults[index]);
            }
        });

        let score = 0;
        if (hasMinLength) score++;
        if (hasUpperCase) score++;
        if (hasNumber) score++;
        if (hasSpecialChar) score++;

        const bars = document.querySelectorAll('.strength-bar');
        bars.forEach((bar, index) => {
            bar.classList.remove('active');
            if (index < score) {
                bar.classList.add('active');
            }
        });

        const strengthLabel = document.getElementById('strength-label');

        let strengthText = 'Very Weak';
        let isValid = false;

        if (score <= 1) {
            strengthText = 'Very Weak';
            if (strengthLabel) {
                strengthLabel.textContent = strengthText;
                strengthLabel.style.color = '#ef4444';
            }
            showError(input, errorElement, icon, 'Password is too weak');
            fieldStates.password.valid = false;
        } else if (score === 2) {
            strengthText = 'Weak';
            if (strengthLabel) {
                strengthLabel.textContent = strengthText;
                strengthLabel.style.color = '#f59e0b';
            }
            showError(input, errorElement, icon, 'Password is weak');
            fieldStates.password.valid = false;
        } else if (score === 3) {
            strengthText = 'Good';
            if (strengthLabel) {
                strengthLabel.textContent = strengthText;
                strengthLabel.style.color = '#10b981';
            }
            showSuccess(input, errorElement, icon);
            fieldStates.password.valid = true;
            isValid = true;
        } else if (score === 4) {
            strengthText = 'Strong';
            if (strengthLabel) {
                strengthLabel.textContent = strengthText;
                strengthLabel.style.color = '#059669';
            }
            showSuccess(input, errorElement, icon);
            fieldStates.password.valid = true;
            isValid = true;
        }

        validateConfirmPassword();
        validateStep3Button();
        return isValid;
    }

    function validateConfirmPassword() {
        const input = document.getElementById('password_confirmation');
        const errorElement = document.getElementById('confirm-password-error');
        const icon = document.getElementById('confirm-password-icon');
        const passwordInput = document.getElementById('password');

        if (!input || !passwordInput) return false;

        const password = passwordInput.value;
        const value = input.value;

        fieldStates.password_confirmation.touched = true;

        if (value.length === 0) {
            showError(input, errorElement, icon, 'Please confirm your password');
            fieldStates.password_confirmation.valid = false;
            validateStep3Button();
            return false;
        }

        if (value !== password) {
            showError(input, errorElement, icon, 'Passwords do not match');
            fieldStates.password_confirmation.valid = false;
            validateStep3Button();
            return false;
        } else {
            showSuccess(input, errorElement, icon);
            fieldStates.password_confirmation.valid = true;
            validateStep3Button();
            return true;
        }
    }

    function validateTerms() {
        const input = document.getElementById('terms');
        const errorElement = document.getElementById('terms-error');

        if (!input) return false;

        fieldStates.terms.touched = true;

        if (!input.checked) {
            if (errorElement) errorElement.classList.remove('hidden');
            fieldStates.terms.valid = false;
            validateStep3Button();
            return false;
        } else {
            if (errorElement) errorElement.classList.add('hidden');
            fieldStates.terms.valid = true;
            validateStep3Button();
            return true;
        }
    }

    function validateStep3Button() {
        const allValid = Object.values(fieldStates).every(field => field.valid);
        const continueBtn = document.getElementById('step3-continue');

        if (continueBtn) {
            if (allValid) {
                continueBtn.disabled = false;
                continueBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
                continueBtn.classList.add('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
            } else {
                continueBtn.disabled = true;
                continueBtn.classList.remove('bg-[#318069]', 'hover:bg-[#276854]', 'cursor-pointer');
                continueBtn.classList.add('bg-gray-300', 'cursor-not-allowed');
            }
        }
    }

    function handleStep3Continue() {
        if (validateStep3()) {
            if (isFreePackage || totalAmount <= 0) {
                console.log('Free/zero amount package selected, submitting form with offline payment');
                submitFormDirectly();
            } else {
                nextStep(4);
            }
        }
    }

    function submitFormDirectly() {
        console.log('Submitting form for free/zero amount package');

        updateSummary();

        const paymentMethodField = document.getElementById('payment_method');
        const paymentOptionField = document.getElementById('payment_option');

        if (paymentMethodField) paymentMethodField.value = 'offline';
        if (paymentOptionField) paymentOptionField.value = 'offline';

        const submitBtn = document.getElementById('step3-continue');
        if (submitBtn) {
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2"></span>Creating Account...';
            submitBtn.disabled = true;
        }

        const form = document.getElementById('doctorRegistrationForm');
        if (form) {
            form.submit();
        }
    }

    function showPasswordRequirements() {
        const requirements = document.getElementById('password-requirements');
        if (requirements) requirements.classList.remove('hidden');
    }

    function hidePasswordRequirements() {
        const passwordInput = document.getElementById('password');
        const requirements = document.getElementById('password-requirements');

        if (passwordInput && requirements) {
            if (passwordInput.value.length === 0) {
                requirements.classList.add('hidden');
            }
        }
    }

    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        const icon = field.nextElementSibling?.querySelector('i');
        if (!icon) return;

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('ri-eye-line');
            icon.classList.add('ri-eye-off-line');
        } else {
            field.type = 'password';
            icon.classList.remove('ri-eye-off-line');
            icon.classList.add('ri-eye-line');
        }

        if (fieldId === 'password') {
            validatePassword();
            validateConfirmPassword();
        } else if (fieldId === 'password_confirmation') {
            validateConfirmPassword();
        }
    }

    function showError(input, errorElement, icon, message) {
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
        if (input) {
            input.classList.remove('border-green-500', 'border-gray-300');
            input.classList.add('border-red-500');
        }

        if (icon) {
            icon.className = 'validation-icon error';
            icon.innerHTML = '<i class="ri-close-line text-xl w-5 h-5 flex items-center justify-center"></i>';
            icon.style.display = 'block';
        }
    }

    function showSuccess(input, errorElement, icon) {
        if (errorElement) {
            errorElement.classList.add('hidden');
        }
        if (input) {
            input.classList.remove('border-red-500', 'border-gray-300');
            input.classList.add('border-green-500');
        }

        if (icon) {
            icon.className = 'validation-icon success';
            icon.innerHTML = '<i class="ri-check-line text-xl w-5 h-5 flex items-center justify-center"></i>';
            icon.style.display = 'block';
        }
    }

    // ========== PAYMENT FUNCTIONS ==========
    function selectPaymentMethod(method) {
        selectedPaymentMethod = method;

        const paymentMethodField = document.getElementById('payment_method');
        if (paymentMethodField) paymentMethodField.value = method;

        document.querySelectorAll('.payment-method-card').forEach(card => {
            card.classList.remove('selected');
        });

        if (event && event.currentTarget) {
            event.currentTarget.classList.add('selected');
        }

        hideAllPaymentInstructions();

        if (method === 'sslcommerz') {
            const sslInstructions = document.getElementById('sslInstructions');
            if (sslInstructions) sslInstructions.classList.remove('hidden');
        }

        updateSummary();
    }

    function hideAllPaymentInstructions() {
        const sslInstructions = document.getElementById('sslInstructions');

        if (sslInstructions) sslInstructions.classList.add('hidden');
    }

    function updatePaymentUI() {
        const paymentMethodCards = document.querySelectorAll('.payment-method-card');
        const sslInstructions = document.getElementById('sslInstructions');
        const completeRegistrationBtn = document.getElementById('complete-registration-btn');
        const paymentOptionField = document.getElementById('payment_option');
        const paymentMethodField = document.getElementById('payment_method');

        console.log('Updating payment UI - isFreePackage:', isFreePackage, 'totalAmount:', totalAmount);

        if (isFreePackage || totalAmount <= 0) {
            console.log('Hiding payment options for free/zero amount package');

            paymentMethodCards.forEach(card => {
                card.style.display = 'none';
            });

            if (sslInstructions) sslInstructions.classList.add('hidden');

            const paymentSection = document.querySelector('.mb-8');
            if (paymentSection) {
                let offlineMessage = document.getElementById('offline-payment-message');
                if (!offlineMessage) {
                    offlineMessage = document.createElement('div');
                    offlineMessage.id = 'offline-payment-message';
                    offlineMessage.className = 'border-2 border-blue-200 bg-blue-50 rounded-xl p-6 mt-6';
                    offlineMessage.innerHTML = `
                        <div class="flex items-start gap-3">
                            <i class="ri-information-line text-blue-600 text-xl"></i>
                            <div>
                                <h6 class="font-bold text-gray-900 mb-2">Free Package Registration</h6>
                                <p class="text-sm text-gray-700">No payment required for free packages. Your account will be created immediately after registration.</p>
                            </div>
                        </div>
                    `;
                    paymentSection.parentNode.insertBefore(offlineMessage, paymentSection.nextSibling);
                } else {
                    offlineMessage.classList.remove('hidden');
                }
            }

            if (completeRegistrationBtn) {
                completeRegistrationBtn.innerHTML = `
                    <i class="ri-check-line w-5 h-5 inline-flex items-center justify-center mr-2"></i>
                    Complete Free Registration
                `;
            }

            if (paymentOptionField) paymentOptionField.value = 'offline';
            if (paymentMethodField) {
                paymentMethodField.value = 'offline';
                selectedPaymentMethod = 'offline';
            }

        } else {
            console.log('Showing payment options for paid package');

            const paypalCard = document.querySelector('[onclick*="paypal"]');
            if (paypalCard) {
                paypalCard.style.display = 'none';
            }

            if (sslInstructions) sslInstructions.classList.remove('hidden');

            selectPaymentMethod('sslcommerz');

            const offlineMessage = document.getElementById('offline-payment-message');
            if (offlineMessage) {
                offlineMessage.classList.add('hidden');
            }

            if (completeRegistrationBtn) {
                completeRegistrationBtn.innerHTML = `
                    <i class="ri-lock-line w-5 h-5 inline-flex items-center justify-center mr-2"></i>
                    Complete Registration with Payment
                `;
            }

            if (paymentOptionField) paymentOptionField.value = 'online';
        }
    }

    // ========== COUPON FUNCTIONS ==========
    async function loadAvailableCoupons() {
        try {
            const response = await fetch('/api/coupons/available');
            if (!response.ok) {
                throw new Error('Failed to fetch coupons');
            }
            const coupons = await response.json();

            const couponsList = document.getElementById('availableCouponsList');
            const loading = document.getElementById('couponsLoading');

            if (loading) loading.classList.add('hidden');

            if (!couponsList) return;

            if (!coupons || coupons.length === 0) {
                couponsList.innerHTML =
                    '<p class="text-center text-gray-600 py-4">No coupons available at the moment.</p>';
                return;
            }

            let couponsHtml = '';
            coupons.forEach(coupon => {
                couponsHtml += `
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-gray-900">${coupon.code || ''}</h4>
                                <p class="text-sm text-gray-600 mt-1">${coupon.description || 'No description available'}</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">Min: ${formatPrice(coupon.min_amount || 0)}</span>
                                    ${coupon.max_discount ? `<span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">Max: ${formatPrice(coupon.max_discount)}</span>` : ''}
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="button" onclick="applyCouponFromList('${coupon.code || ''}')" class="text-sm text-[#318069] hover:text-[#276854] font-semibold">
                                    Apply
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            couponsList.innerHTML = couponsHtml;
        } catch (error) {
            console.error('Error loading coupons:', error);
            const couponsList = document.getElementById('availableCouponsList');
            const loading = document.getElementById('couponsLoading');

            if (loading) loading.classList.add('hidden');
            if (couponsList) {
                couponsList.innerHTML =
                    '<p class="text-center text-red-600 py-4">Error loading coupons. Please try again.</p>';
            }
        }
    }

    function applyCouponFromList(code) {
        const couponInput = document.getElementById('coupon_code');
        if (couponInput) {
            couponInput.value = code;
            applyCoupon();
            hideModal('couponsModal');
        }
    }

    async function applyCoupon() {
        const couponInput = document.getElementById('coupon_code');
        const statusDiv = document.getElementById('couponStatus');
        const applyBtn = document.getElementById('applyCouponBtn');

        if (!couponInput || !statusDiv || !applyBtn) return;

        const couponCode = couponInput.value.trim();

        if (!couponCode) {
            statusDiv.innerHTML =
                '<div class="border-2 border-amber-200 bg-amber-50 rounded-xl p-3"><div class="flex items-center gap-2"><i class="ri-alert-line text-amber-600"></i><span class="text-sm text-amber-800">Please enter a coupon code</span></div></div>';
            return;
        }

        if (isFreePackage) {
            statusDiv.innerHTML =
                '<div class="border-2 border-amber-200 bg-amber-50 rounded-xl p-3"><div class="flex items-center gap-2"><i class="ri-information-line text-amber-600"></i><span class="text-sm text-amber-800">Coupons are not applicable for free packages</span></div></div>';
            return;
        }

        applyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Applying...';
        applyBtn.disabled = true;

        try {
            const subtotal = calculateSubtotal();

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            const response = await fetch('/api/coupons/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    code: couponCode,
                    amount: subtotal
                })
            });

            const result = await response.json();

            if (result.valid) {
                statusDiv.innerHTML = `<div class="border-2 border-green-200 bg-green-50 rounded-xl p-3">
                    <div class="flex items-center gap-2">
                        <i class="ri-checkbox-circle-line text-green-600 text-lg"></i>
                        <div>
                            <span class="text-sm font-semibold text-green-800">Coupon Applied!</span>
                            <p class="text-xs text-green-700 mt-1">${result.message || 'Discount applied successfully'}</p>
                            <p class="text-xs font-bold text-green-800 mt-1">Discount: ${formatPrice(result.discount_amount || 0)}</p>
                        </div>
                    </div>
                </div>`;

                appliedCoupon = result.coupon;
                couponDiscount = result.discount_amount || 0;

                const selectedCouponField = document.getElementById('selectedCoupon');
                if (selectedCouponField) {
                    selectedCouponField.value = couponCode;
                }

                couponInput.classList.remove('border-red-500', 'border-gray-300');
                couponInput.classList.add('border-green-500');

            } else {
                statusDiv.innerHTML = `<div class="border-2 border-red-200 bg-red-50 rounded-xl p-3">
                    <div class="flex items-center gap-2">
                        <i class="ri-close-circle-line text-red-600 text-lg"></i>
                        <div>
                            <span class="text-sm font-semibold text-red-800">Invalid Coupon</span>
                            <p class="text-xs text-red-700 mt-1">${result.message || 'Coupon code is invalid or expired'}</p>
                        </div>
                    </div>
                </div>`;

                couponInput.value = '';

                appliedCoupon = null;
                couponDiscount = 0;

                const selectedCouponField = document.getElementById('selectedCoupon');
                if (selectedCouponField) {
                    selectedCouponField.value = '';
                }

                couponInput.classList.remove('border-green-500', 'border-gray-300');
                couponInput.classList.add('border-red-500');
            }

            updateSummary();

        } catch (error) {
            console.error('Error applying coupon:', error);
            statusDiv.innerHTML =
                '<div class="border-2 border-red-200 bg-red-50 rounded-xl p-3"><div class="flex items-center gap-2"><i class="ri-error-warning-line text-red-600"></i><span class="text-sm text-red-800">Error applying coupon. Please try again.</span></div></div>';

            couponInput.value = '';

            appliedCoupon = null;
            couponDiscount = 0;

            const selectedCouponField = document.getElementById('selectedCoupon');
            if (selectedCouponField) {
                selectedCouponField.value = '';
            }
        } finally {
            applyBtn.innerHTML = 'Apply';
            applyBtn.disabled = false;
        }
    }

    function calculateSubtotal() {
        const selectedPackage = document.querySelector(`[data-package-id="${selectedPackageId}"]`);
        let packagePrice = 0;

        if (selectedPackage) {
            if (selectedBillingCycle === 'yearly') {
                packagePrice = parseFloat(selectedPackage.dataset.packagePriceYearly || 0);
            } else {
                packagePrice = parseFloat(selectedPackage.dataset.packagePriceMonthly || 0);
            }
        }

        let domainPrice = 0;
        if (selectedDomainType === 'new') {
            const extensionSelect = document.getElementById('new_domain_extension');
            domainPrice = getSelectedDomainPriceUsd(extensionSelect?.value || '.com');
        }

        return packagePrice + domainPrice;
    }

    // ========== SUMMARY UPDATE ==========
    function updateSummary() {
        const selectedPackage = document.querySelector(`[data-package-id="${selectedPackageId}"]`);
        let packagePrice = 0;
        let packageName = 'No Package Selected';

        if (selectedPackage) {
            const nameElement = selectedPackage.querySelector('h3');
            packageName = nameElement?.textContent || 'Basic Plan';

            if (selectedBillingCycle === 'yearly') {
                packagePrice = parseFloat(selectedPackage.dataset.packagePriceYearly || 0);
            } else {
                packagePrice = parseFloat(selectedPackage.dataset.packagePriceMonthly || 0);
            }
        }

        const summaryPackage = document.getElementById('summaryPackage');
        const summaryBilling = document.getElementById('summaryBilling');
        const selectedPackageName = document.getElementById('selected-package-name');

        if (summaryPackage) summaryPackage.textContent = packageName;
        if (summaryBilling) summaryBilling.textContent = selectedBillingCycle.charAt(0).toUpperCase() +
            selectedBillingCycle.slice(1) + ' Subscription';
        if (selectedPackageName) selectedPackageName.textContent = packageName;

        let domainPrice = 0;
        let domainUrl = 'yourname.' + APP_DOMAIN;

        if (selectedDomainType === 'subdomain') {
            const subdomainInput = document.getElementById('subdomain_name');
            const subdomain = subdomainInput?.value || 'yourname';
            domainUrl = subdomain + '.' + APP_DOMAIN;
            domainPrice = 0;
        } else if (selectedDomainType === 'new') {
            const domainInput = document.getElementById('new_domain_name');
            const extensionSelect = document.getElementById('new_domain_extension');
            const domainName = domainInput?.value || 'yourclinic';
            const extension = extensionSelect?.value || '.com';
            domainUrl = domainName + extension;
            domainPrice = getSelectedDomainPriceUsd(extension);
        } else if (selectedDomainType === 'existing') {
            const existingDomainInput = document.getElementById('existing_domain');
            const existingDomain = existingDomainInput?.value || 'yourdomain.com';
            domainUrl = existingDomain;
            domainPrice = 0;
        }

        const selectedDomain = document.getElementById('selected-domain');
        if (selectedDomain) selectedDomain.textContent = domainUrl;

        const subtotal = packagePrice + domainPrice;
        const discount = couponDiscount;
        totalAmount = Math.max(0, subtotal - discount);

        const summaryElements = {
            'summaryPackageFee': formatPrice(packagePrice),
            'summaryDomainFee': formatPrice(domainPrice),
            'summaryDiscount': '- ' + formatPrice(discount),
            'summaryTotalAmount': formatPrice(totalAmount),
            'summaryTotal': formatPrice(totalAmount),
            'package-total-amount': formatPrice(totalAmount)
        };

        Object.entries(summaryElements).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) element.textContent = value;
        });

        const hiddenFields = {
            'finalPackagePrice': packagePrice.toFixed(2),
            'finalDomainPrice': domainPrice.toFixed(2),
            'finalDiscount': discount.toFixed(2),
            'finalTotal': totalAmount.toFixed(2),
            'selectedBillingCycle': selectedBillingCycle,
            'selectedSubdomain': domainUrl
        };

        Object.entries(hiddenFields).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) element.value = value;
        });

        const step3ContinueText = document.getElementById('step3-continue-text');
        if (step3ContinueText) {
            if (isFreePackage || totalAmount <= 0) {
                step3ContinueText.textContent = 'Complete Registration';
            } else {
                step3ContinueText.textContent = 'Continue to Payment';
            }
        }

        console.log('Updated summary:', {
            package_name: packageName,
            domain_url: domainUrl,
            package_price: packagePrice,
            domain_price: domainPrice,
            discount_amount: discount,
            total_amount: totalAmount,
            billing_cycle: selectedBillingCycle,
            is_free_package: isFreePackage
        });
    }

    // ========== MODAL FUNCTIONS ==========
    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function hideModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    function showAvailableCoupons() {
        showModal('couponsModal');
    }

    function showTermsModal() {
        showModal('termsModal');
    }

    function showPrivacyModal() {
        showModal('privacyModal');
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
                preview.classList.remove('hidden');
            }
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        reader.readAsDataURL(input.files[0]);

        const photoError = document.getElementById('photo-error');
        if (photoError) photoError.classList.add('hidden');
        fieldStates.photo.valid = true;
        validateStep3Button();
    }

    // Form submission validation
    document.getElementById('doctorRegistrationForm')?.addEventListener('submit', function(e) {
        console.log('Form submitting...');
        console.log('Form state:', {
            isFreePackage,
            totalAmount,
            selectedPaymentMethod,
            payment_method: document.getElementById('payment_method')?.value,
            payment_option: document.getElementById('payment_option')?.value
        });

        updateSummary();

        if (isFreePackage || totalAmount <= 0) {
            if (!validateStep1() || !validateStep2() || !validateStep3()) {
                e.preventDefault();
                return false;
            }

            const paymentMethodField = document.getElementById('payment_method');
            const paymentOptionField = document.getElementById('payment_option');

            if (paymentMethodField) paymentMethodField.value = 'offline';
            if (paymentOptionField) paymentOptionField.value = 'offline';

        } else {
            if (!validateStep4()) {
                e.preventDefault();
                return false;
            }
        }

        const submitBtn = document.getElementById('complete-registration-btn') || document.getElementById('step3-continue');
        if (submitBtn) {
            if (isFreePackage || totalAmount <= 0) {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating Account...';
            } else {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing Payment...';
            }
            submitBtn.disabled = true;
        }

        return true;
    });
</script>
@endpush
