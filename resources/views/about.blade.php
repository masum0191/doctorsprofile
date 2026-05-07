@extends('layouts.sass')

@section('title', 'About Us')

@php
    $companyName = $setting->company_name ?: config('app.name', 'Doctors Profile');
    $tagline = $setting->tagline ?: 'Trusted digital tools for doctors, clinics, and patients.';
    $aboutText = trim((string) ($setting->about ?? ''));
    $foundingYear = $setting->founding_year ?? '2025';
    $teamSize = $setting->team_size ?? '50+';
    $countriesServed = $setting->countries_served ?? '10+';
@endphp

@section('content')
<style>
    .about-hero {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 50%, #e8f5f0 100%);
        padding: 4rem 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-top: 3rem;
    }

    .stat-item {
        text-align: center;
        padding: 1.5rem;
        background: white;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: #318069;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #318069;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
    }

    .value-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        height: 100%;
    }

    .value-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: #318069;
    }

    .value-icon {
        width: 48px;
        height: 48px;
        background: rgba(49, 128, 105, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .value-icon i {
        font-size: 1.5rem;
        color: #318069;
    }

    .team-card {
        text-align: center;
        padding: 1.5rem;
        background: white;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .team-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: #318069;
    }

    .team-avatar {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #318069, #276854);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .team-avatar i {
        font-size: 2.5rem;
        color: white;
    }

    .testimonial-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        position: relative;
    }

    .testimonial-quote {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 3rem;
        color: rgba(49, 128, 105, 0.1);
        font-family: Georgia, serif;
    }

    .contact-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .contact-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: #318069;
    }

    .social-link {
        width: 40px;
        height: 40px;
        background: #f1f5f9;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        color: #64748b;
    }

    .social-link:hover {
        background: #318069;
        color: white;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .stat-number {
            font-size: 1.5rem;
        }
    }
</style>

<!-- Hero Section -->
<section class="about-hero">
    <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
        <div class="text-center max-w-3xl mx-auto">
            <span class="inline-flex items-center rounded-full bg-[#318069]/10 px-4 py-2 text-sm font-semibold text-[#318069] mb-4">
                <i class="ri-information-line mr-2"></i>
                About {{ $companyName }}
            </span>
            <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl mb-4">
                Empowering Healthcare Professionals
            </h1>
            <p class="text-lg leading-8 text-slate-600">
                {{ $tagline }}
            </p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">{{ $foundingYear }}</div>
                <div class="stat-label">Year Founded</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $teamSize }}</div>
                <div class="stat-label">Team Members</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $countriesServed }}</div>
                <div class="stat-label">Countries Served</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">10K+</div>
                <div class="stat-label">Happy Patients</div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="bg-white py-16 sm:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Who We Are -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                    <i class="ri-group-line text-[#318069] text-2xl"></i>
                    Who We Are
                </h2>
                <div class="mt-6 space-y-5 text-base leading-8 text-slate-600">
                    @if($aboutText !== '')
                        {!! nl2br(e($aboutText)) !!}
                    @else
                        <p>{{ $companyName }} helps doctors build a stronger online presence and gives patients a simpler way to discover trusted healthcare professionals.</p>
                        <p>Our platform is designed to support doctor profiles, appointment management, digital practice growth, and a smoother patient experience across web and mobile touchpoints.</p>
                        <p>We focus on practical healthcare technology that is easy to use, scalable for growing practices, and reliable for day-to-day operations.</p>
                    @endif
                </div>
            </div>

            <!-- Our Mission -->
            <div class="rounded-2xl bg-gradient-to-br from-[#318069] to-[#276854] p-6 sm:p-8 text-white shadow-sm">
                <h2 class="text-2xl font-bold flex items-center gap-2">
                    <i class="ri-rocket-line"></i>
                    Our Mission
                </h2>
                <p class="mt-5 text-base leading-8 text-white/90">
                    To modernize healthcare through simple, powerful digital solutions that help doctors focus on what matters most — patient care.
                </p>
                <div class="mt-8 pt-6 border-t border-white/20">
                    <h3 class="text-lg font-semibold mb-3">Our Vision</h3>
                    <p class="text-white/90 leading-7">
                        Building a connected healthcare ecosystem where every doctor and patient can access quality healthcare tools seamlessly.
                    </p>
                </div>
            </div>
        </div>

        <!-- Core Values -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-slate-900 text-center mb-8">
                Our Core Values
            </h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="ri-user-heart-line"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-2">Patient First</h4>
                    <p class="text-sm text-slate-600">Everything we build starts with improving patient care.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="ri-shield-check-line"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-2">Trust & Security</h4>
                    <p class="text-sm text-slate-600">Medical data protection is our top priority.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="ri-lightbulb-line"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-2">Innovation</h4>
                    <p class="text-sm text-slate-600">Constantly evolving with modern healthcare needs.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="ri-team-line"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-2">Collaboration</h4>
                    <p class="text-sm text-slate-600">Working together to create better healthcare solutions.</p>
                </div>
            </div>
        </div>

        <!-- Leadership Team Section -->
        <div class="mt-16">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-slate-900">Meet Our Leadership</h2>
                <p class="text-slate-600 mt-2">A team dedicated to transforming healthcare technology</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="team-card">
                    <div class="team-avatar">
                        <i class="ri-user-line"></i>
                    </div>
                    <h4 class="font-bold text-slate-900">Dr. Sarah Johnson</h4>
                    <p class="text-sm text-[#318069] mb-2">Chief Medical Officer</p>
                    <p class="text-sm text-slate-600">15+ years in clinical practice</p>
                </div>
                <div class="team-card">
                    <div class="team-avatar">
                        <i class="ri-user-line"></i>
                    </div>
                    <h4 class="font-bold text-slate-900">Michael Chen</h4>
                    <p class="text-sm text-[#318069] mb-2">Chief Technology Officer</p>
                    <p class="text-sm text-slate-600">Healthcare tech specialist</p>
                </div>
                <div class="team-card">
                    <div class="team-avatar">
                        <i class="ri-user-line"></i>
                    </div>
                    <h4 class="font-bold text-slate-900">Emily Rodriguez</h4>
                    <p class="text-sm text-[#318069] mb-2">Head of Operations</p>
                    <p class="text-sm text-slate-600">Managing partner relationships</p>
                </div>
            </div>
        </div>

        <!-- Contact & Dark Card Side by Side -->
        <div class="grid lg:grid-cols-2 gap-8 mt-12">
            <!-- Contact Information -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <i class="ri-mail-line text-[#318069]"></i>
                    Contact Information
                </h3>
                <div class="space-y-5">
                    <div class="flex items-start gap-3">
                        <i class="ri-mail-line text-[#318069] mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-slate-900">Email</div>
                            <div class="text-slate-600">{{ $setting->email ?: 'Not updated yet' }}</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="ri-phone-line text-[#318069] mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-slate-900">Phone</div>
                            <div class="text-slate-600">{{ $setting->phone ?: 'Not updated yet' }}</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="ri-map-pin-line text-[#318069] mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-slate-900">Address</div>
                            <div class="text-slate-600">{{ $setting->address ?: 'Not updated yet' }}</div>
                        </div>
                    </div>
                    @if($setting->website)
                    <div class="flex items-start gap-3">
                        <i class="ri-global-line text-[#318069] mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-slate-900">Website</div>
                            <div class="text-slate-600">{{ $setting->website }}</div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Social Links -->
                <div class="mt-6 pt-6 border-t border-slate-200">
                    <h4 class="font-semibold text-slate-900 mb-3">Follow Us</h4>
                    <div class="flex gap-3">
                        <a href="#" class="social-link"><i class="ri-facebook-fill"></i></a>
                        <a href="#" class="social-link"><i class="ri-twitter-x-line"></i></a>
                        <a href="#" class="social-link"><i class="ri-linkedin-fill"></i></a>
                        <a href="#" class="social-link"><i class="ri-instagram-line"></i></a>
                    </div>
                </div>
            </div>

            <!-- Why Choose Us -->
            <div class="rounded-2xl bg-slate-900 p-6 sm:p-8 text-white shadow-sm">
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <i class="ri-star-line"></i>
                    Why Doctors Choose Us
                </h3>
                <ul class="mt-6 space-y-4">
                    <li class="flex items-start gap-3">
                        <i class="ri-checkbox-circle-fill text-[#318069] mt-0.5"></i>
                        <span>Professional doctor profile and branding support</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="ri-checkbox-circle-fill text-[#318069] mt-0.5"></i>
                        <span>Appointment and patient engagement tools</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="ri-checkbox-circle-fill text-[#318069] mt-0.5"></i>
                        <span>Tenant-based practice management setup</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="ri-checkbox-circle-fill text-[#318069] mt-0.5"></i>
                        <span>Scalable platform for growing clinics and individual doctors</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="ri-checkbox-circle-fill text-[#318069] mt-0.5"></i>
                        <span>24/7 dedicated customer support</span>
                    </li>
                </ul>
                
                <!-- CTA Button -->
                <div class="mt-8 pt-6 border-t border-white/20">
                    <a href="" class="inline-flex items-center gap-2 text-[#318069] bg-white px-5 py-2.5 rounded-lg font-semibold hover:bg-gray-100 transition-all">
                        Get in Touch
                        <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-[#318069] to-[#276854]">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Ready to get started?</h2>
        <p class="text-white/90 text-lg mb-6">Join thousands of healthcare professionals using our platform</p>
        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white text-[#318069] px-8 py-3 rounded-xl font-semibold hover:shadow-lg transition-all">
            Sign Up Today
            <i class="ri-arrow-right-line"></i>
        </a>
    </div>
</section>
@endsection