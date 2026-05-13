<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.seo')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.5.0/remixicon.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
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
        background: linear-gradient(135deg, #0d9488, #0f766e);
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
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
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
        color: #0d9488;
        border: 1px solid #0d9488;
    }

    .btn-secondary:hover {
        background: #f0fdfa;
    }

    .btn-primary {
        background: #0d9488;
        color: white;
    }

    .btn-primary:hover {
        background: #0f766e;
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
        background: rgba(13, 148, 136, 0.05);
        border-bottom: 1px solid #e5e7eb;
    }

    .form-card-header i {
        font-size: 20px;
        color: #0d9488;
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
        border-color: #0d9488;
        color: #0d9488;
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
        background: #0d9488 !important;
        color: white;
    }

    .calendar-day.available:hover {
        background: rgba(13, 148, 136, 0.1);
        border-color: #0d9488;
    }

    .calendar-day.today {
        background: rgba(13, 148, 136, 0.1);
        color: #0d9488;
        border-color: #0d9488;
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
        background: #0d9488;
        color: white;
        border-color: #0d9488;
    }

    .time-slot:hover:not(.disabled) {
        border-color: #0d9488;
        background: rgba(13, 148, 136, 0.05);
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

        .time-slots-grid {
            grid-template-columns: repeat(auto-fill, minmax(75px, 1fr));
            gap: 8px;
        }
    }
    </style>
</head>

<body class="bg-white">
    @include('partials.analytics-body')
    <div class="min-h-screen">
        <!-- Header -->
        <header
            class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md shadow-lg border-b border-slate-200/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-3">
                    <div class="flex items-center gap-3 group">
                        <div
                            class="w-12 h-12 bg-teal-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300">
                            <i
                                class="ri-hospital-line text-2xl text-white w-8 h-8 flex items-center justify-center"></i>
                        </div>
                        <div>
                            <h1 class="text-sm leading-4 text-slate-600 font-medium">{{ $doctor->name }}</h1>
                            <p class="text-xl font-bold tracking-tight text-slate-900 mt-0.5">Medical Practice</p>
                        </div>
                    </div>
                    <nav class="hidden lg:flex items-center gap-8">
    <a href="/" class="text-sm font-medium transition-all duration-300 hover:text-teal-600 relative text-teal-600 cursor-pointer">Home</a>
    <a href="#about" class="text-sm font-medium transition-all duration-300 hover:text-teal-600 relative text-slate-700 cursor-pointer">About</a>
    <a href="#services" class="text-sm font-medium transition-all duration-300 hover:text-teal-600 relative text-slate-700 cursor-pointer">Services</a>
    <a href="#expertise" class="text-sm font-medium transition-all duration-300 hover:text-teal-600 relative text-slate-700 cursor-pointer">Expertise</a>
    @if($doctor->testimonials->count() > 0)
        <a href="#testimonials" class="text-sm font-medium transition-all duration-300 hover:text-teal-600 relative text-slate-700 cursor-pointer">Testimonials</a>
    @endif
    <a href="#contact" class="text-sm font-medium transition-all duration-300 hover:text-teal-600 relative text-slate-700 cursor-pointer">Contact</a>
    
    @auth
        <a href="{{ url('admin/dashboard') }}" class="text-sm font-medium transition-all duration-300 hover:text-teal-600 text-slate-700 cursor-pointer">Dashboard</a>
        <button data-open class="inline-flex items-center gap-2 bg-teal-600 text-white px-6 py-2.5 rounded-lg font-medium shadow-lg hover:shadow-xl hover:bg-teal-700 transition-all duration-300 transform hover:scale-105 whitespace-nowrap cursor-pointer">
            <i class="ri-calendar-line w-4 h-4 flex items-center justify-center"></i>Book Appointment
        </button>
    @else
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-teal-600 text-white px-6 py-2.5 rounded-lg font-medium shadow-lg hover:shadow-xl hover:bg-teal-700 transition-all duration-300 transform hover:scale-105 whitespace-nowrap cursor-pointer">
            <i class="ri-login-box-line w-4 h-4 flex items-center justify-center"></i>Login
        </a>
    @endauth
</nav>
                    <button id="mobileMenuButton"
                        class="lg:hidden p-2 rounded-lg bg-slate-100 hover:bg-slate-200 transition-colors duration-300"
                        aria-label="Toggle menu">
                        <i class="ri-menu-line text-xl w-6 h-6 flex items-center justify-center"></i>
                    </button>
                </div>
            </div>
        </header>

        <main>
            <!-- Hero Section -->
            <section class="relative h-screen overflow-hidden">
                <div class="absolute inset-0">
                    <img alt="Medical Excellence" class="w-full h-full object-cover object-top"
                        src="{{ $doctor->galleries->first()->image_url ?? 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=1920&h=1080&fit=crop' }}">
                    <div class="absolute inset-0 bg-slate-900/70"></div>
                </div>
                <div class="relative h-full flex items-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="max-w-3xl text-white">
                            <div
                                class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full mb-6 border border-white/20">
                                <div class="w-2 h-2 bg-teal-400 rounded-full animate-pulse"></div>
                                <span class="text-sm font-medium">{{ $doctor->name }} Medical Practice</span>
                            </div>
                            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold leading-tight mb-6">
                                {{ $doctor->profile->headline ?? 'Exceptional Healthcare' }}
                                <span
                                    class="block text-teal-300">{{ $doctor->profile->subheadline ?? 'For Every Patient' }}</span>
                            </h1>
                            <p class="text-xl md:text-2xl text-slate-200 leading-relaxed mb-8 max-w-2xl">
                                {{ $doctor->profile->about_short ?? 'Providing compassionate, comprehensive medical care with over ' . ($doctor->profile->years_experience ?? '15') . ' years of experience.' }}
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <button data-open
                                    class="inline-flex items-center justify-center gap-3 bg-teal-600 hover:bg-teal-700 text-white px-8 py-4 rounded-lg font-semibold text-lg shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 whitespace-nowrap cursor-pointer">
                                    <i class="ri-calendar-line w-5 h-5 flex items-center justify-center"></i>Schedule
                                    Appointment
                                </button>
                                @if($doctor->accepts_virtual_visits)
                                <button
                                    class="inline-flex items-center justify-center gap-3 bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-lg font-semibold text-lg border border-white/20 hover:bg-white/20 transition-all duration-300 whitespace-nowrap cursor-pointer">
                                    <i class="ri-video-line w-5 h-5 flex items-center justify-center"></i>Virtual
                                    Consultation
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- About Section -->
            <section id="about" class="py-20 bg-slate-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid lg:grid-cols-2 gap-16 items-center">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 bg-teal-100 text-teal-700 px-4 py-2 rounded-full text-sm font-medium mb-6">
                                <i class="ri-user-heart-line w-4 h-4 flex items-center justify-center"></i>About Dr.
                                {{ explode(' ', $doctor->name)[0] ?? 'Doctor' }}
                            </div>
                            <h2 class="text-4xl lg:text-5xl font-bold text-slate-900 mb-6 leading-tight">Dedicated to
                                Your<span class="text-teal-600"> Health &amp; Wellness</span></h2>
                            <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                                {{ $doctor->profile->about_long ?? 'Dr. ' . $doctor->name . ' is a board-certified physician with over ' . ($doctor->profile->years_experience ?? '15') . ' years of experience in providing comprehensive healthcare services.' }}
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <button data-open
                                    class="inline-flex items-center justify-center gap-3 bg-teal-600 hover:bg-teal-700 text-white px-8 py-4 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 whitespace-nowrap cursor-pointer">
                                    <i class="ri-calendar-line w-5 h-5 flex items-center justify-center"></i>Schedule
                                    Consultation
                                </button>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                                <img alt="{{ $doctor->name }}" class="w-full h-[600px] object-cover object-top"
                                    src="{{ $doctor->photo ? url($doctor->photo) : 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=600&h=700&fit=crop' }}">
                                <div class="absolute inset-0 bg-teal-900/20"></div>
                            </div>
                            <div
                                class="absolute -bottom-8 -left-8 bg-white rounded-xl shadow-xl p-6 border border-slate-200">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-teal-600 rounded-lg flex items-center justify-center">
                                        <i class="ri-award-line text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-slate-900">
                                            {{ $doctor->profile->years_experience ?? '15' }}+</p>
                                        <p class="text-sm text-slate-600">Years Experience</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Value Cards -->
                    <div class="mt-20 grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div
                            class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-slate-100">
                            <div class="w-12 h-12 bg-teal-600 rounded-lg flex items-center justify-center mb-4"><i
                                    class="ri-heart-pulse-line text-white text-xl"></i></div>
                            <h3 class="text-xl font-semibold text-slate-900 mb-3">Comprehensive Care</h3>
                            <p class="text-slate-600 leading-relaxed">Complete healthcare services from preventive care
                                to specialized treatments.</p>
                        </div>
                        <div
                            class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-slate-100">
                            <div class="w-12 h-12 bg-teal-600 rounded-lg flex items-center justify-center mb-4"><i
                                    class="ri-user-heart-line text-white text-xl"></i></div>
                            <h3 class="text-xl font-semibold text-slate-900 mb-3">Patient-Centered</h3>
                            <p class="text-slate-600 leading-relaxed">Personalized treatment plans tailored to each
                                patient's unique needs.</p>
                        </div>
                        <div
                            class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-slate-100">
                            <div class="w-12 h-12 bg-teal-600 rounded-lg flex items-center justify-center mb-4"><i
                                    class="ri-shield-check-line text-white text-xl"></i></div>
                            <h3 class="text-xl font-semibold text-slate-900 mb-3">Evidence-Based</h3>
                            <p class="text-slate-600 leading-relaxed">Treatment approaches based on the latest medical
                                research.</p>
                        </div>
                        <div
                            class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-slate-100">
                            <div class="w-12 h-12 bg-teal-600 rounded-lg flex items-center justify-center mb-4"><i
                                    class="ri-time-line text-white text-xl"></i></div>
                            <h3 class="text-xl font-semibold text-slate-900 mb-3">Accessible Care</h3>
                            <p class="text-slate-600 leading-relaxed">Flexible scheduling and telemedicine options for
                                convenient healthcare access.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Education Section -->
            @if($doctor->educations->count() > 0)
            <section class="py-20 bg-white">
                <div class="container mx-auto px-6">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-800 mb-4">Education & Training</h2>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto">Dr. {{ explode(' ', $doctor->name)[0] }}'s
                            extensive educational background from prestigious institutions.</p>
                    </div>
                    <div class="grid md:grid-cols-2 gap-8">
                        @foreach($doctor->educations as $education)
                        <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-shadow duration-300">
                            <div class="flex items-start space-x-4">
                                <div
                                    class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="ri-graduation-cap-line text-teal-600 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $education->degree }}</h3>
                                    <p class="text-teal-600 font-medium mb-1">{{ $education->institution }}</p>
                                    <p class="text-gray-500 text-sm mb-3">
                                        {{ $education->start_year }}{{ $education->end_year ? '-' . $education->end_year : '' }}
                                    </p>
                                    <p class="text-gray-600">{{ $education->description }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            <!-- Certifications Section -->
            @if($doctor->certifications->count() > 0)
            <section class="py-20 bg-teal-50">
                <div class="container mx-auto px-6">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-800 mb-4">Board Certifications & Credentials</h2>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto">Maintaining the highest professional
                            standards through continuous certification.</p>
                    </div>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($doctor->certifications as $cert)
                        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="text-center">
                                <div
                                    class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="ri-shield-check-line text-teal-600 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $cert->title }}</h3>
                                <p class="text-teal-600 font-medium mb-1">{{ $cert->organization }}</p>
                                <p class="text-gray-500 text-sm">{{ $cert->year }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            <!-- Areas of Expertise Section -->
            @if($doctor->specialties->count() > 0)
            <section id="expertise" class="py-20 bg-teal-50">
                <div class="container mx-auto px-6">
                    <div class="text-center mb-16">
                        <div class="inline-flex items-center px-4 py-2 bg-teal-100 rounded-full mb-6">
                            <span class="text-teal-700 font-semibold text-sm">Areas of Expertise</span>
                        </div>
                        <h2 class="text-4xl font-bold text-gray-800 mb-4">Medical Specializations</h2>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto">Comprehensive expertise spanning multiple
                            areas of medicine.</p>
                    </div>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($doctor->specialties as $specialty)
                        <div
                            class="bg-white rounded-xl p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 border border-teal-100">
                            <div
                                class="w-16 h-16 bg-teal-600 text-white rounded-full flex items-center justify-center mb-4">
                                <i class="{{ $specialty->icon ?? 'ri-heart-pulse-line' }} text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">{{ $specialty->name }}</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $specialty->description }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            <!-- Services Section -->
            @if($doctor->services->count() > 0)
            <section id="services" class="py-24 bg-white relative overflow-hidden">
                <div class="container mx-auto px-6 relative z-10">
                    <div class="text-center mb-20">
                        <div class="inline-flex items-center px-4 py-2 bg-teal-100 rounded-full mb-6">
                            <span class="text-teal-700 font-semibold text-sm">Our Services</span>
                        </div>
                        <h2 class="text-5xl font-bold text-gray-900 mb-6">Comprehensive Healthcare<span
                                class="block text-3xl font-light text-gray-600 mt-2">Tailored to Your Needs</span></h2>
                    </div>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($doctor->services as $service)
                        <div
                            class="group relative bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                            <div
                                class="absolute inset-0 bg-teal-50 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            </div>
                            <div class="relative z-10">
                                <div
                                    class="w-16 h-16 bg-teal-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                    <i class="ri-stethoscope-line text-white text-2xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $service->title }}</h3>
                                <p class="text-gray-600 leading-relaxed mb-6">{{ $service->description }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            <!-- Statistics Section -->
            <section class="py-20 bg-gray-50">
                <div class="container mx-auto px-6">
                    <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-2xl p-8 text-center shadow-sm hover:shadow-md transition-all">
                            <div
                                class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ri-time-line text-teal-600 text-2xl"></i>
                            </div>
                            <div class="text-3xl font-bold text-gray-800 mb-2">
                                {{ $doctor->profile->years_experience ?? '15' }}+</div>
                            <div class="text-gray-600 font-medium">Years of Experience</div>
                        </div>
                        <div class="bg-white rounded-2xl p-8 text-center shadow-sm hover:shadow-md transition-all">
                            <div
                                class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ri-user-heart-line text-teal-600 text-2xl"></i>
                            </div>
                            <div class="text-3xl font-bold text-gray-800 mb-2">
                                {{ number_format($doctor->profile->patients_count ?? 5000) }}+</div>
                            <div class="text-gray-600 font-medium">Patients Treated</div>
                        </div>
                        <div class="bg-white rounded-2xl p-8 text-center shadow-sm hover:shadow-md transition-all">
                            <div
                                class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ri-star-line text-teal-600 text-2xl"></i>
                            </div>
                            <div class="text-3xl font-bold text-gray-800 mb-2">
                                {{ $doctor->profile->satisfaction_rate ?? '98' }}%</div>
                            <div class="text-gray-600 font-medium">Patient Satisfaction</div>
                        </div>
                        <div class="bg-white rounded-2xl p-8 text-center shadow-sm hover:shadow-md transition-all">
                            <div
                                class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ri-phone-line text-teal-600 text-2xl"></i>
                            </div>
                            <div class="text-3xl font-bold text-gray-800 mb-2">24/7</div>
                            <div class="text-gray-600 font-medium">Emergency Support</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials Section -->
            @if($doctor->testimonials->count() > 0)
            <section id="testimonials" class="py-20 bg-white">
                <div class="container mx-auto px-6">
                    <div class="text-center mb-16">
                        <div class="inline-flex items-center px-4 py-2 bg-teal-100 rounded-full mb-6">
                            <span class="text-teal-700 font-semibold text-sm">Patient Reviews</span>
                        </div>
                        <h2 class="text-4xl font-bold text-gray-800 mb-4">What Our Patients Say</h2>
                    </div>
                    <div class="max-w-4xl mx-auto">
                        @foreach($doctor->testimonials as $testimonial)
                        <div class="bg-teal-50 rounded-2xl shadow-xl p-8 md:p-12 border border-teal-100 mb-6">
                            <div class="text-center">
                                <div class="flex justify-center mb-6">
                                    @for($i = 1; $i <= 5; $i++) <i
                                        class="ri-star-fill {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }} text-2xl">
                                        </i>
                                        @endfor
                                </div>
                                <blockquote class="text-xl text-gray-700 leading-relaxed mb-8 italic">
                                    "{{ $testimonial->content }}"</blockquote>
                                <div class="flex items-center justify-center space-x-4">
                                    @if($testimonial->photo)
                                    <img alt="{{ $testimonial->patient_name }}"
                                        class="w-16 h-16 rounded-full object-cover"
                                        src="{{ url($testimonial->photo) }}">
                                    @else
                                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center">
                                        <i class="ri-user-fill text-teal-600 text-2xl"></i>
                                    </div>
                                    @endif
                                    <div class="text-left">
                                        <h4 class="font-semibold text-gray-800 text-lg">{{ $testimonial->patient_name }}
                                        </h4>
                                        <p class="text-gray-600">{{ $testimonial->since ?? 'Patient' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            <!-- FAQs Section -->
            @if($doctor->faqs->count() > 0)
            <section class="py-20 bg-white">
                <div class="container mx-auto px-6 lg:px-12">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-800 mb-4">Frequently Asked Questions</h2>
                    </div>
                    <div class="max-w-4xl mx-auto space-y-4">
                        @foreach($doctor->faqs as $faq)
                        <details
                            class="bg-gradient-to-br from-gray-50 to-teal-50 rounded-xl overflow-hidden group cursor-pointer">
                            <summary
                                class="px-8 py-6 font-semibold text-gray-900 text-lg cursor-pointer hover:text-teal-600 transition-colors flex items-center justify-between">
                                <span>{{ $faq->question }}</span>
                                <i class="ri-add-line text-2xl group-open:rotate-45 transition-transform"></i>
                            </summary>
                            <div class="px-8 pb-6">
                                <p class="text-gray-600 leading-relaxed">{{ $faq->answer }}</p>
                            </div>
                        </details>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            <!-- Contact Section -->
            <section id="contact" class="py-24 bg-teal-50 relative overflow-hidden">
                <div class="container mx-auto px-6 relative z-10">
                    <div class="text-center mb-16">
                        <div
                            class="inline-flex items-center px-6 py-3 bg-teal-100 rounded-full border border-teal-200 mb-6">
                            <span class="text-teal-700 font-semibold text-sm">Book Your Visit</span>
                        </div>
                        <h2 class="text-5xl font-light text-gray-800 mb-6">Ready to Begin Your<span
                                class="block font-semibold text-teal-600">Wellness Journey?</span></h2>
                    </div>
                    <div class="grid lg:grid-cols-3 gap-12 items-start">
                        <div class="space-y-6">
                            <div class="bg-white rounded-2xl p-6 shadow-lg border border-teal-100">
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="w-12 h-12 bg-teal-600 rounded-xl flex items-center justify-center"><i
                                            class="ri-phone-line text-white text-xl"></i></div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800">Call Us</h4>
                                        <p class="text-teal-600 font-medium">{{ $doctor->mobile }}</p>
                                    </div>
                                </div>
                                <p class="text-gray-500 text-sm">Available 24/7 for emergencies</p>
                            </div>
                            <div class="bg-white rounded-2xl p-6 shadow-lg border border-teal-100">
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="w-12 h-12 bg-teal-600 rounded-xl flex items-center justify-center"><i
                                            class="ri-mail-line text-white text-xl"></i></div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800">Email</h4>
                                        <p class="text-teal-600 font-medium">{{ $doctor->email }}</p>
                                    </div>
                                </div>
                                <p class="text-gray-500 text-sm">Response within 2 hours</p>
                            </div>
                            <div class="bg-white rounded-2xl p-6 shadow-lg border border-teal-100">
                                <div class="flex items-start space-x-4 mb-4">
                                    <div class="w-12 h-12 bg-teal-600 rounded-xl flex items-center justify-center"><i
                                            class="ri-map-pin-line text-white text-xl"></i></div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800">Location</h4>
                                        <p class="text-gray-600 text-sm">
                                            {{ $doctor->address ?? '123 Medical Center Drive, Suite 200' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-teal-600 rounded-2xl p-6 text-white">
                                <h4 class="text-xl font-semibold mb-3">Office Hours</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between"><span>Monday - Friday</span><span
                                            class="font-medium">8:00 AM - 6:00 PM</span></div>
                                    <div class="flex justify-between"><span>Saturday</span><span
                                            class="font-medium">9:00 AM - 2:00 PM</span></div>
                                    <div class="flex justify-between"><span>Sunday</span><span
                                            class="opacity-80">Closed</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-3xl p-8 shadow-xl border border-teal-100">
                                <h3 class="text-3xl font-semibold text-gray-800 mb-8 text-center">Schedule Your
                                    Appointment</h3>
                                <button data-open
                                    class="w-full bg-teal-600 hover:bg-teal-700 text-white py-4 px-6 rounded-xl font-semibold text-lg transition-all shadow-lg hover:shadow-xl">
                                    <i class="ri-calendar-check-line mr-3 text-xl"></i>Book Appointment Online
                                </button>
                                <div class="mt-8 text-center text-gray-500 text-sm">
                                    Or call us directly at <span
                                        class="font-semibold text-teal-600">{{ $doctor->mobile }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer id="footer" class="bg-gradient-to-br from-gray-900 to-emerald-900 text-white py-16">
            <div class="container mx-auto px-6">
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div>
                        <div class="flex items-center space-x-3 mb-6">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="ri-heart-pulse-line text-white text-xl"></i>
                            </div>
                            <div><span class="text-xl font-bold">{{ $doctor->name }}</span>
                                <div class="text-xs text-emerald-300 font-medium">Medical Specialist</div>
                            </div>
                        </div>
                        <p class="text-gray-300 mb-6 leading-relaxed">
                            {{ $doctor->profile->about_short ?? 'Providing compassionate healthcare with over ' . ($doctor->profile->years_experience ?? '15') . ' years of experience.' }}
                        </p>
                        <div class="flex space-x-3">
                            <a href="#"
                                class="w-10 h-10 bg-emerald-600/20 hover:bg-emerald-600 rounded-full flex items-center justify-center transition-all"><i
                                    class="ri-facebook-fill text-emerald-300 hover:text-white"></i></a>
                            <a href="#"
                                class="w-10 h-10 bg-emerald-600/20 hover:bg-emerald-600 rounded-full flex items-center justify-center transition-all"><i
                                    class="ri-twitter-fill text-emerald-300 hover:text-white"></i></a>
                            <a href="#"
                                class="w-10 h-10 bg-emerald-600/20 hover:bg-emerald-600 rounded-full flex items-center justify-center transition-all"><i
                                    class="ri-linkedin-fill text-emerald-300 hover:text-white"></i></a>
                            <a href="#"
                                class="w-10 h-10 bg-emerald-600/20 hover:bg-emerald-600 rounded-full flex items-center justify-center transition-all"><i
                                    class="ri-instagram-fill text-emerald-300 hover:text-white"></i></a>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-6 text-emerald-300">Quick Links</h3>
                        <ul class="space-y-3">
                            <li><a href="/"
                                    class="text-gray-300 hover:text-emerald-300 transition-colors flex items-center space-x-2"><i
                                        class="ri-arrow-right-s-line text-sm"></i><span>Home</span></a></li>
                            <li><a href="#about"
                                    class="text-gray-300 hover:text-emerald-300 transition-colors flex items-center space-x-2"><i
                                        class="ri-arrow-right-s-line text-sm"></i><span>About</span></a></li>
                            <li><a href="#services"
                                    class="text-gray-300 hover:text-emerald-300 transition-colors flex items-center space-x-2"><i
                                        class="ri-arrow-right-s-line text-sm"></i><span>Services</span></a></li>
                            <li><button data-open
                                    class="text-gray-300 hover:text-emerald-300 transition-colors flex items-center space-x-2"><i
                                        class="ri-arrow-right-s-line text-sm"></i><span>Book Appointment</span></button>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-6 text-emerald-300">Services</h3>
                        <ul class="space-y-3">
                            @foreach($doctor->services->take(6) as $service)
                            <li class="flex items-center space-x-2"><i
                                    class="ri-check-line text-emerald-400 text-sm"></i><span
                                    class="text-gray-300">{{ $service->title }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-6 text-emerald-300">Contact Info</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-8 h-8 bg-emerald-600/20 rounded-full flex items-center justify-center mt-1">
                                    <i class="ri-map-pin-line text-emerald-400 text-sm"></i></div>
                                <div class="text-gray-300 text-sm">
                                    {{ $doctor->address ?? '123 Medical Center Drive, Suite 200' }}</div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-emerald-600/20 rounded-full flex items-center justify-center"><i
                                        class="ri-phone-line text-emerald-400 text-sm"></i></div>
                                <span class="text-gray-300">{{ $doctor->mobile }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-emerald-600/20 rounded-full flex items-center justify-center"><i
                                        class="ri-mail-line text-emerald-400 text-sm"></i></div>
                                <span class="text-gray-300">{{ $doctor->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-t border-emerald-800/50 mt-12 pt-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-gray-400 text-sm">© {{ date('Y') }} {{ $doctor->name }} Medical Practice. All
                            rights reserved.</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Booking Modal -->
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
                nav.classList.toggle('top-20');
                nav.classList.toggle('left-0');
                nav.classList.toggle('right-0');
                nav.classList.toggle('bg-white');
                nav.classList.toggle('p-6');
                nav.classList.toggle('space-y-4');
                nav.classList.toggle('shadow-lg');
                nav.classList.toggle('z-50');
                nav.classList.toggle('border-b');
            });
        }

        // Close mobile menu when clicking a link
        document.querySelectorAll('nav a, nav button').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    nav.classList.add('hidden');
                    nav.classList.remove('flex', 'flex-col', 'absolute', 'top-20', 'left-0',
                        'right-0', 'bg-white', 'p-6', 'space-y-4', 'shadow-lg', 'z-50',
                        'border-b');
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
            if (this.openBtn) {
                this.openBtn.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.openModal();
                    });
                });
            }
            if (this.closeBtn) this.closeBtn.addEventListener('click', () => this.closeModal());
            if (this.modalOverlay) this.modalOverlay.addEventListener('click', (e) => {
                if (e.target === this.modalOverlay) this.closeModal();
            });
            if (this.prevBtn) this.prevBtn.addEventListener('click', () => this.previousStep());
            if (this.nextBtn) this.nextBtn.addEventListener('click', () => this.nextStep());
            if (this.submitBtn) this.submitBtn.addEventListener('click', (e) => this.handleFormSubmit(e));
            if (this.consultationSelect) this.consultationSelect.addEventListener('change', (e) => this
                .onConsultationChange(e.target.value));
            if (this.chamberSelect) this.chamberSelect.addEventListener('change', (e) => this.onChamberChange(e
                .target.value));
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
                this.calendarGrid.querySelectorAll('.calendar-day').forEach(day => day.classList.remove(
                'selected'));
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
            if (this.monthDisplay) this.monthDisplay.textContent = firstDay.toLocaleDateString('en-US', {
                month: 'long'
            });
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
                const dateString =
                    `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
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
                this.slotsPlaceholder.innerHTML =
                    '<div style="text-align:center;padding:20px;">Loading slots...</div>';
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
                this.slotsPlaceholder.innerHTML =
                    '<div style="text-align:center;padding:20px;">No slots available</div>';
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });
                const result = await response.json();
                if (result.success) {
                    this.showStep(3);
                    this.displaySuccessDetails(result.appointment);
                } else alert('Booking failed: ' + (result.message || 'Try again'));
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
                detailsElement.innerHTML =
                    `<div class="space-y-2"><div class="flex justify-between"><span class="text-gray-500">Appointment ID:</span><strong>#${appointment.id}</strong></div><div class="flex justify-between"><span class="text-gray-500">Status:</span><span class="text-green-600 font-semibold">Confirmed</span></div><div class="flex justify-between"><span class="text-gray-500">Date:</span><strong>${appointment.appointment_date}</strong></div><div class="flex justify-between"><span class="text-gray-500">Time:</span><strong>${appointment.appointment_time}</strong></div></div>`;
            }
            const downloadBtn = document.querySelector('[data-download-pdf]');
            if (downloadBtn && appointment) downloadBtn.onclick = () => {
                const element = document.getElementById('pdf-content');
                if (element) html2pdf().set({
                    margin: [10, 10],
                    filename: `appointment-${appointment.id}.pdf`,
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: 'a4',
                        orientation: 'portrait'
                    }
                }).from(element).save();
            };
        }
    }

    const modalOverlay = document.querySelector('[data-overlay]');
    if (modalOverlay) window.bookingSystem = new AppointmentBooking();
    </script>
</body>

</html>
