<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.seo')
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('doctor/css/one.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .pdf-wrapper {
            font-family: "Segoe UI", Arial, sans-serif;
            color: #333;
            padding: 10px;
        }

        .pdf-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        
    </style>
</head>

<body class="bg-white"> 
    <div class="min-h-screen">
        <header id="mainHeader" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-white border-b">
            <div class="container mx-auto px-6 lg:px-12">
                <div class="flex items-center justify-between h-20">
                    <a href="/">
                        <button class="flex items-center gap-3 cursor-pointer">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-teal-500 rounded-lg flex items-center justify-center">
                                <i class="ri-stethoscope-line text-2xl text-white"></i>
                            </div>
                           <div>
                                <h1 class="text-lg sm:text-xl md:text-2xl line-clamp-1 sm:line-clamp-none font-bold text-gray-900 custom-font">
                                    {{ $doctor->name }}
                                </h1>
                                <p class="text-xs sm:text-sm text-gray-600 text-start">
                                    Medical Practice
                                </p>
                            </div>
                        </button>
                    </a>
                    <nav class="hidden lg:flex items-center gap-8">
                        <a href="/"
                            class="text-gray-700 hover:text-cyan-600 font-medium transition-colors cursor-pointer">Home</a>
                        <a href="/#about"
                            class="text-gray-700 hover:text-cyan-600 font-medium transition-colors cursor-pointer">About</a>
                        <a href="/services"
                            class="text-gray-700 hover:text-cyan-600 font-medium transition-colors cursor-pointer">Services</a>
                        <a href="/articles"
                            class="text-gray-700 hover:text-cyan-600 font-medium transition-colors cursor-pointer">Articles</a>
                        {{-- login logout --}}
                        @auth
                            <a href="{{ url('admin/dashboard') }}"
                                class="text-gray-700 hover:text-cyan-600 font-medium transition-colors cursor-pointer">Dashboard</a>
                            <a href="tel:5551234567"
                                class="flex items-center gap-2 px-6 py-3 bg-cyan-600 text-white rounded-lg font-semibold hover:bg-cyan-700 transition-all whitespace-nowrap cursor-pointer">
                                <i class="ri-phone-line"></i>{{ $doctor->mobile }}
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="flex items-center gap-2 px-6 py-3 bg-cyan-600 text-white rounded-lg font-semibold hover:bg-cyan-700 transition-all whitespace-nowrap cursor-pointer">
                                <i class="ri-login-box-line"></i>Login
                            </a>
                            @endif
                        </nav>
                        <button class="lg:hidden w-10 h-10 flex items-center justify-center text-gray-700 cursor-pointer">
                            <i class="text-2xl ri-menu-line"></i>
                        </button>
                    </div>
                </div>
            </header>

            <main class="pt-20">
                @yield('content')

            </main>


            {{-- <!-- Floating Chat Button -->
    <div id="vapi-widget-floating-button" class="vapi-floating-button">
        <div class="vapi-button-content">
            <i class="ri-chat-3-fill text-xl"></i>
            <span>Talk with Us</span>
        </div>
    </div>

    <!-- Chat Box -->
    <div id="vapi-chatbox" class="vapi-chatbox hidden">
        <div class="vapi-chat-header">
            <span class="vapi-title">Talk with Us</span>
            <button id="closeChat" class="vapi-close-btn">&times;</button>
        </div>
        <div class="vapi-chat-body">
            <p>Use text to communicate.</p>
        </div>
        <div class="vapi-chat-footer">
            <input type="text" placeholder="Type your message..." class="vapi-input" />
            <button class="vapi-send-btn">➤</button>
        </div>
    </div> --}}


            <footer class="bg-gray-900 text-gray-300">
                <div class="container mx-auto px-6 lg:px-12 py-16">
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                        <div>
                            <h3 class="text-2xl font-bold text-white mb-4 custom-font">
                                {{ $doctor->name }}</h3>
                            <p class="text-gray-400 mb-6">Providing exceptional medical care with compassion and expertise
                                for over 15 years.</p>
                            <div class="mb-6">
                                <h4 class="text-white font-semibold mb-3">Follow Us</h4>
                                <div class="flex gap-3 flex-wrap">
                                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer"
                                        aria-label="Facebook"
                                        class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-cyan-600 transition-colors cursor-pointer">
                                        <i class="ri-facebook-fill"></i>
                                    </a>
                                    <a href="https://twitter.com" target="_blank" rel="noopener noreferrer"
                                        aria-label="Twitter"
                                        class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-cyan-600 transition-colors cursor-pointer">
                                        <i class="ri-twitter-x-fill"></i>
                                    </a>
                                    <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer"
                                        aria-label="LinkedIn"
                                        class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-cyan-600 transition-colors cursor-pointer">
                                        <i class="ri-linkedin-fill"></i>
                                    </a>
                                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer"
                                        aria-label="Instagram"
                                        class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-cyan-600 transition-colors cursor-pointer">
                                        <i class="ri-instagram-fill"></i>
                                    </a>
                                    <a href="https://youtube.com" target="_blank" rel="noopener noreferrer"
                                        aria-label="YouTube"
                                        class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-cyan-600 transition-colors cursor-pointer">
                                        <i class="ri-youtube-fill"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold text-lg mb-4">Quick Links</h4>
                            <ul class="space-y-3">
                                <li><button class="hover:text-cyan-400 transition-colors cursor-pointer">
                                        {{ $doctor->name }}</button></li>
                                <li><button class="hover:text-cyan-400 transition-colors cursor-pointer">Medical </button>
                                </li>
                                <li><button class="hover:text-cyan-400 transition-colors cursor-pointer">Our
                                        Services</button></li>
                                <li><button
                                        class="hover:text-cyan-400 transition-colors cursor-pointer">Appointments</button>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold text-lg mb-4">Services</h4>
                            <ul class="space-y-3">
                                <li><a href="#" class="hover:text-cyan-400 transition-colors cursor-pointer">General
                                        Checkups</a></li>
                                <li><a href="#" class="hover:text-cyan-400 transition-colors cursor-pointer">Chronic
                                        Disease
                                        Management</a></li>
                                <li><a href="#"
                                        class="hover:text-cyan-400 transition-colors cursor-pointer">Cardiovascular
                                        Care</a></li>
                                <li><a href="#"
                                        class="hover:text-cyan-400 transition-colors cursor-pointer">Preventive
                                        Medicine</a></li>
                                <li><a href="#" class="hover:text-cyan-400 transition-colors cursor-pointer">Women's
                                        Health</a></li>
                                <li><a href="#"
                                        class="hover:text-cyan-400 transition-colors cursor-pointer">Telemedicine
                                        Services</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold text-lg mb-4">Contact Info</h4>
                            <ul class="space-y-4">
                                <li class="flex items-start gap-3">
                                    <i class="ri-map-pin-fill text-cyan-400 mt-1"></i>
                                    <span>{{ $doctor->address }}</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <i class="ri-phone-fill text-cyan-400"></i>
                                    <a href="tel:5551234567"
                                        class="hover:text-cyan-400 transition-colors cursor-pointer">{{ $doctor->mobile }}</a>
                                </li>
                                <li class="flex items-center gap-3">
                                    <i class="ri-mail-fill text-cyan-400"></i>
                                    <a href="mailto:info@drmitchell.com"
                                        class="hover:text-cyan-400 transition-colors cursor-pointer">{{ $doctor->email }}</a>
                                </li>
                                <li class="flex items-center gap-3">
                                    <i class="ri-time-line text-cyan-400"></i>
                                    <span>Mon-Fri: 8AM-6PM<br>Sat: 9AM-2PM</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="border-t border-gray-800 pt-8">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                            <p class="text-gray-400 text-sm">© {{ date('y') }} {{ $doctor->name }} Medical Practice.
                                All rights
                                reserved.</p>
                            <div class="flex gap-6 text-sm">
                                <a href="#" class="hover:text-cyan-400 transition-colors cursor-pointer">Privacy
                                    Policy</a>
                                <a href="#" class="hover:text-cyan-400 transition-colors cursor-pointer">Terms of
                                    Service</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

    <script>

    const header = document.getElementById('mainHeader');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
            header.classList.add('shadow-md');
        } else {
            header.classList.remove('shadow-md');
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
       

        // Mobile menu toggle
        const mobileMenuButton = document.querySelector('.lg\\:hidden');
        const nav = document.querySelector('nav');

        if (mobileMenuButton && nav) {
            mobileMenuButton.addEventListener('click', function () {
                nav.classList.toggle('hidden');
                nav.classList.toggle('flex');
                nav.classList.toggle('flex-col');
                nav.classList.toggle('absolute');
                nav.classList.toggle('top-20');
                nav.classList.toggle('left-0');
                nav.classList.toggle('right-0');
                nav.classList.toggle('bg-white');
                nav.classList.toggle('p-6');
                nav.classList.toggle('shadow-lg');
            });
        }
    });


   

</script> 

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                class AppointmentBooking {
                    constructor() {
                        this.currentStep = 1;
                        this.totalSteps = 2;
                        this.formData = new FormData();
                        this.selectedChamber = null;
                        this.selectedDate = null;
                        this.selectedTime = null;
                        this.currentMonth = new Date().getMonth();
                        this.currentYear = new Date().getFullYear();
                        this.isOnlineConsultation = false;

                        this.initializeElements();
                        this.initializeEventListeners();
                        this.setupInitialState();
                        this.generateCalendar();
                    }

                    initializeElements() {
                        // Modal elements
                        this.modalOverlay = document.querySelector('[data-overlay]');
                        this.openBtn = document.querySelector('[data-open]');
                        this.closeBtn = document.querySelector('[data-close]');
                        this.form = document.querySelector('[data-form]');

                        // Step elements
                        this.steps = document.querySelectorAll('[data-step-indicator]');
                        this.panes = document.querySelectorAll('[data-pane]');
                        this.prevBtn = document.querySelector('[data-prev]');
                        this.nextBtn = document.querySelector('[data-next]');
                        this.submitBtn = document.querySelector('[data-submit]');

                        // Step 1 elements
                        this.consultationSelect = document.querySelector('[data-consultation-select]');
                        this.chamberSelect = document.querySelector('[data-chamber-select]');
                        this.serviceSelect = document.querySelector('[data-service-select]');
                        this.chamberCard = document.querySelector('[data-chamber-card]');
                        this.scheduleSection = document.querySelector('[data-schedule-section]');

                        // Calendar elements
                        this.calendarGrid = document.querySelector('.calendar-grid');
                        this.monthDisplay = document.querySelector('[data-month]');
                        this.yearDisplay = document.querySelector('[data-year]');
                        this.calPrev = document.querySelector('[data-cal-prev]');
                        this.calNext = document.querySelector('[data-cal-next]');

                        // Time slots elements
                        this.timeSlotsContainer = document.querySelector('[data-time-slots]');
                        this.slotsPlaceholder = document.querySelector('[data-slots-placeholder]');
                        this.selectedDateDisplay = document.querySelector('[data-selected-date] span');

                        // Step 2 elements
                        this.summaryPreview = document.querySelector('[data-summary-preview]');
                        this.editSummaryBtn = document.querySelector('[data-edit-summary]');

                        // Summary elements
                        this.sumConsultation = document.querySelector('[data-sum-consultation]');
                        this.sumChamber = document.querySelector('[data-sum-chamber]');
                        this.sumService = document.querySelector('[data-sum-service]');
                        this.sumDateTime = document.querySelector('[data-sum-dt]');
                        this.sumFees = document.querySelector('[data-sum-fees]');
                    }

                    initializeEventListeners() {
                        // Modal controls
                        if (this.openBtn) {
                            this.openBtn.addEventListener('click', () => this.openModal());
                        }

                        if (this.closeBtn) {
                            this.closeBtn.addEventListener('click', () => this.closeModal());
                        }

                        if (this.modalOverlay) {
                            this.modalOverlay.addEventListener('click', (e) => {
                                if (e.target === this.modalOverlay) this.closeModal();
                            });
                        }

                        // Navigation
                        if (this.prevBtn) {
                            this.prevBtn.addEventListener('click', () => this.previousStep());
                        }

                        if (this.nextBtn) {
                            this.nextBtn.addEventListener('click', () => this.nextStep());
                        }

                        if (this.submitBtn) {
                            this.submitBtn.addEventListener('click', (e) => this.handleFormSubmit(e));
                        }

                        // Consultation type change
                        if (this.consultationSelect) {
                            this.consultationSelect.addEventListener('change', (e) => this.onConsultationChange(e
                                .target.value));
                        }

                        // Chamber change
                        if (this.chamberSelect) {
                            this.chamberSelect.addEventListener('change', (e) => this.onChamberChange(e.target
                                .value));
                        }

                        // Service change
                        if (this.serviceSelect) {
                            this.serviceSelect.addEventListener('change', () => this.updateSummaryPreview());
                        }

                        // Calendar navigation
                        if (this.calPrev) {
                            this.calPrev.addEventListener('click', () => this.changeMonth(-1));
                        }

                        if (this.calNext) {
                            this.calNext.addEventListener('click', () => this.changeMonth(1));
                        }

                        // Edit summary
                        if (this.editSummaryBtn) {
                            this.editSummaryBtn.addEventListener('click', () => this.editSummary());
                        }

                        // Form submission
                        if (this.form) {
                            this.form.addEventListener('submit', (e) => this.handleFormSubmit(e));
                        }
                    }

                    setupInitialState() {
                        // Disable online option if not available
                        if (!{{ $doctor->accepts_virtual_visits ? 'true' : 'false' }}) {
                            const onlineOption = this.consultationSelect.querySelector('option[value="online"]');
                            if (onlineOption) {
                                onlineOption.disabled = true;
                                onlineOption.textContent += ' (Not Available)';
                            }
                        }
                    }

                    openModal() {
                        if (this.modalOverlay) {
                            this.modalOverlay.style.display = 'flex';
                            document.body.style.overflow = 'hidden';

                            // Reset to initial state
                            this.resetForm();
                            this.generateCalendar();
                        }
                    }

                    closeModal() {
                        if (this.modalOverlay) {
                            this.modalOverlay.style.display = 'none';
                            document.body.style.overflow = 'auto';
                            this.resetForm();
                        }
                    }

                    resetForm() {
                        this.currentStep = 1;
                        this.selectedChamber = null;
                        this.selectedDate = null;
                        this.selectedTime = null;
                        this.isOnlineConsultation = false;

                        // Reset form fields
                        if (this.form) this.form.reset();

                        // Hide schedule section
                        if (this.scheduleSection) {
                            this.scheduleSection.style.display = 'none';
                        }

                        // Clear calendar selection
                        this.clearCalendarSelection();
                        this.clearTimeSlots();

                        // Update UI
                        this.updateProgress();
                        this.showStep(1);
                    }

                    updateProgress() {
                        if (!this.steps.length) return;

                        this.steps.forEach((step) => {
                            const stepNum = parseInt(step.getAttribute('data-step-indicator'));
                            step.classList.toggle('active', stepNum === this.currentStep);
                            step.classList.toggle('completed', stepNum < this.currentStep);
                        });

                        // Update buttons
                        if (this.prevBtn) {
                            this.prevBtn.style.display = this.currentStep > 1 ? 'flex' : 'none';
                        }

                        if (this.nextBtn && this.submitBtn) {
                            if (this.currentStep === this.totalSteps) {
                                this.nextBtn.style.display = 'none';
                                this.submitBtn.style.display = 'flex';
                                this.updateSummary();
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

                        // Scroll to top of step
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }

                    // // onConsultationChange(type) {
                    // //     this.isOnlineConsultation = type === 'online';

                    // //     // Handle chamber selection based on consultation type
                    //     if (this.isOnlineConsultation) {
                    //         // For online consultation, auto-select first chamber if available
                    //         if (this.chamberSelect && this.chamberSelect.options.length > 1) {
                    //             this.chamberSelect.value = this.chamberSelect.options[1].value;
                    //             this.onChamberChange(this.chamberSelect.value);
                    //         }
                    //         // Disable chamber selection for online
                    //         if (this.chamberSelect) this.chamberSelect.disabled = true;
                    //         if (this.chamberCard) this.chamberCard.style.opacity = '0.6';
                    //     } else {
                    //         // For offline, enable chamber selection
                    //         if (this.chamberSelect) this.chamberSelect.disabled = false;
                    //         if (this.chamberCard) this.chamberCard.style.opacity = '1';

                    //         // Reset chamber if none selected
                    //         if (!this.chamberSelect.value) {
                    //             this.clearScheduleSection();
                    //         }
                    //     }

                    //     this.updateSummaryPreview();
                    // }
                    onConsultationChange(type) {
                        this.isOnlineConsultation = type === 'online';

                        /**
                         * =========================
                         * ONLINE CONSULTATION
                         * =========================
                         */
                        if (this.isOnlineConsultation) {

                            // Use doctor online schedule API (no chamber dependency)
                            this.selectedChamber = {
                                availabilityUrl: "{{ route('doctors.online.slots', ['doctor' => $doctor->id, 'date' => '__DATE__']) }}"
                            };

                            // Disable chamber selection (UI only)
                            if (this.chamberSelect) {
                                this.chamberSelect.value = '';
                                this.chamberSelect.disabled = true;
                            }

                            if (this.chamberCard) {
                                this.chamberCard.style.opacity = '0.6';
                            }

                            // Show schedule section
                            if (this.scheduleSection) {
                                this.scheduleSection.style.display = 'block';
                                this.scheduleSection.scrollIntoView({
                                    behavior: 'smooth'
                                });
                            }
                        }

                        /**
                         * =========================
                         * OFFLINE CONSULTATION
                         * =========================
                         */
                        else {

                            this.isOnlineConsultation = false;
                            this.selectedChamber = null;

                            // Enable chamber selection
                            if (this.chamberSelect) {
                                this.chamberSelect.disabled = false;
                            }

                            if (this.chamberCard) {
                                this.chamberCard.style.opacity = '1';
                            }

                            // Hide schedule until chamber selected
                            if (!this.chamberSelect?.value) {
                                this.clearScheduleSection();
                            }
                        }

                        // Reset previous selections
                        this.clearCalendarSelection();
                        this.clearTimeSlots();

                        // Update summary preview
                        this.updateSummaryPreview();
                    }

                    onChamberChange(chamberId) {
                        if (!chamberId) {
                            this.clearScheduleSection();
                            return;
                        }

                        // Find selected chamber option
                        const chamberOption = this.chamberSelect.querySelector(`option[value="${chamberId}"]`);
                        if (!chamberOption) return;

                        this.selectedChamber = {
                            id: chamberId,
                            name: chamberOption.textContent.split('৳')[0].trim(),
                            fees: chamberOption.getAttribute('data-fees'),
                            location: chamberOption.getAttribute('data-location'),
                            availabilityUrl: chamberOption.getAttribute('data-availability-url')
                        };

                        // Show schedule section
                        if (this.scheduleSection) {
                            this.scheduleSection.style.display = 'block';
                            this.scheduleSection.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest'
                            });
                        }

                        // Clear previous selections
                        this.clearCalendarSelection();
                        this.clearTimeSlots();

                        this.updateSummaryPreview();
                    }

                    clearScheduleSection() {
                        if (this.scheduleSection) {
                            this.scheduleSection.style.display = 'none';
                        }
                        this.clearCalendarSelection();
                        this.clearTimeSlots();
                    }

                    clearCalendarSelection() {
                        if (this.calendarGrid) {
                            this.calendarGrid.querySelectorAll('.calendar-day').forEach(day => {
                                day.classList.remove('selected');
                            });
                        }
                        this.selectedDate = null;

                        if (this.selectedDateDisplay) {
                            this.selectedDateDisplay.textContent = 'Select a date';
                        }
                    }

                    clearTimeSlots() {
                        if (this.timeSlotsContainer) {
                            this.timeSlotsContainer.style.display = 'none';
                            this.timeSlotsContainer.innerHTML = '';
                        }

                        if (this.slotsPlaceholder) {
                            this.slotsPlaceholder.style.display = 'block';
                        }

                        this.selectedTime = null;
                    }

                    generateCalendar() {
                        if (!this.calendarGrid || !this.monthDisplay || !this.yearDisplay) return;

                        const today = new Date();
                        const firstDay = new Date(this.currentYear, this.currentMonth, 1);
                        const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
                        const startingDay = firstDay.getDay();
                        const totalDays = lastDay.getDate();

                        // Update month/year display
                        this.monthDisplay.textContent = firstDay.toLocaleDateString('en-US', {
                            month: 'long'
                        });
                        this.yearDisplay.textContent = this.currentYear;

                        // Clear existing days
                        const dayHeaders = this.calendarGrid.querySelectorAll('.day-header');
                        const existingDays = this.calendarGrid.querySelectorAll('.calendar-day');
                        existingDays.forEach(day => day.remove());

                        // Re-add day headers if they were removed
                        if (dayHeaders.length === 0) {
                            const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                            daysOfWeek.forEach(day => {
                                const dayHeader = document.createElement('div');
                                dayHeader.className = 'day-header';
                                dayHeader.textContent = day;
                                this.calendarGrid.appendChild(dayHeader);
                            });
                        }

                        // Add empty cells for days before the first day of month
                        for (let i = 0; i < startingDay; i++) {
                            const emptyCell = document.createElement('div');
                            emptyCell.className = 'calendar-day empty';
                            this.calendarGrid.appendChild(emptyCell);
                        }

                        // Add days of the month
                        const todayStr = today.toISOString().split('T')[0];

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

                            // Mark today
                            if (currentDate.getTime() === today.getTime()) {
                                dayElement.classList.add('today');
                            }

                            // Mark past dates
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
                        // Remove previous selection
                        this.calendarGrid.querySelectorAll('.calendar-day').forEach(day => {
                            day.classList.remove('selected');
                        });

                        // Add selection to clicked day
                        dayElement.classList.add('selected');
                        this.selectedDate = dayElement.getAttribute('data-date');

                        // Update selected date display
                        if (this.selectedDateDisplay) {
                            const date = new Date(this.selectedDate);
                            const formattedDate = date.toLocaleDateString('en-US', {
                                weekday: 'short',
                                month: 'short',
                                day: 'numeric'
                            });
                            this.selectedDateDisplay.textContent = formattedDate;
                        }

                        // Load available time slots
                        await this.loadTimeSlots(this.selectedDate);
                    }

                    async loadTimeSlots(date) {
                        if (!this.selectedChamber || !this.selectedChamber.availabilityUrl) {
                            console.error('No chamber selected or availability URL missing');
                            return;
                        }

                        // Show loading state
                        if (this.timeSlotsContainer) {
                            this.timeSlotsContainer.style.display = 'none';
                        }

                        if (this.slotsPlaceholder) {
                            this.slotsPlaceholder.style.display = 'none';
                            this.slotsPlaceholder.innerHTML = `
                    <div class="empty-state">
                        <div class="loading-spinner"></div>
                        <p>Loading available slots...</p>
                    </div>
                `;
                            this.slotsPlaceholder.style.display = 'block';
                        }

                        try {
                            const url = this.selectedChamber.availabilityUrl.replace('__DATE__', date);
                            const response = await fetch(url);

                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }

                            const data = await response.json();
                            this.displayTimeSlots(data.slots || []);

                        } catch (error) {
                            console.error('Error loading time slots:', error);
                            this.displayTimeSlots([]);
                        }
                    }

                    displayTimeSlots(slots) {
                        if (!this.timeSlotsContainer || !this.slotsPlaceholder) return;

                        // Clear previous slots
                        this.timeSlotsContainer.innerHTML = '';

                        if (slots.length === 0) {
                            this.slotsPlaceholder.style.display = 'block';
                            this.timeSlotsContainer.style.display = 'none';
                            this.slotsPlaceholder.innerHTML = `
                    <div class="empty-state">
                        <i class="ri-time-off-line"></i>
                        <p>No available slots for this date</p>
                        <small>Please select another date</small>
                    </div>
                `;
                            return;
                        }

                        this.slotsPlaceholder.style.display = 'none';
                        this.timeSlotsContainer.style.display = 'grid';

                        // Sort slots by time
                        slots.sort((a, b) => {
                            const timeA = a.start.split(':').map(Number);
                            const timeB = b.start.split(':').map(Number);
                            return timeA[0] * 60 + timeA[1] - (timeB[0] * 60 + timeB[1]);
                        });

                        // Display slots
                        slots.forEach(slot => {
                            const slotElement = document.createElement('div');
                            slotElement.className = 'time-slot';

                            const timeParts = slot.start.split(':');
                            const hour = parseInt(timeParts[0]);
                            const minute = timeParts[1];
                            const period = hour >= 12 ? 'PM' : 'AM';
                            const displayHour = hour % 12 || 12;

                            slotElement.innerHTML = `
                    <span class="slot-time">${displayHour}:${minute}</span>
                    <span class="slot-period">${period}</span>
                `;

                            slotElement.setAttribute('data-time', slot.start);

                            if (slot.available === false) {
                                slotElement.classList.add('disabled');
                                slotElement.title = 'Slot unavailable';
                            } else {
                                slotElement.addEventListener('click', () => this.selectTime(slotElement,
                                    slot));
                            }

                            this.timeSlotsContainer.appendChild(slotElement);
                        });
                    }

                    selectTime(slotElement, slot) {
                        // Remove previous selection
                        this.timeSlotsContainer.querySelectorAll('.time-slot').forEach(s => {
                            s.classList.remove('selected');
                        });

                        // Add selection to clicked slot
                        slotElement.classList.add('selected');
                        this.selectedTime = slot.start;

                        // Update hidden inputs
                        const dateInput = document.querySelector('[data-appointment-date]');
                        const timeInput = document.querySelector('[data-appointment-time]');
                        if (dateInput) dateInput.value = this.selectedDate;
                        if (timeInput) timeInput.value = this.selectedTime;

                        this.updateSummaryPreview();
                    }

                    updateSummaryPreview() {
                        // Only update if we're on step 1
                        if (this.currentStep !== 1) return;

                        // Consultation
                        if (this.consultationSelect && this.consultationSelect.value) {
                            const consultationText = this.consultationSelect.selectedOptions[0].text;
                            const cleanText = consultationText.split('(')[0].trim();
                            if (this.sumConsultation) {
                                this.sumConsultation.textContent = cleanText;
                            }
                        }

                        // Chamber
                        if (this.chamberSelect && this.chamberSelect.value && this.sumChamber) {
                            const chamberText = this.chamberSelect.selectedOptions[0].text;
                            const cleanText = chamberText.split('৳')[0].trim();
                            this.sumChamber.textContent = cleanText;
                        }

                        // Service
                        if (this.serviceSelect && this.serviceSelect.value && this.sumService) {
                            this.sumService.textContent = this.serviceSelect.selectedOptions[0].text;
                        }

                        // Date & Time
                        if (this.selectedDate && this.selectedTime && this.sumDateTime) {
                            const date = new Date(this.selectedDate);
                            const [hours, minutes] = this.selectedTime.split(':');
                            const hour = parseInt(hours);
                            const ampm = hour >= 12 ? 'PM' : 'AM';
                            const displayHour = hour % 12 || 12;

                            this.sumDateTime.textContent =
                                `${date.toLocaleDateString('en-US', {
                        weekday: 'short',
                        month: 'short',
                        day: 'numeric'
                    })} at ${displayHour}:${minutes} ${ampm}`;
                        } else if (this.sumDateTime) {
                            this.sumDateTime.textContent = '—';
                        }

                        // Fees
                        if (this.chamberSelect && this.chamberSelect.value && this.sumFees) {
                            const fees = this.chamberSelect.selectedOptions[0].getAttribute('data-fees');
                            if (fees) {
                                this.sumFees.textContent = `৳${parseFloat(fees).toLocaleString()}`;
                            }
                        }
                    }

                    previousStep() {
                        if (this.currentStep > 1) {
                            this.showStep(this.currentStep - 1);
                        }
                    }

                    async nextStep() {
                        if (!this.validateStep(this.currentStep)) {
                            return;
                        }

                        if (this.currentStep === 1) {
                            // Validate all selections
                            if (!this.selectedDate || !this.selectedTime) {
                                this.showError('appointment_date', 'Please select a date and time slot');
                                return;
                            }

                            this.showStep(2);
                            this.updateSummary();
                        } else if (this.currentStep === this.totalSteps) {
                            // Already validated in handleFormSubmit
                            this.showStep(3);
                        }
                    }

                    validateStep(step) {
                        let isValid = true;
                        this.hideAllErrors();

                        switch (step) {
                            case 1:
                                // Validate consultation type
                                if (!this.consultationSelect?.value) {
                                    this.showError('consultation_type', 'Please select consultation type');
                                    isValid = false;
                                }

                                // Validate chamber (only for offline)
                                if (!this.isOnlineConsultation && !this.chamberSelect?.value) {
                                    this.showError('chamber_id', 'Please select a chamber');
                                    isValid = false;
                                }

                                // Validate service type
                                if (!this.serviceSelect?.value) {
                                    this.showError('service_type', 'Please select reason for visit');
                                    isValid = false;
                                }

                                // Validate date and time (only if chamber is selected)
                                if (this.chamberSelect?.value && (!this.selectedDate || !this.selectedTime)) {
                                    this.showError('appointment_date', 'Please select a date and time slot');
                                    isValid = false;
                                }
                                break;

                            case 2:
                                // Validate patient details
                                const requiredFields = [
                                    'patient_first_name',
                                    'patient_last_name',
                                    'patient_email',
                                    'patient_phone'
                                ];

                                requiredFields.forEach(field => {
                                    const input = document.querySelector(`[name="${field}"]`);
                                    if (!input || !input.value.trim()) {
                                        this.showError(field, `${this.getFieldLabel(field)} is required`);
                                        isValid = false;
                                    }
                                });

                                // Validate email format
                                const emailInput = document.querySelector('[name="patient_email"]');
                                if (emailInput && emailInput.value) {
                                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                    if (!emailRegex.test(emailInput.value)) {
                                        this.showError('patient_email', 'Please enter a valid email address');
                                        isValid = false;
                                    }
                                }

                                // Validate terms agreement
                                const termsAgreed = document.querySelector('input[name="terms_agreed"]:checked');
                                if (!termsAgreed) {
                                    this.showError('terms_agreed', 'You must agree to the terms and conditions');
                                    isValid = false;
                                }
                                break;
                        }

                        return isValid;
                    }

                    showError(field, message) {
                        // Find or create error element
                        let errorElement = document.querySelector(`[data-error="${field}"]`);
                        if (!errorElement) {
                            errorElement = document.createElement('div');
                            errorElement.className = 'form-error show';
                            errorElement.setAttribute('data-error', field);

                            // Insert after the corresponding input
                            const inputField = document.querySelector(`[name="${field}"]`);
                            if (inputField && inputField.parentNode) {
                                inputField.parentNode.appendChild(errorElement);
                            }
                        }

                        errorElement.textContent = message;
                        errorElement.classList.add('show');

                        // Scroll to error
                        errorElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    hideAllErrors() {
                        document.querySelectorAll('.form-error').forEach(error => {
                            error.classList.remove('show');
                        });
                    }

                    getFieldLabel(fieldName) {
                        const labels = {
                            'patient_first_name': 'First name',
                            'patient_last_name': 'Last name',
                            'patient_email': 'Email',
                            'patient_phone': 'Phone number',
                            'terms_agreed': 'Terms agreement',
                            'consultation_type': 'Consultation type',
                            'chamber_id': 'Chamber',
                            'service_type': 'Service type',
                            'appointment_date': 'Date and time'
                        };
                        return labels[fieldName] || fieldName;
                    }

                    editSummary() {
                        this.showStep(1);
                    }

                    updateSummary() {
                        // Update all summary fields in step 2
                        this.updateSummaryPreview();
                    }

                    async handleFormSubmit(e) {
                        e.preventDefault();

                        if (!this.validateStep(2)) {
                            return;
                        }

                        // Disable submit button
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
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                }
                            });

                            const result = await response.json();

                            if (result.success) {
                                this.showStep(3);
                                this.displaySuccessDetails(result.appointment);
                            } else {
                                alert('Booking failed: ' + (result.message || 'Please try again'));
                                if (this.submitBtn) {
                                    this.submitBtn.disabled = false;
                                    this.submitBtn.innerHTML =
                                        '<i class="ri-calendar-check-line"></i> Confirm Booking';
                                }
                            }
                        } catch (error) {
                            console.error('Booking error:', error);
                            alert('An error occurred. Please try again.');
                            if (this.submitBtn) {
                                this.submitBtn.disabled = false;
                                this.submitBtn.innerHTML = '<i class="ri-calendar-check-line"></i> Confirm Booking';
                            }
                        }
                    }

                    // displaySuccessDetails(appointment) {
                    //     const detailsElement = document.querySelector('[data-success-details]');
                    //     if (detailsElement && appointment) {
                    //         const date = appointment.appointment_date ? new Date(appointment.appointment_date) : null;
                    //         const time = appointment.appointment_time || '';

                    //         let formattedDateTime = 'N/A';
                    //         if (date && time) {
                    //             const [hours, minutes] = time.split(':');
                    //             const hour = parseInt(hours);
                    //             const ampm = hour >= 12 ? 'PM' : 'AM';
                    //             const displayHour = hour % 12 || 12;

                    //             formattedDateTime =
                    //                 `${date.toLocaleDateString('en-US', {
            //                     weekday: 'long',
            //                     year: 'numeric',
            //                     month: 'long',
            //                     day: 'numeric'
            //                 })} at ${displayHour}:${minutes} ${ampm}`;
                    //         }

                    //         detailsElement.innerHTML = `
            //             <div class="detail-item">
            //                 <strong>Appointment ID:</strong> ${appointment.id || 'N/A'}
            //             </div>
            //             <div class="detail-item">
            //                 <strong>Date & Time:</strong> ${formattedDateTime}
            //             </div>
            //             <div class="detail-item">
            //                 <strong>Doctor:</strong> Dr. {{ $doctor->name }}
            //             </div>
            //             <div class="detail-item">
            //                 <strong>Chamber:</strong> ${appointment.chamber_name || 'N/A'}
            //             </div>
            //             <div class="detail-item">
            //                 <strong>Fees:</strong> ৳${appointment.fees ? parseFloat(appointment.fees).toLocaleString() : '0'}
            //             </div>
            //             <div class="detail-item">
            //                 <strong>Status:</strong> <span class="status-confirmed">Confirmed</span>
            //             </div>
            //         `;
                    //     }
                    // }
                    displaySuccessDetails(appointment) {
                        const detailsElement = document.querySelector('[data-success-details]');
                        const downloadBtn = document.querySelector('[data-download-pdf]');
                        if (!detailsElement || !appointment) return;

                        const date = appointment.appointment_date ?
                            new Date(appointment.appointment_date) :
                            null;

                        const time = appointment.appointment_time || '';
                        let formattedDateTime = 'N/A';

                        if (date && time) {
                            const [h, m] = time.split(':');
                            const hour = parseInt(h);
                            const ampm = hour >= 12 ? 'PM' : 'AM';
                            const displayHour = hour % 12 || 12;

                            formattedDateTime =
                                `${date.toLocaleDateString('en-US', {
                weekday: 'long',
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            })} • ${displayHour}:${m} ${ampm}`;
                        }

                        detailsElement.innerHTML = `
        <div id="pdf-content" class="pdf-wrapper">
            <div class="pdf-header">
                <div>
                    <h2>Appointment Confirmation</h2>
                    <p class="sub">DoctorsProfile</p>
                </div>
                <span class="badge-confirmed">CONFIRMED</span>
            </div>

            <hr>

            <table class="pdf-table">
                <tr>
                    <td>ID</td>
                    <td>#${appointment.id}</td>
                </tr>
                <tr>
                    <td>Doctor</td>
                    <td>Dr. {{ $doctor->name }}</td>
                </tr>
                <tr>
                    <td>Consultation Type</td>
                    <td>${appointment.consultation_type}</td>
                </tr>
                <tr>
                    <td>Date & Time</td>
                    <td>${formattedDateTime}</td>
                </tr>
                <tr>
                    <td>Fees</td>
                    <td>৳${appointment.fees ?? 0}</td>
                </tr>
            </table>

            ${
                appointment.meeting_link
                ? `
                        <div class="meeting-box">
                            <strong>Online Meeting Link</strong>
                            <a href="${appointment.meeting_link}" target="_blank">
                                ${appointment.meeting_link}
                            </a>
                        </div>
                        `
                : ''
            }

            <div class="pdf-footer">
                <p>
                    Please be available 5 minutes before your scheduled time.
                </p>
                <small>
                    © ${new Date().getFullYear()} DoctorsProfile. All rights reserved.
                </small>
            </div>
        </div>
    `;

                        if (downloadBtn) {
                            downloadBtn.onclick = () => {
                                const element = document.getElementById('pdf-content');

                                html2pdf().set({
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


                }

                // Initialize the booking system
                const modalOverlay = document.querySelector('[data-overlay]');
                if (modalOverlay) {
                    window.bookingSystem = new AppointmentBooking();
                    console.log('Appointment booking system initialized');
                }
            });
        </script>

    </body>

    </html>
