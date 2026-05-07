@extends('layouts.admin')
@section('title', 'Edit Chamber - ' . $chamber->name)

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

    /* Status Badge */
    .badge {
        display: inline-block;
        padding: 0.25rem 0.625rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 4px;
        border: 1px solid transparent;
    }

    .badge-success {
        background-color: #d1fae5;
        color: #065f46;
        border-color: #a7f3d0;
    }

    .badge-danger {
        background-color: #fee2e2;
        color: #991b1b;
        border-color: #fecaca;
    }

    .badge-info {
        background-color: #e0f2fe;
        color: #075985;
        border-color: #bae6fd;
    }

    .badge-warning {
        background-color: #fef3c7;
        color: #92400e;
        border-color: #fde68a;
    }

    /* Schedule Type Display */
    .schedule-type-display {
        background: #f9fafb;
        border: 1.5px solid #e5e7eb;
        border-radius: 6px;
        padding: 0.75rem;
        margin-top: 0.5rem;
    }

    .schedule-type-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .schedule-type-icon {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        background: linear-gradient(135deg, var(--primary-soft), var(--primary-light));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 0.9rem;
    }

    .schedule-type-text h6 {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin: 0 0 0.125rem 0;
    }

    .schedule-type-text p {
        font-size: 0.75rem;
        color: #6b7280;
        margin: 0;
    }

    /* Fixed Schedule Grid */
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

    .day-schedule-card.active {
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

    /* Custom Schedule Stats */
    .custom-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
        margin-top: 1rem;
    }

    @media (max-width: 640px) {
        .custom-stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .stat-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 1rem;
        text-align: center;
    }

    .stat-number {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #6b7280;
        margin: 0;
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

    /* Warning Alert */
    .warning-alert {
        background: linear-gradient(to right, rgba(245, 158, 11, 0.05), rgba(245, 158, 11, 0.02));
        border: 1px solid rgba(245, 158, 11, 0.15);
        border-left: 2px solid #f59e0b;
        border-radius: 6px;
        padding: 0.75rem;
        margin-top: 1rem;
        display: flex;
        gap: 0.5rem;
        align-items: flex-start;
    }

    .warning-alert i {
        color: #f59e0b;
        font-size: 0.875rem;
        margin-top: 0.125rem;
        flex-shrink: 0;
    }

    .warning-alert p {
        margin: 0;
        color: #374151;
        font-size: 0.8rem;
        line-height: 1.4;
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
        justify-content: space-between;
        align-items: center;
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

    /* Switch */
    .form-check {
        margin: 0;
    }

    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .form-check-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(49, 128, 105, 0.25);
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

    .btn-outline-danger {
        background: white;
        color: #ef4444;
        border: 1.5px solid #ef4444;
    }

    .btn-outline-danger:hover {
        background: #ef4444;
        color: white;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }

    /* Full width for specific groups */
    .form-group.col-span-2 {
        grid-column: span 2;
    }

    @media (max-width: 768px) {
        .form-group.col-span-2 {
            grid-column: span 1;
        }
        
        .form-footer {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .form-footer .btn {
            width: 100%;
        }
    }

    /* Alerts */
    .alert {
        border-radius: 6px;
        border: none;
        margin-bottom: 1rem;
    }

    .alert-success {
        background: linear-gradient(to right, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
        border-left: 3px solid #10b981;
        color: #065f46;
    }

    .alert-danger {
        background: linear-gradient(to right, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
        border-left: 3px solid #ef4444;
        color: #991b1b;
    }
</style>

<div class="pb-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold">Edit Chamber</h1>
            <!-- <p class="text-muted small mb-0">ID: CH{{ str_pad($chamber->id, 3, '0', STR_PAD_LEFT) }}</p> -->
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.chambers.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2"></i>
                <div>
                    <h6 class="mb-1">Please fix the following errors:</h6>
                    <ul class="mb-0 ps-3" style="font-size: 0.875rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Container -->
    <div class="form-container">
        <form method="POST" action="{{ route('admin.chambers.update', $chamber) }}" id="chamberForm">
            @csrf
            @method('PUT')
            
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
                               value="{{ old('name', $chamber->name) }}" 
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
                                   value="{{ old('fees', $chamber->fees) }}" 
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
                                  required>{{ old('address', $chamber->address) }}</textarea>
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
                               value="{{ old('city', $chamber->city) }}" 
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
                               value="{{ old('phone', $chamber->phone) }}" 
                               placeholder="+880 1XXX-XXXXXX">
                        <div class="form-hint">Optional contact number for this chamber</div>
                        @error('phone')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Schedule Type</label>
                        <div class="schedule-type-display">
                            <div class="schedule-type-content">
                                <div class="schedule-type-icon">
                                    <i class="fas fa-{{ $chamber->type === 'fixed' ? 'clock' : 'calendar-day' }}"></i>
                                </div>
                                <div class="schedule-type-text">
                                    <h6>{{ ucfirst($chamber->type) }} Schedule</h6>
                                    <p>Schedule type cannot be changed after creation</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div class="d-flex align-items-center gap-2">
                            <div class="form-check form-switch">
                                <input type="checkbox" 
                                       name="is_active" 
                                       class="form-check-input" 
                                       id="is_active" 
                                       value="1"
                                       {{ old('is_active', $chamber->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Chamber
                                </label>
                            </div>
                            <span class="badge {{ $chamber->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $chamber->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="form-hint">Active chambers accept appointments</div>
                    </div>
                </div>
            </div>

            <!-- Schedule Section -->
            @if($chamber->type === 'fixed')
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-calendar-alt"></i>
                    Fixed Schedule
                    <span class="badge bg-light text-dark ms-2" style="border: 1px solid #d1d5db;">Read Only</span>
                </div>
                
                <div class="info-alert">
                    <i class="fas fa-info-circle"></i>
                    <p>Fixed schedule cannot be modified. To change the schedule, create a new chamber.</p>
                </div>
                
                <div class="day-schedule-grid">
                    @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                    @php
                        $schedule = $chamber->schedule[$day] ?? [
                            'enabled' => false,
                            'start_time' => '09:00',
                            'end_time' => '17:00',
                            'slot_duration' => 30
                        ];
                        $isEnabled = $schedule['enabled'] ?? false;
                    @endphp
                    <div class="day-schedule-card {{ $isEnabled ? 'active' : '' }}">
                        <div class="day-schedule-header">
                            <h6 class="day-label">
                                {{ ucfirst($day) }}
                            </h6>
                            <span class="badge {{ $isEnabled ? 'badge-success' : 'badge-secondary' }}">
                                {{ $isEnabled ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        @if($isEnabled)
                        <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 0.5rem; align-items: end;">
                            <div>
                                <label class="form-label" style="font-size: 0.7rem;">Start</label>
                                <div class="form-control" style="background: #f9fafb; border-color: #e5e7eb; color: #374151; padding: 0.375rem 0.5rem; font-size: 0.8rem;">
                                    {{ \Carbon\Carbon::parse($schedule['start_time'])->format('h:i A') }}
                                </div>
                            </div>
                            
                            <div style="display: flex; align-items: center; justify-content: center; height: 30px; padding-bottom: 1rem; color: #9ca3af;">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            
                            <div>
                                <label class="form-label" style="font-size: 0.7rem;">End</label>
                                <div class="form-control" style="background: #f9fafb; border-color: #e5e7eb; color: #374151; padding: 0.375rem 0.5rem; font-size: 0.8rem;">
                                    {{ \Carbon\Carbon::parse($schedule['end_time'])->format('h:i A') }}
                                </div>
                            </div>
                        </div>
                        
                        <div style="margin-top: 0.5rem;">
                            <label class="form-label" style="font-size: 0.7rem;">Slot Duration</label>
                            <div class="form-control" style="background: #f9fafb; border-color: #e5e7eb; color: #374151; padding: 0.375rem 0.5rem; font-size: 0.8rem;">
                                {{ $schedule['slot_duration'] ?? 30 }} minutes
                            </div>
                        </div>
                        @else
                        <div class="text-center py-2">
                            <div style="color: #9ca3af; font-size: 0.8rem;">
                                <i class="fas fa-calendar-times mb-1" style="font-size: 1rem;"></i>
                                <div>Not Available</div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-calendar-day"></i>
                    Custom Schedule Management
                </div>
                
                <div class="custom-stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">{{ $chamber->customDates->count() }}</div>
                        <div class="stat-label">Total Dates</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number">{{ $chamber->customDates->where('is_active', true)->count() }}</div>
                        <div class="stat-label">Active Dates</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number">{{ $chamber->customDates->where('date', '>=', today())->count() }}</div>
                        <div class="stat-label">Upcoming</div>
                    </div>
                </div>
                
                <div class="info-alert">
                    <i class="fas fa-info-circle"></i>
                    <p>For custom schedule chambers, you need to manage individual dates separately.</p>
                </div>
                
                <div class="text-center">
                    <a href="{{ route('admin.chambers.custom-dates', $chamber) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-calendar-edit me-2"></i>Manage Custom Dates
                    </a>
                </div>
            </div>
            @endif

            <!-- Form Actions -->
            <div class="form-footer">
                <div>
                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-2"></i>Delete Chamber
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.chambers.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <i class="fas fa-save me-2"></i>Update Chamber
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete Chamber
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                </div>
                
                <h6 class="fw-semibold text-center mb-3">Are you sure you want to delete <span class="text-danger">"{{ $chamber->name }}"</span>?</h6>
                
                <div style="background: #fef2f2; border-radius: 6px; padding: 1rem; margin-bottom: 1rem;">
                    <div style="font-size: 0.875rem; color: #991b1b;">
                        <div class="mb-1"><strong>This will permanently delete:</strong></div>
                        <div class="ps-2">
                            <div>• {{ $chamber->appointments->count() }} appointments</div>
                            @if($chamber->type === 'custom')
                            <div>• {{ $chamber->customDates->count() }} custom dates</div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="warning-alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <p><strong>This action cannot be undone.</strong> All associated data will be permanently deleted.</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.chambers.destroy', $chamber) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Chamber
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('chamberForm');
    const submitBtn = document.getElementById('submit-btn');
    
    // Format fees input on blur
    const feesInput = form.querySelector('input[name="fees"]');
    if (feesInput) {
        feesInput.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    }
    
    // Form submission
    form.addEventListener('submit', function(e) {
        const name = form.querySelector('input[name="name"]').value.trim();
        const fees = form.querySelector('input[name="fees"]').value;
        const address = form.querySelector('textarea[name="address"]').value.trim();
        const city = form.querySelector('input[name="city"]').value.trim();
        
        if (!name) {
            e.preventDefault();
            alert('Please enter a chamber name.');
            return false;
        }
        
        if (!fees || parseFloat(fees) < 0) {
            e.preventDefault();
            alert('Please enter a valid consultation fee.');
            return false;
        }
        
        if (!address) {
            e.preventDefault();
            alert('Please enter the chamber address.');
            return false;
        }
        
        if (!city) {
            e.preventDefault();
            alert('Please enter the city.');
            return false;
        }
        
        // Show loading state
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Updating...';
        submitBtn.disabled = true;
        
        // Re-enable after 5 seconds if submission fails
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
    
    // Auto-dismiss alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
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