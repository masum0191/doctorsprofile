@extends('layouts.tenantadmin')
@section('title', 'Template Management')

@section('content')
<style>
    :root {
        --primary: #318069;
        --primary-dark: #276854;
        --primary-light: #e8f5f0;
        --secondary: #FFC107;
        --dark: #1e293b;
        --gray: #64748b;
        --gray-light: #f8fafc;
        --border: #e2e8f0;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    /* Page Header */
    .page-header-compact {
        margin-bottom: 1.5rem;
    }

    .page-title-compact {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .page-title-compact i {
        color: var(--primary);
        font-size: 1.5rem;
    }

    .page-subtitle {
        color: var(--gray);
        font-size: 0.85rem;
        margin: 0;
    }

    /* Template Grid - 3 items per row */
    .template-grid-compact {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }

    /* Compact Card Design */
    .template-card-compact {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid var(--border);
        transition: all 0.25s ease;
        position: relative;
    }

    .template-card-compact:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary);
    }

    /* Card Image */
    .card-image-compact {
        position: relative;
        height: 180px;
        overflow: hidden;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
        cursor: pointer;
    }

    .card-image-compact img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .template-card-compact:hover .card-image-compact img {
        transform: scale(1.05);
    }

    /* Image Overlay on Hover */
    .image-overlay {
        position: absolute;
        inset: 0;
        background: rgba(49, 128, 105, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 2;
    }

    .card-image-compact:hover .image-overlay {
        opacity: 1;
    }

    .overlay-preview-btn {
        background: white;
        color: var(--primary);
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .overlay-preview-btn:hover {
        transform: scale(1.05);
        box-shadow: var(--shadow-md);
    }

    /* Card Badge */
    .card-badge-compact {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        color: white;
        font-size: 0.65rem;
        font-weight: 600;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        letter-spacing: 0.3px;
        z-index: 3;
    }

    /* Card Content */
    .card-content-compact {
        padding: 1rem;
    }

    .card-title-compact {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.35rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-description-compact {
        font-size: 0.75rem;
        color: var(--gray);
        line-height: 1.4;
        margin-bottom: 0.75rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Card Meta */
    .card-meta-compact {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-top: 0.5rem;
        border-top: 1px solid var(--border);
        margin-bottom: 0.75rem;
    }

    .meta-item-compact {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.65rem;
        color: var(--gray);
    }

    .meta-item-compact i {
        font-size: 0.7rem;
        color: var(--primary);
    }

    /* Select Button */
    .btn-select-theme {
        width: 100%;
        padding: 0.5rem;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-select-theme:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    /* Empty State */
    .empty-state-compact {
        background: white;
        border-radius: 16px;
        padding: 3rem 2rem;
        text-align: center;
        border: 1px solid var(--border);
    }

    .empty-icon-compact {
        width: 70px;
        height: 70px;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .empty-icon-compact i {
        font-size: 2rem;
        color: var(--primary);
    }


    /* Selection Modal */
    .selection-modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 1100;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .selection-modal-content {
        background: white;
        border-radius: 16px;
        width: 100%;
        max-width: 400px;
        overflow: hidden;
        animation: modalSlideIn 0.3s ease-out;
    }

    .selection-modal-header {
        padding: 1.25rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .selection-modal-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary);
    }

    .selection-modal-body {
        padding: 1.25rem;
    }

    .selection-modal-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .btn-cancel {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: white;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-confirm {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        background: var(--primary);
        color: white;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-confirm:hover {
        background: var(--primary-dark);
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 1100px) {
        .template-grid-compact {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
    }

    @media (max-width: 768px) {
        .template-grid-compact {
            grid-template-columns: 1fr;
        }
        
        .page-title-compact {
            font-size: 1.25rem;
        }
    }
</style>

<div class="theme-container">
    <!-- Page Header -->
    <div class="page-header-compact">
        <h1 class="page-title-compact">
            Template
        </h1>
        <p class="page-subtitle">Browse, preview, and select your website template</p>
    </div>

    @if($templates->isEmpty())
        <div class="empty-state-compact">
            <div class="empty-icon-compact">
                <i class="fas fa-palette"></i>
            </div>
            <h4 class="fw-semibold text-gray-800 mb-2">No Templates Yet</h4>
            <p class="text-muted small mb-3">Get started by creating your first template</p>
        </div>
    @else
        <div class="template-grid-compact">
            @foreach($templates as $template)
                <div class="template-card-compact">
                    <div class="card-image-compact">
                        <img src="{{ url($template->image) }}" 
                             alt="{{ $template->title }}"
                             onerror="this.onerror=null; this.src='https://placehold.co/400x200/e8f5f0/318069?text=Template'">
                        
                        <!-- Hover Overlay with Preview Button -->
                        <div class="image-overlay">
                            <a href="{{ $template->url }}" target="_blank" class="overlay-preview-btn">
                                <i class="fas fa-eye"></i> Preview
                            </a>
                        </div>
                        
                        <span class="card-badge-compact">
                            <i class="fas fa-crown"></i> Premium
                        </span>
                    </div>
                    
                    <div class="card-content-compact">
                        <h4 class="card-title-compact">{{ $template->title }}</h4>
                        <p class="card-description-compact">{{ Str::limit($template->value, 60) }}</p>
                        
                        <div class="card-meta-compact">
                           
                            <div class="meta-item-compact">
                                <i class="fas fa-code-branch"></i>
                                <span>v{{ rand(1, 3) }}.0</span>
                            </div>
                            <div class="meta-item-compact">
                                <i class="fas fa-download"></i>
                                <span>{{ rand(50, 500) }} uses</span>
                            </div>
                        </div>
                        
                        <button type="button"
                                class="btn-select-theme open-select-modal"
                                data-url="{{ $template->url }}"
                                data-title="{{ $template->title }}">
                            <i class="fas fa-check-circle"></i> Select Theme
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Selection Confirmation Modal -->
<div id="selectionModal" class="selection-modal">
    <div class="selection-modal-content">
        <div class="selection-modal-header">
            <h3>
                <i class="fas fa-question-circle"></i>
                Confirm Selection
            </h3>
            <button type="button" class="close-selection-modal" style="background: none; border: none; font-size: 1.25rem; cursor: pointer;">&times;</button>
        </div>
        <div class="selection-modal-body">
            <p>You are about to select:</p>
            <div style="background: var(--primary-light); padding: 0.75rem; border-radius: 8px; margin: 0.75rem 0;">
                <strong id="selectedTemplateName" style="color: var(--primary);">Template Name</strong>
            </div>
            <p class="text-muted small mb-0">This template will be applied to your website. You can customize it later from the settings.</p>
        </div>
        <div class="selection-modal-footer">
            <button type="button" class="btn-cancel close-selection-modal">Cancel</button>
            <a href="#" id="confirmSelectLink" class="btn-confirm">
                <i class="fas fa-check-circle"></i> Confirm Selection
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectionModal = document.getElementById('selectionModal');
    const selectedTemplateName = document.getElementById('selectedTemplateName');
    const confirmSelectLink = document.getElementById('confirmSelectLink');
    
    function openModal(modalElement) {
        modalElement.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalElement) {
        modalElement.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    // Handle Select Theme buttons
    document.querySelectorAll('.open-select-modal').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const templateUrl = this.dataset.url;
            const templateTitle = this.dataset.title;
            
            selectedTemplateName.textContent = templateTitle;
            confirmSelectLink.href = templateUrl;
            openModal(selectionModal);
        });
    });
    
    // Close modal functions
    document.querySelectorAll('.close-selection-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            closeModal(selectionModal);
        });
    });
    
    selectionModal.addEventListener('click', (e) => {
        if (e.target === selectionModal) closeModal(selectionModal);
    });
    
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && selectionModal.style.display === 'flex') {
            closeModal(selectionModal);
        }
    });
});
</script>

<!-- Create Template Modal -->
<div id="createTemplateModal" class="image-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="modal-content" style="background: white; border-radius: 16px; width: 100%; max-width: 480px; max-height: 90vh; overflow: auto;">
        <div class="modal-header" style="padding: 1rem 1.25rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
            <h3 class="modal-title" style="font-size: 1.1rem; font-weight: 600;">
                <i class="fas fa-plus-circle text-primary me-2"></i>
                Add Template
            </h3>
            <button type="button" class="close-create-modal" style="background: none; border: none; font-size: 1.25rem; cursor: pointer;">&times;</button>
        </div>

        <form id="createTemplateForm" method="POST" action="{{ route('superadmin.templates.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="modal-body" style="padding: 1.25rem;">
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label" style="display: block; font-weight: 500; font-size: 0.8rem; margin-bottom: 0.4rem;">Template Title</label>
                    <input type="text" name="title" class="form-input" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 0.85rem;" placeholder="Enter template title" required>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label" style="display: block; font-weight: 500; font-size: 0.8rem; margin-bottom: 0.4rem;">Template Value</label>
                    <input type="text" name="value" class="form-input" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 0.85rem;" placeholder="Enter unique template value" required>
                    <p class="text-xs text-gray-500 mt-1">Example: `template-modern`</p>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label" style="display: block; font-weight: 500; font-size: 0.8rem; margin-bottom: 0.4rem;">Preview URL</label>
                    <input type="url" name="preview_url" class="form-input" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 0.85rem;" placeholder="https://example.com/template-preview">
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label" style="display: block; font-weight: 500; font-size: 0.8rem; margin-bottom: 0.4rem;">Template Image</label>
                    <div class="file-upload" id="createDropArea" style="border: 2px dashed #e5e7eb; border-radius: 10px; padding: 1.5rem; text-align: center; cursor: pointer;">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 1.5rem; color: #318069; margin-bottom: 0.5rem; display: block;"></i>
                        <div class="upload-text" style="font-size: 0.75rem;">
                            <strong>Click to upload</strong> or drag and drop
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            PNG, JPG, GIF (Max 20MB)
                        </div>
                        <input type="file" id="createFileInput" name="image" class="file-input" style="display: none;" accept="image/*">
                        <div id="createFileName" class="file-name" style="font-size: 0.7rem; margin-top: 0.5rem;"></div>
                    </div>
                    <div class="image-preview" id="createImagePreview" style="display: none; margin-top: 0.75rem;">
                        <img id="createPreviewImage" src="" alt="Template preview" style="width: 100%; border-radius: 8px; max-height: 150px; object-fit: cover;">
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="padding: 0.75rem 1.25rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" class="btn close-create-modal" style="padding: 0.5rem 1rem; border-radius: 6px; border: 1px solid #e5e7eb; background: white; font-size: 0.8rem;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; border-radius: 6px; background: linear-gradient(135deg, #318069, #276854); color: white; border: none; font-size: 0.8rem;">
                    <i class="fas fa-save me-1"></i> Create
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Template Modal -->
<div id="imageModal" class="image-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="modal-content" style="background: white; border-radius: 16px; width: 100%; max-width: 480px; max-height: 90vh; overflow: auto;">
        <div class="modal-header" style="padding: 1rem 1.25rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
            <h3 class="modal-title" style="font-size: 1.1rem; font-weight: 600;">
                <i class="fas fa-edit text-primary me-2"></i>
                Edit Template
            </h3>
            <button type="button" class="close-modal" style="background: none; border: none; font-size: 1.25rem; cursor: pointer;">&times;</button>
        </div>

        <form id="imageForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="modal-body" style="padding: 1.25rem;">
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label" style="display: block; font-weight: 500; font-size: 0.8rem; margin-bottom: 0.4rem;">Template Title</label>
                    <input type="text" id="titleInput" name="title" class="form-input" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 0.85rem;">
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label" style="display: block; font-weight: 500; font-size: 0.8rem; margin-bottom: 0.4rem;">Preview URL</label>
                    <input type="url" id="previewUrlInput" name="preview_url" class="form-input" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 0.85rem;">
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label" style="display: block; font-weight: 500; font-size: 0.8rem; margin-bottom: 0.4rem;">Current Image</label>
                    <div id="currentImagePreview" style="margin-bottom: 0.5rem;">
                        <img id="currentTemplateImage" src="" alt="Current template" style="width: 100%; border-radius: 8px; max-height: 120px; object-fit: cover;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label" style="display: block; font-weight: 500; font-size: 0.8rem; margin-bottom: 0.4rem;">Update Image (Optional)</label>
                    <div class="file-upload" id="dropArea" style="border: 2px dashed #e5e7eb; border-radius: 10px; padding: 1.5rem; text-align: center; cursor: pointer;">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 1.5rem; color: #318069; margin-bottom: 0.5rem; display: block;"></i>
                        <div class="upload-text" style="font-size: 0.75rem;">
                            <strong>Click to upload</strong> or drag and drop
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            PNG, JPG, WebP (Max 20MB)
                        </div>
                        <input type="file" id="fileInput" name="new_image" class="file-input" style="display: none;" accept="image/*">
                        <div id="fileName" class="file-name" style="font-size: 0.7rem; margin-top: 0.5rem;"></div>
                    </div>
                    <div class="image-preview" id="imagePreview" style="display: none; margin-top: 0.75rem;">
                        <img id="previewImage" src="" alt="Preview" style="width: 100%; border-radius: 8px; max-height: 150px; object-fit: cover;">
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="padding: 0.75rem 1.25rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" class="btn close-modal" style="padding: 0.5rem 1rem; border-radius: 6px; border: 1px solid #e5e7eb; background: white; font-size: 0.8rem;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; border-radius: 6px; background: linear-gradient(135deg, #318069, #276854); color: white; border: none; font-size: 0.8rem;">
                    <i class="fas fa-save me-1"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('imageModal');
    const createModal = document.getElementById('createTemplateModal');
    const form = document.getElementById('imageForm');
    const createForm = document.getElementById('createTemplateForm');
    const openCreateModalBtn = document.getElementById('openCreateModalBtn');
    const openCreateModalBtnEmpty = document.getElementById('openCreateModalBtnEmpty');
    const fileInput = document.getElementById('fileInput');
    const createFileInput = document.getElementById('createFileInput');
    const dropArea = document.getElementById('dropArea');
    const createDropArea = document.getElementById('createDropArea');
    const previewImage = document.getElementById('previewImage');
    const createPreviewImage = document.getElementById('createPreviewImage');
    const imagePreview = document.getElementById('imagePreview');
    const createImagePreview = document.getElementById('createImagePreview');
    const fileName = document.getElementById('fileName');
    const createFileName = document.getElementById('createFileName');
    const titleInput = document.getElementById('titleInput');
    const previewUrlInput = document.getElementById('previewUrlInput');
    const currentTemplateImage = document.getElementById('currentTemplateImage');

    const updateRouteBase = '{{ url("superadmin/templates") }}';

    function openModalFunc(modalElement) {
        modalElement.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModalFunc(modalElement) {
        modalElement.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    if (openCreateModalBtn) openCreateModalBtn.addEventListener('click', () => openModalFunc(createModal));
    if (openCreateModalBtnEmpty) openCreateModalBtnEmpty.addEventListener('click', () => openModalFunc(createModal));

    document.querySelectorAll('.open-modal').forEach(button => {
        button.addEventListener('click', function() {
            titleInput.value = this.dataset.title || '';
            previewUrlInput.value = this.dataset.previewUrl || '';
            currentTemplateImage.src = this.dataset.image || '';
            form.action = `${updateRouteBase}/${this.dataset.id}`;
            
            fileInput.value = '';
            imagePreview.style.display = 'none';
            fileName.textContent = '';

            openModalFunc(modal);
        });
    });

    document.querySelectorAll('.close-modal, .close-create-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            closeModalFunc(modal);
            closeModalFunc(createModal);
        });
    });

    [dropArea, createDropArea].forEach(area => {
        if (!area) return;
        area.addEventListener('click', () => area.querySelector('.file-input')?.click());
        area.addEventListener('dragover', (e) => {
            e.preventDefault();
            area.style.borderColor = '#318069';
            area.style.background = '#e8f5f0';
        });
        area.addEventListener('dragleave', () => {
            area.style.borderColor = '#e5e7eb';
            area.style.background = 'transparent';
        });
        area.addEventListener('drop', (e) => {
            e.preventDefault();
            area.style.borderColor = '#e5e7eb';
            area.style.background = 'transparent';
            const input = area.querySelector('.file-input');
            if (input && e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                input.dispatchEvent(new Event('change'));
            }
        });
    });

    fileInput.addEventListener('change', function(e) {
        if (this.files?.[0]) {
            fileName.textContent = this.files[0].name;
            const reader = new FileReader();
            reader.onload = (ev) => {
                previewImage.src = ev.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    createFileInput.addEventListener('change', function(e) {
        if (this.files?.[0]) {
            createFileName.textContent = this.files[0].name;
            const reader = new FileReader();
            reader.onload = (ev) => {
                createPreviewImage.src = ev.target.result;
                createImagePreview.style.display = 'block';
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) closeModalFunc(modal);
        if (e.target === createModal) closeModalFunc(createModal);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModalFunc(modal);
            closeModalFunc(createModal);
        }
    });
});
</script>
@endsection