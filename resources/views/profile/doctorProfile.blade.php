@extends('layouts.admin')

@section('title', 'Edit Doctor Profile')

@section('content')
<style>
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.1);
        --primary-dark: #2a6d5a;
        --primary-soft: rgba(49, 128, 105, 0.05);
        --primary-hover: rgba(49, 128, 105, 0.15);
    }

    .doctor-form{
        position : relative;
    }

    /* Tab Navigation */
    .profile-tabs-wrapper {
        position: relative;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .profile-tabs {
        display: flex;
        gap: 0.5rem;
        background: white;
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        padding: 1rem;
        overflow-x: scroll;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .profile-tabs::-webkit-scrollbar {
        display: none;
    }

    .profile-tab {
    padding: 0.65rem 1.3rem;
    border-radius: 35px;
    font-size: 0.75rem;
    font-weight: 500;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
    border: none;
    background: var(--primary-soft);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-shrink: 0;
}

    .profile-tab:hover {
        background: rgba(49, 128, 105, 0.2);
        color: var(--primary);
    }

    .profile-tab.active {
        background: var(--primary);
        color: white;
        font-weight: 600;
    }

    .tab-icon {
        font-size: 1rem;
    }

    /* Tab Content */
    .tab-content {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Form Cards */
    .form-section-card {
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        background: white;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .form-section-header {
        background: var(--primary-soft);
        border-bottom: 2px solid var(--primary-light);
        padding: 1.25rem 1.5rem;
        font-weight: 600;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-section-header-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .form-section-body {
        padding: 1.5rem;
    }

    /* Form Controls */


    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        border: 1px solid rgba(49, 128, 105, 0.2);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    select.form-control {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23318069' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 16px 12px;
        padding-right: 3rem;
    }

    select.form-control[multiple] {
        background-image: none;
        padding-right: 1rem;
        min-height: 150px;
    }

    .form-hint {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.375rem;
    }

    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    /* Checkbox Group */
    .checkbox-group {
        display: flex;
        gap: .5rem;
        flex-wrap: wrap;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .form-check-input {
        width: 20px;
        height: 20px;
        border-radius: 4px;
        border: 1px solid rgba(49, 128, 105, 0.3);
        background: white;
        cursor: pointer;
        position: relative;
    }

    .form-check-input:checked {
        background: var(--primary);
        border-color: var(--primary);
    }

    .form-check-input:checked::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 0.875rem;
        font-weight: bold;
    }

    /* Multi-select styles */
    .multi-select-option {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .multi-select-option:hover {
        background: var(--primary-soft);
    }

    .multi-select-option.selected {
        background: var(--primary);
        color: white;
    }

    .multi-select-option input[type="checkbox"] {
        width: 18px;
        height: 18px;
    }

    .multi-select-container {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid rgba(49, 128, 105, 0.2);
        border-radius: 8px;
        padding: 0.75rem;
        background: white;
    }

    /* Dynamic Items */
    .dynamic-item-card {
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        background: white;
    }

    .card-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--primary-soft);
    }

    .card-title {
        font-weight: 600;
        color: var(--primary);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .remove-btn {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, 0.2);
        padding: 0.375rem 1rem;
        border-radius: 6px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .remove-btn:hover {
        background: #dc2626;
        color: white;
    }

    .add-btn {
        background: var(--primary-light);
        color: var(--primary);
        border: 1px solid rgba(49, 128, 105, 0.2);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .add-btn:hover {
        background: var(--primary);
        color: white;
    }

    /* Map Container */
    .map-container {
        width: 100%;
        height: 400px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid rgba(49, 128, 105, 0.2);
        margin-bottom: 1.5rem;
    }

    #location-map {
        width: 100%;
        height: 100%;
    }

    /* Leaflet Custom Styles */
    .leaflet-container {
        font-family: inherit;
        font-size: 0.875rem;
    }

    .leaflet-control-zoom {
        border: none !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
        border-radius: 8px !important;
        overflow: hidden;
    }

    .leaflet-control-zoom a {
        width: 36px !important;
        height: 36px !important;
        line-height: 36px !important;
        background: white !important;
        color: #374151 !important;
        border: none !important;
        transition: all 0.2s ease;
    }

    .leaflet-control-zoom a:hover {
        background: var(--primary-soft) !important;
        color: var(--primary) !important;
    }

    .leaflet-control-zoom-in {
        border-bottom: 1px solid #e5e7eb !important;
    }

    /* Theme Selection */
    .theme-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .theme-card {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        position: relative;
    }

    .theme-card:hover {
        border-color: var(--primary-light);
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .theme-card.selected {
        border-color: var(--primary);
        background: var(--primary-soft);
    }

    .theme-card.selected::after {
        content: '✓';
        position: absolute;
        top: 15px;
        right: 15px;
        width: 30px;
        height: 30px;
        background: var(--primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1rem;
    }

    .theme-thumbnail {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-bottom: 1px solid #e5e7eb;
    }

    .theme-content {
        padding: 1.25rem;
    }

    .theme-title {
        font-weight: 600;
        font-size: 1rem;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .theme-description {
        font-size: 0.875rem;
        color: #6b7280;
        line-height: 1.5;
    }

    /* Hide radio buttons and select text */
    .theme-radio .form-check-label {
        display: none !important;
    }

    /* Password Fields */
    .password-field {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6b7280;
        cursor: pointer;
        padding: 0.25rem;
    }

    /* AI Suggestions */
    .use-ai-btn {
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.75rem 1.25rem;
        font-size: 0.875rem;
        cursor: pointer;
        flex-shrink: 0;
    }

    .use-ai-btn:hover {
        background: var(--primary-dark);
    }

    .use-ai-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
    }

    .use-ai-btn.active {
        background: #10b981;
    }

    /* AI Modal Styles */
    .ai-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        animation: fadeIn 0.3s ease;
        overflow-y: auto;
    }

    .ai-modal-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .ai-modal-header {
        padding: 1.25rem 1.5rem;
        background: var(--primary);
        color: white;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    .ai-modal-title {
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .ai-modal-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.875rem;
    }

    .ai-modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .ai-modal-body {
        padding: 1.25rem 1.5rem;
        flex: 1;
        overflow-y: auto;
    }

    .ai-modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    .ai-loading {
        text-align: center;
        padding: 2rem 1.5rem;
    }

    .ai-loading .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid var(--primary-light);
        border-top: 3px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 1.5rem;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .suggestion-field {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .suggestion-field-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .suggestion-field-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
    }

    .suggestion-field-text {
        font-size: 0.875rem;
        color: #4b5563;
        line-height: 1.5;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .ai-tips {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        padding: 0.75rem;
        margin-top: 1rem;
    }

    .ai-tips-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #0369a1;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .ai-tips-list {
        font-size: 0.75rem;
        color: #0c4a6e;
        margin: 0;
        padding-left: 1.25rem;
    }

    .ai-tips-list li {
        margin-bottom: 0.25rem;
    }

    /* Image Upload */
    .image-upload-area {
        border: 2px dashed rgba(49, 128, 105, 0.3);
        border-radius: 8px;
        padding: 2.5rem 2rem;
        text-align: center;
        background: var(--primary-soft);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .image-upload-area:hover {
        border-color: var(--primary);
        background: var(--primary-light);
    }

    .image-preview {
        width: 150px;
        height: 150px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid rgba(49, 128, 105, 0.2);
        margin-bottom: 1rem;
    }

    .form-progress {
            width: 100%;
            z-index: 2;
            padding: 14px 18px;
            background-color: #fff;
            border-radius: 7px;
            position: sticky;
            bottom: 15px;
            left: 0;
            width: 100%;
            text-align: end;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px;
    }

    .progress-bar-container {
        width: 100% !important;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .progress-stats {
        font-size: 0.875rem;
        color: #64748b;
    }

    .progress-actions {
        display: flex;
        gap: 1rem;
    }

    /* Buttons */
    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        border: 1px solid transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .btn-primary:hover {
        background: var(--primary-dark);
    }

    .btn-primary:disabled {
        background: #ccc;
        border-color: #ccc;
        cursor: not-allowed;
    }

    .btn-primary-outline {
        background: transparent;
        color: var(--primary);
        border-color: rgba(49, 128, 105, 0.3);
    }

    .btn-primary-outline:hover {
        background: var(--primary);
        color: white;
    }

    .btn-primary-outline:disabled {
        color: #ccc;
        border-color: #ccc;
        cursor: not-allowed;
    }

    .btn-success {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }

    .btn-success:hover {
        background: #059669;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
    }

    /* Input Groups */
    .input-group {
        display: flex;
        width: 100%;
    }

    .input-group .form-control {
        flex: 1;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-right: none;
    }

    .input-group-append {
        display: flex;
    }

    .input-group-append .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        height: 100%;
    }

    /* Location Search */
    .location-search-container {
        margin-bottom: 1.5rem;
    }

    .search-results {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid rgba(49, 128, 105, 0.2);
        border-radius: 8px;
        margin-top: 0.5rem;
        display: none;
    }

    .search-result-item {
        padding: 0.75rem 1rem;
        cursor: pointer;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .search-result-item:hover {
        background: var(--primary-soft);
    }

    .search-result-item:last-child {
        border-bottom: none;
    }

    .search-result-name {
        font-weight: 500;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .search-result-address {
        font-size: 0.75rem;
        color: #64748b;
    }

    /* Responsive */
    @media (max-width: 768px) {
         .profile-tab {
    font-size: 12px;
}
 .tab-icon {
        font-size: 12px;
    }

    
        .form-grid {
            grid-template-columns: 1fr;
        }
      .form-grid {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

        .checkbox-group {
            flex-direction: column;
            gap: .5rem;
        }

        .progress-bar-container {
            flex-direction: column;
            gap: 1rem;
        }

        .progress-actions {
            width: 100%;
        }

        .progress-actions .btn {
            flex: 1;
        }

        .ai-modal-content {
            width: 95%;
            max-height: 90vh;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .ai-modal-body {
            padding: 1rem;
        }

        .ai-modal-footer {
            padding: 0.75rem 1rem;
            flex-direction: column;
            gap: 0.75rem;
        }

        .ai-modal-footer .btn {
            width: 100%;
        }

        .theme-grid {
            grid-template-columns: 1fr;
        }

        .map-container {
            height: 300px;
        }

        .form-section-body{
            padding: 1rem;
        }
           .dynamic-item-card {
        padding: .9rem;
    }
     .card-header-row {
        margin-bottom: .2rem;
        padding-bottom: 1rem;
    }
    }
    
</style>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>

<div class="profile-edit-container">

    <!-- Tab Navigation -->
    <div class="profile-tabs-wrapper">
        <div class="profile-tabs" id="profileTabs">
            <button class="profile-tab active" data-tab="basic">
                <i class="fas fa-user tab-icon"></i>Basic Info
            </button>
            <button class="profile-tab" data-tab="location">
                <i class="fas fa-map-marker-alt tab-icon"></i>Location
            </button>
            <button class="profile-tab" data-tab="profile">
                <i class="fas fa-id-card tab-icon"></i>Profile
            </button>
            <button class="profile-tab" data-tab="education">
                <i class="fas fa-graduation-cap tab-icon"></i>Education
            </button>
            <button class="profile-tab" data-tab="experience">
                <i class="fas fa-briefcase tab-icon"></i>Experience
            </button>
            <button class="profile-tab" data-tab="certifications">
                <i class="fas fa-certificate tab-icon"></i>Certifications
            </button>
            <button class="profile-tab" data-tab="services">
                <i class="fas fa-clinic-medical tab-icon"></i>Services
            </button>
            <button class="profile-tab" data-tab="testimonials">
                <i class="fas fa-comment-medical tab-icon"></i>Testimonials
            </button>
            <button class="profile-tab" data-tab="affiliations">
                <i class="fas fa-handshake tab-icon"></i>Affiliations
            </button>
            <button class="profile-tab" data-tab="faqs">
                <i class="fas fa-question-circle tab-icon"></i>FAQs
            </button>
            <button class="profile-tab" data-tab="telemedicine">
                <i class="fas fa-video tab-icon"></i>Telemedicine
            </button>
            <button class="profile-tab" data-tab="gallery">
                <i class="fas fa-images tab-icon"></i>Gallery
            </button>
            {{-- <button class="profile-tab" data-tab="theme">
                <i class="fas fa-palette tab-icon"></i>Theme
            </button> --}}
            <button class="profile-tab" data-tab="privacy">
                <i class="fas fa-lock tab-icon"></i>Privacy
            </button>
        </div>
    </div>

    <form action="{{ route('admin.profile.update', $doctor->id) }}" method="POST" enctype="multipart/form-data" class="doctor-form" id="doctor-form">
        @csrf
        @method('PUT')

        <!-- Basic Information Tab -->
        <div class="tab-content active" id="basic-tab">
            <div class="form-section-card">
                <div class="form-section-header">
                    <div class="form-section-header-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <span>Basic Information</span>
                </div>
                <div class="form-section-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $doctor->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $doctor->email) }}" required readonly>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $doctor->mobile) }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Qualification *</label>
                            <select name="qualification" class="form-control" required>
                                <option value="">Select Qualification</option>
                                <option value="MBBS" {{ old('qualification', $doctor->qualification) == 'MBBS' ? 'selected' : '' }}>MBBS</option>
                                <option value="FCPS" {{ old('qualification', $doctor->qualification) == 'FCPS' ? 'selected' : '' }}>FCPS</option>
                                <option value="FRCS" {{ old('qualification', $doctor->qualification) == 'FRCS' ? 'selected' : '' }}>FRCS</option>
                                <option value="MD" {{ old('qualification', $doctor->qualification) == 'MD' ? 'selected' : '' }}>MD</option>
                                <option value="MS" {{ old('qualification', $doctor->qualification) == 'MS' ? 'selected' : '' }}>MS</option>
                                <option value="DNB" {{ old('qualification', $doctor->qualification) == 'DNB' ? 'selected' : '' }}>DNB</option>
                                <option value="MCh" {{ old('qualification', $doctor->qualification) == 'MCh' ? 'selected' : '' }}>MCh</option>
                                <option value="DM" {{ old('qualification', $doctor->qualification) == 'DM' ? 'selected' : '' }}>DM</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Registration Number</label>
                            <input type="text" name="reg_no" class="form-control" value="{{ old('reg_no', $doctor->reg_no) }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Country</label>
                            <select name="country" class="form-control">
                                <option value="">Select Country</option>
                                <option value="USA" {{ old('country', $doctor->country) == 'USA' ? 'selected' : '' }}>United States</option>
                                <option value="UK" {{ old('country', $doctor->country) == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                                <option value="Canada" {{ old('country', $doctor->country) == 'Canada' ? 'selected' : '' }}>Canada</option>
                                <option value="Australia" {{ old('country', $doctor->country) == 'Australia' ? 'selected' : '' }}>Australia</option>
                                <option value="India" {{ old('country', $doctor->country) == 'India' ? 'selected' : '' }}>India</option>
                                <option value="Pakistan" {{ old('country', $doctor->country) == 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                                <option value="Bangladesh" {{ old('country', $doctor->country) == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                                <option value="UAE" {{ old('country', $doctor->country) == 'UAE' ? 'selected' : '' }}>United Arab Emirates</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Rating</label>
                            <input type="number" step="0.1" min="0" max="5" name="rating" class="form-control" value="{{ old('rating', $doctor->rating) }}">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Location Tab -->
        <div class="tab-content" id="location-tab">
            <div class="form-section-card">
                <div class="form-section-header">
                    <div class="form-section-header-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <span>Location & Map</span>
                </div>
                <div class="form-section-body">
                    <div class="location-search-container">
                        <label class="form-label">Search Location</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="location-search-input" placeholder="Search for a city, address, or place...">
                            <div class="input-group-append">
                                <button class="btn btn-primary-outline" type="button" id="location-search-btn">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                        <div class="search-results" id="search-results"></div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Latitude</label>
                            <input type="text" name="latitude" class="form-control" id="latitude-input" value="{{ old('latitude', $doctor->latitude) }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Longitude</label>
                            <input type="text" name="longitude" class="form-control" id="longitude-input" value="{{ old('longitude', $doctor->longitude) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" id="address-input" value="{{ old('address', $doctor->address) }}">
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <label class="form-label">Select Location on Map</label>
                        <div class="map-container">
                            <div id="location-map"></div>
                        </div>
                        <small class="form-hint">Click on the map to set your location. Drag the marker to adjust.</small>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-primary-outline" onclick="getCurrentLocation()">
                            <i class="fas fa-location-arrow"></i> Use Current Location
                        </button>
                        <small class="form-hint ml-2">Allow location access in your browser</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Information Tab -->
        <div class="tab-content" id="profile-tab">
            <div class="form-section-card">
                <div class="form-section-header">
                    <div class="form-section-header-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <span>Profile Information</span>
                </div>
                <div class="form-section-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Headline</label>
                            <div class="input-group">
                                <input type="text" name="headline" class="form-control" id="headline-input"
                                       value="{{ old('headline', $doctor->profile->headline ?? '') }}"
                                       placeholder="Your Health, Our Priority">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary-outline" id="ai-generate-btn" title="Generate AI Content" disabled>
                                        <i class="fas fa-robot"></i> AI
                                    </button>
                                </div>
                            </div>
                            <small class="form-hint">Enter a headline to enable AI suggestions</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Subheadline</label>
                            <input type="text" name="subheadline" class="form-control" id="subheadline-input"
                                   value="{{ old('subheadline', $doctor->profile->subheadline ?? '') }}"
                                   placeholder="Dedicated to Your Wellbeing">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tagline</label>
                            <input type="text" name="tagline" class="form-control" id="tagline-input"
                                   value="{{ old('tagline', $doctor->profile->tagline ?? '') }}"
                                   placeholder="Professional Medical Care">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Medical Specialties</label>
                            <select name="medical_specialties[]" class="form-control" id="specialties-select" multiple>
                               @php
    $specialties = [
        'Cardiology','Hematology','Neurology','Pediatrics','Orthopedics',
        'Dermatology','Ophthalmology','Psychiatry','Gynecology','Urology',
        'Endocrinology','Gastroenterology','Nephrology','Pulmonology','Rheumatology',
        'Oncology','General Surgery','Plastic Surgery','ENT','Anesthesiology',
        'Radiology','Pathology','Emergency Medicine','Family Medicine',
        'Infectious Disease','Geriatrics','Sports Medicine',
        'Allergy & Immunology','Sleep Medicine','Palliative Care',
        'Occupational Medicine','Physical Medicine & Rehabilitation',
        'Vascular Surgery','Thoracic Surgery','Colorectal Surgery',
        'Transplant Surgery','Bariatric Surgery','Pediatric Surgery',
        'Neurosurgery','Interventional Radiology','Pain Medicine',
        'Addiction Medicine','Hyperbaric Medicine','Integrative Medicine',
        'Nuclear Medicine','Preventive Medicine','Public Health',
        'Medical Genetics','Clinical Pharmacology','Toxicology',
        'Forensic Medicine','Reproductive Endocrinology',
        'Maternal-Fetal Medicine','Pediatric Cardiology',
        'Pediatric Oncology','Pediatric Neurology'
    ];

    $selectedSpecialties = old(
        'medical_specialties',
        isset($doctor->specialization)
            ? json_decode($doctor->specialization, true)
            : []
    );
@endphp


                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty }}" {{ in_array($specialty, $selectedSpecialties) ? 'selected' : '' }}>{{ $specialty }}</option>
                                @endforeach
                            </select>
                            <small class="form-hint">Hold Ctrl/Cmd to select multiple specialties</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Years Experience</label>
                            <input type="number" name="years_experience" class="form-control"
                                   value="{{ old('years_experience', $doctor->profile->years_experience ?? '') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Patients Count</label>
                            <input type="number" name="patients_count" class="form-control"
                                   value="{{ old('patients_count', $doctor->profile->patients_count ?? '') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Satisfaction Rate (%)</label>
                            <input type="number" name="satisfaction_rate" class="form-control" min="0" max="100"
                                   value="{{ old('satisfaction_rate', $doctor->profile->satisfaction_rate ?? '') }}">
                        </div>
                        <div class="form-group">
                                <label class="form-label mb-3">Availability Settings</label>
                                <div class="checkbox-group">
                                    <label class="form-check">
                                        <input type="checkbox" name="is_available_today" class="form-check-input" {{ $doctor->is_available_today ? 'checked' : '' }}>
                                        <span class="form-check-label">Available Today</span>
                                    </label>

                                    <label class="form-check">
                                        <input type="checkbox" name="accepts_virtual_visits" class="form-check-input" {{ $doctor->accepts_virtual_visits ? 'checked' : '' }}>
                                        <span class="form-check-label">Accepts Virtual Visits</span>
                                    </label>

                                    <label class="form-check">
                                        <input type="checkbox" name="accepts_insurance" class="form-check-input" {{ $doctor->accepts_insurance ? 'checked' : '' }}>
                                        <span class="form-check-label">Accepts Insurance</span>
                                    </label>
                                </div>
                            </div>
                    </div>

                    <div class="form-group mt-4">
                        <label class="form-label">Short About (Hero Section)</label>
                        <textarea name="about_short" class="form-control" id="about-short-input" rows="3">{{ old('about_short', $doctor->profile->about_short ?? '') }}</textarea>
                    </div>

                    <div class="form-group mt-4">
                        <label class="form-label">Long About (About Section)</label>
                        <textarea name="about_long" class="form-control" id="about-long-input" rows="5">{{ old('about_long', $doctor->profile->about_long ?? '') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group mt-4">
                                <label class="form-label">Profile Photo</label>
                                <div class="image-upload-area" onclick="document.getElementById('photo-input').click()" id="photo-upload-area">
                                    @if ($doctor->photo)
                                        <img src="{{ url($doctor->photo) }}" class="image-preview" alt="Profile Photo" id="photo-preview">
                                        <p class="mb-2 text-muted">Click to change photo</p>
                                    @else
                                        <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-3" id="photo-icon"></i>
                                        <p class="mb-2" id="photo-text">Click to upload profile photo</p>
                                        <small class="text-muted" id="photo-hint">JPEG, PNG up to 5MB</small>
                                    @endif
                                    <input type="file" name="photo" id="photo-input" class="d-none" accept="image/*" onchange="previewProfilePhoto(this)">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                           <div class="form-group mt-4">
                            <label class="form-label">Hero Image</label>
                            <div class="image-upload-area" onclick="document.getElementById('hero-image-input').click()">
                                @if ($doctor->profile && $doctor->profile->hero_image)
                                    <img src="{{ url($doctor->profile->hero_image) }}" class="image-preview" alt="Hero Image">
                                    <p class="mb-2 text-muted">Click to change image</p>
                                @else
                                    <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-3"></i>
                                    <p class="mb-2">Click to upload hero image</p>
                                    <small class="text-muted">JPEG, PNG up to 10MB</small>
                                @endif
                                <input type="file" name="hero_image" id="hero-image-input" class="d-none" accept="image/*">
                            </div>
                          </div>
                        </div>
                        <div class="col-6"></div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Education Tab -->
        <div class="tab-content" id="education-tab">
            <div class="form-section-card">
                <div class="form-section-header">
                    <div class="form-section-header-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <span>Education & Training</span>
                </div>
                <div class="form-section-body">
                    <div id="educations-container">
                        @foreach ($doctor->educations as $index => $education)
                        <div class="dynamic-item-card">
                            <div class="card-header-row">
                                <span class="card-title"><i class="fas fa-graduation-cap"></i> Education #{{ $index + 1 }}</span>
                                <button type="button" class="remove-btn" onclick="removeItem(this)">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Degree *</label>
                                    <input type="text" name="educations[{{ $index }}][degree]" class="form-control" value="{{ $education->degree }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Institution *</label>
                                    <input type="text" name="educations[{{ $index }}][institution]" class="form-control" value="{{ $education->institution }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Start Year</label>
                                    <input type="number" name="educations[{{ $index }}][start_year]" class="form-control" value="{{ $education->start_year }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">End Year</label>
                                    <input type="number" name="educations[{{ $index }}][end_year]" class="form-control" value="{{ $education->end_year }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">City</label>
                                    <input type="text" name="educations[{{ $index }}][city]" class="form-control" value="{{ $education->city }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Country</label>
                                    <input type="text" name="educations[{{ $index }}][country]" class="form-control" value="{{ $education->country }}">
                                </div>
                                <div class="form-group" style="grid-column: span 2;">
                                    <label class="form-label">Description</label>
                                    <textarea name="educations[{{ $index }}][description]" class="form-control" rows="2">{{ $education->description }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="add-btn" onclick="addEducation()">
                        <i class="fas fa-plus"></i> Add Education
                    </button>
                </div>
            </div>
        </div>

        <!-- Experience Tab -->
        <div class="tab-content" id="experience-tab">
            <div class="form-section-card">
                <div class="form-section-header">
                    <div class="form-section-header-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <span>Professional Experience</span>
                </div>
                <div class="form-section-body">
                    <div id="experiences-container">
                        @foreach ($doctor->experiences as $index => $experience)
                        <div class="dynamic-item-card">
                            <div class="card-header-row">
                                <span class="card-title"><i class="fas fa-briefcase"></i> Experience #{{ $index + 1 }}</span>
                                <button type="button" class="remove-btn" onclick="removeItem(this)">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Title *</label>
                                    <input type="text" name="experiences[{{ $index }}][title]" class="form-control" value="{{ $experience->title }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Organization *</label>
                                    <input type="text" name="experiences[{{ $index }}][organization]" class="form-control" value="{{ $experience->organization }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Start Year</label>
                                    <input type="number" name="experiences[{{ $index }}][start_year]" class="form-control" value="{{ $experience->start_year }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">End Year</label>
                                    <input type="number" name="experiences[{{ $index }}][end_year]" class="form-control" value="{{ $experience->end_year }}">
                                </div>
                                <div class="form-group" style="grid-column: span 2;">
                                    <label class="form-label">Description</label>
                                    <textarea name="experiences[{{ $index }}][description]" class="form-control" rows="2">{{ $experience->description }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="add-btn" onclick="addExperience()">
                        <i class="fas fa-plus"></i> Add Experience
                    </button>
                </div>
            </div>
        </div>

        <!-- Certifications Tab -->
        <div class="tab-content" id="certifications-tab">
            <div class="form-section-card">
                <div class="form-section-header">
                    <div class="form-section-header-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <span>Certifications & Credentials</span>
                </div>
                <div class="form-section-body">
                    <div id="certifications-container">
                        @foreach ($doctor->certifications as $index => $certification)
                        <div class="dynamic-item-card">
                            <div class="card-header-row">
                                <span class="card-title"><i class="fas fa-certificate"></i> Certification #{{ $index + 1 }}</span>
                                <button type="button" class="remove-btn" onclick="removeItem(this)">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Title *</label>
                                    <input type="text" name="certifications[{{ $index }}][title]" class="form-control" value="{{ $certification->title }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Organization *</label>
                                    <input type="text" name="certifications[{{ $index }}][organization]" class="form-control" value="{{ $certification->organization }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Year</label>
                                    <input type="number" name="certifications[{{ $index }}][year]" class="form-control" value="{{ $certification->year }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select name="certifications[{{ $index }}][status]" class="form-control">
                                        <option value="active" {{ $certification->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $certification->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="expired" {{ $certification->status == 'expired' ? 'selected' : '' }}>Expired</option>
                                    </select>
                                </div>
                                <div class="form-group" style="grid-column: span 2;">
                                    <label class="form-label">Description</label>
                                    <textarea name="certifications[{{ $index }}][description]" class="form-control" rows="2">{{ $certification->description }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="add-btn" onclick="addCertification()">
                        <i class="fas fa-plus"></i> Add Certification
                    </button>
                </div>
            </div>
        </div>

        <!-- Services Tab -->
        <div class="tab-content" id="services-tab">
            <div class="form-section-card">
                <div class="form-section-header">
                    <div class="form-section-header-icon">
                        <i class="fas fa-clinic-medical"></i>
                    </div>
                    <span>Medical Services</span>
                </div>
                <div class="form-section-body">
                    <div id="services-container">
                        @foreach ($doctor->services as $index => $service)
                        <div class="dynamic-item-card">
                            <div class="card-header-row">
                                <span class="card-title"><i class="fas fa-clinic-medical"></i> Service #{{ $index + 1 }}</span>
                                <button type="button" class="remove-btn" onclick="removeItem(this)">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Service Title *</label>
                                    <input type="text" name="services[{{ $index }}][title]" class="form-control" value="{{ $service->title }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Icon</label>
                                    <select name="services[{{ $index }}][icon]" class="form-control">
                                        <option value="ri-stethoscope-fill" {{ $service->icon == 'ri-stethoscope-fill' ? 'selected' : '' }}>Stethoscope</option>
                                        <option value="ri-heart-pulse-fill" {{ $service->icon == 'ri-heart-pulse-fill' ? 'selected' : '' }}>Heart Pulse</option>
                                        <option value="ri-syringe-fill" {{ $service->icon == 'ri-syringe-fill' ? 'selected' : '' }}>Syringe</option>
                                        <option value="ri-first-aid-kit-fill" {{ $service->icon == 'ri-first-aid-kit-fill' ? 'selected' : '' }}>First Aid Kit</option>
                                        <option value="ri-ambulance-fill" {{ $service->icon == 'ri-ambulance-fill' ? 'selected' : '' }}>Ambulance</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Badge</label>
                                    <input type="text" name="services[{{ $index }}][badge]" class="form-control" value="{{ $service->badge }}" placeholder="Popular">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Order</label>
                                    <input type="number" name="services[{{ $index }}][order_column]" class="form-control" value="{{ $service->order_column }}">
                                </div>
                                <div class="form-group" style="grid-column: span 2;">
                                    <label class="form-label">Description</label>
                                    <textarea name="services[{{ $index }}][description]" class="form-control" rows="2">{{ $service->description }}</textarea>
                                </div>
                                <div class="form-group" style="grid-column: span 2;">
                                    <label class="form-label">Features (one per line)</label>
                                    <textarea name="services[{{ $index }}][features]" class="form-control" rows="3">{{ is_array($service->features) ? implode("\n", $service->features) : $service->features }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="add-btn" onclick="addService()">
                        <i class="fas fa-plus"></i> Add Service
                    </button>
                </div>
            </div>
        </div>

        <!-- Testimonials Tab -->
        <div class="tab-content" id="testimonials-tab">
            <div class="form-section-card">
                <div class="form-section-header">
                    <div class="form-section-header-icon">
                        <i class="fas fa-comment-medical"></i>
                    </div>
                    <span>Patient Testimonials</span>
                </div>
                <div class="form-section-body">
                    <div id="testimonials-container">
                        @foreach ($doctor->testimonials as $index => $testimonial)
                        <div class="dynamic-item-card">
                            <div class="card-header-row">
                                <span class="card-title"><i class="fas fa-comment-medical"></i> Testimonial #{{ $index + 1 }}</span>
                                <button type="button" class="remove-btn" onclick="removeItem(this)">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Patient Name *</label>
                                    <input type="text" name="testimonials[{{ $index }}][patient_name]" class="form-control" value="{{ $testimonial->patient_name }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Patient Since</label>
                                    <input type="text" name="testimonials[{{ $index }}][since]" class="form-control" value="{{ $testimonial->since }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Rating (1-5)</label>
                                    <input type="number" min="1" max="5" name="testimonials[{{ $index }}][rating]" class="form-control" value="{{ $testimonial->rating }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Order</label>
                                    <input type="number" name="testimonials[{{ $index }}][order_column]" class="form-control" value="{{ $testimonial->order_column }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Verified</label>
                                    <div class="form-check">
                                        <input type="checkbox" name="testimonials[{{ $index }}][verified]" class="form-check-input" {{ $testimonial->verified ? 'checked' : '' }}>
                                        <span class="form-check-label">Verified Review</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Patient Photo</label>
                                    <input type="hidden" name="testimonials[{{ $index }}][id]" value="{{ $testimonial->id }}">
<input type="hidden" name="testimonials[{{ $index }}][existing_photo]" value="{{ $testimonial->photo }}">

                                    {{-- <input type="file" name="testimonials[{{ $index }}][photo]" class="form-control">
                                    <input type="hidden" name="testimonials[{{ $index }}][existing_photo]" value="{{ $testimonial->photo }}"> --}}

                                    @if ($testimonial->photo)
                                        <div class="mt-2">
                                            <img src="{{ url($testimonial->photo) }}" width="80" class="rounded">
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group" style="grid-column: span 2;">
                                    <label class="form-label">Testimonial Content *</label>
                                    <textarea name="testimonials[{{ $index }}][content]" class="form-control" rows="3" required>{{ $testimonial->content }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="add-btn" onclick="addTestimonial()">
                        <i class="fas fa-plus"></i> Add Testimonial
                    </button>
                </div>
            </div>
        </div>

        <!-- Affiliations Tab -->
        <div class="tab-content" id="affiliations-tab">
            <div class="form-section-card">
                <div class="form-section-header">
                    <div class="form-section-header-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <span>Professional Affiliations</span>
                </div>
                <div class="form-section-body">
                    <div id="affiliations-container">
                        @foreach ($doctor->affiliations as $index => $affiliation)
                        <div class="dynamic-item-card">
                            <div class="card-header-row">
                                <span class="card-title"><i class="fas fa-handshake"></i> Affiliation #{{ $index + 1 }}</span>
                                <button type="button" class="remove-btn" onclick="removeItem(this)">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Type</label>
                                    <select name="affiliations[{{ $index }}][type]" class="form-control">
                                        <option value="hospital" {{ $affiliation->type == 'hospital' ? 'selected' : '' }}>Hospital</option>
                                        <option value="organization" {{ $affiliation->type == 'organization' ? 'selected' : '' }}>Organization</option>
                                        <option value="society" {{ $affiliation->type == 'society' ? 'selected' : '' }}>Society</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Name *</label>
                                    <input type="text" name="affiliations[{{ $index }}][name]" class="form-control" value="{{ $affiliation->name }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Position/Role</label>
                                    <input type="text" name="affiliations[{{ $index }}][position]" class="form-control" value="{{ $affiliation->position }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Order</label>
                                    <input type="number" name="affiliations[{{ $index }}][order_column]" class="form-control" value="{{ $affiliation->order_column }}">
                                </div>
                                <div class="form-group" style="grid-column: span 2;">
                                    <label class="form-label">Description</label>
                                    <textarea name="affiliations[{{ $index }}][description]" class="form-control" rows="2">{{ $affiliation->description }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="add-btn" onclick="addAffiliation()">
                        <i class="fas fa-plus"></i> Add Affiliation
                    </button>
                </div>
            </div>
        </div>

        <!-- FAQs Tab -->
        <div class="tab-content" id="faqs-tab">
            <div class="form-section-card">
                <div class="form-section-header">
                    <div class="form-section-header-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <span>Frequently Asked Questions</span>
                </div>
                <div class="form-section-body">
                    <div id="faqs-container">
                        @foreach ($doctor->faqs as $index => $faq)
                        <div class="dynamic-item-card">
                            <div class="card-header-row">
                                <span class="card-title"><i class="fas fa-question-circle"></i> FAQ #{{ $index + 1 }}</span>
                                <button type="button" class="remove-btn" onclick="removeItem(this)">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                            <div class="form-grid">
                                <div class="form-group" style="grid-column: span 2;">
                                    <label class="form-label">Question *</label>
                                    <input type="text" name="faqs[{{ $index }}][question]" class="form-control" value="{{ $faq->question }}" required>
                                </div>
                                <div class="form-group" style="grid-column: span 2;">
                                    <label class="form-label">Answer *</label>
                                    <textarea name="faqs[{{ $index }}][answer]" class="form-control" rows="3" required>{{ $faq->answer }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Order</label>
                                    <input type="number" name="faqs[{{ $index }}][order_column]" class="form-control" value="{{ $faq->order_column }}">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="add-btn" onclick="addFaq()">
                        <i class="fas fa-plus"></i> Add FAQ
                    </button>
                </div>
            </div>
        </div>

        <!-- Telemedicine Tab -->
        <div class="tab-content" id="telemedicine-tab">
            <div class="form-section-card">
                <div class="form-section-header">
                    <div class="form-section-header-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <span>Telemedicine Platforms</span>
                </div>
                <div class="form-section-body">
                    <div id="telemedicine-platforms-container">
                        @foreach ($doctor->telemedicinePlatforms as $index => $platform)
                        <div class="dynamic-item-card">
                            <div class="card-header-row">
                                <span class="card-title"><i class="fas fa-video"></i> Platform #{{ $index + 1 }}</span>
                                <button type="button" class="remove-btn" onclick="removeItem(this)">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Platform Name *</label>
                                    <input type="text" name="telemedicine_platforms[{{ $index }}][name]" class="form-control" value="{{ $platform->name }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Icon</label>
                                    <select name="telemedicine_platforms[{{ $index }}][icon]" class="form-control">
                                        <option value="ri-vidicon-fill" {{ $platform->icon == 'ri-vidicon-fill' ? 'selected' : '' }}>Vidicon</option>
                                        <option value="ri-video-fill" {{ $platform->icon == 'ri-video-fill' ? 'selected' : '' }}>Video</option>
                                        <option value="ri-phone-fill" {{ $platform->icon == 'ri-phone-fill' ? 'selected' : '' }}>Phone</option>
                                        <option value="ri-chat-1-fill" {{ $platform->icon == 'ri-chat-1-fill' ? 'selected' : '' }}>Chat</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Color</label>
                                    <input type="color" name="telemedicine_platforms[{{ $index }}][color]" class="form-control" value="{{ $platform->color }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Order</label>
                                    <input type="number" name="telemedicine_platforms[{{ $index }}][order_column]" class="form-control" value="{{ $platform->order_column }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Active</label>
                                    <div class="form-check">
                                        <input type="checkbox" name="telemedicine_platforms[{{ $index }}][active]" class="form-check-input" {{ $platform->active ? 'checked' : '' }}>
                                        <span class="form-check-label">Active Platform</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="add-btn" onclick="addTelemedicinePlatform()">
                        <i class="fas fa-plus"></i> Add Platform
                    </button>
                </div>
            </div>
        </div>

        <!-- Gallery Tab -->
        <div class="tab-content" id="gallery-tab">
            <div class="form-section-card">
                <div class="form-section-header">
                    <div class="form-section-header-icon">
                        <i class="fas fa-images"></i>
                    </div>
                    <span>Gallery Images</span>
                </div>
                <div class="form-section-body">
                    <div id="gallery-container">
                        @foreach ($doctor->galleries as $index => $gallery)
                        <div class="dynamic-item-card">
                            <div class="card-header-row">
                                <span class="card-title"><i class="fas fa-images"></i> Gallery Item #{{ $index + 1 }}</span>
                                <button type="button" class="remove-btn" onclick="removeItem(this)">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Title *</label>
                                    <input type="text" name="gallery[{{ $index }}][title]" class="form-control" value="{{ $gallery->title }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Category</label>
                                    <select name="gallery[{{ $index }}][category]" class="form-control">
                                        <option value="facility" {{ $gallery->category == 'facility' ? 'selected' : '' }}>Facility</option>
                                        <option value="care" {{ $gallery->category == 'care' ? 'selected' : '' }}>Care</option>
                                        <option value="technology" {{ $gallery->category == 'technology' ? 'selected' : '' }}>Technology</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Order</label>
                                    <input type="number" name="gallery[{{ $index }}][order_column]" class="form-control" value="{{ $gallery->order_column }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="gallery[{{ $index }}][image_url]" class="form-control">
                                    <input type="hidden" name="gallery[{{ $index }}][existing_image]" value="{{ $gallery->image_url }}">
                                    <input type="hidden" name="gallery[{{ $index }}][id]" value="{{ $gallery->id }}">

                                    {{-- <input type="file" name="gallery[{{ $index }}][image]" class="form-control">
                                    <input type="hidden" name="gallery[{{ $index }}][existing_image]" value="{{ $gallery->image_url }}"> --}}

                                    @if ($gallery->image_url)
                                        <div class="mt-2">
                                            <img src="{{ url($gallery->image_url) }}" width="80" class="rounded">
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group" style="grid-column: span 2;">
                                    <label class="form-label">Caption</label>
                                    <textarea name="gallery[{{ $index }}][caption]" class="form-control" rows="2">{{ $gallery->caption }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="add-btn" onclick="addGalleryItem()">
                        <i class="fas fa-plus"></i> Add Gallery Item
                    </button>
                </div>
            </div>
        </div>

        <!-- Theme Selection Tab -->
        <div class="tab-content" id="theme-tab">
            <div class="form-section-card">
                <div class="form-section-header">
                    <div class="form-section-header-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <span>Website Theme Selection</span>
                </div>
                <div class="form-section-body">
                    <div class="theme-grid">
                        <!-- Theme 1 -->
                        <label class="theme-card" for="theme-default">
                            <input type="radio" name="theme" id="theme-default" value="default" class="d-none" {{ old('theme', $doctor->theme) == 'default' ? 'checked' : '' }}>
                            <img src="https://images.unsplash.com/photo-1559757175-0eb30cd8c063?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Default Theme" class="theme-thumbnail">
                            <div class="theme-content">
                                <div class="theme-title">Medical Blue</div>
                                <div class="theme-description">Clean blue theme suitable for medical professionals</div>
                            </div>
                        </label>

                        <!-- Theme 2 -->
                        <label class="theme-card" for="theme-green">
                            <input type="radio" name="theme" id="theme-green" value="green" class="d-none" {{ old('theme', $doctor->theme) == 'green' ? 'checked' : '' }}>
                            <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Green Theme" class="theme-thumbnail">
                            <div class="theme-content">
                                <div class="theme-title">Healthcare Green</div>
                                <div class="theme-description">Fresh green theme promoting health and wellness</div>
                            </div>
                        </label>

                        <!-- Theme 3 -->
                        <label class="theme-card" for="theme-modern">
                            <input type="radio" name="theme" id="theme-modern" value="modern" class="d-none" {{ old('theme', $doctor->theme) == 'modern' ? 'checked' : '' }}>
                            <img src="https://images.unsplash.com/photo-1551601651-2a8555f1a136?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Modern Theme" class="theme-thumbnail">
                            <div class="theme-content">
                                <div class="theme-title">Modern White</div>
                                <div class="theme-description">Clean white theme with modern design elements</div>
                            </div>
                        </label>

                        <!-- Theme 4 -->
                        <label class="theme-card" for="theme-professional">
                            <input type="radio" name="theme" id="theme-professional" value="professional" class="d-none" {{ old('theme', $doctor->theme) == 'professional' ? 'checked' : '' }}>
                            <img src="https://images.unsplash.com/photo-1538108149393-fbbd81895907?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Professional Theme" class="theme-thumbnail">
                            <div class="theme-content">
                                <div class="theme-title">Professional Dark</div>
                                <div class="theme-description">Dark professional theme for a premium look</div>
                            </div>
                        </label>

                        <!-- Theme 5 -->
                        <label class="theme-card" for="theme-minimal">
                            <input type="radio" name="theme" id="theme-minimal" value="minimal" class="d-none" {{ old('theme', $doctor->theme) == 'minimal' ? 'checked' : '' }}>
                            <img src="https://images.unsplash.com/photo-1551601651-2a8555f1a136?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Minimal Theme" class="theme-thumbnail">
                            <div class="theme-content">
                                <div class="theme-title">Minimalist</div>
                                <div class="theme-description">Simple and clean design focusing on content</div>
                            </div>
                        </label>

                        <!-- Theme 6 -->
                        <label class="theme-card" for="theme-elegant">
                            <input type="radio" name="theme" id="theme-elegant" value="elegant" class="d-none" {{ old('theme', $doctor->theme) == 'elegant' ? 'checked' : '' }}>
                            <img src="https://images.unsplash.com/photo-1559757175-0eb30cd8c063?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Elegant Theme" class="theme-thumbnail">
                            <div class="theme-content">
                                <div class="theme-title">Elegant Purple</div>
                                <div class="theme-description">Elegant purple theme with premium features</div>
                            </div>
                        </label>
                    </div>

                    <div class="mt-4">
                        <div class="form-hint">Note: Theme selection affects the appearance of your public profile website. Changes will be visible after saving.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Privacy Settings Tab -->
        <div class="tab-content" id="privacy-tab">
            <div class="form-section-card">
                <div class="form-section-header">
                    <div class="form-section-header-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <span>Privacy & Security Settings</span>
                </div>
                <div class="form-section-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Old Password</label>
                            <div class="password-field">
                                <input type="password" name="old_password" class="form-control" id="old-password">
                                <button type="button" class="password-toggle" onclick="togglePassword('old-password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="form-hint">Enter your current password to make changes</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <div class="password-field">
                                <input type="password" name="password" class="form-control" id="new-password">
                                <button type="button" class="password-toggle" onclick="togglePassword('new-password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="form-hint">Minimum 8 characters with letters and numbers</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirm New Password</label>
                            <div class="password-field">
                                <input type="password" name="password_confirmation" class="form-control" id="confirm-password">
                                <button type="button" class="password-toggle" onclick="togglePassword('confirm-password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Password Requirements:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Minimum 8 characters long</li>
                                <li>Include at least one uppercase letter</li>
                                <li>Include at least one lowercase letter</li>
                                <li>Include at least one number</li>
                                <li>Include at least one special character</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="form-check">
                            <input type="checkbox" name="logout_other_devices" class="form-check-input" id="logout-devices">
                            <label class="form-check-label" for="logout-devices">
                                Log out from all other devices after password change
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Modal -->
        <div class="ai-modal" id="ai-modal">
            <div class="ai-modal-content">
                <div class="ai-modal-header">
                    <div class="ai-modal-title">
                        <i class="fas fa-robot"></i>
                        <span>AI Content Suggestions</span>
                    </div>
                    <button class="ai-modal-close" onclick="closeAIModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="ai-modal-body" id="ai-modal-body">
                    <!-- AI content will be loaded here -->
                </div>

                <div class="ai-modal-footer">
                    <button type="button" class="btn btn-primary-outline" onclick="closeAIModal()">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary" onclick="useAllSuggestions()">
                            <i class="fas fa-check-circle"></i> Use All
                        </button>
                        <button type="button" class="btn btn-success" id="ai-regenerate-btn" onclick="regenerateAIContent()">
                            <i class="fas fa-sync-alt"></i> Regenerate
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="form-progress">
            <div class="progress-bar-container">
                <div class="progress-stats">
                    <span id="current-tab-title">Basic Information</span>
                    <span class="mx-2">•</span>
                    <span>Tab <strong id="current-tab">1</strong> of <strong>15</strong></span>
                </div>
                <div class="progress-actions">
                    <button type="button" class="btn btn-primary-outline d-block" onclick="prevTab()" id="prev-btn" disabled>
                        <i class="fas fa-arrow-left"></i> 
                    </button>
                    <button type="button" class="btn btn-primary" onclick="nextTab()" id="next-btn">
                        Next <i class="fas fa-arrow-right ms-1"></i>
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Toast Container -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
    // Tab Management
    const tabs = [
        { id: 'basic', title: 'Basic Information', index: 1 },
        { id: 'location', title: 'Location & Map', index: 2 },
        { id: 'profile', title: 'Profile Information', index: 3 },
        { id: 'education', title: 'Education & Training', index: 4 },
        { id: 'experience', title: 'Professional Experience', index: 5 },
        { id: 'certifications', title: 'Certifications & Credentials', index: 6 },
        { id: 'services', title: 'Medical Services', index: 7 },
        { id: 'testimonials', title: 'Patient Testimonials', index: 8 },
        { id: 'affiliations', title: 'Professional Affiliations', index: 9 },
        { id: 'faqs', title: 'Frequently Asked Questions', index: 10 },
        { id: 'telemedicine', title: 'Telemedicine Platforms', index: 11 },
        { id: 'gallery', title: 'Gallery Images', index: 12 },
       // { id: 'theme', title: 'Theme Selection', index: 13 },
        { id: 'privacy', title: 'Privacy Settings', index: 13 }
    ];

    let currentTabIndex = 0;
    let currentHeadline = '';
    let isGeneratingAI = false;
    let usedSuggestions = new Set();
    let map = null;
    let marker = null;
    let defaultLat = 40.7128;
    let defaultLng = -74.0060;
    let defaultZoom = 12;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tabs
        initializeTabs();

        // Set up tab click handlers
        document.querySelectorAll('.profile-tab').forEach((tab, index) => {
            tab.addEventListener('click', () => switchTab(index));
        });

        updateProgressBar();

        // Initialize AI button state
        updateAIButtonState();

        // Add event listener for headline input
        const headlineInput = document.getElementById('headline-input');
        if (headlineInput) {
            headlineInput.addEventListener('input', updateAIButtonState);
        }

        // Add click handler for AI button
        const aiButton = document.getElementById('ai-generate-btn');
        if (aiButton) {
            aiButton.addEventListener('click', openAIModal);
        }

        // Close modal when clicking outside
        document.getElementById('ai-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAIModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAIModal();
            }
        });

        // Initialize theme cards
        initializeThemeCards();

        // Initialize map when location tab is active
        const locationTab = document.getElementById('location-tab');
        if (locationTab) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !map) {
                        initMap();
                    }
                });
            }, { threshold: 0.1 });

            observer.observe(locationTab);
        }

        // Setup location search
        setupLocationSearch();
    });

    function initializeTabs() {
        tabs.forEach((tab, index) => {
            const tabElement = document.getElementById(`${tab.id}-tab`);
            if (tabElement) {
                tabElement.classList.remove('active');
                if (index === currentTabIndex) {
                    tabElement.classList.add('active');
                }
            }
        });

        document.querySelectorAll('.profile-tab').forEach((tab, index) => {
            tab.classList.remove('active');
            if (index === currentTabIndex) {
                tab.classList.add('active');
            }
        });
    }

    function switchTab(index) {
        if (index < 0 || index >= tabs.length) return;

        document.getElementById(`${tabs[currentTabIndex].id}-tab`).classList.remove('active');
        document.querySelectorAll('.profile-tab')[currentTabIndex].classList.remove('active');

        currentTabIndex = index;
        document.getElementById(`${tabs[currentTabIndex].id}-tab`).classList.add('active');
        document.querySelectorAll('.profile-tab')[currentTabIndex].classList.add('active');

        updateProgressBar();
        window.scrollTo({ top: 0, behavior: 'smooth' });

        // Initialize map if location tab is active
        if (tabs[currentTabIndex].id === 'location' && !map) {
            setTimeout(() => initMap(), 100);
        }
    }

    function nextTab() {
        if (currentTabIndex < tabs.length - 1) {
            switchTab(currentTabIndex + 1);
        }
    }

    function prevTab() {
        if (currentTabIndex > 0) {
            switchTab(currentTabIndex - 1);
        }
    }

    function updateProgressBar() {
        const currentTab = tabs[currentTabIndex];
        document.getElementById('current-tab-title').textContent = currentTab.title;
        document.getElementById('current-tab').textContent = currentTab.index;

        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');

        prevBtn.disabled = currentTabIndex === 0;
        nextBtn.disabled = currentTabIndex === tabs.length - 1;

        if (currentTabIndex === tabs.length - 1) {
            nextBtn.style.display = 'none';
        } else {
            nextBtn.style.display = 'inline-flex';
        }
    }

    // Update AI button state based on headline input
    function updateAIButtonState() {
        const headlineInput = document.getElementById('headline-input');
        const aiButton = document.getElementById('ai-generate-btn');

        if (headlineInput && aiButton) {
            const headline = headlineInput.value.trim();
            if (headline.length > 0) {
                aiButton.disabled = false;
                currentHeadline = headline;
            } else {
                aiButton.disabled = true;
                currentHeadline = '';
            }
        }
    }

    // Initialize theme cards
    function initializeThemeCards() {
        const themeCards = document.querySelectorAll('.theme-card');
        themeCards.forEach(card => {
            const radio = card.querySelector('input[type="radio"]');

            card.addEventListener('click', function() {
                // Remove selected class from all cards
                themeCards.forEach(c => c.classList.remove('selected'));

                // Add selected class to clicked card
                this.classList.add('selected');

                // Check the radio button
                if (radio) {
                    radio.checked = true;
                }
            });

            // Initialize selected state
            if (radio && radio.checked) {
                card.classList.add('selected');
            }
        });
    }

    // Setup location search functionality
    function setupLocationSearch() {
        const searchInput = document.getElementById('location-search-input');
        const searchBtn = document.getElementById('location-search-btn');
        const searchResults = document.getElementById('search-results');

        if (searchInput && searchBtn) {
            // Search on button click
            searchBtn.addEventListener('click', performLocationSearch);

            // Search on Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performLocationSearch();
                }
            });

            // Close results when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target) && !searchBtn.contains(e.target)) {
                    searchResults.style.display = 'none';
                }
            });
        }
    }

    // Perform location search using Nominatim API
    async function performLocationSearch() {
        const searchInput = document.getElementById('location-search-input');
        const searchResults = document.getElementById('search-results');

        if (!searchInput || !searchResults) return;

        const query = searchInput.value.trim();
        if (!query) {
            showToast('Please enter a location to search', 'warning');
            return;
        }

        // Show loading
        searchResults.innerHTML = '<div class="search-result-item" style="text-align: center; padding: 2rem;"><i class="fas fa-spinner fa-spin"></i> Searching...</div>';
        searchResults.style.display = 'block';

        try {
            // Using Nominatim OpenStreetMap API
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=10&addressdetails=1`);
            const data = await response.json();

            if (data.length === 0) {
                searchResults.innerHTML = '<div class="search-result-item" style="text-align: center; padding: 2rem;">No locations found</div>';
                return;
            }

            // Display results
            searchResults.innerHTML = '';
            data.forEach(result => {
                const item = document.createElement('div');
                item.className = 'search-result-item';
                item.innerHTML = `
                    <div class="search-result-name">${result.display_name.split(',')[0]}</div>
                    <div class="search-result-address">${result.display_name}</div>
                `;

                item.addEventListener('click', function() {
                    // Update map position
                    const lat = parseFloat(result.lat);
                    const lng = parseFloat(result.lon);

                    if (map && marker) {
                        map.setView([lat, lng], 15);
                        marker.setLatLng([lat, lng]);
                        updateLatLngInputs(lat, lng);
                    }

                    // Update search input
                    searchInput.value = result.display_name;

                    // Hide results
                    searchResults.style.display = 'none';

                    showToast('Location selected', 'success');
                });

                searchResults.appendChild(item);
            });

        } catch (error) {
            console.error('Location search error:', error);
            searchResults.innerHTML = '<div class="search-result-item" style="text-align: center; padding: 2rem; color: #dc2626;">Error searching location</div>';
        }
    }

    // Initialize Leaflet Map
    function initMap() {
        // Get coordinates from input fields or use defaults
        const latInput = document.getElementById('latitude-input');
        const lngInput = document.getElementById('longitude-input');

        let lat = defaultLat;
        let lng = defaultLng;

        if (latInput && latInput.value) {
            lat = parseFloat(latInput.value);
        }
        if (lngInput && lngInput.value) {
            lng = parseFloat(lngInput.value);
        }

        // Initialize map
        map = L.map('location-map').setView([lat, lng], defaultZoom);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19,
        }).addTo(map);

        // Create custom marker icon
        const customIcon = L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Add marker
        marker = L.marker([lat, lng], {
            draggable: true,
            icon: customIcon
        }).addTo(map);

        // Add popup to marker
        marker.bindPopup('Drag to set location').openPopup();

        // Update inputs when marker is dragged
        marker.on('dragend', function(event) {
            const position = marker.getLatLng();
            updateLatLngInputs(position.lat, position.lng);
        });

        // Update marker and inputs when map is clicked
        map.on('click', function(event) {
            marker.setLatLng(event.latlng);
            updateLatLngInputs(event.latlng.lat, event.latlng.lng);
        });

        // Update inputs with initial values
        updateLatLngInputs(lat, lng);

        // Add some controls
        L.control.scale().addTo(map);
    }

    function updateLatLngInputs(lat, lng) {
        document.getElementById('latitude-input').value = lat.toFixed(6);
        document.getElementById('longitude-input').value = lng.toFixed(6);
    }

    // Get current location
    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    if (map && marker) {
                        map.setView([lat, lng], defaultZoom);
                        marker.setLatLng([lat, lng]);
                        updateLatLngInputs(lat, lng);
                        showToast('Location updated from your current position', 'success');
                    }
                },
                function(error) {
                    let errorMessage = 'Unable to get your location. ';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += 'Permission denied. Please enable location services.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Location information unavailable.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'Location request timed out.';
                            break;
                        case error.UNKNOWN_ERROR:
                            errorMessage += 'An unknown error occurred.';
                            break;
                    }
                    showToast(errorMessage, 'warning');
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        } else {
            showToast('Geolocation is not supported by your browser', 'warning');
        }
    }

    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const toggleBtn = field.nextElementSibling;

        if (field.type === 'password') {
            field.type = 'text';
            toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            field.type = 'password';
            toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
        }
    }

    // Open AI Modal
    function openAIModal() {
        if (isGeneratingAI) return;

        const headlineInput = document.getElementById('headline-input');
        const headline = headlineInput ? headlineInput.value.trim() : '';

        if (!headline) {
            showToast('Please enter a headline first', 'warning');
            return;
        }

        currentHeadline = headline;
        const modal = document.getElementById('ai-modal');
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';

        // Generate initial content
        generateAIContent(headline);
    }

    // Close AI Modal
    function closeAIModal() {
        const modal = document.getElementById('ai-modal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        isGeneratingAI = false;
        usedSuggestions.clear();
    }

    // Generate AI Content
    function generateAIContent(headline) {
        if (isGeneratingAI) return;
        isGeneratingAI = true;

        const modalBody = document.getElementById('ai-modal-body');
        modalBody.innerHTML = `
            <div class="ai-loading">
                <div class="spinner"></div>
                <h4 class="mb-2">Generating AI Suggestions</h4>
                <p class="text-muted">Analyzing your headline and creating optimized content...</p>
            </div>
        `;

        // Disable regenerate button during generation
        const regenerateBtn = document.getElementById('ai-regenerate-btn');
        if (regenerateBtn) {
            regenerateBtn.disabled = true;
        }

        // Simulate API call with timeout
        setTimeout(() => {
            const content = generateMockContent(headline);
            displayAIContent(content);
            isGeneratingAI = false;

            if (regenerateBtn) {
                regenerateBtn.disabled = false;
            }
        }, 1500);
    }

    // Regenerate AI Content
    function regenerateAIContent() {
        if (!currentHeadline || isGeneratingAI) return;
        usedSuggestions.clear();
        generateAIContent(currentHeadline);
    }

    // Generate mock content
    function generateMockContent(headline) {
        const keywords = extractKeywords(headline);
        const keyword = keywords[0] || 'healthcare';
        const doctorName = "{{ $doctor->name }}";
        const qualification = "{{ $doctor->qualification }}";

        const variations = [
            {
                subheadline: `Comprehensive ${keyword} care for your wellbeing`,
                tagline: "Excellence in Patient Care",
                about_short: `Dr. ${doctorName} provides comprehensive ${keyword} services with a patient-centered approach. With expertise in ${qualification}, we're committed to delivering exceptional medical care.`,
                about_long: `Dr. ${doctorName} is a dedicated medical professional with extensive experience in ${keyword}. Our practice is built on providing comprehensive medical care that addresses both immediate health concerns and long-term wellness goals. We believe in a holistic approach to healthcare, combining advanced medical treatments with personalized attention.`
            },
            {
                subheadline: `Specialized ${keyword} treatments and care`,
                tagline: "Your Health Partner",
                about_short: `With years of experience in ${keyword}, Dr. ${doctorName} offers specialized medical services tailored to your needs. Our commitment is to provide the highest quality care in a compassionate environment.`,
                about_long: `Dr. ${doctorName} brings extensive knowledge and expertise in ${keyword} to provide outstanding medical care. Our approach focuses on understanding each patient's unique needs and developing personalized treatment plans. We utilize the latest medical advancements while maintaining a warm, patient-focused environment.`
            },
            {
                subheadline: `Advanced ${keyword} solutions for better health`,
                tagline: "Innovative Medical Care",
                about_short: `Dr. ${doctorName} specializes in advanced ${keyword} treatments, offering cutting-edge solutions for various health conditions. We prioritize patient comfort and successful outcomes.`,
                about_long: `At our practice, Dr. ${doctorName} combines extensive experience in ${keyword} with innovative treatment approaches. We're dedicated to helping patients achieve optimal health through comprehensive care plans, ongoing support, and state-of-the-art medical techniques. Our patient-centered philosophy ensures that each individual receives personalized attention and the highest standard of care.`
            }
        ];

        return variations[Math.floor(Math.random() * variations.length)];
    }

    // Extract keywords from headline
    function extractKeywords(text) {
        const commonWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'your', 'our', 'my', 'their'];
        const words = text.toLowerCase().split(/\s+/);
        return words.filter(word =>
            word.length > 2 &&
            !commonWords.includes(word)
        );
    }

    // Display AI Content in Modal
    function displayAIContent(content) {
        const modalBody = document.getElementById('ai-modal-body');

        modalBody.innerHTML = `
            <div class="mb-3">
                <p class="text-muted mb-2"><i class="fas fa-lightbulb me-2 text-warning"></i>AI-generated suggestions based on: "<strong>${currentHeadline}</strong>"</p>
            </div>

            <div class="suggestion-field">
                <div class="suggestion-field-header">
                    <span class="suggestion-field-label">Subheadline</span>
                    <button type="button" class="btn btn-sm btn-primary-outline use-btn" data-field="subheadline" data-value="${content.subheadline.replace(/"/g, '&quot;')}">
                        <i class="fas fa-check me-1"></i> Use
                    </button>
                </div>
                <div class="suggestion-field-text">${content.subheadline}</div>
            </div>

            <div class="suggestion-field">
                <div class="suggestion-field-header">
                    <span class="suggestion-field-label">Tagline</span>
                    <button type="button" class="btn btn-sm btn-primary-outline use-btn" data-field="tagline" data-value="${content.tagline.replace(/"/g, '&quot;')}">
                        <i class="fas fa-check me-1"></i> Use
                    </button>
                </div>
                <div class="suggestion-field-text">${content.tagline}</div>
            </div>

            <div class="suggestion-field">
                <div class="suggestion-field-header">
                    <span class="suggestion-field-label">Short About (Hero Section)</span>
                    <button type="button" class="btn btn-sm btn-primary-outline use-btn" data-field="about_short" data-value="${content.about_short.replace(/"/g, '&quot;').replace(/\n/g, '<br>')}">
                        <i class="fas fa-check me-1"></i> Use
                    </button>
                </div>
                <div class="suggestion-field-text">${content.about_short}</div>
            </div>

            <div class="suggestion-field">
                <div class="suggestion-field-header">
                    <span class="suggestion-field-label">Long About (About Section)</span>
                    <button type="button" class="btn btn-sm btn-primary-outline use-btn" data-field="about_long" data-value="${content.about_long.replace(/"/g, '&quot;').replace(/\n/g, '<br>')}">
                        <i class="fas fa-check me-1"></i> Use
                    </button>
                </div>
                <div class="suggestion-field-text">${content.about_long}</div>
            </div>

            <div class="ai-tips">
                <div class="ai-tips-title">
                    <i class="fas fa-info-circle"></i>
                    <span>Tips for best results:</span>
                </div>
                <ul class="ai-tips-list">
                    <li>Review suggestions before applying</li>
                    <li>Customize the AI-generated content to match your tone</li>
                    <li>Use the "Regenerate" button for different variations</li>
                    <li>Combine multiple suggestions for the best results</li>
                </ul>
            </div>
        `;

        // Add event listeners to use buttons
        modalBody.querySelectorAll('.use-btn').forEach(button => {
            button.addEventListener('click', function() {
                const field = this.getAttribute('data-field');
                let value = this.getAttribute('data-value');
                value = value.replace(/<br>/g, '\n');

                useSuggestion(field, value, this);
            });
        });
    }

    // Use a single suggestion
    function useSuggestion(field, value, button) {
        const fieldId = field === 'about_short' ? 'about-short-input' :
                       field === 'about_long' ? 'about-long-input' :
                       field === 'subheadline' ? 'subheadline-input' :
                       field === 'tagline' ? 'tagline-input' : field;

        const targetField = document.getElementById(fieldId);
        if (targetField) {
            targetField.value = value;

            // Mark button as used
            if (button) {
                button.classList.remove('btn-primary-outline');
                button.classList.add('btn-success', 'active');
                button.innerHTML = '<i class="fas fa-check me-1"></i> Used';
                button.disabled = true;
                usedSuggestions.add(field);
            }

            const fieldName = field.replace('_', ' ');
            showToast(`Applied ${fieldName} suggestion`, 'success');

            // Trigger input event to update any dependent logic
            targetField.dispatchEvent(new Event('input'));
        }
    }

    // Use all suggestions
    function useAllSuggestions() {
        const modalBody = document.getElementById('ai-modal-body');
        const useButtons = modalBody.querySelectorAll('.use-btn');

        useButtons.forEach(button => {
            if (!button.disabled) {
                const field = button.getAttribute('data-field');
                let value = button.getAttribute('data-value');
                value = value.replace(/<br>/g, '\n');

                useSuggestion(field, value, button);
            }
        });

        showToast('All suggestions applied successfully', 'success');
    }

    // Dynamic Item Counters (keep existing functions)
    let educationCount = {{ $doctor->educations->count() }};
    let experienceCount = {{ $doctor->experiences->count() }};
    let certificationCount = {{ $doctor->certifications->count() }};
    let affiliationCount = {{ $doctor->affiliations->count() }};
    let serviceCount = {{ $doctor->services->count() }};
    let testimonialCount = {{ $doctor->testimonials->count() }};
    let faqCount = {{ $doctor->faqs->count() }};
    let platformCount = {{ $doctor->telemedicinePlatforms->count() }};
    let galleryCount = {{ $doctor->galleries->count() }};

    // Education
    function addEducation() {
        const container = document.getElementById('educations-container');
        const item = document.createElement('div');
        item.className = 'dynamic-item-card';
        item.innerHTML = `
            <div class="card-header-row">
                <span class="card-title"><i class="fas fa-graduation-cap"></i> Education #${educationCount + 1}</span>
                <button type="button" class="remove-btn" onclick="removeItem(this)">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Degree *</label>
                    <input type="text" name="educations[${educationCount}][degree]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Institution *</label>
                    <input type="text" name="educations[${educationCount}][institution]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Start Year</label>
                    <input type="number" name="educations[${educationCount}][start_year]" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">End Year</label>
                    <input type="number" name="educations[${educationCount}][end_year]" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="educations[${educationCount}][city]" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Country</label>
                    <input type="text" name="educations[${educationCount}][country]" class="form-control">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Description</label>
                    <textarea name="educations[${educationCount}][description]" class="form-control" rows="2"></textarea>
                </div>
            </div>
        `;
        container.appendChild(item);
        educationCount++;
    }

    // Experience
    function addExperience() {
        const container = document.getElementById('experiences-container');
        const item = document.createElement('div');
        item.className = 'dynamic-item-card';
        item.innerHTML = `
            <div class="card-header-row">
                <span class="card-title"><i class="fas fa-briefcase"></i> Experience #${experienceCount + 1}</span>
                <button type="button" class="remove-btn" onclick="removeItem(this)">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="experiences[${experienceCount}][title]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Organization *</label>
                    <input type="text" name="experiences[${experienceCount}][organization]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Start Year</label>
                    <input type="number" name="experiences[${experienceCount}][start_year]" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">End Year</label>
                                    <input type="number" name="experiences[${experienceCount}][end_year]" class="form-control">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Description</label>
                    <textarea name="experiences[${experienceCount}][description]" class="form-control" rows="2"></textarea>
                </div>
            </div>
        `;
        container.appendChild(item);
        experienceCount++;
    }

    // Certification
    function addCertification() {
        const container = document.getElementById('certifications-container');
        const item = document.createElement('div');
        item.className = 'dynamic-item-card';
        item.innerHTML = `
            <div class="card-header-row">
                <span class="card-title"><i class="fas fa-certificate"></i> Certification #${certificationCount + 1}</span>
                <button type="button" class="remove-btn" onclick="removeItem(this)">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="certifications[${certificationCount}][title]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Organization *</label>
                    <input type="text" name="certifications[${certificationCount}][organization]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Year</label>
                    <input type="number" name="certifications[${certificationCount}][year]" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="certifications[${certificationCount}][status]" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Description</label>
                    <textarea name="certifications[${certificationCount}][description]" class="form-control" rows="2"></textarea>
                </div>
            </div>
        `;
        container.appendChild(item);
        certificationCount++;
    }

    // Service
    function addService() {
        const container = document.getElementById('services-container');
        const item = document.createElement('div');
        item.className = 'dynamic-item-card';
        item.innerHTML = `
            <div class="card-header-row">
                <span class="card-title"><i class="fas fa-clinic-medical"></i> Service #${serviceCount + 1}</span>
                <button type="button" class="remove-btn" onclick="removeItem(this)">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Service Title *</label>
                    <input type="text" name="services[${serviceCount}][title]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Icon</label>
                    <select name="services[${serviceCount}][icon]" class="form-control">
                        <option value="ri-stethoscope-fill">Stethoscope</option>
                        <option value="ri-heart-pulse-fill">Heart Pulse</option>
                        <option value="ri-first-aid-kit-fill">First Aid Kit</option>
                        <option value="ri-hospital-fill">Hospital</option>
                        <option value="ri-ambulance-fill">Ambulance</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Badge</label>
                    <input type="text" name="services[${serviceCount}][badge]" class="form-control" placeholder="Popular">
                </div>
                <div class="form-group">
                    <label class="form-label">Order</label>
                    <input type="number" name="services[${serviceCount}][order_column]" class="form-control" value="0">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Description</label>
                    <textarea name="services[${serviceCount}][description]" class="form-control" rows="2"></textarea>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Features (one per line)</label>
                    <textarea name="services[${serviceCount}][features]" class="form-control" rows="3" placeholder="Feature 1&#10;Feature 2&#10;Feature 3"></textarea>
                </div>
            </div>
        `;
        container.appendChild(item);
        serviceCount++;
    }

    // Testimonial
    function addTestimonial() {
        const container = document.getElementById('testimonials-container');
        const item = document.createElement('div');
        item.className = 'dynamic-item-card';
        item.innerHTML = `
            <div class="card-header-row">
                <span class="card-title"><i class="fas fa-comment-medical"></i> Testimonial #${testimonialCount + 1}</span>
                <button type="button" class="remove-btn" onclick="removeItem(this)">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Patient Name *</label>
                    <input type="text" name="testimonials[${testimonialCount}][patient_name]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Patient Since</label>
                    <input type="text" name="testimonials[${testimonialCount}][since]" class="form-control" placeholder="2019">
                </div>
                <div class="form-group">
                    <label class="form-label">Rating (1-5)</label>
                    <input type="number" min="1" max="5" name="testimonials[${testimonialCount}][rating]" class="form-control" value="5">
                </div>
                <div class="form-group">
                    <label class="form-label">Order</label>
                    <input type="number" name="testimonials[${testimonialCount}][order_column]" class="form-control" value="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Verified</label>
                    <div class="form-check">
                        <input type="checkbox" name="testimonials[${testimonialCount}][verified]" class="form-check-input" checked>
                        <span class="form-check-label">Verified Review</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Patient Photo</label>
                    <input type="file" name="testimonials[${testimonialCount}][photo]" class="form-control">

                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Testimonial Content *</label>
                    <textarea name="testimonials[${testimonialCount}][content]" class="form-control" rows="3" required></textarea>
                </div>
            </div>
        `;
        container.appendChild(item);
        testimonialCount++;
    }

    // Affiliation
    function addAffiliation() {
        const container = document.getElementById('affiliations-container');
        const item = document.createElement('div');
        item.className = 'dynamic-item-card';
        item.innerHTML = `
            <div class="card-header-row">
                <span class="card-title"><i class="fas fa-handshake"></i> Affiliation #${affiliationCount + 1}</span>
                <button type="button" class="remove-btn" onclick="removeItem(this)">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="affiliations[${affiliationCount}][type]" class="form-control">
                        <option value="hospital">Hospital</option>
                        <option value="organization">Organization</option>
                        <option value="society">Society</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" name="affiliations[${affiliationCount}][name]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Position/Role</label>
                    <input type="text" name="affiliations[${affiliationCount}][position]" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Order</label>
                    <input type="number" name="affiliations[${affiliationCount}][order_column]" class="form-control" value="0">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Description</label>
                    <textarea name="affiliations[${affiliationCount}][description]" class="form-control" rows="2"></textarea>
                </div>
            </div>
        `;
        container.appendChild(item);
        affiliationCount++;
    }

    // FAQ
    function addFaq() {
        const container = document.getElementById('faqs-container');
        const item = document.createElement('div');
        item.className = 'dynamic-item-card';
        item.innerHTML = `
            <div class="card-header-row">
                <span class="card-title"><i class="fas fa-question-circle"></i> FAQ #${faqCount + 1}</span>
                <button type="button" class="remove-btn" onclick="removeItem(this)">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
            <div class="form-grid">
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Question *</label>
                    <input type="text" name="faqs[${faqCount}][question]" class="form-control" required>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Answer *</label>
                    <textarea name="faqs[${faqCount}][answer]" class="form-control" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Order</label>
                    <input type="number" name="faqs[${faqCount}][order_column]" class="form-control" value="0">
                </div>
            </div>
        `;
        container.appendChild(item);
        faqCount++;
    }

    // Telemedicine Platform
    function addTelemedicinePlatform() {
        const container = document.getElementById('telemedicine-platforms-container');
        const item = document.createElement('div');
        item.className = 'dynamic-item-card';
        item.innerHTML = `
            <div class="card-header-row">
                <span class="card-title"><i class="fas fa-video"></i> Platform #${platformCount + 1}</span>
                <button type="button" class="remove-btn" onclick="removeItem(this)">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Platform Name *</label>
                    <input type="text" name="telemedicine_platforms[${platformCount}][name]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Icon</label>
                    <select name="telemedicine_platforms[${platformCount}][icon]" class="form-control">
                        <option value="ri-vidicon-fill">Vidicon</option>
                        <option value="ri-video-fill">Video</option>
                        <option value="ri-phone-fill">Phone</option>
                        <option value="ri-chat-1-fill">Chat</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Color</label>
                    <input type="color" name="telemedicine_platforms[${platformCount}][color]" class="form-control" value="#3b82f6">
                </div>
                <div class="form-group">
                    <label class="form-label">Order</label>
                    <input type="number" name="telemedicine_platforms[${platformCount}][order_column]" class="form-control" value="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Active</label>
                    <div class="form-check">
                        <input type="checkbox" name="telemedicine_platforms[${platformCount}][active]" class="form-check-input" checked>
                        <span class="form-check-label">Active Platform</span>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(item);
        platformCount++;
    }

    // Gallery
    function addGalleryItem() {
        const container = document.getElementById('gallery-container');
        const item = document.createElement('div');
        item.className = 'dynamic-item-card';
        item.innerHTML = `
            <div class="card-header-row">
                <span class="card-title"><i class="fas fa-images"></i> Gallery Item #${galleryCount + 1}</span>
                <button type="button" class="remove-btn" onclick="removeItem(this)">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="gallery[${galleryCount}][title]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="gallery[${galleryCount}][category]" class="form-control">
                        <option value="facility">Facility</option>
                        <option value="care">Care</option>
                        <option value="technology">Technology</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Order</label>
                    <input type="number" name="gallery[${galleryCount}][order_column]" class="form-control" value="0">

                </div>
                <div class="form-group">
                    <label class="form-label">Image</label>
                    <input type="file" name="gallery[${galleryCount}][image_url]" class="form-control">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Caption</label>
                    <textarea name="gallery[${galleryCount}][caption]" class="form-control" rows="2"></textarea>
                </div>
            </div>
        `;
        container.appendChild(item);
        galleryCount++;
    }

    // Remove item
    function removeItem(button) {
        if (confirm('Are you sure you want to remove this item?')) {
            button.closest('.dynamic-item-card').remove();
        }
    }

    // Image preview
    function previewImage(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const area = input.closest('.image-upload-area');
                area.innerHTML = `
                    <img src="${e.target.result}" class="image-preview" alt="Preview">
                    <p class="mb-2 text-muted">Click to change image</p>
                `;
                area.onclick = () => input.click();
            };
            reader.readAsDataURL(file);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[type="file"][accept="image/*"]').forEach(input => {
            input.addEventListener('change', function() {
                previewImage(this);
            });
        });
    });

    // Toast notification
    function showToast(message, type = 'info') {
        const container = document.querySelector('.toast-container');
        const toastId = 'toast-' + Date.now();

        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'primary'} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;

        container.appendChild(toast);

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 3000);
    }

    // Profile Photo Preview Function
function previewProfilePhoto(input) {
    const file = input.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const uploadArea = document.getElementById('photo-upload-area');
        const preview = document.getElementById('photo-preview');
        const icon = document.getElementById('photo-icon');
        const text = document.getElementById('photo-text');
        const hint = document.getElementById('photo-hint');

        if (preview) {
            preview.src = e.target.result;
        } else {
            // Create preview image if it doesn't exist
            if (icon) icon.remove();
            if (text) text.remove();
            if (hint) hint.remove();

            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'image-preview';
            img.alt = 'Profile Photo';
            img.id = 'photo-preview';

            const p = document.createElement('p');
            p.className = 'mb-2 text-muted';
            p.textContent = 'Click to change photo';

            uploadArea.prepend(p);
            uploadArea.prepend(img);
        }
    };
    reader.readAsDataURL(file);
}

// Hero Image Preview Function
function previewHeroImage(input) {
    const file = input.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const uploadArea = document.getElementById('hero-upload-area');
        const preview = document.getElementById('hero-preview');
        const icon = document.getElementById('hero-icon');
        const text = document.getElementById('hero-text');
        const hint = document.getElementById('hero-hint');

        if (preview) {
            preview.src = e.target.result;
        } else {
            // Create preview image if it doesn't exist
            if (icon) icon.remove();
            if (text) text.remove();
            if (hint) hint.remove();

            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'image-preview';
            img.alt = 'Hero Image';
            img.id = 'hero-preview';

            const p = document.createElement('p');
            p.className = 'mb-2 text-muted';
            p.textContent = 'Click to change image';

            uploadArea.prepend(p);
            uploadArea.prepend(img);
        }
    };
    reader.readAsDataURL(file);
}

// Also update the existing previewImage function to be more generic
function previewImage(input, type = 'profile') {
    const file = input.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const area = input.closest('.image-upload-area');
        const preview = area.querySelector('img');

        if (preview) {
            preview.src = e.target.result;
        } else {
            // Remove existing content
            area.innerHTML = '';

            // Create new preview
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'image-preview';
            img.alt = type === 'profile' ? 'Profile Photo' : 'Hero Image';

            const p = document.createElement('p');
            p.className = 'mb-2 text-muted';
            p.textContent = 'Click to change image';

            // Re-add the file input
            const fileInput = input.cloneNode(true);
            fileInput.style.display = 'none';
            fileInput.onchange = input.onchange;

            area.appendChild(img);
            area.appendChild(p);
            area.appendChild(fileInput);

            // Reattach click event
            area.onclick = () => fileInput.click();
        }
    };
    reader.readAsDataURL(file);
}

// Also update the existing DOMContentLoaded event listener for testimonial and gallery images
document.addEventListener('DOMContentLoaded', function() {
    // Set up event listeners for testimonial photo inputs
    document.querySelectorAll('input[name*="testimonials"][name*="photo"]').forEach(input => {
        input.addEventListener('change', function(e) {
            previewTestimonialPhoto(this);
        });
    });

    // Set up event listeners for gallery image inputs
    document.querySelectorAll('input[name*="gallery"][name*="image_url"]').forEach(input => {
        input.addEventListener('change', function(e) {
            previewGalleryImage(this);
        });
    });
});

// Testimonial photo preview
function previewTestimonialPhoto(input) {
    const file = input.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const container = input.closest('.dynamic-item-card');
        const existingPreview = container.querySelector('img');

        if (existingPreview) {
            existingPreview.src = e.target.result;
        } else {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.width = 80;
            img.className = 'rounded mt-2';
            img.alt = 'Patient Photo';

            // Insert after the file input
            input.parentNode.appendChild(img);
        }
    };
    reader.readAsDataURL(file);
}

// Gallery image preview
function previewGalleryImage(input) {
    const file = input.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const container = input.closest('.dynamic-item-card');
        const existingPreview = container.querySelector('img');

        if (existingPreview) {
            existingPreview.src = e.target.result;
        } else {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.width = 80;
            img.className = 'rounded mt-2';
            img.alt = 'Gallery Image';

            // Insert after the file input
            input.parentNode.appendChild(img);
        }
    };
    reader.readAsDataURL(file);
}
</script>
@endsection
