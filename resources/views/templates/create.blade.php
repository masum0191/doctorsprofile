@extends('layouts.tenantadmin')
@section('title', 'Template Selection')

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

    /* Active Card Style */
    .template-card-compact.active-card {
        border: 2px solid var(--primary);
        box-shadow: 0 0 0 2px rgba(49, 128, 105, 0.2);
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

    .card-badge-compact.active-badge {
        background: var(--primary);
        right: auto;
        left: 0.75rem;
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

    .btn-select-theme:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
        transform: none;
    }

    .btn-active-theme {
        width: 100%;
        padding: 0.5rem;
        background: #e8f5f0;
        color: var(--primary);
        border: 1px solid var(--primary);
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: default;
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

    .theme-container {
        max-width: 1000px;
        margin: 0 auto;
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
        transition: all 0.2s ease;
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

    /* Alert Styles */
    .alert-custom {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-success-custom {
        background: #e8f5f0;
        color: var(--primary-dark);
        border-left: 4px solid var(--primary);
    }

    /* Pagination */
    .pagination-container {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }

    .pagination {
        display: flex;
        gap: 0.3rem;
        list-style: none;
        padding: 0;
    }

    .pagination li a, .pagination li span {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.8rem;
        color: var(--gray);
        text-decoration: none;
        border: 1px solid var(--border);
    }

    .pagination li.active span {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
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
            <i class="fas fa-palette"></i> Template Selection
        </h1>
        <p class="page-subtitle">Browse, preview, and select your website template</p>
    </div>

    {{-- STATUS MESSAGE --}}
    @if (session('status'))
        <div class="alert-custom alert-success-custom">
            <i class="fas fa-check-circle"></i>
            {{ session('status') }}
        </div>
    @endif

    @if($templates->isEmpty())
        <div class="empty-state-compact">
            <div class="empty-icon-compact">
                <i class="fas fa-palette"></i>
            </div>
            <h4 class="fw-semibold text-gray-800 mb-2">No Templates Available</h4>
            <p class="text-muted small mb-3">No templates are currently available for selection.</p>
        </div>
    @else
        <div class="template-grid-compact">
            @foreach($templates as $template)
                @php
                    $isActive = isset($templateValue) && $templateValue === $template->value;
                @endphp

                <div class="template-card-compact {{ $isActive ? 'active-card' : '' }}">
                    <div class="card-image-compact">
                        @if (!empty($template->image))
                            <img src="{{ url($template->image) }}" 
                                 alt="{{ $template->title }}"
                                 onerror="this.onerror=null; this.src='https://placehold.co/400x200/e8f5f0/318069?text=Template'">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height:100%;">
                                <i class="fas fa-image text-muted fa-2x"></i>
                            </div>
                        @endif
                        
                        <!-- Hover Overlay with Preview Button -->
                        <div class="image-overlay">
                            @if (!empty($template->image))
                                <a href="{{ url($template->image) }}" target="_blank" class="overlay-preview-btn">
                                    <i class="fas fa-eye"></i> Preview
                                </a>
                            @else
                                <span class="overlay-preview-btn" style="cursor: default;">
                                    <i class="fas fa-eye"></i> No Preview
                                </span>
                            @endif
                        </div>
                        
                        @if($isActive)
                            <span class="card-badge-compact active-badge">
                                <i class="fas fa-check-circle"></i> Active
                            </span>
                        @else
                            <span class="card-badge-compact">
                                <i class="fas fa-crown"></i> Premium
                            </span>
                        @endif
                    </div>
                    
                    <div class="card-content-compact">
                        <h4 class="card-title-compact">{{ $template->title }}</h4>
                        <p class="card-description-compact">{{ Str::limit($template->value ?? $template->title, 60) }}</p>
                        
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
                        
                        @if ($isActive)
                            <div class="btn-active-theme">
                                <i class="fas fa-check-circle"></i> Currently Active
                            </div>
                        @else
                            <button type="button"
                                    class="btn-select-theme open-select-modal"
                                    data-value="{{ $template->value }}"
                                    data-title="{{ $template->title }}">
                                <i class="fas fa-check-circle"></i> Select Theme
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        @if(method_exists($templates, 'links'))
            <div class="pagination-container">
                {{ $templates->links('pagination::bootstrap-4') }}
            </div>
        @endif
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
            <form id="activateForm" method="POST" action="{{ route('templates.activate') }}">
                @csrf
                <input type="hidden" name="template_value" id="selectedTemplateValue">
                <button type="submit" class="btn-confirm">
                    <i class="fas fa-check-circle"></i> Confirm Selection
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectionModal = document.getElementById('selectionModal');
    const selectedTemplateName = document.getElementById('selectedTemplateName');
    const selectedTemplateValue = document.getElementById('selectedTemplateValue');
    const activateForm = document.getElementById('activateForm');
    
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
            const templateValue = this.dataset.value;
            const templateTitle = this.dataset.title;
            
            selectedTemplateName.textContent = templateTitle;
            selectedTemplateValue.value = templateValue;
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
@endsection