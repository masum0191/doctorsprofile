@extends('layouts.admin')
@section('title', 'Create Prescription')

@section('content')
<style>
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.1);
        --primary-dark: #2a6d5a;
        --primary-soft: rgba(49, 128, 105, 0.05);
        --primary-hover: rgba(49, 128, 105, 0.15);
    }

    .global-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Patient Selection Section */
    .patient-selection-section {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border-left: 4px solid var(--primary);
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .section-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
    }

    .section-title h2 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
        color: #111827;
    }

    .section-subtitle {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* Search Container */
    .search-container {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .search-input-wrapper {
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 1rem;
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
        font-size: 1.125rem;
    }

    /* Search Results */
    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
    }

    .search-result-item {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .search-result-item:hover {
        background: #f9fafb;
    }

    .search-result-item:last-child {
        border-bottom: none;
    }

    .result-name {
        font-weight: 500;
        color: #111827;
        margin-bottom: 0.25rem;
    }

    .result-details {
        font-size: 0.875rem;
        color: #6b7280;
    }

    /* Selected Patient Display */
    .selected-patient-display {
        background: #f8fafc;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 1.5rem;
        margin-top: 1.5rem;
        display: none;
    }

    .selected-patient-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .selected-patient-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .patient-avatar {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    .patient-details h3 {
        margin: 0 0 0.25rem 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
    }

    .patient-meta {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .btn-change-patient {
        background: white;
        color: #6b7280;
        border: 1px solid #d1d5db;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-change-patient:hover {
        background: #f9fafb;
        border-color: var(--primary);
        color: var(--primary);
    }

    .patient-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .stat-box {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
    }

    .stat-label {
        display: block;
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
    }

    /* Prescription Form (Initially Hidden) */
    #prescriptionFormContainer {
        display: none;
    }

    /* Form Sections */
    .form-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid #e5e7eb;
    }

    .form-section .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f3f4f6;
    }

    .form-section .section-title h5 {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
    }

    .form-section .section-subtitle {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 0;
    }

    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.25rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-weight: 500;
        color: #374151;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .form-input {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #111827;
        transition: all 0.2s ease;
        background: white;
    }

    .form-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    /* Selected Items */
    .selected-items-container {
        margin-bottom: 1.5rem;
        max-height: 200px;
        overflow-y: auto;
    }

    .selected-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.875rem 1rem;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        transition: all 0.2s ease;
    }

    .selected-item:hover {
        border-color: var(--primary);
        background: white;
    }

    .item-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .item-icon {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        background: white;
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 0.875rem;
    }

    .item-details h6 {
        margin: 0;
        font-size: 0.875rem;
        font-weight: 500;
        color: #111827;
    }

    .item-details p {
        margin: 0.25rem 0 0;
        font-size: 0.75rem;
        color: #6b7280;
    }

    .item-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.75rem;
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

    .btn-remove:hover {
        color: #dc2626;
        border-color: #dc2626;
    }

    /* Add Button */
    .btn-add {
        width: 100%;
        padding: 0.875rem;
        background: white;
        border: 1px dashed #d1d5db;
        border-radius: 8px;
        color: #6b7280;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-add:hover {
        background: #f9fafb;
        border-color: var(--primary);
        color: var(--primary);
    }

    /* Preview Section */
    .preview-section {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1.5rem;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        white-space: pre-wrap;
        font-size: 0.875rem;
        line-height: 1.6;
        max-height: 300px;
        overflow-y: auto;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
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
        padding: 0.875rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: var(--primary);
        color: var(--primary);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #9ca3af;
    }

    .empty-state-icon {
        font-size: 2rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Modal Styling */
    .compact-modal .modal-dialog {
        max-width: 900px;
    }

    .compact-modal .modal-content {
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background: white;
        border-bottom: 1px solid #e5e7eb;
        padding: 1.25rem 1.5rem;
        border-radius: 12px 12px 0 0;
    }

    .modal-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-body {
        padding: 1.5rem;
        max-height: 70vh;
        overflow-y: auto;
    }

    .btn-close-custom {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: #6b7280;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .btn-close-custom:hover {
        background: #f3f4f6;
        color: #111827;
    }

    /* Search Bar in Modal */
    .search-wrapper {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .search-wrapper .search-input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 2.5rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #111827;
        transition: all 0.2s ease;
        background: white;
    }

    .search-wrapper .search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    .search-wrapper .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    /* Suggestions Grid */
    .suggestions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.75rem;
    }

    .suggestion-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .suggestion-card:hover {
        border-color: var(--primary);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .suggestion-name {
        font-weight: 500;
        font-size: 0.875rem;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .suggestion-details {
        font-size: 0.75rem;
        color: #6b7280;
        line-height: 1.4;
    }

    .suggestion-badge {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--primary-light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }
</style>

<div class="global-container">
    <!-- Patient Selection Section -->
    <div class="patient-selection-section" id="patientSelectionSection">
        <div class="section-title">
            <div class="section-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div>
                <h2>Create Prescription</h2>
                <div class="section-subtitle">Select a patient to begin writing prescription</div>
            </div>
        </div>

        <!-- Patient Search -->
        <div class="search-container">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="patientSearch" class="search-input" 
                       placeholder="Search patient by name, phone number, or ID..." 
                       autocomplete="off">
            </div>
            
            <!-- Search Results Dropdown -->
            <div class="search-results" id="searchResults"></div>
        </div>

        <!-- Or Select from Recent Patients -->
        <div style="margin-top: 2rem;">
            <h4 style="font-size: 1rem; color: #374151; margin-bottom: 1rem;">Recent Patients</h4>
            <div class="suggestions-grid" id="recentPatients">
                <!-- Recent patients will be loaded here -->
            </div>
        </div>

        <!-- Selected Patient Display (Initially Hidden) -->
        <div class="selected-patient-display" id="selectedPatientDisplay">
            <div class="selected-patient-header">
                <div class="selected-patient-info">
                    <div class="patient-avatar" id="selectedPatientAvatar">P</div>
                    <div class="patient-details">
                        <h3 id="selectedPatientName">Patient Name</h3>
                        <div class="patient-meta">
                            Patient ID: <span id="selectedPatientId">PT0000</span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-change-patient" onclick="changePatient()">
                    <i class="fas fa-exchange-alt mr-2"></i> Change Patient
                </button>
            </div>

            <div class="patient-stats-grid">
                <div class="stat-box">
                    <span class="stat-label">Age</span>
                    <span class="stat-value" id="selectedPatientAge">--</span>
                </div>
                <div class="stat-box">
                    <span class="stat-label">Blood Group</span>
                    <span class="stat-value" id="selectedPatientBlood">--</span>
                </div>
                <div class="stat-box">
                    <span class="stat-label">Phone</span>
                    <span class="stat-value" id="selectedPatientPhone">--</span>
                </div>
                <div class="stat-box">
                    <span class="stat-label">Last Visit</span>
                    <span class="stat-value" id="selectedPatientLastVisit">--</span>
                </div>
            </div>

            <div class="action-buttons" style="margin-top: 1.5rem; padding-top: 1.5rem;">
                <button type="button" class="btn-secondary" onclick="changePatient()">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </button>
                <button type="button" class="btn-primary" onclick="startPrescription()">
                    <i class="fas fa-file-medical mr-2"></i> Start Prescription
                </button>
            </div>
        </div>
    </div>

    <!-- Prescription Form Container (Initially Hidden) -->
    <div id="prescriptionFormContainer">
        <!-- Diagnosis Section -->
        <div class="form-section">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <div>
                    <h5>Diagnosis Information</h5>
                    <div class="section-subtitle">Enter patient's diagnosis and chief complaints</div>
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Appointment Date</label>
                    <input type="date" name="prescribed_date" class="form-input" 
                           id="prescriptionDate" value="{{ now()->toDateString() }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Chief Complaint</label>
                    <input type="text" name="chief_complaint" class="form-input" 
                           id="chiefComplaint" placeholder="e.g., Fever, Headache...">
                </div>
                
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label">Link to Appointment (Optional)</label>
                    <select name="appointment_id" class="form-input" id="appointmentSelect">
                        <option value="">-- Select Appointment --</option>
                    </select>
                </div>
                
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label">Diagnosis Details</label>
                    <textarea name="diagnosis" rows="3" class="form-input" 
                              id="diagnosisDetails" placeholder="Enter diagnosis details..."></textarea>
                </div>
            </div>
        </div>

        <!-- Medications Section -->
        <div class="form-section">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-pills"></i>
                </div>
                <div>
                    <h5>Medications</h5>
                    <div class="section-subtitle">Prescribe medicines for the patient</div>
                </div>
            </div>
            
            <!-- Selected Medicines -->
            <div class="selected-items-container" id="selectedMedicinesContainer">
                <div class="selected-items-header">Selected Medicines (<span id="medicinesCount">0</span>)</div>
                <div id="medicinesList">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-pills"></i>
                        </div>
                        <p>No medicines added yet</p>
                    </div>
                </div>
            </div>
            
            <!-- Add Medicine Button -->
            <button type="button" class="btn-add" onclick="openMedicineModal()">
                <i class="fas fa-plus"></i> Add Medicine
            </button>
        </div>

        <!-- Tests Section -->
        <div class="form-section">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-vial"></i>
                </div>
                <div>
                    <h5>Recommended Tests</h5>
                    <div class="section-subtitle">Add tests for the patient</div>
                </div>
            </div>
            
            <!-- Selected Tests -->
            <div class="selected-items-container" id="selectedTestsContainer">
                <div class="selected-items-header">Selected Tests (<span id="testsCount">0</span>)</div>
                <div id="testsList">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-vial"></i>
                        </div>
                        <p>No tests added yet</p>
                    </div>
                </div>
            </div>
            
            <!-- Add Test Button -->
            <button type="button" class="btn-add" onclick="openTestModal()">
                <i class="fas fa-plus"></i> Add Test
            </button>
        </div>

        <!-- Instructions Section -->
        <div class="form-section">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-comment-medical"></i>
                </div>
                <div>
                    <h5>Instructions & Follow-up</h5>
                    <div class="section-subtitle">Provide patient guidance</div>
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label">Patient Instructions</label>
                    <textarea name="instructions" rows="3" class="form-input" 
                              id="patientInstructions" placeholder="Enter instructions for the patient..."></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Follow-up Date</label>
                    <input type="text" name="next_visit_date" class="form-input" 
                           id="nextVisitDate" placeholder="e.g., In 7 days">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Diet Advice</label>
                    <textarea name="diet_advice" rows="2" class="form-input" 
                              id="dietAdvice" placeholder="Diet recommendations..."></textarea>
                </div>
            </div>
        </div>

        <!-- Prescription Preview -->
        <div class="form-section">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-file-medical"></i>
                </div>
                <div>
                    <h5>Prescription Preview</h5>
                    <div class="section-subtitle">Review before finalizing</div>
                </div>
            </div>
            
            <div class="preview-section" id="prescriptionPreview">
                <!-- Preview will be generated here -->
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button type="button" class="btn-secondary" onclick="changePatient()">
                <i class="fas fa-arrow-left mr-2"></i> Change Patient
            </button>
            <button type="button" class="btn-secondary" onclick="resetForm()">
                <i class="fas fa-redo mr-2"></i> Reset Form
            </button>
            <button type="button" class="btn-primary" onclick="savePrescription()">
                <i class="fas fa-save mr-2"></i> Save Prescription
            </button>
        </div>
    </div>
</div>

<!-- Medicine Modal -->
<div class="modal fade compact-modal" id="medicineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-pills mr-2"></i>Add Medicine
                </h5>
                <button type="button" class="btn-close-custom" onclick="closeMedicineModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- Search Bar -->
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="medicineSearch" class="search-input" 
                           placeholder="Search medicine or type custom name..." 
                           onkeypress="handleMedicineSearch(event)">
                </div>
                
                <!-- Selected Medicines in Modal -->
                <div class="selected-items-container" id="modalSelectedMedicines">
                    <!-- Selected medicines will appear here -->
                </div>
                
                <!-- Suggestions Grid -->
                <div id="medicineSuggestionsContainer">
                    <div class="selected-items-header">Suggestions</div>
                    <div class="suggestions-grid" id="medicineSuggestions">
                        <!-- Dynamic content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Modal -->
<div class="modal fade compact-modal" id="testModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-vial mr-2"></i>Add Tests
                </h5>
                <button type="button" class="btn-close-custom" onclick="closeTestModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- Search Bar -->
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="testSearch" class="search-input" 
                           placeholder="Search tests or type custom name..."
                           onkeypress="handleTestSearch(event)">
                </div>
                
                <!-- Selected Tests in Modal -->
                <div class="selected-items-container" id="modalSelectedTests">
                    <!-- Selected tests will appear here -->
                </div>
                
                <!-- Suggestions Grid -->
                <div id="testSuggestionsContainer">
                    <div class="selected-items-header">Suggestions</div>
                    <div class="suggestions-grid" id="testSuggestions">
                        <!-- Dynamic content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Medicine Modal -->
<div class="modal fade compact-modal" id="editMedicineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit mr-2"></i>Edit Medicine
                </h5>
                <button type="button" class="btn-close-custom" onclick="closeEditMedicineModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="editMedicineForm">
                <!-- Dynamic form will be inserted here -->
            </div>
        </div>
    </div>
</div>

<!-- Hidden Form -->
<form method="POST" action="{{ route('admin.prescriptions.store') }}" 
      id="prescriptionForm" style="display: none;">
    @csrf
    <input type="hidden" name="patient_id" id="patientId">
    <input type="hidden" name="medicines" id="medicinesData">
    <input type="hidden" name="tests" id="testsData">
</form>

<script>
    // Sample Patient Data (In real app, this would come from API)
    const allPatients = [
        { id: 1, name: "John Doe", phone: "+880 1712 345678", blood_group: "A+", age: 35, last_visit: "2024-01-15", avatar: "J" },
        { id: 2, name: "Sarah Smith", phone: "+880 1713 456789", blood_group: "B+", age: 28, last_visit: "2024-01-18", avatar: "S" },
        { id: 3, name: "Michael Johnson", phone: "+880 1714 567890", blood_group: "O+", age: 45, last_visit: "2024-01-10", avatar: "M" },
        { id: 4, name: "Emily Davis", phone: "+880 1715 678901", blood_group: "AB+", age: 32, last_visit: "2024-01-12", avatar: "E" },
        { id: 5, name: "Robert Wilson", phone: "+880 1716 789012", blood_group: "A-", age: 50, last_visit: "2024-01-08", avatar: "R" },
        { id: 6, name: "Lisa Brown", phone: "+880 1717 890123", blood_group: "B-", age: 29, last_visit: "2024-01-05", avatar: "L" },
        { id: 7, name: "David Miller", phone: "+880 1718 901234", blood_group: "O-", age: 38, last_visit: "2024-01-20", avatar: "D" },
        { id: 8, name: "Jennifer Taylor", phone: "+880 1719 012345", blood_group: "AB-", age: 42, last_visit: "2024-01-22", avatar: "J" }
    ];

    // Sample Medicine Data
    const medicineSuggestions = [
        { id: 1, name: "Paracetamol 500mg", schedule: "1+0+1", duration: "3 days", instructions: "After food" },
        { id: 2, name: "Ibuprofen 400mg", schedule: "1+0+1", duration: "5 days", instructions: "With food" },
        { id: 3, name: "Cetirizine 10mg", schedule: "0+0+1", duration: "7 days", instructions: "Before dinner" },
        { id: 4, name: "Omeprazole 20mg", schedule: "0+0+1", duration: "14 days", instructions: "Before dinner" },
        { id: 5, name: "Amoxicillin 500mg", schedule: "1+1+1", duration: "7 days", instructions: "After food" },
        { id: 6, name: "Azithromycin 500mg", schedule: "1+0+0", duration: "3 days", instructions: "Before food" },
        { id: 7, name: "Diclofenac 50mg", schedule: "1+0+1", duration: "5 days", instructions: "With food" },
        { id: 8, name: "Vitamin C 500mg", schedule: "0+0+1", duration: "30 days", instructions: "After breakfast" }
    ];

    // Sample Test Data
    const testSuggestions = [
        { id: 1, name: "Complete Blood Count (CBC)", category: "Hematology" },
        { id: 2, name: "Blood Glucose Fasting", category: "Biochemistry" },
        { id: 3, name: "Lipid Profile", category: "Biochemistry" },
        { id: 4, name: "Liver Function Test", category: "Biochemistry" },
        { id: 5, name: "Kidney Function Test", category: "Biochemistry" },
        { id: 6, name: "Thyroid Profile (TSH)", category: "Hormone" },
        { id: 7, name: "Urine Routine", category: "Urine" },
        { id: 8, name: "ECG", category: "Cardiology" }
    ];

    // State Management
    let selectedPatient = null;
    let selectedMedicines = [];
    let selectedTests = [];
    let currentEditMedicineIndex = null;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        loadRecentPatients();
        setupSearch();
    });

    // Load Recent Patients
    function loadRecentPatients() {
        const container = document.getElementById('recentPatients');
        container.innerHTML = allPatients.slice(0, 6).map(patient => `
            <div class="suggestion-card" onclick="selectPatient(${patient.id})">
                <div class="suggestion-name">${patient.name}</div>
                <div class="suggestion-details">
                    ${patient.phone}<br>
                    Age: ${patient.age} • Blood: ${patient.blood_group}
                </div>
                <div class="suggestion-badge">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        `).join('');
    }

    // Setup Search
    function setupSearch() {
        const searchInput = document.getElementById('patientSearch');
        const searchResults = document.getElementById('searchResults');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            if (searchTerm.length < 2) {
                searchResults.style.display = 'none';
                return;
            }

            const filtered = allPatients.filter(patient => 
                patient.name.toLowerCase().includes(searchTerm) ||
                patient.phone.includes(searchTerm) ||
                `PT${patient.id.toString().padStart(4, '0')}`.toLowerCase().includes(searchTerm)
            );

            if (filtered.length === 0) {
                searchResults.innerHTML = `
                    <div class="search-result-item" style="cursor: default;">
                        <div class="result-name">No patients found</div>
                        <div class="result-details">Try a different search term</div>
                    </div>
                `;
            } else {
                searchResults.innerHTML = filtered.map(patient => `
                    <div class="search-result-item" onclick="selectPatient(${patient.id})">
                        <div class="result-name">${patient.name}</div>
                        <div class="result-details">
                            ${patient.phone} • Age: ${patient.age} • Blood: ${patient.blood_group}
                        </div>
                    </div>
                `).join('');
            }

            searchResults.style.display = 'block';
        });

        // Close search results when clicking outside
        document.addEventListener('click', function(event) {
            if (!searchResults.contains(event.target) && event.target !== searchInput) {
                searchResults.style.display = 'none';
            }
        });
    }

    // Select Patient
    function selectPatient(patientId) {
        const patient = allPatients.find(p => p.id === patientId);
        if (!patient) return;

        selectedPatient = patient;
        
        // Update display
        document.getElementById('selectedPatientAvatar').textContent = patient.avatar;
        document.getElementById('selectedPatientName').textContent = patient.name;
        document.getElementById('selectedPatientId').textContent = `PT${patient.id.toString().padStart(4, '0')}`;
        document.getElementById('selectedPatientAge').textContent = `${patient.age} Years`;
        document.getElementById('selectedPatientBlood').textContent = patient.blood_group;
        document.getElementById('selectedPatientPhone').textContent = patient.phone;
        document.getElementById('selectedPatientLastVisit').textContent = 
            new Date(patient.last_visit).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        
        // Show selected patient display
        document.getElementById('selectedPatientDisplay').style.display = 'block';
        document.getElementById('searchResults').style.display = 'none';
        document.getElementById('patientSearch').value = '';
        
        // Hide recent patients
        document.getElementById('recentPatients').style.display = 'none';
    }

    // Change Patient
    function changePatient() {
        selectedPatient = null;
        selectedMedicines = [];
        selectedTests = [];
        
        // Hide prescription form
        document.getElementById('prescriptionFormContainer').style.display = 'none';
        
        // Reset patient display
        document.getElementById('selectedPatientDisplay').style.display = 'none';
        document.getElementById('recentPatients').style.display = 'grid';
        
        // Reset form
        resetForm();
    }

    // Start Prescription
    function startPrescription() {
        if (!selectedPatient) {
            alert('Please select a patient first');
            return;
        }

        // Hide selection section
        document.getElementById('patientSelectionSection').style.display = 'none';
        
        // Show prescription form
        document.getElementById('prescriptionFormContainer').style.display = 'block';
        
        // Update prescription form with patient data
        document.getElementById('patientId').value = selectedPatient.id;
        
        // Generate initial preview
        generatePreview();
    }

    // Open Medicine Modal
    function openMedicineModal() {
        renderMedicineSuggestions();
        updateModalSelectedMedicines();
        const modal = new bootstrap.Modal(document.getElementById('medicineModal'));
        modal.show();
        
        // Focus search input
        setTimeout(() => {
            document.getElementById('medicineSearch').focus();
        }, 100);
    }

    // Close Medicine Modal
    function closeMedicineModal() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('medicineModal'));
        modal.hide();
    }

    // Open Test Modal
    function openTestModal() {
        renderTestSuggestions();
        updateModalSelectedTests();
        const modal = new bootstrap.Modal(document.getElementById('testModal'));
        modal.show();
        
        // Focus search input
        setTimeout(() => {
            document.getElementById('testSearch').focus();
        }, 100);
    }

    // Close Test Modal
    function closeTestModal() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('testModal'));
        modal.hide();
    }

    // Close Edit Medicine Modal
    function closeEditMedicineModal() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('editMedicineModal'));
        modal.hide();
    }

    // Handle Medicine Search
    function handleMedicineSearch(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            const searchInput = document.getElementById('medicineSearch');
            const searchTerm = searchInput.value.trim();
            
            if (searchTerm) {
                addCustomMedicine(searchTerm);
                searchInput.value = '';
                renderMedicineSuggestions();
            }
        } else {
            // Real-time search
            setTimeout(() => {
                renderMedicineSuggestions();
            }, 100);
        }
    }

    // Handle Test Search
    function handleTestSearch(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            const searchInput = document.getElementById('testSearch');
            const searchTerm = searchInput.value.trim();
            
            if (searchTerm) {
                addCustomTest(searchTerm);
                searchInput.value = '';
                renderTestSuggestions();
            }
        } else {
            // Real-time search
            setTimeout(() => {
                renderTestSuggestions();
            }, 100);
        }
    }

    // Render Medicine Suggestions
    function renderMedicineSuggestions() {
        const container = document.getElementById('medicineSuggestions');
        const searchTerm = document.getElementById('medicineSearch')?.value.toLowerCase() || '';
        
        // Filter out already selected medicines
        const filtered = medicineSuggestions.filter(med => {
            if (selectedMedicines.some(m => m.id === med.id && !m.custom)) {
                return false; // Skip already selected
            }
            return med.name.toLowerCase().includes(searchTerm);
        });
        
        if (filtered.length === 0 && !searchTerm) {
            container.innerHTML = `
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <div class="empty-state-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <p>Type to search for medicines</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = filtered.map(med => `
            <div class="suggestion-card" onclick="addMedicine(${med.id})">
                <div class="suggestion-name">${med.name}</div>
                <div class="suggestion-details">
                    ${med.schedule} • ${med.duration}
                    ${med.instructions ? `<br><small>${med.instructions}</small>` : ''}
                </div>
                <div class="suggestion-badge">
                    <i class="fas fa-plus"></i>
                </div>
            </div>
        `).join('');
    }

    // Render Test Suggestions
    function renderTestSuggestions() {
        const container = document.getElementById('testSuggestions');
        const searchTerm = document.getElementById('testSearch')?.value.toLowerCase() || '';
        
        // Filter out already selected tests
        const filtered = testSuggestions.filter(test => {
            if (selectedTests.some(t => t.id === test.id && !t.custom)) {
                return false; // Skip already selected
            }
            return test.name.toLowerCase().includes(searchTerm) ||
                   test.category.toLowerCase().includes(searchTerm);
        });
        
        if (filtered.length === 0 && !searchTerm) {
            container.innerHTML = `
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <div class="empty-state-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <p>Type to search for tests</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = filtered.map(test => `
            <div class="suggestion-card" onclick="addTest(${test.id})">
                <div class="suggestion-name">${test.name}</div>
                <div class="suggestion-details">${test.category}</div>
                <div class="suggestion-badge">
                    <i class="fas fa-plus"></i>
                </div>
            </div>
        `).join('');
    }

    // Add Medicine
    function addMedicine(medicineId = null) {
        let medicine;
        
        if (medicineId) {
            // From suggestions
            medicine = medicineSuggestions.find(m => m.id === medicineId);
            if (medicine) {
                selectedMedicines.push({
                    ...medicine,
                    custom: false
                });
            }
        }
        
        updateSelectedMedicinesDisplay();
        updateModalSelectedMedicines();
        renderMedicineSuggestions();
        generatePreview();
    }

    // Add Custom Medicine
    function addCustomMedicine(name) {
        if (!name) return;
        
        // Check if already exists
        const existing = selectedMedicines.find(m => 
            m.name.toLowerCase() === name.toLowerCase() && m.custom
        );
        
        if (!existing) {
            selectedMedicines.push({
                id: Date.now(),
                name: name,
                schedule: "1+0+1",
                duration: "7 days",
                instructions: "",
                custom: true
            });
        }
        
        updateSelectedMedicinesDisplay();
        updateModalSelectedMedicines();
        generatePreview();
    }

    // Add Test
    function addTest(testId = null) {
        let test;
        
        if (testId) {
            // From suggestions
            test = testSuggestions.find(t => t.id === testId);
            if (test) {
                selectedTests.push({
                    ...test,
                    custom: false
                });
            }
        }
        
        updateSelectedTestsDisplay();
        updateModalSelectedTests();
        renderTestSuggestions();
        generatePreview();
    }

    // Add Custom Test
    function addCustomTest(name) {
        if (!name) return;
        
        // Check if already exists
        const existing = selectedTests.find(t => 
            t.name.toLowerCase() === name.toLowerCase() && t.custom
        );
        
        if (!existing) {
            selectedTests.push({
                id: Date.now(),
                name: name,
                category: "Custom",
                custom: true
            });
        }
        
        updateSelectedTestsDisplay();
        updateModalSelectedTests();
        generatePreview();
    }

    // Update Selected Medicines Display
    function updateSelectedMedicinesDisplay() {
        const container = document.getElementById('medicinesList');
        const countElement = document.getElementById('medicinesCount');
        
        countElement.textContent = selectedMedicines.length;
        
        if (selectedMedicines.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <p>No medicines added yet</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = selectedMedicines.map((med, index) => `
            <div class="selected-item">
                <div class="item-content">
                    <div class="item-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <div class="item-details">
                        <h6>${med.name}</h6>
                        <p>${med.schedule} • ${med.duration} ${med.instructions ? `• ${med.instructions}` : ''}</p>
                    </div>
                </div>
                <div class="item-actions">
                    <button class="btn-icon btn-edit" onclick="editMedicine(${index})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-icon btn-remove" onclick="removeMedicine(${index})" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `).join('');
    }

    // Update Modal Selected Medicines
    function updateModalSelectedMedicines() {
        const container = document.getElementById('modalSelectedMedicines');
        
        if (selectedMedicines.length === 0) {
            container.innerHTML = '';
            return;
        }
        
        container.innerHTML = selectedMedicines.map((med, index) => `
            <div class="selected-item">
                <div class="item-content">
                    <div class="item-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <div class="item-details">
                        <h6>${med.name}</h6>
                        <p>${med.schedule} • ${med.duration}</p>
                    </div>
                </div>
                <div class="item-actions">
                    <button class="btn-icon btn-edit" onclick="editMedicine(${index})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-icon btn-remove" onclick="removeMedicine(${index})" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `).join('');
    }

    // Update Selected Tests Display
    function updateSelectedTestsDisplay() {
        const container = document.getElementById('testsList');
        const countElement = document.getElementById('testsCount');
        
        countElement.textContent = selectedTests.length;
        
        if (selectedTests.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-vial"></i>
                    </div>
                    <p>No tests added yet</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = selectedTests.map((test, index) => `
            <div class="selected-item">
                <div class="item-content">
                    <div class="item-icon">
                        <i class="fas fa-vial"></i>
                    </div>
                    <div class="item-details">
                        <h6>${test.name}</h6>
                        ${test.category ? `<p>${test.category}</p>` : ''}
                    </div>
                </div>
                <div class="item-actions">
                    <button class="btn-icon btn-remove" onclick="removeTest(${index})" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `).join('');
    }

    // Update Modal Selected Tests
    function updateModalSelectedTests() {
        const container = document.getElementById('modalSelectedTests');
        
        if (selectedTests.length === 0) {
            container.innerHTML = '';
            return;
        }
        
        container.innerHTML = selectedTests.map((test, index) => `
            <div class="selected-item">
                <div class="item-content">
                    <div class="item-icon">
                        <i class="fas fa-vial"></i>
                    </div>
                    <div class="item-details">
                        <h6>${test.name}</h6>
                        ${test.category ? `<p>${test.category}</p>` : ''}
                    </div>
                </div>
                <div class="item-actions">
                    <button class="btn-icon btn-remove" onclick="removeTest(${index})" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `).join('');
    }

    // Remove Medicine
    function removeMedicine(index) {
        selectedMedicines.splice(index, 1);
        updateSelectedMedicinesDisplay();
        updateModalSelectedMedicines();
        renderMedicineSuggestions();
        generatePreview();
    }

    // Remove Test
    function removeTest(index) {
        selectedTests.splice(index, 1);
        updateSelectedTestsDisplay();
        updateModalSelectedTests();
        renderTestSuggestions();
        generatePreview();
    }

    // Edit Medicine
    function editMedicine(index) {
        currentEditMedicineIndex = index;
        const med = selectedMedicines[index];
        
        const form = document.getElementById('editMedicineForm');
        form.innerHTML = `
            <div class="form-group">
                <label class="form-label">Medicine Name</label>
                <input type="text" id="editName" class="form-input" value="${med.name}">
            </div>
            
            <div class="form-group">
                <label class="form-label">Dosage Schedule</label>
                <select id="editSchedule" class="form-input">
                    <option value="1+0+1" ${med.schedule === '1+0+1' ? 'selected' : ''}>1+0+1 (Morning & Night)</option>
                    <option value="1+1+1" ${med.schedule === '1+1+1' ? 'selected' : ''}>1+1+1 (Three times daily)</option>
                    <option value="0+0+1" ${med.schedule === '0+0+1' ? 'selected' : ''}>0+0+1 (Night only)</option>
                    <option value="1+0+0" ${med.schedule === '1+0+0' ? 'selected' : ''}>1+0+0 (Morning only)</option>
                    <option value="0+1+0" ${med.schedule === '0+1+0' ? 'selected' : ''}>0+1+0 (Afternoon only)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Duration</label>
                <div class="duration-options">
                    <span class="duration-option ${med.duration.includes('3') ? 'active' : ''}" onclick="selectDuration(3)">3 days</span>
                    <span class="duration-option ${med.duration.includes('5') ? 'active' : ''}" onclick="selectDuration(5)">5 days</span>
                    <span class="duration-option ${med.duration.includes('7') ? 'active' : ''}" onclick="selectDuration(7)">7 days</span>
                    <span class="duration-option ${med.duration.includes('10') ? 'active' : ''}" onclick="selectDuration(10)">10 days</span>
                    <span class="duration-option ${med.duration.includes('14') ? 'active' : ''}" onclick="selectDuration(14)">14 days</span>
                    <span class="duration-option ${med.duration.includes('30') ? 'active' : ''}" onclick="selectDuration(30)">30 days</span>
                </div>
                <input type="text" id="editDuration" class="form-input mt-2" value="${med.duration}" placeholder="Custom duration">
            </div>
            
            <div class="form-group">
                <label class="form-label">Instructions</label>
                <input type="text" id="editInstructions" class="form-input" value="${med.instructions || ''}" 
                       placeholder="e.g., After food, Before meal...">
            </div>
            
            <div class="action-buttons">
                <button type="button" class="btn-secondary" onclick="closeEditMedicineModal()">
                    Cancel
                </button>
                <button type="button" class="btn-primary" onclick="saveEditedMedicine()">
                    Save Changes
                </button>
            </div>
        `;
        
        const modal = new bootstrap.Modal(document.getElementById('editMedicineModal'));
        modal.show();
    }

    // Select Duration
    function selectDuration(days) {
        const durationInput = document.getElementById('editDuration');
        durationInput.value = `${days} days`;
        
        // Update active class
        document.querySelectorAll('.duration-option').forEach(option => {
            option.classList.remove('active');
        });
        event.target.classList.add('active');
    }

    // Save Edited Medicine
    function saveEditedMedicine() {
        const name = document.getElementById('editName').value;
        const schedule = document.getElementById('editSchedule').value;
        const duration = document.getElementById('editDuration').value;
        const instructions = document.getElementById('editInstructions').value;
        
        if (name && schedule && duration) {
            selectedMedicines[currentEditMedicineIndex] = {
                ...selectedMedicines[currentEditMedicineIndex],
                name: name,
                schedule: schedule,
                duration: duration,
                instructions: instructions
            };
            
            updateSelectedMedicinesDisplay();
            updateModalSelectedMedicines();
            generatePreview();
            
            closeEditMedicineModal();
        }
    }

    // Generate Preview
    function generatePreview() {
        const preview = document.getElementById('prescriptionPreview');
        const today = new Date().toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        const patientName = selectedPatient ? selectedPatient.name : "Patient Name";
        const patientId = selectedPatient ? `PT${selectedPatient.id.toString().padStart(4, '0')}` : "PT0000";
        const patientAge = selectedPatient ? `${selectedPatient.age} Years` : "--";
        const patientBlood = selectedPatient ? selectedPatient.blood_group : "--";

        const medicinesList = selectedMedicines.length > 0 ? 
            selectedMedicines.map(med => 
                `• ${med.name}\n  ${med.schedule} ${med.instructions ? '(' + med.instructions + ')' : ''} for ${med.duration}`
            ).join('\n\n') : 'No medications prescribed.';

        const testsList = selectedTests.length > 0 ? 
            selectedTests.map(test => `• ${test.name}`).join('\n') : 'No tests recommended.';

        const diagnosis = document.getElementById('diagnosisDetails')?.value || 'Not specified.';
        const instructions = document.getElementById('patientInstructions')?.value || 'Follow up as needed.';
        const followUp = document.getElementById('nextVisitDate')?.value || 'Not specified.';

        const html = `
========================================================================
                           MEDICAL PRESCRIPTION
========================================================================

Patient:     ${patientName}
Date:        ${today}
Age:         ${patientAge}
Blood Group: ${patientBlood}
Patient ID:  ${patientId}

------------------------------------------------------------------------
DIAGNOSIS:
${diagnosis}

------------------------------------------------------------------------
MEDICATIONS:
${medicinesList}

------------------------------------------------------------------------
RECOMMENDED TESTS:
${testsList}

------------------------------------------------------------------------
INSTRUCTIONS:
${instructions}

------------------------------------------------------------------------
FOLLOW-UP:
${followUp}

========================================================================
Dr. {{ auth()->user()->name ?? 'Doctor Name' }}
MBBS, FCPS
${new Date().toLocaleDateString()}
        `;

        preview.textContent = html;
    }

    // Reset Form
    function resetForm() {
        selectedMedicines = [];
        selectedTests = [];
        
        // Reset form fields
        document.getElementById('prescriptionDate').value = new Date().toISOString().split('T')[0];
        document.getElementById('chiefComplaint').value = '';
        document.getElementById('diagnosisDetails').value = '';
        document.getElementById('patientInstructions').value = '';
        document.getElementById('nextVisitDate').value = '';
        document.getElementById('dietAdvice').value = '';
        
        // Reset selected items
        updateSelectedMedicinesDisplay();
        updateSelectedTestsDisplay();
        updateModalSelectedMedicines();
        updateModalSelectedTests();
        
        // Generate empty preview
        generatePreview();
    }

    // Save Prescription
    function savePrescription() {
        if (!selectedPatient) {
            alert('Please select a patient first');
            return;
        }

        // Update hidden fields
        const medicinesText = selectedMedicines.map(med => 
            `${med.name} – ${med.schedule} ${med.instructions ? '(' + med.instructions + ')' : ''} × ${med.duration}`
        ).join('\n');
        
        const testsText = selectedTests.map(test => 
            `${test.name}`
        ).join('\n');
        
        document.getElementById('medicinesData').value = medicinesText;
        document.getElementById('testsData').value = testsText;
        document.getElementById('patientId').value = selectedPatient.id;
        
        // Submit form
        document.getElementById('prescriptionForm').submit();
    }
</script>
@endsection