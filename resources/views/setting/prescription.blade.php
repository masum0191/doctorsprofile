@extends('layouts.admin')
@section('title', 'Create Prescription Template')
@section('content')
<style>
    /* Keep all your CSS styles exactly as they were */
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.1);
        --primary-dark: #2a6d5a;
        --primary-soft: rgba(49, 128, 105, 0.05);
    }

    .template-container {
        margin: 0 auto;
    }

    .page-header {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.5rem;
    }

    .page-title h1 {
        margin: 0;
        font-size: 1.75rem;
        font-weight: 600;
        color: #111827;
    }

    .page-subtitle {
        color: #6b7280;
        font-size: 0.875rem;
    }

    /* Template Creation Card */
    .template-card {
        margin-top: 1rem;
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
        width: 35px;
        height: 35px;
        border-radius: 8px;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
    }

    .form-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid #e5e7eb;
    }

    .section-title h3 {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
    }

    .section-subtitle {
        color: #6b7280;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    /* Form Styles */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        font-weight: 500;
        color: #374151;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .form-label .required {
        color: #dc2626;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
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

    textarea.form-input {
        min-height: 100px;
        resize: vertical;
    }

    /* Selected Items */
    .selected-items-container {
        margin-bottom: 1.5rem;
    }

    .selected-items-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .selected-items-title {
        font-weight: 500;
        color: #374151;
        font-size: 0.875rem;
    }

    .items-count {
        background: var(--primary);
        color: white;
        padding: 0.125rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
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
        flex: 1;
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
        flex-shrink: 0;
    }

    .item-details {
        flex: 1;
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

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #9ca3af;
        border: 2px dashed #e5e7eb;
        border-radius: 8px;
        background: #fafafa;
    }

    .empty-state-icon {
        font-size: 2rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state p {
        margin: 0;
        font-size: 0.875rem;
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

    /* Preview Section */
    .preview-section {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1.5rem;
        font-family: 'Courier New', monospace;
        white-space: pre-wrap;
        font-size: 0.875rem;
        line-height: 1.6;
        max-height: 300px;
        overflow-y: auto;
    }

    /* Modal Styling */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        width: 100%;
        max-width: 900px;
        max-height: 90vh;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background: var(--primary);
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-size: 1.125rem;
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
        font-size: 1.25rem;
        color: white;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .modal-close:hover {
        color: #f0f0f0;
    }

    .modal-body {
        padding: 1.5rem;
        max-height: 70vh;
        overflow-y: auto;
    }

    /* Search Bar in Modal */
    .search-wrapper {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .search-input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 2.5rem;
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

    /* Template List */
    .template-item {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s ease;
    }

    .template-item:hover {
        border-color: var(--primary);
        background: var(--primary-soft);
    }

    .template-item-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }

    .template-item-name {
        font-weight: 500;
        color: #111827;
        font-size: 0.875rem;
    }

    .template-item-desc {
        color: #6b7280;
        font-size: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .template-item-stats {
        display: flex;
        gap: 1rem;
        font-size: 0.75rem;
        color: #9ca3af;
    }

    /* Loading */
    .loading {
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

    /* Toast Notification */
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

<div class="template-container">
    <!-- Page Header -->
    <div class="page-title mb-3">
        <div class="section-icon">
            <i class="fas fa-layer-group"></i>
        </div>
        <div>
            <h1>Create Prescription Template</h1>
        </div>
    </div>

    <!-- Template Creation Form -->
    <div class="template-card">
        <!-- Template Basic Info -->
        <div class="form-section">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <h3>Template Information</h3>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Template Name <span class="required">*</span></label>
                    <input type="text" id="templateName" class="form-input"
                           placeholder="e.g., Fever Treatment, Diabetes Management">
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
                    <h3>Medications</h3>
                    <div class="section-subtitle">Common medicines for this template</div>
                </div>
            </div>

            <!-- Selected Medicines -->
            <div class="selected-items-container" id="selectedMedicinesContainer">
                <div class="selected-items-header">
                    <div class="selected-items-title">Selected Medicines</div>
                    <span class="items-count" id="medicinesCount">0</span>
                </div>
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
                    <h3>Recommended Tests</h3>
                    <div class="section-subtitle">Common tests for this template</div>
                </div>
            </div>

            <!-- Selected Tests -->
            <div class="selected-items-container" id="selectedTestsContainer">
                <div class="selected-items-header">
                    <div class="selected-items-title">Selected Tests</div>
                    <span class="items-count" id="testsCount">0</span>
                </div>
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
                    <h3>Instructions & Follow-up</h3>
                    <div class="section-subtitle">Common instructions for patients</div>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Follow-up Advice</label>
                    <input type="text" id="nextVisitDate" class="form-input"
                           placeholder="e.g., After 3 days, After 1 week">
                </div>

                <div class="form-group">
                    <label class="form-label">Diet Advice</label>
                    <textarea id="dietAdvice" class="form-input"
                              placeholder="Common diet recommendations..."></textarea>
                </div>
            </div>
        </div>

        <!-- Template Preview -->
        <div class="form-section">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div>
                    <h3>Template Preview</h3>
                    <div class="section-subtitle">Preview how your template will look</div>
                </div>
            </div>

            <div class="preview-section" id="templatePreview">
                <!-- Preview will be generated here -->
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button type="button" class="btn-secondary" onclick="resetForm()">
                    <i class="fas fa-redo mr-2"></i> Reset Form
                </button>
                <button type="button" class="btn-primary" id="saveTemplateBtn" onclick="saveTemplate()">
                    <i class="fas fa-save mr-2"></i> Save Template
                </button>
            </div>
        </div>
    </div>

    <!-- Existing Templates Section -->
    <div class="form-section">
        <div class="templates-list" id="existingTemplatesSection">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div>
                    <h3>Existing Templates</h3>
                </div>
            </div>

            <div id="existingTemplatesList">
                <div class="loading" id="templatesLoading">
                    <div class="loading-spinner"></div>
                    <p>Loading templates...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Medicine Modal -->
<div class="modal-overlay" id="medicineModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                <i class="fas fa-pills mr-2"></i>Add Medicine to Template
            </h5>
            <button type="button" class="modal-close" onclick="closeMedicineModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <!-- Search Bar -->
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="medicineSearch" class="search-input"
                       placeholder="Search medicine or type custom name..."
                       onkeyup="searchMedicines()">
            </div>

            <!-- Selected Medicines in Modal -->
            <div class="selected-items-container" id="modalSelectedMedicines">
                <!-- Selected medicines will appear here -->
            </div>

            <!-- Suggestions Grid -->
            <div id="medicineSuggestionsContainer">
                <div class="suggestions-grid" id="medicineSuggestions">
                    <!-- Dynamic content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Modal -->
<div class="modal-overlay" id="testModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                <i class="fas fa-vial mr-2"></i>Add Tests to Template
            </h5>
            <button type="button" class="modal-close" onclick="closeTestModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <!-- Search Bar -->
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="testSearch" class="search-input"
                       placeholder="Search tests..."
                       onkeyup="searchTests()">
            </div>

            <!-- Custom Test Form -->
            <div class="form-section" id="customTestForm">
                <div class="form-group">
                    <label class="form-label">Add Custom Test</label>
                    <input type="text" id="customTestName" class="form-input" placeholder="Enter custom test name">
                </div>
                <button type="button" class="btn-primary" onclick="addCustomTest()">
                    <i class="fas fa-plus mr-2"></i> Add Custom Test
                </button>
            </div>

            <!-- Selected Tests in Modal -->
            <div class="selected-items-container" id="modalSelectedTests">
                <!-- Selected tests will appear here -->
            </div>

            <!-- Test Suggestions -->
            <div id="testSuggestionsContainer">
                <div class="selected-items-header">Test Suggestions</div>
                <div class="suggestions-grid" id="testSuggestions">
                    <!-- Dynamic test suggestions will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Medicine Modal -->
<div class="modal-overlay" id="editMedicineModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                <i class="fas fa-edit mr-2"></i>Edit Medicine
            </h5>
            <button type="button" class="modal-close" onclick="closeEditMedicineModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="editMedicineForm">
            <!-- Dynamic form will be inserted here -->
        </div>
    </div>
</div>

<script>
    // State Management
    let selectedMedicines = [];
    let selectedTests = [];
    let currentEditMedicineIndex = null;
    let currentTemplateId = null; // For editing existing templates

    // Get CSRF token safely
    let csrfToken = '';
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (csrfMeta) {
        csrfToken = csrfMeta.getAttribute('content');
    } else {
        csrfToken = '{{ csrf_token() }}';
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        console.log('CSRF Token loaded:', csrfToken ? 'Yes' : 'No');
        updatePreview();
        loadExistingTemplates();

        // Auto-update preview on input changes
        document.getElementById('templateName').addEventListener('input', updatePreview);
        document.getElementById('nextVisitDate').addEventListener('input', updatePreview);
        document.getElementById('dietAdvice').addEventListener('input', updatePreview);
    });

    // Open Medicine Modal
    function openMedicineModal() {
        updateModalSelectedMedicines();
        document.getElementById('medicineModal').style.display = 'flex';
        document.getElementById('medicineSearch').focus();
        searchMedicines();
    }

    // Close Medicine Modal
    function closeMedicineModal() {
        document.getElementById('medicineModal').style.display = 'none';
    }

    // Open Test Modal
    function openTestModal() {
        updateModalSelectedTests();
        document.getElementById('testModal').style.display = 'flex';
        document.getElementById('testSearch').focus();
        searchTests();
    }

    // Close Test Modal
    function closeTestModal() {
        document.getElementById('testModal').style.display = 'none';
    }

    // Close Edit Medicine Modal
    function closeEditMedicineModal() {
        document.getElementById('editMedicineModal').style.display = 'none';
    }

    // Search Medicines from Database
    function searchMedicines() {
        const searchTerm = document.getElementById('medicineSearch').value.trim();

        fetch(`/admin/prescriptions-template/search/medicines?search=${encodeURIComponent(searchTerm)}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(medicines => {
            renderMedicineSuggestions(medicines, searchTerm);
        })
        .catch(error => {
            console.error('Error searching medicines:', error);
            renderMedicineSuggestions([], searchTerm);
        });
    }

    // Search Tests from Database
    function searchTests() {
        const searchTerm = document.getElementById('testSearch').value.trim();

        fetch(`/admin/prescriptions-template/tests/search?search=${encodeURIComponent(searchTerm)}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(tests => {
            renderTestSuggestions(tests, searchTerm);
        })
        .catch(error => {
            console.error('Error searching tests:', error);
            renderTestSuggestions([], searchTerm);
        });
    }

    // Render Medicine Suggestions
    function renderMedicineSuggestions(medicines, searchTerm = '') {
        const container = document.getElementById('medicineSuggestions');

        if (!medicines || medicines.length === 0) {
            container.innerHTML = `
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <div class="empty-state-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <p>${searchTerm ? 'No medicines found' : 'Type to search for medicines'}</p>
                </div>
            `;
            return;
        }

        // Filter out already selected medicines
        const filtered = medicines.filter(med => {
            return !selectedMedicines.some(m => m.id === med.id);
        });

        container.innerHTML = filtered.map(med => `
            <div class="suggestion-card" onclick="addMedicine(${med.id})">
                <div class="suggestion-name">${med.medicine_name}</div>
                <div class="suggestion-details">
                    ${med.medicine_dose} • ${med.medicine_day} days
                    ${med.medicine_mg ? `<br><small>Strength: ${med.medicine_mg}</small>` : ''}
                    ${med.company_name ? `<br><small>Company: ${med.company_name}</small>` : ''}
                </div>
                <div class="suggestion-badge">
                    <i class="fas fa-plus"></i>
                </div>
            </div>
        `).join('');
    }

    // Render Test Suggestions
    function renderTestSuggestions(tests, searchTerm = '') {
        const container = document.getElementById('testSuggestions');

        if (!tests || tests.length === 0) {
            container.innerHTML = `
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <div class="empty-state-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <p>${searchTerm ? 'No tests found' : 'Type to search for tests'}</p>
                </div>
            `;
            return;
        }

        // Filter out already selected tests
        const filtered = tests.filter(test => {
            return !selectedTests.some(t => t.id === test.id && !t.custom);
        });

        container.innerHTML = filtered.map(test => `
            <div class="suggestion-card" onclick="addTest(${test.id})">
                <div class="suggestion-name">${test.test_name}</div>
                <div class="suggestion-details">
                    ${test.test_category || 'General'}
                    ${test.description ? `<br><small>${test.description.substring(0, 50)}${test.description.length > 50 ? '...' : ''}</small>` : ''}
                </div>
                <div class="suggestion-badge">
                    <i class="fas fa-plus"></i>
                </div>
            </div>
        `).join('');
    }

    // Add Medicine from Database
    function addMedicine(medicineId) {
        // Check if already added
        if (selectedMedicines.some(m => m.id === medicineId)) {
            showToast('Medicine already added', 'error');
            return;
        }

        // Get medicine details
        fetch(`/admin/medicine-templates/${medicineId}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(medicine => {
            if (medicine) {
                selectedMedicines.push({
                    id: medicine.id,
                    name: medicine.medicine_name,
                    schedule: medicine.medicine_dose,
                    duration: medicine.medicine_day,
                    instructions: medicine.medicine_comments,
                    strength: medicine.medicine_mg,
                    custom: false
                });

                updateSelectedMedicinesDisplay();
                updateModalSelectedMedicines();
                searchMedicines(); // Refresh suggestions
                updatePreview();
                showToast('Medicine added successfully', 'success');
            }
        })
        .catch(error => {
            console.error('Error fetching medicine details:', error);
            showToast('Failed to add medicine', 'error');
        });
    }

    // Add Test from Database
    function addTest(testId) {
        // Check if already added
        if (selectedTests.some(t => t.id === testId && !t.custom)) {
            showToast('Test already added', 'error');
            return;
        }

        // Get test details
        fetch(`/admin/prescriptions-template/tests/${testId}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(test => {
            if (test) {
                selectedTests.push({
                    id: test.id,
                    name: test.test_name,
                    category: test.test_category || 'General',
                    custom: false
                });

                updateSelectedTestsDisplay();
                updateModalSelectedTests();
                searchTests(); // Refresh suggestions
                updatePreview();
                showToast('Test added successfully', 'success');
            }
        })
        .catch(error => {
            console.error('Error fetching test details:', error);
            showToast('Failed to add test', 'error');
        });
    }

    // Add Custom Test
    function addCustomTest() {
        const name = document.getElementById('customTestName').value.trim();

        if (!name) {
            showToast('Please enter test name', 'error');
            return;
        }

        // Check if already added
        if (selectedTests.some(t => t.name.toLowerCase() === name.toLowerCase())) {
            showToast('Test already added', 'error');
            return;
        }

        selectedTests.push({
            id: Date.now(),
            name: name,
            category: "Custom",
            custom: true
        });

        updateSelectedTestsDisplay();
        updateModalSelectedTests();
        updatePreview();
        showToast('Custom test added successfully', 'success');

        // Clear input
        document.getElementById('customTestName').value = '';
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
                        <p>${med.schedule} • ${med.duration} ${med.strength ? `• ${med.strength}` : ''}</p>
                        ${med.instructions ? `<p><small>${med.instructions}</small></p>` : ''}
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
        searchMedicines();
        updatePreview();
        showToast('Medicine removed', 'success');
    }

    // Remove Test
    function removeTest(index) {
        selectedTests.splice(index, 1);
        updateSelectedTestsDisplay();
        updateModalSelectedTests();
        searchTests();
        updatePreview();
        showToast('Test removed', 'success');
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
                    <option value="As needed" ${med.schedule === 'As needed' ? 'selected' : ''}>As needed</option>
                    <option value="Custom" ${!['1+0+1','1+1+1','0+0+1','1+0+0','0+1+0','As needed'].includes(med.schedule) ? 'selected' : ''}>Custom</option>
                </select>
                <input type="text" id="customSchedule" class="form-input mt-2"
                       ${!['1+0+1','1+1+1','0+0+1','1+0+0','0+1+0','As needed'].includes(med.schedule) ? 'style="display:block;"' : 'style="display:none;"'}
                       value="${!['1+0+1','1+1+1','0+0+1','1+0+0','0+1+0','As needed'].includes(med.schedule) ? med.schedule : ''}"
                       placeholder="Enter custom schedule">
            </div>

            <div class="form-group">
                <label class="form-label">Duration</label>
                <div class="duration-options">
                    <span class="duration-option ${med.duration.includes('3') || med.duration === '3' ? 'active' : ''}" onclick="selectDuration(3)">3 days</span>
                    <span class="duration-option ${med.duration.includes('5') || med.duration === '5' ? 'active' : ''}" onclick="selectDuration(5)">5 days</span>
                    <span class="duration-option ${med.duration.includes('7') || med.duration === '7' ? 'active' : ''}" onclick="selectDuration(7)">7 days</span>
                    <span class="duration-option ${med.duration.includes('10') || med.duration === '10' ? 'active' : ''}" onclick="selectDuration(10)">10 days</span>
                    <span class="duration-option ${med.duration.includes('14') || med.duration === '14' ? 'active' : ''}" onclick="selectDuration(14)">14 days</span>
                    <span class="duration-option ${med.duration.includes('30') || med.duration === '30' ? 'active' : ''}" onclick="selectDuration(30)">30 days</span>
                </div>
                <input type="text" id="editDuration" class="form-input mt-2" value="${med.duration}" placeholder="Custom duration">
            </div>

            <div class="form-group">
                <label class="form-label">Instructions</label>
                <input type="text" id="editInstructions" class="form-input" value="${med.instructions || ''}"
                       placeholder="e.g., After food, Before meal, With milk...">
            </div>

            <div class="form-group">
                <label class="form-label">Strength</label>
                <input type="text" id="editStrength" class="form-input" value="${med.strength || ''}"
                       placeholder="e.g., 500mg, 10mg/ml">
            </div>

            <div class="action-buttons">
                <button type="button" class="btn-secondary" onclick="closeEditMedicineModal()">
                    Cancel
                </button>
                <button type="button" class="btn-primary" onclick="saveEditedMedicine()">
                    Save Changes
                </button>
            </div>

            <script>
                document.getElementById('editSchedule').addEventListener('change', function() {
                    const customSchedule = document.getElementById('customSchedule');
                    customSchedule.style.display = this.value === 'Custom' ? 'block' : 'none';
                    if (this.value !== 'Custom') {
                        customSchedule.value = '';
                    }
                });
            <\/script>
        `;

        document.getElementById('editMedicineModal').style.display = 'flex';
    }

    // Select Duration in Edit Modal
    function selectDuration(days) {
        const durationInput = document.getElementById('editDuration');
        durationInput.value = `${days} days`;

        // Update active class
        const options = document.querySelectorAll('.duration-option');
        options.forEach(option => {
            option.classList.remove('active');
        });
        event.target.classList.add('active');
    }

    // Save Edited Medicine
    function saveEditedMedicine() {
        const name = document.getElementById('editName').value;
        let schedule = document.getElementById('editSchedule').value;
        const customSchedule = document.getElementById('customSchedule').value;
        const duration = document.getElementById('editDuration').value;
        const instructions = document.getElementById('editInstructions').value;
        const strength = document.getElementById('editStrength').value;

        if (schedule === 'Custom' && customSchedule) {
            schedule = customSchedule;
        }

        if (name && schedule && duration) {
            selectedMedicines[currentEditMedicineIndex] = {
                ...selectedMedicines[currentEditMedicineIndex],
                name: name,
                schedule: schedule,
                duration: duration,
                instructions: instructions,
                strength: strength
            };

            updateSelectedMedicinesDisplay();
            updateModalSelectedMedicines();
            updatePreview();

            closeEditMedicineModal();
            showToast('Medicine updated successfully', 'success');
        }
    }

    // Update Template Preview
    function updatePreview() {
        const preview = document.getElementById('templatePreview');

        const templateName = document.getElementById('templateName').value || '[Template Name]';
        const followUp = document.getElementById('nextVisitDate').value || 'Not specified';
        const dietAdvice = document.getElementById('dietAdvice').value || 'Not specified';

        const medicinesList = selectedMedicines.length > 0 ?
            selectedMedicines.map(med =>
                `• ${med.name} - ${med.schedule} (${med.duration}) ${med.strength ? `[${med.strength}]` : ''} ${med.instructions ? `- ${med.instructions}` : ''}`
            ).join('\n') : 'No medications in template.';

        const testsList = selectedTests.length > 0 ?
            selectedTests.map(test => `• ${test.name} ${test.category ? `(${test.category})` : ''}`).join('\n') : 'No tests in template.';

        const html = `
========================================================================
                     PRESCRIPTION TEMPLATE PREVIEW
========================================================================

Template:     ${templateName}

MEDICATIONS:
${medicinesList}

------------------------------------------------------------------------
RECOMMENDED TESTS:
${testsList}

------------------------------------------------------------------------
FOLLOW-UP:
${followUp}

------------------------------------------------------------------------
DIET ADVICE:
${dietAdvice}
========================================================================
        `;

        preview.textContent = html;
    }

    // Reset Form
    function resetForm() {
        if (!confirm('Are you sure you want to reset the form? All unsaved changes will be lost.')) {
            return;
        }

        // Reset form fields
        document.getElementById('templateName').value = '';
        document.getElementById('nextVisitDate').value = '';
        document.getElementById('dietAdvice').value = '';

        // Clear selected items
        selectedMedicines = [];
        selectedTests = [];
        currentTemplateId = null;
        updateSelectedMedicinesDisplay();
        updateSelectedTestsDisplay();
        updateModalSelectedMedicines();
        updateModalSelectedTests();

        // Reset search inputs
        document.getElementById('medicineSearch').value = '';
        document.getElementById('testSearch').value = '';
        document.getElementById('customTestName').value = '';

        // Update button text
        document.getElementById('saveTemplateBtn').innerHTML = '<i class="fas fa-save mr-2"></i> Save Template';

        // Update preview
        updatePreview();

        showToast('Form reset successfully', 'success');
    }

    // Save Template to Database
    function saveTemplate() {
        const templateName = document.getElementById('templateName').value.trim();

        if (!templateName) {
            showToast('Please enter a template name', 'error');
            document.getElementById('templateName').focus();
            return;
        }

        // Prepare form data
        const formData = new FormData();
        formData.append('_token', csrfToken);

        // Add _method for PUT if updating
        if (currentTemplateId) {
            formData.append('_method', 'PUT');
        }

        // Collect form data
        formData.append('template_name', templateName);
        formData.append('next_visit', document.getElementById('nextVisitDate').value.trim());
        formData.append('diet_advice', document.getElementById('dietAdvice').value.trim());

        // Add medicine IDs
        const medicineIds = selectedMedicines.filter(med => !med.custom).map(med => med.id);
        medicineIds.forEach(id => {
            formData.append('medicine_ids[]', id);
        });

        // Add test names (investigation_ids)
        const testNames = selectedTests.map(test => test.name );
        testNames.forEach(name => {
            formData.append('investigation_ids[]', name);
        });

        // Show loading
        const saveBtn = document.getElementById('saveTemplateBtn');
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
        saveBtn.disabled = true;

        // Determine URL - Use your actual route names
        const url = currentTemplateId
            ? `/admin/prescriptions-template/${currentTemplateId}`
            : '{{ route("admin.prescriptions-template.store") }}';

        console.log('Saving to URL:', url);
        console.log('CSRF Token present:', csrfToken ? 'Yes' : 'No');

        // Send request
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            console.log('Save response:', data);
            if (data.success) {
                showToast(data.message, 'success');

                // Reset form if not editing
                if (!currentTemplateId) {
                    resetForm();
                }

                // Reload templates list
                loadExistingTemplates();
            } else {
                throw new Error(data.message || 'Failed to save template');
            }
        })
        .catch(error => {
            console.error('Error saving template:', error);
            showToast(error.message || 'Failed to save template. Please try again.', 'error');
        })
        .finally(() => {
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        });
    }

    // Load Existing Templates from Database
    function loadExistingTemplates() {
    const container = document.getElementById('existingTemplatesList');

    fetch('{{ route("admin.prescriptions-template.index") }}', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest' // This header helps Laravel identify AJAX requests
        }
    })
    .then(response => {
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error("Expected JSON response but got: " + contentType);
        }
        return response.json();
    })
    .then(data => {
        const templates = data.prescriptions_template || [];
        displayTemplates(templates);
    })
    .catch(error => {
        console.error('Error loading templates:', error);
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <p>Failed to load templates. Please refresh the page.</p>
            </div>
        `;
    });
}

    // Display Templates
    function displayTemplates(templates) {
        const container = document.getElementById('existingTemplatesList');

        if (!templates || templates.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <p>No templates saved yet</p>
                </div>
            `;
            return;
        }

        // Sort by most recent
        templates.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

        container.innerHTML = templates.map(template => {
            const medicineIds = template.medicine_ids || [];
            const investigationIds = template.investigation_ids || [];

            return `
                <div class="template-item">
                    <div class="template-item-header">
                        <div>
                            <div class="template-item-name">${template.template_name}</div>
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button class="btn-icon" onclick="loadTemplate('${template.id}')" title="Load into form">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon btn-remove" onclick="deleteTemplate('${template.id}')" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="template-item-stats">
                        <span><i class="fas fa-pills"></i> ${medicineIds.length} meds</span>
                        <span><i class="fas fa-vial"></i> ${investigationIds.length} tests</span>
                        <span><i class="fas fa-calendar"></i> ${new Date(template.created_at).toLocaleDateString()}</span>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Load Template into Form for Editing
    function loadTemplate(templateId) {
        if (!confirm('Load this template into the form? Current form data will be replaced.')) {
            return;
        }

        showLoading(true);

        fetch(`/admin/prescriptions-template/${templateId}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            showLoading(false);

            if (!data.template) {
                showToast('Template not found', 'error');
                return;
            }

            const template = data.template;
            const medicineDetails = data.medicine_details || [];

            // Set current template ID for update
            currentTemplateId = templateId;

            // Populate form fields
            document.getElementById('templateName').value = template.template_name || '';
            document.getElementById('nextVisitDate').value = template.next_visit || '';
            document.getElementById('dietAdvice').value = template.diet_advice || '';

            // Load medicines
            selectedMedicines = medicineDetails.map(med => ({
                id: med.id,
                name: med.medicine_name,
                schedule: med.medicine_dose,
                duration: med.medicine_day,
                instructions: med.medicine_comments,
                strength: med.medicine_mg,
                custom: false
            }));
            updateSelectedMedicinesDisplay();

            // Load tests (convert investigation_ids array to test objects)
            selectedTests = (template.investigation_ids || []).map(testName => ({
                id: Date.now() + Math.random(),
                name: testName,
                category: "General",
                custom: true // Mark as custom since we only have names
            }));
            updateSelectedTestsDisplay();

            // Update button text
            document.getElementById('saveTemplateBtn').innerHTML = '<i class="fas fa-save mr-2"></i> Update Template';

            // Update preview
            updatePreview();

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });

            showToast('Template loaded successfully', 'success');
        })
        .catch(error => {
            showLoading(false);
            console.error('Error loading template:', error);
            showToast('Failed to load template', 'error');
        });
    }

    // Delete Template
    function deleteTemplate(templateId) {
        if (!confirm('Are you sure you want to delete this template?')) {
            return;
        }

        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('_method', 'DELETE');

        fetch(`/admin/prescriptions-template/${templateId}`, {
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
                showToast(data.message, 'success');
                loadExistingTemplates();
            } else {
                throw new Error(data.message || 'Failed to delete template');
            }
        })
        .catch(error => {
            console.error('Error deleting template:', error);
            showToast(error.message || 'Failed to delete template', 'error');
        });
    }

    // Show Loading
    function showLoading(show) {
        if (show) {
            document.body.insertAdjacentHTML('beforeend', `
                <div class="loading-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
                    <div class="loading-spinner"></div>
                </div>
            `);
        } else {
            const overlay = document.querySelector('.loading-overlay');
            if (overlay) overlay.remove();
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

    // Close modals when clicking outside
    document.addEventListener('click', function(event) {
        const modals = ['medicineModal', 'testModal', 'editMedicineModal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (event.target === modal) {
                if (modalId === 'medicineModal') closeMedicineModal();
                if (modalId === 'testModal') closeTestModal();
                if (modalId === 'editMedicineModal') closeEditMedicineModal();
            }
        });
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeMedicineModal();
            closeTestModal();
            closeEditMedicineModal();
        }
    });
</script>
@endsection
