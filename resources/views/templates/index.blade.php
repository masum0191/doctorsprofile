@extends('layouts.supperadmin')
@section('title', 'Template Management')

@section('content')
<style>
    :root {
        --primary: #318069;
        --primary-dark: #276854;
        --primary-light: #e8f5f0;
        --danger: #ef4444;
        --danger-dark: #dc2626;
        --danger-light: #fef2f2;
        --text-dark: #1f2937;
        --text-muted: #6b7280;
        --border: #e5e7eb;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    /* Header */
    .template-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
    }

    .header-title h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0 0 0.25rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .header-title h1 i {
        color: var(--primary);
        font-size: 1.5rem;
    }

    .header-title p {
        color: var(--text-muted);
        font-size: 0.85rem;
        margin: 0;
    }

    .header-stats {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-badge {
        background: var(--primary-light);
        padding: 0.35rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--primary);
    }

    .btn-primary-custom {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-primary-custom:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    /* Template Grid */
    .template-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    /* Template Card */
    .template-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }

    .template-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary);
    }

    /* Image Container */
    .template-image-container {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    }

    .template-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .template-card:hover .template-image {
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
        gap: 1rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .template-image-container:hover .image-overlay {
        opacity: 1;
    }

    .overlay-btn {
        padding: 0.5rem 1.25rem;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .overlay-preview {
        background: white;
        color: var(--primary);
    }

    .overlay-preview:hover {
        background: #f8fafc;
        transform: scale(1.05);
    }

    /* Badge */
    .template-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: var(--primary);
        color: white;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        box-shadow: var(--shadow-sm);
        z-index: 2;
    }

    /* Content */
    .template-content {
        padding: 1.25rem;
    }

    .template-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    .template-description {
        font-size: 0.8rem;
        color: var(--text-muted);
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Meta Info */
    .template-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding-top: 0.75rem;
        border-top: 1px solid var(--border);
        margin-bottom: 1rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    .meta-item i {
        color: var(--primary);
        font-size: 0.7rem;
    }

    /* Action Buttons */
    .template-actions {
        display: flex;
        gap: 0.75rem;
    }

    .action-btn {
        flex: 1;
        padding: 0.5rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 500;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        text-decoration: none;
    }

    .btn-edit {
        background: var(--primary-light);
        color: var(--primary);
    }

    .btn-edit:hover {
        background: var(--primary);
        color: white;
    }

    .btn-delete {
        background: var(--danger-light);
        color: var(--danger);
    }

    .btn-delete:hover {
        background: var(--danger);
        color: white;
    }

    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 16px;
        padding: 4rem 2rem;
        text-align: center;
        border: 1px solid var(--border);
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .empty-icon i {
        font-size: 2rem;
        color: var(--primary);
    }

    /* Delete Modal */
    .delete-modal {
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

    .delete-modal-content {
        background: white;
        border-radius: 16px;
        width: 100%;
        max-width: 400px;
        overflow: hidden;
        animation: modalSlideIn 0.3s ease-out;
    }

    .delete-modal-header {
        padding: 1.25rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .delete-modal-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--danger);
    }

    .delete-modal-body {
        padding: 1.25rem;
    }

    .delete-modal-footer {
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

    .btn-confirm-delete {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        background: var(--danger);
        color: white;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-confirm-delete:hover {
        background: var(--danger-dark);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .template-grid {
            grid-template-columns: 1fr;
        }
        
        .template-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .template-actions {
            flex-direction: column;
        }
    }

    /* Modal Animation */
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
</style>

<div class="">
    <!-- Header -->
    <div class="template-header">
        <div class="header-title">
            <h1>
                Template
            </h1>
            <p>Manage your website templates - preview, edit, or remove templates</p>
        </div>
        <div class="header-stats">
            <span class="stat-badge">
                <i class="fas fa-layer-group"></i> {{ $templates->count() }} Templates
            </span>
            <button type="button" class="btn-primary-custom" id="openCreateModalBtn">
                <i class="fas fa-plus"></i>
                Add Template
            </button>
        </div>
    </div>

    @if($templates->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-palette"></i>
            </div>
            <h4 class="fw-semibold text-gray-800 mb-2">No Templates Found</h4>
            <p class="text-muted mb-3">Get started by adding your first template</p>
            <button type="button" class="btn-primary-custom" id="openCreateModalBtnEmpty">
                <i class="fas fa-plus"></i> Add Template
            </button>
        </div>
    @else
        <div class="template-grid">
            @foreach($templates as $template)
                <div class="template-card">
                    <div class="template-image-container">
                        <img src="{{ url($template->image) }}"
                             alt="{{ $template->title }}"
                             class="template-image"
                             onerror="this.onerror=null; this.src='https://placehold.co/400x240/f3f4f6/64748B?text=No+Image'">
                        
                        <!-- Hover Overlay -->
                        <div class="image-overlay">
                            <a href="{{ $template->url }}" target="_blank" class="overlay-btn overlay-preview">
                                <i class="fas fa-eye"></i> Preview
                            </a>
                        </div>
                        
                        <span class="template-badge">
                            <i class="fas fa-crown"></i> Premium
                        </span>
                    </div>

                    <div class="template-content">
                        <h3 class="template-title">{{ $template->title }}</h3>
                        <p class="template-description">{{ Str::limit($template->value, 60) }}</p>
                        
                        <div class="template-meta">
                            
                            <div class="meta-item">
                                <i class="fas fa-code-branch"></i>
                                <span>v{{ rand(1, 3) }}.0</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-download"></i>
                                <span>{{ rand(50, 500) }} uses</span>
                            </div>
                        </div>
                        
                        <div class="template-actions">
                            <button type="button"
                                    class="action-btn btn-edit open-modal"
                                    data-id="{{ $template->id }}"
                                    data-title="{{ $template->title }}"
                                    data-value="{{ $template->value }}"
                                    data-image="{{ $template->image ? url($template->image) : '' }}"
                                    data-preview-url="{{ $template->url ?? '' }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="button"
                                    class="action-btn btn-delete open-delete-modal"
                                    data-id="{{ $template->id }}"
                                    data-title="{{ $template->title }}">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="delete-modal">
    <div class="delete-modal-content">
        <div class="delete-modal-header">
            <h3>
                <i class="fas fa-trash-alt"></i>
                Delete Template
            </h3>
            <button type="button" class="close-delete-modal" style="background: none; border: none; font-size: 1.25rem; cursor: pointer;">&times;</button>
        </div>
        <div class="delete-modal-body">
            <p>Are you sure you want to delete <strong id="deleteTemplateName"></strong>?</p>
            <p class="text-muted small mb-0">This action cannot be undone. The template will be permanently removed.</p>
        </div>
        <div class="delete-modal-footer">
            <button type="button" class="btn-cancel close-delete-modal">Cancel</button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-confirm-delete">Delete Template</button>
            </form>
        </div>
    </div>
</div>

<!-- Create Template Modal -->
<div id="createTemplateModal" class="image-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="modal-content" style="background: white; border-radius: 16px; width: 100%; max-width: 500px; max-height: 90vh; overflow: auto;">
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
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; border-radius: 6px; background: #318069; color: white; border: none; font-size: 0.8rem;">
                    <i class="fas fa-save me-1"></i> Create
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Template Modal -->
<div id="imageModal" class="image-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="modal-content" style="background: white; border-radius: 16px; width: 100%; max-width: 500px; max-height: 90vh; overflow: auto;">
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

                <!-- ADD THIS DESCRIPTION FIELD -->
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label" style="display: block; font-weight: 500; font-size: 0.8rem; margin-bottom: 0.4rem;">Template Description / Subtitle</label>
                    <textarea id="valueInput" name="value" class="form-input" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 0.85rem; min-height: 80px;" placeholder="Enter template description..."></textarea>
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
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; border-radius: 6px; background: #318069; color: white; border: none; font-size: 0.8rem;">
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
    const deleteModal = document.getElementById('deleteModal');
    const form = document.getElementById('imageForm');
    const createForm = document.getElementById('createTemplateForm');
    const deleteForm = document.getElementById('deleteForm');
    const openCreateModalBtn = document.getElementById('openCreateModalBtn');
    const openCreateModalBtnEmpty = document.getElementById('openCreateModalBtnEmpty');
    const deleteTemplateName = document.getElementById('deleteTemplateName');
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

    function openModal(modalElement) {
        modalElement.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalElement) {
        modalElement.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    if (openCreateModalBtn) openCreateModalBtn.addEventListener('click', () => openModal(createModal));
    if (openCreateModalBtnEmpty) openCreateModalBtnEmpty.addEventListener('click', () => openModal(createModal));

    // Open edit modal
document.querySelectorAll('.open-modal').forEach(button => {
    button.addEventListener('click', function() {
        const valueInput = document.getElementById('valueInput');
        
        titleInput.value = this.dataset.title || '';
        previewUrlInput.value = this.dataset.previewUrl || '';
        currentTemplateImage.src = this.dataset.image || '';
        
        // ADD THIS LINE - to populate description field
        if (valueInput) {
            valueInput.value = this.dataset.value || '';
        }
        
        form.action = `${updateRouteBase}/${this.dataset.id}`;
        
        fileInput.value = '';
        imagePreview.style.display = 'none';
        fileName.textContent = '';

        openModal(modal);
    });
});
    // Open delete modal
    document.querySelectorAll('.open-delete-modal').forEach(button => {
        button.addEventListener('click', function() {
            const templateId = this.dataset.id;
            const templateTitle = this.dataset.title;
            deleteTemplateName.textContent = templateTitle;
            deleteForm.action = `${updateRouteBase}/${templateId}`;
            openModal(deleteModal);
        });
    });

    // Close modals
    document.querySelectorAll('.close-modal, .close-create-modal, .close-delete-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            closeModal(modal);
            closeModal(createModal);
            closeModal(deleteModal);
        });
    });

    // File upload handling
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
        if (e.target === modal) closeModal(modal);
        if (e.target === createModal) closeModal(createModal);
        if (e.target === deleteModal) closeModal(deleteModal);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal(modal);
            closeModal(createModal);
            closeModal(deleteModal);
        }
    });
});
</script>
@endsection