@extends('layouts.forntend')
@section('title', 'Services')
@section('content')
    <section id="services" class="py-20 bg-gradient-to-br from-gray-50 to-cyan-50">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="text-center mb-16">
                <div class="inline-block px-4 py-2 bg-cyan-100 text-cyan-700 rounded-full text-sm font-medium mb-4">
                    Our Services
                </div>
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4" style="font-family: 'Playfair Display', serif;">
                    Comprehensive Healthcare Services
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">We offer a wide range of medical services
                    designed to keep you healthy and address your healthcare needs at every stage of life.</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($services as $s)
                    <div class="bg-white rounded-2xl p-8 hover:shadow-2xl transition-all duration-300 cursor-pointer group">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-teal-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <i class="{{ $s->icon ?: 'ri-stethoscope-fill' }} text-3xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">
                            {{ $s->title }}
                            @if ($s->badge)
                                <span
                                    class="ml-2 inline-block text-xs px-2 py-1 bg-cyan-100 text-cyan-700 rounded">{{ $s->badge }}</span>
                            @endif
                        </h3>
                        @if ($s->description)
                            <p class="text-gray-600 mb-6 leading-relaxed">{{ $s->description }}</p>
                        @endif

                        @if (!empty($s->features))
                            <ul class="space-y-3">
                                @foreach ($s->features as $f)
                                    <li class="flex items-start gap-2">
                                        <i class="ri-check-line text-cyan-600 mt-1 flex-shrink-0"></i>
                                        <span class="text-gray-700 text-sm">{{ $f }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach

            </div>

            <div class="mt-16 bg-white rounded-2xl p-8 lg:p-12 shadow-xl">
                <div class="grid lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">Why Choose Our Practice?</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">We are committed to providing exceptional
                            medical care in a comfortable and welcoming environment. Our patient-centered
                            approach ensures you receive personalized attention and comprehensive treatment.</p>
                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="ri-time-line text-cyan-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Flexible Scheduling</h4>
                                    <p class="text-gray-600 text-sm">Convenient appointment times including
                                        early morning and evening slots</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="ri-shield-check-line text-cyan-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Insurance Accepted</h4>
                                    <p class="text-gray-600 text-sm">We accept most major insurance plans and
                                        offer flexible payment options</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="ri-phone-line text-cyan-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">24/7 Support</h4>
                                    <p class="text-gray-600 text-sm">Emergency contact available for urgent
                                        medical concerns</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <img alt="Medical facility" class="rounded-xl shadow-lg w-full h-auto object-cover object-top"
                            src="https://readdy.ai/api/search-image?query=Modern%20medical%20clinic%20interior%2C%20bright%20and%20clean%20examination%20room%2C%20professional%20healthcare%20facility%2C%20medical%20equipment%2C%20comfortable%20patient%20area%2C%20welcoming%20atmosphere%2C%20natural%20lighting%2C%20contemporary%20design&amp;width=700&amp;height=500&amp;seq=clinic-interior-001&amp;orientation=landscape">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
