<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $doctor->name . ' - ' . ($setting->site_name ?? 'Medical Practice'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Pacifico&family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.5.0/remixicon.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
    * {
        font-family: 'Inter', sans-serif;
    }

    .pacifico-font {
        font-family: 'Pacifico', cursive;
    }

    .pdf-wrapper {
        font-family: "Segoe UI", Arial, sans-serif;
        color: #333;
        padding: 10px;
    }

    .pdf-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .pdf-header h2 {
        margin: 0;
        font-size: 22px;
    }

    .pdf-header .sub {
        font-size: 12px;
        color: #777;
    }

    .badge-confirmed {
        background: #28a745;
        color: #fff;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .pdf-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .pdf-table td {
        padding: 3px;
        font-size: 13px;
        border-bottom: 1px solid #eee;
    }

    .pdf-table td:first-child {
        font-weight: 600;
        width: 40%;
        color: #555;
    }

    .meeting-box {
        margin-top: 20px;
        padding: 12px;
        border-left: 4px solid #007bff;
        background: #f4f8ff;
    }

    .meeting-box a {
        display: block;
        margin-top: 6px;
        word-break: break-all;
        color: #007bff;
    }

    .pdf-footer {
        margin-top: 30px;
        text-align: center;
        font-size: 12px;
        color: #666;
    }

    /* Modal Styles */
    .modal-overlay-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 20px;
        overflow-y: auto;
    }

   .enhanced-modal {
    background: white;
    border-radius: 16px;
    width: 100%;
    max-width: 800px;
    overflow: auto;
    height: auto;
    max-height: 90vh; 
    margin: auto;
    display: flex;
    flex-direction: column;
    position: relative;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

    .enhanced-header {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        padding: 20px 24px;
        flex-shrink: 0;
    }

    .enhanced-header h2 {
        font-size: 18px;
        font-weight: 600;
        color: white;
        margin: 0;
    }

    .close-btn {
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.8);
        font-size: 24px;
        cursor: pointer;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: 20px;
        right: 24px;
    }

    .close-btn:hover {
        color: white;
    }

    .enhanced-form {
        padding: 24px;
        flex: 1;
        overflow-y: auto;
    }

    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
        animation: fadeIn 0.3s ease;
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

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        font-size: 14px;
        font-weight: 500;
        color: #1f2937;
        margin-bottom: 6px;
        display: block;
    }

    .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .enhanced-footer {
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        padding: 16px 24px;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        flex-shrink: 0;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-secondary {
        background: white;
        color: #2563eb;
        border: 1px solid #2563eb;
    }

    .btn-secondary:hover {
        background: #eff6ff;
    }

    .btn-primary {
        background: #2563eb;
        color: white;
    }

    .btn-primary:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    .btn-success {
        background: #10b981;
        color: white;
    }

    .btn-success:hover {
        background: #059669;
    }

    .selection-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .form-card {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }

    .form-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: rgba(37, 99, 235, 0.05);
        border-bottom: 1px solid #e5e7eb;
    }

    .form-card-header i {
        font-size: 20px;
        color: #2563eb;
    }

    .form-card-header h5 {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
    }

    .form-card-body {
        padding: 16px;
    }

    .enhanced-select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        background: white;
    }

    .schedule-section {
        margin-top: 24px;
    }

    .calendar-container {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px;
        background: #f9fafb;
    }

    .calendar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .calendar-nav {
        width: 36px;
        height: 36px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .calendar-nav:hover {
        border-color: #2563eb;
        color: #2563eb;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
        margin-bottom: 16px;
    }

    .day-header {
        text-align: center;
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        padding: 8px 0;
    }

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s;
    }

    .calendar-day.selected {
        background: #2563eb !important;
        color: white;
    }

    .calendar-day.available:hover {
        background: rgba(37, 99, 235, 0.1);
        border-color: #2563eb;
    }

    .calendar-day.today {
        background: rgba(37, 99, 235, 0.1);
        color: #2563eb;
        border-color: #2563eb;
    }

    .calendar-day.past {
        background: #f3f4f6;
        color: #9ca3af;
        cursor: not-allowed;
        opacity: 0.5;
    }

    .time-slots-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
        gap: 10px;
        min-height: 120px;
    }

    .time-slot {
        padding: 10px 6px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        background: white;
        text-align: center;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .time-slot.selected {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }

    .time-slot:hover:not(.disabled) {
        border-color: #2563eb;
        background: rgba(37, 99, 235, 0.05);
    }

    .success-section {
        text-align: center;
    }

    .success-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #10b981, #059669);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .success-icon i {
        font-size: 32px;
        color: white;
    }

    .appointment-details {
        background: #f9fafb;
        border-radius: 12px;
        padding: 16px;
        margin-top: 20px;
        text-align: left;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .selection-grid {
            grid-template-columns: 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .enhanced-footer {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .enhanced-form {
            padding: 16px;
        }

        .calendar-grid {
            gap: 4px;
        }

        .calendar-day {
            font-size: 11px;
        }

        .day-header {
            font-size: 10px;
            padding: 5px 0;
        }

        .time-slots-grid {
            grid-template-columns: repeat(auto-fill, minmax(75px, 1fr));
            gap: 8px;
        }

        .time-slot {
            padding: 8px 4px;
            font-size: 11px;
        }
    }
    </style>
</head>

<body class="bg-white">
    <div class="min-h-screen bg-white">
        <!-- Header -->
        <header id="mainHeader" class="bg-white sticky top-0 z-50 transition-all duration-300 border-b shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-3 md:py-4">
                    <div class="flex items-center">
                        <div class="text-xl md:text-2xl font-bold text-blue-600 pacifico-font">
                            {{ $doctor->name }}
                        </div>
                    </div>

                   <nav class="hidden md:flex items-center space-x-6 lg:space-x-8">
                    <a href="/" class="text-gray-700 hover:text-blue-600 transition-colors text-sm lg:text-base">Home</a>
                    <a href="#about" class="text-gray-700 hover:text-blue-600 transition-colors text-sm lg:text-base">About</a>
                    <a href="#services" class="text-gray-700 hover:text-blue-600 transition-colors text-sm lg:text-base">Services</a>
                    @if($doctor->testimonials->count() > 0)
                        <a href="#testimonials" class="text-gray-700 hover:text-blue-600 transition-colors text-sm lg:text-base">Testimonials</a>
                    @endif
                    <a href="#footer" class="text-gray-700 hover:text-blue-600 transition-colors text-sm lg:text-base">Contact</a>
                    
                    @auth
                        <a href="{{ url('admin/dashboard') }}" class="text-gray-700 hover:text-blue-600 transition-colors text-sm lg:text-base">Dashboard</a>
                        <button data-open class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-5 py-2.5 text-sm rounded-xl transition-all shadow-md hover:shadow-lg">
                            Book Appointment
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-5 py-2.5 text-sm rounded-xl transition-all shadow-md hover:shadow-lg">
                            Login
                        </a>
                    @endauth
                </nav>

                    <div class="items-center gap-3 flex md:hidden">
                        <button id="mobileMenuButton"
                            class="md:hidden p-2 rounded-md text-gray-700 hover:text-blue-600 cursor-pointer">
                            <i class="ri-menu-line text-2xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <!-- Hero Section -->
            <section id="home"
                class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50/30 via-white to-sky-50/40 py-12 md:py-16">
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute top-20 left-20 w-32 h-32 bg-blue-200 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-40 right-32 w-48 h-48 bg-sky-200 rounded-full blur-3xl"></div>
                </div>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-16 items-center">
                        <!-- Left Content -->
                        <div class="text-center lg:text-left space-y-6 md:space-y-8 order-2 lg:order-1">
                            <div
                                class="inline-flex items-center px-3 py-1.5 md:px-4 md:py-2 bg-blue-50 rounded-full border border-blue-100 mx-auto lg:mx-0">
                                <i class="ri-heart-pulse-line text-blue-500 mr-2 text-sm md:text-base"></i>
                                <span
                                    class="text-blue-700 text-xs md:text-sm font-medium">{{ $doctor->profile->tagline ?? 'Trusted Healthcare Provider' }}</span>
                            </div>
                            <h1
                                class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-light text-gray-800 leading-tight">
                                {{ $doctor->profile->headline ?? 'Compassionate Care,' }}
                                <span
                                    class="text-blue-500 block font-normal">{{ $doctor->profile->subheadline ?? 'Expert Treatment' }}</span>
                            </h1>
                            <p class="text-base md:text-lg lg:text-xl text-gray-600 leading-relaxed">
                                {{ $doctor->profile->about_short ?? 'Dr. ' . $doctor->name . ' provides personalized healthcare with over ' . ($doctor->profile->years_experience ?? '15') . ' years of experience.' }}
                            </p>
                            <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center lg:justify-start">
                                <button data-open
                                    class="inline-flex items-center justify-center font-medium transition-all duration-300 cursor-pointer bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white shadow-md hover:shadow-lg px-5 py-2.5 md:px-8 md:py-4 text-sm md:text-lg rounded-xl">
                                    <i class="ri-calendar-line mr-2"></i>Book Appointment
                                </button>
                                @if($doctor->accepts_virtual_visits)
                                <button
                                    class="inline-flex items-center justify-center font-medium transition-all duration-300 cursor-pointer border-2 border-blue-200 text-blue-600 hover:bg-blue-50 hover:border-blue-300 bg-white/80 backdrop-blur-sm px-5 py-2.5 md:px-8 md:py-4 text-sm md:text-lg rounded-xl">
                                    <i class="ri-video-line mr-2"></i>Virtual Consultation
                                </button>
                                @endif
                            </div>
                            <!-- Stats Row - Enhanced -->
                            <div class="grid grid-cols-3 gap-3 md:gap-6 pt-4 md:pt-8 border-t border-gray-200">
                                <div class="text-center">
                                    <div class="text-xl md:text-3xl font-bold text-blue-600">
                                        {{ $doctor->profile->years_experience ?? '15' }}+</div>
                                    <div class="text-xs md:text-sm text-gray-600 mt-1">Years Experience</div>
                                </div>
                                <div class="text-center border-x border-gray-200">
                                    <div class="text-xl md:text-3xl font-bold text-blue-600">
                                        {{ number_format($doctor->profile->patients_count ?? 5000) }}+</div>
                                    <div class="text-xs md:text-sm text-gray-600 mt-1">Happy Patients</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xl md:text-3xl font-bold text-blue-600">
                                        {{ $doctor->profile->satisfaction_rate ?? '98' }}%</div>
                                    <div class="text-xs md:text-sm text-gray-600 mt-1">Satisfaction</div>
                                </div>
                            </div>
                            <div class="flex items-center justify-center lg:justify-start space-x-4 pt-2">
                                <div class="flex -space-x-2">
                                    <div
                                        class="w-8 h-8 bg-blue-100 rounded-full border-2 border-white flex items-center justify-center text-blue-600 text-xs font-bold">
                                        JD</div>
                                    <div
                                        class="w-8 h-8 bg-sky-100 rounded-full border-2 border-white flex items-center justify-center text-sky-600 text-xs font-bold">
                                        MK</div>
                                    <div
                                        class="w-8 h-8 bg-blue-200 rounded-full border-2 border-white flex items-center justify-center text-blue-600 text-xs font-bold">
                                        RS</div>
                                </div>
                                <span class="text-xs md:text-sm text-gray-600">Trusted by 5000+ patients</span>
                            </div>
                        </div>
                        <!-- Right Image -->
                        <div class="flex justify-center order-1 lg:order-2">
                            <div class="relative">
                                <div
                                    class="relative w-64 h-64 sm:w-80 sm:h-80 md:w-96 md:h-96 rounded-3xl overflow-hidden shadow-2xl bg-gradient-to-br from-blue-50 to-white">
                                    <img alt="{{ $doctor->name }}" class="w-full h-full object-cover object-top"
                                        src="{{ $doctor->photo ? url($doctor->photo) : 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=400&h=500&fit=crop' }}">
                                </div>
                                <div
                                    class="absolute -top-3 -right-3 md:-top-6 md:-right-6 bg-white/90 backdrop-blur-sm rounded-xl md:rounded-2xl p-2 md:p-4 shadow-lg border border-blue-100">
                                    <i class="ri-heart-pulse-line text-xl md:text-2xl text-rose-400"></i>
                                </div>
                                <div
                                    class="absolute -bottom-3 -left-3 md:-bottom-6 md:-left-6 bg-blue-500/90 backdrop-blur-sm rounded-xl md:rounded-2xl p-2 md:p-4 shadow-lg">
                                    <i class="ri-stethoscope-line text-xl md:text-2xl text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- About Section -->
            <section id="about" class="py-12 md:py-20 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 items-center">
                        <div class="relative order-2 lg:order-1">
                            <div class="w-full h-64 sm:h-80 md:h-96 rounded-2xl overflow-hidden shadow-xl">
                                <img alt="{{ $doctor->name }} in office" class="w-full h-full object-cover object-top"
                                    src="{{ $doctor->galleries->where('category', 'care')->first()->image_url ?? 'https://images.unsplash.com/photo-1622253692010-333f2da6031f?w=600&h=400&fit=crop' }}">
                            </div>
                            <div
                                class="absolute -bottom-4 right-4 md:-bottom-6 md:right-6 bg-blue-600 rounded-lg md:rounded-xl p-3 md:p-6 shadow-xl">
                                <div class="text-white text-center">
                                    <div class="text-xl md:text-3xl font-bold">
                                        {{ $doctor->profile->years_experience ?? '15' }}+</div>
                                    <div class="text-xs md:text-sm">Years Exp.</div>
                                </div>
                            </div>
                        </div>
                        <div class="order-1 lg:order-2">
                            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-4 md:mb-6">Meet Dr.
                                {{ explode(' ', $doctor->name)[0] ?? 'Doctor' }}</h2>
                            <p class="text-base md:text-lg text-gray-600 mb-4 md:mb-6 leading-relaxed">
                                {{ $doctor->profile->about_long ?? 'Dr. ' . $doctor->name . ' is a board-certified physician with over ' . ($doctor->profile->years_experience ?? '15') . ' years of experience providing comprehensive healthcare.' }}
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                                @foreach($doctor->specialties->take(4) as $specialty)
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-6 h-6 md:w-8 md:h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="ri-check-line text-blue-600 text-xs md:text-sm"></i>
                                    </div>
                                    <span class="text-sm md:text-base text-gray-700">{{ $specialty->name }}</span>
                                </div>
                                @endforeach
                                @if($doctor->qualification)
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-6 h-6 md:w-8 md:h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="ri-check-line text-blue-600 text-xs md:text-sm"></i>
                                    </div>
                                    <span class="text-sm md:text-base text-gray-700">{{ $doctor->qualification }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Education Section -->
            @if($doctor->educations->count() > 0)
            <section class="py-12 md:py-20 bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-8 md:mb-12">
                        <div
                            class="inline-flex items-center justify-center w-12 h-12 md:w-16 md:h-16 bg-blue-100 rounded-full mb-4 md:mb-6">
                            <i class="ri-book-open-line text-xl md:text-2xl text-blue-600"></i>
                        </div>
                        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 md:mb-4">Education &
                            Training</h2>
                        <p class="text-sm md:text-lg text-gray-600 max-w-3xl mx-auto">Dr.
                            {{ explode(' ', $doctor->name)[0] }}'s extensive educational background from prestigious
                            institutions.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 lg:gap-10">
                        @foreach($doctor->educations as $education)
                        <div
                            class="group bg-white rounded-xl md:rounded-2xl p-5 md:p-6 shadow-md hover:shadow-xl transition-all hover:-translate-y-2">
                            <div class="flex items-center justify-between mb-4">
                                <div
                                    class="w-12 h-12 md:w-16 md:h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <i class="ri-graduation-cap-line text-xl md:text-2xl text-blue-600"></i>
                                </div>
                                <span
                                    class="text-xs md:text-sm font-semibold text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                    {{ $education->start_year }}{{ $education->end_year ? '-' . $education->end_year : '' }}
                                </span>
                            </div>
                            <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">{{ $education->degree }}</h3>
                            <h4 class="text-blue-600 font-semibold text-sm md:text-base mb-2">
                                {{ $education->institution }}</h4>
                            <p class="text-gray-600 text-sm">{{ $education->description }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            <!-- Services Section -->
            @if($doctor->services->count() > 0)
            <section id="services" class="py-12 md:py-20 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-8 md:mb-12">
                        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 md:mb-4">Comprehensive
                            Healthcare Services</h2>
                        <p class="text-sm md:text-lg text-gray-600 max-w-3xl mx-auto">From routine check-ups to
                            specialized treatments.</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-8">
                        @foreach($doctor->services as $service)
                        <div
                            class="bg-gradient-to-br from-blue-50 to-white rounded-xl p-5 md:p-6 border border-blue-100 hover:shadow-xl transition-all group">
                            <div
                                class="w-12 h-12 md:w-16 md:h-16 bg-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-700 transition-colors">
                                <i class="ri-stethoscope-line text-white text-xl md:text-2xl"></i>
                            </div>
                            <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">{{ $service->title }}</h3>
                            <p class="text-sm md:text-base text-gray-600 mb-3">{{ $service->description }}</p>
                            <button
                                class="text-blue-600 font-medium text-sm hover:text-blue-700 inline-flex items-center gap-1 group-hover:gap-2 transition-all">Learn
                                More <i class="ri-arrow-right-line"></i></button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            <!-- Testimonials Section -->
            @if($doctor->testimonials->count() > 0)
            <section id="testimonials" class="py-12 md:py-20 bg-gradient-to-br from-blue-50 to-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-8 md:mb-12">
                        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 md:mb-4">What Our
                            Patients Say</h2>
                        <p class="text-sm md:text-lg text-gray-600 max-w-3xl mx-auto">Don't just take our word for it.
                        </p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-8">
                        @foreach($doctor->testimonials as $testimonial)
                        <div class="bg-white rounded-xl p-5 md:p-6 shadow-lg hover:shadow-xl transition-all">
                            <div class="flex items-center gap-3 mb-4">
                                @if($testimonial->photo)
                                <img alt="{{ $testimonial->patient_name }}"
                                    class="w-12 h-12 md:w-14 md:h-14 rounded-full object-cover"
                                    src="{{ url($testimonial->photo) }}">
                                @else
                                <div
                                    class="w-12 h-12 md:w-14 md:h-14 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="ri-user-fill text-xl text-blue-600"></i>
                                </div>
                                @endif
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm md:text-base">
                                        {{ $testimonial->patient_name }}</h4>
                                    <p class="text-xs text-gray-500">Patient since
                                        {{ $testimonial->since ?? date('Y') }}</p>
                                </div>
                            </div>
                            <div class="flex gap-1 mb-3">
                                @for($i = 1; $i <= 5; $i++) <i
                                    class="ri-star-fill text-xs md:text-sm {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}">
                                    </i>
                                    @endfor
                            </div>
                            <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                                "{{ Str::limit($testimonial->content, 120) }}"</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            <!-- FAQs Section -->
            @if($doctor->faqs->count() > 0)
            <section class="py-12 md:py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-12">
                    <div class="text-center mb-8 md:mb-12">
                        <div
                            class="inline-block px-3 py-1 md:px-4 md:py-2 bg-blue-100 text-blue-700 rounded-full text-xs md:text-sm font-medium mb-3 md:mb-4">
                            FAQ</div>
                        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 md:mb-4">Frequently
                            Asked Questions</h2>
                    </div>
                    <div class="max-w-3xl mx-auto space-y-3 md:space-y-4">
                        @foreach($doctor->faqs as $faq)
                        <details class="bg-gray-50 rounded-xl overflow-hidden group cursor-pointer">
                            <summary
                                class="px-4 py-3 md:px-6 md:py-4 font-semibold text-gray-900 text-sm md:text-base cursor-pointer hover:text-blue-600 transition-colors flex items-center justify-between">
                                <span>{{ $faq->question }}</span>
                                <i class="ri-add-line text-lg md:text-xl group-open:rotate-45 transition-transform"></i>
                            </summary>
                            <div class="px-4 pb-3 md:px-6 md:pb-4">
                                <p class="text-gray-600 text-sm md:text-base leading-relaxed">{{ $faq->answer }}</p>
                            </div>
                        </details>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            <!-- Contact Section -->
            <section id="contact" class="py-12 md:py-20 bg-gradient-to-br from-blue-50/50 via-white to-sky-50/30">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-8 md:mb-12">
                        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-800 mb-3 md:mb-4">Book Your
                            Appointment</h2>
                        <p class="text-sm md:text-lg text-gray-600">Schedule your appointment with Dr.
                            {{ explode(' ', $doctor->name)[0] }} today.</p>
                    </div>
                    <div
                        class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-blue-100/50 p-5 md:p-8">
                        <button data-open
                            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-3 md:py-4 px-4 rounded-xl font-medium text-sm md:text-lg transition-all shadow-md hover:shadow-xl">
                            <i class="ri-calendar-check-line mr-2"></i>Book Appointment Online
                        </button>
                        <div class="mt-8 pt-6 border-t border-gray-200/50 text-center">
                            <h3 class="text-base md:text-lg font-medium text-gray-800 mb-4">Need Immediate Assistance?
                            </h3>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="tel:{{ $doctor->mobile }}"
                                    class="flex items-center justify-center space-x-2 text-blue-600 hover:text-blue-700 transition-colors">
                                    <div
                                        class="w-8 h-8 md:w-10 md:h-10 bg-blue-50 rounded-full flex items-center justify-center">
                                        <i class="ri-phone-line text-blue-600 text-sm md:text-base"></i>
                                    </div>
                                    <span class="font-medium text-sm md:text-base">Call: {{ $doctor->mobile }}</span>
                                </a>
                                <a href="mailto:{{ $doctor->email }}"
                                    class="flex items-center justify-center space-x-2 text-blue-600 hover:text-blue-700 transition-colors">
                                    <div
                                        class="w-8 h-8 md:w-10 md:h-10 bg-blue-50 rounded-full flex items-center justify-center">
                                        <i class="ri-mail-line text-blue-600 text-sm md:text-base"></i>
                                    </div>
                                    <span class="font-medium text-sm md:text-base">Email: {{ $doctor->email }}</span>
                                </a>
                            </div>
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <div class="flex items-center justify-center gap-4 text-sm text-gray-500">
                                    <span class="flex items-center gap-1"><i class="ri-time-line text-blue-500"></i>
                                        Mon-Fri: 9AM-6PM</span>
                                    <span class="flex items-center gap-1"><i class="ri-map-pin-line text-blue-500"></i>
                                        {{ $doctor->city ?? 'Dhaka' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer id="footer" class="bg-gray-50 border-t border-gray-200 py-8 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div>
                        <div class="text-xl md:text-2xl font-bold text-blue-600 mb-3 pacifico-font">
                            {{ $doctor->name }}
                        </div>
                        <p class="text-sm text-gray-600 mb-4">
                            {{ $doctor->profile->about_short ?? 'Compassionate healthcare with over ' . ($doctor->profile->years_experience ?? '15') . ' years of experience.' }}
                        </p>
                        <div class="flex space-x-3">
                            <a href="#"
                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 hover:bg-blue-200 transition-colors"><i
                                    class="ri-facebook-fill text-sm"></i></a>
                            <a href="#"
                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 hover:bg-blue-200 transition-colors"><i
                                    class="ri-twitter-fill text-sm"></i></a>
                            <a href="#"
                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 hover:bg-blue-200 transition-colors"><i
                                    class="ri-linkedin-fill text-sm"></i></a>
                            <a href="#"
                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 hover:bg-blue-200 transition-colors"><i
                                    class="ri-instagram-fill text-sm"></i></a>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="/" class="text-gray-600 hover:text-blue-600 transition-colors text-sm">Home</a>
                            </li>
                            <li><a href="#about"
                                    class="text-gray-600 hover:text-blue-600 transition-colors text-sm">About Us</a>
                            </li>
                            <li><a href="#services"
                                    class="text-gray-600 hover:text-blue-600 transition-colors text-sm">Services</a>
                            </li>
                            <li><button data-open
                                    class="text-gray-600 hover:text-blue-600 transition-colors text-sm">Book
                                    Appointment</button></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">Contact Info</h3>
                        <div class="space-y-2">
                            <div class="flex items-start space-x-2">
                                <i class="ri-map-pin-line text-blue-600 mt-0.5 text-sm"></i>
                                <span
                                    class="text-gray-600 text-sm">{{ $doctor->address ?? '123 Medical Center Drive, Suite 200, New York, NY 10001' }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="ri-phone-line text-blue-600 text-sm"></i>
                                <span class="text-gray-600 text-sm">{{ $doctor->mobile }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="ri-mail-line text-blue-600 text-sm"></i>
                                <span class="text-gray-600 text-sm">{{ $doctor->email }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">Hours</h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p>Monday - Friday: 9:00 AM - 6:00 PM</p>
                            <p>Saturday: 10:00 AM - 2:00 PM</p>
                            <p>Sunday: Closed</p>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-200 mt-6 md:mt-8 pt-6 text-center">
                    <p class="text-gray-600 text-xs md:text-sm">© {{ date('Y') }} {{ $doctor->name }} Medical Practice.
                        All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Booking Modal (same as before, condensed for space) -->
    <div class="modal-overlay-container" data-overlay style="display: none;">
        <div class="enhanced-modal">
            <header class="enhanced-header">
                <h2>Book Appointment with Dr. {{ $doctor->name }}</h2>
                <button class="close-btn" type="button" data-close>&times;</button>
            </header>
            <form id="appointment-form" data-form method="POST" action="{{ route('appointments.store') }}">
                @csrf
                <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                <div class="enhanced-form">
                    <div class="form-step active" data-pane="1">
                        <div class="selection-grid">
                            <div class="form-card">
                                <div class="form-card-header"><i class="ri-video-chat-line"></i>
                                    <h5>Consultation Type</h5>
                                </div>
                                <div class="form-card-body">
                                    <select name="consultation_type" class="enhanced-select" data-consultation-select
                                        required>
                                        <option value="">Select type</option>
                                        <option value="online" {{ !$doctor->accepts_virtual_visits ? 'disabled' : '' }}>
                                            Online Consultation</option>
                                        <option value="offline">In-person Visit</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-card" data-chamber-card>
                                <div class="form-card-header"><i class="ri-building-line"></i>
                                    <h5>Select Chamber</h5>
                                </div>
                                <div class="form-card-body">
                                    <select name="chamber_id" class="enhanced-select" data-chamber-select>
                                        <option value="">Select chamber</option>
                                        @foreach ($chambers->where('is_active', true) as $chamber)
                                        <option value="{{ $chamber->id }}" data-fees="{{ $chamber->fees }}"
                                            data-availability-url="{{ route('chambers.slots', ['chamber' => $chamber->id, 'date' => '__DATE__']) }}">
                                            {{ $chamber->name }} - ৳{{ number_format($chamber->fees) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-card">
                                <div class="form-card-header"><i class="ri-stethoscope-line"></i>
                                    <h5>Reason for Visit</h5>
                                </div>
                                <div class="form-card-body">
                                    <select name="service_type" class="enhanced-select" data-service-select required>
                                        <option value="">Select reason</option>
                                        <option value="New Patient Visit">New Patient Visit</option>
                                        <option value="Annual Physical">Annual Physical</option>
                                        <option value="Follow-up Visit">Follow-up Visit</option>
                                        <option value="Acute Illness">Acute Illness</option>
                                        <option value="Chronic Condition">Chronic Condition</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="schedule-section" data-schedule-section style="display: none;">
                            <div class="calendar-container">
                                <div class="calendar-header">
                                    <button type="button" class="calendar-nav" data-cal-prev>←</button>
                                    <div class="month-year"><span class="month" data-month>—</span> <span class="year"
                                            data-year>—</span></div>
                                    <button type="button" class="calendar-nav" data-cal-next>→</button>
                                </div>
                                <div class="calendar-grid" data-calendar-grid></div>
                            </div>
                            <div class="time-slots-container mt-4">
                                <div class="time-slots-grid" data-slots-placeholder>
                                    <div style="text-align:center;padding:20px;">Select a date to view slots</div>
                                </div>
                                <div class="time-slots-grid" data-time-slots style="display: none;"></div>
                            </div>
                        </div>
                        <input type="hidden" name="appointment_date" data-appointment-date>
                        <input type="hidden" name="appointment_time" data-appointment-time>
                    </div>
                    <div class="form-step" data-pane="2">
                        <div class="form-group"><label class="form-label">First Name *</label><input type="text"
                                name="patient_first_name" class="form-input" placeholder="John" required></div>
                        <div class="form-group"><label class="form-label">Last Name *</label><input type="text"
                                name="patient_last_name" class="form-input" placeholder="Doe" required></div>
                        <div class="form-group"><label class="form-label">Email *</label><input type="email"
                                name="patient_email" class="form-input" placeholder="john@example.com" required></div>
                        <div class="form-group"><label class="form-label">Phone *</label><input type="tel"
                                name="patient_phone" class="form-input" placeholder="+880 1XXX XXXXXX" required></div>
                        <div class="form-group"><label class="form-label">Reason (Optional)</label><textarea
                                name="notes" class="form-input" rows="3"
                                placeholder="Describe your symptoms..."></textarea></div>
                        <div class="mt-4"><label class="flex items-center gap-2"><input type="checkbox"
                                    name="terms_agreed" required><span class="text-sm">I agree to terms &
                                    conditions</span></label></div>
                    </div>
                    <div class="form-step" data-pane="3">
                        <div class="success-section">
                            <div class="success-icon"><i class="ri-checkbox-circle-fill"></i></div>
                            <h3 class="text-xl font-bold">Appointment Confirmed!</h3>
                            <p class="text-gray-600">Your appointment has been successfully booked.</p>
                            <div class="appointment-details mt-4" data-success-details></div>
                            <div class="flex flex-col sm:flex-row gap-3 mt-6 justify-center">
                                <button type="button" class="btn btn-primary" data-close>Close</button>
                                <button type="button" class="btn btn-secondary" data-download-pdf>Download PDF</button>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="enhanced-footer" data-footer>
                    <button type="button" class="btn btn-secondary" data-prev style="display: none;">Back</button>
                    <button type="button" class="btn btn-primary" data-next>Continue</button>
                    <button type="submit" class="btn btn-success" data-submit style="display: none;">Confirm
                        Booking</button>
                </footer>
            </form>
        </div>
    </div>

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const nav = document.querySelector('header nav');

        if (mobileMenuButton && nav) {
            mobileMenuButton.addEventListener('click', function() {
                nav.classList.toggle('hidden');
                nav.classList.toggle('flex');
                nav.classList.toggle('flex-col');
                nav.classList.toggle('absolute');
                nav.classList.toggle('top-16');
                nav.classList.toggle('left-0');
                nav.classList.toggle('right-0');
                nav.classList.toggle('bg-white');
                nav.classList.toggle('p-5');
                nav.classList.toggle('space-y-3');
                nav.classList.toggle('shadow-lg');
                nav.classList.toggle('z-50');
                nav.classList.toggle('border-b');
            });
        }

        // Close mobile menu when clicking a link
        document.querySelectorAll('nav a, nav button').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    nav.classList.add('hidden');
                    nav.classList.remove('flex', 'flex-col', 'absolute', 'top-16', 'left-0', 'right-0', 'bg-white', 'p-5', 'space-y-3', 'shadow-lg', 'z-50', 'border-b');
                }
            });
        });
    });

    // AppointmentBooking Class
    class AppointmentBooking {
        constructor() {
            this.currentStep = 1;
            this.totalSteps = 2;
            this.selectedChamber = null;
            this.selectedDate = null;
            this.selectedTime = null;
            this.currentMonth = new Date().getMonth();
            this.currentYear = new Date().getFullYear();
            this.isOnlineConsultation = false;
            this.initializeElements();
            this.initializeEventListeners();
            this.generateCalendar();
        }

        initializeElements() {
            this.modalOverlay = document.querySelector('[data-overlay]');
            this.openBtn = document.querySelectorAll('[data-open]');
            this.closeBtn = document.querySelector('[data-close]');
            this.form = document.querySelector('[data-form]');
            this.panes = document.querySelectorAll('[data-pane]');
            this.prevBtn = document.querySelector('[data-prev]');
            this.nextBtn = document.querySelector('[data-next]');
            this.submitBtn = document.querySelector('[data-submit]');
            this.consultationSelect = document.querySelector('[data-consultation-select]');
            this.chamberSelect = document.querySelector('[data-chamber-select]');
            this.serviceSelect = document.querySelector('[data-service-select]');
            this.chamberCard = document.querySelector('[data-chamber-card]');
            this.scheduleSection = document.querySelector('[data-schedule-section]');
            this.calendarGrid = document.querySelector('[data-calendar-grid]');
            this.monthDisplay = document.querySelector('[data-month]');
            this.yearDisplay = document.querySelector('[data-year]');
            this.calPrev = document.querySelector('[data-cal-prev]');
            this.calNext = document.querySelector('[data-cal-next]');
            this.timeSlotsContainer = document.querySelector('[data-time-slots]');
            this.slotsPlaceholder = document.querySelector('[data-slots-placeholder]');
            this.appointmentDateInput = document.querySelector('[data-appointment-date]');
            this.appointmentTimeInput = document.querySelector('[data-appointment-time]');
        }

        initializeEventListeners() {
            // Open modal - handle multiple buttons with data-open attribute
            if (this.openBtn) {
                this.openBtn.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.openModal();
                    });
                });
            }

            if (this.closeBtn) {
                this.closeBtn.addEventListener('click', () => this.closeModal());
            }

            if (this.modalOverlay) {
                this.modalOverlay.addEventListener('click', (e) => {
                    if (e.target === this.modalOverlay) this.closeModal();
                });
            }

            if (this.prevBtn) this.prevBtn.addEventListener('click', () => this.previousStep());
            if (this.nextBtn) this.nextBtn.addEventListener('click', () => this.nextStep());
            if (this.submitBtn) this.submitBtn.addEventListener('click', (e) => this.handleFormSubmit(e));
            if (this.consultationSelect) this.consultationSelect.addEventListener('change', (e) => this.onConsultationChange(e.target.value));
            if (this.chamberSelect) this.chamberSelect.addEventListener('change', (e) => this.onChamberChange(e.target.value));
            if (this.calPrev) this.calPrev.addEventListener('click', () => this.changeMonth(-1));
            if (this.calNext) this.calNext.addEventListener('click', () => this.changeMonth(1));
            if (this.form) this.form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        openModal() {
            if (this.modalOverlay) {
                this.modalOverlay.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                this.resetForm();
                this.generateCalendar();
            }
        }

        closeModal() {
            if (this.modalOverlay) {
                this.modalOverlay.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }

        resetForm() {
            this.currentStep = 1;
            this.selectedChamber = null;
            this.selectedDate = null;
            this.selectedTime = null;
            this.isOnlineConsultation = false;
            if (this.form) this.form.reset();
            if (this.scheduleSection) this.scheduleSection.style.display = 'none';
            this.clearCalendarSelection();
            this.clearTimeSlots();
            this.updateProgress();
            this.showStep(1);
        }

        updateProgress() {
            if (this.prevBtn) this.prevBtn.style.display = this.currentStep > 1 ? 'flex' : 'none';
            if (this.nextBtn && this.submitBtn) {
                if (this.currentStep === this.totalSteps) {
                    this.nextBtn.style.display = 'none';
                    this.submitBtn.style.display = 'flex';
                } else {
                    this.nextBtn.style.display = 'flex';
                    this.submitBtn.style.display = 'none';
                }
            }
        }

        showStep(stepNumber) {
            this.panes.forEach(pane => {
                const paneNumber = parseInt(pane.getAttribute('data-pane'));
                pane.style.display = paneNumber === stepNumber ? 'block' : 'none';
            });
            this.currentStep = stepNumber;
            this.updateProgress();
        }

        onConsultationChange(type) {
            this.isOnlineConsultation = type === 'online';
            if (this.isOnlineConsultation) {
                this.selectedChamber = {
                    availabilityUrl: "{{ route('doctors.online.slots', ['doctor' => $doctor->id, 'date' => '__DATE__']) }}"
                };
                if (this.chamberSelect) {
                    this.chamberSelect.value = '';
                    this.chamberSelect.disabled = true;
                }
                if (this.scheduleSection) this.scheduleSection.style.display = 'block';
            } else {
                this.selectedChamber = null;
                if (this.chamberSelect) this.chamberSelect.disabled = false;
                if (!this.chamberSelect?.value) this.clearScheduleSection();
            }
            this.clearCalendarSelection();
            this.clearTimeSlots();
        }

        onChamberChange(chamberId) {
            if (!chamberId) {
                this.clearScheduleSection();
                return;
            }
            const chamberOption = this.chamberSelect.querySelector(`option[value="${chamberId}"]`);
            if (!chamberOption) return;
            this.selectedChamber = {
                id: chamberId,
                name: chamberOption.textContent.split('৳')[0].trim(),
                fees: chamberOption.getAttribute('data-fees'),
                availabilityUrl: chamberOption.getAttribute('data-availability-url')
            };
            if (this.scheduleSection) this.scheduleSection.style.display = 'block';
            this.clearCalendarSelection();
            this.clearTimeSlots();
        }

        clearScheduleSection() {
            if (this.scheduleSection) this.scheduleSection.style.display = 'none';
            this.clearCalendarSelection();
            this.clearTimeSlots();
        }

        clearCalendarSelection() {
            if (this.calendarGrid) {
                this.calendarGrid.querySelectorAll('.calendar-day').forEach(day => day.classList.remove('selected'));
            }
            this.selectedDate = null;
        }

        clearTimeSlots() {
            if (this.timeSlotsContainer) this.timeSlotsContainer.style.display = 'none';
            if (this.slotsPlaceholder) this.slotsPlaceholder.style.display = 'block';
            this.selectedTime = null;
        }

        generateCalendar() {
            if (!this.calendarGrid) return;
            const today = new Date();
            const firstDay = new Date(this.currentYear, this.currentMonth, 1);
            const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
            const startingDay = firstDay.getDay();
            const totalDays = lastDay.getDate();
            if (this.monthDisplay) this.monthDisplay.textContent = firstDay.toLocaleDateString('en-US', { month: 'long' });
            if (this.yearDisplay) this.yearDisplay.textContent = this.currentYear;
            this.calendarGrid.innerHTML = '';
            const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            daysOfWeek.forEach(day => {
                const dayHeader = document.createElement('div');
                dayHeader.className = 'day-header';
                dayHeader.textContent = day;
                this.calendarGrid.appendChild(dayHeader);
            });
            for (let i = 0; i < startingDay; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'calendar-day empty';
                this.calendarGrid.appendChild(emptyCell);
            }
            for (let day = 1; day <= totalDays; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                const dateString = `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                dayElement.setAttribute('data-date', dateString);
                dayElement.textContent = day;
                const currentDate = new Date(this.currentYear, this.currentMonth, day);
                currentDate.setHours(0, 0, 0, 0);
                today.setHours(0, 0, 0, 0);
                if (currentDate.getTime() === today.getTime()) dayElement.classList.add('today');
                if (currentDate < today) {
                    dayElement.classList.add('past', 'disabled');
                } else {
                    dayElement.classList.add('available');
                    dayElement.addEventListener('click', () => this.selectDate(dayElement));
                }
                this.calendarGrid.appendChild(dayElement);
            }
        }

        changeMonth(direction) {
            this.currentMonth += direction;
            if (this.currentMonth < 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else if (this.currentMonth > 11) {
                this.currentMonth = 0;
                this.currentYear++;
            }
            this.generateCalendar();
            this.clearCalendarSelection();
            this.clearTimeSlots();
        }

        async selectDate(dayElement) {
            this.calendarGrid.querySelectorAll('.calendar-day').forEach(day => day.classList.remove('selected'));
            dayElement.classList.add('selected');
            this.selectedDate = dayElement.getAttribute('data-date');
            if (this.appointmentDateInput) this.appointmentDateInput.value = this.selectedDate;
            await this.loadTimeSlots(this.selectedDate);
        }

        async loadTimeSlots(date) {
            if (!this.selectedChamber?.availabilityUrl) return;
            if (this.slotsPlaceholder) {
                this.slotsPlaceholder.style.display = 'block';
                this.slotsPlaceholder.innerHTML = '<div style="text-align:center;padding:20px;">Loading slots...</div>';
            }
            try {
                const url = this.selectedChamber.availabilityUrl.replace('__DATE__', date);
                const response = await fetch(url);
                const data = await response.json();
                this.displayTimeSlots(data.slots || []);
            } catch (error) {
                console.error('Error:', error);
                this.displayTimeSlots([]);
            }
        }

        displayTimeSlots(slots) {
            if (!this.timeSlotsContainer || !this.slotsPlaceholder) return;
            this.timeSlotsContainer.innerHTML = '';
            if (slots.length === 0) {
                this.slotsPlaceholder.style.display = 'block';
                this.slotsPlaceholder.innerHTML = '<div style="text-align:center;padding:20px;">No slots available</div>';
                this.timeSlotsContainer.style.display = 'none';
                return;
            }
            this.slotsPlaceholder.style.display = 'none';
            this.timeSlotsContainer.style.display = 'grid';
            slots.forEach(slot => {
                const timeParts = slot.start.split(':');
                const hour = parseInt(timeParts[0]);
                const minute = timeParts[1];
                const period = hour >= 12 ? 'PM' : 'AM';
                const displayHour = hour % 12 || 12;
                const slotElement = document.createElement('div');
                slotElement.className = 'time-slot';
                slotElement.innerHTML = `${displayHour}:${minute} ${period}`;
                slotElement.setAttribute('data-time', slot.start);
                slotElement.addEventListener('click', () => this.selectTime(slotElement, slot.start));
                this.timeSlotsContainer.appendChild(slotElement);
            });
        }

        selectTime(slotElement, time) {
            this.timeSlotsContainer.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
            slotElement.classList.add('selected');
            this.selectedTime = time;
            if (this.appointmentTimeInput) this.appointmentTimeInput.value = this.selectedTime;
        }

        previousStep() {
            if (this.currentStep > 1) this.showStep(this.currentStep - 1);
        }

        nextStep() {
            if (this.currentStep === 1 && (!this.selectedDate || !this.selectedTime)) {
                alert('Please select a date and time slot');
                return;
            }
            this.showStep(this.currentStep + 1);
        }

        async handleFormSubmit(e) {
            e.preventDefault();
            if (!this.validateStep() || !this.selectedDate || !this.selectedTime) {
                alert('Please complete all fields');
                return;
            }
            if (this.submitBtn) {
                this.submitBtn.disabled = true;
                this.submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Processing...';
            }
            try {
                const formData = new FormData(this.form);
                const response = await fetch(this.form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                const result = await response.json();
                if (result.success) {
                    this.showStep(3);
                    this.displaySuccessDetails(result.appointment);
                } else {
                    alert('Booking failed: ' + (result.message || 'Try again'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred');
            } finally {
                if (this.submitBtn) {
                    this.submitBtn.disabled = false;
                    this.submitBtn.innerHTML = '<i class="ri-calendar-check-line"></i> Confirm Booking';
                }
            }
        }

        validateStep() {
            const firstName = this.form.querySelector('[name="patient_first_name"]')?.value;
            const lastName = this.form.querySelector('[name="patient_last_name"]')?.value;
            const email = this.form.querySelector('[name="patient_email"]')?.value;
            const phone = this.form.querySelector('[name="patient_phone"]')?.value;
            const terms = this.form.querySelector('[name="terms_agreed"]')?.checked;
            if (!firstName || !lastName || !email || !phone) {
                alert('Please fill all patient details');
                return false;
            }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                alert('Enter valid email');
                return false;
            }
            if (!terms) {
                alert('Accept terms to continue');
                return false;
            }
            return true;
        }

        displaySuccessDetails(appointment) {
            const detailsElement = document.querySelector('[data-success-details]');
            if (detailsElement && appointment) {
                detailsElement.innerHTML = `
                    <div class="space-y-2">
                        <div class="flex justify-between"><span class="text-gray-500">Appointment ID:</span><strong>#${appointment.id}</strong></div>
                        <div class="flex justify-between"><span class="text-gray-500">Status:</span><span class="text-green-600 font-semibold">Confirmed</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Date:</span><strong>${appointment.appointment_date}</strong></div>
                        <div class="flex justify-between"><span class="text-gray-500">Time:</span><strong>${appointment.appointment_time}</strong></div>
                    </div>
                `;
            }
            const downloadBtn = document.querySelector('[data-download-pdf]');
            if (downloadBtn && appointment) {
                downloadBtn.onclick = () => {
                    const element = document.getElementById('pdf-content');
                    if (element) {
                        html2pdf().set({
                            margin: [10, 10],
                            filename: `appointment-${appointment.id}.pdf`,
                            image: { type: 'jpeg', quality: 0.98 },
                            html2canvas: { scale: 2 },
                            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                        }).from(element).save();
                    }
                };
            }
        }
    }

    // Initialize the booking system when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        const modalOverlay = document.querySelector('[data-overlay]');
        if (modalOverlay) {
            window.bookingSystem = new AppointmentBooking();
            console.log('Appointment booking system initialized');
        }
    });
</script>
</body>

</html>