@extends('layouts.admin')
@section('title', 'New Prescription')

@section('content')
<style>
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.1);
        --primary-dark: #2a6d5a;
        --primary-soft: rgba(49, 128, 105, 0.05);
        --primary-hover: rgba(49, 128, 105, 0.15);
    }

    /* Professional Patient Header */
    .patient-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border-left: 4px solid var(--primary);
    }

    .patient-main-info {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .patient-avatar {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    .patient-details {
      width: 100%;
         display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .patient-name {
        font-size: 1rem;
        font-weight: 500;
        color: #111827;
        margin-bottom: 0.25rem;
    }

    .patient-meta {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .patient-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
    }

    .stat-item {
        text-align: center;
        padding: 0.75rem;
        background: #f9fafb;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-label {
        display: block;
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: .9rem;
        font-weight: 600;
        color: #111827;
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

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f3f4f6;
    }

    .section-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
    }

    .section-title h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 500;
        color: #111827;
    }

    .section-subtitle {
        color: #6b7280;
        font-size: 0.775rem;
    }

    /* Form Layout */
  

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

    /* Selected Items List */
    .selected-items-header {
        margin-bottom: 1rem;
        font-weight: 500;
        color: #374151;
        font-size: 0.875rem;
    }

    .selected-items-container {
        max-height: 220px;
        overflow-y: auto;
        /* display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem; */
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
        background: var(--primary);
        padding: 10px 24px;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
    }

    .modal-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #fff;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-body {
        padding: 1.5rem;
        overflow-y: auto;
    }

    .btn-close-custom {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: #dfdcdc;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-close-custom:hover {
        color: #fff;
    }

    /* Search Bar */
    .search-wrapper {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .search-input {
        width: 100%;
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
        width: 100% !important;
    }

    .search-icon {
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
        opacity: 0.5;
    }

    /* Duration Options */
    .duration-options {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin: 1rem 0;
    }

    .duration-option {
        padding: 0.375rem 0.875rem;
        border: 1px solid #d1d5db;
        border-radius: 20px;
        font-size: 0.75rem;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s ease;
        background: white;
    }

    .duration-option:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .duration-option.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
</style>

<div class="compact-container">
    <!-- Professional Patient Header -->
    <div class="patient-header">
        <div class="patient-main-info">
           
            <div class="patient-details">
                <h1 class="patient-name">
                    Name: {{ isset($patient) ? $patient->name : 'Patient Name' }}
                </h1>
                <div class="patient-meta">
                    Patient ID: {{ isset($patient) ? 'PT' . str_pad($patient->id, 4, '0', STR_PAD_LEFT) : 'N/A' }}
                </div>
            </div>
        </div>
        
        <div class="patient-stats">
            <div class="stat-item">
                <span class="stat-label">Age</span>
                <span class="stat-value">35 Years</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Blood Group</span>
                <span class="stat-value">A+</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Phone</span>
                <span class="stat-value">+880 1712 345678</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Last Visit</span>
                <span class="stat-value">{{ now()->subDays(15)->format('M d, Y') }}</span>
            </div>
        </div>
    </div>

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
        
        <div class="row">
           <div class="col-6">
             <div class="form-group">
                <label class="form-label">Appointment Date</label>
                <input type="date" name="prescribed_date" class="form-input" 
                       value="{{ now()->toDateString() }}" required>
            </div>
           </div>
           <div class="col-6">
<div class="form-group" style="grid-column: 1 / -1;">
                <label class="form-label">Link to Appointment (Optional)</label>
                <select name="appointment_id" class="form-input">
                    <option value="">-- Select Appointment --</option>
                    @if(isset($lastAppointment))
                        <option value="{{ $lastAppointment->id }}">
                            {{ optional($lastAppointment->appointment_date)->format('M d, Y') }} 
                            at {{ \Carbon\Carbon::parse($lastAppointment->appointment_time)->format('h:i A') }}
                            ({{ ucfirst($lastAppointment->status) }})
                        </option>
                    @endif
                </select>
            </div>
           </div>
           <div class="col-12">
            <div class="form-group">
                <label class="form-label">Chief Complaint</label>
                <input type="text" name="chief_complaint" class="form-input" 
                       placeholder="e.g., Fever, Headache...">
            </div>
           </div>
           <div class="col-12">
             <div class="form-group">
                <label class="form-label">Diagnosis Details</label>
                <textarea name="diagnosis" rows="3" class="form-input" 
                          placeholder="Enter diagnosis details...">{{ old('diagnosis') }}</textarea>
            </div>
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
            <div class="selected-items-header">Selected Medicines ({{ isset($selectedMedicines) ? count($selectedMedicines) : 0 }})</div>
            <div id="medicinesList">
                <!-- Dynamic content will be inserted here -->
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
            <div class="selected-items-header">Selected Tests ({{ isset($selectedTests) ? count($selectedTests) : 0 }})</div>
            <div id="testsList">
                <!-- Dynamic content will be inserted here -->
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
                          placeholder="Enter instructions for the patient...">{{ old('instructions') }}</textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Follow-up Date</label>
                <input type="text" name="next_visit_date" class="form-input" 
                       placeholder="e.g., In 7 days">
            </div>
            
            <div class="form-group">
                <label class="form-label">Diet Advice</label>
                <textarea name="diet_advice" rows="2" class="form-input" 
                          placeholder="Diet recommendations..."></textarea>
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
        <a href="{{ route('admin.patients.index') }}" class="btn-secondary">
            <i class="fas fa-times mr-2"></i> Cancel
        </a>
        <button type="button" class="btn-primary" onclick="savePrescription()">
            <i class="fas fa-save mr-2"></i> Save Prescription
        </button>
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
<form method="POST" action="{{ route('admin.patients.prescriptions.store', isset($patient) ? $patient->id : '') }}" 
      id="prescriptionForm" style="display: none;">
    @csrf
    <input type="hidden" name="medicines" id="medicinesData">
    <input type="hidden" name="tests" id="testsData">
</form>

<script>
    // Sample Data
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
    let selectedMedicines = [];
    let selectedTests = [];
    let currentEditMedicineIndex = null;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateSelectedMedicinesDisplay();
        updateSelectedTestsDisplay();
        generatePreview();
    });

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
        const header = document.querySelector('#selectedMedicinesContainer .selected-items-header');
        
        if (selectedMedicines.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <p>No medicines added yet</p>
                </div>
            `;
            header.textContent = "Selected Medicines (0)";
            return;
        }
        
        header.textContent = `Selected Medicines (${selectedMedicines.length})`;
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
        const header = document.querySelector('#selectedTestsContainer .selected-items-header');
        
        if (selectedTests.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-vial"></i>
                    </div>
                    <p>No tests added yet</p>
                </div>
            `;
            header.textContent = "Selected Tests (0)";
            return;
        }
        
        header.textContent = `Selected Tests (${selectedTests.length})`;
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

        const medicinesList = selectedMedicines.length > 0 ? 
            selectedMedicines.map(med => 
                `• ${med.name}\n  ${med.schedule} ${med.instructions ? '(' + med.instructions + ')' : ''} for ${med.duration}`
            ).join('\n\n') : 'No medications prescribed.';

        const testsList = selectedTests.length > 0 ? 
            selectedTests.map(test => `• ${test.name}`).join('\n') : 'No tests recommended.';

        const html = `
========================================================================
                           MEDICAL PRESCRIPTION
========================================================================

Patient:     {{ isset($patient) ? $patient->name : 'Patient Name' }}
Date:        ${today}
Age:         35 Years
Blood Group: A+
Patient ID:  {{ isset($patient) ? 'PT' . str_pad($patient->id, 4, '0', STR_PAD_LEFT) : 'N/A' }}

------------------------------------------------------------------------
DIAGNOSIS:
${document.querySelector('textarea[name="diagnosis"]')?.value || 'Not specified.'}

------------------------------------------------------------------------
MEDICATIONS:
${medicinesList}

------------------------------------------------------------------------
RECOMMENDED TESTS:
${testsList}

------------------------------------------------------------------------
INSTRUCTIONS:
${document.querySelector('textarea[name="instructions"]')?.value || 'Follow up as needed.'}

------------------------------------------------------------------------
FOLLOW-UP:
${document.querySelector('input[name="next_visit_date"]')?.value || 'Not specified.'}

========================================================================
Dr. {{ auth()->user()->name ?? 'Doctor Name' }}
MBBS, FCPS
${new Date().toLocaleDateString()}
        `;

        preview.textContent = html;
    }

    // Save Prescription
    function savePrescription() {
        // Update hidden fields
        const medicinesText = selectedMedicines.map(med => 
            `${med.name} – ${med.schedule} ${med.instructions ? '(' + med.instructions + ')' : ''} × ${med.duration}`
        ).join('\n');
        
        const testsText = selectedTests.map(test => 
            `${test.name}`
        ).join('\n');
        
        document.getElementById('medicinesData').value = medicinesText;
        document.getElementById('testsData').value = testsText;
        
        // Submit form
        document.getElementById('prescriptionForm').submit();
    }
</script>
@endsection