@extends('layouts.admin')
@section('title', 'Create Prescription')

@section('content')
    <div class="global-container">
        <!-- Patient Selection Section -->
        <div class="patient-selection-section" id="patientSelectionSection">
            <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <h2>Create Prescription</h2>
                </div>
            </div>
            <div style="display:flex; justify-content:flex-end;">
                <button type="button" class="btn-primary" onclick="openAddPatientModal()">
                    <i class="fas fa-user-plus mr-2"></i> Add New Patient
                </button>
            </div>
            </div>
            <div class="modal fade compact-modal" id="addPatientModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content patient-modal">

                        <!-- Header -->
                        <div class="modal-header patient-modal-header">
                            <div class="header-left">
                                <div class="header-icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div>
                                    <h5 class="modal-title mb-0">Add New Patient</h5>
                                    <small class="text-muted">Create patient profile before prescription</small>
                                </div>
                            </div>

                            <button type="button" class="btn-close-custom" onclick="closeAddPatientModal()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="modal-body patient-modal-body">
                            <form id="addPatientForm">
                                @csrf

                                <!-- BASIC INFORMATION -->
                                <div class="form-section-card">
                                    <div class="section-title">
                                        <i class="fas fa-id-card"></i>
                                        <span>Basic Information</span>
                                    </div>

                                    <div class="form-grid-2">
                                        <div class="form-group">
                                            <label class="form-label">Patient Name *</label>
                                            <input type="text" name="name" class="form-input" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Age</label>
                                            <input type="number" name="age" min="0" max="150"
                                                class="form-input">
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Mobile *</label>
                                            <input type="text" name="mobile" class="form-input" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-input">
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Gender *</label>
                                            <select name="gender" class="form-input" required>
                                                <option value="">Select</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Address *</label>
                                            <input type="text" name="address" class="form-input" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- VITALITY -->
                                <div class="form-section-card">
                                    <div class="section-title">
                                        <i class="fas fa-heartbeat"></i>
                                        <span>Vitality</span>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Vitality Notes</label>
                                        <input type="text" name="vitality" class="form-input"
                                            placeholder="e.g. Normal, Weak, BP High">
                                    </div>
                                </div>

                                <!-- BASIC DETAILS -->
                                <div class="form-section-card">
                                    <div class="section-title">
                                        <i class="fas fa-notes-medical"></i>
                                        <span>Basic Details</span>
                                    </div>

                                    <div class="form-grid-3">
                                        <div class="form-group">
                                            <label class="form-label">Blood Group</label>
                                            <input type="text" name="basic_details[blood_group]" class="form-input">
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Height (cm)</label>
                                            <input type="number" step="0.01" name="basic_details[height]"
                                                class="form-input">
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Weight (kg)</label>
                                            <input type="number" step="0.01" name="basic_details[weight]"
                                                class="form-input">
                                        </div>
                                    </div>
                                </div>

                                <!-- EMERGENCY CONTACT -->
                                <div class="form-section-card">
                                    <div class="section-title">
                                        <i class="fas fa-phone-alt"></i>
                                        <span>Emergency Contact</span>
                                    </div>

                                    <div class="form-grid-3">
                                        <div class="form-group">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="emergency_contact[name]" class="form-input">
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Relationship</label>
                                            <input type="text" name="emergency_contact[relationship]"
                                                class="form-input">
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Contact Number</label>
                                            <input type="text" name="emergency_contact[contact]" class="form-input">
                                        </div>
                                    </div>
                                </div>

                                <!-- MEDICAL HISTORY -->
                                <div class="form-section-card">
                                    <div class="section-title">
                                        <i class="fas fa-file-medical"></i>
                                        <span>Medical History</span>
                                    </div>

                                    <div class="form-group">
                                        <textarea name="medical_history" rows="3" class="form-input"
                                            placeholder="Previous illness, surgery, allergy, chronic disease..."></textarea>
                                    </div>
                                </div>

                                <!-- ACTION -->
                                <div class="modal-footer patient-modal-footer">
                                    <button type="button" class="btn-secondary" onclick="closeAddPatientModal()">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn-primary">
                                        <i class="fas fa-save mr-2"></i> Save Patient
                                    </button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>


            <!-- Patient Search -->
            <div class="search-container">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="patientSearch" class="search-input"
                        placeholder="Search patient by name, phone number, or ID..." autocomplete="off">
                </div>

                <!-- Search Results Dropdown -->
                <div class="search-results" id="searchResults"></div>
            </div>

            <!-- Or Select from Recent Patients -->
            <div style="margin-top: 2rem;">
                <h4 style="font-size: 1rem; color: #374151; margin-bottom: 1rem;">Recent Patients</h4>
                <div class="suggestions-grid" id="recentPatients">
                    @foreach ($patients as $patient)
                        <div class="suggestion-card" onclick="selectPatient({{ json_encode($patient) }})">
                            <div class="suggestion-name">{{ $patient['name'] }}</div>
                            <div class="suggestion-details">
                                {{ $patient['phone'] }}<br>
                                Age: {{ $patient['age'] }} • Gender: {{ $patient['gender'] }}
                            </div>
                            <div class="suggestion-badge">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    @endforeach
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
                        <span class="stat-label">Gender</span>
                        <span class="stat-value" id="selectedPatientGender">--</span>
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
        <div id="prescriptionFormContainer" style="display: none;">
            <!-- Professional Patient Header -->
            <div class="patient-header" id="prescriptionPatientHeader">
                <div class="patient-main-info">
                    <div class="patient-details">
                        <h1 class="patient-name" id="prescriptionPatientName">
                            Name: Loading...
                        </h1>
                        <div class="patient-meta" id="prescriptionPatientId">
                            Patient ID: Loading...
                        </div>
                    </div>
                </div>

                <div class="patient-stats">
                    <div class="stat-item">
                        <span class="stat-label">Age</span>
                        <span class="stat-value" id="prescriptionPatientAge">--</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Gender</span>
                        <span class="stat-value" id="prescriptionPatientGender">--</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Phone</span>
                        <span class="stat-value" id="prescriptionPatientPhone">--</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Last Visit</span>
                        <span class="stat-value" id="prescriptionPatientLastVisit">--</span>
                    </div>
                </div>
            </div>

            <!-- Template Section -->
            @if ($prescriptions_template->count() > 0)
                <div class="form-section" id="templateSection">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div>
                            <h5>Prescription Template</h5>
                            <div class="section-subtitle">Use predefined templates</div>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label mb-2">Quick Templates</label>
                            <div class="quick-templates-grid" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                @foreach ($prescriptions_template as $template)
                                    <button type="button" class="template-chip"
                                        onclick="loadTemplate({{ $template->id }})">
                                        <i class="fas fa-prescription"></i> {{ $template->template_name }}
                                    </button>
                                @endforeach
                                <button type="button" class="template-chip" onclick="clearTemplate()"
                                    style="background: #f3f4f6; color: #6b7280;">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                        </div>

                        <div class="form-group" id="templateInfo" style="display: none;">
                            <label class="form-label mb-2">Active Template</label>
                            <div class="template-info-box">
                                <div
                                    style="font-size: 0.875rem; color: var(--primary); font-weight: 500; margin-bottom: 0.25rem;">
                                    <span id="activeTemplateName">Template Name</span>
                                </div>
                                <div id="templateDescription" style="font-size: 0.75rem; color: #6b7280;">
                                    Template description will appear here
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Diagnosis Section -->
            <form method="POST" action="{{ route('admin.prescriptions.store') }}" id="prescriptionForm">
                @csrf
                <input type="hidden" name="doctor_id" value="{{ auth()->id() }}">
                <input type="hidden" name="patient_id" id="patientId">

                <div class="form-section">
                    <div class="section-title mb-3">
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
                            <label class="form-label">Prescription Date <span class="text-danger">*</span></label>
                            <input type="date" name="prescribed_date" class="form-input" id="prescriptionDate"
                                value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Chief Complaint</label>
                            <input type="text" name="chief_complaint" class="form-input" id="chiefComplaint"
                                placeholder="e.g., Fever, Headache...">
                        </div>

                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label class="form-label">Diagnosis Details</label>
                            <textarea name="diagnosis" rows="3" class="form-input" id="diagnosisDetails"
                                placeholder="Enter diagnosis details..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Medicines Section -->
                <div class="form-section">
                    <div class="section-title mb-3">
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

                    <!-- Hidden medicine fields -->
                    <div id="hiddenMedicineFields"></div>
                </div>

                <!-- Tests Section -->
                <div class="form-section">
                    <div class="section-title mb-3">
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

                    <!-- Hidden test fields -->
                    <div id="hiddenTestFields"></div>
                </div>

                <!-- Instructions Section -->
                <div class="form-section">
                    <div class="section-title mb-3">
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
                            <textarea name="instructions" rows="3" class="form-input" id="patientInstructions"
                                placeholder="Enter instructions for the patient..."></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Follow-up Date</label>
                            <input type="date" name="next_visit_date" class="form-input" id="nextVisitDate">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Diet Advice</label>
                            <textarea name="diet_advice" rows="2" class="form-input" id="dietAdvice"
                                placeholder="Diet recommendations..."></textarea>
                        </div>
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
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i> Save Prescription
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Medicine Modal -->
    <div class="modal fade compact-modal" id="medicineModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h5 class="modal-title">
                        <i class="fas fa-pills mr-2"></i>Add Medicine
                    </h5>
                    <button type="button" class="btn-close-custom" onclick="closeMedicineModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="medicineSearch" class="search-input"
                            placeholder="Search medicine or type custom name..." onkeyup="handleMedicineSearch(event)">
                    </div>

                    <div class="selected-items-container" id="modalSelectedMedicines">
                        <!-- Selected medicines will appear here -->
                    </div>

                    <div id="medicineSuggestionsContainer">
                        <div class="selected-items-header">Suggestions</div>
                        <div class="suggestions-grid" id="medicineSuggestions">
                            @foreach ($medicines as $medicine)
                                <div class="suggestion-card" onclick="addMedicine({{ $medicine->id }})">
                                    <div class="suggestion-name">{{ $medicine->medicine_name }}</div>
                                    <div class="suggestion-details">
                                        {{ $medicine->medicine_dose }}<br>
                                        {{ $medicine->medicine_type }}
                                    </div>
                                    <div class="suggestion-badge">
                                        <i class="fas fa-plus"></i>
                                    </div>
                                </div>
                            @endforeach
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
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="testSearch" class="search-input"
                            placeholder="Search tests or type custom name..." onkeyup="handleTestSearch(event)">
                    </div>

                    <div class="selected-items-container" id="modalSelectedTests">
                        <!-- Selected tests will appear here -->
                    </div>

                    <div id="testSuggestionsContainer">
                        <div class="selected-items-header">Suggestions</div>
                        <div class="suggestions-grid" id="testSuggestions">
                            @foreach ($tests as $test)
                                <div class="suggestion-card" onclick="addTest({{ $test->id }})">
                                    <div class="suggestion-name">{{ $test->name }}</div>
                                    <div class="suggestion-details">{{ $test->category ?? 'Test' }}</div>
                                    <div class="suggestion-badge">
                                        <i class="fas fa-plus"></i>
                                    </div>
                                </div>
                            @endforeach
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

    <script>
        // Data from backend
        const allPatients = @json($patients);
        const allMedicines = @json($medicines);
        const allTests = @json($tests);
        const allTemplates = @json($prescriptions_template);

        // State Management
        let selectedPatient = null;
        let selectedMedicines = [];
        let selectedTests = [];
        let currentEditMedicineIndex = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupSearch();
        });

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
                    (patient.phone && patient.phone.includes(searchTerm)) ||
                    patient.id.toString().includes(searchTerm)
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
                    <div class="search-result-item" onclick="selectPatient(${JSON.stringify(patient).replace(/"/g, '&quot;')})">
                        <div class="result-name">${patient.name}</div>
                        <div class="result-details">
                            ${patient.phone} • Age: ${patient.age} • Gender: ${patient.gender}
                        </div>
                    </div>
                `).join('');
                }

                searchResults.style.display = 'block';
            });

            document.addEventListener('click', function(event) {
                if (!searchResults.contains(event.target) && event.target !== searchInput) {
                    searchResults.style.display = 'none';
                }
            });
        }

        // Select Patient
        function selectPatient(patient) {
            selectedPatient = patient;

            // Update display
            document.getElementById('selectedPatientAvatar').textContent = patient.avatar;
            document.getElementById('selectedPatientName').textContent = patient.name;
            document.getElementById('selectedPatientId').textContent = `PT${patient.id.toString().padStart(4, '0')}`;
            document.getElementById('selectedPatientAge').textContent = `${patient.age} Years`;
            document.getElementById('selectedPatientGender').textContent = patient.gender;
            document.getElementById('selectedPatientPhone').textContent = patient.phone;
            document.getElementById('selectedPatientLastVisit').textContent =
                patient.last_visit ? new Date(patient.last_visit).toLocaleDateString() : '--';

            // Show selected patient display
            document.getElementById('selectedPatientDisplay').style.display = 'block';
            document.getElementById('searchResults').style.display = 'none';
            document.getElementById('patientSearch').value = '';

            // Hide recent patients
            document.getElementById('recentPatients').style.display = 'none';
        }

        // Update patient header in prescription form
        function updatePrescriptionPatientHeader() {
            if (!selectedPatient) return;

            document.getElementById('prescriptionPatientName').textContent =
                `Name: ${selectedPatient.name}`;
            document.getElementById('prescriptionPatientId').textContent =
                `Patient ID: PT${selectedPatient.id.toString().padStart(4, '0')}`;
            document.getElementById('prescriptionPatientAge').textContent =
                `${selectedPatient.age} Years`;
            document.getElementById('prescriptionPatientGender').textContent =
                selectedPatient.gender;
            document.getElementById('prescriptionPatientPhone').textContent =
                selectedPatient.phone;
            document.getElementById('prescriptionPatientLastVisit').textContent =
                selectedPatient.last_visit ? new Date(selectedPatient.last_visit).toLocaleDateString() : '--';
        }

        // Change Patient
        function changePatient() {
            selectedPatient = null;
            selectedMedicines = [];
            selectedTests = [];

            document.getElementById('prescriptionFormContainer').style.display = 'none';
            document.getElementById('patientSelectionSection').style.display = 'block';
            document.getElementById('selectedPatientDisplay').style.display = 'none';
            document.getElementById('recentPatients').style.display = 'grid';

            resetForm();
        }

        // Start Prescription
        function startPrescription() {
            if (!selectedPatient) {
                alert('Please select a patient first');
                return;
            }

            document.getElementById('patientSelectionSection').style.display = 'none';
            document.getElementById('prescriptionFormContainer').style.display = 'block';
            updatePrescriptionPatientHeader();
            document.getElementById('patientId').value = selectedPatient.id;
        }

        // Modal Functions
        function openMedicineModal() {
            renderMedicineSuggestions();
            updateModalSelectedMedicines();
            const modal = new bootstrap.Modal(document.getElementById('medicineModal'));
            modal.show();

            setTimeout(() => {
                document.getElementById('medicineSearch').focus();
            }, 100);
        }

        function closeMedicineModal() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('medicineModal'));
            modal.hide();
        }

        function openTestModal() {
            renderTestSuggestions();
            updateModalSelectedTests();
            const modal = new bootstrap.Modal(document.getElementById('testModal'));
            modal.show();

            setTimeout(() => {
                document.getElementById('testSearch').focus();
            }, 100);
        }

        function closeTestModal() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('testModal'));
            modal.hide();
        }

        function closeEditMedicineModal() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('editMedicineModal'));
            modal.hide();
        }

        // Search Functions
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
                setTimeout(() => {
                    renderMedicineSuggestions();
                }, 100);
            }
        }

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
                setTimeout(() => {
                    renderTestSuggestions();
                }, 100);
            }
        }

        // Render Suggestions
        function renderMedicineSuggestions() {
            const container = document.getElementById('medicineSuggestions');
            const searchTerm = document.getElementById('medicineSearch')?.value.toLowerCase() || '';

            const filtered = allMedicines.filter(med => {
                if (selectedMedicines.some(m => m.id === med.id && !m.custom)) {
                    return false;
                }
                return med.medicine_name.toLowerCase().includes(searchTerm) ||
                    (med.generic_name && med.generic_name.toLowerCase().includes(searchTerm));
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
                <div class="suggestion-name">${med.medicine_name}</div>
                <div class="suggestion-details">
                    ${med.generic_name || ''}<br>
                    <small>${med.medicine_dose || ''}</small>
                </div>
                <div class="suggestion-badge">
                    <i class="fas fa-plus"></i>
                </div>
            </div>
        `).join('');
        }

        function renderTestSuggestions() {
            const container = document.getElementById('testSuggestions');
            const searchTerm = document.getElementById('testSearch')?.value.toLowerCase() || '';

            const filtered = allTests.filter(test => {
                if (selectedTests.some(t => t.id === test.id && !t.custom)) {
                    return false;
                }
                return test.name.toLowerCase().includes(searchTerm);
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
                <div class="suggestion-badge">
                    <i class="fas fa-plus"></i>
                </div>
            </div>
        `).join('');
        }

        // Add Medicine
        function addMedicine(medicineId = null) {
            if (medicineId) {
                const medicine = allMedicines.find(m => m.id === medicineId);
                if (medicine) {
                    selectedMedicines.push({
                        id: medicine.id,
                        name: medicine.medicine_name,
                        dosage: medicine.medicine_dose || '',
                        frequency: 'Once daily',
                        duration: '7 days',
                        instructions: medicine.medicine_description || '',
                        type: medicine.medicine_type || '',
                        custom: false
                    });
                }
            }

            updateSelectedMedicinesDisplay();
            updateModalSelectedMedicines();
            updateHiddenMedicineFields();
            renderMedicineSuggestions();
        }

        function addCustomMedicine(name) {
            if (!name) return;

            const existing = selectedMedicines.find(m =>
                m.name.toLowerCase() === name.toLowerCase() && m.custom
            );

            if (!existing) {
                selectedMedicines.push({
                    id: Date.now(),
                    name: name,
                    dosage: "",
                    frequency: "Once daily",
                    duration: "7 days",
                    instructions: "",
                    custom: true
                });
            }

            updateSelectedMedicinesDisplay();
            updateModalSelectedMedicines();
            updateHiddenMedicineFields();
        }

        // Add Test
        function addTest(testId = null) {
            if (testId) {
                const test = allTests.find(t => t.id === testId);
                if (test) {
                    selectedTests.push({
                        id: test.id,
                        name: test.name,
                        custom: false
                    });
                }
            }

            updateSelectedTestsDisplay();
            updateModalSelectedTests();
            updateHiddenTestFields();
            renderTestSuggestions();
        }

        function addCustomTest(name) {
            if (!name) return;

            const existing = selectedTests.find(t =>
                t.name.toLowerCase() === name.toLowerCase() && t.custom
            );

            if (!existing) {
                selectedTests.push({
                    id: Date.now(),
                    name: name,
                    custom: true
                });
            }

            updateSelectedTestsDisplay();
            updateModalSelectedTests();
            updateHiddenTestFields();
        }

        // Update Displays
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
                        <p>${med.dosage || ''} • ${med.frequency} • ${med.duration} • ${med.instructions}</p>
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
                        <p>${med.dosage || ''} • ${med.frequency} • ${med.duration}</p>
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

        // Update Hidden Fields for Form Submission
        function updateHiddenMedicineFields() {
            const container = document.getElementById('hiddenMedicineFields');
            container.innerHTML = '';

            selectedMedicines.forEach((med, index) => {
                if (!med.custom) {
                    container.innerHTML += `
                    <input type="hidden" name="medicines[${index}][medicine_id]" value="${med.id}">
                    <input type="hidden" name="medicines[${index}][name]" value="${med.name}">
                    <input type="hidden" name="medicines[${index}][dosage]" value="${med.dosage || ''}">
                    <input type="hidden" name="medicines[${index}][frequency]" value="${med.frequency || ''}">
                    <input type="hidden" name="medicines[${index}][duration]" value="${med.duration || ''}">
                    <input type="hidden" name="medicines[${index}][instruction]" value="${med.instructions || ''}">
                `;
                }
            });
        }

        function updateHiddenTestFields() {
            const container = document.getElementById('hiddenTestFields');
            container.innerHTML = '';

            selectedTests.forEach((test, index) => {
                if (!test.custom) {
                    container.innerHTML += `
                    <input type="hidden" name="tests[${index}][test_id]" value="${test.id}">
                    <input type="hidden" name="tests[${index}][name]" value="${test.name}">
                `;
                }
            });
        }

        // Remove Items
        function removeMedicine(index) {
            selectedMedicines.splice(index, 1);
            updateSelectedMedicinesDisplay();
            updateModalSelectedMedicines();
            updateHiddenMedicineFields();
            renderMedicineSuggestions();
        }

        function removeTest(index) {
            selectedTests.splice(index, 1);
            updateSelectedTestsDisplay();
            updateModalSelectedTests();
            updateHiddenTestFields();
            renderTestSuggestions();
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
                <label class="form-label">Dosage</label>
                <input type="text" id="editDosage" class="form-input" value="${med.dosage || ''}"
                       placeholder="e.g., 500mg, 1 tablet">
            </div>

            <div class="form-group">
                <label class="form-label">Frequency</label>
                <select id="editFrequency" class="form-input">
                    <option value="Once daily" ${med.frequency === 'Once daily' ? 'selected' : ''}>Once daily</option>
                    <option value="Twice daily" ${med.frequency === 'Twice daily' ? 'selected' : ''}>Twice daily</option>
                    <option value="Three times daily" ${med.frequency === 'Three times daily' ? 'selected' : ''}>Three times daily</option>
                    <option value="Four times daily" ${med.frequency === 'Four times daily' ? 'selected' : ''}>Four times daily</option>
                    <option value="As needed" ${med.frequency === 'As needed' ? 'selected' : ''}>As needed</option>
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

        function selectDuration(days) {
            const durationInput = document.getElementById('editDuration');
            durationInput.value = `${days} days`;

            document.querySelectorAll('.duration-option').forEach(option => {
                option.classList.remove('active');
            });
            event.target.classList.add('active');
        }

        function saveEditedMedicine() {
            const name = document.getElementById('editName').value;
            const dosage = document.getElementById('editDosage').value;
            const frequency = document.getElementById('editFrequency').value;
            const duration = document.getElementById('editDuration').value;
            const instructions = document.getElementById('editInstructions').value;

            if (name && duration) {
                selectedMedicines[currentEditMedicineIndex] = {
                    ...selectedMedicines[currentEditMedicineIndex],
                    name: name,
                    dosage: dosage,
                    frequency: frequency,
                    duration: duration,
                    instructions: instructions
                };

                updateSelectedMedicinesDisplay();
                updateModalSelectedMedicines();
                updateHiddenMedicineFields();

                closeEditMedicineModal();
            }
        }

        // Load Template
        function loadTemplate(templateId) {
            fetch(`/admin/prescription-templates/${templateId}`)
                .then(response => response.json())
                .then(template => {
                    // Populate form with template data
                    document.getElementById('diagnosisDetails').value = template.diagnosis || '';
                    document.getElementById('chiefComplaint').value = template.chief_complaint || '';
                    document.getElementById('patientInstructions').value = template.instructions || '';
                    document.getElementById('dietAdvice').value = template.diet_advice || '';

                    // Show template info
                    document.getElementById('templateInfo').style.display = 'block';
                    document.getElementById('activeTemplateName').textContent = template.name;
                    document.getElementById('templateDescription').textContent = template.description;

                    // Clear existing items
                    selectedMedicines = [];
                    selectedTests = [];

                    // Load template medicines and tests
                    if (template.medicines) {
                        selectedMedicines = template.medicines;
                    }

                    if (template.tests) {
                        selectedTests = template.tests;
                    }

                    // Update displays
                    updateSelectedMedicinesDisplay();
                    updateSelectedTestsDisplay();
                    updateHiddenMedicineFields();
                    updateHiddenTestFields();

                    // Highlight active template
                    document.querySelectorAll('.template-chip').forEach(chip => {
                        chip.classList.remove('active');
                    });
                    event.target.classList.add('active');
                })
                .catch(error => {
                    console.error('Error loading template:', error);
                    //  alert('Failed to load template');
                });
        }

        function clearTemplate() {
            // Clear form fields
            document.getElementById('diagnosisDetails').value = '';
            document.getElementById('chiefComplaint').value = '';
            document.getElementById('patientInstructions').value = '';
            document.getElementById('dietAdvice').value = '';

            // Hide template info
            document.getElementById('templateInfo').style.display = 'none';

            // Remove active class
            document.querySelectorAll('.template-chip').forEach(chip => {
                chip.classList.remove('active');
            });
        }

        // Reset Form
        function resetForm() {
            selectedMedicines = [];
            selectedTests = [];

            document.getElementById('prescriptionDate').value = '{{ date('Y-m-d') }}';
            document.getElementById('chiefComplaint').value = '';
            document.getElementById('diagnosisDetails').value = '';
            document.getElementById('patientInstructions').value = '';
            document.getElementById('nextVisitDate').value = '';
            document.getElementById('dietAdvice').value = '';

            updateSelectedMedicinesDisplay();
            updateSelectedTestsDisplay();
            updateHiddenMedicineFields();
            updateHiddenTestFields();

            // Update patient header
            updatePrescriptionPatientHeader();
        }

        // Form Submission
        document.getElementById('prescriptionForm').addEventListener('submit', function(e) {
            if (!selectedPatient) {
                e.preventDefault();
                alert('Please select a patient');
                return;
            }

            // Validation
            const prescriptionDate = document.getElementById('prescriptionDate').value;
            if (!prescriptionDate) {
                e.preventDefault();
                alert('Please select a prescription date');
                return;
            }

            // Show loading
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
            submitBtn.disabled = true;
        });
    </script>
    <script>
        function openAddPatientModal() {
            new bootstrap.Modal(document.getElementById('addPatientModal')).show();
        }

        function closeAddPatientModal() {
            const modalEl = document.getElementById('addPatientModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }

        /* Submit Patient via AJAX */
        document.getElementById('addPatientForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = this;
            const btn = form.querySelector('button[type="submit"]');

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

            try {
                const response = await fetch("{{ route('admin.patients.store') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: new FormData(form)
                });

                let data;
                try {
                    data = await response.json();
                } catch {
                    throw {
                        message: 'Invalid server response'
                    };
                }

                if (!response.ok) {
                    throw data;
                }

                // ✅ SUCCESS
                allPatients.unshift(data.patient);
                selectPatient(data.patient);
                closeAddPatientModal();
                form.reset();

            } catch (err) {

                // ✅ VALIDATION ERRORS
                if (err.errors) {
                    const firstKey = Object.keys(err.errors)[0];
                    alert(err.errors[firstKey][0]);
                }
                // ✅ GENERAL ERROR
                else {
                    alert(err.message || 'Something went wrong');
                }

            } finally {
                // ✅ ALWAYS RESTORE BUTTON
                btn.disabled = false;
                btn.innerHTML = 'Save Patient';
            }
        });
    </script>

    <style>
        /* Add these styles to your existing CSS */
        .global-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .patient-selection-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .search-container {
            position: relative;
            margin-bottom: 20px;
        }

        .search-input-wrapper {
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 12px 16px 12px 40px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: #318069;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-top: 4px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .search-result-item {
            padding: 12px 16px;
            cursor: pointer;
            transition: background-color 0.2s;
            border-bottom: 1px solid #f3f4f6;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-item:hover {
            background-color: #f9fafb;
        }

        .result-name {
            font-weight: 500;
            color: #111827;
            margin-bottom: 4px;
        }

        .result-details {
            font-size: 12px;
            color: #6b7280;
        }

        .suggestions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 12px;
        }

        .suggestion-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .suggestion-card:hover {
            border-color: #318069;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1);
        }

        .suggestion-name {
            font-weight: 500;
            color: #111827;
        }

        .suggestion-details {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.4;
        }

        .suggestion-badge {
            position: absolute;
            right: 16px;
            top: 16px;
            color: #318069;
        }

        .selected-patient-display {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            margin-top: 24px;
            display: none;
        }

        .selected-patient-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .selected-patient-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .patient-avatar {
            width: 64px;
            height: 64px;
            background: #318069;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
        }

        .patient-details h3 {
            margin: 0 0 4px 0;
            font-size: 20px;
            color: #111827;
        }

        .patient-meta {
            font-size: 14px;
            color: #6b7280;
        }

        .btn-change-patient {
            padding: 8px 16px;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            color: #374151;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }

        .btn-change-patient:hover {
            background: #f9fafb;
        }

        .patient-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-box {
            background: #f9fafb;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
        }

        .stat-label {
            display: block;
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-value {
            display: block;
            font-size: 20px;
            font-weight: 600;
            color: #111827;
        }

        .patient-header {
            background: #318069;
            color: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .patient-main-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .patient-name {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 8px 0;
        }

        .patient-stats {
            display: flex;
            gap: 32px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-item .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            margin-bottom: 4px;
        }

        .stat-item .stat-value {
            color: white;
            font-size: 18px;
            font-weight: 600;
        }

        .form-section {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            background: #318069;
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .section-title h5 {
            font-size: 16px;
            color: #111827;
            margin: 0;
        }

        .section-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-top: 4px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #318069;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        textarea.form-input {
            min-height: 80px;
            resize: vertical;
        }

        .selected-items-container {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
            min-height: 20px;
        }

        .selected-items-header {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .selected-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        .selected-item:last-child {
            margin-bottom: 0;
        }

        .item-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .item-icon {
            width: 32px;
            height: 32px;
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .item-details h6 {
            margin: 0 0 4px 0;
            font-size: 14px;
            color: #111827;
        }

        .item-details p {
            margin: 0;
            font-size: 12px;
            color: #6b7280;
        }

        .item-actions {
            display: flex;
            gap: 8px;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 6px;
            background: transparent;
            color: #6b7280;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .btn-edit:hover {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .btn-remove:hover {
            background: #fee2e2;
            color: #dc2626;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-state-icon {
            width: 64px;
            height: 64px;
            background: #f3f4f6;
            color: #9ca3af;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 24px;
        }

        .empty-state p {
            color: #6b7280;
            margin: 0;
        }

        .btn-add {
            width: 100%;
            padding: 12px;
            background: white;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            color: #6b7280;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-add:hover {
            border-color: #318069;
            color: #318069;
            background: #eff6ff;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            padding: 24px 0;
            border-top: 1px solid #e5e7eb;
            margin-top: 24px;
        }

        .btn-primary,
        .btn-secondary {
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-primary {
            background: 318069;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(49, 128, 105, 0.5);
        }

        .btn-secondary {
            background: white;
            border: 1px solid #d1d5db;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        /* Modal Styles */
        .modal.compact-modal .modal-dialog {
            max-width: 500px;
        }

        .modal-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }

        .btn-close-custom {
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 4px;
            font-size: 16px;
        }

        .btn-close-custom:hover {
            color: #374151;
        }

        .modal-body {
            padding: 20px;
            max-height: 60vh;
            overflow-y: auto;
        }

        .search-wrapper {
            position: relative;
            margin-bottom: 16px;
        }

        .duration-options {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }

        .duration-option {
            padding: 6px 12px;
            background: #f3f4f6;
            border-radius: 4px;
            font-size: 12px;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s;
        }

        .duration-option:hover {
            background: #e5e7eb;
        }

        .duration-option.active {
            background: #318069;
            color: white;
        }

        .quick-templates-grid {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .template-chip {
            padding: 8px 16px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            font-size: 12px;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .template-chip:hover {
            background: #e5e7eb;
            border-color: #d1d5db;
        }

        .template-chip.active {
            background: #318069;
            color: white;
            border-color: #318069;
        }

        .template-info-box {
            padding: 12px;
            background: #eff6ff;
            border-radius: 8px;
            border-left: 4px solid #318069;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .global-container {
                padding: 10px;
            }

            .patient-selection-section,
            .form-section {
                padding: 20px;
            }

            .patient-stats {
                flex-wrap: wrap;
                gap: 16px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
            }
        }


        .patient-modal {
    border-radius: 14px;
    overflow: hidden;
}

.patient-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
    padding: 18px 24px;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: #318069;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
}

.patient-modal-body {
    background: #f9fafb;
    padding: 24px;
}

.form-section-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 18px;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #1f2937;
}

.section-title i {
    color: #318069;
}

.form-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.form-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

@media (max-width: 768px) {
    .form-grid-2,
    .form-grid-3 {
        grid-template-columns: 1fr;
    }
}

.patient-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding-top: 12px;
    border-top: 1px solid #e5e7eb;
}

    </style>
@endsection
