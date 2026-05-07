@extends('layouts.sass')

@section('title', 'Doctor Profile - Professional Medical Websites Made Simple')

@section('content')
<!-- Hero Section with Enhanced Visuals -->
<section class="relative pt-20 pb-12 md:pt-32 md:pb-16 overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-10 left-10 w-48 md:w-72 h-48 md:h-72 bg-[#318069]/5 rounded-full mix-blend-multiply filter blur-3xl animate-pulse"></div>
        <div class="absolute top-40 right-10 w-64 md:w-96 h-64 md:h-96 bg-[#FFC107]/5 rounded-full mix-blend-multiply filter blur-3xl animate-pulse delay-1000"></div>
        <div class="absolute bottom-20 left-1/3 w-56 md:w-80 h-56 md:h-80 bg-[#318069]/3 rounded-full mix-blend-multiply filter blur-3xl animate-pulse delay-500"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-8 md:gap-12 items-center">
            <!-- Left Content -->
            <div class="text-center lg:text-left">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-4 md:mb-6 leading-tight">
                    Your Digital Practice
                    <span class="relative inline-block">
                        <span class="relative z-10 text-[#318069]">Elevated</span>
                        <svg class="absolute -bottom-2 left-0 w-full h-2 md:h-3 text-[#FFC107]/40" viewBox="0 0 200 20">
                            <path d="M10,10 Q100,20 190,10" stroke="currentColor" fill="none" stroke-width="6 md:stroke-width-8"/>
                        </svg>
                    </span>
                </h1>

                <p class="text-base md:text-lg text-gray-600 mb-6 md:mb-8 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    Transform your medical practice with a stunning, professional website that attracts patients, showcases your expertise, and manages appointments—all in one seamless platform.
                </p>

                <div class="flex flex-col sm:flex-row gap-3 md:gap-4 mb-8 md:mb-12 justify-center lg:justify-start">
                    <a href="{{route('doctor.create')}}"
                       class="group inline-flex items-center justify-center px-6 md:px-8 py-3 md:py-4 bg-gradient-to-r from-[#318069] to-emerald-600 text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-xl hover:scale-[1.02] text-sm md:text-base">
                        <span>Start Free Trial</span>
                        <i class="ri-arrow-right-line ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                <!-- Trust Metrics -->
                <div class="grid grid-cols-3 gap-3 md:gap-6 p-4 md:p-6 bg-white/50 backdrop-blur-sm rounded-2xl border border-white/30 shadow-sm">
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-[#318069] mb-1">4.9</div>
                        <div class="flex justify-center gap-0.5 md:gap-1 mb-1">
                            <i class="ri-star-fill text-yellow-500 text-xs md:text-sm"></i>
                            <i class="ri-star-fill text-yellow-500 text-xs md:text-sm"></i>
                            <i class="ri-star-fill text-yellow-500 text-xs md:text-sm"></i>
                            <i class="ri-star-fill text-yellow-500 text-xs md:text-sm"></i>
                            <i class="ri-star-fill text-yellow-500 text-xs md:text-sm"></i>
                        </div>
                        <div class="text-xs md:text-sm text-gray-600">Rating</div>
                    </div>
                    <div class="text-center border-x border-gray-200">
                        <div class="text-2xl md:text-3xl font-bold text-[#318069] mb-1">10K+</div>
                        <p class="text-xs md:text-sm text-gray-600">Patients Served</p>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-[#318069] mb-1">24/7</div>
                        <p class="text-xs md:text-sm text-gray-600">Support</p>
                    </div>
                </div>
            </div>

            <!-- Right Content - Interactive Profile Demo -->
            <div class="relative mt-8 lg:mt-0">
                <div class="relative rounded-2xl md:rounded-3xl overflow-hidden shadow-2xl border-4 md:border-8 border-white">
                    <!-- Profile Demo Interface -->
                    <div class="bg-gradient-to-br from-gray-900 to-gray-800">
                        <!-- Mock Browser Header -->
                        <div class="flex items-center gap-1.5 md:gap-2 p-3 md:p-4 bg-gray-900 border-b border-gray-800">
                            <div class="flex gap-1.5 md:gap-2">
                                <div class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-red-500"></div>
                                <div class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-green-500"></div>
                            </div>
                            <div class="flex-1 text-center">
                                <span class="text-[10px] md:text-xs text-gray-400 truncate">dr-sarah-johnson.doctorsprofile.com</span>
                            </div>
                        </div>

                        <!-- Profile Content -->
                        <div class="p-4 md:p-6">
                            <!-- Doctor Header -->
                            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 md:gap-6 mb-6 md:mb-8">
                                <div class="relative">
                                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
                                        <i class="ri-user-line text-3xl md:text-4xl text-white"></i>
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 md:w-8 md:h-8 bg-green-500 rounded-full border-2 md:border-4 border-gray-800 flex items-center justify-center">
                                        <i class="ri-check-line text-xs md:text-sm text-white"></i>
                                    </div>
                                </div>
                                <div class="text-center sm:text-left">
                                    <h3 class="text-xl md:text-2xl font-bold text-white mb-1">Dr. Sarah Johnson, MD</h3>
                                    <p class="text-cyan-400 text-sm md:text-base mb-2">Cardiologist • 15 Years Experience</p>
                                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 md:gap-4">
                                        <div class="flex items-center gap-1">
                                            <i class="ri-star-fill text-yellow-500 text-xs md:text-sm"></i>
                                            <span class="text-white text-xs md:text-sm">4.9 (247 reviews)</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <i class="ri-map-pin-line text-gray-400 text-xs md:text-sm"></i>
                                            <span class="text-gray-300 text-xs md:text-sm">New York, NY</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabs - Responsive horizontal scroll on mobile -->
                            <div class="flex gap-1 mb-4 md:mb-6 bg-gray-800/50 rounded-lg p-1 overflow-x-auto">
                                <button class="flex-1 py-1.5 md:py-2 text-xs md:text-sm font-medium text-white bg-gray-700 rounded-md whitespace-nowrap px-2">Profile</button>
                                <button class="flex-1 py-1.5 md:py-2 text-xs md:text-sm font-medium text-gray-400 hover:text-white transition-colors whitespace-nowrap px-2">Services</button>
                                <button class="flex-1 py-1.5 md:py-2 text-xs md:text-sm font-medium text-gray-400 hover:text-white transition-colors whitespace-nowrap px-2">Appointments</button>
                                <button class="flex-1 py-1.5 md:py-2 text-xs md:text-sm font-medium text-gray-400 hover:text-white transition-colors whitespace-nowrap px-2">Reviews</button>
                            </div>

                            <!-- Profile Info Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4 mb-4 md:mb-6">
                                <div class="bg-gray-800/50 rounded-lg p-3 md:p-4">
                                    <div class="text-gray-400 text-[10px] md:text-xs mb-1">SPECIALIZATION</div>
                                    <div class="text-white text-sm md:text-base font-medium">Cardiology</div>
                                </div>
                                <div class="bg-gray-800/50 rounded-lg p-3 md:p-4">
                                    <div class="text-gray-400 text-[10px] md:text-xs mb-1">EXPERIENCE</div>
                                    <div class="text-white text-sm md:text-base font-medium">15 Years</div>
                                </div>
                                <div class="bg-gray-800/50 rounded-lg p-3 md:p-4">
                                    <div class="text-gray-400 text-[10px] md:text-xs mb-1">LANGUAGES</div>
                                    <div class="text-white text-sm md:text-base font-medium">English, Spanish</div>
                                </div>
                                <div class="bg-gray-800/50 rounded-lg p-3 md:p-4">
                                    <div class="text-gray-400 text-[10px] md:text-xs mb-1">AVAILABILITY</div>
                                    <div class="text-white text-sm md:text-base font-medium">Online & In-Person</div>
                                </div>
                            </div>

                            <!-- CTA Button -->
                            <button class="w-full py-2.5 md:py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-semibold rounded-lg transition-all flex items-center justify-center gap-2 text-sm md:text-base">
                                <i class="ri-calendar-line"></i>
                                <span>Book Appointment</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Floating Elements -->
                <div class="absolute -top-3 md:-top-6 -left-3 md:-left-6 w-24 h-24 md:w-32 md:h-32 bg-gradient-to-br from-[#318069]/20 to-transparent rounded-full blur-xl"></div>
                <div class="absolute -bottom-3 md:-bottom-6 -right-3 md:-right-6 w-32 h-32 md:w-40 md:h-40 bg-gradient-to-br from-[#FFC107]/20 to-transparent rounded-full blur-xl"></div>
            </div>
        </div>
    </div>
</section>

<!-- Visual Feature Showcase -->
<section id="demo" class="py-12 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 md:mb-16">
            <span class="inline-block px-4 md:px-5 py-1.5 md:py-2 bg-gradient-to-r from-[#318069]/10 to-emerald-100 text-[#318069] font-semibold rounded-full text-xs md:text-sm mb-3 md:mb-4">
                <i class="ri-sparkling-line mr-1 md:mr-2 text-sm"></i>
                What You Get
            </span>
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-2 md:mb-4">
                Beautiful, Professional Profiles That <span class="text-[#318069]">Convert</span>
            </h2>
            <p class="text-base md:text-lg text-gray-600 max-w-3xl mx-auto px-4">
                See how our platform transforms basic information into compelling digital experiences
            </p>
        </div>

        <!-- Interactive Feature Comparison -->
        <div class="grid lg:grid-cols-2 gap-8 md:gap-12 items-center mb-12 md:mb-20">
            <div class="order-2 lg:order-1">
                <div class="relative">
                    <div class="absolute -inset-4 bg-gradient-to-r from-[#318069]/10 to-transparent rounded-3xl blur-3xl"></div>
                    <div class="relative bg-white rounded-xl md:rounded-2xl p-6 md:p-8 shadow-2xl border border-gray-100">
                        <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-4 md:mb-6">Before: Basic Information</h3>
                        <div class="space-y-4 md:space-y-6">
                            <div class="flex items-start gap-3 md:gap-4 p-3 md:p-4 bg-gray-50 rounded-xl">
                                <div class="w-8 h-8 md:w-10 md:h-10 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="ri-user-line text-gray-500 text-sm md:text-base"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-700 text-sm md:text-base mb-1">Dr. Sarah Johnson</h4>
                                    <p class="text-xs md:text-sm text-gray-500">Cardiologist in New York</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 md:gap-4 p-3 md:p-4 bg-gray-50 rounded-xl">
                                <div class="w-8 h-8 md:w-10 md:h-10 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="ri-phone-line text-gray-500 text-sm md:text-base"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-700 text-sm md:text-base mb-1">Contact Information</h4>
                                    <p class="text-xs md:text-sm text-gray-500">Phone: (555) 123-4567</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="order-1 lg:order-2 relative">
                <div class="absolute -inset-4 bg-gradient-to-l from-emerald-100/50 to-transparent rounded-3xl blur-3xl"></div>
                <div class="relative bg-gradient-to-br from-[#318069] to-emerald-600 rounded-xl md:rounded-2xl p-6 md:p-8 shadow-2xl">
                    <h3 class="text-xl md:text-2xl font-bold text-white mb-4 md:mb-6">After: Complete Digital Presence</h3>
                    <div class="space-y-4 md:space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 md:p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20 gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <i class="ri-user-line text-white text-sm md:text-base"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-white text-sm md:text-base">Full Professional Profile</h4>
                                    <p class="text-xs text-white/80">Bio, qualifications, experience</p>
                                </div>
                            </div>
                            <i class="ri-check-double-line text-xl md:text-2xl text-white self-end sm:self-center"></i>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 md:p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20 gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <i class="ri-calendar-line text-white text-sm md:text-base"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-white text-sm md:text-base">Online Booking System</h4>
                                    <p class="text-xs text-white/80">24/7 appointment scheduling</p>
                                </div>
                            </div>
                            <i class="ri-check-double-line text-xl md:text-2xl text-white self-end sm:self-center"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feature Grid with Icons -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-br from-[#318069]/5 to-transparent rounded-2xl transform group-hover:scale-105 transition-transform duration-300"></div>
                <div class="relative p-6 md:p-8">
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-[#318069] to-emerald-500 rounded-xl md:rounded-2xl flex items-center justify-center mb-4 md:mb-6 transform group-hover:-translate-y-2 transition-transform duration-300">
                        <i class="ri-line-chart-line text-xl md:text-2xl text-white"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-4">Patient Growth</h3>
                    <p class="text-sm md:text-base text-gray-600 mb-4 md:mb-6">Increase new patient appointments by 40% with optimized online presence</p>
                    <div class="flex items-center text-[#318069] font-medium text-sm md:text-base">
                        <span>See Results</span>
                        <i class="ri-arrow-right-line ml-2 group-hover:translate-x-2 transition-transform"></i>
                    </div>
                </div>
            </div>

            <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-100 to-transparent rounded-2xl transform group-hover:scale-105 transition-transform duration-300"></div>
                <div class="relative p-6 md:p-8">
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl md:rounded-2xl flex items-center justify-center mb-4 md:mb-6 transform group-hover:-translate-y-2 transition-transform duration-300">
                        <i class="ri-time-line text-xl md:text-2xl text-white"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-4">Time Savings</h3>
                    <p class="text-sm md:text-base text-gray-600 mb-4 md:mb-6">Save 10+ hours weekly on appointment management and paperwork</p>
                    <div class="flex items-center text-cyan-600 font-medium text-sm md:text-base">
                        <span>Learn How</span>
                        <i class="ri-arrow-right-line ml-2 group-hover:translate-x-2 transition-transform"></i>
                    </div>
                </div>
            </div>

            <div class="relative group sm:col-span-2 lg:col-span-1">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-100 to-transparent rounded-2xl transform group-hover:scale-105 transition-transform duration-300"></div>
                <div class="relative p-6 md:p-8">
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl md:rounded-2xl flex items-center justify-center mb-4 md:mb-6 transform group-hover:-translate-y-2 transition-transform duration-300">
                        <i class="ri-star-line text-xl md:text-2xl text-white"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-4">Reputation Building</h3>
                    <p class="text-sm md:text-base text-gray-600 mb-4 md:mb-6">Collect and showcase patient reviews to build trust and credibility</p>
                    <div class="flex items-center text-purple-600 font-medium text-sm md:text-base">
                        <span>View Examples</span>
                        <i class="ri-arrow-right-line ml-2 group-hover:translate-x-2 transition-transform"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Architecture & How It Works -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 md:mb-16">
            <span class="inline-block px-4 md:px-5 py-1.5 md:py-2 bg-gradient-to-r from-cyan-100 to-blue-100 text-cyan-700 font-semibold rounded-full text-xs md:text-sm mb-3 md:mb-4">
                <i class="ri-node-tree mr-1 md:mr-2 text-sm"></i>
                Platform Architecture
            </span>
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-2 md:mb-4">
                Built for <span class="text-[#318069]">Medical Excellence</span>
            </h2>
            <p class="text-base md:text-lg text-gray-600 max-w-3xl mx-auto px-4">
                A robust, secure platform designed specifically for healthcare professionals
            </p>
        </div>

        <!-- Architecture Visualization -->
        <div class="relative">
            <div class="grid md:grid-cols-3 gap-6 md:gap-8">
                <!-- Layer 1 -->
                <div class="relative">
                    <div class="bg-white rounded-xl md:rounded-2xl p-6 md:p-8 shadow-xl border border-gray-100 transform hover:-translate-y-2 transition-transform duration-300">
                        <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-lg md:rounded-xl flex items-center justify-center mb-4 md:mb-6">
                            <i class="ri-shield-check-line text-xl md:text-2xl text-white"></i>
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-4">Secure Foundation</h3>
                        <p class="text-sm md:text-base text-gray-600 mb-4 md:mb-6">HIPAA-compliant infrastructure with end-to-end encryption</p>
                        <ul class="space-y-2 md:space-y-3">
                            <li class="flex items-center gap-2 text-xs md:text-sm text-gray-600">
                                <i class="ri-check-line text-cyan-600"></i>
                                <span>Data encryption at rest & in transit</span>
                            </li>
                            <li class="flex items-center gap-2 text-xs md:text-sm text-gray-600">
                                <i class="ri-check-line text-cyan-600"></i>
                                <span>Regular security audits</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Layer 2 -->
                <div class="relative md:mt-8 lg:mt-12">
                    <div class="bg-white rounded-xl md:rounded-2xl p-6 md:p-8 shadow-xl border border-gray-100 transform hover:-translate-y-2 transition-transform duration-300">
                        <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-[#318069] to-emerald-500 rounded-lg md:rounded-xl flex items-center justify-center mb-4 md:mb-6">
                            <i class="ri-cpu-line text-xl md:text-2xl text-white"></i>
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-4">Intelligent Core</h3>
                        <p class="text-sm md:text-base text-gray-600 mb-4 md:mb-6">Smart algorithms for appointment optimization and patient matching</p>
                        <ul class="space-y-2 md:space-y-3">
                            <li class="flex items-center gap-2 text-xs md:text-sm text-gray-600">
                                <i class="ri-check-line text-emerald-600"></i>
                                <span>AI-powered scheduling</span>
                            </li>
                            <li class="flex items-center gap-2 text-xs md:text-sm text-gray-600">
                                <i class="ri-check-line text-emerald-600"></i>
                                <span>Automated reminders</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Layer 3 -->
                <div class="relative">
                    <div class="bg-white rounded-xl md:rounded-2xl p-6 md:p-8 shadow-xl border border-gray-100 transform hover:-translate-y-2 transition-transform duration-300">
                        <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg md:rounded-xl flex items-center justify-center mb-4 md:mb-6">
                            <i class="ri-smartphone-line text-xl md:text-2xl text-white"></i>
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-4">Beautiful Interface</h3>
                        <p class="text-sm md:text-base text-gray-600 mb-4 md:mb-6">Modern, responsive design that works on all devices</p>
                        <ul class="space-y-2 md:space-y-3">
                            <li class="flex items-center gap-2 text-xs md:text-sm text-gray-600">
                                <i class="ri-check-line text-purple-600"></i>
                                <span>Mobile-first design</span>
                            </li>
                            <li class="flex items-center gap-2 text-xs md:text-sm text-gray-600">
                                <i class="ri-check-line text-purple-600"></i>
                                <span>Customizable themes</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Enhanced Patient Journey Section -->
<section class="py-12 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <span class="inline-block px-4 md:px-5 py-1.5 md:py-2 bg-gradient-to-r from-blue-50 to-cyan-50 text-blue-700 font-semibold rounded-full text-xs md:text-sm mb-3 md:mb-4">
                <i class="ri-flow-chart mr-1 md:mr-2 text-sm"></i>
                The Patient Journey
            </span>
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-2 md:mb-4">
                From Discovery to <span class="text-[#318069]">Care Continuity</span>
            </h2>
            <p class="text-base md:text-lg text-gray-600 max-w-3xl mx-auto px-4">
                A seamless experience that guides patients through every step of their healthcare journey
            </p>
        </div>

        <!-- Elegant Patient Journey Visualization -->
        <div class="relative">
            <div class="relative bg-white/80 backdrop-blur-sm rounded-xl md:rounded-3xl p-6 md:p-8 lg:p-12 shadow-2xl border border-gray-100">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center text-center lg:text-left lg:items-start lg:flex-row lg:gap-6 md:gap-8">
                        <div class="relative mb-4 lg:mb-0">
                            <div class="w-14 h-14 md:w-20 md:h-20 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl md:rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="ri-search-eye-line text-xl md:text-2xl text-white"></i>
                            </div>
                            <div class="absolute -top-2 -right-2 w-7 h-7 md:w-10 md:h-10 bg-white rounded-full border-2 md:border-4 border-blue-100 flex items-center justify-center shadow-md">
                                <span class="text-xs md:text-sm font-bold text-blue-600">01</span>
                            </div>
                        </div>
                        <div class="lg:max-w-xs">
                            <h3 class="text-base md:text-xl font-bold text-gray-900 mb-2 md:mb-3">Discovery & Research</h3>
                            <p class="text-xs md:text-sm text-gray-600 leading-relaxed">
                                Patients find you through search engines, referrals, accessing comprehensive information about your expertise.
                            </p>
                            <div class="mt-2 md:mt-4 flex items-center justify-center lg:justify-start gap-1 md:gap-2 text-blue-600">
                                <i class="ri-search-line text-xs md:text-sm"></i>
                                <span class="text-xs md:text-sm font-medium">SEO Optimized Profiles</span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex flex-col items-center text-center lg:text-left lg:items-start lg:flex-row lg:gap-6 md:gap-8">
                        <div class="relative mb-4 lg:mb-0">
                            <div class="w-14 h-14 md:w-20 md:h-20 bg-gradient-to-br from-[#318069] to-emerald-500 rounded-xl md:rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="ri-profile-line text-xl md:text-2xl text-white"></i>
                            </div>
                            <div class="absolute -top-2 -right-2 w-7 h-7 md:w-10 md:h-10 bg-white rounded-full border-2 md:border-4 border-emerald-100 flex items-center justify-center shadow-md">
                                <span class="text-xs md:text-sm font-bold text-emerald-600">02</span>
                            </div>
                        </div>
                        <div class="lg:max-w-xs">
                            <h3 class="text-base md:text-xl font-bold text-gray-900 mb-2 md:mb-3">Profile Evaluation</h3>
                            <p class="text-xs md:text-sm text-gray-600 leading-relaxed">
                                Patients review credentials, experience, patient testimonials, and treatment philosophy.
                            </p>
                            <div class="mt-2 md:mt-4 flex items-center justify-center lg:justify-start gap-1 md:gap-2 text-emerald-600">
                                <i class="ri-medal-line text-xs md:text-sm"></i>
                                <span class="text-xs md:text-sm font-medium">Verified Credentials</span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex flex-col items-center text-center lg:text-left lg:items-start lg:flex-row lg:gap-6 md:gap-8">
                        <div class="relative mb-4 lg:mb-0">
                            <div class="w-14 h-14 md:w-20 md:h-20 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl md:rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="ri-calendar-check-line text-xl md:text-2xl text-white"></i>
                            </div>
                            <div class="absolute -top-2 -right-2 w-7 h-7 md:w-10 md:h-10 bg-white rounded-full border-2 md:border-4 border-amber-100 flex items-center justify-center shadow-md">
                                <span class="text-xs md:text-sm font-bold text-amber-600">03</span>
                            </div>
                        </div>
                        <div class="lg:max-w-xs">
                            <h3 class="text-base md:text-xl font-bold text-gray-900 mb-2 md:mb-3">Appointment & Consultation</h3>
                            <p class="text-xs md:text-sm text-gray-600 leading-relaxed">
                                Seamless online booking, virtual consultations, and automated reminders.
                            </p>
                            <div class="mt-2 md:mt-4 flex items-center justify-center lg:justify-start gap-1 md:gap-2 text-amber-600">
                                <i class="ri-video-line text-xs md:text-sm"></i>
                                <span class="text-xs md:text-sm font-medium">Virtual Consultations</span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="flex flex-col items-center text-center lg:text-left lg:items-start lg:flex-row lg:gap-6 md:gap-8">
                        <div class="relative mb-4 lg:mb-0">
                            <div class="w-14 h-14 md:w-20 md:h-20 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl md:rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="ri-heart-pulse-line text-xl md:text-2xl text-white"></i>
                            </div>
                            <div class="absolute -top-2 -right-2 w-7 h-7 md:w-10 md:h-10 bg-white rounded-full border-2 md:border-4 border-purple-100 flex items-center justify-center shadow-md">
                                <span class="text-xs md:text-sm font-bold text-purple-600">04</span>
                            </div>
                        </div>
                        <div class="lg:max-w-xs">
                            <h3 class="text-base md:text-xl font-bold text-gray-900 mb-2 md:mb-3">Ongoing Care & Follow-up</h3>
                            <p class="text-xs md:text-sm text-gray-600 leading-relaxed">
                                Continuous patient engagement through follow-up communications and health education.
                            </p>
                            <div class="mt-2 md:mt-4 flex items-center justify-center lg:justify-start gap-1 md:gap-2 text-purple-600">
                                <i class="ri-chat-follow-up-line text-xs md:text-sm"></i>
                                <span class="text-xs md:text-sm font-medium">Automated Follow-ups</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Journey Insight -->
        <div class="mt-8 md:mt-12 text-center">
            <div class="inline-flex items-center gap-2 md:gap-3 bg-white/80 backdrop-blur-sm rounded-full px-4 md:px-6 py-2 md:py-3 shadow-sm border border-gray-200">
                <div class="w-6 h-6 md:w-8 md:h-8 bg-[#318069]/10 rounded-full flex items-center justify-center">
                    <i class="ri-lightbulb-line text-[#318069] text-xs md:text-sm"></i>
                </div>
                <p class="text-xs md:text-sm text-gray-700">
                    <span class="font-semibold">Insight:</span> Practices using our platform see a 72% improvement in patient journey satisfaction
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Enhanced Pricing Section -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <span class="inline-block px-4 md:px-5 py-1.5 md:py-2 bg-gradient-to-r from-[#FFC107]/10 to-amber-100 text-amber-700 font-semibold rounded-full text-xs md:text-sm mb-3 md:mb-4">
                <i class="ri-price-tag-3-line mr-1 md:mr-2 text-sm"></i>
                Simple Pricing
            </span>
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-2 md:mb-4">
                Choose Your <span class="text-[#318069]">Growth Path</span>
            </h2>
            <p class="text-base md:text-lg text-gray-600 max-w-3xl mx-auto px-4">
                Transparent pricing with no hidden fees. Start small, scale as you grow.
            </p>
        </div>

        <!-- Pricing Cards - Responsive Grid -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 max-w-6xl mx-auto">
            @foreach ($packages as $index => $package)
                <div class="relative bg-white rounded-xl md:rounded-2xl border-2 transition-all hover:shadow-2xl
                    {{ $index == 1 ? 'border-[#318069] shadow-xl scale-100 lg:scale-105' : 'border-gray-200 hover:border-[#318069]/50' }}
                    flex flex-col h-full package-card" data-package-id="{{ $package->id }}">

                    @if($index == 1)
                        <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                            <div class="bg-[#FFC107] text-gray-900 px-4 md:px-6 py-1 md:py-1.5 rounded-full text-xs md:text-sm font-bold shadow-lg whitespace-nowrap">
                                Most Popular
                            </div>
                        </div>
                    @endif

                    <div class="p-5 md:p-8 flex flex-col flex-1">
                        <div class="mb-6 md:mb-8">
                            <h3 class="text-xl md:text-2xl font-bold text-gray-900 text-center mb-3 md:mb-5">{{ $package->name }}</h3>
                            <div class="flex items-end justify-center gap-1">
                                @php
                                    $displayMonthly = round($package->price_monthly * ($pricingContext['exchange_rate'] ?? 1));
                                @endphp
                                <span class="text-3xl md:text-5xl font-bold text-gray-900 package-price" data-monthly="{{ $package->price_monthly }}" data-yearly="{{ $package->price_yearly }}">
                                    {{ $pricingContext['currency_symbol'] }}{{ number_format($displayMonthly, 0) }}
                                </span>
                                <span class="text-gray-600 mb-1 md:mb-2 package-period text-sm md:text-base">/month</span>
                            </div>
                            <div class="mt-2 text-center text-[10px] md:text-xs text-gray-500">
                                Base pricing in USD, automatically converted
                            </div>
                            <div class="mt-3 md:mt-4 text-center text-xs md:text-sm text-gray-500">
                                <i class="ri-checkbox-circle-line text-[#318069] mr-1"></i>
                                <span class="billing-savings" data-yearly-savings="{{ $package->price_monthly > 0 ? round((1 - ($package->price_yearly / ($package->price_monthly * 12))) * 100) : 0 }}%">
                                    Save {{ $package->price_monthly > 0 ? round((1 - ($package->price_yearly / ($package->price_monthly * 12))) * 100) : 0 }}% with yearly
                                </span>
                            </div>
                        </div>

                        <div class="space-y-2 md:space-y-3 mb-6 md:mb-8 flex-1">
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

                            @foreach (array_slice($features, 0, 5) as $feature)
                                <div class="flex items-start gap-2 md:gap-3">
                                    <div class="w-4 h-4 md:w-5 md:h-5 bg-[#318069]/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i class="ri-check-line text-[#318069] text-[10px] md:text-sm"></i>
                                    </div>
                                    <span class="text-xs md:text-sm text-gray-700">
                                        {{ $feature }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <div class="pt-3 md:pt-4 mt-auto">
                            <a href="/doctor/create" class="block w-full py-3 md:py-4 rounded-xl font-bold transition-all text-center text-sm md:text-base
                                {{ $index == 1 ? 'bg-[#318069] hover:bg-[#276854] text-white shadow-lg hover:shadow-xl' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                                Select {{ $package->name }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Enhanced FAQ Section -->
<section class="py-12 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <span class="inline-block px-4 md:px-5 py-1.5 md:py-2 bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-700 font-semibold rounded-full text-xs md:text-sm mb-3 md:mb-4">
                <i class="ri-question-line mr-1 md:mr-2 text-sm"></i>
                Common Questions
            </span>
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-2 md:mb-4">
                Everything You Need to <span class="text-[#318069]">Know</span>
            </h2>
            <p class="text-base md:text-lg text-gray-600">
                Clear answers to help you make the right decision
            </p>
        </div>

        <div class="space-y-4 md:space-y-6">
            <!-- FAQ Items - Responsive -->
            <div class="group bg-white rounded-xl md:rounded-2xl p-5 md:p-8 shadow-lg border border-gray-200 hover:border-[#318069]/30 transition-all duration-300 hover:shadow-xl">
                <button class="faq-question w-full flex items-center justify-between text-left gap-3">
                    <div class="flex items-center gap-3 md:gap-4">
                        <div class="w-8 h-8 md:w-12 md:h-12 bg-purple-100 rounded-lg md:rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="ri-palette-line text-purple-600 text-base md:text-xl"></i>
                        </div>
                        <h3 class="text-sm md:text-lg font-semibold text-gray-900">Can I customize the design to match my branding?</h3>
                    </div>
                    <i class="ri-add-line text-[#318069] text-lg md:text-xl transition-transform duration-300 flex-shrink-0"></i>
                </button>
                <div class="faq-answer mt-4 md:mt-6 hidden">
                    <div class="pl-12 md:pl-16">
                        <div class="bg-gradient-to-r from-purple-50 to-white rounded-lg md:rounded-xl p-4 md:p-6">
                            <p class="text-sm md:text-base text-gray-600 mb-3 md:mb-4">
                                Absolutely! Our platform offers extensive customization options including:
                            </p>
                            <ul class="space-y-2 md:space-y-3">
                                <li class="flex items-start gap-2 md:gap-3 text-xs md:text-sm">
                                    <i class="ri-check-line text-[#318069] mt-1"></i>
                                    <span>Custom color schemes matching your practice colors</span>
                                </li>
                                <li class="flex items-start gap-2 md:gap-3 text-xs md:text-sm">
                                    <i class="ri-check-line text-[#318069] mt-1"></i>
                                    <span>Multiple layout options and page templates</span>
                                </li>
                                <li class="flex items-start gap-2 md:gap-3 text-xs md:text-sm">
                                    <i class="ri-check-line text-[#318069] mt-1"></i>
                                    <span>Custom logo, banner, and imagery upload</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="group bg-white rounded-xl md:rounded-2xl p-5 md:p-8 shadow-lg border border-gray-200 hover:border-[#318069]/30 transition-all duration-300 hover:shadow-xl">
                <button class="faq-question w-full flex items-center justify-between text-left gap-3">
                    <div class="flex items-center gap-3 md:gap-4">
                        <div class="w-8 h-8 md:w-12 md:h-12 bg-orange-100 rounded-lg md:rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="ri-cpu-line text-orange-600 text-base md:text-xl"></i>
                        </div>
                        <h3 class="text-sm md:text-lg font-semibold text-gray-900">Do you integrate with my existing systems?</h3>
                    </div>
                    <i class="ri-add-line text-[#318069] text-lg md:text-xl transition-transform duration-300 flex-shrink-0"></i>
                </button>
                <div class="faq-answer mt-4 md:mt-6 hidden">
                    <div class="pl-12 md:pl-16">
                        <div class="bg-gradient-to-r from-orange-50 to-white rounded-lg md:rounded-xl p-4 md:p-6">
                            <p class="text-sm md:text-base text-gray-600 mb-3 md:mb-4">
                                Yes! We offer seamless integrations with popular healthcare systems including:
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 md:gap-3">
                                <div class="flex items-center gap-2 p-2 md:p-3 bg-white rounded-lg border text-xs md:text-sm">
                                    <i class="ri-database-2-line text-blue-600"></i>
                                    <span>Electronic Health Records</span>
                                </div>
                                <div class="flex items-center gap-2 p-2 md:p-3 bg-white rounded-lg border text-xs md:text-sm">
                                    <i class="ri-calendar-2-line text-green-600"></i>
                                    <span>Practice Management</span>
                                </div>
                                <div class="flex items-center gap-2 p-2 md:p-3 bg-white rounded-lg border text-xs md:text-sm">
                                    <i class="ri-bill-line text-purple-600"></i>
                                    <span>Billing Systems</span>
                                </div>
                                <div class="flex items-center gap-2 p-2 md:p-3 bg-white rounded-lg border text-xs md:text-sm">
                                    <i class="ri-message-3-line text-cyan-600"></i>
                                    <span>Patient Communication</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="group bg-white rounded-xl md:rounded-2xl p-5 md:p-8 shadow-lg border border-gray-200 hover:border-[#318069]/30 transition-all duration-300 hover:shadow-xl">
                <button class="faq-question w-full flex items-center justify-between text-left gap-3">
                    <div class="flex items-center gap-3 md:gap-4">
                        <div class="w-8 h-8 md:w-12 md:h-12 bg-teal-100 rounded-lg md:rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="ri-building-line text-teal-600 text-base md:text-xl"></i>
                        </div>
                        <h3 class="text-sm md:text-lg font-semibold text-gray-900">Can I manage multiple locations?</h3>
                    </div>
                    <i class="ri-add-line text-[#318069] text-lg md:text-xl transition-transform duration-300 flex-shrink-0"></i>
                </button>
                <div class="faq-answer mt-4 md:mt-6 hidden">
                    <div class="pl-12 md:pl-16">
                        <div class="bg-gradient-to-r from-teal-50 to-white rounded-lg md:rounded-xl p-4 md:p-6">
                            <p class="text-sm md:text-base text-gray-600 mb-3 md:mb-4">
                                Yes! Our platform supports multi-location management. Features include:
                            </p>
                            <ul class="space-y-2 md:space-y-3">
                                <li class="flex items-start gap-2 md:gap-3 text-xs md:text-sm">
                                    <i class="ri-check-line text-[#318069] mt-1"></i>
                                    <span>Centralized dashboard for all locations</span>
                                </li>
                                <li class="flex items-start gap-2 md:gap-3 text-xs md:text-sm">
                                    <i class="ri-check-line text-[#318069] mt-1"></i>
                                    <span>Location-specific scheduling and availability</span>
                                </li>
                                <li class="flex items-start gap-2 md:gap-3 text-xs md:text-sm">
                                    <i class="ri-check-line text-[#318069] mt-1"></i>
                                    <span>Staff management with permissions per location</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Final CTA -->
        <div class="mt-10 md:mt-20 text-center">
            <div class="bg-gradient-to-r from-[#318069]/5 to-emerald-100/50 rounded-2xl md:rounded-3xl p-8 md:p-12 border border-[#318069]/10">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 md:mb-6">Ready to Transform Your Practice?</h2>
                <p class="text-base md:text-lg text-gray-600 mb-6 md:mb-8 max-w-2xl mx-auto px-4">
                    Join thousands of doctors who have elevated their practice with a professional digital presence.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center">
                    <a href="{{route('doctor.create')}}"
                       class="group inline-flex items-center justify-center px-6 md:px-10 py-3 md:py-5 bg-gradient-to-r from-[#318069] to-emerald-600 text-white font-bold rounded-xl md:rounded-2xl transition-all shadow-2xl hover:shadow-3xl hover:scale-[1.02] text-sm md:text-base">
                        <span>Start Your Free Trial</span>
                        <i class="ri-arrow-right-line ml-2 md:ml-3 text-base md:text-xl group-hover:translate-x-2 transition-transform"></i>
                    </a>
                </div>
                <p class="mt-6 md:mt-8 text-gray-500 text-xs md:text-sm">
                    <i class="ri-shield-check-line text-[#318069] mr-1 md:mr-2"></i>
                    No credit card required • 14-day free trial • Cancel anytime
                </p>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // FAQ Toggle Functionality
        const faqQuestions = document.querySelectorAll('.faq-question');
        faqQuestions.forEach(question => {
            question.addEventListener('click', function() {
                const answer = this.closest('.group').querySelector('.faq-answer');
                const icon = this.querySelector('i:last-child');

                // Close other FAQs
                faqQuestions.forEach(q => {
                    if (q !== this) {
                        const otherAnswer = q.closest('.group').querySelector('.faq-answer');
                        const otherIcon = q.querySelector('i:last-child');
                        if (otherAnswer && !otherAnswer.classList.contains('hidden')) {
                            otherAnswer.classList.add('hidden');
                            otherIcon.classList.remove('ri-subtract-line');
                            otherIcon.classList.add('ri-add-line');
                        }
                    }
                });

                // Toggle current FAQ
                answer.classList.toggle('hidden');
                if (icon.classList.contains('ri-add-line')) {
                    icon.classList.remove('ri-add-line');
                    icon.classList.add('ri-subtract-line');
                } else {
                    icon.classList.remove('ri-subtract-line');
                    icon.classList.add('ri-add-line');
                }
            });
        });

        // Package Price Toggle
        const PACKAGE_CURRENCY_SYMBOL = @json($pricingContext['currency_symbol'] ?? '$');
        const PACKAGE_EXCHANGE_RATE = {{ (float) ($pricingContext['exchange_rate'] ?? 1) }};

        function formatPackagePrice(price) {
            return `${PACKAGE_CURRENCY_SYMBOL}${Math.round(parseFloat(price || 0) * PACKAGE_EXCHANGE_RATE)}`;
        }

        // Pricing toggle buttons
        const monthlyBtn = document.getElementById('billing-toggle-monthly');
        const yearlyBtn = document.getElementById('billing-toggle-yearly');
        let selectedBillingCycle = 'monthly';

        if (monthlyBtn && yearlyBtn) {
            monthlyBtn.addEventListener('click', () => selectBillingCycle('monthly'));
            yearlyBtn.addEventListener('click', () => selectBillingCycle('yearly'));
        }

        function selectBillingCycle(cycle) {
            selectedBillingCycle = cycle;
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
        }

        function updatePackagePrices() {
            document.querySelectorAll('.package-card').forEach(card => {
                const priceElement = card.querySelector('.package-price');
                const periodElement = card.querySelector('.package-period');
                const savingsElement = card.querySelector('.billing-savings');

                if (priceElement && periodElement) {
                    const monthlyPrice = parseFloat(priceElement.dataset.monthly);
                    const yearlyPrice = parseFloat(priceElement.dataset.yearly);

                    if (selectedBillingCycle === 'yearly') {
                        priceElement.textContent = formatPackagePrice(yearlyPrice);
                        periodElement.textContent = '/year';
                        if (savingsElement) savingsElement.classList.add('hidden');
                    } else {
                        priceElement.textContent = formatPackagePrice(monthlyPrice);
                        periodElement.textContent = '/month';
                        if (savingsElement) savingsElement.classList.remove('hidden');
                    }
                }
            });
        }
    });
</script>
@endpush

@endsection