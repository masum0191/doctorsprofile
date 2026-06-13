<div id="locationModal"
    class="hidden fixed sm:absolute top-1/2 left-1/2 sm:top-full sm:left-0 transform -translate-x-1/2 -translate-y-1/2 sm:translate-x-0 sm:translate-y-0 w-[calc(100vw-2rem)] sm:w-[350px] max-w-[400px] mt-0 sm:mt-2 bg-white rounded-2xl shadow-xl border border-gray-200 z-[99999] p-4 sm:p-6">
    <div class="flex justify-between items-center mb-4 sm:hidden">
        <h4 class="font-semibold text-gray-800">Select Location</h4>
        <button type="button" class="close-modal-btn p-2">
            <i class="ri-close-line text-xl"></i>
        </button>
    </div>

    <h4 class="font-semibold text-gray-800 mb-4 hidden sm:block">Select Your Location</h4>

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

    <div class="mb-4">
        <label class="text-xs text-start font-medium text-gray-500 mb-2 block">Popular Cities</label>
        <div class="grid grid-cols-2 gap-2">
            <button type="button" class="quick-location px-3 py-2 rounded-lg border border-gray-200 text-xs font-medium text-gray-700 hover:bg-[#318069] hover:text-white hover:border-[#318069] transition-colors" data-city="Dhaka" data-lat="23.8103" data-lng="90.4125">Dhaka</button>
            <button type="button" class="quick-location px-3 py-2 rounded-lg border border-gray-200 text-xs font-medium text-gray-700 hover:bg-[#318069] hover:text-white hover:border-[#318069] transition-colors" data-city="Chattogram" data-lat="22.3569" data-lng="91.7832">Chattogram</button>
            <button type="button" class="quick-location px-3 py-2 rounded-lg border border-gray-200 text-xs font-medium text-gray-700 hover:bg-[#318069] hover:text-white hover:border-[#318069] transition-colors" data-city="Sylhet" data-lat="24.8949" data-lng="91.8687">Sylhet</button>
            <button type="button" class="quick-location px-3 py-2 rounded-lg border border-gray-200 text-xs font-medium text-gray-700 hover:bg-[#318069] hover:text-white hover:border-[#318069] transition-colors" data-city="Khulna" data-lat="22.8456" data-lng="89.5403">Khulna</button>
        </div>
    </div>

    <div id="manualCityContainer" class="mt-3 hidden">
        <label class="text-xs text-start font-medium text-gray-500 mb-1 block">Enter City Name</label>
        <input type="text" id="cityInput" class="w-full form-input rounded-xl border-gray-300 focus:border-[#318069] focus:ring-[#318069] text-sm sm:text-base p-3" placeholder="Enter city name">
    </div>

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

    <div class="mb-4">
        <label class="text-xs font-medium text-gray-500 mb-1 block">Map Pin</label>
        <p class="text-xs text-gray-500 mb-2 text-left">Click the map or drag the pin to search around that area.</p>
        <div id="map" class="h-40 sm:h-48 rounded-xl overflow-hidden border border-gray-300"></div>
    </div>

    <div class="mt-4 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3">
        <button type="button" id="useCurrentLocation" class="text-sm font-medium text-[#318069] hover:text-[#276854] py-2 sm:py-0 transition-colors text-left">
            <i class="ri-crosshair-line mr-1"></i>Use My Location
        </button>
        <div class="flex gap-2">
            <button type="button" id="clearLocationBtn" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium transition-colors">
                Clear
            </button>
            <button type="button" id="cancelLocationBtn" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium transition-colors">
                Cancel
            </button>
            <button type="button" id="applyLocation" class="px-4 py-2 bg-[#318069] text-white rounded-lg hover:bg-[#276854] text-sm font-medium transition-colors">
                Show Doctors
            </button>
        </div>
    </div>
</div>
<?php /**PATH D:\doctorprofiles\resources\views/partials/search-location-modal.blade.php ENDPATH**/ ?>