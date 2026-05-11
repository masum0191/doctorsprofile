@extends('layouts.sass')

@section('title', 'Dr. ' . $doctor->name . ' | Doctor Profile')
@section('meta_description', 'View profile, qualifications, and contact details for Dr. ' . $doctor->name . '.')

@php
    $specializations = $doctor->specialization_list ?? ['General Medicine'];
    $primarySpecialization = $specializations[0] ?? 'General Medicine';
    $rating = $doctor->rating ? number_format((float) $doctor->rating, 1) : '4.5';
    $profile = $tenantDoctor?->profile;
    $experience = $profile?->years_experience
        ?? $doctor->experience_years
        ?? data_get($doctor->basic_details, 'experience_years')
        ?? 1;
    $location = collect([$doctor->city, $doctor->state, $doctor->country])->filter()->implode(', ');
    $about = $profile?->about_long
        ?? $profile?->about_short
        ?? ($tenantDoctor?->qualification
        ? 'Dr. ' . $doctor->name . ' is a ' . $tenantDoctor->qualification . ' specialist focused on ' . strtolower($primarySpecialization) . ' care.'
        : 'Dr. ' . $doctor->name . ' provides patient-centered care with a focus on evidence-based treatment and accessible medical support.');
    $profileUrl = optional($doctor->detail_domain)->domain ? 'http://' . $doctor->detail_domain->domain : null;
    $qualifications = $tenantDoctor && $tenantDoctor->educations->count() > 0
        ? $tenantDoctor->educations
        : collect(preg_split('/[,\\n]+/', (string) ($tenantDoctor?->qualification ?? $doctor->qualification)))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values();
    $headline = $profile?->headline ?: ('Dr. ' . $doctor->name);
    $subheadline = $profile?->subheadline ?: ($tenantDoctor?->qualification ?: $primarySpecialization);
    $patientsCount = $profile?->patients_count;
    $satisfactionRate = $profile?->satisfaction_rate;
    $contactPhone = $tenantDoctor?->mobile ?: $doctor->mobile;
    $contactEmail = $tenantDoctor?->email ?: $doctor->email;
    $registrationNumber = $tenantDoctor?->reg_no ?: $doctor->reg_no;
    $displayLocation = collect([
        $tenantDoctor?->city ?: $doctor->city,
        $tenantDoctor?->state ?: $doctor->state,
        $tenantDoctor?->country ?: $doctor->country,
    ])->filter()->implode(', ');
    $serviceTitles = $tenantDoctor?->services?->pluck('title')->filter()->values() ?? collect();
    $specialtyTitles = $tenantDoctor?->specialties?->pluck('name')->filter()->values() ?? collect();
    if ($specialtyTitles->isNotEmpty()) {
        $specializations = $specialtyTitles->all();
        $primarySpecialization = $specializations[0] ?? $primarySpecialization;
    }
    if (!($qualifications instanceof \Illuminate\Support\Collection)) {
        $qualifications = collect($qualifications);
    }
    $qualifications = $qualifications
        ->map(fn ($item) => is_string($item) ? trim($item) : $item)
        ->filter()
        ->values();
    $packageFeatures = $packageFeatures ?? config('package_features.presets.free', []);
    $canBookAppointments = (bool) ($packageFeatures['appointment_booking'] ?? false);
    $showServices = (bool) ($packageFeatures['services'] ?? false);
    $showProfessionalProfile = (bool) ($packageFeatures['profile_professional'] ?? false);
    $showContent = (bool) ($packageFeatures['content'] ?? false);
    $showDoctorWebsite = (bool) (($packageFeatures['subdomain'] ?? false) || ($packageFeatures['custom_domain'] ?? false));
@endphp

@section('content')
<section class="relative bg-gradient-to-r from-[#318069] to-teal-700 mt-16 pt-10 pb-8 sm:pb-12">
   

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
        <nav class="flex mb-4 sm:mb-6 text-white/80 text-xs sm:text-sm">
            <a href="{{ url('/') }}" class="hover:text-white transition">Home</a>
            <i class="ri-arrow-right-s-line mx-2"></i>
            <a href="{{ route('finds') }}" class="hover:text-white transition">Doctors</a>
            <i class="ri-arrow-right-s-line mx-2"></i>
            <span class="text-white font-medium">Dr. {{ $doctor->name }}</span>
        </nav>

        <div class="flex flex-col md:flex-row gap-6 sm:gap-8 items-start">
            <div class="flex-shrink-0">
                <div class="w-28 h-28 sm:w-36 sm:h-36 md:w-44 md:h-44 rounded-2xl overflow-hidden bg-white shadow-xl">
                    <img
                        src="{{ $doctor->photo ? url($doctor->photo) : 'https://ui-avatars.com/api/?name=' . urlencode('Dr. ' . $doctor->name) . '&background=ffffff&color=318069&size=300' }}"
                        alt="Dr. {{ $doctor->name }}"
                        class="w-full h-full object-cover object-top"
                    >
                </div>
            </div>

            <div class="flex-1 text-white">
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-2">
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold">{{ $headline }}</h1>
                    @if($doctor->feature)
                        <span class="bg-[#FFC107] text-black px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-semibold">
                            Featured
                        </span>
                    @endif
                    <span class="bg-emerald-500 text-white px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-semibold">
                        {{ $doctor->is_available_today ? 'Available Today' : 'Schedule Available' }}
                    </span>
                </div>

                <div class="flex flex-wrap gap-2 mb-3">
                    @if($subheadline)
                        <span class="bg-white/15 backdrop-blur-sm px-3 sm:px-4 py-1 rounded-full text-xs sm:text-sm">{{ $subheadline }}</span>
                    @endif
                    @foreach($specializations as $specialization)
                        <span class="bg-white/20 backdrop-blur-sm px-3 sm:px-4 py-1 rounded-full text-xs sm:text-sm">{{ $specialization }}</span>
                    @endforeach
                </div>

                <div class="flex flex-wrap gap-4 sm:gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <i class="ri-briefcase-line text-[#FFC107]"></i>
                        <span>{{ $experience }}+ years experience</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="ri-star-fill text-[#FFC107]"></i>
                        <span>{{ $rating }} rating</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="ri-map-pin-line text-[#FFC107]"></i>
                        <span>{{ $displayLocation ?: 'Bangladesh' }}</span>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 mt-4 sm:mt-6">
                    @if($canBookAppointments)
                        <button
                            type="button"
                            onclick="window.openBookingModal('{{ $doctor->id }}', @js($doctor->name))"
                            class="bg-[#FFC107] hover:bg-amber-500 text-gray-900 px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-semibold transition-all flex items-center gap-2 text-sm sm:text-base"
                        >
                            <i class="ri-calendar-check-line"></i>
                            Book Appointment
                        </button>
                    @endif

                    @if($profileUrl && $showDoctorWebsite)
                        <a
                            href="{{ $profileUrl }}"
                            target="_blank"
                            class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-semibold transition-all flex items-center gap-2 text-sm sm:text-base"
                        >
                            <i class="ri-external-link-line"></i>
                            Visit Doctor Website
                        </a>
                    @endif

                    @if($contactPhone)
                        <a
                            href="tel:{{ $contactPhone }}"
                            class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-semibold transition-all flex items-center gap-2 text-sm sm:text-base"
                        >
                            <i class="ri-phone-line"></i>
                            Call Doctor
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        <div class="lg:col-span-2 space-y-6 sm:space-y-8">
            <div class="bg-white rounded-xl sm:rounded-2xl p-5 sm:p-6 md:p-8 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-[#318069]/10 rounded-xl flex items-center justify-center">
                        <i class="ri-user-smile-line text-xl text-[#318069]"></i>
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">About Dr. {{ $doctor->name }}</h2>
                </div>

                <p class="text-gray-600 leading-relaxed text-sm sm:text-base">
                    {{ $about }}
                </p>
            </div>

            <div class="bg-white rounded-xl sm:rounded-2xl p-5 sm:p-6 md:p-8 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-[#318069]/10 rounded-xl flex items-center justify-center">
                        <i class="ri-graduation-cap-line text-xl text-[#318069]"></i>
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">Education & Qualifications</h2>
                </div>

                <div class="space-y-3">
                    @forelse($qualifications as $qualification)
                        <div class="flex items-start gap-3">
                            <i class="ri-checkbox-circle-fill text-[#318069] text-sm mt-0.5"></i>
                            <span class="text-gray-700 text-sm sm:text-base">
                                {{ is_object($qualification) ? trim(collect([$qualification->degree, $qualification->institution])->filter()->implode(' - ')) : $qualification }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm sm:text-base text-gray-600">Qualification details will be updated soon.</p>
                    @endforelse
                </div>
            </div>

            @if($showProfessionalProfile && $tenantDoctor && $tenantDoctor->experiences->count() > 0)
                <div class="bg-white rounded-xl sm:rounded-2xl p-5 sm:p-6 md:p-8 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-[#318069]/10 rounded-xl flex items-center justify-center">
                            <i class="ri-briefcase-4-line text-xl text-[#318069]"></i>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-gray-800">Professional Experience</h2>
                    </div>

                    <div class="space-y-4">
                        @foreach($tenantDoctor->experiences as $experienceItem)
                            <div class="border border-gray-100 rounded-xl p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $experienceItem->title }}</h3>
                                        @if($experienceItem->organization)
                                            <p class="text-sm text-[#318069] mt-1">{{ $experienceItem->organization }}</p>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-500 whitespace-nowrap">
                                        {{ $experienceItem->start_year }}@if($experienceItem->end_year)-{{ $experienceItem->end_year }}@else - Present @endif
                                    </span>
                                </div>
                                @if($experienceItem->description)
                                    <p class="text-sm text-gray-600 mt-3">{{ $experienceItem->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($showServices)
            <div class="bg-white rounded-xl sm:rounded-2xl p-5 sm:p-6 md:p-8 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-[#318069]/10 rounded-xl flex items-center justify-center">
                        <i class="ri-stethoscope-line text-xl text-[#318069]"></i>
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">Areas of Expertise</h2>
                </div>

                <div class="flex flex-wrap gap-2">
                    @foreach($serviceTitles->isNotEmpty() ? $serviceTitles : collect($specializations) as $specialization)
                        <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-[#318069]/10 text-[#318069] rounded-full text-xs sm:text-sm font-medium">{{ $specialization }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-5 sm:space-y-6">
            <div class="bg-gradient-to-br from-[#318069] to-teal-700 rounded-xl sm:rounded-2xl p-5 sm:p-6 text-white">
                <h3 class="text-lg sm:text-xl font-bold mb-4">Quick Information</h3>
                <div class="space-y-4 text-sm sm:text-base">
                    <div class="flex items-start gap-3">
                        <i class="ri-stethoscope-line mt-1"></i>
                        <div>
                            <div class="text-white/70 text-xs uppercase tracking-wide">Primary Specialty</div>
                            <div>{{ $primarySpecialization }}</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="ri-map-pin-line mt-1"></i>
                        <div>
                            <div class="text-white/70 text-xs uppercase tracking-wide">Location</div>
                            <div>{{ $displayLocation ?: 'Bangladesh' }}</div>
                        </div>
                    </div>
                    @if($registrationNumber)
                        <div class="flex items-start gap-3">
                            <i class="ri-shield-check-line mt-1"></i>
                            <div>
                                <div class="text-white/70 text-xs uppercase tracking-wide">Registration No</div>
                                <div>{{ $registrationNumber }}</div>
                            </div>
                        </div>
                    @endif
                    @if($contactPhone)
                        <div class="flex items-start gap-3">
                            <i class="ri-phone-line mt-1"></i>
                            <div>
                                <div class="text-white/70 text-xs uppercase tracking-wide">Phone</div>
                                <div>{{ $contactPhone }}</div>
                            </div>
                        </div>
                    @endif
                    @if($contactEmail)
                        <div class="flex items-start gap-3">
                            <i class="ri-mail-line mt-1"></i>
                            <div>
                                <div class="text-white/70 text-xs uppercase tracking-wide">Email</div>
                                <div class="break-all">{{ $contactEmail }}</div>
                            </div>
                        </div>
                    @endif
                    @if($patientsCount)
                        <div class="flex items-start gap-3">
                            <i class="ri-group-line mt-1"></i>
                            <div>
                                <div class="text-white/70 text-xs uppercase tracking-wide">Patients</div>
                                <div>{{ number_format($patientsCount) }}+</div>
                            </div>
                        </div>
                    @endif
                    @if($satisfactionRate)
                        <div class="flex items-start gap-3">
                            <i class="ri-heart-pulse-line mt-1"></i>
                            <div>
                                <div class="text-white/70 text-xs uppercase tracking-wide">Satisfaction</div>
                                <div>{{ $satisfactionRate }}%</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($showServices && $chambers->count() > 0)
                <div class="bg-white rounded-xl sm:rounded-2xl p-5 sm:p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Chambers</h3>
                    <div class="space-y-4">
                        @foreach($chambers as $chamber)
                            <div class="border border-gray-100 rounded-xl p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $chamber->name }}</h4>
                                        @if($chamber->address)
                                            <p class="text-sm text-gray-600 mt-1">{{ $chamber->address }}</p>
                                        @endif
                                        @if($chamber->city)
                                            <p class="text-xs text-gray-500 mt-1">{{ $chamber->city }}</p>
                                        @endif
                                    </div>
                                    @if($chamber->fees)
                                        <span class="text-sm font-semibold text-[#318069]">BDT {{ number_format((float) $chamber->fees, 2) }}</span>
                                    @endif
                                </div>
                                @if($chamber->phone)
                                    <p class="text-sm text-gray-600 mt-3">
                                        <i class="ri-phone-line mr-1"></i>{{ $chamber->phone }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl sm:rounded-2xl p-5 sm:p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Availability</h3>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-center justify-between gap-4">
                        <span>Available today</span>
                        <span class="font-semibold {{ $doctor->is_available_today ? 'text-emerald-600' : 'text-gray-500' }}">
                            {{ $doctor->is_available_today ? 'Yes' : 'Please check website' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span>Virtual visits</span>
                        <span class="font-semibold {{ $doctor->accepts_virtual_visits ? 'text-emerald-600' : 'text-gray-500' }}">
                            {{ $doctor->accepts_virtual_visits ? 'Available' : 'Not listed' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span>Insurance</span>
                        <span class="font-semibold {{ $doctor->accepts_insurance ? 'text-emerald-600' : 'text-gray-500' }}">
                            {{ $doctor->accepts_insurance ? 'Accepted' : 'Not listed' }}
                        </span>
                    </div>
                </div>
            </div>

            @if($profileUrl && $showDoctorWebsite)
                <div class="bg-white rounded-xl sm:rounded-2xl p-5 sm:p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Book or Learn More</h3>
                    <p class="text-sm text-gray-600 mb-4">Open the doctor's website to see appointment options and published profile details.</p>
                    <a href="{{ $profileUrl }}" target="_blank" class="inline-flex items-center justify-center w-full bg-[#318069] hover:bg-[#276854] text-white py-3 rounded-xl font-semibold transition-colors">
                        Visit Website
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
