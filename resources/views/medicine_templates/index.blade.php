@extends('layouts.admin')
@section('title', 'Medicine Template Management')
@section('content')
<style>
    /* Keep all your existing CSS styles exactly as they were */
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.1);
        --primary-dark: #2a6d5a;
        --primary-soft: rgba(49, 128, 105, 0.05);
    }

    .container-fluid {
        margin: 0 auto;
        padding: 20px;
    }

    /* Page Header */
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .page-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .page-title h1 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-subtitle {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .btn-create {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-create:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(49, 128, 105, 0.2);
    }

    /* Search Bar */
    .search-container {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #111827;
        transition: all 0.2s ease;
        background: white;
    }

    .search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    /* Templates Grid */
    .templates-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .template-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 1rem;
        transition: all 0.2s ease;
        cursor: pointer;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .template-card:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(49, 128, 105, 0.1);
    }

    .template-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .template-info {
        flex: 1;
        min-width: 0;
    }

    .template-name {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .template-generic {
        color: #6b7280;
        font-size: 0.75rem;
        margin-bottom: 0.5rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .template-badge {
        display: inline-block;
        padding: 0.125rem 0.5rem;
        border-radius: 12px;
        font-size: 0.675rem;
        font-weight: 500;
    }

    .badge-tablet { background: #dbeafe; color: #1d4ed8; }
    .badge-syrup { background: #f3e8ff; color: #7c3aed; }
    .badge-capsule { background: #fef3c7; color: #d97706; }
    .badge-injection { background: #fee2e2; color: #dc2626; }
    .badge-ointment { background: #dcfce7; color: #15803d; }
    .badge-drop { background: #e0e7ff; color: #3730a3; }
    .badge-inhaler { background: #f0f9ff; color: #0369a1; }
    .badge-suspension { background: #fef3c7; color: #d97706; }
    .badge-powder { background: #f5f5f4; color: #57534e; }
    .badge-cream { background: #fefce8; color: #ca8a04; }

    .template-actions {
        display: flex;
        gap: 0.25rem;
        flex-shrink: 0;
    }

    .btn-icon {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.7rem;
        background: white;
        border: 1px solid #e5e7eb;
    }

    .btn-icon:hover {
        background: #f9fafb;
    }

    .btn-edit:hover {
        color: var(--primary);
        border-color: var(--primary);
    }

    .btn-delete:hover {
        color: #dc2626;
        border-color: #dc2626;
    }

    .template-details {
        flex: 1;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.125rem;
    }

    .detail-label {
        color: #6b7280;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        color: #111827;
        font-size: 0.8rem;
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .detail-company {
        grid-column: 1 / -1;
    }

    .template-footer {
        padding-top: 0.75rem;
        border-top: 1px solid #f3f4f6;
        margin-top: auto;
    }

    .template-comments {
        font-size: 0.75rem;
        color: #6b7280;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        overflow-y: auto;
        align-items: flex-start;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        width: 100%;
        max-width: 700px;
        margin: 0 auto;
        min-height: fit-content;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background: var(--primary);
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-size: 1rem;
        font-weight: 600;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.125rem;
        color: white;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .modal-close:hover {
        color: #f0f0f0;
    }

    .modal-body {
        padding: 1.25rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        font-weight: 500;
        color: #374151;
        font-size: 0.8125rem;
        margin-bottom: 0.375rem;
    }

    .form-label .required {
        color: #dc2626;
    }

    .form-input {
        width: 100%;
        padding: 0.625rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.8125rem;
        color: #111827;
        transition: all 0.2s ease;
        background: white;
    }

    .form-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    .form-select {
        width: 100%;
        padding: 0.625rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.8125rem;
        color: #111827;
        background: white;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 12px;
    }

    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    textarea.form-input {
        min-height: 80px;
        resize: vertical;
        font-size: 0.8125rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid #e5e7eb;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.8125rem;
        transition: all 0.2s ease;
        flex: 1;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(49, 128, 105, 0.2);
    }

    .btn-secondary {
        background: white;
        color: #374151;
        border: 1px solid #d1d5db;
        padding: 0.75rem 1.25rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.8125rem;
        transition: all 0.2s ease;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: var(--primary);
        color: var(--primary);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #9ca3af;
        border: 2px dashed #e5e7eb;
        border-radius: 10px;
        background: #fafafa;
        grid-column: 1 / -1;
    }

    .empty-state-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state p {
        margin: 0;
        font-size: 0.875rem;
    }

    .view-mode .form-input,
    .view-mode .form-select {
        background-color: #f9fafb;
        border-color: #e5e7eb;
        color: #6b7280;
        cursor: default;
        pointer-events: none;
    }

    .view-mode .form-select {
        background-image: none;
    }

    .loading {
        display: none;
        text-align: center;
        padding: 2rem;
        color: #6b7280;
    }

    .loading-spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid var(--primary);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Toast Notification Styles */
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 9999;
        animation: slideIn 0.3s ease, fadeOut 0.3s ease 2.7s forwards;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        max-width: 350px;
    }

    .toast-success {
        background-color: var(--primary);
    }

    .toast-error {
        background-color: #dc2626;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <div>
                <h1>
                    <i class="fas fa-pills"></i>
                    Medicine Templates
                </h1>
            </div>
            <button type="button" class="btn-create" onclick="openCreateModal()">
                <i class="fas fa-plus"></i> New Template
            </button>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="search-container">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="searchInput" class="search-input"
               placeholder="Search by name, generic name, or company..."
               onkeyup="searchTemplates()">
    </div>

    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="loading" style="display: none;">
        <div class="loading-spinner"></div>
        <p>Loading templates...</p>
    </div>

    <!-- Templates Grid -->
    <div id="templatesGrid" class="templates-grid">
        <!-- Templates will be loaded via PHP initially -->
        @if($templates->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-pills"></i>
                </div>
                <p>No medicine templates found</p>
                <button type="button" class="btn-create" onclick="openCreateModal()" style="margin-top: 1rem;">
                    <i class="fas fa-plus"></i> Create First Template
                </button>
            </div>
        @else
            @foreach($templates as $template)
                <div class="template-card" onclick="viewTemplate({{ $template->id }})">
                    <div class="template-header">
                        <div class="template-info">
                            <div class="template-name">{{ $template->medicine_name }}</div>
                            <div class="template-generic">{{ $template->generic_name ?: 'N/A' }}</div>
                            <span class="template-badge {{ getBadgeClass($template->medicine_type) }}">
                                {{ $template->medicine_type }}
                            </span>
                        </div>
                        <div class="template-actions">
                            <button class="btn-icon btn-edit" onclick="editMedicine({{ $template->id }}); event.stopPropagation();" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon btn-delete" onclick="confirmDelete({{ $template->id }}); event.stopPropagation();" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="template-details">
                        <div class="detail-item">
                            <span class="detail-label">Dose</span>
                            <span class="detail-value">{{ $template->medicine_dose }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Days</span>
                            <span class="detail-value">
                                @if(in_array($template->medicine_day, ['Continuously', 'Until finished']))
                                    {{ $template->medicine_day }}
                                @else
                                    {{ $template->medicine_day }} days
                                @endif
                            </span>
                        </div>
                        @if($template->medicine_mg)
                            <div class="detail-item">
                                <span class="detail-label">Strength</span>
                                <span class="detail-value">{{ $template->medicine_mg }}</span>
                            </div>
                        @endif
                        @if($template->company_name)
                            <div class="detail-item detail-company">
                                <span class="detail-label">Company</span>
                                <span class="detail-value">{{ $template->company_name }}</span>
                            </div>
                        @endif
                    </div>

                    @if($template->medicine_comments)
                        <div class="template-footer">
                            <div class="template-comments">
                                <strong>Note:</strong> {{ $template->medicine_comments }}
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>

<!-- Create/Edit Modal -->
<div class="modal-overlay" id="medicineModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                <i class="fas fa-pills mr-2"></i>
                <span id="modalTitle">Create Medicine Template</span>
            </h5>
            <button type="button" class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="medicineForm">
                <!-- CSRF Token will be added by JavaScript -->
                <input type="hidden" id="medicineId" name="id">

                <div class="form-grid">
                    <!-- JSON Medicine Select -->
                    {{-- <div class="form-group full-width">
                        <label class="form-label">Select From Medicine List</label>
                        <select id="jsonMedicineSelect" class="form-select">
                            <option value="">Search & select medicine</option>
                        </select>
                    </div> --}}

                    <!-- Medicine Name -->
                    <div class="form-group full-width">
                        <label class="form-label">Medicine Name <span class="required">*</span></label>
                        <input type="text" id="medicineName" name="medicine_name" class="form-input"
                               placeholder="e.g., Paracetamol 500mg" required>
                    </div>

                    <!-- Generic Name -->
                    <div class="form-group">
                        <label class="form-label">Generic Name</label>
                        <input type="text" id="genericName" name="generic_name" class="form-input"
                               placeholder="e.g., Acetaminophen">
                    </div>

                    <!-- Company -->
                    <div class="form-group">
                        <label class="form-label">Company</label>
                        <select id="company" name="company_name" class="form-select">
                            <option value="">Select Company</option>
                           @foreach ($medcinCompany as $value)
                            <option value="{{ $value->company_name }}">{{ $value->company_name }}</option>
                           @endforeach
                        </select>
                    </div>

                    <!-- Type -->
                    <div class="form-group">
                        <label class="form-label">Type <span class="required">*</span></label>
                        <select id="type" name="medicine_type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="Tablet">Tablet</option>
                            <option value="Syrup">Syrup</option>
                            <option value="Capsule">Capsule</option>
                            <option value="Injection">Injection</option>
                            <option value="Ointment">Ointment</option>
                            <option value="Drop">Drop</option>
                            <option value="Inhaler">Inhaler</option>
                            <option value="Suspension">Suspension</option>
                            <option value="Powder">Powder</option>
                            <option value="Cream">Cream</option>
                        </select>
                    </div>

                    <!-- Dose Schedule -->
                    <div class="form-group">
                        <label class="form-label">Dose Schedule <span class="required">*</span></label>
                        <select id="dose" name="medicine_dose" class="form-select" required>
                            <option value="">Select Dose</option>
                            <option value="1+0+1">1+0+1 (Morning & Night)</option>
                            <option value="1+1+1">1+1+1 (Three times daily)</option>
                            <option value="0+0+1">0+0+1 (Night only)</option>
                            <option value="1+0+0">1+0+0 (Morning only)</option>
                            <option value="0+1+0">0+1+0 (Afternoon only)</option>
                            <option value="1+1+0">1+1+0 (Morning & Afternoon)</option>
                            <option value="0+0+0+1">0+0+0+1 (Once daily)</option>
                            <option value="As needed">As needed</option>
                            <option value="Custom">Custom (Specify in comments)</option>
                        </select>
                    </div>

                    <!-- Days -->
                    <div class="form-group">
                        <label class="form-label">Days <span class="required">*</span></label>
                        <select id="days" name="medicine_day" class="form-select" required>
                            <option value="">Select Days</option>
                            <option value="1">1 Day</option>
                            <option value="3">3 Days</option>
                            <option value="5">5 Days</option>
                            <option value="7">7 Days</option>
                            <option value="10">10 Days</option>
                            <option value="14">14 Days</option>
                            <option value="21">21 Days</option>
                            <option value="30">30 Days</option>
                            <option value="60">60 Days</option>
                            <option value="90">90 Days</option>
                            <option value="Continuously">Continuously</option>
                            <option value="Until finished">Until finished</option>
                        </select>
                    </div>

                    <!-- Strength -->
                    <div class="form-group">
                        <label class="form-label">Strength (mg/ml)</label>
                        <select id="strength" name="medicine_mg" class="form-select">
                            <option value="">Select Strength</option>
                            <option value="5mg">5mg</option>
                            <option value="10mg">10mg</option>
                            <option value="20mg">20mg</option>
                            <option value="25mg">25mg</option>
                            <option value="50mg">50mg</option>
                            <option value="75mg">75mg</option>
                            <option value="100mg">100mg</option>
                            <option value="125mg">125mg</option>
                            <option value="250mg">250mg</option>
                            <option value="500mg">500mg</option>
                            <option value="625mg">625mg</option>
                            <option value="1000mg">1000mg</option>
                            <option value="5ml">5ml</option>
                            <option value="10ml">10ml</option>
                            <option value="15ml">15ml</option>
                            <option value="20ml">20ml</option>
                            <option value="50ml">50ml</option>
                            <option value="100ml">100ml</option>
                            <option value="200ml">200ml</option>
                            <option value="500ml">500ml</option>
                            <option value="Custom">Custom Strength</option>
                        </select>
                    </div>

                    <!-- Custom Strength Input -->
                    <div class="form-group" id="customStrengthGroup" style="display: none;">
                        <label class="form-label">Custom Strength</label>
                        <input type="text" id="customStrength" name="custom_strength" class="form-input"
                               placeholder="e.g., 250mg/5ml, 10mg/5ml">
                    </div>

                    <!-- Medicine URL -->
                    <div class="form-group full-width">
                        <label class="form-label">Medicine URL</label>
                        <input type="url" id="medicineUrl" name="medicine_url" class="form-input"
                               placeholder="https://example.com/medicine-info">
                    </div>

                    <!-- Comments -->
                    <div class="form-group">
                        <label class="form-label">Comments</label>
                        <textarea id="comments" name="medicine_comments" class="form-input" rows="2"
                                  placeholder="Special instructions or comments..."></textarea>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea id="description" name="medicine_description" class="form-input" rows="2"
                                  placeholder="Detailed description of the medicine..."></textarea>
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="button" class="btn-secondary" onclick="closeModal()">
                        Cancel
                    </button>
                    <button type="button" class="btn-primary" id="submitButton" onclick="saveMedicine()">
                        <span id="saveButtonText">Save Template</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal-overlay" id="confirmModal" style="display: none;">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header">
            <h5 class="modal-title">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span id="confirmTitle">Confirm Action</span>
            </h5>
            <button type="button" class="modal-close" onclick="closeConfirmModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p id="confirmMessage">Are you sure you want to delete this template?</p>
            <div class="action-buttons">
                <button type="button" class="btn-secondary" onclick="closeConfirmModal()">
                    Cancel
                </button>
                <button type="button" class="btn-primary" id="confirmActionButton">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

{{-- <script>
    // Global variables
    let currentMedicineId = null;
    let isViewMode = false;
    let jsonMedicines = [];

    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide custom strength input
        document.getElementById('strength').addEventListener('change', function() {
            const customStrengthGroup = document.getElementById('customStrengthGroup');
            if (this.value === 'Custom') {
                customStrengthGroup.style.display = 'block';
                document.getElementById('customStrength').focus();
            } else {
                customStrengthGroup.style.display = 'none';
                document.getElementById('customStrength').value = '';
            }
        });

        // Load JSON medicines for dropdown
        loadJsonMedicines();
    });

    // PHP Helper function (for initial load)
    @php
        function getBadgeClass($type) {
            if (!$type) return 'badge-tablet';

            switch(strtolower($type)) {
                case 'tablet': return 'badge-tablet';
                case 'syrup': return 'badge-syrup';
                case 'capsule': return 'badge-capsule';
                case 'injection': return 'badge-injection';
                case 'ointment': return 'badge-ointment';
                case 'drop': return 'badge-drop';
                case 'inhaler': return 'badge-inhaler';
                case 'suspension': return 'badge-suspension';
                case 'powder': return 'badge-powder';
                case 'cream': return 'badge-cream';
                default: return 'badge-tablet';
            }
        }
    @endphp

    // Load JSON medicines for dropdown
    function loadJsonMedicines() {
        fetch('{{ route("admin.medicine-templates.json") }}')
            .then(res => {
                if (!res.ok) throw new Error('Failed to load medicine list');
                return res.json();
            })
            .then(data => {
                jsonMedicines = data;
                const select = document.getElementById('jsonMedicineSelect');
                select.innerHTML = `<option value="">Search & select medicine</option>`;

                data.forEach((med, index) => {
                    select.innerHTML += `
                        <option value="${index}">
                            ${med.medicine_name || 'Unknown'} ${med.med_mg ? `(${med.med_mg})` : ''}
                        </option>
                    `;
                });

                // Add event listener for when user selects from dropdown
                select.addEventListener('change', function() {
                    const index = this.value;
                    if (index === '') return;

                    const med = jsonMedicines[index];
                    if (!med) return;

                    // Auto-fill form fields
                    document.getElementById('medicineName').value = med.medicine_name || '';
                    document.getElementById('genericName').value = med.med_group_id || '';

                    // Set type
                    const type = normalizeType(med.med_type);
                    if (type) document.getElementById('type').value = type;

                    // Set dose
                    const dose = normalizeDose(med.med_dose);
                    if (dose) document.getElementById('dose').value = dose;

                    // Set days
                    const days = normalizeDay(med.med_day);
                    if (days) document.getElementById('days').value = days;

                    // Set strength
                    const strength = normalizeStrength(med.med_mg);
                    if (strength === 'Custom' && med.med_mg) {
                        document.getElementById('strength').value = 'Custom';
                        document.getElementById('customStrength').value = med.med_mg;
                        document.getElementById('customStrengthGroup').style.display = 'block';
                    } else if (strength) {
                        document.getElementById('strength').value = strength;
                        document.getElementById('customStrengthGroup').style.display = 'none';
                    }

                    document.getElementById('comments').value = med.med_comments || '';
                    document.getElementById('description').value = med.med_description || '';
                });
            })
            .catch(error => {
                console.error('Error loading JSON medicines:', error);
            });
    }

    // Helper functions for normalizing JSON data
    function normalizeType(type) {
        if (!type) return '';
        type = type.toLowerCase();
        if (type.includes('tab')) return 'Tablet';
        if (type.includes('syr')) return 'Syrup';
        if (type.includes('cap')) return 'Capsule';
        if (type.includes('inj')) return 'Injection';
        if (type.includes('oint')) return 'Ointment';
        if (type.includes('drop')) return 'Drop';
        if (type.includes('inh')) return 'Inhaler';
        if (type.includes('sus')) return 'Suspension';
        if (type.includes('pow')) return 'Powder';
        if (type.includes('cre')) return 'Cream';
        return type.charAt(0).toUpperCase() + type.slice(1);
    }

    function normalizeDose(dose) {
        if (!dose) return '';
        // Convert common dose patterns to our format
        const doseMap = {
            '1+0+1': '1+0+1',
            '1+1+1': '1+1+1',
            '0+0+1': '0+0+1',
            '1+0+0': '1+0+0',
            '0+1+0': '0+1+0',
            '1+1+0': '1+1+0',
            'once daily': '0+0+0+1',
            'twice daily': '1+0+1',
            'three times daily': '1+1+1',
            'as needed': 'As needed'
        };

        dose = dose.replace(/\s/g, '').toLowerCase();
        return doseMap[dose] || dose;
    }

    function normalizeDay(day) {
        if (!day) return '';
        day = day.toString();
        // Extract number from string
        const match = day.match(/\d+/);
        return match ? match[0] : day;
    }

    function normalizeStrength(mg) {
        if (!mg) return '';
        mg = mg.trim().toLowerCase();

        // Check if it's one of our predefined values
        const predefined = [
            '5mg','10mg','20mg','25mg','50mg','75mg','100mg','125mg','250mg','500mg','625mg','1000mg',
            '5ml','10ml','15ml','20ml','50ml','100ml','200ml','500ml'
        ];

        if (predefined.includes(mg)) {
            return mg;
        }

        // If it contains mg or ml but not in predefined list, treat as custom
        if (mg.includes('mg') || mg.includes('ml')) {
            return 'Custom';
        }

        return '';
    }

    // Show/Hide Loading Indicator
    function showLoading(show) {
        const loadingIndicator = document.getElementById('loadingIndicator');
        const templatesGrid = document.getElementById('templatesGrid');

        if (show) {
            loadingIndicator.style.display = 'block';
            templatesGrid.style.opacity = '0.5';
        } else {
            loadingIndicator.style.display = 'none';
            templatesGrid.style.opacity = '1';
        }
    }

    // Show Toast Notification
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    // Search Templates
    function searchTemplates() {
        const searchTerm = document.getElementById('searchInput').value.trim();

        if (searchTerm.length === 0) {
            location.reload();
            return;
        }
 
        showLoading(true);

        fetch(`{{ route("admin.medicine-templates.search") }}?search=${encodeURIComponent(searchTerm)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(templates => {
            showLoading(false);
            displayTemplates(templates);
        })
        .catch(error => {
            showLoading(false);
            console.error('Error searching templates:', error);
            showToast('Failed to search templates', 'error');
        });
    }

    // Display Templates from AJAX search
    function displayTemplates(templates) {
        const container = document.getElementById('templatesGrid');
        const noTemplatesMsg = document.querySelector('#noTemplatesMessage');

        if (!templates || templates.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <p>No templates found matching your search</p>
                </div>
            `;
            if (noTemplatesMsg) {
                noTemplatesMsg.style.display = 'none';
            }
            return;
        }

        if (noTemplatesMsg) {
            noTemplatesMsg.style.display = 'none';
        }

        // Sort by name
        const sortedTemplates = [...templates].sort((a, b) =>
            a.medicine_name.localeCompare(b.medicine_name)
        );

        container.innerHTML = sortedTemplates.map(template => {
            // Get badge class based on type
            const badgeClass = getBadgeClassJS(template.medicine_type);

            // Format days display
            const daysDisplay = template.medicine_day === 'Continuously' || template.medicine_day === 'Until finished'
                ? template.medicine_day
                : `${template.medicine_day} days`;

            return `
                <div class="template-card" onclick="viewTemplate(${template.id})">
                    <div class="template-header">
                        <div class="template-info">
                            <div class="template-name">${escapeHtml(template.medicine_name)}</div>
                            <div class="template-generic">${escapeHtml(template.generic_name || 'N/A')}</div>
                            <span class="template-badge ${badgeClass}">${escapeHtml(template.medicine_type)}</span>
                        </div>
                        <div class="template-actions">
                            <button class="btn-icon btn-edit" onclick="editMedicine(${template.id}); event.stopPropagation();" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon btn-delete" onclick="confirmDelete(${template.id}); event.stopPropagation();" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="template-details">
                        <div class="detail-item">
                            <span class="detail-label">Dose</span>
                            <span class="detail-value">${escapeHtml(template.medicine_dose)}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Days</span>
                            <span class="detail-value">${daysDisplay}</span>
                        </div>
                        ${template.medicine_mg ? `
                            <div class="detail-item">
                                <span class="detail-label">Strength</span>
                                <span class="detail-value">${escapeHtml(template.medicine_mg)}</span>
                            </div>
                        ` : ''}
                        ${template.company_name ? `
                            <div class="detail-item detail-company">
                                <span class="detail-label">Company</span>
                                <span class="detail-value">${escapeHtml(template.company_name)}</span>
                            </div>
                        ` : ''}
                    </div>

                    ${template.medicine_comments ? `
                        <div class="template-footer">
                            <div class="template-comments">
                                <strong>Note:</strong> ${escapeHtml(template.medicine_comments)}
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;
        }).join('');
    }

    // Get Badge Class (JavaScript version)
    function getBadgeClassJS(type) {
        if (!type) return 'badge-tablet';

        switch((type || '').toLowerCase()) {
            case 'tablet': return 'badge-tablet';
            case 'syrup': return 'badge-syrup';
            case 'capsule': return 'badge-capsule';
            case 'injection': return 'badge-injection';
            case 'ointment': return 'badge-ointment';
            case 'drop': return 'badge-drop';
            case 'inhaler': return 'badge-inhaler';
            case 'suspension': return 'badge-suspension';
            case 'powder': return 'badge-powder';
            case 'cream': return 'badge-cream';
            default: return 'badge-tablet';
        }
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Open Create Modal
    function openCreateModal() {
        currentMedicineId = null;
        isViewMode = false;
        document.getElementById('modalTitle').textContent = 'Create Medicine Template';
        document.getElementById('saveButtonText').textContent = 'Save Template';
        document.getElementById('medicineId').value = '';

        // Reset form
        resetForm();

        // Remove view mode classes
        document.getElementById('medicineForm').classList.remove('view-mode');

        // Change cancel button text back
        const cancelBtn = document.querySelector('#medicineModal .btn-secondary');
        if (cancelBtn) cancelBtn.textContent = 'Cancel';

        // Set submit button back to save function
        const submitBtn = document.getElementById('submitButton');
        submitBtn.onclick = saveMedicine;

        document.getElementById('medicineModal').style.display = 'flex';
    }

    // Close Modal
    function closeModal() {
        document.getElementById('medicineModal').style.display = 'none';
        isViewMode = false;
        document.getElementById('medicineForm').classList.remove('view-mode');
    }

    // Reset Form
    function resetForm() {
        document.getElementById('medicineForm').reset();
        document.getElementById('jsonMedicineSelect').value = '';
        document.getElementById('customStrengthGroup').style.display = 'none';
        document.getElementById('customStrength').value = '';
    }

    // Save Medicine (Create/Update)
    function saveMedicine() {
        // Basic validation
        const medicineName = document.getElementById('medicineName').value.trim();
        if (!medicineName) {
            showToast('Please enter medicine name', 'error');
            document.getElementById('medicineName').focus();
            return;
        }

        const medicineType = document.getElementById('type').value;
        if (!medicineType) {
            showToast('Please select medicine type', 'error');
            document.getElementById('type').focus();
            return;
        }

        const medicineDose = document.getElementById('dose').value;
        if (!medicineDose) {
            showToast('Please select dose schedule', 'error');
            document.getElementById('dose').focus();
            return;
        }

        const medicineDay = document.getElementById('days').value;
        if (!medicineDay) {
            showToast('Please select days', 'error');
            document.getElementById('days').focus();
            return;
        }

        // Get form data
        const medicineId = document.getElementById('medicineId').value;
        const isUpdate = !!medicineId;

        // Create form data
        const formData = new FormData();

        // Add CSRF token
        formData.append('_token', csrfToken);

        // Add method spoofing for PUT requests
        if (isUpdate) {
            formData.append('_method', 'PUT');
        }

        // Collect form values
        formData.append('medicine_name', medicineName);
        formData.append('generic_name', document.getElementById('genericName').value.trim());
        formData.append('company_name', document.getElementById('company').value);
        formData.append('medicine_type', medicineType);
        formData.append('medicine_dose', medicineDose);
        formData.append('medicine_day', medicineDay);

        // Handle strength
        const strength = document.getElementById('strength').value;
        if (strength === 'Custom') {
            const customStrength = document.getElementById('customStrength').value.trim();
            formData.append('medicine_mg', customStrength || '');
        } else {
            formData.append('medicine_mg', strength);
        }

        formData.append('medicine_url', document.getElementById('medicineUrl').value.trim());
        formData.append('medicine_comments', document.getElementById('comments').value.trim());
        formData.append('medicine_description', document.getElementById('description').value.trim());

        // Show loading on button
        const submitBtn = document.getElementById('submitButton');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        submitBtn.disabled = true;

        // Determine URL
        const url = isUpdate ?
            `/admin/medicine-templates/${medicineId}` :
            '{{ route("admin.medicine-templates.store") }}';

        // Send request
        fetch(url, {
            method: 'POST', // Always POST, using _method for PUT
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                closeModal();
                location.reload(); // Reload page to refresh the list
                showToast(data.message, 'success');
            } else {
                throw new Error(data.message || 'Failed to save template');
            }
        })
        .catch(error => {
            console.error('Error saving template:', error);
            showToast(error.message || 'Failed to save template. Please try again.', 'error');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }

    // Edit Medicine
    function editMedicine(medicineId) {
        showLoading(true);

        fetch(`/admin/medicine-templates/${medicineId}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch template');
            }
            return response.json();
        })
        .then(medicine => {
            showLoading(false);
            if (!medicine) {
                showToast('Template not found', 'error');
                return;
            }

            currentMedicineId = medicineId;
            isViewMode = false;
            document.getElementById('modalTitle').textContent = 'Edit Medicine Template';
            document.getElementById('saveButtonText').textContent = 'Update Template';
            document.getElementById('medicineId').value = medicineId;

            // Remove view mode classes
            document.getElementById('medicineForm').classList.remove('view-mode');

            // Reset JSON select
            document.getElementById('jsonMedicineSelect').value = '';

            // Populate form
            document.getElementById('medicineName').value = medicine.medicine_name || '';
            document.getElementById('genericName').value = medicine.generic_name || '';
            document.getElementById('company').value = medicine.company_name || '';
            document.getElementById('type').value = medicine.medicine_type || '';
            document.getElementById('dose').value = medicine.medicine_dose || '';
            document.getElementById('days').value = medicine.medicine_day || '';

            // Handle strength
            let strengthValue = medicine.medicine_mg || '';
            const isCustomStrength = strengthValue && ![
                '5mg','10mg','20mg','25mg','50mg','75mg','100mg','125mg','250mg','500mg','625mg','1000mg',
                '5ml','10ml','15ml','20ml','50ml','100ml','200ml','500ml'
            ].includes(strengthValue);

            if (isCustomStrength && strengthValue) {
                document.getElementById('strength').value = 'Custom';
                document.getElementById('customStrength').value = strengthValue;
                document.getElementById('customStrengthGroup').style.display = 'block';
            } else {
                document.getElementById('strength').value = strengthValue;
                document.getElementById('customStrengthGroup').style.display = 'none';
            }

            document.getElementById('medicineUrl').value = medicine.medicine_url || '';
            document.getElementById('comments').value = medicine.medicine_comments || '';
            document.getElementById('description').value = medicine.medicine_description || '';

            // Show modal
            document.getElementById('medicineModal').style.display = 'flex';
        })
        .catch(error => {
            showLoading(false);
            console.error('Error loading template:', error);
            showToast('Failed to load template. Please try again.', 'error');
        });
    }

    // View Template
    function viewTemplate(medicineId) {
        showLoading(true);

        fetch(`/admin/medicine-templates/${medicineId}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch template');
            }
            return response.json();
        })
        .then(medicine => {
            showLoading(false);
            if (!medicine) {
                showToast('Template not found', 'error');
                return;
            }

            currentMedicineId = medicineId;
            isViewMode = true;
            document.getElementById('modalTitle').textContent = medicine.medicine_name || 'View Template';
            document.getElementById('saveButtonText').textContent = 'Edit Template';
            document.getElementById('medicineId').value = medicineId;

            // Add view mode class
            document.getElementById('medicineForm').classList.add('view-mode');

            // Change button text
            const cancelBtn = document.querySelector('#medicineModal .btn-secondary');
            if (cancelBtn) cancelBtn.textContent = 'Close';

            // Reset JSON select
            document.getElementById('jsonMedicineSelect').value = '';

            // Populate form (readonly)
            document.getElementById('medicineName').value = medicine.medicine_name || '';
            document.getElementById('genericName').value = medicine.generic_name || '';
            document.getElementById('company').value = medicine.company_name || '';
            document.getElementById('type').value = medicine.medicine_type || '';
            document.getElementById('dose').value = medicine.medicine_dose || '';
            document.getElementById('days').value = medicine.medicine_day || '';

            // Handle strength display
            let strengthValue = medicine.medicine_mg || '';
            const isCustomStrength = strengthValue && ![
                '5mg','10mg','20mg','25mg','50mg','75mg','100mg','125mg','250mg','500mg','625mg','1000mg',
                '5ml','10ml','15ml','20ml','50ml','100ml','200ml','500ml'
            ].includes(strengthValue);

            if (isCustomStrength) {
                document.getElementById('strength').value = 'Custom';
                document.getElementById('customStrength').value = strengthValue;
                document.getElementById('customStrengthGroup').style.display = 'block';
            } else {
                document.getElementById('strength').value = strengthValue;
                document.getElementById('customStrengthGroup').style.display = 'none';
            }

            document.getElementById('medicineUrl').value = medicine.medicine_url || '';
            document.getElementById('comments').value = medicine.medicine_comments || '';
            document.getElementById('description').value = medicine.medicine_description || '';

            // Change save button to edit mode
            const submitBtn = document.getElementById('submitButton');
            submitBtn.onclick = function() {
                editMedicine(medicineId);
            };

            // Show modal
            document.getElementById('medicineModal').style.display = 'flex';
        })
        .catch(error => {
            showLoading(false);
            console.error('Error loading template:', error);
            showToast('Failed to load template. Please try again.', 'error');
        });
    }

    // Confirm Delete
    function confirmDelete(medicineId) {
        currentMedicineId = medicineId;

        // Find medicine name from existing HTML
        const templateCard = document.querySelector(`.template-card[onclick*="viewTemplate(${medicineId})"]`);
        let medicineName = 'this template';

        if (templateCard) {
            const nameElement = templateCard.querySelector('.template-name');
            if (nameElement) {
                medicineName = `"${nameElement.textContent}"`;
            }
        }

        document.getElementById('confirmTitle').textContent = 'Delete Template';
        document.getElementById('confirmMessage').textContent =
            `Are you sure you want to delete ${medicineName}? This action cannot be undone.`;

        // Set up confirmation callback
        const confirmBtn = document.getElementById('confirmActionButton');
        confirmBtn.onclick = function() {
            deleteMedicine(medicineId);
        };

        // Show confirmation modal
        document.getElementById('confirmModal').style.display = 'flex';
    }

    // Delete Medicine
    function deleteMedicine(medicineId) {
        closeConfirmModal();

        // Create form data for DELETE request
        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('_method', 'DELETE');

        fetch(`/admin/medicine-templates/${medicineId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload(); // Reload page to refresh the list
                showToast(data.message, 'success');
            } else {
                throw new Error(data.message || 'Failed to delete template');
            }
        })
        .catch(error => {
            console.error('Error deleting template:', error);
            showToast(error.message || 'Failed to delete template. Please try again.', 'error');
        });
    }

    // Close Confirmation Modal
    function closeConfirmModal() {
        document.getElementById('confirmModal').style.display = 'none';
    }

    // Close modals when clicking outside
    document.addEventListener('click', function(event) {
        const medicineModal = document.getElementById('medicineModal');
        const confirmModal = document.getElementById('confirmModal');

        if (event.target === medicineModal) {
            closeModal();
        }
        if (event.target === confirmModal) {
            closeConfirmModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
            closeConfirmModal();
        }
    });
</script> --}}
<script>
    // Global variables
    let currentMedicineId = null;
    let isViewMode = false;
    let jsonMedicines = [];
    let isJsonMedicinesLoading = false;
    let allMedicines = []; // Store all medicines for search
    let selectedMedicineIndex = null;

    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide custom strength input
        document.getElementById('strength').addEventListener('change', function() {
            const customStrengthGroup = document.getElementById('customStrengthGroup');
            if (this.value === 'Custom') {
                customStrengthGroup.style.display = 'block';
                document.getElementById('customStrength').focus();
            } else {
                customStrengthGroup.style.display = 'none';
                document.getElementById('customStrength').value = '';
            }
        });

        // Create search input for medicines
        createMedicineSearchInput();
    });

    // Create searchable input for medicines
    function createMedicineSearchInput() {
        const medicineNameGroup = document.querySelector('input[name="medicine_name"]').closest('.form-group');
        const existingSearchDiv = document.getElementById('medicineSearchContainer');

        if (existingSearchDiv) {
            existingSearchDiv.remove();
        }

        // Create search container
        const searchContainer = document.createElement('div');
        searchContainer.id = 'medicineSearchContainer';
        searchContainer.className = 'medicine-search-container';
        searchContainer.style.position = 'relative';
        searchContainer.style.marginBottom = '10px';

        // Create search input
        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.id = 'medicineSearchInput';
        searchInput.className = 'form-input';
        searchInput.placeholder = 'Type medicine name to search...';
        searchInput.autocomplete = 'off';
        searchInput.style.width = '100%';
        searchInput.style.paddingRight = '40px';

        // Create search icon
        const searchIcon = document.createElement('div');
        searchIcon.className = 'search-icon';
        searchIcon.innerHTML = '<i class="fas fa-search"></i>';
        searchIcon.style.position = 'absolute';
        searchIcon.style.right = '10px';
        searchIcon.style.top = '50%';
        searchIcon.style.transform = 'translateY(-50%)';
        searchIcon.style.color = '#9ca3af';
        searchIcon.style.pointerEvents = 'none';

        // Create dropdown results container
        const resultsContainer = document.createElement('div');
        resultsContainer.id = 'medicineSearchResults';
        resultsContainer.className = 'search-results';
        resultsContainer.style.display = 'none';
        resultsContainer.style.position = 'absolute';
        resultsContainer.style.width = '100%';
        resultsContainer.style.maxHeight = '300px';
        resultsContainer.style.overflowY = 'auto';
        resultsContainer.style.backgroundColor = 'white';
        resultsContainer.style.border = '1px solid #d1d5db';
        resultsContainer.style.borderRadius = '6px';
        resultsContainer.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
        resultsContainer.style.zIndex = '1000';
        resultsContainer.style.marginTop = '5px';

        // Assemble the search container
        searchContainer.appendChild(searchInput);
        searchContainer.appendChild(searchIcon);
        searchContainer.appendChild(resultsContainer);

        // Insert before the medicine name input
        const medicineNameInput = document.getElementById('medicineName');
        medicineNameInput.parentNode.insertBefore(searchContainer, medicineNameInput);

        // Event listeners for search
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim();

            if (searchTerm.length < 2) {
                resultsContainer.style.display = 'none';
                return;
            }

            searchMedicines(searchTerm);
        });

        searchInput.addEventListener('focus', function() {
            const searchTerm = this.value.trim();
            if (searchTerm.length >= 2) {
                searchMedicines(searchTerm);
            }

            // Load medicines if not loaded
            if (jsonMedicines.length === 0 && !isJsonMedicinesLoading) {
                loadJsonMedicines();
            }
        });

        // Close results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchContainer.contains(e.target)) {
                resultsContainer.style.display = 'none';
            }
        });

        // Add key navigation
        searchInput.addEventListener('keydown', function(e) {
            const results = resultsContainer.querySelectorAll('.search-result-item');
            const activeResult = resultsContainer.querySelector('.search-result-item.active');
            let currentIndex = -1;

            if (activeResult) {
                currentIndex = Array.from(results).indexOf(activeResult);
            }

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (results.length > 0) {
                    const nextIndex = currentIndex < results.length - 1 ? currentIndex + 1 : 0;
                    setActiveResult(results, nextIndex);
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (results.length > 0) {
                    const prevIndex = currentIndex > 0 ? currentIndex - 1 : results.length - 1;
                    setActiveResult(results, prevIndex);
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (activeResult) {
                    selectMedicineFromSearch(activeResult);
                }
            } else if (e.key === 'Escape') {
                resultsContainer.style.display = 'none';
            }
        });
    }

    // Set active result in search
    function setActiveResult(results, index) {
        results.forEach(r => r.classList.remove('active'));
        results[index].classList.add('active');
        results[index].scrollIntoView({ block: 'nearest' });
    }

    // Search medicines function
    function searchMedicines(searchTerm) {
        const resultsContainer = document.getElementById('medicineSearchResults');

        if (jsonMedicines.length === 0) {
            resultsContainer.innerHTML = '<div class="no-results">Loading medicines...</div>';
            resultsContainer.style.display = 'block';
            loadJsonMedicines();
            return;
        }

        const searchLower = searchTerm.toLowerCase();
        const results = jsonMedicines.filter(med => {
            const name = (med.medicine_name || '').toLowerCase();
            const generic = (med.med_group_id || '').toLowerCase();
            const type = (med.med_type || '').toLowerCase();

            return name.includes(searchLower) ||
                   generic.includes(searchLower) ||
                   type.includes(searchLower);
        });

        displaySearchResults(results);
    }

    // Display search results
    function displaySearchResults(results) {
        const resultsContainer = document.getElementById('medicineSearchResults');

        if (results.length === 0) {
            resultsContainer.innerHTML = '<div class="no-results">No medicines found</div>';
            resultsContainer.style.display = 'block';
            return;
        }

        let resultsHTML = '';

        results.slice(0, 50).forEach((med, index) => {
            const name = med.medicine_name || 'Unknown';
            const generic = med.med_group_id ? ` (${med.med_group_id})` : '';
            const type = med.med_type ? ` - ${med.med_type}` : '';
            const strength = med.med_mg ? ` - ${med.med_mg}` : '';

            resultsHTML += `
                <div class="search-result-item" data-index="${index}">
                    <div class="result-name">${name}</div>
                    <div class="result-details">
                        <span class="result-generic">${generic}</span>
                        <span class="result-type">${type}</span>
                        <span class="result-strength">${strength}</span>
                    </div>
                </div>
            `;
        });

        resultsContainer.innerHTML = resultsHTML;
        resultsContainer.style.display = 'block';

        // Add click event to results
        const resultItems = resultsContainer.querySelectorAll('.search-result-item');
        resultItems.forEach(item => {
            item.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                selectMedicineFromSearch(this, results[index]);
            });

            item.addEventListener('mouseenter', function() {
                resultItems.forEach(r => r.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }

    // Select medicine from search results
    function selectMedicineFromSearch(element, medicine = null) {
        const resultsContainer = document.getElementById('medicineSearchResults');
        const searchInput = document.getElementById('medicineSearchInput');

        if (!medicine) {
            const index = element.getAttribute('data-index');
            medicine = jsonMedicines[index];
        }

        if (!medicine) return;

        // Fill the search input with selected medicine name
        searchInput.value = medicine.medicine_name || '';

        // Fill the actual medicine name field
        document.getElementById('medicineName').value = medicine.medicine_name || '';

        // Auto-fill other fields
        autoFillMedicineFields(medicine);

        // Hide results
        resultsContainer.style.display = 'none';

        // Highlight the selected item
        const allResults = resultsContainer.querySelectorAll('.search-result-item');
        allResults.forEach(r => r.classList.remove('selected'));
        element.classList.add('selected');
    }

    // Auto-fill medicine fields
    function autoFillMedicineFields(medicine) {
        // Generic Name
        document.getElementById('genericName').value = medicine.med_group_id || '';

        // Type
        const type = normalizeType(medicine.med_type);
        if (type) {
            document.getElementById('type').value = type;
        }

        // Dose
        const dose = normalizeDose(medicine.med_dose);
        if (dose) {
            document.getElementById('dose').value = dose;
        }

        // Days
        const days = normalizeDay(medicine.med_day);
        if (days) {
            document.getElementById('days').value = days;
        }

        // Strength
        const strength = normalizeStrength(medicine.med_mg);
        if (strength === 'Custom' && medicine.med_mg) {
            document.getElementById('strength').value = 'Custom';
            document.getElementById('customStrength').value = medicine.med_mg;
            document.getElementById('customStrengthGroup').style.display = 'block';
        } else if (strength) {
            document.getElementById('strength').value = strength;
            document.getElementById('customStrengthGroup').style.display = 'none';
        }

        // Comments
        document.getElementById('comments').value = medicine.med_comments || '';

        // Description
        document.getElementById('description').value = medicine.med_description || '';

        // Show success message
        showToast(`Loaded "${medicine.medicine_name}" details`, 'success');
    }

    // Load JSON medicines for dropdown with loading state
    function loadJsonMedicines() {
        if (isJsonMedicinesLoading) return;

        isJsonMedicinesLoading = true;
        const searchInput = document.getElementById('medicineSearchInput');
        const originalPlaceholder = searchInput.placeholder;

        // Show loading state
        searchInput.placeholder = 'Loading medicines database...';
        searchInput.disabled = true;

        fetch('{{ route("admin.medicine-templates.json") }}')
            .then(res => {
                if (!res.ok) throw new Error('Failed to load medicine list');
                return res.json();
            })
            .then(data => {
                jsonMedicines = data;
                allMedicines = [...data]; // Keep a copy for search

                if (data.length === 0) {
                    showToast('No medicines found in database', 'warning');
                    return;
                }

                // Sort alphabetically
                jsonMedicines.sort((a, b) => {
                    const nameA = (a.medicine_name || '').toLowerCase();
                    const nameB = (b.medicine_name || '').toLowerCase();
                    return nameA.localeCompare(nameB);
                });

                showToast(`Loaded ${data.length} medicines`, 'success');

                // If there's text in search, re-search
                const currentSearch = searchInput.value.trim();
                if (currentSearch.length >= 2) {
                    searchMedicines(currentSearch);
                }
            })
            .catch(error => {
                console.error('Error loading JSON medicines:', error);
                showToast('Failed to load medicine database. Please try again.', 'error');
            })
            .finally(() => {
                searchInput.disabled = false;
                searchInput.placeholder = originalPlaceholder;
                isJsonMedicinesLoading = false;
            });
    }

    // Helper functions for normalizing JSON data
    function normalizeType(type) {
        if (!type) return '';
        type = type.toString().toLowerCase();

        const typeMap = {
            'tab': 'Tablet',
            'tablet': 'Tablet',
            'syr': 'Syrup',
            'syrup': 'Syrup',
            'cap': 'Capsule',
            'capsule': 'Capsule',
            'inj': 'Injection',
            'injection': 'Injection',
            'oint': 'Ointment',
            'ointment': 'Ointment',
            'drop': 'Drop',
            'inh': 'Inhaler',
            'inhaler': 'Inhaler',
            'sus': 'Suspension',
            'suspension': 'Suspension',
            'pow': 'Powder',
            'powder': 'Powder',
            'cre': 'Cream',
            'cream': 'Cream',
            'sol': 'Solution',
            'solution': 'Solution',
            'lot': 'Lotion',
            'lotion': 'Lotion',
            'gel': 'Gel'
        };

        for (const [key, value] of Object.entries(typeMap)) {
            if (type.includes(key)) {
                return value;
            }
        }

        return type.charAt(0).toUpperCase() + type.slice(1);
    }

    function normalizeDose(dose) {
        if (!dose) return '';
        dose = dose.toString().toLowerCase().replace(/\s/g, '');

        const doseMap = {
            '1+0+1': '1+0+1',
            '1+1+1': '1+1+1',
            '0+0+1': '0+0+1',
            '1+0+0': '1+0+0',
            '0+1+0': '0+1+0',
            '1+1+0': '1+1+0',
            '0+0+0+1': '0+0+0+1',
            'onceaday': '0+0+0+1',
            'twiceaday': '1+0+1',
            'threetimesaday': '1+1+1',
            'asneeded': 'As needed',
            'prn': 'As needed',
            'sos': 'As needed',
            'bd': '1+0+1',
            'tds': '1+1+1',
            'qid': '1+1+1+1',
            'q4h': 'Custom',
            'q6h': 'Custom',
            'q8h': 'Custom',
            'q12h': '1+0+1',
            'daily': '0+0+0+1'
        };

        // Try exact match first
        if (doseMap[dose]) {
            return doseMap[dose];
        }

        // Try partial match
        for (const [key, value] of Object.entries(doseMap)) {
            if (dose.includes(key)) {
                return value;
            }
        }

        return dose;
    }

    function normalizeDay(day) {
        if (!day) return '';
        day = day.toString().toLowerCase();

        // Extract number from string
        const match = day.match(/\d+/);
        if (match) {
            return match[0];
        }

        // Handle special cases
        const dayMap = {
            'continuously': 'Continuously',
            'continuous': 'Continuously',
            'untilfinished': 'Until finished',
            'untilsymptomsresolve': 'Until finished',
            'prn': 'As needed',
            'sos': 'As needed',
            'lifelong': 'Continuously',
            'chronic': 'Continuously'
        };

        for (const [key, value] of Object.entries(dayMap)) {
            if (day.includes(key)) {
                return value;
            }
        }

        return day;
    }

    function normalizeStrength(mg) {
        if (!mg) return '';
        mg = mg.toString().trim().toLowerCase();

        // Check if it's one of our predefined values
        const predefined = [
            '5mg','10mg','20mg','25mg','50mg','75mg','100mg','125mg','250mg','500mg','625mg','1000mg',
            '5ml','10ml','15ml','20ml','50ml','100ml','200ml','500ml',
            '1mg','2mg','2.5mg','5mg/5ml','10mg/5ml','25mg/5ml','50mg/5ml','100mg/5ml',
            '250mg/5ml','500mg/5ml','1g','2g','5g','10g'
        ];

        if (predefined.includes(mg)) {
            return mg;
        }

        // If it contains mg, ml, g, mcg, iu, etc., treat as custom
        if (mg.match(/(mg|ml|g|mcg|iu|%|\/)/i)) {
            return 'Custom';
        }

        return '';
    }

    // PHP Helper function (for initial load)
    @php
        function getBadgeClass($type) {
            if (!$type) return 'badge-tablet';

            switch(strtolower($type)) {
                case 'tablet': return 'badge-tablet';
                case 'syrup': return 'badge-syrup';
                case 'capsule': return 'badge-capsule';
                case 'injection': return 'badge-injection';
                case 'ointment': return 'badge-ointment';
                case 'drop': return 'badge-drop';
                case 'inhaler': return 'badge-inhaler';
                case 'suspension': return 'badge-suspension';
                case 'powder': return 'badge-powder';
                case 'cream': return 'badge-cream';
                default: return 'badge-tablet';
            }
        }
    @endphp

    // Show/Hide Loading Indicator
    function showLoading(show) {
        const loadingIndicator = document.getElementById('loadingIndicator');
        const templatesGrid = document.getElementById('templatesGrid');

        if (show) {
            loadingIndicator.style.display = 'block';
            templatesGrid.style.opacity = '0.5';
        } else {
            loadingIndicator.style.display = 'none';
            templatesGrid.style.opacity = '1';
        }
    }

    // Show Toast Notification
    function showToast(message, type = 'success') {
        // Remove existing toasts
        const existingToasts = document.querySelectorAll('.toast');
        existingToasts.forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    // Search Templates
    function searchTemplates() {
        const searchTerm = document.getElementById('searchInput').value.trim();

        if (searchTerm.length === 0) {
            location.reload();
            return;
        }

        showLoading(true);

        fetch(`{{ route("admin.medicine-template.search") }}?search=${encodeURIComponent(searchTerm)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(templates => {
            showLoading(false);
            displayTemplates(templates);
        })
        .catch(error => {
            showLoading(false);
            console.error('Error searching templates:', error);
            showToast('Failed to search templates', 'error');
        });
    }

    // Display Templates from AJAX search
    function displayTemplates(templates) {
        const container = document.getElementById('templatesGrid');

        if (!templates || templates.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <p>No templates found matching your search</p>
                </div>
            `;
            return;
        }

        // Sort by name
        const sortedTemplates = [...templates].sort((a, b) =>
            a.medicine_name.localeCompare(b.medicine_name)
        );

        container.innerHTML = sortedTemplates.map(template => {
            // Get badge class based on type
            const badgeClass = getBadgeClassJS(template.medicine_type);

            // Format days display
            const daysDisplay = template.medicine_day === 'Continuously' || template.medicine_day === 'Until finished'
                ? template.medicine_day
                : `${template.medicine_day} days`;

            return `
                <div class="template-card" onclick="viewTemplate(${template.id})">
                    <div class="template-header">
                        <div class="template-info">
                            <div class="template-name">${escapeHtml(template.medicine_name)}</div>
                            <div class="template-generic">${escapeHtml(template.generic_name || 'N/A')}</div>
                            <span class="template-badge ${badgeClass}">${escapeHtml(template.medicine_type)}</span>
                        </div>
                        <div class="template-actions">
                            <button class="btn-icon btn-edit" onclick="editMedicine(${template.id}); event.stopPropagation();" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon btn-delete" onclick="confirmDelete(${template.id}); event.stopPropagation();" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="template-details">
                        <div class="detail-item">
                            <span class="detail-label">Dose</span>
                            <span class="detail-value">${escapeHtml(template.medicine_dose)}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Days</span>
                            <span class="detail-value">${daysDisplay}</span>
                        </div>
                        ${template.medicine_mg ? `
                            <div class="detail-item">
                                <span class="detail-label">Strength</span>
                                <span class="detail-value">${escapeHtml(template.medicine_mg)}</span>
                            </div>
                        ` : ''}
                        ${template.company_name ? `
                            <div class="detail-item detail-company">
                                <span class="detail-label">Company</span>
                                <span class="detail-value">${escapeHtml(template.company_name)}</span>
                            </div>
                        ` : ''}
                    </div>

                    ${template.medicine_comments ? `
                        <div class="template-footer">
                            <div class="template-comments">
                                <strong>Note:</strong> ${escapeHtml(template.medicine_comments)}
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;
        }).join('');
    }

    // Get Badge Class (JavaScript version)
    function getBadgeClassJS(type) {
        if (!type) return 'badge-tablet';

        switch((type || '').toLowerCase()) {
            case 'tablet': return 'badge-tablet';
            case 'syrup': return 'badge-syrup';
            case 'capsule': return 'badge-capsule';
            case 'injection': return 'badge-injection';
            case 'ointment': return 'badge-ointment';
            case 'drop': return 'badge-drop';
            case 'inhaler': return 'badge-inhaler';
            case 'suspension': return 'badge-suspension';
            case 'powder': return 'badge-powder';
            case 'cream': return 'badge-cream';
            default: return 'badge-tablet';
        }
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Open Create Modal
    function openCreateModal() {
        currentMedicineId = null;
        isViewMode = false;
        document.getElementById('modalTitle').textContent = 'Create Medicine Template';
        document.getElementById('saveButtonText').textContent = 'Save Template';
        document.getElementById('medicineId').value = '';

        // Reset form
        resetForm();

        // Remove view mode classes
        document.getElementById('medicineForm').classList.remove('view-mode');

        // Change cancel button text back
        const cancelBtn = document.querySelector('#medicineModal .btn-secondary');
        if (cancelBtn) cancelBtn.textContent = 'Cancel';

        // Set submit button back to save function
        const submitBtn = document.getElementById('submitButton');
        submitBtn.onclick = saveMedicine;

        // Clear search input
        const searchInput = document.getElementById('medicineSearchInput');
        if (searchInput) {
            searchInput.value = '';
        }

        // Clear search results
        const resultsContainer = document.getElementById('medicineSearchResults');
        if (resultsContainer) {
            resultsContainer.style.display = 'none';
        }

        // Load JSON medicines if not loaded
        if (jsonMedicines.length === 0 && !isJsonMedicinesLoading) {
            loadJsonMedicines();
        }

        document.getElementById('medicineModal').style.display = 'flex';

        // Focus on search input
        setTimeout(() => {
            if (searchInput) {
                searchInput.focus();
            }
        }, 100);
    }

    // Close Modal
    function closeModal() {
        document.getElementById('medicineModal').style.display = 'none';
        isViewMode = false;
        document.getElementById('medicineForm').classList.remove('view-mode');
    }

    // Reset Form
    function resetForm() {
        document.getElementById('medicineForm').reset();
        document.getElementById('customStrengthGroup').style.display = 'none';
        document.getElementById('customStrength').value = '';

        // Clear the search input if it exists
        const searchInput = document.getElementById('medicineSearchInput');
        if (searchInput) {
            searchInput.value = '';
        }

        // Clear search results
        const resultsContainer = document.getElementById('medicineSearchResults');
        if (resultsContainer) {
            resultsContainer.style.display = 'none';
        }
    }

    // Save Medicine (Create/Update)
    function saveMedicine() {
        // Basic validation
        const medicineName = document.getElementById('medicineName').value.trim();
        if (!medicineName) {
            showToast('Please enter medicine name', 'error');
            document.getElementById('medicineName').focus();
            return;
        }

        const medicineType = document.getElementById('type').value;
        if (!medicineType) {
            showToast('Please select medicine type', 'error');
            document.getElementById('type').focus();
            return;
        }

        const medicineDose = document.getElementById('dose').value;
        if (!medicineDose) {
            showToast('Please select dose schedule', 'error');
            document.getElementById('dose').focus();
            return;
        }

        const medicineDay = document.getElementById('days').value;
        if (!medicineDay) {
            showToast('Please select days', 'error');
            document.getElementById('days').focus();
            return;
        }

        // Get form data
        const medicineId = document.getElementById('medicineId').value;
        const isUpdate = !!medicineId;

        // Create form data
        const formData = new FormData();

        // Add CSRF token
        formData.append('_token', csrfToken);

        // Add method spoofing for PUT requests
        if (isUpdate) {
            formData.append('_method', 'PUT');
        }

        // Collect form values
        formData.append('medicine_name', medicineName);
        formData.append('generic_name', document.getElementById('genericName').value.trim());
        formData.append('company_name', document.getElementById('company').value);
        formData.append('medicine_type', medicineType);
        formData.append('medicine_dose', medicineDose);
        formData.append('medicine_day', medicineDay);

        // Handle strength
        const strength = document.getElementById('strength').value;
        if (strength === 'Custom') {
            const customStrength = document.getElementById('customStrength').value.trim();
            formData.append('medicine_mg', customStrength || '');
        } else {
            formData.append('medicine_mg', strength);
        }

        formData.append('medicine_url', document.getElementById('medicineUrl').value.trim());
        formData.append('medicine_comments', document.getElementById('comments').value.trim());
        formData.append('medicine_description', document.getElementById('description').value.trim());

        // Show loading on button
        const submitBtn = document.getElementById('submitButton');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        submitBtn.disabled = true;

        // Determine URL
        const url = isUpdate ?
            `/admin/medicine-templates/${medicineId}` :
            '{{ route("admin.medicine-templates.store") }}';

        // Send request
        fetch(url, {
            method: 'POST', // Always POST, using _method for PUT
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                closeModal();
                location.reload(); // Reload page to refresh the list
                showToast(data.message, 'success');
            } else {
                throw new Error(data.message || 'Failed to save template');
            }
        })
        .catch(error => {
            console.error('Error saving template:', error);
            showToast(error.message || 'Failed to save template. Please try again.', 'error');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }

    // Edit Medicine
    function editMedicine(medicineId) {
        showLoading(true);

        fetch(`/admin/medicine-templates/${medicineId}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch template');
            }
            return response.json();
        })
        .then(medicine => {
            showLoading(false);
            if (!medicine) {
                showToast('Template not found', 'error');
                return;
            }

            currentMedicineId = medicineId;
            isViewMode = false;
            document.getElementById('modalTitle').textContent = 'Edit Medicine Template';
            document.getElementById('saveButtonText').textContent = 'Update Template';
            document.getElementById('medicineId').value = medicineId;

            // Remove view mode classes
            document.getElementById('medicineForm').classList.remove('view-mode');

            // Populate form
            document.getElementById('medicineName').value = medicine.medicine_name || '';
            document.getElementById('genericName').value = medicine.generic_name || '';
            document.getElementById('company').value = medicine.company_name || '';
            document.getElementById('type').value = medicine.medicine_type || '';
            document.getElementById('dose').value = medicine.medicine_dose || '';
            document.getElementById('days').value = medicine.medicine_day || '';

            // Handle strength
            let strengthValue = medicine.medicine_mg || '';
            const isCustomStrength = strengthValue && ![
                '5mg','10mg','20mg','25mg','50mg','75mg','100mg','125mg','250mg','500mg','625mg','1000mg',
                '5ml','10ml','15ml','20ml','50ml','100ml','200ml','500ml'
            ].includes(strengthValue);

            if (isCustomStrength && strengthValue) {
                document.getElementById('strength').value = 'Custom';
                document.getElementById('customStrength').value = strengthValue;
                document.getElementById('customStrengthGroup').style.display = 'block';
            } else {
                document.getElementById('strength').value = strengthValue;
                document.getElementById('customStrengthGroup').style.display = 'none';
            }

            document.getElementById('medicineUrl').value = medicine.medicine_url || '';
            document.getElementById('comments').value = medicine.medicine_comments || '';
            document.getElementById('description').value = medicine.medicine_description || '';

            // Clear search input
            const searchInput = document.getElementById('medicineSearchInput');
            if (searchInput) {
                searchInput.value = '';
            }

            // Clear search results
            const resultsContainer = document.getElementById('medicineSearchResults');
            if (resultsContainer) {
                resultsContainer.style.display = 'none';
            }

            // Show modal
            document.getElementById('medicineModal').style.display = 'flex';
        })
        .catch(error => {
            showLoading(false);
            console.error('Error loading template:', error);
            showToast('Failed to load template. Please try again.', 'error');
        });
    }

    // View Template
    function viewTemplate(medicineId) {
        showLoading(true);

        fetch(`/admin/medicine-templates/${medicineId}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch template');
            }
            return response.json();
        })
        .then(medicine => {
            showLoading(false);
            if (!medicine) {
                showToast('Template not found', 'error');
                return;
            }

            currentMedicineId = medicineId;
            isViewMode = true;
            document.getElementById('modalTitle').textContent = medicine.medicine_name || 'View Template';
            document.getElementById('saveButtonText').textContent = 'Edit Template';
            document.getElementById('medicineId').value = medicineId;

            // Add view mode class
            document.getElementById('medicineForm').classList.add('view-mode');

            // Change button text
            const cancelBtn = document.querySelector('#medicineModal .btn-secondary');
            if (cancelBtn) cancelBtn.textContent = 'Close';

            // Populate form (readonly)
            document.getElementById('medicineName').value = medicine.medicine_name || '';
            document.getElementById('genericName').value = medicine.generic_name || '';
            document.getElementById('company').value = medicine.company_name || '';
            document.getElementById('type').value = medicine.medicine_type || '';
            document.getElementById('dose').value = medicine.medicine_dose || '';
            document.getElementById('days').value = medicine.medicine_day || '';

            // Handle strength display
            let strengthValue = medicine.medicine_mg || '';
            const isCustomStrength = strengthValue && ![
                '5mg','10mg','20mg','25mg','50mg','75mg','100mg','125mg','250mg','500mg','625mg','1000mg',
                '5ml','10ml','15ml','20ml','50ml','100ml','200ml','500ml'
            ].includes(strengthValue);

            if (isCustomStrength) {
                document.getElementById('strength').value = 'Custom';
                document.getElementById('customStrength').value = strengthValue;
                document.getElementById('customStrengthGroup').style.display = 'block';
            } else {
                document.getElementById('strength').value = strengthValue;
                document.getElementById('customStrengthGroup').style.display = 'none';
            }

            document.getElementById('medicineUrl').value = medicine.medicine_url || '';
            document.getElementById('comments').value = medicine.medicine_comments || '';
            document.getElementById('description').value = medicine.medicine_description || '';

            // Change save button to edit mode
            const submitBtn = document.getElementById('submitButton');
            submitBtn.onclick = function() {
                editMedicine(medicineId);
            };

            // Clear search input
            const searchInput = document.getElementById('medicineSearchInput');
            if (searchInput) {
                searchInput.value = '';
                searchInput.disabled = true;
            }

            // Clear search results
            const resultsContainer = document.getElementById('medicineSearchResults');
            if (resultsContainer) {
                resultsContainer.style.display = 'none';
            }

            // Show modal
            document.getElementById('medicineModal').style.display = 'flex';
        })
        .catch(error => {
            showLoading(false);
            console.error('Error loading template:', error);
            showToast('Failed to load template. Please try again.', 'error');
        });
    }

    // Confirm Delete
    function confirmDelete(medicineId) {
        currentMedicineId = medicineId;

        // Find medicine name from existing HTML
        const templateCard = document.querySelector(`.template-card[onclick*="viewTemplate(${medicineId})"]`);
        let medicineName = 'this template';

        if (templateCard) {
            const nameElement = templateCard.querySelector('.template-name');
            if (nameElement) {
                medicineName = `"${nameElement.textContent}"`;
            }
        }

        document.getElementById('confirmTitle').textContent = 'Delete Template';
        document.getElementById('confirmMessage').textContent =
            `Are you sure you want to delete ${medicineName}? This action cannot be undone.`;

        // Set up confirmation callback
        const confirmBtn = document.getElementById('confirmActionButton');
        confirmBtn.onclick = function() {
            deleteMedicine(medicineId);
        };

        // Show confirmation modal
        document.getElementById('confirmModal').style.display = 'flex';
    }

    // Delete Medicine
    function deleteMedicine(medicineId) {
        closeConfirmModal();

        // Create form data for DELETE request
        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('_method', 'DELETE');

        fetch(`/admin/medicine-templates/${medicineId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload(); // Reload page to refresh the list
                showToast(data.message, 'success');
            } else {
                throw new Error(data.message || 'Failed to delete template');
            }
        })
        .catch(error => {
            console.error('Error deleting template:', error);
            showToast(error.message || 'Failed to delete template. Please try again.', 'error');
        });
    }

    // Close Confirmation Modal
    function closeConfirmModal() {
        document.getElementById('confirmModal').style.display = 'none';
    }

    // Close modals when clicking outside
    document.addEventListener('click', function(event) {
        const medicineModal = document.getElementById('medicineModal');
        const confirmModal = document.getElementById('confirmModal');

        if (event.target === medicineModal) {
            closeModal();
        }
        if (event.target === confirmModal) {
            closeConfirmModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
            closeConfirmModal();
        }
    });
</script>

<style>
    /* Add search results styles */
    .search-result-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #e5e7eb;
        transition: background-color 0.2s;
    }

    .search-result-item:hover,
    .search-result-item.active {
        background-color: #f3f4f6;
    }

    .search-result-item.selected {
        background-color: var(--primary-light);
        border-left: 3px solid var(--primary);
    }

    .search-result-item:last-child {
        border-bottom: none;
    }

    .result-name {
        font-weight: 600;
        color: #111827;
        margin-bottom: 2px;
    }

    .result-details {
        font-size: 0.8rem;
        color: #6b7280;
    }

    .result-generic,
    .result-type,
    .result-strength {
        margin-right: 8px;
    }

    .result-generic {
        color: #059669;
    }

    .result-type {
        color: #7c3aed;
    }

    .result-strength {
        color: #dc2626;
    }

    .no-results {
        padding: 15px;
        text-align: center;
        color: #6b7280;
        font-style: italic;
    }

    .medicine-search-container {
        position: relative;
    }

    .search-results {
        position: absolute;
        width: 100%;
        background: white;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        max-height: 300px;
        overflow-y: auto;
    }
</style>
@endsection
