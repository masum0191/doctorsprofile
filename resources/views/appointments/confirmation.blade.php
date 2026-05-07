@extends('layouts.sass')

@section('title', 'Appointment Confirmation')
@section('meta_description', 'Appointment confirmation and booking receipt.')

@php
    $appointmentNumber = $appointment->appointment_number ?: ('APT' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT));
    $paymentLabel = match ($appointment->payment_method) {
        'ssl_commerce' => 'SSL Commerz',
        'cod' => 'Cash on Visit',
        'free' => 'Free',
        default => $appointment->payment_method ? \Illuminate\Support\Str::headline($appointment->payment_method) : 'Pending',
    };
    $statusLabel = \Illuminate\Support\Str::headline($appointment->status ?? 'pending');
    $paymentStatusLabel = \Illuminate\Support\Str::headline($appointment->payment_status ?? 'pending');
    
    $isPending = $appointment->status === 'pending' || $appointment->payment_status === 'pending';
@endphp

@section('content')
<section class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-teal-50/40 pt-28 pb-16 print:bg-white print:pt-8 print:pb-0">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 print:max-w-none print:px-0">
        
        <!-- Success Header with Action Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div class="text-center sm:text-left">
                <div class="hidden print:block text-left mb-2">
                    <div class="text-xl font-bold text-[#318069]">✓ APPOINTMENT CONFIRMED</div>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Appointment Confirmation</h1>
                <p class="mt-1 text-gray-600 text-sm">Your booking request has been received successfully.</p>
            </div>
            
            <!-- Action Buttons - Top Right -->
            <div class="flex flex-col sm:flex-row gap-3 print:hidden">
               
                
                <div class="flex gap-2">
                    <button onclick="window.print()" class="bg-gradient-to-r from-[#318069] to-[#276854] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 text-white px-5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2">
                        <i class="ri-printer-line"></i>
                        <span>Download / Print</span>
                    </button>
                    <a href="{{ route('finds') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center gap-2">
                        <i class="ri-arrow-left-line"></i>
                        <span>Back</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.12)] border border-gray-100 overflow-hidden print:shadow-none print:border print:border-gray-200">
            
            <!-- Header Bar -->
            <div class="bg-gradient-to-r from-[#318069] to-teal-700 px-6 sm:px-8 py-5 text-white print:bg-gray-800">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-white/80 text-xs uppercase tracking-[0.2em] print:text-gray-400">Booking Reference</p>
                        <h2 class="text-2xl font-bold mt-1 font-mono">{{ $appointmentNumber }}</h2>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 rounded-full bg-white/20 text-sm backdrop-blur-sm print:bg-gray-700 print:text-white">
                            {{ $statusLabel }}
                        </span>
                        <span class="px-3 py-1 rounded-full bg-white/20 text-sm backdrop-blur-sm print:bg-gray-700 print:text-white">
                            Payment: {{ $paymentStatusLabel }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="p-6 sm:p-8 grid grid-cols-1 lg:grid-cols-2 gap-8 print:p-4 print:gap-4">
                
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2 print:text-base">
                           
                            Appointment Details
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-3">
                                <span class="text-gray-500 text-sm">Doctor</span>
                                <span class="font-semibold text-sm text-gray-900 text-right">Dr. {{ $appointment->doctor->name ?? 'Doctor' }}</span>
                            </div>
                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-3">
                                <span class="text-gray-500 text-sm">Date</span>
                                <span class="font-semibold text-sm text-gray-900">{{ optional($appointment->appointment_date)->format('l, d F Y') }}</span>
                            </div>
                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-3">
                                <span class="text-gray-500 text-sm">Time</span>
                                <span class="font-semibold text-sm text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</span>
                            </div>
                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-3">
                                <span class="text-gray-500 text-sm">Consultation Type</span>
                                <span class="font-semibold text-sm text-gray-900">{{ \Illuminate\Support\Str::headline($appointment->consultation_type) }}</span>
                            </div>
                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-3">
                                <span class="text-gray-500 text-sm">Chamber / Location</span>
                                <span class="font-semibold text-sm text-gray-900 text-right">{{ $appointment->chamber->name ?? 'Online Consultation' }}</span>
                            </div>
                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-3">
                                <span class="text-gray-500 text-sm">Payment Method</span>
                                <span class="font-semibold text-sm text-gray-900">{{ $paymentLabel }}</span>
                            </div>
                            <div class="flex justify-between gap-4 pt-2">
                                <span class="text-gray-700 font-semibold">Total Amount</span>
                                <span class="font-bold text-xl text-[#318069]">{{ number_format((float) $appointment->amount, 2) }} {{ $appointment->currency }}</span>
                            </div>
                        </div>
                    </div>

                    @if($appointment->chamber && ($appointment->chamber->address || $appointment->chamber->phone))
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2 print:text-base">
                               
                                Chamber Information
                            </h3>
                            <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4 space-y-2 text-sm text-gray-600 print:bg-white print:border print:border-gray-200">
                                @if($appointment->chamber->name)
                                    <p class="font-semibold text-gray-800">{{ $appointment->chamber->name }}</p>
                                @endif
                                @if($appointment->chamber->address)
                                    <p><i class="ri-map-pin-line mr-2 text-[#318069] print:hidden"></i>{{ $appointment->chamber->address }}</p>
                                @endif
                                @if($appointment->chamber->city)
                                    <p><i class="ri-building-line mr-2 text-[#318069] print:hidden"></i>{{ $appointment->chamber->city }}</p>
                                @endif
                                @if($appointment->chamber->phone)
                                    <p><i class="ri-phone-line mr-2 text-[#318069] print:hidden"></i>{{ $appointment->chamber->phone }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2 print:text-base">
                           
                            Patient Information
                        </h3>
                        <div class="rounded-2xl bg-gray-50 border border-gray-100 p-5 space-y-3 print:bg-white print:border print:border-gray-200">
                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500 text-sm">Full Name</span>
                                <span class="font-semibold text-sm text-gray-900 text-right">{{ trim($appointment->patient_first_name . ' ' . $appointment->patient_last_name) }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500 text-sm">Email Address</span>
                                <span class="font-semibold text-sm text-gray-900 text-right break-all">{{ $appointment->patient_email }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500 text-sm">Phone Number</span>
                                <span class="font-semibold text-sm text-gray-900 text-right">{{ $appointment->patient_phone }}</span>
                            </div>
                            @if($appointment->patient_symptoms)
                            <div class="flex justify-between gap-4 pt-2 border-t border-gray-200">
                                <span class="text-gray-500 text-sm">Symptoms / Notes</span>
                                <span class="text-gray-700 text-right text-sm">{{ $appointment->patient_symptoms }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Important Note Card -->
                    <div class="rounded-2xl border-l-4 border-l-amber-500 bg-amber-50 p-5 print:border print:border-gray-300">
                        <div class="flex items-start gap-3">
                            <div>
                                <h3 class="font-bold text-gray-900 mb-2">Important Information</h3>
                                <ul class="text-xs text-gray-700 space-y-1 list-disc list-inside">
                                    <li>Please arrive 10 minutes before your scheduled time</li>
                                    <li>Keep this confirmation for reference</li>
                                    @if($appointment->payment_method === 'cod')
                                    <li>Payment will be collected during your visit</li>
                                    @endif
                                    @if($isPending)
                                    <li class="text-amber-700 font-medium">This appointment is pending confirmation from the doctor's office</li>
                                    @endif
                                    <li>Bring any relevant medical reports if available</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t border-gray-100 px-6 sm:px-8 py-4 bg-gray-50/50 print:bg-white print:border-t">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-gray-500">
                    <div class="flex items-center gap-2">
                        <i class="ri-mail-send-line print:hidden"></i>
                        <span>Confirmation sent to: {{ $appointment->patient_email }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="ri-customer-service-line print:hidden"></i>
                        <span>For support: support@doctorsprofile.com</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Enhanced Print Styles */
    @media print {
        @page {
            margin: 1.5cm;
            size: A4;
        }
        
        /* Hide non-printable elements */
        header,
        footer,
        #mainHeader,
        #backToTopBtn,
        .modal-overlay-container,
        .no-print,
        .print\:hidden,
        button,
        .btn,
        [onclick="window.print()"],
        a[href*="finds"] {
            display: none !important;
        }
        
        /* Force white background */
        body, 
        section, 
        .bg-gradient-to-br,
        .bg-white {
            background: white !important;
            background-color: white !important;
        }
        
        /* Remove shadows and borders for print */
        .shadow-\[0_20px_60px_-15px_rgba\(0,0,0,0\.12\)\] {
            box-shadow: none !important;
        }
        
        .rounded-3xl {
            border-radius: 0 !important;
        }
        
        /* Ensure borders are visible */
        .border {
            border: 1px solid #e5e7eb !important;
        }
        
        /* Text colors for print */
        .text-gray-900, 
        .font-bold,
        .font-semibold {
            color: black !important;
        }
        
        .text-gray-500,
        .text-gray-600,
        .text-gray-700 {
            color: #4b5563 !important;
        }
        
        /* Keep brand color for print */
        .text-\[#318069\] {
            color: #318069 !important;
        }
        
        /* Ensure proper spacing */
        .p-6, .p-8 {
            padding: 1rem !important;
        }
        
        .gap-8 {
            gap: 1rem !important;
        }
        
        /* Keep borders for cards */
        .border-b {
            border-bottom: 1px solid #e5e7eb !important;
        }
        
        /* Page break control */
        .page-break-inside-avoid {
            page-break-inside: avoid;
        }
        
        /* Show pending status in print */
        .print\:bg-gray-700 {
            background-color: #374151 !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add print title for better document identification
        const printTitle = document.createElement('div');
        printTitle.className = 'print-only hidden print:block text-center mb-4';
        printTitle.innerHTML = `
            <div style="font-size: 12px; color: #666; margin-bottom: 20px; text-align: center;">
                Printed on: ${new Date().toLocaleString()}
            </div>
        `;
        document.querySelector('.prescription-card')?.prepend(printTitle);
    });
    
    // Before print event to prepare page
    window.addEventListener('beforeprint', function() {
        document.body.classList.add('printing');
    });
    
    window.addEventListener('afterprint', function() {
        document.body.classList.remove('printing');
    });
</script>
@endsection