@extends('layouts.supperadmin')
@section('title', 'Create New Package')

@section('content')
<style>
    /* Reuse the same compact form styles */
    .form-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }
    
    .form-header {
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .form-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }
    
    .form-title i {
        color: #318069;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: 7fr 5fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-control {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #318069;
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
    }
    
    .toggle-container {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    
    /* Fixed Toggle Switch */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
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
        background-color: #e5e7eb;
        transition: .4s;
        border-radius: 34px;
    }
    
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .toggle-slider {
        background-color: #318069;
    }
    
    input:checked + .toggle-slider:before {
        transform: translateX(24px);
    }
    
    .toggle-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        flex: 1;
    }
    
    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
        margin-top: 1.5rem;
    }
    
    .btn {
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    
    .btn-secondary {
        background: white;
        border-color: #e5e7eb;
        color: #374151;
    }
    
    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }
    
    .btn-primary {
        background: #318069;
        color: white;
        border: none;
    }
    
    .btn-primary:hover {
        background: #2a6d5a;
        transform: translateY(-1px);
    }
    
    /* Error Styling */
    .error-message {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .feature-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.9rem 1rem;
        background: #fff;
    }

    .feature-card label {
        font-weight: 600;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .feature-card p {
        margin: 0.4rem 0 0;
        font-size: 0.78rem;
        color: #6b7280;
    }
</style>

<div class="pb-3">
    <div class="form-card">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-plus"></i>
                Create New Package
            </h1>
        </div>

        <form action="{{ route('packages.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <!-- Left Column -->
                <div>
                    <div class="form-group">
                        <label class="form-label" for="name">Package Name</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="form-control" 
                               value="{{ old('name') }}" 
                               placeholder="e.g., Professional Plan" 
                               required>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="description">Description</label>
                        <textarea id="description" 
                                  name="description" 
                                  class="form-control summernote" 
                                  rows="3" 
                                  placeholder="Brief description...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="toggle-container">
                        <div class="toggle-switch">
                            <input type="checkbox" 
                                   id="is_visible" 
                                   name="is_visible" 
                                   value="1" 
                                   {{ old('is_visible') ? 'checked' : 'checked' }}>
                            <span class="toggle-slider"></span>
                        </div>
                        <label for="is_visible" class="toggle-label">
                            Make package visible to users
                        </label>
                    </div>
                    @error('is_visible')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <div class="form-group mt-3">
                        <label class="form-label">Doctor Dashboard Permissions</label>
                        <div class="feature-grid">
                            @php
                                $featureLabels = [
                                    'doctor' => ['title' => 'Doctor', 'text' => 'Dashboard and doctor profile access'],
                                    'appointments' => ['title' => 'Appointments', 'text' => 'Appointment list, calendar, and status updates'],
                                    'patients' => ['title' => 'Patients', 'text' => 'Patient list, profiles, records, and prescriptions'],
                                    'services' => ['title' => 'Services', 'text' => 'Chambers, telemedicine, and billing sections'],
                                    'content' => ['title' => 'Content', 'text' => 'Posts, testimonials, FAQs, and AI content'],
                                ];
                            @endphp

                            @foreach($featureLabels as $featureKey => $featureMeta)
                                <div class="feature-card">
                                    <label for="feature_{{ $featureKey }}">
                                        <input
                                            type="checkbox"
                                            id="feature_{{ $featureKey }}"
                                            name="features[{{ $featureKey }}]"
                                            value="1"
                                            {{ old("features.$featureKey", true) ? 'checked' : '' }}
                                        >
                                        {{ $featureMeta['title'] }}
                                    </label>
                                    <p>{{ $featureMeta['text'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="form-group">
                        <label class="form-label" for="price_monthly">Monthly Price (USD Base)</label>
                        <input type="number" 
                               id="price_monthly" 
                               name="price_monthly" 
                               class="form-control" 
                               value="{{ old('price_monthly') }}" 
                               step="0.01" 
                               min="0" 
                               placeholder="0.00" 
                               required>
                        @error('price_monthly')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="price_yearly">Yearly Price (USD Base)</label>
                        <input type="number" 
                               id="price_yearly" 
                               name="price_yearly" 
                               class="form-control" 
                               value="{{ old('price_yearly') }}" 
                               step="0.01" 
                               min="0" 
                               placeholder="0.00">
                        @error('price_yearly')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="storage_gb">Storage (GB)</label>
                        <input type="number" 
                               id="storage_gb" 
                               name="storage_gb" 
                               class="form-control" 
                               value="{{ old('storage_gb', 10) }}" 
                               min="1" 
                               placeholder="10" 
                               required>
                        @error('storage_gb')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="max_doctors">Max Doctors (Optional)</label>
                        <input type="number" 
                               id="max_doctors" 
                               name="max_doctors" 
                               class="form-control" 
                               value="{{ old('max_doctors') }}" 
                               min="1" 
                               placeholder="Leave empty for unlimited">
                        @error('max_doctors')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="max_patients">Max Patients (Optional)</label>
                        <input type="number" 
                               id="max_patients" 
                               name="max_patients" 
                               class="form-control" 
                               value="{{ old('max_patients') }}" 
                               min="1" 
                               placeholder="Leave empty for unlimited">
                        @error('max_patients')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('packages.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>
                    Create Package
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
