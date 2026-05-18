@extends('layouts.sass')
@section('title', 'Featured Doctors')
@section('meta_description', 'Browse featured doctors and compare trusted medical profiles.')
@section('canonical', route('featured.doctors'))

@section('content')
<section class="pt-24 pb-20 bg-gradient-to-b from-slate-50 to-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 bg-white border border-[#318069]/20 rounded-full px-4 py-2 mb-4 shadow-sm">
                    <i class="ri-star-line text-[#318069]"></i>
                    <span class="text-sm font-semibold text-[#318069]">Featured Doctors</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-2">
                    All Featured Doctors
                </h1>
                <p class="text-slate-500 max-w-2xl">
                    Meet highlighted healthcare professionals and view their profiles, specialties, and availability.
                </p>
            </div>

            <a href="{{ route('finds') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#318069] hover:text-[#318069]">
                <i class="ri-user-search-line"></i>
                All Doctors
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
            @forelse ($doctors as $doctor)
                @php
                    $profileUrl = route('doc-details', ['doctor' => $doctor->id, 'slug' => \Illuminate\Support\Str::slug($doctor->name)]);
                    $specializations = $doctor->specializationList();
                    $rating = $doctor->rating ?? 4.5;
                    $experience = $doctor->experience_years ?? 1;
                @endphp

                <a href="{{ $profileUrl }}" class="group bg-white rounded-xl sm:rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="relative h-40 sm:h-44 md:h-48 lg:h-52 overflow-hidden bg-gray-100">
                        <img
                            src="{{ $doctor->photo ? url($doctor->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($doctor->name) . '&background=318069&color=fff&size=400' }}"
                            alt="Dr. {{ $doctor->name }}"
                            class="w-full h-full object-contain object-top group-hover:scale-105 transition-transform duration-300"
                            loading="lazy">

                        <span class="absolute top-2 sm:top-3 left-2 sm:left-3 bg-[#FFC107] text-black px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold">
                            Featured
                        </span>
                        <span class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-[#318069] text-white px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-medium">
                            {{ $doctor->is_available_today ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>

                    <div class="p-3 sm:p-4 md:p-5">
                        <h3 class="text-sm sm:text-base md:text-lg font-bold text-gray-900 mb-1 line-clamp-1">
                            Dr. {{ $doctor->name }}
                        </h3>

                        <div class="mt-2 flex flex-wrap gap-1 sm:gap-2">
                            @forelse(array_slice($specializations ?: ['General'], 0, 2) as $spec)
                                <span class="px-2 sm:px-3 py-0.5 sm:py-1 text-[10px] sm:text-xs rounded-full bg-blue-100 text-blue-700">
                                    {{ $spec }}
                                </span>
                            @empty
                                <span class="px-2 sm:px-3 py-0.5 sm:py-1 text-[10px] sm:text-xs rounded-full bg-blue-100 text-blue-700">
                                    General
                                </span>
                            @endforelse
                            @if(count($specializations) > 2)
                                <span class="px-2 sm:px-3 py-0.5 sm:py-1 text-[10px] sm:text-xs rounded-full bg-gray-100 text-gray-700">
                                    +{{ count($specializations) - 2 }}
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-1 sm:gap-2 mt-2 mb-3">
                            <i class="ri-star-fill text-[#FFC107] text-xs sm:text-sm"></i>
                            <span class="font-bold text-gray-900 text-xs sm:text-sm">{{ number_format($rating, 1) }}</span>
                            <span class="text-gray-500 text-[10px] sm:text-xs">({{ $doctor->review_count ?? 0 }})</span>
                        </div>

                        <div class="space-y-1 sm:space-y-2 text-xs text-gray-600">
                            <div class="flex items-center gap-1 sm:gap-2 truncate">
                                <i class="ri-briefcase-line text-gray-400 flex-shrink-0"></i>
                                <span class="truncate">{{ $experience }} {{ $experience > 1 ? 'years' : 'year' }} exp.</span>
                            </div>
                            <div class="flex items-center gap-1 sm:gap-2 truncate">
                                <i class="ri-hospital-line text-gray-400 flex-shrink-0"></i>
                                <span class="truncate">{{ $doctor->city ?? $doctor->address ?? 'Medical Center' }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-20 bg-white rounded-2xl border border-slate-100">
                    <i class="ri-star-line text-5xl text-slate-300 mb-4"></i>
                    <h2 class="text-xl font-bold text-slate-900 mb-2">No Featured Doctors Found</h2>
                    <p class="text-slate-500">Featured doctor profiles will appear here once they are added.</p>
                </div>
            @endforelse
        </div>

        @if ($doctors->hasPages())
            <div class="mt-10">
                {{ $doctors->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
