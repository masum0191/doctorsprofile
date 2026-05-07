@extends('layouts.sass')
@section('title', 'Find Doctors Nearby')

@php
    use Illuminate\Support\Str;
    use App\Models\Specialty;
    use App\Models\User;
    use App\Models\DoctorPost;
    $specialtyModel = Specialty::query();
    $featuredDoctors = User::where('role', 'tenant')
    ->where('status', 1)
    ->where('feature', 1)
    ->latest()
    ->take(8)
    ->get();
    $doctor = User::first();
    $posts = DoctorPost::where('is_published',1)->paginate(9);
@endphp

@section('content')

<!-- Hero Section - Fully Responsive -->
<section class="relative min-h-[600px] sm:min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 via-white to-teal-50/30 pt-12 sm:pt-16">
    <!-- Background Elements - Adjusted for mobile -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-[#318069]/10 to-[#318069]/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-gradient-to-tr from-[#FFC107]/5 to-[#FFC107]/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-[#318069]/3 rounded-full blur-2xl hidden sm:block"></div>
        <div class="absolute top-20 right-1/4 w-32 h-32 border-2 border-[#318069]/10 rounded-2xl rotate-12 hidden lg:block"></div>
        <div class="absolute bottom-40 left-1/3 w-24 h-24 border-2 border-[#FFC107]/10 rounded-full hidden md:block"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(49,128,105,0.04)_1px,transparent_1px),linear-gradient(90deg,rgba(49,128,105,0.04)_1px,transparent_1px)] bg-[size:64px_64px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-6xl mx-auto px-4 sm:px-6 text-center py-12 sm:py-16 md:py-20">
        <!-- Heading - Responsive text sizes -->
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold text-gray-900 mb-3 custom-title-line px-2">
            Find & Connect with Top <br class="hidden xs:block"> Medical <span class="text-[#318069]">Experts</span>
        </h1>

        <p class="text-base sm:text-lg md:text-xl text-gray-600 mb-6 sm:mb-8 max-w-3xl mx-auto leading-relaxed px-4">
            Book appointments instantly with trusted doctors, specialists.
        </p>

        <!-- Search Form - Fully Responsive -->
      <form id="searchForm" class="px-2 sm:px-4">
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] p-4 sm:p-6 md:p-8 max-w-5xl mx-auto border border-[#318069]/20">
                <!-- Mobile: Stack vertically, Desktop: Grid layout -->
                <div class="flex flex-col md:grid md:grid-cols-12 gap-3 md:gap-4">
                    <!-- Search Input - Full width on mobile -->
                    <div class="w-full md:col-span-5">
                        <div class="flex items-center bg-gray-100 rounded-xl sm:rounded-2xl px-3 sm:px-5 py-3 sm:py-4 border-2 border-transparent focus-within:border-[#318069] focus-within:bg-white transition-all">
                            <i class="ri-search-line text-xl sm:text-2xl text-gray-400 mr-2 sm:mr-3 flex-shrink-0"></i>
                            <input id="doctor-search" placeholder="Search doctors, specialties..."
                                class="flex-1 outline-none bg-transparent text-sm sm:text-base text-gray-700 placeholder-gray-400 min-w-0"
                                type="text" value="">
                        </div>
                    </div>

                    <!-- Specialty Select - Full width on mobile -->
                    <div class="w-full md:col-span-3">
                        <div class="flex items-center bg-gray-100 rounded-xl sm:rounded-2xl px-3 sm:px-5 py-3 sm:py-4 border-2 border-transparent focus-within:border-[#318069] focus-within:bg-white transition-all cursor-pointer relative">
                            <i class="ri-stethoscope-line text-xl sm:text-2xl text-gray-400 mr-2 sm:mr-3 flex-shrink-0"></i>
                            <select id="specialty"
                                class="flex-1 outline-none appearance-none bg-transparent text-sm sm:text-base text-gray-700 cursor-pointer pr-6">
                                <option value="">All specialty</option>
                                @foreach ($specialtyModel->get() as $specialty)
                                    <option value="{{ $specialty->name }}">{{ Str::title($specialty->name) }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute right-3 sm:right-4 flex items-center text-gray-500">
                                <i class="ri-arrow-down-s-line text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Location Selector - Full width on mobile -->
                    <div class="w-full md:col-span-3 relative">
                        <div id="locationSelector" class="flex items-center bg-gray-100 rounded-xl sm:rounded-2xl px-3 sm:px-5 py-3 sm:py-4 border-2 border-transparent focus-within:border-[#318069] focus-within:bg-white transition-all cursor-pointer">
                            <i class="ri-map-pin-line text-xl sm:text-2xl text-gray-400 mr-2 sm:mr-3 flex-shrink-0"></i>
                            <div class="flex-1 flex items-center justify-between min-w-0">
                                <span id="locationText" class="text-sm sm:text-base text-gray-700 truncate">Detecting...</span>
                                <i class="ri-arrow-down-s-line text-xl text-gray-500 flex-shrink-0 ml-2"></i>
                            </div>
                            <input type="hidden" id="latInput" value="">
                            <input type="hidden" id="lngInput" value="">
                        </div>

                        <!-- Location Modal - Responsive positioning -->
                        <div id="locationModal" class="hidden fixed sm:absolute top-1/2 left-1/2 sm:top-full sm:left-0 transform -translate-x-1/2 -translate-y-1/2 sm:translate-x-0 sm:translate-y-0 w-[calc(100vw-2rem)] sm:w-[350px] max-w-[400px] mt-0 sm:mt-2 bg-white rounded-2xl shadow-xl border border-gray-200 z-[99999] p-4 sm:p-6">
    
    <div class="flex justify-between items-center mb-4 sm:hidden">
        <h4 class="font-semibold text-gray-800">Select Location</h4>
        <button type="button" onclick="closeLocationModal()" class="p-2">
            <i class="ri-close-line text-xl"></i>
        </button>
    </div>

    <h4 class="font-semibold text-gray-800 mb-4 hidden sm:block">Select Your Location</h4>

    <!-- Country Select with Search -->
    <div class="mb-4">
        <label class="text-xs font-medium text-start text-gray-500 mb-1 block">Country</label>
        <div class="relative">
            <i class="ri-global-line text-xl text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2 z-10"></i>
            <select id="countrySelect" class="w-full outline-none bg-gray-100 rounded-xl pl-12 pr-10 py-3 text-sm sm:text-base text-gray-700 appearance-none">
                <option value="">Select country...</option>
            </select>
            <div class="pointer-events-none absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500">
                <i class="ri-arrow-down-s-line text-xl"></i>
            </div>
        </div>
    </div>

    <!-- City Select with Search -->
    <div class="mb-4">
        <label class="text-xs text-start font-medium text-gray-500 mb-1 block">City / District</label>
        <div class="relative">
            <i class="ri-map-pin-line text-xl text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2 z-10"></i>
            <select id="citySelect" class="w-full outline-none bg-gray-100 rounded-xl pl-12 pr-10 py-3 text-sm sm:text-base text-gray-700 appearance-none" disabled>
                <option value="">Select country first</option>
            </select>
            <div class="pointer-events-none absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500">
                <i class="ri-arrow-down-s-line text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Manual City Input (for Other option) -->
    <div id="manualCityContainer" class="mt-3 hidden">
        <label class="text-xs text-start font-medium text-gray-500 mb-1 block">Enter City Name</label>
        <input type="text" id="cityInput" class="w-full form-input rounded-xl border-gray-300 focus:border-[#318069] focus:ring-[#318069] text-sm sm:text-base p-3" placeholder="Enter city name">
    </div>

    <!-- Radius Select -->
    <div class="mb-4">
        <label class="text-xs text-start font-medium text-gray-500 mb-1 block">Search Radius</label>
        <div class="relative">
            <i class="ri-gps-line text-xl text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2 z-10"></i>
            <select id="radiusSelect" class="w-full outline-none bg-gray-100 rounded-xl pl-12 pr-10 py-3 text-[14px] text-gray-700 appearance-none">
                <option value="10">Within 10 km</option>
                <option value="25" selected>Within 25 km</option>
                <option value="50">Within 50 km</option>
                <option value="100">Within 100 km</option>
            </select>
            <div class="pointer-events-none absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500">
                <i class="ri-arrow-down-s-line text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Map -->
    <div class="mb-4">
        <label class="text-xs font-medium text-gray-500 mb-1 block">Map View</label>
        <div id="map" class="h-40 sm:h-48 rounded-xl overflow-hidden border border-gray-300"></div>
    </div>

    <!-- Actions -->
    <div class="mt-4 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3">
        <button type="button" id="useCurrentLocation" class="text-sm font-medium text-[#318069] hover:text-[#276854] py-2 sm:py-0 transition-colors text-left">
            <i class="ri-crosshair-line mr-1"></i>Current Location
        </button>
        <div class="flex gap-2">
            <button type="button" id="cancelLocationBtn" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium transition-colors">
                Cancel
            </button>
            <button type="button" id="applyLocation" class="px-4 py-2 bg-[#318069] text-white rounded-lg hover:bg-[#276854] text-sm font-medium transition-colors">
                Apply
            </button>
        </div>
    </div>
</div>
                    </div>

                    <!-- Search Button - Full width on mobile -->
                    <div class="w-full md:col-span-1">
                        <button type="submit"
                            class="w-full h-full bg-[#318069] hover:bg-[#276854] text-white rounded-xl sm:rounded-2xl font-medium transition-all hover:shadow-lg flex items-center justify-center whitespace-nowrap px-6 py-3 md:py-0">
                            <i class="ri-search-line text-xl sm:text-2xl"></i>
                            <span class="ml-2 md:hidden">Search</span>
                        </button>
                    </div>
                </div>

                <!-- Popular Searches - Responsive wrapping -->
                <div class="mt-6 sm:mt-8 flex flex-wrap gap-2 sm:gap-3 justify-center items-center px-2">
                    <span class="text-xs sm:text-sm font-medium text-gray-500 w-full sm:w-auto text-center sm:text-left mb-1 sm:mb-0">Popular:</span>
                    <button type="button" data-filter="top_rated"
                        class="filter-badge px-3 sm:px-5 py-1.5 sm:py-2 bg-gray-50 hover:bg-[#318069] hover:text-white text-gray-700 rounded-full text-xs sm:text-sm font-medium transition-all hover:shadow-md whitespace-nowrap cursor-pointer border border-gray-200 hover:border-[#318069]">
                        Top Rated
                    </button>
                    <button type="button" data-filter="available_today"
                        class="filter-badge px-3 sm:px-5 py-1.5 sm:py-2 bg-gray-50 hover:bg-[#318069] hover:text-white text-gray-700 rounded-full text-xs sm:text-sm font-medium transition-all hover:shadow-md whitespace-nowrap cursor-pointer border border-gray-200 hover:border-[#318069]">
                        Available Today
                    </button>
                    <button type="button" data-filter="virtual_visits"
                        class="filter-badge px-3 sm:px-5 py-1.5 sm:py-2 bg-gray-50 hover:bg-[#318069] hover:text-white text-gray-700 rounded-full text-xs sm:text-sm font-medium transition-all hover:shadow-md whitespace-nowrap cursor-pointer border border-gray-200 hover:border-[#318069]">
                        Virtual Visits
                    </button>
                    <button type="button" id="near-me"
                        class="px-3 sm:px-5 py-1.5 sm:py-2 bg-gray-50 hover:bg-[#318069] hover:text-white text-gray-700 rounded-full text-xs sm:text-sm font-medium transition-all hover:shadow-md whitespace-nowrap cursor-pointer border border-gray-200 hover:border-[#318069]">
                        Near Me
                    </button>
                    <button type="button" data-filter="accepts_insurance"
                        class="filter-badge px-3 sm:px-5 py-1.5 sm:py-2 bg-gray-50 hover:bg-[#318069] hover:text-white text-gray-700 rounded-full text-xs sm:text-sm font-medium transition-all hover:shadow-md whitespace-nowrap cursor-pointer border border-gray-200 hover:border-[#318069]">
                        Accepts Insurance
                    </button>
                </div>
            </div>
        </form>

        <!-- Stats - Responsive grid -->
        <div class="mt-12 sm:mt-16 md:mt-20 grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 md:gap-8 max-w-4xl mx-auto">
            <div class="text-center group">
                <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-[#318069] mb-1 sm:mb-2 group-hover:scale-110 transition-transform">5,000+</div>
                <div class="text-xs sm:text-sm text-gray-600 font-medium">Verified Doctors</div>
            </div>
            <div class="text-center group">
                <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-[#318069] mb-1 sm:mb-2 group-hover:scale-110 transition-transform">50+</div>
                <div class="text-xs sm:text-sm text-gray-600 font-medium">Medical Specialties</div>
            </div>
            <div class="text-center group">
                <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-[#318069] mb-1 sm:mb-2 group-hover:scale-110 transition-transform">100K+</div>
                <div class="text-xs sm:text-sm text-gray-600 font-medium">Happy Patients</div>
            </div>
            <div class="text-center group">
                <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-[#318069] mb-1 sm:mb-2 group-hover:scale-110 transition-transform">24/7</div>
                <div class="text-xs sm:text-sm text-gray-600 font-medium">Support Available</div>
            </div>
        </div>
    </div>
</section>

<!-- Doctors Section - Fully Responsive -->
<section id="doctors" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 sm:mb-8 gap-3">
        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Available Doctors</h2>
        <div id="doctorCount" class="text-md font-semibold text-[#318069]"></div>
    </div>

    <div id="doctorGrid">
            <!-- Doctors will be loaded here dynamically -->
            <div class="col-span-1 md:col-span-2 lg:col-span-3">
                <div class="text-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#318069] mx-auto mb-4"></div>
                    <p class="text-gray-600">Finding doctors near you...</p>
                </div>
            </div>
        </div>
         <!-- View All Button -->
        <div class="text-center mt-8">
            <a href="/all-doctors" class="inline-block w-full sm:w-auto">
                <button class="w-full sm:w-auto bg-gray-100 hover:bg-[#318069] hover:text-white text-gray-700 px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-medium transition-colors whitespace-nowrap text-sm sm:text-base">
                    View All Doctors
                    <i class="ri-arrow-right-line ml-2"></i>
                </button>
            </a>
        </div>
</section>

<!-- CTA Section - Fully Responsive -->
<section class="px-4 sm:px-6">
    <div class="container max-w-5xl pt-8 sm:pt-10 pb-12 mx-auto">
        <div class="text-center">
            <div class="bg-gradient-to-br from-[#318069] to-teal-700 rounded-2xl sm:rounded-3xl p-6 sm:p-8 md:p-12 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white rounded-full blur-3xl"></div>
                </div>
                <div class="relative z-10">
                    <h3 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-3 sm:mb-4 px-2">Create Doctor Website, Register with us</h3>
                    <p class="text-sm sm:text-base md:text-lg lg:text-xl text-white/90 mb-6 sm:mb-8 max-w-2xl mx-auto px-4">
                        Join thousands of patients who trust us for their healthcare needs.
                    </p>
                    <div class="flex flex-wrap gap-3 sm:gap-4 justify-center px-4">
                        <a href="{{ url('/package') }}" class="w-full sm:w-auto">
                            <button class="w-full sm:w-auto bg-white hover:bg-gray-50 text-[#318069] px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-bold transition-all hover:shadow-xl whitespace-nowrap inline-flex items-center justify-center gap-2 text-sm sm:text-base">
                                Create Doctor Website
                                <i class="ri-arrow-right-line"></i>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Section - Fully Responsive -->
<section class="pb-10 bg-gradient-to-b from-white to-gray-50 px-4 sm:px-6">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-5 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-3 sm:mb-4 px-2">Why Choose Doctor Profiles?</h2>
            <p class="text-sm sm:text-base md:text-lg text-gray-600 max-w-2xl mx-auto px-4">
                We make healthcare accessible, convenient, and trustworthy for everyone
            </p>
        </div>

        <!-- Features Grid - Responsive -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
            <!-- Feature 1 -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 group cursor-pointer">
                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform duration-300" style="background-color: rgba(49, 128, 105, 0.082);">
                    <i class="ri-shield-check-line text-2xl sm:text-3xl" style="color: rgb(49, 128, 105);"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Verified Doctors</h3>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">All doctors are thoroughly verified and licensed healthcare professionals.</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 group cursor-pointer">
                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform duration-300" style="background-color: rgba(255, 193, 7, 0.082);">
                    <i class="ri-calendar-check-line text-2xl sm:text-3xl" style="color: rgb(255, 193, 7);"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Easy Appointment Booking</h3>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">Book appointments instantly with just a few clicks.</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 group cursor-pointer">
                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform duration-300" style="background-color: rgba(49, 128, 105, 0.082);">
                    <i class="ri-time-line text-2xl sm:text-3xl" style="color: rgb(49, 128, 105);"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Real-time Availability</h3>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">See doctor availability in real-time and choose convenient slots.</p>
            </div>

            <!-- Feature 4 -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 group cursor-pointer">
                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform duration-300" style="background-color: rgba(255, 193, 7, 0.082);">
                    <i class="ri-heart-pulse-line text-2xl sm:text-3xl" style="color: rgb(255, 193, 7);"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Trusted Healthcare</h3>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">Access a network of trusted healthcare professionals.</p>
            </div>
        </div>

        <!-- Trust Badges - Responsive -->
        <div class="mt-12 sm:mt-16 bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 md:p-12 shadow-sm border border-gray-100">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8 text-center">
                <div>
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[#318069]/10 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <i class="ri-shield-star-line text-2xl sm:text-3xl text-[#318069]"></i>
                    </div>
                    <h4 class="font-bold text-base sm:text-lg text-gray-900 mb-1 sm:mb-2">100% Secure</h4>
                    <p class="text-xs sm:text-sm text-gray-600">Your data is protected</p>
                </div>
                <div>
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[#FFC107]/10 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <i class="ri-customer-service-2-line text-2xl sm:text-3xl text-[#FFC107]"></i>
                    </div>
                    <h4 class="font-bold text-base sm:text-lg text-gray-900 mb-1 sm:mb-2">24/7 Support</h4>
                    <p class="text-xs sm:text-sm text-gray-600">Always here to help</p>
                </div>
                <div>
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[#318069]/10 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <i class="ri-smartphone-line text-2xl sm:text-3xl text-[#318069]"></i>
                    </div>
                    <h4 class="font-bold text-base sm:text-lg text-gray-900 mb-1 sm:mb-2">Mobile Friendly</h4>
                    <p class="text-xs sm:text-sm text-gray-600">Access from any device</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Browse by Specialty - Fully Responsive -->
<section class="bg-gradient-to-b from-gray-50 to-white relative overflow-hidden py-12 sm:py-10 md:py-10 px-4 sm:px-6">
    <div class="absolute top-20 left-10 w-72 h-72 bg-[#318069]/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 right-10 w-72 h-72 bg-[#FFC107]/5 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 bg-white border border-[#318069]/20 rounded-full px-4 sm:px-5 py-1.5 sm:py-2 mb-4 sm:mb-6 shadow-sm">
                <i class="ri-hospital-line text-[#318069] text-sm sm:text-base"></i>
                <span class="text-xs sm:text-sm font-semibold text-[#318069]">Medical Specialties</span>
            </div>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">Browse by <span class="text-[#318069]">Specialty</span></h2>
            <p class="text-sm sm:text-base md:text-lg text-gray-600 max-w-2xl mx-auto px-4">
                Find expert doctors across all medical specialties in your area.
            </p>
        </div>

        <!-- Specialty Grid - Responsive -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4 md:gap-6">
            @php
                $colors = ['blue', 'emerald', 'rose', 'amber', 'purple', 'teal', 'indigo', 'pink', 'cyan', 'orange', 'lime', 'fuchsia'];
            @endphp

            @foreach ($specialtyModel->get()->take(12) as $index => $item)
                @php $color = $colors[$index % count($colors)]; @endphp
@php $doctors_count = User::where('role','tenant')
        ->where('status',1)
        ->whereJsonContains('specialization', $item->name)
        ->count();
        @endphp
                <a href="{{ url('specialty/' . $item->id) }}" class="group block">
                    <div class="rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-{{ $color }}-200 bg-{{ $color }}-500/10 transition-all duration-300 hover:-translate-y-2 h-full flex flex-col items-center text-center">
                        <div class="w-12 h-12 sm:w-14 md:w-16 bg-white rounded-xl sm:rounded-2xl flex items-center justify-center mb-3 sm:mb-4 group-hover:scale-110 transition-transform shadow-sm">
                            <i class="ri-{{ $item->icon }}-line text-xl sm:text-2xl md:text-3xl text-{{ $color }}-600"></i>
                        </div>
                        <h3 class="text-xs sm:text-sm md:text-base font-bold text-gray-900 mb-1 sm:mb-2 line-clamp-2">
                            {{ $item->name }}
                        </h3>
                        <p class="text-xs text-gray-600">
                            {{ $doctors_count ?? 0 }}+ Doctors
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Doctors - Fully Responsive -->
<section class="py-12  bg-white px-4 sm:px-6">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">Featured Doctors</h2>
            <p class="text-sm sm:text-base md:text-lg text-gray-600 max-w-2xl mx-auto px-4">
                Meet our top-rated healthcare professionals ready to serve you
            </p>
        </div>

        <!-- Featured Doctors Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
            @foreach ($featuredDoctors as $featuredDoctor)
                @php
                    $domain = App\Models\Domain::where('tenant_id', $featuredDoctor->tenant_id)->first();
                @endphp

                <a href="{{ route('doc-details', ['doctor' => $featuredDoctor->id, 'slug' => \Illuminate\Support\Str::slug($featuredDoctor->name)]) }}" class="bg-white rounded-xl sm:rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 group">
                    <!-- Image - Responsive height -->
                    <div class="relative h-40 sm:h-44 md:h-48 lg:h-52 overflow-hidden bg-gray-100">
                        <img src="{{ $featuredDoctor->photo ? url($featuredDoctor->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($featuredDoctor->name) . '&background=318069&color=fff&size=400' }}"
                            class="w-full h-full object-contain object-top group-hover:scale-105 transition-transform duration-300"
                            alt="Dr. {{ $featuredDoctor->name }}"
                            loading="lazy">

                        <!-- Badges - Responsive positioning and sizing -->
                        <span class="absolute top-2 sm:top-3 left-2 sm:left-3 bg-[#FFC107] text-black px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold">
                            ⭐ Featured
                        </span>
                        <span class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-[#318069] text-white px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-medium">
                            {{ $featuredDoctor->is_available_today ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>

                    <!-- Content - Responsive padding and text -->
                    <div class="p-3 sm:p-4 md:p-5">
                        <h3 class="text-sm sm:text-base md:text-lg font-bold text-gray-900 mb-1 line-clamp-1">
                            Dr. {{ $featuredDoctor->name }}
                        </h3>
                        <div class="mt-1 sm:mt-2">
                    @php
                        $specializations = json_decode($featuredDoctor->specialization, true) ?? ['General'];
                    @endphp

                    <div class="flex flex-wrap gap-1 sm:gap-2">
                        @foreach(array_slice($specializations, 0, 2) as $spec)
                            <span class="px-2 sm:px-3 py-0.5 sm:py-1 text-[10px] sm:text-xs rounded-full bg-blue-100 text-blue-700">
                                {{ $spec ?? 'General' }}
                            </span>
                        @endforeach
                        @if(count($specializations) > 2)
                            <span class="px-2 sm:px-3 py-0.5 sm:py-1 text-[10px] sm:text-xs rounded-full bg-gray-100 text-gray-700">
                                +{{ count($specializations) - 2 }}
                            </span>
                        @endif
                    </div>
                </div>

                        <!-- Rating -->
                        <div class="flex items-center gap-1 sm:gap-2 mt-1 mb-2 sm:mb-3">
                            <i class="ri-star-fill text-[#FFC107] text-xs sm:text-sm"></i>
                            <span class="font-bold text-gray-900 text-xs sm:text-sm">
                                {{ $featuredDoctor->rating ?? '4.5' }}
                            </span>
                        </div>

                        <!-- Meta - Responsive -->
                        <div class="space-y-1 sm:space-y-2 text-xs text-gray-600">
                            <div class="flex items-center gap-1 sm:gap-2 truncate">
                                <i class="ri-briefcase-line text-gray-400 flex-shrink-0"></i>
                                <span class="truncate">{{ $featuredDoctor->experience_years ?? 1 }} years exp.</span>
                            </div>
                            <div class="flex items-center gap-1 sm:gap-2 truncate">
                                <i class="ri-hospital-line text-gray-400 flex-shrink-0"></i>
                                <span class="truncate">{{ $featuredDoctor->city ?? 'Medical Center' }}</span>
                            </div>
                        </div>

                        <!-- View Details Button -->
                        <!-- <a href="{{ route('doc-details', ['doctor' => $featuredDoctor->id, 'slug' => \Illuminate\Support\Str::slug($featuredDoctor->name)]) }}"
                            class="block text-center bg-[#318069] hover:bg-[#276854] text-white py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-medium transition">
                            View Details
                        </a> -->
                    </div>
</a>
            @endforeach
        </div>

       
    </div>
</section>

<!-- How It Works - Fully Responsive -->
<section class="pb-10 sm:pb-12 md:pb-16 bg-white relative overflow-hidden px-4 sm:px-6">
    <div class="absolute inset-0">
        <div class="absolute top-1/4 left-0 w-96 h-96 bg-gradient-to-r from-[#318069]/5 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-0 w-96 h-96 bg-gradient-to-l from-[#FFC107]/5 to-transparent rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 bg-[#318069]/5 border border-[#318069]/20 rounded-full px-4 sm:px-5 py-1.5 sm:py-2 mb-4 sm:mb-6">
                <i class="ri-lightbulb-line text-[#318069] text-sm sm:text-base"></i>
                <span class="text-xs sm:text-sm font-semibold text-[#318069]">Simple Process</span>
            </div>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">How It <span class="text-[#318069]">Works</span></h2>

        </div>

        <!-- Steps - Responsive grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 relative">
            <!-- Connector Line - Hidden on mobile -->
            <div class="hidden md:block absolute top-24 left-1/4 right-1/4 h-0.5 bg-gradient-to-r from-[#318069]/20 via-[#FFC107]/20 to-[#318069]/20"></div>

            <!-- Step 1 -->
            <div class="relative">
                <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl sm:rounded-3xl p-6 sm:p-8 border border-gray-100 hover:shadow-2xl transition-all duration-300 group cursor-pointer h-full">
                    <div class="absolute -top-3 -right-3 sm:-top-4 sm:-right-4 w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-white to-gray-50 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg border border-gray-200 group-hover:scale-110 transition-transform">
                        <span class="text-lg sm:text-2xl font-bold text-[#318069]">01</span>
                    </div>
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-[#318069] to-teal-600 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all shadow-lg">
                        <i class="ri-search-line text-2xl sm:text-3xl text-white"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-2 sm:mb-4 group-hover:text-[#318069] transition-colors">Search for Doctors</h3>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed">Browse through our extensive database of verified healthcare professionals.</p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="relative">
                <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl sm:rounded-3xl p-6 sm:p-8 border border-gray-100 hover:shadow-2xl transition-all duration-300 group cursor-pointer h-full">
                    <div class="absolute -top-3 -right-3 sm:-top-4 sm:-right-4 w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-white to-gray-50 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg border border-gray-200 group-hover:scale-110 transition-transform">
                        <span class="text-lg sm:text-2xl font-bold text-[#318069]">02</span>
                    </div>
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-[#FFC107] to-amber-500 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all shadow-lg">
                        <i class="ri-calendar-check-line text-2xl sm:text-3xl text-white"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-2 sm:mb-4 group-hover:text-[#318069] transition-colors">Book Appointment</h3>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed">Select your preferred time slot from real-time availability calendar.</p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="relative">
                <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl sm:rounded-3xl p-6 sm:p-8 border border-gray-100 hover:shadow-2xl transition-all duration-300 group cursor-pointer h-full">
                    <div class="absolute -top-3 -right-3 sm:-top-4 sm:-right-4 w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-white to-gray-50 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg border border-gray-200 group-hover:scale-110 transition-transform">
                        <span class="text-lg sm:text-2xl font-bold text-[#318069]">03</span>
                    </div>
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-[#318069] to-teal-600 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all shadow-lg">
                        <i class="ri-video-chat-line text-2xl sm:text-3xl text-white"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-2 sm:mb-4 group-hover:text-[#318069] transition-colors">Consult & Get Care</h3>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed">Meet your doctor in-person or via video consultation.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Health News Section - Fully Responsive -->
<section class="pb-10 sm:pb-12 md:pb-16 bg-white relative overflow-hidden px-4 sm:px-6">
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 bg-[#318069]/5 border border-[#318069]/20 rounded-full px-4 sm:px-5 py-1.5 sm:py-2 mb-4 sm:mb-6">
                <i class="ri-newspaper-line text-[#318069] text-sm sm:text-base"></i>
                <span class="text-xs sm:text-sm font-semibold text-[#318069]">Latest Health Insights</span>
            </div>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">Health News & <span class="text-[#318069]">Updates</span></h2>
        </div>

        <!-- Blog Posts Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach ($posts as $post)
                <a href="{{ url('singles-article', $post->slug) }}" class="bg-white rounded-xl sm:rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer group">
                    <div class="relative overflow-hidden">
                        <img alt="{{ $post->title }}"
                            class="w-full h-40 sm:h-48 md:h-56 object-cover object-top group-hover:scale-110 transition-transform duration-500"
                            src="{{ $post->cover_image ?: 'https://via.placeholder.com/600x400?text=Blog' }}"
                            loading="lazy">
                        @if ($post->category)
                            <div class="absolute top-3 left-3 sm:top-4 sm:left-4 bg-cyan-600 text-white px-2 sm:px-3 py-1 rounded-full text-xs font-semibold">
                                {{ $post->category->name }}
                            </div>
                        @endif
                    </div>

                    <div class="p-4 sm:p-5 md:p-6">
                        <div class="flex items-center gap-3 sm:gap-4 text-xs text-gray-500 mb-2 sm:mb-3">
                            <div class="flex items-center gap-1">
                                <i class="ri-calendar-line"></i>
                                <span>{{ optional($post->published_at)->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <i class="ri-time-line"></i>
                                <span>{{ $post->readTime }} min</span>
                            </div>
                        </div>

                        <h3 class="text-sm sm:text-base md:text-lg font-bold text-gray-900 mb-2 group-hover:text-cyan-600 transition-colors line-clamp-2">
                            {{ $post->title }}
                        </h3>

                        @if ($post->excerpt)
                            <p class="text-xs sm:text-sm text-gray-600 mb-3 line-clamp-2">{{ $post->excerpt }}</p>
                        @endif

                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 sm:w-7 sm:h-7 bg-gradient-to-br from-cyan-500 to-teal-500 rounded-full flex items-center justify-center">
                                    <i class="ri-user-line text-white text-xs"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-700">{{ $doctor->name }}</span>
                            </div>
                            <div 
                                class="text-cyan-600 font-semibold text-xs flex items-center gap-1 hover:gap-2 transition-all">
                                Read <span class="hidden sm:inline">More</span> <i class="ri-arrow-right-line"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- View All Button -->
        <div class="text-center mt-8 sm:mt-12">
            <a href="/all-articles" class="inline-block w-full sm:w-auto">
                <button class="w-full sm:w-auto bg-[#318069] hover:bg-[#276854] text-white px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-semibold transition-all hover:shadow-lg whitespace-nowrap text-sm sm:text-base">
                    View All Health Articles
                    <i class="ri-arrow-right-line ml-2"></i>
                </button>
            </a>
        </div>
    </div>
</section>

@endsection
