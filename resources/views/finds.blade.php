@extends('layouts.sass')
@section('title', 'Find Doctors Nearby')
@section('meta_description', 'Search doctors by specialty, city, availability, and care options.')
@section('canonical', route('finds'))

@php
    use Illuminate\Support\Str;
@endphp

@section('content')

<!-- Hero Section -->
<section id="search-section"
    class="relative flex items-center justify-center  bg-gradient-to-br from-gray-50 via-white to-teal-50/30">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-[#318069]/10 to-[#318069]/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-gradient-to-tr from-[#FFC107]/5 to-[#FFC107]/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-[#318069]/3 rounded-full blur-2xl"></div>
        <div class="absolute top-20 right-1/4 w-32 h-32 border-2 border-[#318069]/20 rounded-2xl rotate-12"></div>
        <div class="absolute bottom-40 left-1/3 w-24 h-24 border-2 border-[#FFC107]/10 rounded-full"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(49,128,105,0.04)_1px,transparent_1px),linear-gradient(90deg,rgba(49,128,105,0.04)_1px,transparent_1px)] bg-[size:64px_64px]"></div>
    </div>
    <div class="relative z-10 w-full max-w-6xl mx-auto px-4 text-center pt-24 pb-16">
        
       <form id="searchForm">
    <div class="bg-white rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] p-4 sm:p-6 md:p-8 max-w-5xl mx-auto border border-[#318069]/20">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-5 flex items-center bg-gray-100 rounded-2xl px-5 py-4 border-2 border-transparent focus-within:border-[#318069] focus-within:bg-white transition-all">
                <i class="ri-search-line text-2xl text-gray-400 mr-3 w-6 h-6 flex items-center justify-center"></i>
                <input id="doctor-search" placeholder="Search doctors, specialties..."
                    class="flex-1 outline-none bg-transparent text-base text-gray-700 placeholder-gray-400"
                    type="text" value="">
            </div>
            <div class="md:col-span-3 flex items-center bg-gray-100 rounded-2xl px-5 py-4 border-2 border-transparent focus-within:border-[#318069] focus-within:bg-white transition-all cursor-pointer">
                <i class="ri-stethoscope-line text-2xl text-gray-400 mr-3 w-6 h-6 flex items-center justify-center"></i>
                <select id="specialty" class="flex-1 outline-none bg-transparent text-base text-gray-700 cursor-pointer">
                    <option value="">All specialty</option>
                    <option value="Cardiology">Cardiology</option>
                    <option value="Neurology">Neurology</option>
                    <option value="Pediatrics">Pediatrics</option>
                    <option value="General">General</option>
                    <option value="Dermatology">Dermatology</option>
                    <option value="Orthopedics">Orthopedics</option>
                    <option value="Gynecology">Gynecology</option>
                    <option value="Psychiatry">Psychiatry</option>
                    <option value="Ophthalmology">Ophthalmology</option>
                    <option value="Dentistry">Dentistry</option>
                    <option value="Urology">Urology</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="md:col-span-3 relative">
                <div id="locationSelector" class="flex items-center bg-gray-100 rounded-2xl px-5 py-4 border-2 border-transparent focus-within:border-[#318069] focus-within:bg-white transition-all cursor-pointer">
                    <i class="ri-map-pin-line text-2xl text-gray-400 mr-3 w-6 h-6 flex items-center justify-center"></i>
                    <div class="flex-1 flex items-center justify-between">
                        <span id="locationText" class="text-base text-gray-700">Detecting...</span>
                        <i class="ri-arrow-down-s-line text-xl text-gray-500"></i>
                    </div>
                    <input type="hidden" id="latInput" value="">
                    <input type="hidden" id="lngInput" value="">
                </div>
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
            <div class="md:col-span-1">
                <button type="submit"
                    class="w-full h-full bg-[#318069] hover:bg-[#276854] text-white rounded-2xl font-medium transition-all hover:shadow-lg flex items-center justify-center whitespace-nowrap px-6 py-4 md:py-0">
                    <i class="ri-search-line text-2xl w-6 h-6 flex items-center justify-center"></i>
                </button>
            </div>
        </div>
        <div class="mt-8 flex flex-wrap gap-3 justify-center items-center">
            <span class="text-sm font-medium text-gray-500">Popular Searches:</span>
            <button type="button" data-filter="top_rated" class="filter-badge px-5 py-2 bg-gray-50 hover:bg-[#318069] hover:text-white text-gray-700 rounded-full text-sm font-medium transition-all hover:shadow-md whitespace-nowrap cursor-pointer border border-gray-200 hover:border-[#318069]">Top Rated</button>
            <button type="button" data-filter="available_today" class="filter-badge px-5 py-2 bg-gray-50 hover:bg-[#318069] hover:text-white text-gray-700 rounded-full text-sm font-medium transition-all hover:shadow-md whitespace-nowrap cursor-pointer border border-gray-200 hover:border-[#318069]">Available Today</button>
            <button type="button" data-filter="virtual_visits" class="filter-badge px-5 py-2 bg-gray-50 hover:bg-[#318069] hover:text-white text-gray-700 rounded-full text-sm font-medium transition-all hover:shadow-md whitespace-nowrap cursor-pointer border border-gray-200 hover:border-[#318069]">Virtual Visits</button>
            <button type="button" id="near-me" class="px-5 py-2 bg-gray-50 hover:bg-[#318069] hover:text-white text-gray-700 rounded-full text-sm font-medium transition-all hover:shadow-md whitespace-nowrap cursor-pointer border border-gray-200 hover:border-[#318069]">Near Me</button>
            <button type="button" data-filter="accepts_insurance" class="filter-badge px-5 py-2 bg-gray-50 hover:bg-[#318069] hover:text-white text-gray-700 rounded-full text-sm font-medium transition-all hover:shadow-md whitespace-nowrap cursor-pointer border border-gray-200 hover:border-[#318069]">Accepts Insurance</button>
        </div>
    </div>
</form>
    </div>
</section>

<!-- Doctors Section -->
<section id="doctors" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-8">
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
</section>



@endsection
