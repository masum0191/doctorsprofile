@extends('layouts.forntend')
@section('title', $doctor->name . ' - ' . ($setting->site_name ?? 'Medical Practice'))
@section('content')
    <style>
        /* Enhanced Modal Styles with Fixed Scrolling */
        :root {
            --primary: #318069;
            --primary-light: rgba(49, 128, 105, 0.1);
            --primary-dark: #2a6d5a;
            --primary-soft: rgba(49, 128, 105, 0.05);
            --primary-hover: rgba(49, 128, 105, 0.15);
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border: #e5e7eb;
            --background: #ffffff;
            --background-light: #f9fafb;
        }

        /* Modal Overlay - Fixed to viewport */
        .modal-overlay-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Changed from auto to 100% to fill viewport */
            background: rgba(0, 0, 0, 0.6);
            display: none;
            /* Controlled by your JS */
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
            overflow-y: auto;
            /* Enable vertical scrolling on the overlay itself */
        }

        .appointment-form{
            width: 100%;
            height: 100%;
            overflow-y: auto;
        }
        /* Modal Container - Constrained height */
        .enhanced-modal {
            background: var(--background);
            border-radius: 16px;
            width: 100%;
            max-width: 800px;
            overflow: hidden;
            /* Use max-height to ensure it doesn't go off-screen on small laptops */
            max-height: 90vh; 
            margin: auto;
            /* Helps with centering when content is long */
            display: flex;
            flex-direction: column;
            position: relative;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        /* Ensure the form area is the part that scrolls */

        /* Modal Header - Fixed position */
        .enhanced-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 24px 28px;
            border-bottom: none;
            flex-shrink: 0;
            /* Prevent header from shrinking */
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
            font-size: 28px;
            cursor: pointer;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 24px;
            right: 24px;
        }

        .close-btn:hover {
            color: white;
        }

        /* Progress Bar - Fixed position */
        .progress-bar {
            background: var(--background-light);
            padding: 20px 32px;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
            /* Prevent progress bar from shrinking */
        }

        .step {
            display: flex;
            align-items: center;
            gap: 12px;
            opacity: 0.5;
            transition: all 0.3s ease;
        }

        .step.active {
            opacity: 1;
        }

        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-secondary);
            transition: all 0.3s ease;
        }

        .step.active .step-number {
            background: var(--primary);
            color: white;
        }

        .step.completed .step-number {
            background: var(--success);
            color: white;
        }

        .step-title {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .step.active .step-title {
            color: var(--primary);
            font-weight: 600;
        }

        /* Form Content - Scrollable area */
        .enhanced-form {
            padding: 26px;
            flex: 1;
            overflow-y: auto;
            /* This allows the form content to scroll while header/footer stay fixed */
            -webkit-overflow-scrolling: touch;
            /* Smooth scroll for mobile */
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

        /* Step Headers */
        .step-header {
            margin-bottom: 32px;
        }

        .step-header h4 {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 8px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .step-header h4 i {
            color: var(--primary);
        }

        .step-subtitle {
            color: var(--text-secondary);
            font-size: 14px;
            margin: 0;
        }

        /* Selection Grid */
        .selection-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .form-card {
            background: var(--background-light);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .form-card:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(49, 128, 105, 0.1);
        }

        .form-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            background: var(--primary-soft);
            border-bottom: 1px solid var(--border);
        }

        .form-card-header i {
            font-size: 20px;
            color: var(--primary);
            width: 35px;
            height: 35px;
            background: var(--primary-light);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-card-header h5 {
            margin: 0 0 4px 0;
            font-size: 15px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-card-header small {
            color: var(--text-secondary);
            font-size: 13px;
        }

        .form-card-body {
            padding: 20px;
        }

        /* Enhanced Select */
        .enhanced-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            color: var(--text-primary);
            background: white;
            transition: all 0.2s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23318069'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 20px;
            padding-right: 48px;
        }

        .enhanced-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        }

        .enhanced-select option {
            padding: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .option-tag {
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 12px;
            margin-left: 8px;
            font-weight: 500;
        }

        .option-tag.available {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
        }

        .option-tag.unavailable {
            background: rgba(239, 68, 68, 0.1);
            color: #991b1b;
        }

        .option-tag.fee {
            background: rgba(49, 128, 105, 0.1);
            color: var(--primary);
        }

        /* Form Hints */
        .form-hint {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .form-hint i {
            color: var(--primary);
            font-size: 14px;
        }

        /* Schedule Section */
        .schedule-section {
            margin-top: 32px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-header {
            margin-bottom: 20px;
        }

        .section-header h5 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 4px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-header h5 i {
            color: var(--primary);
        }

        .section-subtitle {
            color: var(--text-secondary);
            font-size: 14px;
            margin: 0;
        }

        /* Calendar & Time Picker */
        .schedule-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
        }

        /* Calendar Styles */
        .calendar-container {
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 20px;
            background: var(--background-light);
        }

        .calendar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .calendar-nav {
            width: 36px;
            height: 36px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: white;
            color: var(--text-secondary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .calendar-nav:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-soft);
        }

        .month-year {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
        }

        .month-year .month {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .month-year .year {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            margin-bottom: 20px;
        }

        .day-header {
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            padding: 8px 0;
            text-transform: uppercase;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
            position: relative;
        }

        .calendar-day:hover:not(.disabled):not(.past) {
            background: var(--primary-soft);
            border-color: var(--primary-light);
        }

        .calendar-day.today {
            background: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary);
        }

        .calendar-day.selected {
            background: var(--primary) !important;
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(49, 128, 105, 0.2);
        }

        .calendar-day.available {
            background: white;
            border: 2px solid rgba(16, 185, 129, 0.3);
        }

        .calendar-day.past {
            background: var(--background-light);
            color: var(--text-secondary);
            cursor: not-allowed;
            opacity: 0.5;
        }

        .calendar-day.disabled {
            background: var(--background-light);
            color: var(--text-secondary);
            cursor: not-allowed;
            opacity: 0.5;
        }

        .calendar-footer {
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .legend {
            display: flex;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-secondary);
        }

        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .legend-dot.today {
            background: var(--primary);
        }

        .legend-dot.available {
            background: rgba(16, 185, 129, 0.3);
            border: 2px solid #10b981;
        }

        .legend-dot.selected {
            background: var(--primary);
        }

        .d-none {
            display: none !important;
        }

        /* Time Slots */
        .time-slots-container {
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 20px;
            background: var(--background-light);
        }

        .time-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .time-header h6 {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .selected-date {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .selected-date i {
            color: var(--primary);
        }

        .time-slots-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            margin-bottom: 20px;
            min-height: 150px;
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px 20px;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.3;
        }

        .empty-state p {
            margin: 0;
            font-size: 14px;
        }

        .time-slot {
            padding: 12px 8px;
            border: 2px solid var(--border);
            border-radius: 8px;
            background: white;
            text-align: center;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .time-slot:hover:not(.disabled) {
            border-color: var(--primary);
            background: var(--primary-soft);
        }

        .time-slot.selected {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(49, 128, 105, 0.2);
        }

        .time-slot.disabled {
            background: var(--background-light);
            color: var(--text-secondary);
            border-color: var(--border);
            cursor: not-allowed;
            opacity: 0.5;
        }

        .time-slot .slot-time {
            font-size: 15px;
            font-weight: 600;
        }

        .time-slot .slot-period {
            font-size: 11px;
            opacity: 0.8;
        }

        .form-error {
            color: red !important;
            font-size: 13px !important;
            margin-top: 5px !important;
        }

        .time-info {
            text-align: center;
            font-size: 12px;
            color: var(--text-secondary);
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .time-info i {
            color: var(--primary);
            margin-right: 4px;
        }

        /* Summary Preview */
        .summary-preview {
            background: var(--background-light);
            border: 1px solid var(--border);
            border-radius: 12px;
            margin-bottom: 32px;
            overflow: hidden;
        }

        .summary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: var(--primary-soft);
            border-bottom: 1px solid var(--border);
        }

        .summary-header h5 {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-header h5 i {
            color: var(--primary);
        }

        .edit-btn {
            background: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .edit-btn:hover {
            background: var(--primary);
            color: white;
        }

        .summary-content {
            padding: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-row.highlight {
            background: white;
            padding: 16px;
            border-radius: 8px;
            margin-top: 8px;
            border: 1px solid var(--border);
        }

        .summary-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .summary-label i {
            color: var(--primary);
            font-size: 16px;
        }

        .summary-value {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
            text-align: right;
        }

        /* Form Section */
        .form-section {
            margin-bottom: 32px;
        }

        .form-section h5 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 20px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section h5 i {
            color: var(--primary);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-label .required {
            color: var(--danger);
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            color: var(--text-primary);
            background: white;
            transition: all 0.2s;
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        }

        .form-input::placeholder {
            color: var(--text-secondary);
        }

        textarea.form-input {
            resize: vertical;
            min-height: 80px;
        }

        /* Terms Section */
        .terms-section {
            background: var(--primary-soft);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            margin-top: 32px;
        }

        .form-check {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin: 0;
        }

        .form-check input[type="checkbox"] {
            margin-top: 4px;
            accent-color: var(--primary);
        }

        .form-check label {
            font-size: 14px;
            color: var(--text-primary);
            line-height: 1.5;
        }

        .form-check label a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .form-check label a:hover {
            text-decoration: underline;
        }

        /* Success Section */
        .success-section {
            text-align: center;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--success), #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 5px;
        }

        .success-icon i {
            font-size: 36px;
            color: white;
        }

        .success-content h3 {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .success-message {
            color: var(--text-secondary);
            font-size: 16px;
            margin: 0 0 32px 0;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .appointment-details {
            background: var(--background-light);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 5px;
            margin: 32px 0;
            text-align: left;
        }
        .pdf-header h2{
            font-size: 18px !important;
        }

        .success-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            margin-top: 32px;
        }

        /* Footer */
        .enhanced-footer {
            background: var(--background-light);
            border-top: 1px solid var(--border);
            padding: 20px 32px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 20px;
            flex-shrink: 0;
            /* Prevent footer from shrinking */
        }

        /* Buttons */
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 120px;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-secondary {
            background: white;
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .btn-secondary:hover:not(:disabled) {
            background: var(--primary-soft);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(49, 128, 105, 0.2);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover:not(:disabled) {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .modal-overlay-container {
                padding: 10px;
            }

            .enhanced-modal {
                width: 100%;
                border-radius: 0;
            }

            .enhanced-form {
                padding: 20px;
            }

            .selection-grid {
                grid-template-columns: 1fr;
            }

            .schedule-container {
                grid-template-columns: 1fr;
                padding: 16px;
            }

            .calendar-grid {
                gap: 4px;
            }

            .calendar-day {
                font-size: 13px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .enhanced-footer {
                flex-direction: column;
                gap: 12px;
                padding: 16px;
            }

            .btn {
                width: 100%;
            }

            .success-actions {
                flex-direction: column;
            }
        }

        /* Print Styles */
        @media print {
            .modal-overlay-container {
                position: static;
                display: block !important;
                background: white;
                padding: 20px;
            }

            .enhanced-modal {
                max-width: 100%;
                max-height: none;
                box-shadow: none;
                border: 1px solid #ddd;
            }

            .close-btn,
            .enhanced-footer,
            .progress-bar {
                display: none !important;
            }
        }
    </style>


    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center bg-gradient-to-br from-cyan-50 via-white to-teal-50">
        <div class="absolute inset-0 z-0 opacity-15 bg-cover bg-center"
            style="background-image: url('{{ @$doctor->profile->hero_image ? url($doctor->profile->hero_image) : 'https://img.freepik.com/free-photo/blur-hospital_1203-7972.jpg' }}')">
        </div>
        <div class="container mx-auto px-6 lg:px-12 py-20 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-8">
                    <div class="inline-block px-4 py-2 bg-cyan-100 text-cyan-700 rounded-full text-sm font-medium">
                        <i
                            class="ri-stethoscope-line mr-2"></i>{{ $doctor->profile->tagline ?? 'Professional Medical Care' }}
                    </div>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold text-gray-900 leading-snug md:leading-tight">
                        {{ $doctor->profile->headline ?? 'Your Health, Our Priority' }}
                    </h1>
                    <p class="text-md md:text-lg text-gray-600 leading-relaxed">
                        {{ $doctor->profile->about_short ?? 'Comprehensive healthcare services with years of experience.' }}
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <button
                            class="px-6 py-3 bg-cyan-600 text-white rounded-lg font-semibold hover:bg-cyan-700 transition-all shadow-lg hover:shadow-xl whitespace-nowrap cursor-pointer launch-btn"
                            type="button" data-open>
                            <i class="ri-calendar-check-line mr-2"></i>Book Appointment
                        </button>
                    </div>
                    <div class="grid grid-cols-3 gap-6 pt-8 border-t border-gray-200">
                        <div>
                            <div class="text-3xl font-bold text-cyan-600">{{ $doctor->profile->years_experience ?? '15' }}+
                            </div>
                            <div class="text-sm text-gray-600 mt-1">Years Experience</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-cyan-600">{{ $doctor->profile->patients_count ?? '5000' }}+
                            </div>
                            <div class="text-sm text-gray-600 mt-1">Happy Patients</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-cyan-600">{{ $doctor->profile->satisfaction_rate ?? '98' }}%
                            </div>
                            <div class="text-sm text-gray-600 mt-1">Satisfaction Rate</div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                   <div class="relative bg-white rounded-2xl overflow-hidden shadow-2xl 
                    w-full h-[260px] sm:h-[320px] md:h-[400px] lg:h-[450px]">

                    <img 
                        alt="{{ $doctor->name }}" 
                        src="{{ $doctor->photo ? url($doctor->photo) : 'https://img.freepik.com/free-photo/female-doctor-hospital_23-2148827760.jpg' }}"
                        class="w-full h-full object-contain"
                    >
                </div>
                    <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-xl shadow-xl">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="ri-shield-check-fill text-2xl text-green-600"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Board Certified</div>
                                <div class="text-sm text-gray-600">Licensed Physician</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MODAL --}}
    <div class="modal-overlay-container" data-overlay style="display: none;">
        <div class="booking-modal enhanced-modal">
            <!-- Modal HTML remains exactly the same -->
            <header class="modal-header enhanced-header">
                <h2>Book Appointment with Dr. {{ $doctor->name }}</h2>
                <button class="close-btn" type="button" data-close>&times;</button>
            </header>

            {{-- Simplified 2-step progress bar --}}
            <div class="progress-bar d-none" data-steps>
                <div class="step active" data-step-indicator="1">
                    <div class="step-number">1</div>
                    <div class="step-title">Schedule</div>
                </div>
                <div class="step" data-step-indicator="2">
                    <div class="step-number">2</div>
                    <div class="step-title">Your Details</div>
                </div>
            </div>

            <form id="appointment-form" class="appointment-form" data-form method="POST" action="{{ route('appointments.store') }}">
                @csrf
                <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                <div class="form-content enhanced-form">
                    {{-- STEP 1: Service Selection & Date/Time --}}
                    <div class="form-step active" data-pane="1">


                        {{-- Service Selection Cards --}}
                        <div class="selection-grid">
                            {{-- Consultation Type --}}
                            <div class="form-card">
                                <div class="form-card-header">
                                    <i class="ri-video-chat-line"></i>
                                    <div>
                                        <h5>Consultation Type</h5>
                                        <!-- <small>Choose how you'd like to consult</small> -->
                                    </div>
                                </div>
                                <div class="form-card-body">
                                    <select name="consultation_type" class="enhanced-select" required
                                        data-consultation-select>
                                        <option value="">Select type</option>
                                        <option value="online" {{ !$doctor->accepts_virtual_visits ? 'disabled' : '' }}>
                                            Online Consultation
                                            <span
                                                class="option-tag {{ $doctor->accepts_virtual_visits ? 'available' : 'unavailable' }}">
                                                {{ $doctor->accepts_virtual_visits ? 'Available' : 'Not Available' }}
                                            </span>
                                        </option>
                                        <option value="offline">In-person Visit</option>
                                    </select>
                                    <!-- <div class="form-hint">
                                            <i class="ri-information-line"></i>
                                            <span data-consultation-hint>Select your preferred consultation method</span>
                                        </div> -->
                                </div>
                            </div>

                            {{-- Chamber Selection --}}
                            <div class="form-card" data-chamber-card>
                                <div class="form-card-header">
                                    <i class="ri-building-line"></i>
                                    <div>
                                        <h5>Select Chamber</h5>
                                        <!-- <small>Choose your preferred location</small> -->
                                    </div>
                                </div>
                                <div class="form-card-body">
                                    <select name="chamber_id" class="enhanced-select" data-chamber-select>
                                        <option value="">Select chamber</option>
                                        @foreach ($chambers->where('is_active', true) as $chamber)
                                            <option value="{{ $chamber->id }}" data-fees="{{ $chamber->fees }}"
                                                data-location="{{ $chamber->city }}"
                                                data-availability-url="{{ route('chambers.slots', ['chamber' => $chamber->id, 'date' => '__DATE__']) }}">
                                                {{ $chamber->name }}
                                                <span class="option-tag fee">৳{{ number_format($chamber->fees) }}</span>
                                            </option>
                                        @endforeach
                                    </select>
                                    <!-- <div class="form-hint">
                                            <i class="ri-map-pin-line"></i>
                                            <span data-chamber-hint>Fees vary by location</span>
                                        </div> -->
                                </div>
                            </div>

                            {{-- Service Type --}}
                            <div class="form-card">
                                <div class="form-card-header">
                                    <i class="ri-stethoscope-line"></i>
                                    <div>
                                        <h5>Reason for Visit</h5>
                                        <!-- <small>What brings you today?</small> -->
                                    </div>
                                </div>
                                <div class="form-card-body">
                                    <select name="service_type" class="enhanced-select" required data-service-select>
                                        <option value="">Select reason</option>
                                        @foreach ([
            'New Patient Visit' => 'First consultation',
            'Annual Physical' => 'Yearly check-up',
            'Follow-up Visit' => 'Ongoing care',
            'Acute Illness' => 'Cold/flu, etc.',
            'Chronic Condition' => 'Diabetes, hypertension, etc.',
            'Medication Review' => 'Prescription refill',
            'Vaccination' => 'Immunization',
            'Other' => 'Specify in notes',
        ] as $service => $description)
                                            <option value="{{ $service }}" data-description="{{ $description }}">
                                                {{ $service }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!-- <div class="form-hint">
                                            <i class="ri-question-line"></i>
                                            <span data-service-hint>Helps us prepare for your visit</span>
                                        </div> -->
                                </div>
                            </div>
                        </div>

                        {{-- Calendar & Time Picker (Initially Hidden) --}}
                        <div class="schedule-section" data-schedule-section style="display: none;">
                            <div class="section-header">
                                <h5><i class="ri-calendar-line"></i> Select Date & Time</h5>
                                <p class="section-subtitle">Choose your preferred appointment slot</p>
                            </div>

                            <div class="schedule-container">
                                {{-- Calendar --}}
                                <div class="calendar-container">
                                    <div class="calendar-header">
                                        <button type="button" class="calendar-nav" data-cal-prev>
                                            <i class="ri-arrow-left-s-line"></i>
                                        </button>
                                        <div class="month-year">
                                            <span class="month" data-month>—</span>
                                            <span class="year" data-year>—</span>
                                        </div>
                                        <button type="button" class="calendar-nav" data-cal-next>
                                            <i class="ri-arrow-right-s-line"></i>
                                        </button>
                                    </div>

                                    <div class="calendar-grid">
                                        <div class="day-header">Sun</div>
                                        <div class="day-header">Mon</div>
                                        <div class="day-header">Tue</div>
                                        <div class="day-header">Wed</div>
                                        <div class="day-header">Thu</div>
                                        <div class="day-header">Fri</div>
                                        <div class="day-header">Sat</div>
                                        {{-- Days will be injected here --}}
                                    </div>

                                    <div class="calendar-footer">
                                        <div class="legend">
                                            <div class="legend-item">
                                                <span class="legend-dot today"></span>
                                                <span>Today</span>
                                            </div>
                                            <div class="legend-item">
                                                <span class="legend-dot available"></span>
                                                <span>Available</span>
                                            </div>
                                            <div class="legend-item">
                                                <span class="legend-dot selected"></span>
                                                <span>Selected</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Time Slots --}}
                                <div class="time-slots-container">
                                    <div class="time-header">
                                        <h6>Available Time Slots</h6>
                                        <div class="selected-date" data-selected-date>
                                            <i class="ri-calendar-event-line"></i>
                                            <span>Select a date</span>
                                        </div>
                                    </div>

                                    <div class="time-slots-grid" data-slots-placeholder>
                                        <div class="empty-state">
                                            <i class="ri-time-line"></i>
                                            <p>Select a date to view available slots</p>
                                        </div>
                                    </div>

                                    <div class="time-slots-grid" data-time-slots style="display: none;">
                                        {{-- Time slots will be injected here --}}
                                    </div>


                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="appointment_date" data-appointment-date>
                        <input type="hidden" name="appointment_time" data-appointment-time>
                    </div>

                    {{-- STEP 2: Patient Details with Summary --}}
                    <div class="form-step" data-pane="2">

                        {{-- Patient Information Form --}}
                        <div class="form-section">
                            <h5><i class="ri-user-settings-line"></i> Personal Details</h5>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">First Name <span class="required">*</span></label>
                                    <input type="text" name="patient_first_name" class="form-input"
                                        value="{{ auth()->check() ? auth()->user()->name : '' }}" placeholder="John"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Last Name <span class="required">*</span></label>
                                    <input type="text" name="patient_last_name" class="form-input" placeholder="Doe"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email Address <span class="required">*</span></label>
                                    <input type="email" name="patient_email" class="form-input"
                                        value="{{ auth()->check() ? auth()->user()->email : '' }}"
                                        placeholder="john@example.com" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Phone Number <span class="required">*</span></label>
                                    <input type="tel" name="patient_phone" class="form-input"
                                        value="{{ auth()->check() ? auth()->user()->phone : '' }}"
                                        placeholder="+880 1XXX XXXXXX" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="patient_dob" class="form-input"
                                        max="{{ date('Y-m-d') }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Gender</label>
                                    <select name="patient_gender" class="form-input">
                                        <option value="">Select</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group full-width">
                                    <label class="form-label">Reason for Visit (Optional)</label>
                                    <textarea name="notes" class="form-input" rows="2"
                                        placeholder="Please describe your symptoms or reason for visit..."></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Terms & Conditions --}}
                        <div class="terms-section">
                            <div class="form-check">
                                <input type="checkbox" name="terms_agreed" id="terms_agreed" required>
                                <label for="terms_agreed">
                                    I agree to the <a href="#" target="_blank">terms and conditions</a> and privacy
                                    policy.
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- STEP 3: Success --}}
                    <div class="form-step" data-pane="3">
                        <div class="success-section">
                            <div class="success-icon">
                                <i class="ri-checkbox-circle-fill"></i>
                            </div>
                            <div class="success-content">
                                <h3>Appointment Confirmed!</h3>
                                <p class="success-message">Your appointment has been successfully booked.
                                    You will receive a confirmation email shortly.</p>

                                <div class="appointment-details" data-success-details>
                                    {{-- Appointment details will be injected here --}}
                                </div>

                                <div class="success-actions">
                                    <button type="button" class="btn btn-primary" data-close>
                                        <i class="ri-home-line"></i> Return to Home
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-download-pdf>
                                        <i class="ri-download-line"></i> Download PDF
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                

            </form>
            <footer class="modal-footer enhanced-footer" data-footer>
                    <button type="button" class="btn btn-secondary" data-prev style="display: none;">
                        <i class="ri-arrow-left-line"></i> Back
                    </button>
                    <button type="button" class="btn btn-primary" data-next>
                        Continue <i class="ri-arrow-right-line"></i>
                    </button>
                    <button type="submit" class="btn btn-success" data-submit style="display: none;">
                        <i class="ri-calendar-check-line"></i> Confirm Booking
                    </button>
                </footer>
        </div>
    </div>
    <!-- About Section -->
    <section id="about" class="py-16 bg-white">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="text-center mb-8 md:mb-16">
                <div class="inline-block px-4 py-2 bg-cyan-100 text-cyan-700 rounded-full text-sm font-medium mb-4">
                    About Dr. {{ explode(' ', $doctor->name)[0] ?? 'Doctor' }}
                </div>
                <h2 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-4 custom-font">
                    {{ $doctor->profile->subheadline ?? 'Dedicated to Your Wellbeing' }}
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    {{ $doctor->profile->tagline ?? 'With years of medical practice, combining expertise with compassionate care.' }}
                </p>
            </div>

            <!-- Professional Background -->
            <div class="grid lg:grid-cols-2 gap-12 items-center mb-20">
                <div>
                    <img alt="Dr. {{ $doctor->name }} with patient"
                        class="rounded-2xl shadow-xl w-full h-auto object-cover object-top"
                        src="{{ $doctor->galleries->where('category', 'care')->first()->image_url ?? 'https://readdy.ai/api/search-image?query=Professional%20doctor%20consulting%20with%20patient' }}">
                </div>
                <div class="space-y-6">
                    <h3 class="text-3xl font-bold text-gray-900">Professional Background</h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $doctor->profile->about_long ?? 'Board-certified physician with comprehensive medical experience.' }}
                    </p>

                    @if ($doctor->qualification)
                        <p class="text-gray-600 leading-relaxed">Dr. {{ $doctor->name }} is a
                            {{ $doctor->qualification }} specializing in internal medicine and cardiology.</p>
                    @endif

                    @if ($doctor->reg_no)
                        <p class="text-gray-600 leading-relaxed">Registration Number: {{ $doctor->reg_no }}</p>
                    @endif
                </div>
            </div>

            <!-- Education & Training -->
            @if ($doctor->educations->count() > 0)
                <div class="mb-20">
                    <div class="text-center mb-6 sm:mb-12">
                        <h3 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2 custom-font">Education & Training</h3>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Comprehensive medical education from renowned
                            institutions</p>
                    </div>
                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach ($doctor->educations as $education)
                            <div
                                class="bg-white border-2 border-gray-100 p-6 rounded-xl hover:border-cyan-200 hover:shadow-lg transition-all cursor-pointer">
                                <div class="flex flex-col md:flex-row items-start gap-4">
                                    <div
                                        class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-teal-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="ri-graduation-cap-fill text-2xl text-white"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm text-cyan-600 font-semibold mb-1">
                                            {{ $education->start_year }}@if ($education->end_year)
                                                -{{ $education->end_year }}
                                            @endif
                                        </div>
                                        <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $education->degree }}</h4>
                                        <p class="text-gray-700 font-medium mb-1">{{ $education->institution }}</p>
                                        @if ($education->city)
                                            <p class="text-gray-500 text-sm mb-2">
                                                {{ $education->city }}{{ $education->country ? ', ' . $education->country : '' }}
                                            </p>
                                        @endif
                                        @if ($education->description)
                                            <p class="text-gray-600 text-sm">{{ $education->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Certifications -->
            @if ($doctor->certifications->count() > 0)
                <div class="mb-20">
                    <div class="text-center mb-6 sm:mb-12">
                        <h3 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2 custom-font">Board Certifications &
                            Credentials</h3>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Maintaining the highest standards of medical
                            excellence</p>
                    </div>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($doctor->certifications as $certification)
                            <div
                                class="bg-gradient-to-br from-cyan-50 to-teal-50 p-6 rounded-xl hover:shadow-lg transition-all cursor-pointer">
                                <div class="w-14 h-14 bg-cyan-600 rounded-lg flex items-center justify-center mb-4">
                                    <i class="ri-award-fill text-2xl text-white"></i>
                                </div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-sm text-cyan-600 font-semibold">{{ $certification->year }}</span>
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                        {{ ucfirst($certification->status) }}
                                    </span>
                                </div>
                                <h4 class="text-lg font-bold text-gray-900 mb-2">{{ $certification->title }}</h4>
                                <p class="text-gray-600 text-sm">{{ $certification->organization }}</p>
                                @if ($certification->description)
                                    <p class="text-gray-600 text-sm mt-2">{{ $certification->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Professional Affiliations -->
            @if ($doctor->affiliations->count() > 0)
                <div class="mb-20">
                    <div class="text-center mb-6 sm:mb-12">
                        <h3 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2 custom-font">Professional Affiliations
                        </h3>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Proud member of leading medical institutions and
                            organizations</p>
                    </div>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($doctor->affiliations as $affiliation)
                            <div
                                class="bg-white border-2 border-gray-100 p-6 rounded-xl hover:border-teal-200 hover:shadow-lg transition-all cursor-pointer">
                                <div class="flex flex-col md:flex-row items-start gap-4">
                                    <div
                                        class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        @if ($affiliation->type == 'hospital')
                                            <i class="ri-hospital-line text-xl text-teal-600"></i>
                                        @elseif($affiliation->type == 'organization')
                                            <i class="ri-building-line text-xl text-teal-600"></i>
                                        @else
                                            <i class="ri-team-fill text-xl text-teal-600"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-xs text-teal-600 font-semibold mb-2 uppercase">
                                            {{ ucfirst($affiliation->type) }} Affiliation
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $affiliation->name }}</h4>
                                        @if ($affiliation->position)
                                            <p class="text-gray-600 text-sm">{{ $affiliation->position }}</p>
                                        @endif
                                        @if ($affiliation->description)
                                            <p class="text-gray-600 text-sm mt-2">{{ $affiliation->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Areas of Expertise -->
            @if ($doctor->services->count() > 0)
                <div class="bg-gray-50 rounded-2xl p-3 lg:p-8">
                    <h3 class="text-3xl font-bold text-gray-900 mb-8 text-center">Areas of Expertise</h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        @foreach ($doctor->services as $service)
                            <div class="flex items-center gap-3 bg-white p-4 rounded-lg">
                                <div
                                    class="w-8 h-8 bg-cyan-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="ri-check-line text-cyan-600"></i>
                                </div>
                                <span class="text-gray-700 font-medium">{{ $service->title }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Specialties Section -->
    @if ($doctor->specialties->count() > 0)
        <section id="specialties" class="py-20 bg-gradient-to-br from-cyan-50 to-teal-50">
            <div class="container mx-auto px-6 lg:px-12">
                <div class="text-center mb-8 md:mb-16">
                    <div class="inline-block px-4 py-2 bg-cyan-600 text-white rounded-full text-sm font-medium mb-4">
                        Medical Specialties
                    </div>
                    <h2 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-4 custom-font">Areas of Specialization</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">Providing expert medical care across multiple
                        specialties with a patient-centered approach</p>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($doctor->specialties as $specialty)
                        <div
                            class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer group hover:-translate-y-2">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-teal-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <i class="{{ $specialty->icon ?? 'ri-heart-pulse-fill' }} text-3xl text-white"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $specialty->name }}</h3>
                            <p class="text-gray-600 leading-relaxed mb-4">
                                {{ $specialty->description ?? 'Comprehensive medical care and treatment.' }}</p>
                            @if ($specialty->patients_treated)
                                <div class="flex items-center gap-2 text-cyan-600 font-semibold">
                                    <i class="ri-user-line"></i>
                                    <span>{{ number_format($specialty->patients_treated) }}+ Patients Treated</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Experience & Timeline -->
    @if ($doctor->experiences->count() > 0)
        <section id="experience" class="py-16 bg-white">
            <div class="container mx-auto px-6 lg:px-12">
                <div class="text-center mb-8 md:mb-16">
                    <div class="inline-block px-4 py-2 bg-teal-100 text-teal-700 rounded-full text-sm font-medium mb-4">
                        Professional Journey
                    </div>
                    <h2 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-4 custom-font">Experience & Achievements
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">A proven track record of excellence in medical
                        practice and patient care</p>
                </div>

                <!-- Stats -->
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
                    <div class="bg-gradient-to-br from-cyan-500 to-teal-500 rounded-2xl p-8 text-center text-white">
                        <div class="text-5xl font-bold mb-2">{{ $doctor->profile->years_experience ?? '15' }}+</div>
                        <div class="text-cyan-100 font-medium">Years Experience</div>
                    </div>
                    <div class="bg-gradient-to-br from-cyan-500 to-teal-500 rounded-2xl p-8 text-center text-white">
                        <div class="text-5xl font-bold mb-2">{{ $doctor->profile->patients_count ?? '12,000' }}+</div>
                        <div class="text-cyan-100 font-medium">Patients Treated</div>
                    </div>
                    <div class="bg-gradient-to-br from-cyan-500 to-teal-500 rounded-2xl p-8 text-center text-white">
                        <div class="text-5xl font-bold mb-2">{{ $doctor->certifications->count() ?? '25' }}+</div>
                        <div class="text-cyan-100 font-medium">Certifications</div>
                    </div>
                    <div class="bg-gradient-to-br from-cyan-500 to-teal-500 rounded-2xl p-8 text-center text-white">
                        <div class="text-5xl font-bold mb-2">{{ $doctor->profile->satisfaction_rate ?? '98' }}%</div>
                        <div class="text-cyan-100 font-medium">Patient Satisfaction</div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="relative">
                    <div
                        class="absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-cyan-200 to-teal-200 hidden lg:block">
                    </div>
                    <div class="space-y-12">
                        @foreach ($doctor->experiences as $index => $experience)
                            <div
                                class="flex flex-col lg:flex-row gap-4 md:gap-8 items-center {{ $index % 2 == 0 ? '' : 'lg:flex-row-reverse' }}">
                                <div class="flex-1 {{ $index % 2 == 0 ? 'lg:text-right' : 'lg:text-left' }}">
                                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                                        <div class="text-cyan-600 font-bold text-lg mb-2">
                                            {{ $experience->start_year }}@if ($experience->end_year)
                                                -{{ $experience->end_year }}
                                            @endif
                                        </div>
                                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $experience->title }}</h3>
                                        <div class="text-teal-600 font-semibold mb-3">{{ $experience->organization }}
                                        </div>
                                        @if ($experience->description)
                                            <p class="text-gray-600">{{ $experience->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="hidden md:flex relative z-10">
                                    <div
                                        class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-teal-500 rounded-full flex items-center justify-center shadow-lg">
                                        <i class="ri-briefcase-fill text-2xl text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-1 hidden lg:block"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Online Chamber Section -->
    @if ($doctor->accepts_virtual_visits && $doctor->telemedicinePlatforms->count() > 0)
        <section id="online-chamber" class="py-20 bg-gradient-to-br from-cyan-50 via-white to-teal-50">
            <div class="container mx-auto px-6 lg:px-12">
                <div class="text-center mb-8 md:mb-16">
                    <div class="inline-block px-4 py-2 bg-cyan-100 text-cyan-700 rounded-full text-sm font-medium mb-4">
                        Telemedicine Services
                    </div>
                    <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-3 custom-font">Online Chamber & Virtual Care
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">Experience quality healthcare from the comfort of
                        your home with our comprehensive telemedicine services</p>
                </div>

                <!-- Supported Platforms -->
                <div class="bg-white p-6 rounded-xl border-2 border-cyan-100 mb-8">
                    <h4 class="text-lg font-bold text-gray-900 mb-4">Supported Platforms</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach ($doctor->telemedicinePlatforms->where('active', true) as $platform)
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                    style="background-color: {{ $platform->color ?? '#3b82f6' }}">
                                    <i class="{{ $platform->icon }} text-white"></i>
                                </div>
                                <span class="text-gray-700 font-medium">{{ $platform->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Services Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
                    <div
                        class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all cursor-pointer border-2 border-transparent hover:border-cyan-200">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-teal-500 rounded-xl flex items-center justify-center mb-4">
                            <i class="ri-video-chat-fill text-3xl text-white"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Video Consultations</h4>
                        <p class="text-gray-600 mb-4 text-sm leading-relaxed">Face-to-face virtual appointments from the
                            comfort of your home</p>
                    </div>
                    <!-- Add other service cards similarly -->
                </div>
            </div>
        </section>
    @endif

    <!-- Gallery Section -->
    @if ($doctor->galleries->count() > 0)
        <section id="gallery" class="py-16 bg-gray-50">
            <div class="container mx-auto px-6 lg:px-12">
                <div class="text-center mb-6 sm:mb-12">
                    <div class="inline-block px-4 py-2 bg-teal-100 text-teal-700 rounded-full text-sm font-medium mb-4">
                        Photo Gallery
                    </div>
                    <h2 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-4 custom-font">Our Clinic & Facilities</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">Take a virtual tour of our modern medical facility
                    </p>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($doctor->galleries as $gallery)
                        <div
                            class="group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer hover:shadow-2xl transition-all duration-300">
                            <img alt="{{ $gallery->title }}"
                                class="w-full h-64 object-cover object-top group-hover:scale-110 transition-transform duration-500"
                                src="{{ url($gallery->image_url) }}">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-0 left-0 right-0 p-6">
                                    <div class="text-cyan-400 text-sm font-semibold mb-1">
                                        {{ ucfirst($gallery->category) }}</div>
                                    <h3 class="text-white text-xl font-bold">{{ $gallery->title }}</h3>
                                    @if ($gallery->caption)
                                        <p class="text-gray-300 text-sm mt-1">{{ $gallery->caption }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Testimonials Section -->
    @if ($doctor->testimonials->count() > 0)
        <section class="py-16 bg-gradient-to-br from-gray-50 to-cyan-50">
            <div class="container mx-auto px-6 lg:px-12">
                <div class="text-center mb-8 md:mb-16">
                    <div class="inline-block px-4 py-2 bg-cyan-100 text-cyan-700 rounded-full text-sm font-medium mb-4">
                        Patient Testimonials
                    </div>
                    <h2 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-4 custom-font">What Our Patients Say</h2>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">Don't just take our word for it. Here's what our
                        patients have to say about their experience.</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($doctor->testimonials as $testimonial)
                        <div
                            class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer">
                            <div class="flex items-center gap-4 mb-6">
                                @if ($testimonial->photo)
                                    <img alt="{{ $testimonial->patient_name }}"
                                        class="w-16 h-16 rounded-full object-cover object-top"
                                        src="{{ url($testimonial->photo) }}">
                                @else
                                    <div class="w-16 h-16 bg-cyan-100 rounded-full flex items-center justify-center">
                                        <i class="ri-user-fill text-2xl text-cyan-600"></i>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $testimonial->patient_name }}</h4>
                                    @if ($testimonial->since)
                                        <p class="text-sm text-gray-600">Patient since {{ $testimonial->since }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex gap-1 mb-4">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i
                                        class="ri-star-fill {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                            <p class="text-gray-600 leading-relaxed">"{{ $testimonial->content }}"</p>
                            @if ($testimonial->verified)
                                <div class="mt-6 pt-6 border-t border-gray-100">
                                    <div class="flex items-center gap-2 text-cyan-600">
                                        <i class="ri-verified-badge-fill"></i>
                                        <span class="text-sm font-medium">Verified Patient</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- FAQs Section -->
    @if ($doctor->faqs->count() > 0)
        <section class="py-16 bg-white">
            <div class="container mx-auto px-6 lg:px-12">
                <div class="text-center mb-8 md:mb-16">
                    <div class="inline-block px-4 py-2 bg-cyan-100 text-cyan-700 rounded-full text-sm font-medium mb-4">
                        FAQ
                    </div>
                    <h2 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-4 custom-font">Frequently Asked Questions
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">Find answers to common questions about our practice,
                        appointments, and services.</p>
                </div>
                <div class="max-w-4xl mx-auto space-y-4">
                    @foreach ($doctor->faqs as $faq)
                        <details
                            class="bg-gradient-to-br from-gray-50 to-cyan-50 rounded-xl overflow-hidden group cursor-pointer">
                            <summary
                                class="px-8 py-6 font-semibold text-gray-900 text-lg cursor-pointer hover:text-cyan-600 transition-colors flex items-center justify-between">
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

@endsection
