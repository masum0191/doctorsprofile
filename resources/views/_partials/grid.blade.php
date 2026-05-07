<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
    @foreach($doctors as $doctor)
    @php
        $rating = $doctor->rating ?? 4.5;
        $experience = $doctor->experience_years ?? 1;
        $experience_years = $doctor->experience_years ?? 1;
        $rating = $doctor->rating ?? 4.5;
        $review_count = $doctor->review_count ?? 0;
        $detailsUrl = route('doc-details', ['doctor' => $doctor->id, 'slug' => \Illuminate\Support\Str::slug($doctor->name)]);
    @endphp
    
    <a href="{{ $detailsUrl }}" class="group bg-white rounded-xl sm:rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        {{-- IMAGE - Responsive height --}}
        <div class="relative h-40 sm:h-44 md:h-48 bg-gray-100">
            <img
                src="{{ $doctor->photo ? url($doctor->photo) : 'https://ui-avatars.com/api/?name='.urlencode($doctor->name).'&background=318069&color=fff' }}"
                alt="Dr. {{ $doctor->name }}"
                class="w-full h-full object-contain object-top"
                loading="lazy"
            >
            
            {{-- Availability Badge - Responsive sizing --}}
            <span class="absolute top-2 sm:top-3 right-2 sm:right-3 px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold bg-emerald-600 text-white">
                Available
            </span>
        </div>

        {{-- CONTENT - Responsive padding and text sizes --}}
        <div class="p-3 sm:p-4 md:p-5 space-y-2 sm:space-y-3">
            <div>
                <h3 class="text-sm sm:text-base md:text-lg font-bold text-gray-900 hover:text-[#318069] transition-colors duration-200 line-clamp-1">
                    
                     {{ $doctor->name }}
                </h3>
                
                {{-- Specializations - Responsive wrapping --}}
                <div class="mt-1 sm:mt-2">
                    @php
                        $specializations = json_decode($doctor->specialization, true) ?? ['General'];
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
            </div>

            {{-- Rating - Responsive --}}
            <div class="flex items-center gap-1 sm:gap-2 text-xs sm:text-sm">
                <i class="ri-star-fill text-[#FFC107] text-xs sm:text-sm"></i>
                <span class="font-semibold">{{ number_format($rating, 1) }}</span>
                <span class="text-gray-500 text-[10px] sm:text-xs">({{ $review_count ?? 0 }})</span>
            </div>

            {{-- Details - Responsive --}}
            <div class="hidden sm:block text-[10px] sm:text-xs text-gray-600 space-y-1">
                <div class="flex items-center gap-1 truncate">
                    <i class="ri-briefcase-line flex-shrink-0"></i>
                    <span class="truncate">{{ $experience }} year(s) experience</span>
                </div>
                <div class="flex items-center gap-1 truncate">
                    <i class="ri-hospital-line flex-shrink-0"></i>
                    <span class="truncate">{{ $doctor->address ?? 'Medical Center' }}</span>
                </div>
            </div>

            {{-- Book Button - Responsive sizing --}}
            <!-- <button
                type="button"
                class="w-full mt-2 sm:mt-3 bg-[#318069] hover:bg-[#276854] text-white py-2 sm:py-2.5 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold transition-colors flex items-center justify-center gap-1 sm:gap-2"
                onclick="window.location.href='{{ $detailsUrl }}'"
            >
                <i class="ri-user-search-line text-sm sm:text-base"></i>
                <span class="sm:inline">View</span>
                <span class="hidden sm:inline">Profile</span>
            </button> -->
        </div>
    </a>
    @endforeach

    {{-- Empty State - Responsive --}}
    @if($doctors->count() === 0)
    <div class="col-span-full text-center py-8 sm:py-12">
        <div class="text-gray-400 mb-3">
            <i class="ri-user-search-line text-4xl sm:text-5xl"></i>
        </div>
        <p class="text-sm sm:text-base text-gray-500">No doctors found matching your criteria.</p>
        <button onclick="window.location.reload()" class="mt-4 text-[#318069] text-sm sm:text-base hover:underline">
            Try again
        </button>
    </div>
    @endif
</div>
