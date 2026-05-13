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
    <link rel="stylesheet" href="{{asset('doctor/css/eight.css')}}">
<meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body class="bg-white">
    @include('partials.analytics-body')
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
                                <h1 class="text-xl font-bold text-gray-900 custom-font custom-font">
                                    {{$doctor->name}}</h1>
                                <p class="text-xs text-gray-600 text-start">Medical Practice</p>
                            </div>
                        </button>
                    </a>
                    <nav class="hidden lg:flex items-center gap-8">
                        <a href="/"
                            class="text-gray-700 hover:text-cyan-600 font-medium transition-colors cursor-pointer">Home</a>
                        <a href="#about"
                            class="text-gray-700 hover:text-cyan-600 font-medium transition-colors cursor-pointer">About</a>
                        <a href="/services"
                            class="text-gray-700 hover:text-cyan-600 font-medium transition-colors cursor-pointer">Services</a>
                        <a href="/articles"
                            class="text-gray-700 hover:text-cyan-600 font-medium transition-colors cursor-pointer">Articles</a>
                            {{-- login logout --}}
                        @auth
                        <a href=""
                            class="text-gray-700 hover:text-cyan-600 font-medium transition-colors cursor-pointer">Dashboard</a>
                        <a href="tel:5551234567"
                            class="flex items-center gap-2 px-6 py-3 bg-cyan-600 text-white rounded-lg font-semibold hover:bg-cyan-700 transition-all whitespace-nowrap cursor-pointer">
                            <i class="ri-phone-line"></i>{{$doctor->mobile}}
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
                            {{$doctor->name}}</h3>
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
                            <li><button class="hover:text-cyan-400 transition-colors cursor-pointer"> {{$doctor->name}}</button></li>
                            <li><button
                                    class="hover:text-cyan-400 transition-colors cursor-pointer">Medical </button>
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
                            <li><a href="#" class="hover:text-cyan-400 transition-colors cursor-pointer">Chronic Disease
                                    Management</a></li>
                            <li><a href="#" class="hover:text-cyan-400 transition-colors cursor-pointer">Cardiovascular
                                    Care</a></li>
                            <li><a href="#" class="hover:text-cyan-400 transition-colors cursor-pointer">Preventive
                                    Medicine</a></li>
                            <li><a href="#" class="hover:text-cyan-400 transition-colors cursor-pointer">Women's
                                    Health</a></li>
                            <li><a href="#" class="hover:text-cyan-400 transition-colors cursor-pointer">Telemedicine
                                    Services</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold text-lg mb-4">Contact Info</h4>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3">
                                <i class="ri-map-pin-fill text-cyan-400 mt-1"></i>
                                <span>{{$doctor->address}}</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="ri-phone-fill text-cyan-400"></i>
                                <a href="tel:5551234567"
                                    class="hover:text-cyan-400 transition-colors cursor-pointer">{{$doctor->mobile}}</a>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="ri-mail-fill text-cyan-400"></i>
                                <a href="mailto:info@drmitchell.com"
                                    class="hover:text-cyan-400 transition-colors cursor-pointer">{{$doctor->email}}</a>
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
                        <p class="text-gray-400 text-sm">© {{date('y')}} {{$doctor->name}} Medical Practice. All rights
                            reserved.</p>
                        <div class="flex gap-6 text-sm">
                            <a href="#" class="hover:text-cyan-400 transition-colors cursor-pointer">Privacy Policy</a>
                            <a href="#" class="hover:text-cyan-400 transition-colors cursor-pointer">Terms of
                                Service</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

   {{--<script>

        const header = document.getElementById('mainHeader');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                header.classList.add('shadow-md');
            } else {
                header.classList.remove('shadow-md');
            }
        });


        // Simple form handling for demo purposes
        document.addEventListener('DOMContentLoaded', function () {
            // const form = document.querySelector('form');
            // if (form) {
            //     form.addEventListener('submit', function (e) {
            //         e.preventDefault();
            //         alert('Thank you for your appointment request! We will contact you shortly to confirm your appointment.');
            //         form.reset();
            //     });
            // }

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


        const chatbox = document.getElementById("vapi-chatbox");
        const chatButton = document.getElementById("vapi-widget-floating-button");
        const closeButton = document.getElementById("closeChat");

        chatButton.addEventListener("click", () => {
            chatbox.classList.toggle("hidden");
        });

        closeButton.addEventListener("click", () => {
            chatbox.classList.add("hidden");
        });

    </script>--}}
   <script>
document.addEventListener('DOMContentLoaded', function() {
    class AppointmentBooking {
        constructor() {
            this.currentStep = 1;
            this.totalSteps = 6;
            this.formData = new FormData();
            this.selectedChamber = null;
            this.selectedDate = null;
            this.selectedTime = null;
            this.currentMonth = new Date().getMonth();
            this.currentYear = new Date().getFullYear();

            this.initializeElements();
            this.initializeEventListeners();
            this.generateCalendar();
        }

        initializeElements() {
            this.modalOverlay = document.querySelector('[data-overlay]');
            this.openBtn = document.querySelector('[data-open]');
            this.closeBtn = document.querySelector('[data-close]');
            this.form = document.querySelector('[data-form]');
            this.steps = document.querySelectorAll('[data-step-indicator]');
            this.panes = document.querySelectorAll('[data-pane]');
            this.prevBtn = document.querySelector('[data-prev]');
            this.nextBtn = document.querySelector('[data-next]');
            this.submitBtn = document.querySelector('[data-submit]');
            this.calendar = document.querySelector('[data-cal]');
            this.monthDisplay = document.querySelector('[data-month]');
            this.calPrev = document.querySelector('[data-cal-prev]');
            this.calNext = document.querySelector('[data-cal-next]');
            this.timeSlots = document.querySelector('[data-time-slots]');
            this.slotsPlaceholder = document.querySelector('[data-slots-placeholder]');
        }

        initializeEventListeners() {
            // Safe event listener for open button
            if (this.openBtn) {
                this.openBtn.addEventListener('click', () => this.openModal());
            }

            // Safe event listener for close button
            if (this.closeBtn) {
                this.closeBtn.addEventListener('click', () => this.closeModal());
            }

            // Safe event listener for modal overlay
            if (this.modalOverlay) {
                this.modalOverlay.addEventListener('click', (e) => {
                    if (e.target === this.modalOverlay) this.closeModal();
                });
            }

            // Safe event listeners for navigation buttons
            if (this.prevBtn) {
                this.prevBtn.addEventListener('click', () => this.previousStep());
            }

            if (this.nextBtn) {
                this.nextBtn.addEventListener('click', () => this.nextStep());
            }

            if (this.submitBtn) {
                this.submitBtn.addEventListener('click', (e) => this.handleFormSubmit(e));
            }

            // Safe event listeners for calendar navigation
            if (this.calPrev) {
                this.calPrev.addEventListener('click', () => this.changeMonth(-1));
            }

            if (this.calNext) {
                this.calNext.addEventListener('click', () => this.changeMonth(1));
            }

            // Consultation type change
            const consultationRadios = document.querySelectorAll('input[name="consultation_type"]');
            if (consultationRadios.length > 0) {
                consultationRadios.forEach(radio => {
                    radio.addEventListener('change', (e) => this.onConsultationChange(e.target.value));
                });
            }

            // Chamber selection - Use event delegation on the container
            const chambersContainer = document.querySelector('[data-chambers-container]');
            if (chambersContainer) {
                chambersContainer.addEventListener('change', (e) => {
                    if (e.target.name === 'chamber_id') {
                        this.onChamberChange(e.target);
                    }
                });
            }

            // Form submission
            if (this.form) {
                this.form.addEventListener('submit', (e) => this.handleFormSubmit(e));
            }
        }

        openModal() {
            if (this.modalOverlay) {
                this.modalOverlay.style.display = 'flex';
                document.body.style.overflow = 'hidden';
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
            this.formData = new FormData();
            this.selectedChamber = null;
            this.selectedDate = null;
            this.selectedTime = null;
            this.updateProgress();
            this.showStep(1);
        }

        updateProgress() {
            if (this.steps.length === 0) return;

            this.steps.forEach((step, index) => {
                const stepNum = parseInt(step.getAttribute('data-step-indicator'));
                step.classList.toggle('active', stepNum === this.currentStep);
                step.classList.toggle('completed', stepNum < this.currentStep);
            });

            if (this.prevBtn) {
                this.prevBtn.disabled = this.currentStep === 1;
            }

            if (this.nextBtn && this.submitBtn) {
                if (this.currentStep === this.totalSteps) {
                    this.nextBtn.style.display = 'none';
                    this.submitBtn.style.display = 'block';
                    this.updateSummary();
                } else {
                    this.nextBtn.style.display = 'block';
                    this.submitBtn.style.display = 'none';
                }
            }
        }

        showStep(stepNumber) {
            this.panes.forEach(pane => {
                const paneNumber = parseInt(pane.getAttribute('data-pane'));
                pane.style.display = paneNumber === stepNumber ? 'block' : 'none';
                pane.classList.toggle('active', paneNumber === stepNumber);
            });
            this.currentStep = stepNumber;
            this.updateProgress();
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

            // Special handling for step 2 (chamber selection)
            if (this.currentStep === 2) {
                const selectedChamber = document.querySelector('input[name="chamber_id"]:checked');
                if (!selectedChamber) {
                    this.showError('chamber_id', 'Please select a chamber');
                    return;
                }
                // Store the chamber input element
                this.selectedChamber = selectedChamber;
                console.log('Chamber selected in step 2:', this.selectedChamber);
            }

            // Special handling for step 4 (date & time)
            if (this.currentStep === 4) {
                if (!this.selectedDate || !this.selectedTime) {
                    this.showError('appointment_date', 'Please select a date and time');
                    return;
                }
            }

            this.showStep(this.currentStep + 1);
        }

        validateStep(step) {
            let isValid = true;
            this.hideAllErrors();

            switch(step) {
                case 1:
                    if (!document.querySelector('input[name="consultation_type"]:checked')) {
                        this.showError('consultation_type', 'Please select consultation type');
                        isValid = false;
                    }
                    break;
                case 2:
                    if (!document.querySelector('input[name="chamber_id"]:checked')) {
                        this.showError('chamber_id', 'Please select a chamber');
                        isValid = false;
                    }
                    break;
                case 3:
                    if (!document.querySelector('input[name="service_type"]:checked')) {
                        this.showError('service_type', 'Please select service type');
                        isValid = false;
                    }
                    break;
                case 5:
                    const requiredFields = [
                        'patient_first_name', 'patient_last_name',
                        'patient_email', 'patient_phone'
                    ];
                    requiredFields.forEach(field => {
                        const input = document.querySelector(`[name="${field}"]`);
                        if (!input || !input.value.trim()) {
                            this.showError(field, `${this.getFieldLabel(field)} is required`);
                            isValid = false;
                        }
                    });
                    break;
            }

            return isValid;
        }

        showError(field, message) {
            const errorElement = document.querySelector(`[data-error="${field}"]`);
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.classList.add('show');
            }
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
                'patient_phone': 'Phone'
            };
            return labels[fieldName] || fieldName;
        }

        onConsultationChange(type) {
            const onlineStatus = document.querySelector('[data-online-status]');
            if (onlineStatus) {
                if (type === 'online' && !{{ $doctor->accepts_virtual_visits ? 'true' : 'false' }}) {
                    onlineStatus.innerHTML = '<i class="ri-close-line"></i> Not Available';
                    onlineStatus.className = 'text-danger';
                } else {
                    onlineStatus.innerHTML = '<i class="ri-check-line"></i> Available';
                    onlineStatus.className = 'text-success';
                }
            }
        }

        onChamberChange(chamberInput) {
            console.log('Chamber changed:', chamberInput);
            if (!chamberInput) {
                console.error('No chamber input provided');
                return;
            }

            // Store the actual input element
            this.selectedChamber = chamberInput;
            this.selectedDate = null;
            this.selectedTime = null;

            // Clear any previous time slots
            if (this.timeSlots) {
                this.timeSlots.innerHTML = '';
                this.timeSlots.style.display = 'none';
            }
            if (this.slotsPlaceholder) {
                this.slotsPlaceholder.style.display = 'block';
            }

            console.log('Selected chamber set:', this.selectedChamber);
            this.updateCalendar();
        }

        generateCalendar() {
            this.updateCalendar();
        }

        updateCalendar() {
            if (!this.calendar || !this.monthDisplay) return;

            const firstDay = new Date(this.currentYear, this.currentMonth, 1);
            const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
            const startingDay = firstDay.getDay();
            const monthLength = lastDay.getDate();

            this.monthDisplay.textContent =
                firstDay.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

            // Clear existing days (keep day names)
            const existingDays = this.calendar.querySelectorAll('.calendar-day');
            existingDays.forEach(day => day.remove());

            // Add empty cells for days before the first day of month
            for (let i = 0; i < startingDay; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'calendar-day empty';
                this.calendar.appendChild(emptyCell);
            }

            // Add days of the month
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Reset time for accurate comparison

            for (let day = 1; day <= monthLength; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                dayElement.textContent = day;

                const dateString = `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                dayElement.setAttribute('data-date', dateString);

                const currentDate = new Date(this.currentYear, this.currentMonth, day);

                // Disable past dates
                if (currentDate < today) {
                    dayElement.classList.add('disabled');
                } else {
                    dayElement.addEventListener('click', () => this.selectDate(dayElement));
                }

                this.calendar.appendChild(dayElement);
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
            this.updateCalendar();
        }

        async selectDate(dayElement) {
            // Remove previous selection
            this.calendar.querySelectorAll('.calendar-day').forEach(day => {
                day.classList.remove('selected');
            });

            dayElement.classList.add('selected');
            this.selectedDate = dayElement.getAttribute('data-date');

            // Load available time slots
            await this.loadTimeSlots(this.selectedDate);
        }

        async loadTimeSlots(date) {
            console.log('Loading time slots for date:', date);
            console.log('Selected chamber:', this.selectedChamber);

            // FIX: Comprehensive null checking
            if (!this.selectedChamber) {
                console.error('No chamber selected - cannot load time slots');
                this.showError('chamber_id', 'Please select a chamber first');
                return;
            }

            // FIX: Check if selectedChamber is a valid DOM element
            if (!(this.selectedChamber instanceof Element)) {
                console.error('selectedChamber is not a valid DOM element:', this.selectedChamber);
                this.showError('chamber_id', 'Invalid chamber selection');
                return;
            }

            // FIX: Check if getAttribute method exists
            if (typeof this.selectedChamber.getAttribute !== 'function') {
                console.error('selectedChamber does not have getAttribute method:', this.selectedChamber);
                this.showError('chamber_id', 'Chamber configuration error');
                return;
            }

            const availabilityUrl = this.selectedChamber.getAttribute('data-availability-url');
            console.log('Availability URL:', availabilityUrl);

            // FIX: Check if URL attribute exists and is valid
            if (!availabilityUrl || availabilityUrl === 'null' || availabilityUrl.includes('__DATE__') === false) {
                console.error('Invalid data-availability-url attribute:', availabilityUrl);
                this.showError('chamber_id', 'Chamber configuration error - invalid URL');
                return;
            }

            if (this.slotsPlaceholder) {
                this.slotsPlaceholder.style.display = 'block';
            }

            if (this.timeSlots) {
                this.timeSlots.style.display = 'none';
                this.timeSlots.innerHTML = '';
            }

            try {
                const url = availabilityUrl.replace('__DATE__', date);
                console.log('Fetching slots from:', url);

                const response = await fetch(url);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Slots data received:', data);

                if (this.slotsPlaceholder) {
                    this.slotsPlaceholder.style.display = 'none';
                }

                if (this.timeSlots) {
                    this.timeSlots.style.display = 'block';

                    if (data.slots && data.slots.length > 0) {
                        data.slots.forEach(slot => {
                            const slotElement = document.createElement('div');
                            slotElement.className = 'time-slot';
                            slotElement.textContent = slot.display || this.formatTimeForDisplay(slot.start);
                            slotElement.setAttribute('data-time', slot.start);
                            slotElement.setAttribute('data-display', slot.display || slot.start);
                            slotElement.addEventListener('click', () => this.selectTime(slotElement, slot));
                            this.timeSlots.appendChild(slotElement);
                        });
                    } else {
                        this.timeSlots.innerHTML = '<div class="muted">No available slots for this date</div>';
                    }
                }
            } catch (error) {
                console.error('Error loading time slots:', error);
                if (this.timeSlots) {
                    this.timeSlots.innerHTML = '<div class="text-danger">Error loading available slots</div>';
                }
            }
        }

        selectTime(slotElement, slot) {
            // Remove previous selection
            if (this.timeSlots) {
                this.timeSlots.querySelectorAll('.time-slot').forEach(s => {
                    s.classList.remove('selected');
                });
            }

            slotElement.classList.add('selected');
            this.selectedTime = slot.start;

            // Update hidden inputs
            const dateInput = document.querySelector('[data-appointment-date]');
            const timeInput = document.querySelector('[data-appointment-time]');

            if (dateInput) dateInput.value = this.selectedDate;
            if (timeInput) timeInput.value = this.selectedTime;
        }

        updateSummary() {
            // Consultation
            const consultation = document.querySelector('input[name="consultation_type"]:checked');
            if (consultation) {
                const sumElement = document.querySelector('[data-sum-consultation]');
                if (sumElement) {
                    sumElement.textContent =
                        consultation.value === 'online' ? 'Online (Video)' : 'In Person';
                }
            }

            // Chamber - FIX: Safe chamber name extraction
            if (this.selectedChamber && this.selectedChamber.closest) {
                try {
                    const chamberCard = this.selectedChamber.closest('.chamber-card');
                    const chamberName = chamberCard ? chamberCard.querySelector('h4')?.textContent : 'Selected Chamber';
                    const sumElement = document.querySelector('[data-sum-chamber]');
                    if (sumElement) {
                        sumElement.textContent = chamberName || 'Selected Chamber';
                    }
                } catch (error) {
                    console.error('Error getting chamber name:', error);
                    const sumElement = document.querySelector('[data-sum-chamber]');
                    if (sumElement) sumElement.textContent = 'Selected Chamber';
                }
            }

            // Service
            const service = document.querySelector('input[name="service_type"]:checked');
            if (service) {
                const sumElement = document.querySelector('[data-sum-service]');
                if (sumElement) {
                    sumElement.textContent = service.value;
                }
            }

            // Date & Time
            if (this.selectedDate && this.selectedTime) {
                const date = new Date(this.selectedDate);
                const time = this.selectedTime;
                const sumElement = document.querySelector('[data-sum-dt]');
                if (sumElement) {
                    sumElement.textContent =
                        `${date.toLocaleDateString()} at ${this.formatTime(time)}`;
                }
            }

            // Fees - FIX: Safe fee extraction
            if (this.selectedChamber && typeof this.selectedChamber.getAttribute === 'function') {
                try {
                    const fees = this.selectedChamber.getAttribute('data-fees');
                    const sumElement = document.querySelector('[data-sum-fees]');
                    if (sumElement && fees) {
                        sumElement.textContent = `৳${parseFloat(fees).toLocaleString()}`;
                    }
                } catch (error) {
                    console.error('Error getting chamber fees:', error);
                }
            }

            // Patient info
            const firstName = document.querySelector('[name="patient_first_name"]');
            const lastName = document.querySelector('[name="patient_last_name"]');
            const email = document.querySelector('[name="patient_email"]');
            const phone = document.querySelector('[name="patient_phone"]');

            const patientElement = document.querySelector('[data-sum-patient]');
            const contactElement = document.querySelector('[data-sum-contact]');

            if (patientElement && firstName && lastName) {
                patientElement.textContent = `${firstName.value} ${lastName.value}`;
            }

            if (contactElement && email && phone) {
                contactElement.textContent = `${email.value} | ${phone.value}`;
            }
        }

        formatTime(timeString) {
            try {
                const [hours, minutes] = timeString.split(':');
                const hour = parseInt(hours);
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const displayHour = hour % 12 || 12;
                return `${displayHour}:${minutes} ${ampm}`;
            } catch (error) {
                return timeString;
            }
        }

        formatTimeForDisplay(timeString) {
            if (!timeString) return 'Invalid Time';

            try {
                const timeParts = timeString.split(':');
                if (timeParts.length < 2) return timeString;

                const hours = parseInt(timeParts[0]);
                const minutes = timeParts[1];
                const ampm = hours >= 12 ? 'PM' : 'AM';
                const displayHours = hours % 12 || 12;

                return `${displayHours}:${minutes} ${ampm}`;
            } catch (e) {
                return timeString;
            }
        }

        async handleFormSubmit(e) {
            e.preventDefault();

            console.log('Form submission started');
            console.log('Selected chamber:', this.selectedChamber);
            console.log('Selected date:', this.selectedDate);
            console.log('Selected time:', this.selectedTime);

            // FIX: Comprehensive validation before submission
            if (!this.selectedChamber) {
                console.error('No chamber selected for submission');
                this.showStep(2);
                this.showError('chamber_id', 'Please select a chamber');
                return;
            }

            if (!this.selectedDate || !this.selectedTime) {
                console.error('No date/time selected for submission');
                this.showStep(4);
                this.showError('appointment_date', 'Please select a date and time');
                return;
            }

            if (!this.validateStep(5)) {
                this.showStep(5);
                return;
            }

            const termsAgreed = document.querySelector('input[name="terms_agreed"]:checked');
            if (!termsAgreed) {
                alert('Please agree to the terms and conditions');
                return;
            }

            if (this.submitBtn) {
                this.submitBtn.disabled = true;
                this.submitBtn.textContent = 'Booking...';
            }

            try {
                const formData = new FormData(this.form);

                // Add debug logging
                console.log('Submitting to:', this.form.action);
                for (let [key, value] of formData.entries()) {
                    console.log(`Form data: ${key} = ${value}`);
                }

                const response = await fetch(this.form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();
                console.log('Server response:', result);

                if (result.success) {
                    this.showStep(7);
                    this.displaySuccessDetails(result.appointment);
                } else {
                    alert('Booking failed: ' + (result.message || 'Please try again'));
                    if (this.submitBtn) {
                        this.submitBtn.disabled = false;
                        this.submitBtn.textContent = 'Confirm Booking';
                    }
                }
            } catch (error) {
                console.error('Booking error:', error);
                alert('Booking failed. Please try again.');
                if (this.submitBtn) {
                    this.submitBtn.disabled = false;
                    this.submitBtn.textContent = 'Confirm Booking';
                }
            }
        }

        displaySuccessDetails(appointment) {
            const detailsElement = document.querySelector('[data-success-details]');
            if (detailsElement && appointment) {
                detailsElement.innerHTML = `
                    <div class="appointment-details">
                        <p><strong>Appointment ID:</strong> ${appointment.id || 'N/A'}</p>
                        <p><strong>Date & Time:</strong> ${appointment.appointment_date ? new Date(appointment.appointment_date).toLocaleDateString() : 'N/A'} at ${appointment.appointment_time || 'N/A'}</p>
                        <p><strong>Chamber:</strong> ${appointment.chamber_name || 'N/A'}</p>
                        <p><strong>Fees:</strong> ৳${appointment.fees ? parseFloat(appointment.fees).toLocaleString() : '0'}</p>
                    </div>
                `;
            }
        }
    }

    // Initialize the booking system only if required elements exist
    const modalOverlay = document.querySelector('[data-overlay]');
    if (modalOverlay) {
        new AppointmentBooking();
    } else {
        console.log('Appointment booking modal not found on this page');
    }
});
</script>
</body>

</html>
