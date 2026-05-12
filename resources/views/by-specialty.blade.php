@extends('layouts.sass')
@section('title', 'Doctors by Specialty')
@section('meta_description', 'Browse doctors specializing in ' . $specialty->name . ' and compare available profiles.')
@section('canonical', route('specialty.doctors', ['slug' => $specialty->id]))

@section('content')
<section class="pt-24 pb-20 bg-gradient-to-b from-slate-50 to-white min-h-screen">
    <div class="container mx-auto px-4">

        {{-- Page Header --}}
        <div class="mb-10">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-2">
                Doctors Specializing in
                <span class="text-[#318069]">{{ $specialty->name }}</span>
            </h1>
            <p class="text-slate-500 max-w-2xl">
                View doctor profiles, experience, and professional details.
            </p>
        </div>

        {{-- Doctor Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            @forelse ($doctors as $doctor)
                @php
                    $profileUrl = route('doc-details', ['doctor' => $doctor->id, 'slug' => \Illuminate\Support\Str::slug($doctor->name)]);
                    $experience = $doctor->experience_years ?? 1;
                    $rating = $doctor->rating ?? 4.5;
                    $reviews = $doctor->review_count ?? 0;
                @endphp

                <a href="{{ $profileUrl }}"
                    class="group bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">

                    {{-- Image --}}
                    <div class="relative h-52 bg-slate-100 overflow-hidden">
                        <img
                            src="{{ $doctor->photo ? url($doctor->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($doctor->name) . '&background=318069&color=fff&size=400' }}"
                            alt="Dr. {{ $doctor->name }}"
                            class="w-full h-full object-contain object-top transition-transform duration-500 group-hover:scale-110">

                        <span
                            class="absolute top-4 right-4 text-xs font-semibold px-3 py-1 rounded-full bg-[#318069] text-white">
                            Profile Available
                        </span>
                    </div>

                    {{-- Content --}}
                    <div class="p-5 flex flex-col gap-4">

                        {{-- Name --}}
                        <div>
                            <h3 class="text-lg font-bold line-clamp-1 text-slate-900">
                                Dr. {{ $doctor->name }}
                            </h3>
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

                        {{-- Rating --}}
                        <div class="flex items-center gap-2 text-sm">
                            <i class="ri-star-fill text-[#FFC107]"></i>
                            <span class="font-semibold text-slate-900">{{ $rating }}</span>
                            <span class="text-slate-500">({{ $reviews }} reviews)</span>
                        </div>

                        {{-- Meta --}}
                        <div class="space-y-2 text-xs text-slate-600">
                            <div class="flex items-center gap-2">
                                <i class="ri-briefcase-line text-slate-400"></i>
                                <span>{{ $experience }} {{ $experience > 1 ? 'years' : 'year' }} experience</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="ri-hospital-line text-slate-400"></i>
                                <span class="truncate">{{ $doctor->city ?? 'Medical Center' }}</span>
                            </div>
                        </div>


                    </div>
                </a>

            @empty
                {{-- Empty State --}}
                <div class="col-span-full text-center py-20 bg-white rounded-2xl border border-slate-100">
                    <i class="ri-search-line text-5xl text-slate-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">No Doctors Found</h3>
                    <p class="text-slate-500">
                        No doctors are available for this specialty right now.
                    </p>
                </div>
            @endforelse

        </div>
    </div>
</section>
@endsection
