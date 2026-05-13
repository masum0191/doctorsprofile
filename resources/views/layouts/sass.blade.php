<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <?php
    $settingModel = \App\Models\CompanySetting::first();
    ?>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ url($settingModel->favicon) }}" type="image/x-icon" />
    @include('partials.seo')

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href=" https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .custom-title-line {
            line-height: 1.12 !important;
        }

       /* Fixed Modal Scrolling */
        .modal-overlay-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            z-index: 99999;
            justify-content: center;
            align-items: center;
            padding: 40px;

            
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 600px;
            height: 100%;
            max-height: 700px;
            overflow-y: auto;
            margin: auto; /* Centers the modal */
            position: relative;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: modalFadeIn 0.3s ease;
            /* Remove max-height and overflow from here */
        }


        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 28px;
            cursor: pointer;
            color: #666;
            background: none;
            border: none;
            line-height: 1;
        }

        .close-modal:hover {
            color: #000;
        }



        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .btn-submit {
            background: #318069;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
        }

        .btn-submit:hover {
            background: #276854;
        }

        /* Multi-step form styles */

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1;
            position: relative;
        }

        .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #e5e7eb;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-bottom: 8px;
            transition: all 0.3s;
        }

        .step.active .step-circle {
            background: #318069;
            color: white;
        }

        .step-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
        }

        .step.active .step-label {
            color: #318069;
            font-weight: 600;
        }

        .step-line {
            flex: 1;
            height: 2px;
            background: #e5e7eb;
            margin: 0 10px;
            align-self: center;
        }

        .form-step {
            transition: opacity 0.3s;
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .payment-method input:checked+div {
            border-color: #318069;
            background-color: rgba(49, 128, 105, 0.05);
        }

        .payment-method {
            cursor: pointer;
        }

        /* Enhanced Modal Styles - Keeping All Original Classes */
.modal-overlay-container {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px);
    z-index: 99999;
    justify-content: center;
    align-items: center;
    padding: 20px;
}


@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.close-modal {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 24px;
    cursor: pointer;
    color: #9ca3af;
    background: none;
    border: none;
    line-height: 1;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s;
}

.close-modal:hover {
    color: #318069;
    background-color: #f3f4f6;
}



.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.2s;
    background-color: white;
}

.form-control:focus {
    outline: none;
    border-color: #318069;
    box-shadow: 0 0 0 4px rgba(49, 128, 105, 0.1);
}

.form-control:hover {
    border-color: #d1d5db;
}

select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1.2rem;
    padding-right: 2.5rem;
}

.btn-submit {
    background: linear-gradient(135deg, #318069 0%, #276854 100%);
    color: white;
    padding: 0.875rem 1.5rem;
    border: none;
    border-radius: 10px;
    width: 100%;
    cursor: pointer;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.2s;
    box-shadow: 0 4px 6px -1px rgba(49, 128, 105, 0.2);
}

.btn-submit:hover {
    background: linear-gradient(135deg, #276854 0%, #1f5443 100%);
    transform: translateY(-1px);
    box-shadow: 0 10px 15px -3px rgba(49, 128, 105, 0.3);
}

.btn-submit:active {
    transform: translateY(0);
}

/* Progress Steps Enhancement */
.progress-steps {
    padding-top:10px;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    flex: 1;
    position: relative;
}

.step-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f3f4f6;
    color: #9ca3af;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-bottom: 0.5rem;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.step.active .step-circle {
    background: #318069;
    color: white;
    box-shadow: 0 0 0 4px rgba(49, 128, 105, 0.2);
    transform: scale(1.05);
}

.step-label {
    font-size: 0.75rem;
    color: #9ca3af;
    font-weight: 500;
    transition: all 0.3s;
}

.step.active .step-label {
    color: #318069;
    font-weight: 600;
}

.step-line {
    flex: 1;
    height: 2px;
    background: #e5e7eb;
    margin: 0 0.5rem;
    align-self: center;
}

/* Payment Method Enhancement */
.payment-method {
    cursor: pointer;
}

.payment-method input:checked + div {
    border-color: #318069;
    background-color: rgba(49, 128, 105, 0.05);
    box-shadow: 0 4px 12px rgba(49, 128, 105, 0.1);
    transform: scale(1.02);
}

.payment-method div {
    transition: all 0.2s;
    border-width: 2px;
}

/* Button Secondary Enhancement */
.btn-secondary {
    background: white;
    color: #374151;
    border: 1.5px solid #e5e7eb;
    padding: 0.875rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 1rem;
}

.btn-secondary:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* Terms Section Enhancement */
.terms-section {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 1rem;
}

.terms-section input[type="checkbox"] {
    width: 1rem;
    height: 1rem;
    accent-color: #318069;
}

/* Appointment Summary Enhancement */
.appointment-summary {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 1rem;
}

/* Payment Summary Enhancement */
.payment-summary {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 1.25rem;
}

/* Responsive Enhancement */
@media (max-width: 640px) {
    .modal-content {
        padding: 20px;
        width: 95%;
        height: auto;
        max-height: auto;
    }

    .form-grid {
        gap: 0.75rem;
    }

    .step-label {
        font-size: 0.7rem;
    }

    .step-circle {
        width: 32px;
        height: 32px;
        font-size: 0.9rem;
    }
}



/* Searchable Select Styles */
.searchable-select-wrapper {
    position: relative;
    width: 100%;
}

.ts-control {
    background: transparent !important;
    border-radius: 0px !important;
    border: none !important;
    outline: none !important;
    padding: 0px !important;
    font-size: 14px !important;
    cursor: pointer !important;
    min-height: auto !important;
    box-shadow: none !important;
}

.ts-control:hover {
    background: #e5e7eb !important;
}

.ts-wrapper.focus .ts-control {
    border-color: #318069 !important;
    background: transparent !important;
    box-shadow: none !important;
}

.ts-dropdown {
    border-radius: 0.75rem !important;
    border: 1px solid #e5e7eb !important;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
    max-height: 250px !important;
    overflow-y: auto !important;
    z-index: 9999 !important;
}

.ts-dropdown .option {
    padding: 10px 15px !important;
    font-size: 14px !important;
}

.ts-dropdown .option:hover {
    background: #e8f5f0 !important;
    color: #318069 !important;
}

.ts-dropdown .active {
    background: #318069 !important;
    color: white !important;
}

.ts-wrapper .ts-control .item {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
}

    </style>



</head>

<body>
    @include('partials.analytics-body')
    <div class="bg-white">
        <header id="mainHeader" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-transparent">
            <div class="max-w-7xl mx-auto px-3 md:px-6 py-3">
                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-6">
                        <a href="{{ url('/') }}">
                            <img alt="Doctor Directory Logo" class="h-8 md:h-12  w-auto object-contain"
                                src="{{ url($settingModel->logo) }}">
                        </a>
                    </div>
                    <!-- <div class="col-span-6">
                        <div class="hidden lg:flex flex-1 max-w-2xl mx-8">
                    <div class="w-full max-w-xl bg-gray-50 rounded-full border border-[#318069]/20 flex items-center px-3 py-2"><i
                            class="ri-search-line text-xl text-gray-400 mr-3"></i><input
                            placeholder="Search anything here.."
                            class="flex-1 outline-none text-sm bg-gray-50 text-gray-700 placeholder-gray-400" type="text"
                            autocomplete="off"><button
                            class="ml-3 bg-[#318069] hover:bg-[#276854] text-white px-6 py-2 rounded-full text-sm font-medium transition-colors whitespace-nowrap">Search</button>
                    </div>
                </div>
                    </div> -->
                    <div class="col-span-6 flex justify-end">
                         <div class="flex items-center gap-8">

                        <a href="{{ url('/package') }}"
                        class="bg-[#318069] hover:bg-[#276854] text-white px-5 py-3 rounded-full md:text-[15px] text-xs font-medium transition-colors whitespace-nowrap">Create Doctor Website</a>
                </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow-1">
            {{-- flash message --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @elseif(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            @yield('content')
        </main>


        <footer class="relative mt-24 bg-gradient-to-b from-white to-slate-50">
            <!-- Top Wave -->
            <div class="absolute -top-20 left-0 right-0 h-20 overflow-hidden rotate-180">
                <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="absolute top-0 left-0 w-full h-full">
                    <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28
                70.36-5.37,136.33-33.31,206.8-37.5
                C438.64,32.43,512.34,53.67,583,72.05
                c69.27,18,138.3,24.88,209.4,13.08
                36.15-6,69.85-17.84,104.45-29.34
                C989.49,25,1113-14.29,1200,52.47V0Z" fill="#006A4E" fill-opacity="0.25" />
                    <path d="M0,0V15.81
                C13,36.92,27.64,56.86,47.69,72.05
                99.41,111.27,165,111,224.58,91.58
                c31.15-10.15,60.09-26.07,89.67-39.8
                40.92-19,84.73-46,130.83-49.67
                36.26-2.85,70.9,9.42,98.6,31.56
                31.77,25.39,62.32,62,103.63,73
                40.44,10.79,81.35-6.69,119.13-24.28
                s75.16-39,116.92-43.05
                c59.73-5.85,113.28,22.88,168.9,38.84
                30.2,8.66,59,6.17,87.09-7.5
                22.43-10.89,48-26.93,60.65-49.24V0Z" fill="#006A4E" fill-opacity="0.5" />
                    <path d="M0,0V5.63
                C149.93,59,314.09,71.32,475.83,42.57
                c43-7.64,84.23-20.12,127.61-26.46
                59-8.63,112.48,12.24,165.56,35.4
                C827.93,77.22,886,95.24,951.2,90
                c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="#006A4E" fill-opacity="0.25" />
                </svg>
            </div>

            <!-- Main Footer -->
            <div class="relative z-10 bg-[#4e9480] py-20">
                <div class="max-w-7xl mx-auto px-6">

                    <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-4">

                        <!-- Brand -->
                        <div>
                            <img src="{{ url('images/logo.png') }}" class="h-16 mb-6 filter invert brightness-0"
                                alt="Doctors Profile XYZ">
                            <p class="text-slate-100 text-sm leading-relaxed mb-6">
                                <strong>Doctors Profile XYZ</strong> is a Trust-IT-BD product that helps patients
                                easily discover verified doctors, view profiles, check availability, and
                                book appointments online with confidence.
                            </p>

                            <div class="space-y-3 text-sm text-slate-100">
                                <div class="flex items-center gap-2">
                                    <i class="ri-shield-check-line text-[#FFC107]"></i>
                                    <span>Verified Doctors & Clinics</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div>
                            <h4
                                class="text-white text-lg font-semibold mb-6 border-b border-white/20 pb-3 relative
                        after:absolute after:-bottom-[2px] after:left-0 after:w-10 after:h-[2px]
                        after:bg-gradient-to-br from-[#FFC107] to-amber-500">
                                Quick Links
                            </h4>

                            <ul class="space-y-3 text-sm text-slate-100">
                                <li>
                                    <a href="/" class="flex items-center gap-2 hover:text-[#FFC107] transition">
                                        <i class="ri-arrow-right-s-line text-xs"></i> Home
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.login') }}"
                                        class="flex items-center gap-2 hover:text-[#FFC107] transition">
                                        <i class="ri-arrow-right-s-line text-xs"></i> Login
                                    </a>
                                </li>
                                <li>
                                    <a href="/package"
                                        class="flex items-center gap-2 hover:text-[#FFC107] transition">
                                        <i class="ri-arrow-right-s-line text-xs"></i> Package
                                    </a>
                                </li>
                                <li>
                                    <a href="/about" class="flex items-center gap-2 hover:text-[#FFC107] transition">
                                        <i class="ri-arrow-right-s-line text-xs"></i> About Us
                                    </a>
                                </li>
                                <li>
                                    <a href="/contact"
                                        class="flex items-center gap-2 hover:text-[#FFC107] transition">
                                        <i class="ri-arrow-right-s-line text-xs"></i> Contact Support
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Contact -->
                        <div>
                            <h4
                                class="text-white text-lg font-semibold mb-6 border-b border-white/20 pb-3 relative
                        after:absolute after:-bottom-[2px] after:left-0 after:w-10 after:h-[2px]
                        after:bg-gradient-to-br from-[#FFC107] to-amber-500">
                                Contact Information
                            </h4>

                            <div class="space-y-6 text-slate-100 text-sm">
                                <div class="flex gap-4">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                        <i class="ri-map-pin-line text-lg"></i>
                                    </div>
                                    <p>
                                        <strong>Office</strong><br>
                                        {{ $settingModel->address }}
                                    </p>
                                </div>

                                <div class="flex gap-4">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                        <i class="ri-phone-line text-lg"></i>
                                    </div>
                                    <p><strong>Phone</strong><br>{{ $settingModel->phone }}</p>
                                </div>

                                <div class="flex gap-4">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                        <i class="ri-mail-line text-lg"></i>
                                    </div>
                                    <p><strong>E-mail</strong><br>{{ $settingModel->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Newsletter -->
                        <div>
                            <h4
                                class="text-white text-lg font-semibold mb-6 border-b border-white/20 pb-3 relative
                        after:absolute after:-bottom-[2px] after:left-0 after:w-10 after:h-[2px]
                        after:bg-gradient-to-br from-[#FFC107] to-amber-500">
                                Stay Updated
                            </h4>

                            <p class="text-slate-100 text-sm mb-2">
                                Get health tips, doctor updates & platform news.
                            </p>

                            <form class="flex overflow-hidden rounded-xl border border-white/20 mb-6">
                                <input type="email" placeholder="Your email address"
                                    class="flex-1 px-4 py-3 text-sm outline-none">
                                <button
                                    class="bg-gradient-to-br from-[#FFC107] to-amber-500 px-5 text-white transition">
                                    <i class="ri-send-plane-2-line"></i>
                                </button>
                            </form>

                            <h5 class="text-white font-semibold mb-2">Follow Us</h5>
                            <div class="flex gap-3">
                                <a
                                    class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center hover:bg-emerald-600 transition">
                                    <i class="ri-facebook-fill text-lg text-white"></i>
                                </a>
                                <a
                                    class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center hover:bg-emerald-600 transition">
                                    <i class="ri-twitter-fill text-lg text-white"></i>
                                </a>
                                <a
                                    class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center hover:bg-emerald-600 transition">
                                    <i class="ri-linkedin-fill text-lg text-white"></i>
                                </a>
                                <a
                                    class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center hover:bg-emerald-600 transition">
                                    <i class="ri-instagram-line text-lg text-white"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Bottom -->
            <div class="bg-[#318069] py-6 text-center text-sm text-white/90">
                © 2025 Doctors Profile XYZ · A Trust-IT-BD Product · All rights reserved
            </div>

           <!-- Back to Top -->
            <button id="backToTopBtn"
                class="fixed bottom-8 right-8 w-12 h-12 bg-gradient-to-br from-[#FFC107] to-amber-500
                text-white rounded-full flex items-center justify-center shadow-lg z-50 opacity-0 invisible transition-all duration-300 hover:scale-110 hover:shadow-xl">
                <i class="ri-arrow-up-line"></i>
            </button>

        </footer>

    </div>

   <!-- MODAL - Enhanced Design Only (Structure Unchanged) -->
<div id="bookingModal" class="modal-overlay-container" style="display: none;">
    <div class="modal-content">
        <button class="close-modal" onclick="window.closeBookingModal()">
            <i class="ri-close-line text-2xl"></i>
        </button>
        <h2 id="modalDoctorName" class="text-xl font-bold mb-4 text-gray-800">Book Appointment</h2>

        <!-- Progress Steps - Enhanced Colors -->
        <div class="progress-steps mb-6">
            <div class="flex items-center justify-between">
                <div class="step active" data-step="1">
                    <div class="step-circle">1</div>
                    <div class="step-label">Details</div>
                </div>
                <div class="step-line"></div>
                <div class="step" data-step="2">
                    <div class="step-circle">2</div>
                    <div class="step-label">Patient Info</div>
                </div>
                <div class="step-line"></div>
                <div class="step" data-step="3">
                    <div class="step-circle">3</div>
                    <div class="step-label">Payment</div>
                </div>
            </div>
        </div>

        <form id="bookingForm" action="{{ route('appointments.store') }}" method="POST">
            @csrf
            <input type="hidden" id="doctor_id" name="doctor_id">
            <input type="hidden" id="appointment_date" name="appointment_date">
            <input type="hidden" id="appointment_time" name="appointment_time">
            <input type="hidden" id="total_amount" name="total_amount">

            <!-- Step 1: Appointment Details -->
            <div class="form-step active" data-step="1">
                <!-- Consultation Type -->
                <div class="form-group mb-3">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Consultation Type</label>
                    <select id="consultationType" name="consultation_type" class="form-control" required>
                        <option value="">Select</option>
                        <option value="online">Online</option>
                        <option value="offline">In-person</option>
                    </select>
                </div>

                <!-- Chamber -->
                <div class="form-group mb-3">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Chamber</label>
                    <select id="chamberSelect" name="chamber_id" class="form-control" disabled>
                        <option value="">Select chamber</option>
                    </select>
                    <small id="chamberFees" class="text-sm text-[#318069] font-medium mt-1 hidden">
                        <i class="ri-information-line mr-1"></i>Fee: ৳<span id="feeDisplay">0.00</span>
                    </small>
                </div>

                <!-- Date -->
                <div class="form-group mb-4">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Date</label>
                    <input type="date" id="datePicker" class="form-control" min="{{ date('Y-m-d') }}" required>
                </div>

                <!-- Time -->
                <div class="form-group mb-3">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Time Slot</label>
                    <select id="timeSelect" class="form-control" required>
                        <option value="">Select time</option>
                    </select>
                </div>

                <!-- Appointment Summary -->
                <div class="appointment-summary mt-6 p-4 bg-gray-50 rounded-lg hidden">
                    <h4 class="font-semibold mb-2 text-gray-800">Appointment Summary</h4>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Consultation Fee:</span>
                        <span id="feeAmount" class="font-semibold mt-3 text-[#318069]">৳ 0.00</span>
                    </div>
                </div>

                <button type="button" class="next-step btn-submit mt-4" data-next="2">Next: Patient Info</button>
            </div>

            <!-- Step 2: Patient Information -->
            <div class="form-step" data-step="2" style="display: none;">
                <div class="form-grid grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="block text-sm font-semibold mb-2 text-gray-700">First Name</label>
                        <input type="text" name="patient_first_name" placeholder="First Name"
                            class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-semibold mb-2 text-gray-700">Last Name</label>
                        <input type="text" name="patient_last_name" placeholder="Last Name"
                            class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-semibold mb-2 text-gray-700">Email</label>
                        <input type="email" name="patient_email" placeholder="Email" class="form-control"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-semibold mb-2 text-gray-700">Phone</label>
                        <input type="tel" name="patient_phone" placeholder="Phone" class="form-control"
                            required>
                    </div>
                    <div class="form-group md:col-span-2">
                        <label class="block text-sm font-semibold mb-2 text-gray-700">Symptoms / Reason for Visit</label>
                        <textarea name="patient_symptoms" placeholder="Symptoms or reason for visit (optional)" class="form-control"
                            rows="3"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" class="prev-step btn-secondary" data-prev="1">Back</button>
                    <button type="button" class="next-step btn-submit" data-next="3">Next: Payment</button>
                </div>
            </div>

            <!-- Step 3: Payment -->
            <div class="form-step" data-step="3" style="display: none;">
                <div class="payment-summary p-4 bg-gray-50 rounded-lg mb-4">
                    <h4 class="font-semibold mb-3 text-gray-800">Payment Summary</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Consultation Fee:</span>
                            <span id="finalFeeAmount" class="font-medium">৳ 0.00</span>
                        </div>
                        <div class="flex justify-between border-t pt-2">
                            <span class="font-semibold text-gray-800">Total Amount:</span>
                            <span id="totalFeeAmount" class="font-semibold text-lg text-[#318069]">৳ 0.00</span>
                        </div>
                    </div>
                </div>

                <div class="payment-methods mb-6">
                    <h4 class="font-semibold mb-3 text-gray-800">Select Payment Method</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="payment-method" id="paymentMethodSslCommerce">
                            <input type="radio" name="payment_method" value="ssl_commerce" checked
                                class="hidden">
                            <div class="border rounded-lg p-4 text-center cursor-pointer hover:border-[#318069] transition-all">
                                <div class="text-2xl mb-2">💳</div>
                                <div class="text-sm font-medium">SSL Commerz</div>
                                <div class="text-xs text-gray-500">Card/Bank/Mobile</div>
                            </div>
                        </label>
                        <label class="payment-method" id="paymentMethodCod">
                            <input type="radio" name="payment_method" value="cod" class="hidden">
                            <div class="border rounded-lg p-4 text-center cursor-pointer hover:border-[#318069] transition-all">
                                <div class="text-2xl mb-2">💰</div>
                                <div class="text-sm font-medium">Cash on Visit</div>
                                <div class="text-xs text-gray-500">Pay at chamber</div>
                            </div>
                        </label>
                    </div>
                    <p id="paymentGatewayNotice" class="text-xs text-gray-500 mt-3"></p>
                </div>

                <div class="terms-section mb-6">
                    <label class="flex items-start">
                        <input type="checkbox" name="terms_agreed" class="mt-1 mr-2 accent-[#318069]" required>
                        <span class="text-sm text-gray-600">I agree to the <a href="#" class="text-[#318069] hover:underline">terms and
                                conditions</a> and understand that this appointment is subject to doctor's
                            availability.</span>
                    </label>
                </div>

                <div class="flex gap-3">
                    <button type="button" class="prev-step btn-secondary" data-prev="2">Back</button>
                    <button type="submit" class="btn-submit flex-1" id="bookingSubmitButton">Confirm & Pay Now</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="bookingSuccessModal" class="modal-overlay-container" style="display: none;">
   <div class="modal-content max-w-2xl relative bg-white rounded-2xl shadow-2xl">
    
    <!-- Close Button -->
    <button class="absolute right-4 top-4 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 hover:rotate-90 transition-all duration-300 flex items-center justify-center z-10 group" 
            onclick="window.closeBookingSuccessModal()">
        <i class="ri-close-line text-xl text-gray-500 group-hover:text-gray-700"></i>
    </button>

    <!-- Success Icon & Title -->
    <div class="text-center mb-6">
        <div class="w-16 h-16 mx-auto rounded-full bg-emerald-50 flex items-center justify-center mb-2 shadow-inner">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#318069] to-[#276854] flex items-center justify-center shadow-lg">
                <i class="ri-check-double-line text-3xl text-white"></i>
            </div>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-1">Appointment Confirmed!</h2>
        <p id="successModalMessage" class="text-gray-500 text-sm">Your appointment has been successfully scheduled.</p>
    </div>

    <!-- Appointment Details Card -->
    <div class="bg-gray-50 rounded-xl p-5 space-y-4 border border-gray-100">
        <div class="flex items-center gap-2 pb-3 border-b border-gray-200">
            <i class="ri-calendar-check-line text-[#318069] text-lg"></i>
            <h3 class="font-semibold text-gray-800">Appointment Summary</h3>
        </div>
        
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-500 text-sm flex items-center gap-2">
                    <i class="ri-file-copy-line text-gray-400 text-sm"></i> Reference
                </span>
                <span id="successAppointmentReference" class="font-mono font-semibold text-gray-900 bg-gray-100 px-2 py-0.5 rounded text-sm">-</span>
            </div>
            
            <div class="flex justify-between">
                <span class="text-gray-500 text-sm flex items-center gap-2">
                    <i class="ri-user-line text-gray-400 text-sm"></i> Patient
                </span>
                <span id="successPatientName" class="font-medium text-sm text-gray-900 text-right">-</span>
            </div>
            
            <div class="flex justify-between">
                <span class="text-gray-500 text-sm flex items-center gap-2">
                    <i class="ri-calendar-line text-gray-400 text-sm"></i> Date
                </span>
                <span id="successAppointmentDate" class="font-medium text-sm text-gray-900">-</span>
            </div>
            
            <div class="flex justify-between">
                <span class="text-gray-500 text-sm flex items-center gap-2">
                    <i class="ri-time-line text-gray-400 text-sm"></i> Time
                </span>
                <span id="successAppointmentTime" class="font-medium text-sm text-gray-900">-</span>
            </div>
            
            <div class="flex justify-between">
                <span class="text-gray-500 text-sm flex items-center gap-2">
                    <i class="ri-bank-card-line text-gray-400 text-sm"></i> Payment
                </span>
                <span id="successPaymentMethod" class="font-medium text-gray-900">
                    <span class="inline-block px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-xs">-</span>
                </span>
            </div>
            
            <div class="flex justify-between pt-3 border-t border-gray-200">
                <span class="text-gray-700 font-semibold text-sm flex items-center gap-2">
                Total Amount
                </span>
                <span id="successAmount" class="font-bold text-xl text-[#318069]">-</span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-6">
        <button id="successDownloadBtn" class="bg-gradient-to-r from-[#318069] to-[#276854] text-white px-4 py-3 rounded-xl font-semibold text-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
            <i class="ri-download-line"></i>
            <span>Download</span>
        </button>
        <button id="successViewBtn" class="bg-white border border-gray-300 text-gray-700 px-4 py-3 rounded-xl font-medium text-sm hover:border-[#318069] hover:text-[#318069] hover:bg-gray-50 transition-all duration-200 flex items-center justify-center gap-2">
            <i class="ri-eye-line"></i>
            <span>View Details</span>
        </button>
        <button onclick="window.closeBookingSuccessModal()" class="bg-gray-100 border border-gray-200 text-gray-600 px-4 py-3 rounded-xl font-medium text-sm hover:bg-gray-200 hover:text-gray-800 transition-all duration-200 flex items-center justify-center gap-2">
            <i class="ri-close-line"></i>
            <span>Close</span>
        </button>
    </div>

    <!-- Footer Note -->
    <div class="text-center mt-5 pt-4 border-t border-gray-100">
        <p class="text-xs text-gray-400 flex items-center justify-center gap-1">
            <i class="ri-mail-send-line"></i> 
            A confirmation has been sent to your registered email
        </p>
    </div>
</div>
</div>




<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    {{-- jQuery CDN --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
    <script>
        const header = document.getElementById('mainHeader');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                header.classList.add('bg-white', 'shadow-md');
                header.classList.remove('bg-transparent');
            } else {
                header.classList.remove('bg-white', 'shadow-md');
                header.classList.add('bg-transparent');
            }
        });
    </script>

    {{-- <script>
    // Mobile Menu Toggle
    document.addEventListener('DOMContentLoaded', function() {
      const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
      const mobileMenu = document.querySelector('.mobile-menu');

      if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
          mobileMenu.classList.toggle('hidden');
          mobileMenuBtn.innerHTML = mobileMenu.classList.contains('hidden') ?
            '<i class="bi bi-list text-xl"></i>' :
            '<i class="bi bi-x-lg text-xl"></i>';
        });
      }

      // Back to Top Button
      const backToTop = document.getElementById('backToTop');

      window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
          backToTop.classList.add('visible');
        } else {
          backToTop.classList.remove('visible');
        }
      });

      backToTop.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });

      // Smooth scrolling for anchor links
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          e.preventDefault();
          const target = document.querySelector(this.getAttribute('href'));
          if (target) {
            target.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
          }
        });
      });
    });
  </script> --}}



    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        (function() {
            // Elements
            const grid = document.getElementById('doctorGrid');
            const count = document.getElementById('doctorCount');
            const citySelect = document.getElementById('citySelect');
            const manualCityContainer = document.getElementById('manualCityContainer');
            const cityInput = document.getElementById('cityInput');
            const latEl = document.getElementById('latInput');
            const lngEl = document.getElementById('lngInput');
            const radEl = document.getElementById('radiusSelect');
            const locationText = document.getElementById('locationText');
            const locationModal = document.getElementById('locationModal');
            const useCurrentLocationBtn = document.getElementById('useCurrentLocation');
            const applyLocationBtn = document.getElementById('applyLocation');
            const nearMeBtn = document.getElementById('near-me');
            const searchForm = document.getElementById('searchForm');
            const doctorSearchEl = document.getElementById('doctor-search');
            const specialtyEl = document.getElementById('specialty');
            const filterBadges = document.querySelectorAll('.filter-badge');

            // Bangladeshi cities with coordinates
            const cities = {
                'dhaka': {
                    name: 'Dhaka',
                    lat: 23.8103,
                    lng: 90.4125
                },
                'chattogram': {
                    name: 'Chattogram',
                    lat: 22.3569,
                    lng: 91.7832
                },
                'khulna': {
                    name: 'Khulna',
                    lat: 22.8456,
                    lng: 89.5403
                },
                'rajshahi': {
                    name: 'Rajshahi',
                    lat: 24.3745,
                    lng: 88.6042
                },
                'sylhet': {
                    name: 'Sylhet',
                    lat: 24.8949,
                    lng: 91.8687
                },
                'barishal': {
                    name: 'Barishal',
                    lat: 22.7010,
                    lng: 90.3535
                },
                'rangpur': {
                    name: 'Rangpur',
                    lat: 25.7439,
                    lng: 89.2752
                },
                'mymensingh': {
                    name: 'Mymensingh',
                    lat: 24.7471,
                    lng: 90.4203
                },
                'cumilla': {
                    name: 'Cumilla',
                    lat: 23.4680,
                    lng: 91.1782
                },
                'narayanganj': {
                    name: 'Narayanganj',
                    lat: 23.6238,
                    lng: 90.5000
                },
                'gazipur': {
                    name: 'Gazipur',
                    lat: 24.0022,
                    lng: 90.4264
                }

            };

            // Map variables
            let map = null;
            let marker = null;
            let currentLocation = {
                lat: 23.8103,
                lng: 90.4125,
                name: 'Dhaka'
            };

            // Debounce helper
            function debounce(func, delay = 400) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        func.apply(this, args);
                    }, delay);
                };
            }

            // Initialize map in modal
            function initMap(lat, lng) {
                if (!map) {
                    map = L.map('map').setView([lat, lng], 12);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(map);

                    marker = L.marker([lat, lng], {
                        draggable: true,
                        title: 'Drag to change location'
                    }).addTo(map);

                    marker.on('moveend', function() {
                        const {
                            lat,
                            lng
                        } = marker.getLatLng();
                        setLatLng(lat, lng);
                        reverseGeocode(lat, lng);
                    });

                    map.on('click', (e) => {
                        marker.setLatLng(e.latlng);
                        setLatLng(e.latlng.lat, e.latlng.lng);
                        reverseGeocode(e.latlng.lat, e.latlng.lng);
                    });
                } else {
                    map.setView([lat, lng], 12);
                    marker.setLatLng([lat, lng]);
                }
            }

            // Set latitude and longitude
            function setLatLng(lat, lng) {
                latEl.value = lat.toFixed(6);
                lngEl.value = lng.toFixed(6);
                currentLocation.lat = lat;
                currentLocation.lng = lng;
            }

            // Update location text
            function updateLocationText(cityName) {
                locationText.textContent = cityName || 'Set Location';
                currentLocation.name = cityName || 'Custom Location';
            }

            // Reverse geocode coordinates to city name
           async function reverseGeocode(lat, lng) {
    try {
        const response = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=10&addressdetails=1`
        );

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
        console.log("Geocode response:", data);

        if (data.address) {
            const city =
                data.address.city ||
                data.address.town ||
                data.address.village ||
                data.address.county ||
                data.address.state;

            if (!city) {
                console.warn("No city found in response");
                return;
            }

            updateLocationText(city);

            const cityKey = findCityKey(city);

            if (cityKey) {
                citySelect.value = cityKey;
                manualCityContainer.style.display = 'none';
            } else {
                citySelect.value = 'other';
                manualCityContainer.style.display = 'block';
                cityInput.value = city;
            }
        }

    } catch (error) {
        console.error('Reverse geocoding error:', error);
    }
}

            // Find city key from name
            function findCityKey(cityName) {
                if (!cityName) return null;
                const lowerCityName = cityName.toLowerCase();
                for (const [key, city] of Object.entries(cities)) {
                    if (city.name.toLowerCase().includes(lowerCityName) ||
                        lowerCityName.includes(city.name.toLowerCase())) {
                        return key;
                    }
                }
                return null;
            }

            // Get active filters
            function getActiveFilters() {
                const params = {};

                // Search term
                const searchTerm = doctorSearchEl.value.trim();
                if (searchTerm) params.search = searchTerm;

                // Specialty
                const specialty = specialtyEl.value;
                if (specialty) params.specialty = specialty;

                // Active filter badges
                filterBadges.forEach(badge => {
                    if (badge.classList.contains('active')) {
                        params[badge.dataset.filter] = true;
                    }
                });

                // Location
                if (latEl.value && lngEl.value) {
                    params.lat = latEl.value;
                    params.lng = lngEl.value;
                    params.radius = radEl.value || 25;
                }

                return params;
            }

            // Fetch doctors from server
            async function fetchDoctors(params = {}) {
                try {
                    const url = new URL('/doctors/nearby', window.location.origin);

                    // Add parameters
                    Object.entries(params).forEach(([k, v]) => {
                        if (v !== undefined && v !== null && String(v).trim() !== '') {
                            url.searchParams.set(k, v);
                        }
                    });

                    // Show loading state
                    grid.innerHTML = `
                    <div class="col-span-1 md:col-span-2 lg:col-span-3">
                        <div class="text-center py-12">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#318069] mx-auto mb-4"></div>
                            <p class="text-gray-600">Finding doctors near you...</p>
                        </div>
                    </div>`;
                    count.textContent = '';

                    const res = await fetch(url.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    if (!res.ok) throw new Error('Failed to fetch doctors');
                    const data = await res.json();

                    if (data.html && data.count > 0) {
                        grid.innerHTML = data.html;
                        count.textContent = `${data.count} doctor(s) found`;
                    } else {
                        grid.innerHTML = `
                        <div class="col-span-1 md:col-span-2 lg:col-span-3">
                            <div class="flex flex-col max-w-2xl mx-auto items-center justify-center py-16 px-6 bg-white rounded-2xl border border-gray-100 shadow-sm">

                                <!-- Icon Circle -->
                                <div class="flex items-center justify-center w-20 h-20 rounded-full bg-[#318069]/10 mb-6">
                                <i class="ri-user-search-line text-4xl text-[#318069]"></i>
                                </div>

                                <!-- Title -->
                                <h3 class="text-2xl font-semibold text-gray-800 mb-2">
                                No Doctors Found
                                </h3>

                                <!-- Description -->
                                <p class="text-gray-600 text-center max-w-md mb-6">
                                We couldn’t find any doctors matching your search.
                                Try changing your location, specialty, or increasing the search radius.
                                </p>

                                <!-- Action Buttons -->
                                <div class="flex flex-wrap gap-3">
                                <button
                                    onclick="document.getElementById('doctor-search')?.focus()"
                                    class="px-5 py-2.5 rounded-lg bg-[#318069] text-white text-sm font-medium hover:opacity-90 transition">
                                    Modify Search
                                </button>

                                <button
                                    onclick="document.getElementById('near-me')?.click()"
                                    class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                                    Use Near Me
                                </button>
                                </div>

                            </div>
                            </div>`;
                        count.textContent = '0 doctors found';
                    }
                } catch (error) {
                    console.error('Error fetching doctors:', error);
                    grid.innerHTML = `
                    <div class="col-span-1 md:col-span-2 lg:col-span-3">
                        <div class="text-center py-12">
                            <i class="ri-error-warning-line text-4xl text-yellow-500 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Unable to load doctors</h3>
                            <p class="text-gray-600">Please check your connection and try again.</p>
                        </div>
                    </div>`;
                    count.textContent = '';
                }
            }

            // Apply filters
            function applyFilters() {
                const params = getActiveFilters();
                fetchDoctors(params);

                // Save to localStorage
                localStorage.setItem('doctor_search_params', JSON.stringify(params));
            }

            // Toggle location modal
            function toggleLocationModal() {
                locationModal.classList.toggle('hidden');
                if (!locationModal.classList.contains('hidden')) {
                    initMap(currentLocation.lat, currentLocation.lng);
                }
            }

            // Use current location
            function useCurrentLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            setLatLng(lat, lng);
                            initMap(lat, lng);
                            reverseGeocode(lat, lng);
                            updateLocationText('Current Location');

                            // Apply filters after getting location
                            setTimeout(() => applyFilters(), 1000);
                        },
                        (error) => {
                            console.error('Geolocation error:', error);
                            alert(
                                'Unable to get your location. Please enable location services or select manually.'
                            );
                        }, {
                            enableHighAccuracy: true,
                            timeout: 20000,
                            maximumAge: 0
                        }
                    );
                } else {
                    alert('Geolocation is not supported by your browser');
                }
            }

            // Cancel button functionality for location modal
        document.getElementById('cancelLocationBtn')?.addEventListener('click', function() {
            const modal = document.getElementById('locationModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });

        // Also ensure the close button on mobile works
        document.querySelectorAll('#locationModal .close-modal-btn, #locationModal .ri-close-line')?.forEach(btn => {
            btn?.addEventListener('click', function() {
                const modal = document.getElementById('locationModal');
                if (modal) {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });
        });

            // Event Listeners
            searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                applyFilters();
            });

            doctorSearchEl.addEventListener('input', debounce(applyFilters, 400));
            specialtyEl.addEventListener('change', applyFilters);

            filterBadges.forEach(badge => {
                badge.addEventListener('click', () => {
                    badge.classList.toggle('active');
                    if (badge.classList.contains('active')) {
                        badge.style.backgroundColor = '#318069';
                        badge.style.color = 'white';
                        badge.style.borderColor = '#318069';
                    } else {
                        badge.style.backgroundColor = '';
                        badge.style.color = '';
                        badge.style.borderColor = '';
                    }
                    applyFilters();
                });
            });

            nearMeBtn.addEventListener('click', () => {
                useCurrentLocation();
            });

            // Location modal event listeners
            document.getElementById('locationSelector').addEventListener('click', toggleLocationModal);
            applyLocationBtn.addEventListener('click', () => {
                toggleLocationModal();
                applyFilters();
            });

            useCurrentLocationBtn.addEventListener('click', useCurrentLocation);

            citySelect.addEventListener('change', function() {
                if (this.value === 'other') {
                    manualCityContainer.style.display = 'block';
                    cityInput.focus();
                } else {
                    manualCityContainer.style.display = 'none';
                    cityInput.value = '';

                    if (this.value && cities[this.value]) {
                        const city = cities[this.value];
                        setLatLng(city.lat, city.lng);
                        initMap(city.lat, city.lng);
                        updateLocationText(city.name);
                    }
                }
            });

            cityInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (this.value.trim()) {
                        updateLocationText(this.value.trim());
                        // Here you could add geocoding for manual city input
                    }
                }
            });

            // Close modal when clicking outside
           document.addEventListener('click', (e) => {
            const locationSelector = document.getElementById('locationSelector') ||
                                    document.querySelector('.md\\:col-span-3 .flex.items-center');

            if (!locationModal.contains(e.target) &&
                !locationSelector.contains(e.target) &&
                !locationModal.classList.contains('hidden')) {
                locationModal.classList.add('hidden');
            }
        });

            // Initial load - try to get user location first
            window.addEventListener('DOMContentLoaded', () => {
                useCurrentLocation();
            });

        })();


        // ============================================
// SEARCHABLE COUNTRY & CITY DROPDOWN - FIXED
// ============================================

class LocationSelector {
    constructor() {
        this.countriesData = [];
        this.citiesData = [];
        this.countrySelectInstance = null;
        this.citySelectInstance = null;
        this.selectedCountry = null;
        this.apiBase = 'https://countriesnow.space/api/v0.1';
    }

    // Initialize the selector
    async init() {
        console.log('Initializing Location Selector...');
        await this.loadCountries();
        this.setupEventListeners();
    }

    // Load countries from API
    async loadCountries() {
        const countrySelect = document.getElementById('countrySelect');
        if (!countrySelect) return;

        countrySelect.innerHTML = '<option value="">Loading countries...</option>';
        
        try {
            const response = await fetch(`${this.apiBase}/countries`);
            const data = await response.json();
            
            if (data.error === false && data.data) {
                this.countriesData = data.data;
                this.initCountrySelect();
                console.log('Countries loaded:', this.countriesData.length);
            } else {
                throw new Error('API returned no data');
            }
        } catch (error) {
            console.error('Error loading countries:', error);
            this.loadFallbackCountries();
        }
    }

    // Initialize searchable country dropdown
    initCountrySelect() {
        const countrySelect = document.getElementById('countrySelect');
        if (!countrySelect) return;

        // Clear and populate options
        countrySelect.innerHTML = '<option value="">Select country...</option>';
        
        // Show only first 5 countries initially
        const initialCountries = this.countriesData.slice(0, 5);
        const remainingCountries = this.countriesData.slice(5);
        
        initialCountries.forEach(country => {
            const option = document.createElement('option');
            option.value = country.iso2 || country.country;
            option.textContent = country.country;
            countrySelect.appendChild(option);
        });
        
        // Add remaining countries as options (they will appear in search)
        remainingCountries.forEach(country => {
            const option = document.createElement('option');
            option.value = country.iso2 || country.country;
            option.textContent = country.country;
            countrySelect.appendChild(option);
        });

        // Destroy existing instance if any
        if (this.countrySelectInstance && this.countrySelectInstance.destroy) {
            this.countrySelectInstance.destroy();
        }

        // Initialize Tom Select for searchable dropdown
        this.countrySelectInstance = new TomSelect(countrySelect, {
            sortField: 'text',
            searchField: ['text'],
            maxOptions: 10,
            maxItems: 1,
            placeholder: 'Search for a country...',
            create: false,
            onChange: (value) => {
                console.log('Country changed:', value);
                if (value) {
                    const selectedOption = Array.from(countrySelect.options).find(opt => opt.value === value);
                    this.selectedCountry = selectedOption?.textContent;
                    if (this.selectedCountry) {
                        this.loadCities(this.selectedCountry);
                    }
                } else {
                    this.clearCitySelect();
                }
            }
        });
    }

    // Load cities for selected country
    async loadCities(countryName) {
        console.log('Loading cities for:', countryName);
        
        const citySelect = document.getElementById('citySelect');
        if (!citySelect) return;

        // Reset and disable city select while loading
        citySelect.innerHTML = '<option value="">Loading cities...</option>';
        citySelect.disabled = true;

        // Destroy existing Tom Select instance for city
        if (this.citySelectInstance && this.citySelectInstance.destroy) {
            this.citySelectInstance.destroy();
            this.citySelectInstance = null;
        }

        try {
            const response = await fetch(`${this.apiBase}/countries/cities`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ country: countryName })
            });
            
            const data = await response.json();
            console.log('Cities API response:', data);
            
            if (data.error === false && data.data && data.data.length > 0) {
                this.citiesData = data.data;
                this.initCitySelect();
                console.log('Cities loaded:', this.citiesData.length);
            } else {
                throw new Error('No cities found');
            }
        } catch (error) {
            console.error('Error loading cities:', error);
            this.loadFallbackCities(countryName);
        }
    }

    // Initialize searchable city dropdown
    initCitySelect() {
        const citySelect = document.getElementById('citySelect');
        if (!citySelect) return;

        // Clear and populate options
        citySelect.innerHTML = '<option value="">Select city...</option>';
        
        // Show only first 5 cities initially
        const initialCities = this.citiesData.slice(0, 5);
        const remainingCities = this.citiesData.slice(5);
        
        initialCities.forEach(city => {
            const option = document.createElement('option');
            option.value = city;
            option.textContent = city;
            citySelect.appendChild(option);
        });
        
        // Add remaining cities as options
        remainingCities.forEach(city => {
            const option = document.createElement('option');
            option.value = city;
            option.textContent = city;
            citySelect.appendChild(option);
        });

        // Enable the select first
        citySelect.disabled = false;

        // Destroy existing instance if any
        if (this.citySelectInstance && this.citySelectInstance.destroy) {
            this.citySelectInstance.destroy();
        }

        // Initialize Tom Select for searchable dropdown
        this.citySelectInstance = new TomSelect(citySelect, {
            sortField: 'text',
            searchField: ['text'],
            maxOptions: 10,
            maxItems: 1,
            placeholder: 'Search for a city...',
            create: true,
            createFilter: (input) => {
                return input.length > 2;
            },
            render: {
                option: (data, escape) => {
                    return `<div class="d-flex align-items-center gap-2 py-1">
                                
                                <span>${escape(data.text)}</span>
                            </div>`;
                },
                item: (data, escape) => {
                    return `<div class="d-flex align-items-center gap-2">
                                <span>${escape(data.text)}</span>
                            </div>`;
                },
                no_results: (data, escape) => {
                    return `<div class="p-3 text-center text-gray-500">
                                <i class="ri-search-line text-xl mb-1 block"></i>
                                <span>No city found. Press Enter to add "${escape(data.input)}"</span>
                            </div>`;
                }
            },
            onItemAdd: (value) => {
                console.log('City selected:', value);
                this.onCitySelected(value);
            }
        });
        
        console.log('City select initialized successfully');
    }

    // Handle city selection
    onCitySelected(cityName) {
        console.log('City selected callback:', cityName);
        updateLocationText(cityName);
        closeLocationModal();
        if (typeof applyFilters === 'function') {
            setTimeout(() => applyFilters(), 500);
        }
    }

    // Clear city select
    clearCitySelect() {
        const citySelect = document.getElementById('citySelect');
        if (!citySelect) return;
        
        // Destroy existing Tom Select instance
        if (this.citySelectInstance && this.citySelectInstance.destroy) {
            this.citySelectInstance.destroy();
            this.citySelectInstance = null;
        }
        
        citySelect.innerHTML = '<option value="">Select country first</option>';
        citySelect.disabled = true;
        this.citiesData = [];
    }

    // Fallback countries
    loadFallbackCountries() {
        const fallbackCountries = [
            'Bangladesh', 'India', 'Pakistan', 'Sri Lanka', 'Nepal',
            'United States', 'United Kingdom', 'Canada', 'Australia',
            'Germany', 'France', 'Japan', 'China', 'Brazil', 'Mexico'
        ];
        
        const countrySelect = document.getElementById('countrySelect');
        if (!countrySelect) return;
        
        countrySelect.innerHTML = '<option value="">Select country...</option>';
        
        const initialCountries = fallbackCountries.slice(0, 5);
        const remainingCountries = fallbackCountries.slice(5);
        
        initialCountries.forEach(country => {
            const option = document.createElement('option');
            option.value = country;
            option.textContent = country;
            countrySelect.appendChild(option);
        });
        
        remainingCountries.forEach(country => {
            const option = document.createElement('option');
            option.value = country;
            option.textContent = country;
            countrySelect.appendChild(option);
        });
        
        this.countriesData = fallbackCountries.map(c => ({ country: c, iso2: c }));
        
        if (this.countrySelectInstance && this.countrySelectInstance.destroy) {
            this.countrySelectInstance.destroy();
        }

        this.countrySelectInstance = new TomSelect(countrySelect, {
            sortField: 'text',
            searchField: ['text'],
            maxOptions: 10,
            placeholder: 'Search for a country...',
            create: false,
            onChange: (value) => {
                if (value) {
                    this.selectedCountry = value;
                    this.loadFallbackCities(value);
                }
            }
        });
    }

    // Fallback cities
    loadFallbackCities(countryName) {
        console.log('Loading fallback cities for:', countryName);
        
        const citiesDatabase = {
            'Bangladesh': ['Dhaka', 'Chattogram', 'Khulna', 'Rajshahi', 'Sylhet', 'Barishal', 'Rangpur', 'Mymensingh', 'Cumilla', 'Narayanganj', 'Gazipur'],
            'India': ['Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Kolkata', 'Hyderabad', 'Ahmedabad', 'Pune', 'Jaipur', 'Lucknow'],
            'Pakistan': ['Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan', 'Peshawar'],
            'United States': ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia', 'San Antonio', 'San Diego'],
            'United Kingdom': ['London', 'Manchester', 'Birmingham', 'Liverpool', 'Leeds', 'Sheffield', 'Bristol'],
            'Canada': ['Toronto', 'Vancouver', 'Montreal', 'Calgary', 'Edmonton', 'Ottawa'],
            'Australia': ['Sydney', 'Melbourne', 'Brisbane', 'Perth', 'Adelaide']
        };
        
        this.citiesData = citiesDatabase[countryName] || [`${countryName} Capital`, `${countryName} City 1`];
        this.initCitySelect();
    }

    // Setup event listeners
    setupEventListeners() {
        document.getElementById('useCurrentLocation')?.addEventListener('click', () => {
            this.useCurrentLocation();
        });
        
        document.getElementById('applyLocation')?.addEventListener('click', () => {
            closeLocationModal();
            if (typeof applyFilters === 'function') {
                applyFilters();
            }
        });
        
        document.getElementById('cityInput')?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const cityName = e.target.value.trim();
                if (cityName) {
                    this.onCitySelected(cityName);
                }
            }
        });
    }

    // Get current location
    async useCurrentLocation() {
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser');
            return;
        }
        
        navigator.geolocation.getCurrentPosition(
            async (position) => {
                const { latitude, longitude } = position.coords;
                
                try {
                    const response = await fetch(
                        `https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json&addressdetails=1`
                    );
                    const data = await response.json();
                    
                    const city = data.address?.city || data.address?.town || data.address?.village;
                    const country = data.address?.country;
                    
                    if (city) {
                        updateLocationText(city);
                    }
                    
                    if (country && this.countrySelectInstance) {
                        const options = this.countrySelectInstance.options;
                        for (let i = 0; i < options.length; i++) {
                            if (options[i].text === country) {
                                this.countrySelectInstance.setValue(options[i].value);
                                break;
                            }
                        }
                    }
                    
                    closeLocationModal();
                    if (typeof setLatLng === 'function') setLatLng(latitude, longitude);
                    if (typeof initMap === 'function') initMap(latitude, longitude);
                    if (typeof applyFilters === 'function') applyFilters();
                    
                } catch (error) {
                    console.error('Reverse geocoding error:', error);
                }
            },
            (error) => {
                console.error('Geolocation error:', error);
                alert('Unable to get your location. Please select manually.');
            }
        );
    }
}

// ============================================
// INITIALIZE
// ============================================

let locationSelector = null;

document.addEventListener('DOMContentLoaded', async function() {
    locationSelector = new LocationSelector();
    await locationSelector.init();
});

// Helper functions
function closeLocationModal() {
    const modal = document.getElementById('locationModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function updateLocationText(text) {
    const locationText = document.getElementById('locationText');
    if (locationText) {
        locationText.textContent = text;
    }
}

// Cancel button
document.getElementById('cancelLocationBtn')?.addEventListener('click', function() {
    closeLocationModal();
});
    </script>
    <script>
        let bookingPaymentMethods = [];
        let latestBookingSuccess = null;

        // Global booking modal functions
        window.openBookingModal = async function(doctorId, doctorName) {
            console.log('Opening modal for doctor:', doctorId, doctorName);

            const modal = document.getElementById('bookingModal');
            if (!modal) {
                console.error('Modal not found!');
                return;
            }

            // Set doctor info
            document.getElementById('doctor_id').value = doctorId;
            document.getElementById('modalDoctorName').textContent = 'Book with Dr. ' + doctorName;

            // Reset form to initial state
            resetBookingForm();
            await loadPaymentMethods(doctorId);

            // Show modal
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            document.body.style.paddingRight = window.innerWidth - document.documentElement.clientWidth +
            'px'; // Prevent scrollbar jump
        };

        window.closeBookingModal = function() {
            const modal = document.getElementById('bookingModal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
                document.body.style.paddingRight = '';
            }
        };

        window.closeBookingSuccessModal = function() {
            const modal = document.getElementById('bookingSuccessModal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
                document.body.style.paddingRight = '';
            }
        };

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('bookingModal');
            if (!modal) {
                console.error('Modal not found on page load');
                return;
            }
            const successModal = document.getElementById('bookingSuccessModal');

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    window.closeBookingModal();
                }
            });

            // Close modal when clicking X
            const closeBtn = modal.querySelector('.close-modal');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    window.closeBookingModal();
                });
            }

            if (successModal) {
                successModal.addEventListener('click', function(e) {
                    if (e.target === successModal) {
                        window.closeBookingSuccessModal();
                    }
                });
            }

            // Handle consultation type change
            const consultationType = document.getElementById('consultationType');
            if (consultationType) {
                consultationType.addEventListener('change', handleConsultationChange);
            }

            // Handle chamber selection change
            const chamberSelect = document.getElementById('chamberSelect');
            if (chamberSelect) {
                chamberSelect.addEventListener('change', handleChamberChange);
            }

            // Handle date change
            const datePicker = document.getElementById('datePicker');
            if (datePicker) {
                // Set minimum date to today
                const today = new Date().toISOString().split('T')[0];
                datePicker.min = today;

                datePicker.addEventListener('change', handleDateChange);
            }

            // Handle time selection
            const timeSelect = document.getElementById('timeSelect');
            if (timeSelect) {
                timeSelect.addEventListener('change', function() {
                    const timeInput = document.getElementById('appointment_time');
                    if (timeInput) timeInput.value = this.value;
                });
            }

            // Handle form submission
            const bookingForm = document.getElementById('bookingForm');
            if (bookingForm) {
                bookingForm.addEventListener('submit', handleFormSubmit);
            }

            // Initialize multi-step form if exists
            initializeMultiStepForm();
        });

        // Reset booking form to initial state
        function resetBookingForm() {
            document.getElementById('consultationType').value = '';
            document.getElementById('chamberSelect').innerHTML = '<option value="">Select chamber</option>';
            document.getElementById('chamberSelect').disabled = true;
            document.getElementById('datePicker').value = '';
            document.getElementById('timeSelect').innerHTML = '<option value="">Select time</option>';
            document.getElementById('appointment_date').value = '';
            document.getElementById('appointment_time').value = '';

            // Reset patient info if in multi-step form
            const patientFirstName = document.querySelector('input[name="patient_first_name"]');
            const patientLastName = document.querySelector('input[name="patient_last_name"]');
            const patientEmail = document.querySelector('input[name="patient_email"]');
            const patientPhone = document.querySelector('input[name="patient_phone"]');

            if (patientFirstName) patientFirstName.value = '';
            if (patientLastName) patientLastName.value = '';
            if (patientEmail) patientEmail.value = '';
            if (patientPhone) patientPhone.value = '';

            updatePaymentMethodOptions([]);

            // Reset steps if multi-step form
            const steps = document.querySelectorAll('.form-step');
            if (steps.length > 1) {
                steps.forEach((step, index) => {
                    step.style.display = index === 0 ? 'block' : 'none';
                });

                // Reset progress
                document.querySelectorAll('.step').forEach((step, index) => {
                    if (index === 0) {
                        step.classList.add('active');
                    } else {
                        step.classList.remove('active');
                    }
                });
            }
        }

        async function loadPaymentMethods(doctorId) {
            try {
                const response = await fetch(`/doctors/${doctorId}/payment-methods`);
                if (!response.ok) throw new Error('Failed to load payment methods');

                const data = await response.json();
                bookingPaymentMethods = Array.isArray(data.methods) ? data.methods : [];
                updatePaymentMethodOptions(bookingPaymentMethods);
            } catch (error) {
                console.error('Error loading payment methods:', error);
                bookingPaymentMethods = [{
                    value: 'cod',
                    label: 'Cash on Visit',
                    description: 'Pay at chamber',
                    type: 'offline'
                }];
                updatePaymentMethodOptions(bookingPaymentMethods);
            }
        }

        function updatePaymentMethodOptions(methods) {
            const sslLabel = document.getElementById('paymentMethodSslCommerce');
            const codLabel = document.getElementById('paymentMethodCod');
            const notice = document.getElementById('paymentGatewayNotice');
            const submitButton = document.getElementById('bookingSubmitButton');

            const sslInput = sslLabel ? sslLabel.querySelector('input[name="payment_method"]') : null;
            const codInput = codLabel ? codLabel.querySelector('input[name="payment_method"]') : null;

            const hasSsl = methods.some(method => method.value === 'ssl_commerce');
            const hasCod = methods.some(method => method.value === 'cod');

            if (sslLabel && sslInput) {
                sslLabel.style.display = hasSsl ? '' : 'none';
                sslInput.disabled = !hasSsl;
                sslInput.checked = false;
            }

            if (codLabel && codInput) {
                codLabel.style.display = hasCod ? '' : 'none';
                codInput.disabled = !hasCod;
                codInput.checked = false;
            }

            const defaultMethod = methods[0]?.value || 'cod';
            if (defaultMethod === 'ssl_commerce' && sslInput && !sslInput.disabled) {
                sslInput.checked = true;
            } else if (codInput && !codInput.disabled) {
                codInput.checked = true;
            }

            if (notice) {
                if (!methods.length) {
                    notice.textContent = 'Loading available payment gateways...';
                } else if (hasSsl) {
                    notice.textContent = 'Payment methods are loaded from this doctor\'s active tenant gateway settings.';
                } else {
                    notice.textContent = 'Online payment is not active for this doctor. You can still book and pay during the visit.';
                }
            }

            if (submitButton) {
                submitButton.textContent = hasSsl ? 'Confirm & Pay Now' : 'Confirm Booking';
            }
        }

        // Handle consultation type change
        async function handleConsultationChange() {
            const chamberSelect = document.getElementById('chamberSelect');
            if (!chamberSelect) return;

            if (this.value === 'offline') {
                const doctorId = document.getElementById('doctor_id').value;
                if (!doctorId) {
                    alert('Please select a doctor first');
                    this.value = '';
                    return;
                }

                // Load chambers
                try {
                    chamberSelect.disabled = false;
                    chamberSelect.innerHTML = '<option value="">Loading chambers...</option>';

                    const response = await fetch(`/doctors/${doctorId}/chambers`);
                    if (!response.ok) throw new Error('Failed to load chambers');

                    const chambers = await response.json();

                    chamberSelect.innerHTML = '<option value="">Select chamber</option>';
                    chambers.forEach(chamber => {
                        const option = document.createElement('option');
                        option.value = chamber.id;
                        option.textContent = `${chamber.name} - ৳${parseFloat(chamber.fees).toFixed(2)}`;
                        option.dataset.fees = chamber.fees || '0';
                        chamberSelect.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error loading chambers:', error);
                    chamberSelect.innerHTML = '<option value="">Error loading chambers</option>';
                    setTimeout(() => {
                        chamberSelect.innerHTML = '<option value="">Select chamber</option>';
                    }, 2000);
                }
            } else {
                chamberSelect.disabled = true;
                chamberSelect.innerHTML = '<option value="">Select chamber</option>';
                document.getElementById('datePicker').value = '';
                document.getElementById('timeSelect').innerHTML = '<option value="">Select time</option>';
            }
        }

        // Handle chamber selection change
        function handleChamberChange() {
            const selectedOption = this.options[this.selectedIndex];
            const fees = selectedOption.dataset.fees || '0.00';

            // Update fee display if exists
            const feeDisplay = document.getElementById('chamberFees');
            if (feeDisplay) {
                feeDisplay.textContent = `Fee: ৳${fees}`;
                feeDisplay.classList.remove('hidden');
            }

            // Clear date and time when chamber changes
            document.getElementById('datePicker').value = '';
            document.getElementById('timeSelect').innerHTML = '<option value="">Select time</option>';
        }

        // Handle date change
        async function handleDateChange() {
            const date = this.value;
            const dateInput = document.getElementById('appointment_date');
            if (dateInput) dateInput.value = date;

            const doctorId = document.getElementById('doctor_id').value;
            const consultationType = document.getElementById('consultationType').value;
            const chamberId = document.getElementById('chamberSelect').value;

            if (!doctorId) {
                alert('Please select a doctor first');
                this.value = '';
                return;
            }

            if (!date) return;

            if (consultationType === 'offline' && !chamberId) {
                alert('Please select a chamber first');
                this.value = '';
                return;
            }

            try {
                const timeSelect = document.getElementById('timeSelect');
                if (timeSelect) {
                    timeSelect.disabled = true;
                    timeSelect.innerHTML = '<option value="">Loading time slots...</option>';
                }

                let url = '';
                if (consultationType === 'online') {
                    url = `/doctors/${doctorId}/online-slots/${encodeURIComponent(date)}`;
                } else {
                    url = `/chambers/${doctorId}/${chamberId}/slots/${encodeURIComponent(date)}`;
                }

                const response = await fetch(url);
                if (!response.ok) throw new Error('Failed to load time slots');

                const data = await response.json();

                if (timeSelect) {
                    timeSelect.innerHTML = '<option value="">Select time</option>';
                    timeSelect.disabled = false;

                    if (data.slots && data.slots.length > 0) {
                        // Show all slots (no available check)
                        data.slots.forEach(slot => {
                            const option = document.createElement('option');
                            option.value = slot.start;
                            // Use display field or format time
                            if (slot.display) {
                                option.textContent = slot.display;
                            } else {
                                const time = new Date('1970-01-01T' + slot.start + 'Z');
                                option.textContent = time.toLocaleTimeString('en-US', {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: true
                                });
                            }
                            timeSelect.appendChild(option);
                        });

                        // Auto-select first slot if only one available
                        if (data.slots.length === 1) {
                            timeSelect.value = data.slots[0].start;
                            const timeInput = document.getElementById('appointment_time');
                            if (timeInput) timeInput.value = data.slots[0].start;
                        }
                    } else {
                        timeSelect.innerHTML = '<option value="">No slots available</option>';
                    }
                }
            } catch (error) {
                console.error('Error loading time slots:', error);
                const timeSelect = document.getElementById('timeSelect');
                if (timeSelect) {
                    timeSelect.innerHTML = '<option value="">Error loading slots</option>';
                    setTimeout(() => {
                        timeSelect.innerHTML = '<option value="">Select time</option>';
                        timeSelect.disabled = false;
                    }, 2000);
                }
                alert('Failed to load time slots. Please try again.');
            }
        }

        // Handle form submission
        async function handleFormSubmit(e) {
            e.preventDefault();

            // Validate form
            if (!validateBookingForm()) {
                return false;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Processing...';
            submitBtn.disabled = true;

            try {
                // Collect form data
                const formData = new FormData(this);

                // Add terms_agreed if checkbox exists
                const termsCheckbox = this.querySelector('input[name="terms_agreed"]');
                if (termsCheckbox && termsCheckbox.checked) {
                    formData.append('terms_agreed', '1');
                }

                // Send to server
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // If redirect URL is provided (for SSL), redirect
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        window.closeBookingModal();
                        showBookingSuccessModal(data);
                    }
                } else {
                    showErrorMessage(data.message || 'Failed to book appointment');
                }
            } catch (error) {
                console.error('Booking error:', error);
                showErrorMessage('An error occurred. Please try again.');
            } finally {
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }

        // Validate booking form
        function validateBookingForm() {
            const consultationType = document.getElementById('consultationType').value;
            const chamberSelect = document.getElementById('chamberSelect');
            const date = document.getElementById('datePicker').value;
            const time = document.getElementById('timeSelect').value;

            if (!consultationType) {
                alert('Please select consultation type');
                return false;
            }

            if (consultationType === 'offline') {
                if (!chamberSelect.value) {
                    alert('Please select a chamber');
                    return false;
                }
            }

            if (!date) {
                alert('Please select a date');
                return false;
            }

            if (!time) {
                alert('Please select a time slot');
                return false;
            }

            return true;
        }

        // Initialize multi-step form functionality
        function initializeMultiStepForm() {
            const nextButtons = document.querySelectorAll('.next-step');
            const prevButtons = document.querySelectorAll('.prev-step');

            if (nextButtons.length === 0) return; // Not a multi-step form

            // Next button click
            nextButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const currentStep = this.closest('.form-step');
                    const nextStepNum = this.dataset.next;
                    const nextStep = document.querySelector(`.form-step[data-step="${nextStepNum}"]`);

                    if (!validateCurrentStep(currentStep.dataset.step)) return;

                    currentStep.style.display = 'none';
                    nextStep.style.display = 'block';

                    // Update progress
                    document.querySelectorAll('.step').forEach(step => {
                        step.classList.remove('active');
                        if (step.dataset.step === nextStepNum) {
                            step.classList.add('active');
                        }
                    });
                });
            });

            // Previous button click
            prevButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const currentStep = this.closest('.form-step');
                    const prevStepNum = this.dataset.prev;
                    const prevStep = document.querySelector(`.form-step[data-step="${prevStepNum}"]`);

                    currentStep.style.display = 'none';
                    prevStep.style.display = 'block';

                    // Update progress
                    document.querySelectorAll('.step').forEach(step => {
                        step.classList.remove('active');
                        if (step.dataset.step === prevStepNum) {
                            step.classList.add('active');
                        }
                    });
                });
            });
        }

        // Validate current step in multi-step form
        function validateCurrentStep(stepNum) {
            switch (stepNum) {
                case '1': // Appointment details
                    return validateBookingForm();
                case '2': // Patient info
                    const firstName = document.querySelector('input[name="patient_first_name"]').value;
                    const lastName = document.querySelector('input[name="patient_last_name"]').value;
                    const email = document.querySelector('input[name="patient_email"]').value;
                    const phone = document.querySelector('input[name="patient_phone"]').value;

                    if (!firstName || !lastName || !email || !phone) {
                        alert('Please fill all required patient information');
                        return false;
                    }

                    // Validate email
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)) {
                        alert('Please enter a valid email address');
                        return false;
                    }

                    return true;
                default:
                    return true;
            }
        }

        // Show success message
        function showSuccessMessage(message) {
            // You can use a toast notification library or custom alert
            alert(message); // Replace with better notification UI
        }

        // Show error message
        function showErrorMessage(message) {
            alert(message); // Replace with better notification UI
        }

        function showBookingSuccessModal(data) {
            latestBookingSuccess = data || {};

            const modal = document.getElementById('bookingSuccessModal');
            if (!modal) {
                showSuccessMessage(data.message || 'Appointment booked successfully!');
                return;
            }

            document.getElementById('successModalMessage').textContent = data.message || 'Your appointment request has been received.';
            document.getElementById('successAppointmentReference').textContent = data.appointment_reference || ('APT' + String(data.appointment_id || '').padStart(6, '0'));
            document.getElementById('successPatientName').textContent = data.patient_name || '-';
            document.getElementById('successAppointmentDate').textContent = formatSuccessDate(data.appointment_date);
            document.getElementById('successAppointmentTime').textContent = formatSuccessTime(data.appointment_time);
            document.getElementById('successPaymentMethod').textContent = formatPaymentMethod(data.payment_method);
            document.getElementById('successAmount').textContent = formatCurrency(data.amount);

            const viewBtn = document.getElementById('successViewBtn');
            const downloadBtn = document.getElementById('successDownloadBtn');

            if (viewBtn) {
                viewBtn.onclick = function() {
                    if (latestBookingSuccess && latestBookingSuccess.confirmation_url) {
                        window.open(latestBookingSuccess.confirmation_url, '_blank');
                    }
                };
            }

            if (downloadBtn) {
                downloadBtn.onclick = function() {
                    if (latestBookingSuccess && latestBookingSuccess.confirmation_url) {
                        const popup = window.open(latestBookingSuccess.confirmation_url, '_blank');
                        if (popup) {
                            setTimeout(function() {
                                popup.print();
                            }, 800);
                        }
                    }
                };
            }

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            document.body.style.paddingRight = window.innerWidth - document.documentElement.clientWidth + 'px';
        }

        function formatSuccessDate(dateValue) {
            if (!dateValue) return '-';
            const parsed = new Date(dateValue);
            return isNaN(parsed.getTime())
                ? dateValue
                : parsed.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
        }

        function formatSuccessTime(timeValue) {
            if (!timeValue) return '-';
            const parsed = new Date('1970-01-01T' + timeValue);
            return isNaN(parsed.getTime())
                ? timeValue
                : parsed.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
        }

        function formatPaymentMethod(value) {
            if (!value) return '-';
            const map = {
                ssl_commerce: 'SSL Commerz',
                cod: 'Cash on Visit',
                free: 'Free'
            };
            return map[value] || value.replace(/_/g, ' ');
        }

        function formatCurrency(amount) {
            const numeric = Number(amount || 0);
            return 'BDT ' + numeric.toFixed(2);
        }

        // Test function for debugging
        function testModal() {
            console.log('Testing modal...');
            const modal = document.getElementById('bookingModal');
            if (modal) {
                console.log('Modal found, displaying...');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            } else {
                console.error('Modal not found!');
            }
        }

        // Keyboard support - close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.closeBookingModal();
                window.closeBookingSuccessModal();
            }
        });
    </script>

<script>
    // Simple Back to Top functionality
    const backToTopBtn = document.getElementById('backToTopBtn');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTopBtn.style.opacity = '1';
            backToTopBtn.style.visibility = 'visible';
        } else {
            backToTopBtn.style.opacity = '0';
            backToTopBtn.style.visibility = 'hidden';
        }
    });

    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>

    @stack('scripts')
</body>

</html>
