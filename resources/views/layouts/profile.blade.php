@extends('layouts.sass')

@section('title', 'Doctor Profile - Create Your Professional Online Presence')

@section('content')
<!-- Hero Section -->
<section class="relative pt-24 pb-16 md:pt-32 md:pb-24 overflow-hidden">
    <!-- Background Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-[#318069]/5 via-white to-[#FFC107]/5"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div>
                <div class="inline-flex items-center gap-2 bg-[#318069]/10 rounded-full px-4 py-2 mb-6">
                    <i class="ri-verified-badge-line text-[#318069]"></i>
                    <span class="text-sm font-semibold text-[#318069]">Trusted by 500+ Medical Professionals</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                    Create Your Professional
                    <span class="text-[#318069]">Doctor Profile</span>
                    in Minutes
                </h1>
                
                <p class="text-xl text-gray-600 mb-8 max-w-2xl">
                    Build a stunning online presence that attracts new patients, showcases your expertise, and grows your practice with our all-in-one platform.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('doctor.register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-[#318069] hover:bg-[#276854] text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-xl hover:scale-[1.02]">
                        <span>Get Started Free</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </a>
                    <a href="#how-it-works" class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-300 hover:border-[#318069] text-gray-700 hover:text-[#318069] font-semibold rounded-xl transition-all">
                        <i class="ri-play-circle-line mr-2"></i>
                        <span>See How It Works</span>
                    </a>
                </div>
                
                <!-- Trust Indicators -->
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <p class="text-sm text-gray-500 mb-4">Join thousands of satisfied doctors</p>
                    <div class="flex items-center gap-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="ri-star-fill text-yellow-500"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">4.9/5</p>
                                <p class="text-sm text-gray-500">Rating</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="ri-user-heart-line text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">10,000+</p>
                                <p class="text-sm text-gray-500">Patients Served</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Content - Hero Image/Illustration -->
            <div class="relative">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                    <!-- Mockup of Doctor Profile -->
                    <div class="bg-gradient-to-br from-white to-gray-50 p-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-[#318069]/10 rounded-full flex items-center justify-center">
                                <i class="ri-user-line text-3xl text-[#318069]"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Dr. Sarah Johnson</h3>
                                <p class="text-sm text-gray-600">Cardiologist • 15 Years Experience</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200">
                                <i class="ri-calendar-line text-[#318069]"></i>
                                <span class="text-sm">Online Appointment Booking</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200">
                                <i class="ri-video-line text-[#318069]"></i>
                                <span class="text-sm">Virtual Consultations</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200">
                                <i class="ri-file-text-line text-[#318069]"></i>
                                <span class="text-sm">Patient Reviews & Ratings</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Floating Elements -->
                <div class="absolute -top-4 -left-4 w-24 h-24 bg-[#FFC107]/10 rounded-full"></div>
                <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-[#318069]/5 rounded-full"></div>
            </div>
        </div>
    </div>
</section>

<!-- What is Doctor Profile Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-2 bg-[#318069]/10 text-[#318069] font-semibold rounded-full mb-4">
                What is Doctor Profile?
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Your Complete Digital Practice Solution
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                A professional online platform that connects you with patients and manages your practice efficiently
            </p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-200 hover:border-[#318069] transition-all hover:shadow-xl">
                <div class="w-16 h-16 bg-[#318069]/10 rounded-xl flex items-center justify-center mb-6">
                    <i class="ri-global-line text-3xl text-[#318069]"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Digital Presence</h3>
                <p class="text-gray-600 mb-6">
                    Create a beautiful, professional website that showcases your expertise, qualifications, and services to attract new patients 24/7.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-[#318069]"></i>
                        <span class="text-sm">Custom domain or subdomain</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-[#318069]"></i>
                        <span class="text-sm">SEO optimized</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-[#318069]"></i>
                        <span class="text-sm">Mobile responsive design</span>
                    </li>
                </ul>
            </div>
            
            <!-- Card 2 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-200 hover:border-[#318069] transition-all hover:shadow-xl">
                <div class="w-16 h-16 bg-[#318069]/10 rounded-xl flex items-center justify-center mb-6">
                    <i class="ri-calendar-schedule-line text-3xl text-[#318069]"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Practice Management</h3>
                <p class="text-gray-600 mb-6">
                    Streamline your practice with online appointment scheduling, patient records management, and automated reminders.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-[#318069]"></i>
                        <span class="text-sm">Online booking system</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-[#318069]"></i>
                        <span class="text-sm">Patient database</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-[#318069]"></i>
                        <span class="text-sm">Automated notifications</span>
                    </li>
                </ul>
            </div>
            
            <!-- Card 3 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-200 hover:border-[#318069] transition-all hover:shadow-xl">
                <div class="w-16 h-16 bg-[#318069]/10 rounded-xl flex items-center justify-center mb-6">
                    <i class="ri-line-chart-line text-3xl text-[#318069]"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Growth & Engagement</h3>
                <p class="text-gray-600 mb-6">
                    Build your reputation with patient reviews, share educational content, and grow your practice through digital channels.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-[#318069]"></i>
                        <span class="text-sm">Review management</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-[#318069]"></i>
                        <span class="text-sm">Blog/content platform</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="ri-check-line text-[#318069]"></i>
                        <span class="text-sm">Analytics dashboard</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 bg-[#318069]/10 text-[#318069] font-semibold rounded-full mb-4">
                Simple Process
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Get Started in 4 Easy Steps
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                From signup to your first patient booking - we've made it incredibly simple
            </p>
        </div>
        
        <!-- Steps Timeline -->
        <div class="relative">
            <!-- Connecting Line -->
            <div class="hidden lg:block absolute left-1/2 top-0 bottom-0 w-0.5 bg-gray-200 transform -translate-x-1/2"></div>
            
            <div class="space-y-12 lg:space-y-0">
                <!-- Step 1 -->
                <div class="relative lg:flex items-center">
                    <div class="lg:w-1/2 lg:pr-12 lg:text-right mb-8 lg:mb-0">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-[#318069] text-white rounded-full font-bold text-lg mb-4">
                            1
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Sign Up & Choose Package</h3>
                        <p class="text-gray-600">
                            Select the perfect plan for your practice needs. Start with our Basic plan and upgrade anytime.
                        </p>
                    </div>
                    
                    <div class="hidden lg:flex items-center justify-center w-12 h-12 bg-white border-4 border-white rounded-full z-10">
                        <div class="w-4 h-4 bg-[#318069] rounded-full"></div>
                    </div>
                    
                    <div class="lg:w-1/2 lg:pl-12">
                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-[#318069]/10 rounded-lg flex items-center justify-center">
                                    <i class="ri-box-3-line text-xl text-[#318069]"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Flexible Plans</h4>
                                    <p class="text-sm text-gray-600">Monthly or yearly billing</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2 -->
                <div class="relative lg:flex items-center">
                    <div class="lg:w-1/2 lg:pr-12 lg:text-right order-2 lg:order-1">
                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-[#318069]/10 rounded-lg flex items-center justify-center">
                                    <i class="ri-global-line text-xl text-[#318069]"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Domain Setup</h4>
                                    <p class="text-sm text-gray-600">Free subdomain or custom domain</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="hidden lg:flex items-center justify-center w-12 h-12 bg-white border-4 border-white rounded-full z-10 order-1">
                        <div class="w-4 h-4 bg-[#318069] rounded-full"></div>
                    </div>
                    
                    <div class="lg:w-1/2 lg:pl-12 mb-8 lg:mb-0 order-1 lg:order-2">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-[#318069] text-white rounded-full font-bold text-lg mb-4">
                            2
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Setup Your Profile</h3>
                        <p class="text-gray-600">
                            Choose your domain, upload your professional photo, and fill in your practice details.
                        </p>
                    </div>
                </div>
                
                <!-- Step 3 -->
                <div class="relative lg:flex items-center">
                    <div class="lg:w-1/2 lg:pr-12 lg:text-right mb-8 lg:mb-0">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-[#318069] text-white rounded-full font-bold text-lg mb-4">
                            3
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Customize Your Design</h3>
                        <p class="text-gray-600">
                            Select colors, layout, and add your services, qualifications, and practice information.
                        </p>
                    </div>
                    
                    <div class="hidden lg:flex items-center justify-center w-12 h-12 bg-white border-4 border-white rounded-full z-10">
                        <div class="w-4 h-4 bg-[#318069] rounded-full"></div>
                    </div>
                    
                    <div class="lg:w-1/2 lg:pl-12">
                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-[#318069]/10 rounded-lg flex items-center justify-center">
                                    <i class="ri-palette-line text-xl text-[#318069]"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Easy Customization</h4>
                                    <p class="text-sm text-gray-600">No coding required</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 4 -->
                <div class="relative lg:flex items-center">
                    <div class="lg:w-1/2 lg:pr-12 lg:text-right order-2 lg:order-1">
                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-[#318069]/10 rounded-lg flex items-center justify-center">
                                    <i class="ri-rocket-line text-xl text-[#318069]"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Go Live!</h4>
                                    <p class="text-sm text-gray-600">Start accepting patients</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="hidden lg:flex items-center justify-center w-12 h-12 bg-white border-4 border-white rounded-full z-10 order-1">
                        <div class="w-4 h-4 bg-[#318069] rounded-full"></div>
                    </div>
                    
                    <div class="lg:w-1/2 lg:pl-12 mb-8 lg:mb-0 order-1 lg:order-2">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-[#318069] text-white rounded-full font-bold text-lg mb-4">
                            4
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Launch & Grow</h3>
                        <p class="text-gray-600">
                            Your profile goes live instantly! Start accepting bookings and grow your practice.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-16">
            <a href="{{ route('doctor.register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-[#318069] hover:bg-[#276854] text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-xl">
                <span>Start Your Journey Now</span>
                <i class="ri-arrow-right-line ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Package Selection Preview -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-2 bg-[#318069]/10 text-[#318069] font-semibold rounded-full mb-4">
                Affordable Pricing
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Choose Your Perfect Plan
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Select the package that fits your practice. No hidden fees, cancel anytime.
            </p>
        </div>
        
        <!-- Billing Toggle -->
        <div class="flex justify-center mb-8">
            <div class="bg-gray-100 rounded-full p-1 inline-flex">
                <button type="button" class="billing-toggle-btn px-6 py-2 rounded-full font-semibold text-gray-700 transition-all active bg-white text-[#318069] shadow-sm" data-cycle="monthly">
                    Monthly Billing
                </button>
                <button type="button" class="billing-toggle-btn px-6 py-2 rounded-full font-semibold text-gray-700 transition-all" data-cycle="yearly">
                    Yearly Billing (Save 20%)
                </button>
            </div>
        </div>
        
        <!-- Pricing Cards -->
        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <!-- Basic Plan -->
            <div class="bg-white rounded-2xl border-2 border-gray-200 p-8 shadow-lg hover:shadow-xl transition-all">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Basic</h3>
                    <p class="text-sm text-gray-600 mb-4">Perfect for solo practitioners starting out</p>
                    <div class="flex items-end justify-center gap-1">
                        <span class="text-5xl font-bold text-gray-900" data-monthly="29" data-yearly="278">
                            $29
                        </span>
                        <span class="text-gray-600 mb-2">/month</span>
                    </div>
                    <div class="mt-4 text-center text-sm text-gray-500">
                        <i class="ri-checkbox-circle-line text-[#318069] mr-1"></i>
                        <span>Save 20% with yearly billing</span>
                    </div>
                </div>
                
                <div class="space-y-3 mb-8">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 bg-[#318069]/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="ri-check-line text-[#318069] text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-700">Free subdomain (yourname.doctorsprofile.xyz)</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 bg-[#318069]/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="ri-check-line text-[#318069] text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-700">Professional profile page</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 bg-[#318069]/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="ri-check-line text-[#318069] text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-700">Basic appointment scheduling</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 bg-[#318069]/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="ri-check-line text-[#318069] text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-700">Email support</span>
                    </div>
                </div>
                
                <a href="{{ route('doctor.register') }}" class="block w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-center rounded-xl font-semibold transition-all">
                    Get Started
                </a>
            </div>
            
            <!-- Professional Plan (Most Popular) -->
            <div class="bg-white rounded-2xl border-2 border-[#318069] p-8 shadow-xl relative scale-105">
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <div class="bg-[#FFC107] text-gray-900 px-6 py-1.5 rounded-full text-sm font-bold shadow-lg">
                        Most Popular
                    </div>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Professional</h3>
                    <p class="text-sm text-gray-600 mb-4">Best for growing practices</p>
                    <div class="flex items-end justify-center gap-1">
                        <span class="text-5xl font-bold text-gray-900" data-monthly="59" data-yearly="566">
                            $59
                        </span>
                        <span class="text-gray-600 mb-2">/month</span>
                    </div>
                    <div class="mt-4 text-center text-sm text-gray-500">
                        <i class="ri-checkbox-circle-line text-[#318069] mr-1"></i>
                        <span>Save 20% with yearly billing</span>
                    </div>
                </div>
                
                <div class="space-y-3 mb-8">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 bg-[#318069]/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="ri-check-line text-[#318069] text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-700">Everything in Basic, plus:</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 bg-[#318069]/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="ri-check-line text-[#318069] text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-700">Custom domain (yourclinic.com)</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 bg-[#318069]/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="ri-check-line text-[#318069] text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-700">Advanced scheduling & reminders</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 bg-[#318069]/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="ri-check-line text-[#318069] text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-700">Patient database (up to 1000)</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 bg-[#318069]/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="ri-check-line text-[#318069] text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-700">Priority email & chat support</span>
                    </div>
                </div>
                
                <a href="{{ route('doctor.register') }}" class="block w-full py-3 bg-[#318069] hover:bg-[#276854] text-white text-center rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl">
                    Get Started
                </a>
            </div>
            
            <!-- Enterprise Plan -->
            <div class="bg-white rounded-2xl border-2 border-gray-200 p-8 shadow-lg hover:shadow-xl transition-all">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Enterprise</h3>
                    <p class="text-sm text-gray-600 mb-4">For clinics & hospitals</p>
                    <div class="flex items-end justify-center gap-1">
                        <span class="text-5xl font-bold text-gray-900" data-monthly="99" data-yearly="950">
                            $99
                        </span>
                        <span class="text-gray-600 mb-2">/month</span>
                    </div>
                    <div class="mt-4 text-center text-sm text-gray-500">
                        <i class="ri-checkbox-circle-line text-[#318069] mr-1"></i>
                        <span>Save 20% with yearly billing</span>
                    </div>
                </div>
                
                <div class="space-y-3 mb-8">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 bg-[#318069]/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="ri-check-line text-[#318069] text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-700">Everything in Professional, plus:</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 bg-[#318069]/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="ri-check-line text-[#318069] text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-700">Multiple doctor profiles</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 bg-[#318069]/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="ri-check-line text-[#318069] text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-700">Unlimited patient database</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 bg-[#318069]/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="ri-check-line text-[#318069] text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-700">Advanced analytics & reports</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 bg-[#318069]/10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="ri-check-line text-[#318069] text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-700">Dedicated account manager</span>
                    </div>
                </div>
                
                <a href="{{ route('doctor.register') }}" class="block w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-center rounded-xl font-semibold transition-all">
                    Get Started
                </a>
            </div>
        </div>
        
        <div class="text-center mt-8">
            <p class="text-gray-600">
                All plans include SSL certificate, 99.9% uptime, and regular updates.
                <a href="{{ route('doctor.register') }}" class="text-[#318069] font-semibold hover:underline ml-1">
                    Start free trial →
                </a>
            </p>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-2 bg-[#318069]/10 text-[#318069] font-semibold rounded-full mb-4">
                FAQs
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Frequently Asked Questions
            </h2>
            <p class="text-xl text-gray-600">
                Everything you need to know about creating your doctor profile
            </p>
        </div>
        
        <div class="space-y-6">
            <!-- FAQ 1 -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <button class="faq-question w-full flex items-center justify-between text-left">
                    <h3 class="text-lg font-semibold text-gray-900">How long does it take to set up my profile?</h3>
                    <i class="ri-add-line text-[#318069] text-xl"></i>
                </button>
                <div class="faq-answer mt-4 hidden">
                    <p class="text-gray-600">
                        You can have your basic profile up and running in less than 10 minutes! The initial setup is quick and straightforward. Complete customization typically takes 1-2 hours depending on how much content you want to add.
                    </p>
                </div>
            </div>
            
            <!-- FAQ 2 -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <button class="faq-question w-full flex items-center justify-between text-left">
                    <h3 class="text-lg font-semibold text-gray-900">Do I need technical skills to use this platform?</h3>
                    <i class="ri-add-line text-[#318069] text-xl"></i>
                </button>
                <div class="faq-answer mt-4 hidden">
                    <p class="text-gray-600">
                        Not at all! Our platform is designed specifically for healthcare professionals with no technical background. The interface is intuitive and user-friendly. We also provide step-by-step guides and video tutorials to help you every step of the way.
                    </p>
                </div>
            </div>
            
            <!-- FAQ 3 -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <button class="faq-question w-full flex items-center justify-between text-left">
                    <h3 class="text-lg font-semibold text-gray-900">Can I upgrade or downgrade my plan later?</h3>
                    <i class="ri-add-line text-[#318069] text-xl"></i>
                </button>
                <div class="faq-answer mt-4 hidden">
                    <p class="text-gray-600">
                        Yes, absolutely! You can upgrade or downgrade your plan at any time from your dashboard. When upgrading, you'll get immediate access to new features. When downgrading, the changes take effect at the start of your next billing cycle.
                    </p>
                </div>
            </div>
            
            <!-- FAQ 4 -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <button class="faq-question w-full flex items-center justify-between text-left">
                    <h3 class="text-lg font-semibold text-gray-900">What happens to my data if I cancel?</h3>
                    <i class="ri-add-line text-[#318069] text-xl"></i>
                </button>
                <div class="faq-answer mt-4 hidden">
                    <p class="text-gray-600">
                        We understand your data is important. If you decide to cancel, we keep your data for 30 days in case you want to reactivate your account. After 30 days, we securely delete all your data. You can also export your data at any time from the dashboard.
                    </p>
                </div>
            </div>
            
            <!-- FAQ 5 -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <button class="faq-question w-full flex items-center justify-between text-left">
                    <h3 class="text-lg font-semibold text-gray-900">Is there a free trial available?</h3>
                    <i class="ri-add-line text-[#318069] text-xl"></i>
                </button>
                <div class="faq-answer mt-4 hidden">
                    <p class="text-gray-600">
                        Yes! We offer a 14-day free trial for all our plans. No credit card is required to start your trial. You get full access to all features during the trial period so you can properly evaluate if our platform meets your needs.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-12">
            <p class="text-gray-600 mb-6">
                Still have questions? We're here to help!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="mailto:support@doctorsprofile.com" class="inline-flex items-center justify-center px-6 py-3 border-2 border-[#318069] text-[#318069] hover:bg-[#318069] hover:text-white font-semibold rounded-xl transition-all">
                    <i class="ri-mail-line mr-2"></i>
                    <span>Email Support</span>
                </a>
                <a href="{{ route('doctor.register') }}" class="inline-flex items-center justify-center px-6 py-3 bg-[#318069] hover:bg-[#276854] text-white font-semibold rounded-xl transition-all">
                    <i class="ri-rocket-line mr-2"></i>
                    <span>Start Free Trial</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA Section -->
<section class="py-16 bg-gradient-to-r from-[#318069]/10 to-[#FFC107]/10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
            Ready to Transform Your Practice?
        </h2>
        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
            Join thousands of doctors who have elevated their practice with a professional online profile. Start attracting more patients today.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('doctor.register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-[#318069] hover:bg-[#276854] text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-xl hover:scale-[1.02]">
                <span>Create Your Profile Now</span>
                <i class="ri-arrow-right-line ml-2"></i>
            </a>
            <a href="#how-it-works" class="inline-flex items-center justify-center px-8 py-4 bg-white hover:bg-gray-50 text-gray-700 font-semibold rounded-xl transition-all border border-gray-300 hover:border-gray-400">
                <i class="ri-question-line mr-2"></i>
                <span>Need Help Deciding?</span>
            </a>
        </div>
        
        <div class="mt-12 pt-8 border-t border-gray-300/30">
            <p class="text-sm text-gray-600">
                <i class="ri-shield-check-line text-[#318069] mr-1"></i>
                <span>No credit card required • 14-day free trial • Cancel anytime</span>
            </p>
        </div>
    </div>
</section>

<!-- JavaScript for Interactive Elements -->
@push('scripts')
<script>
    // Billing Toggle Functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Billing Toggle
        const billingButtons = document.querySelectorAll('.billing-toggle-btn');
        billingButtons.forEach(button => {
            button.addEventListener('click', function() {
                const cycle = this.dataset.cycle;
                
                // Update active state
                billingButtons.forEach(btn => {
                    btn.classList.remove('active', 'bg-white', 'text-[#318069]', 'shadow-sm');
                    btn.classList.add('text-gray-700');
                });
                
                this.classList.add('active', 'bg-white', 'text-[#318069]', 'shadow-sm');
                this.classList.remove('text-gray-700');
                
                // Update prices
                updatePricing(cycle);
            });
        });
        
        // FAQ Toggle
        const faqQuestions = document.querySelectorAll('.faq-question');
        faqQuestions.forEach(question => {
            question.addEventListener('click', function() {
                const answer = this.nextElementSibling;
                const icon = this.querySelector('i');
                
                // Toggle answer visibility
                answer.classList.toggle('hidden');
                
                // Toggle icon
                if (icon.classList.contains('ri-add-line')) {
                    icon.classList.remove('ri-add-line');
                    icon.classList.add('ri-subtract-line');
                } else {
                    icon.classList.remove('ri-subtract-line');
                    icon.classList.add('ri-add-line');
                }
            });
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
    
    function updatePricing(cycle) {
        const priceElements = document.querySelectorAll('[data-monthly][data-yearly]');
        
        priceElements.forEach(element => {
            const monthly = element.getAttribute('data-monthly');
            const yearly = element.getAttribute('data-yearly');
            
            if (cycle === 'yearly') {
                // Calculate yearly price (monthly * 12 * 0.8 for 20% discount)
                const yearlyPrice = Math.round(monthly * 12 * 0.8);
                element.textContent = `$${yearlyPrice}`;
                
                // Update period text
                const periodElement = element.nextElementSibling;
                if (periodElement && periodElement.classList.contains('text-gray-600')) {
                    periodElement.textContent = '/year';
                }
            } else {
                element.textContent = `$${monthly}`;
                
                // Update period text
                const periodElement = element.nextElementSibling;
                if (periodElement && periodElement.classList.contains('text-gray-600')) {
                    periodElement.textContent = '/month';
                }
            }
        });
    }
</script>
@endpush

@endsection