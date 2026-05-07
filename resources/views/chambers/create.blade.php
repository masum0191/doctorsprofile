@extends('layouts.admin')
@section('title', 'Create Chamber')

@section('content')
<style>
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.08);
        --primary-dark: #2a6d5a;
        --primary-soft: rgba(49, 128, 105, 0.05);
        --primary-border: rgba(49, 128, 105, 0.15);
    }


    .form-section {
        background: #fff;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .section-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: var(--primary);
        font-size: 1rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.375rem;
    }

    .form-hint {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.25rem;
        line-height: 1.3;
    }

    .required-star {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-control {
        width: 100%;
        border: 1.5px solid #d1d5db;
        border-radius: 6px;
        padding: 0.625rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: #fff;
        font-family: inherit;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    .form-control::placeholder {
        color: #9ca3af;
        font-size: 0.85rem;
    }

    textarea.form-control {
        min-height: 80px;
        resize: vertical;
        line-height: 1.4;
    }

    /* Schedule Type Cards */
    .schedule-type-cards {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-top: 0.5rem;
    }

    @media (max-width: 640px) {
        .schedule-type-cards {
            grid-template-columns: 1fr;
        }
    }

    .schedule-type-card {
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.15s ease;
        background: #fff;
    }

    .schedule-type-card:hover {
        border-color: var(--primary);
    }

    .schedule-type-card.selected {
        border-color: var(--primary);
        background: linear-gradient(135deg, var(--primary-soft), rgba(49, 128, 105, 0.02));
    }

    .schedule-type-card .icon {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        background: linear-gradient(135deg, var(--primary-soft), var(--primary-light));
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
        color: var(--primary);
        font-size: 0.9rem;
    }

    .schedule-type-card.selected .icon {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
    }

    .schedule-type-card h6 {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.25rem;
    }

    .schedule-type-card p {
        font-size: 0.75rem;
        color: #6b7280;
        margin: 0;
        line-height: 1.3;
    }

    .schedule-type-card input[type="radio"] {
        display: none;
    }

    /* Day Schedule Grid */
    .day-schedule-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 0.75rem;
        margin-top: 1rem;
    }

    @media (max-width: 640px) {
        .day-schedule-grid {
            grid-template-columns: 1fr;
        }
    }

    .day-schedule-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 0.875rem;
        transition: all 0.15s ease;
    }

    .day-schedule-card.enabled {
        border-color: var(--primary-border);
        background: linear-gradient(to bottom, rgba(49, 128, 105, 0.02), rgba(49, 128, 105, 0.01));
    }

    .day-schedule-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.625rem;
    }

    .day-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
        margin: 0;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 38px;
        height: 20px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #d1d5db;
        transition: .15s;
        border-radius: 34px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        transition: .15s;
        border-radius: 50%;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    }

    input:checked + .toggle-slider {
        background-color: var(--primary);
    }

    input:checked + .toggle-slider:before {
        transform: translateX(18px);
    }

    /* Time Inputs */
    .time-inputs {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 0.5rem;
        align-items: end;
    }

    .time-slot-container {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 0.5rem;
        align-items: end;
        margin-top: 0.5rem;
    }

    .time-input-group {
        position: relative;
    }

    .time-input-group label {
        display: block;
        font-size: 0.7rem;
        font-weight: 500;
        color: #6b7280;
        margin-bottom: 0.125rem;
    }

    .time-input {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        padding: 0.375rem 0.5rem;
        font-size: 0.8rem;
        font-family: inherit;
        transition: all 0.15s ease;
        background: white;
    }

    .time-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    .time-input:disabled {
        background-color: #f9fafb;
        border-color: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
    }

    .time-separator {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 30px;
        padding-bottom: 1rem;
        color: #9ca3af;
        font-size: 0.8rem;
    }

    /* Info Alert */
    .info-alert {
        background: linear-gradient(to right, rgba(59, 130, 246, 0.05), rgba(59, 130, 246, 0.02));
        border: 1px solid rgba(59, 130, 246, 0.15);
        border-left: 2px solid #3b82f6;
        border-radius: 6px;
        padding: 0.75rem;
        margin-bottom: 1rem;
        display: flex;
        gap: 0.5rem;
        align-items: flex-start;
    }

    .info-alert i {
        color: #3b82f6;
        font-size: 0.875rem;
        margin-top: 0.125rem;
        flex-shrink: 0;
    }

    .info-alert p {
        margin: 0;
        color: #374151;
        font-size: 0.8rem;
        line-height: 1.4;
    }

    /* Custom Schedule Info */
    .custom-schedule-info {
        background: linear-gradient(to right, rgba(139, 92, 246, 0.05), rgba(139, 92, 246, 0.02));
        border: 1px solid rgba(139, 92, 246, 0.15);
        border-left: 2px solid #8b5cf6;
        border-radius: 6px;
        padding: 0.75rem;
        margin-top: 1rem;
        display: none;
    }

    .custom-schedule-info .icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(139, 92, 246, 0.05));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #8b5cf6;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .custom-schedule-info h6 {
        color: #7c3aed;
        font-weight: 600;
        margin-bottom: 0.25rem;
        font-size: 0.85rem;
    }

    .custom-schedule-info p {
        color: #6b7280;
        margin: 0;
        line-height: 1.4;
        font-size: 0.8rem;
    }

    /* Input Group */
    .input-group {
        display: flex;
        align-items: stretch;
    }

    .input-group .form-control {
        flex: 1;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .input-group-text {
        display: flex;
        align-items: center;
        padding: 0.625rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        background-color: #f9fafb;
        border: 1.5px solid #d1d5db;
        border-left: none;
        border-radius: 0 6px 6px 0;
    }

    /* Error States */
    .form-control.error {
        border-color: #ef4444;
        background: linear-gradient(to right, rgba(239, 68, 68, 0.02), rgba(239, 68, 68, 0.01));
    }

    .error-message {
        font-size: 0.75rem;
        color: #ef4444;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .error-message i {
        font-size: 0.7rem;
    }

    /* Form Actions */
    .form-footer {
        background: #fff;
        border-radius: 10px;
        padding: 1rem;
        border: 1px solid #e5e7eb;
        margin-top: 1rem;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    /* Compact Header */
    .compact-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .compact-header h1 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-section {
            padding: 1.25rem;
        }
        
        .time-inputs {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
        
        .time-slot-container {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
        
        .time-separator {
            height: auto;
            padding: 0;
            justify-content: center;
            transform: rotate(90deg);
            margin: 0.25rem 0;
        }
        
        .form-footer {
            flex-direction: column;
        }
        
        .form-footer .btn {
            width: 100%;
        }
    }

    /* Button Styles */
    .btn {
        padding: 0.625rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.15s ease;
        border: none;
        cursor: pointer;
        font-family: inherit;
    }


    .btn-outline-secondary {
        background: white;
        color: #374151;
        border: 1.5px solid #d1d5db;
    }

    .btn-outline-secondary:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-soft);
    }

    /* Full width for specific groups */
    .form-group.col-span-2 {
        grid-column: span 2;
    }

    @media (max-width: 768px) {
        .form-group.col-span-2 {
            grid-column: span 1;
        }
    }
</style>

<div class="pb-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold">Create New Chamber</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.chambers.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <form method="POST" action="{{ route('admin.chambers.store') }}" id="chamberForm">
            @csrf
            
            <!-- Chamber Basics -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-building"></i>
                    Chamber Information
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">
                            Chamber Name <span class="required-star">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               class="form-control @error('name') error @enderror" 
                               value="{{ old('name') }}" 
                               placeholder="e.g., Main Clinic"
                               required>
                        <div class="form-hint">Enter a descriptive name for this chamber</div>
                        @error('name')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Consultation Fee <span class="required-star">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   name="fees" 
                                   class="form-control @error('fees') error @enderror" 
                                   value="{{ old('fees') }}" 
                                   min="0" 
                                   step="0.01" 
                                   placeholder="0.00"
                                   required>
                            <span class="input-group-text">৳</span>
                        </div>
                        <div class="form-hint">Consultation fee in Bangladeshi Taka</div>
                        @error('fees')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-group col-span-2">
                        <label class="form-label">
                            Address <span class="required-star">*</span>
                        </label>
                        <textarea name="address" 
                                  class="form-control @error('address') error @enderror" 
                                  rows="2"
                                  placeholder="Enter complete chamber address"
                                  required>{{ old('address') }}</textarea>
                        <div class="form-hint">Full address including building, floor, and landmarks</div>
                        @error('address')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            City <span class="required-star">*</span>
                        </label>
                        <input type="text" 
                               name="city" 
                               class="form-control @error('city') error @enderror" 
                               value="{{ old('city') }}" 
                               placeholder="e.g., Dhaka"
                               required>
                        @error('city')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Contact Number</label>
                        <input type="tel" 
                               name="phone" 
                               class="form-control @error('phone') error @enderror" 
                               value="{{ old('phone') }}" 
                               placeholder="+880 1XXX-XXXXXX">
                        <div class="form-hint">Optional contact number for this chamber</div>
                        @error('phone')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Schedule Configuration -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-calendar-alt"></i>
                    Schedule Configuration
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        Schedule Type <span class="required-star">*</span>
                    </label>
                    <div class="schedule-type-cards">
                        <div class="schedule-type-card {{ old('type', 'fixed') == 'fixed' ? 'selected' : '' }}" 
                             onclick="selectScheduleType('fixed')">
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <input type="radio" 
                                   name="type" 
                                   value="fixed" 
                                   id="type-fixed" 
                                   {{ old('type', 'fixed') == 'fixed' ? 'checked' : '' }} 
                                   required>
                            <h6>Fixed Weekly Schedule</h6>
                            <p>Set regular working days and hours each week</p>
                        </div>
                        
                        <div class="schedule-type-card {{ old('type') == 'custom' ? 'selected' : '' }}" 
                             onclick="selectScheduleType('custom')">
                            <div class="icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <input type="radio" 
                                   name="type" 
                                   value="custom" 
                                   id="type-custom" 
                                   {{ old('type') == 'custom' ? 'checked' : '' }}>
                            <h6>Custom Dates</h6>
                            <p>Add specific dates and times for appointments</p>
                        </div>
                    </div>
                    @error('type')
                        <div class="error-message" style="margin-top: 0.5rem;">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Fixed Schedule Section -->
                <div id="fixed-schedule" style="display: {{ old('type', 'fixed') == 'fixed' ? 'block' : 'none' }}; margin-top: 1rem;">
                    <div class="info-alert">
                        <i class="fas fa-info-circle"></i>
                        <p>Enable the days when you are available and set your working hours. You can also adjust appointment slot durations.</p>
                    </div>
                    
                    <div class="day-schedule-grid">
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                        @php
                            $oldEnabled = old("schedule.$day.enabled", $day != 'sunday' && $day != 'saturday');
                        @endphp
                        <div class="day-schedule-card {{ $oldEnabled ? 'enabled' : '' }}" id="day-card-{{ $day }}">
                            <div class="day-schedule-header">
                                <h6 class="day-label">
                                    {{ ucfirst($day) }}
                                </h6>
                                <label class="toggle-switch">
                                    <input type="hidden" name="schedule[{{ $day }}][enabled]" value="0">
                                    <input type="checkbox" 
                                           name="schedule[{{ $day }}][enabled]"
                                           class="day-toggle"
                                           value="1"
                                           id="toggle-{{ $day }}"
                                           {{ $oldEnabled ? 'checked' : '' }}
                                           onchange="toggleDaySchedule('{{ $day }}')">
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="time-inputs">
                                <div class="time-input-group">
                                    <label>Start</label>
                                    <input type="time" 
                                           name="schedule[{{ $day }}][start_time]"
                                           class="time-input"
                                           value="{{ old("schedule.$day.start_time", '09:00') }}"
                                           id="start-{{ $day }}"
                                           {{ !$oldEnabled ? 'disabled' : '' }}
                                           onchange="validateTime('{{ $day }}')">
                                </div>

                                <div class="time-separator">
                                    <i class="fas fa-arrow-right"></i>
                                </div>

                                <div class="time-input-group">
                                    <label>End</label>
                                    <input type="time" 
                                           name="schedule[{{ $day }}][end_time]"
                                           class="time-input"
                                           value="{{ old("schedule.$day.end_time", '17:00') }}"
                                           id="end-{{ $day }}"
                                           {{ !$oldEnabled ? 'disabled' : '' }}
                                           onchange="validateTime('{{ $day }}')">
                                </div>
                            </div>

                            <div class="time-slot-container">
                                <div class="time-input-group">
                                    <label>Slot (min)</label>
                                    <input type="number" 
                                           name="schedule[{{ $day }}][slot_duration]"
                                           class="time-input"
                                           value="{{ old("schedule.$day.slot_duration", 30) }}"
                                           min="15" 
                                           max="120" 
                                           step="15"
                                           id="slot-{{ $day }}"
                                           {{ !$oldEnabled ? 'disabled' : '' }}
                                           placeholder="30">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Custom Schedule Info -->
                <div id="custom-schedule-info" class="custom-schedule-info" style="display: {{ old('type') == 'custom' ? 'block' : 'none' }}">
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h6>Custom Schedule Selected</h6>
                    <p>For chambers with custom schedules, you can add specific dates and times after creating the chamber.</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-footer">
                <a href="{{ route('admin.chambers.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary" id="submit-btn">
                    <i class="fas fa-plus-circle me-2"></i>Create Chamber
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('chamberForm');
    
    // Initialize day schedules
    initializeDaySchedules();
    
    // Auto-enable weekdays by default
    autoEnableWeekdays();
    
    // Setup form submission
    setupFormSubmission();
});

function initializeDaySchedules() {
    document.querySelectorAll('.day-toggle').forEach(toggle => {
        const day = toggle.id.replace('toggle-', '');
        toggleDaySchedule(day);
    });
}

function autoEnableWeekdays() {
    const weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
    weekdays.forEach(day => {
        const toggle = document.getElementById(`toggle-${day}`);
        if (toggle && !toggle.checked) {
            toggle.checked = true;
            toggleDaySchedule(day);
        }
    });
}

function selectScheduleType(type) {
    // Update radio button
    document.getElementById(`type-${type}`).checked = true;
    
    // Update card selection
    document.querySelectorAll('.schedule-type-card').forEach(card => {
        card.classList.remove('selected');
    });
    event.currentTarget.classList.add('selected');
    
    // Show/hide sections
    if (type === 'fixed') {
        document.getElementById('fixed-schedule').style.display = 'block';
        document.getElementById('custom-schedule-info').style.display = 'none';
    } else {
        document.getElementById('fixed-schedule').style.display = 'none';
        document.getElementById('custom-schedule-info').style.display = 'block';
    }
}

function toggleDaySchedule(day) {
    const toggle = document.getElementById(`toggle-${day}`);
    const startInput = document.getElementById(`start-${day}`);
    const endInput = document.getElementById(`end-${day}`);
    const slotInput = document.getElementById(`slot-${day}`);
    const dayCard = document.getElementById(`day-card-${day}`);
    
    const isEnabled = toggle.checked;
    
    // Enable/disable inputs
    [startInput, endInput, slotInput].forEach(input => {
        input.disabled = !isEnabled;
        input.required = isEnabled;
    });
    
    // Update card appearance
    if (isEnabled) {
        dayCard.classList.add('enabled');
        // Set default values if empty
        if (!startInput.value) startInput.value = '09:00';
        if (!endInput.value) endInput.value = '17:00';
        if (!slotInput.value) slotInput.value = '30';
    } else {
        dayCard.classList.remove('enabled');
    }
}

function validateTime(day) {
    const startInput = document.getElementById(`start-${day}`);
    const endInput = document.getElementById(`end-${day}`);
    
    // Clear any existing errors
    startInput.classList.remove('error');
    endInput.classList.remove('error');
    const errorDiv = startInput.parentNode.querySelector('.time-error');
    if (errorDiv) {
        errorDiv.remove();
    }
    
    if (startInput.value && endInput.value) {
        const startTime = new Date(`2000-01-01T${startInput.value}`);
        const endTime = new Date(`2000-01-01T${endInput.value}`);
        
        if (startTime >= endTime) {
            startInput.classList.add('error');
            endInput.classList.add('error');
            
            // Show error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message time-error';
            errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> End time must be after start time';
            startInput.parentNode.appendChild(errorDiv);
            return false;
        }
    }
    return true;
}

function setupFormSubmission() {
    const form = document.getElementById('chamberForm');
    const submitBtn = document.getElementById('submit-btn');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate schedule type
        const scheduleType = document.querySelector('input[name="type"]:checked');
        if (!scheduleType) {
            alert('Please select a schedule type');
            return false;
        }
        
        if (scheduleType.value === 'fixed') {
            // Check if at least one day is enabled
            let hasEnabledDay = false;
            let timeErrors = false;
            
            document.querySelectorAll('.day-toggle').forEach(toggle => {
                if (toggle.checked) {
                    hasEnabledDay = true;
                    const day = toggle.id.replace('toggle-', '');
                    if (!validateTime(day)) {
                        timeErrors = true;
                    }
                }
            });
            
            if (!hasEnabledDay) {
                alert('Please enable at least one day for fixed schedule');
                return false;
            }
            
            if (timeErrors) {
                alert('Please fix time validation errors before submitting');
                return false;
            }
        }
        
        // Show loading state
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Creating...';
        submitBtn.disabled = true;
        
        // Submit the form
        form.submit();
        
        // Re-enable after 5 seconds if submission fails
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
}
</script>

@if($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Scroll to first error
    const firstError = document.querySelector('.form-control.error');
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        firstError.focus();
    }
});
</script>
@endif
@endsection