@extends('layouts.supperadmin')
@section('title', $post->exists ? 'Edit Post' : 'New Post')

@section('content')
<style>
    /* Enhanced Blog Form Styles - Match Dashboard */
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.1);
        --primary-dark: #2a6d5a;
        --primary-soft: rgba(49, 128, 105, 0.05);
        --primary-hover: rgba(49, 128, 105, 0.15);
        --ai-blue: #3b82f6;
        --ai-light: rgba(59, 130, 246, 0.1);
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #0ea5e9;
    }

    /* Enhanced Header */
    .form-header {
        background: linear-gradient(135deg, rgba(49, 128, 105, 0.03), rgba(49, 128, 105, 0.08));
        border-radius: 16px;
        border: 1px solid rgba(49, 128, 105, 0.1);
        padding: 0.9rem 1.3rem;
        margin-bottom: 1.2rem;
        position: relative;
        overflow: hidden;
    }

    .form-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
    }

    .form-header h1 {
        font-weight: 700;
        font-size: 1.4rem;
    }

    .form-header p {
        color: #64748b;
        font-size: 1.1rem;
    }

    /* AI Badge */
    .ai-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        margin-left: 0.5rem;
    }

    /* Enhanced Cards */
    .form-card {
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        background: white;
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .form-card:hover {
        box-shadow: 0 8px 25px rgba(49, 128, 105, 0.1);
    }

    .card-header {
        background: var(--primary-soft);
        border-bottom: 1px solid var(--primary-light);
        font-weight: 500;
        padding: .9rem 1.4rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }

    .card-header h5 {
        font-size: 1.1rem;
        margin: 0;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-body {
        padding: 0 1rem 1rem;
    }

    /* AI Generation Card */
    .ai-generation-card {
        border: 1px solid rgba(59, 130, 246, 0.2);
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.03), rgba(59, 130, 246, 0.08));
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .ai-generation-card .card-header {
        background: rgba(59, 130, 246, 0.1);
        border-bottom: 1px solid rgba(59, 130, 246, 0.2);
    }

    /* Enhanced Form Controls */
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

    .form-control, .form-select {
        border: 1px solid rgba(49, 128, 105, 0.2);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    .form-control-lg {
        font-size: 1.25rem;
        font-weight: 600;
        padding: 1rem 1.25rem;
    }

    /* AI Action Buttons */
    .ai-action-btn {
        background: var(--ai-light);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: var(--ai-blue);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .ai-action-btn:hover {
        background: rgba(59, 130, 246, 0.2);
        border-color: var(--ai-blue);
        transform: translateY(-1px);
    }

    .ai-action-btn i {
        font-size: 0.9rem;
    }

    .ai-action-btn.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .ai-action-btn.disabled:hover {
        transform: none;
        background: var(--ai-light);
    }

    /* AI Generation Controls */
    .ai-controls {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-top: 0.75rem;
    }

    .ai-prompt-input {
        flex: 1;
        min-width: 200px;
        border: 1px solid rgba(59, 130, 246, 0.3);
        background: rgba(59, 130, 246, 0.05);
    }

    .ai-prompt-input:focus {
        border-color: var(--ai-blue);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background: white;
    }

    /* Loading States */
    .ai-loading {
        position: relative;
        color: transparent !important;
    }

    .ai-loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid var(--ai-blue);
        border-radius: 50%;
        border-top-color: transparent;
        animation: ai-spin 0.8s linear infinite;
    }

    @keyframes ai-spin {
        to { transform: rotate(360deg); }
    }

    /* AI Suggestions */
    .ai-suggestions {
        margin-top: 1rem;
        padding: 1rem;
        background: rgba(59, 130, 246, 0.05);
        border-radius: 8px;
        border: 1px dashed rgba(59, 130, 246, 0.3);
    }

    .ai-suggestion-title {
        font-weight: 600;
        color: var(--ai-blue);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .ai-suggestion-item {
        background: white;
        border: 1px solid rgba(59, 130, 246, 0.1);
        border-radius: 6px;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .ai-suggestion-item:hover {
        background: var(--ai-light);
        border-color: var(--ai-blue);
        transform: translateX(3px);
    }

    .ai-suggestion-item:last-child {
        margin-bottom: 0;
    }

    /* Image Generation Options */
    .image-style-options {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .style-option {
        padding: 0.75rem;
        border: 2px solid rgba(59, 130, 246, 0.1);
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: white;
    }

    .style-option:hover {
        border-color: var(--ai-blue);
        background: var(--ai-light);
    }

    .style-option.active {
        border-color: var(--ai-blue);
        background: var(--ai-light);
        color: var(--ai-blue);
    }

    .style-option i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    /* Image Generation Preview */
    .ai-image-preview {
        margin-top: 1rem;
        padding: 1rem;
        background: rgba(59, 130, 246, 0.05);
        border-radius: 8px;
        border: 1px dashed rgba(59, 130, 246, 0.3);
    }

    .ai-generated-images {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .ai-generated-image {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s ease;
    }

    .ai-generated-image:hover {
        transform: scale(1.05);
        border-color: var(--ai-blue);
    }

    .ai-generated-image img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .ai-image-select {
        position: absolute;
        bottom: 0.5rem;
        right: 0.5rem;
        background: var(--ai-blue);
        color: white;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .ai-generated-image:hover .ai-image-select {
        opacity: 1;
    }

    .image-preview-container {
        width: 100%;
        aspect-ratio: 1200 / 630;
        min-height: 220px;
        border: 1px dashed rgba(49, 128, 105, 0.35);
        border-radius: 12px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        cursor: pointer;
        position: relative;
        text-align: center;
        padding: 1rem;
    }

    .image-placeholder {
        display: block;
        font-size: 2rem;
        color: rgba(49, 128, 105, 0.6);
        margin-bottom: 0.75rem;
    }

    .image-preview {
        display: none;
        width: 100%;
        height: 100%;
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        object-position: center;
        border-radius: 8px;
        background: white;
    }

    /* AI Content Generation */
    .ai-content-options {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }

    .content-option-btn {
        padding: 0.5rem 1rem;
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.2);
        border-radius: 20px;
        color: var(--ai-blue);
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .content-option-btn:hover {
        background: rgba(59, 130, 246, 0.2);
        transform: translateY(-1px);
    }

    .content-option-btn.active {
        background: var(--ai-blue);
        color: white;
        border-color: var(--ai-blue);
    }

    /* AI Loading Overlay */
    .ai-loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(5px);
    }

    .ai-loading-spinner {
        width: 50px;
        height: 50px;
        border: 3px solid var(--ai-light);
        border-radius: 50%;
        border-top-color: var(--ai-blue);
        animation: ai-spin 1s linear infinite;
        margin-bottom: 1rem;
    }

    .ai-loading-text {
        color: var(--ai-blue);
        font-weight: 500;
        font-size: 1.1rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .ai-controls {
            flex-direction: column;
        }

        .ai-prompt-input {
            min-width: 100%;
        }

        .image-style-options {
            grid-template-columns: repeat(2, 1fr);
        }

        .ai-generated-images {
            grid-template-columns: repeat(2, 1fr);
        }

        .image-preview-container {
            min-height: 180px;
        }
    }
</style>

<div class="">
    <!-- Enhanced Header Section -->
    <div class="form-header">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1>{{ $post->exists ? 'Edit Post' : 'Create New Post' }}</h1>
                <p class="mb-0">Create and manage your health articles with AI-powered assistance</p>
            </div>
            <div class="col-lg-5 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
                @if($post->exists && $post->is_published)
                <a href="{{ url('singles-article', [$post->slug]) }}"
                   target="_blank"
                   class="btn btn-outline-success">
                    <i class="fas fa-external-link-alt me-2"></i>View Live
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Validation Summary -->
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <div class="row align-items-center">
            <div class="col-1 text-center">
                <i class="fas fa-exclamation-triangle fs-4"></i>
            </div>
            <div class="col-10">
                <h6 class="fw-semibold mb-2">Please fix the following errors:</h6>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $e)
                    <li class="small">{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="col-1 text-end">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- AI Generation Modal -->
    <div class="modal fade" id="aiModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-robot me-2"></i>
                        <span id="aiModalTitle">AI Assistant</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="aiModalContent">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST"
          action="{{ $post->exists ? route('posts.update',$post) : route('posts.store') }}"
          class="needs-validation" novalidate enctype="multipart/form-data" id="postForm">
        @csrf
        @if($post->exists) @method('PUT') @endif

        <div class="row g-4">
            <!-- Left Column - Main Content -->
            <div class="col-lg-8">
                <!-- AI Generation Card -->
                <div class="ai-generation-card">
                    <div class="card-header">
                        <h5>
                            <i class="fas fa-robot text-ai-blue"></i>
                            AI Content Assistant
                            <span class="ai-badge">BETA</span>
                        </h5>
                        <button type="button" class="ai-action-btn" onclick="openAIAssistant()">
                            <i class="fas fa-magic"></i>
                            Open AI Assistant
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <button type="button" class="ai-action-btn w-100" onclick="generateExcerpt()" id="generateExcerptBtn">
                                    <i class="fas fa-align-left"></i>
                                    Generate Description
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="ai-action-btn w-100" onclick="generateImages()" id="generateImagesBtn">
                                    <i class="fas fa-image"></i>
                                    Generate Images
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="ai-action-btn w-100" onclick="generateContent()" id="generateContentBtn">
                                    <i class="fas fa-file-alt"></i>
                                    Generate Content
                                </button>
                            </div>
                        </div>

                        <!-- AI Suggestions for Excerpt -->
                        <div class="ai-suggestions" id="excerptSuggestions" style="display: none;">
                            <div class="ai-suggestion-title">
                                <i class="fas fa-lightbulb"></i>
                                Suggested Descriptions
                            </div>
                            <div id="excerptSuggestionList"></div>
                        </div>
                    </div>
                </div>

                <!-- Basic Information Card -->
                <div class="form-card">
                    <div class="card-header">
                        <i class="fas fa-info-circle"></i>
                        <h5>Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <!-- Title with AI Enhancement -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label">
                                        Post Title <span class="required">*</span>
                                    </label>
                                    <button type="button" class="ai-action-btn" onclick="suggestTitles()" id="suggestTitlesBtn">
                                        <i class="fas fa-lightbulb"></i>
                                        Suggest Titles
                                    </button>
                                </div>
                                <input name="title"
                                       type="text"
                                       class="form-control form-control-lg @error('title') is-invalid @enderror"
                                       value="{{ old('title', $post->title) }}"
                                       required
                                       id="titleInput"
                                       placeholder="Enter a compelling title for your health article">
                                @error('title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Category, Type, and Read Time -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">
                                    Category
                                    <span class="required">*</span>
                                </label>
                                <select name="category_id" class="form-select" required id="categorySelect">
                                    <option value="">Select Category</option>
                                    @foreach($category as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category', $post->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Type
                                    <span class="required">*</span>
                                </label>
                                <select name="type_id" class="form-select" required id="typeSelect">
                                    <option value="">Select Type</option>
                                    @foreach($type as $typ)
                                        <option value="{{ $typ->id }}" {{ old('type', $post->type_id) == $typ->id ? 'selected' : '' }}>{{ $typ->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Read Time (minutes)
                                </label>
                                <input type="number"
                                       min="1"
                                       max="120"
                                       name="read_minutes"
                                       value="{{ old('read_minutes', $post->read_minutes ?? 5) }}"
                                       class="form-control"
                                       placeholder="5">
                            </div>
                        </div>

                        <!-- Excerpt with AI Generation -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label">
                                        Short Description (Excerpt)
                                    </label>
                                    <div class="ai-controls">
                                        <input type="text"
                                               class="form-control ai-prompt-input"
                                               id="excerptPrompt"
                                               placeholder="Enter keywords or topic...">
                                        <button type="button" class="ai-action-btn" onclick="generateExcerptWithPrompt()">
                                            <i class="fas fa-bolt"></i>
                                            Generate
                                        </button>
                                    </div>
                                </div>
                                <textarea name="excerpt"
                                          rows="3"
                                          class="form-control"
                                          id="excerptInput"
                                          placeholder="Brief summary of your article (appears in post listings)">{{ old('excerpt', $post->excerpt) }}</textarea>
                                <div class="character-counter" id="excerptCounter">
                                    <span class="text-muted">Recommended: 150-160 characters</span>
                                    <span class="count">0/160</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media Content Card -->
                <div class="form-card">
                    <div class="card-header">
                        <i class="fas fa-images"></i>
                        <h5>Media Content</h5>
                    </div>
                    <div class="card-body">
                        <!-- Cover Image with AI Generation -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label">
                                        Cover Image
                                    </label>
                                    <button type="button" class="ai-action-btn" onclick="openImageGenerator()">
                                        <i class="fas fa-robot"></i>
                                        AI Generate Image
                                    </button>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-link"></i>
                                            </span>
                                            <input name="cover_image"
                                                   type="url"
                                                   class="form-control"
                                                   id="coverInput"
                                                   value="{{ old('cover_image', $post->cover_image) }}"
                                                   placeholder="https://example.com/image.jpg">
                                            <button class="btn btn-outline-secondary" type="button" onclick="clearCoverImage()">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label d-md-none">Or Upload Image</label>
                                        <input type="file"
                                               name="cover_image_upload"
                                               class="form-control"
                                               id="coverUpload"
                                               accept="image/*">
                                    </div>
                                </div>

                                <!-- AI Image Generation Options -->
                                <div class="ai-image-preview" id="aiImageOptions" style="display: none;">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="ai-controls">
                                                <input type="text"
                                                       class="form-control ai-prompt-input"
                                                       id="imagePrompt"
                                                       placeholder="Describe the image you want to generate...">
                                                <button type="button" class="ai-action-btn" onclick="generateAIImage()">
                                                    <i class="fas fa-bolt"></i>
                                                    Generate Image
                                                </button>
                                            </div>

                                            <div class="image-style-options mt-3">
                                                <div class="style-option" data-style="photorealistic">
                                                    <i class="fas fa-camera"></i>
                                                    <span>Photo</span>
                                                </div>
                                                <div class="style-option" data-style="digital-art">
                                                    <i class="fas fa-paint-brush"></i>
                                                    <span>Art</span>
                                                </div>
                                                <div class="style-option" data-style="minimal">
                                                    <i class="fas fa-border-none"></i>
                                                    <span>Minimal</span>
                                                </div>
                                                <div class="style-option" data-style="3d-render">
                                                    <i class="fas fa-cube"></i>
                                                    <span>3D</span>
                                                </div>
                                            </div>

                                            <div class="ai-generated-images" id="aiGeneratedImages"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preview -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="image-preview-container" onclick="document.getElementById('coverUpload').click()">
                                            <div id="coverPreviewPlaceholder" style="{{ old('cover_image', $post->cover_image) ? 'display: none;' : '' }}">
                                                <i class="fas fa-image image-placeholder"></i>
                                                <p class="text-muted mb-2">Click to upload or paste image URL</p>
                                                <small class="text-muted">Recommended: 1200x630px • Max: 2MB</small>
                                            </div>
                                            <img id="coverPreview"
                                                 src="{{ old('cover_image', $post->cover_image) ?: '#' }}"
                                                 alt="Cover preview"
                                                 class="image-preview"
                                                 style="{{ old('cover_image', $post->cover_image) ? 'display: block;' : '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Article Content Card -->
                <div class="form-card">
                    <div class="card-header">
                        <i class="fas fa-edit"></i>
                        <h5>Article Content</h5>
                        <div class="ai-content-options">
                            <button type="button" class="content-option-btn" onclick="generateSection('introduction')">
                                Intro
                            </button>
                            <button type="button" class="content-option-btn" onclick="generateSection('body')">
                                Body
                            </button>
                            <button type="button" class="content-option-btn" onclick="generateSection('conclusion')">
                                Conclusion
                            </button>
                            <button type="button" class="content-option-btn" onclick="generateSection('key_points')">
                                Key Points
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="ai-controls mb-3">
                                    <input type="text"
                                           class="form-control ai-prompt-input"
                                           id="contentPrompt"
                                           placeholder="What would you like to write about?">
                                    <button type="button" class="ai-action-btn" onclick="generateContentWithPrompt()">
                                        <i class="fas fa-bolt"></i>
                                        AI Write
                                    </button>
                                </div>

                                <label class="form-label">
                                    Content <span class="required">*</span>
                                </label>
                                <textarea name="body"
                                          rows="15"
                                          class="summernote form-control @error('body') is-invalid @enderror"
                                          id="contentEditor"
                                          placeholder="Write your health article content here...">{{ old('body', $post->body) }}</textarea>
                                @error('body')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Meta & Settings -->
            <div class="col-lg-4">
                <!-- SEO Settings Card -->
                <div class="form-card">
                    <div class="card-header">
                        <i class="fas fa-search"></i>
                        <h5>SEO Settings</h5>
                        <button type="button" class="ai-action-btn" onclick="generateSEO()">
                            <i class="fas fa-robot"></i>
                            AI Optimize
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label">Meta Title</label>
                                <input name="meta_title"
                                       type="text"
                                       class="form-control"
                                       value="{{ old('meta_title', $post->meta_title) }}"
                                       id="metaTitleInput"
                                       placeholder="Custom meta title for SEO">
                                <div class="character-counter" id="metaTitleCounter">
                                    <span class="text-muted">Recommended: 50-60 characters</span>
                                    <span class="count">0/60</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description"
                                          rows="4"
                                          class="form-control"
                                          id="metaDescriptionInput"
                                          placeholder="Custom meta description for SEO">{{ old('meta_description', $post->meta_description) }}</textarea>
                                <div class="character-counter" id="metaDescriptionCounter">
                                    <span class="text-muted">Recommended: 150-160 characters</span>
                                    <span class="count">0/160</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label class="form-label">Meta Keywords</label>
                                <div class="ai-controls">
                                    <input type="text"
                                           class="form-control ai-prompt-input"
                                           id="keywordsPrompt"
                                           placeholder="Topic or keywords...">
                                    <button type="button" class="ai-action-btn" onclick="generateKeywords()">
                                        <i class="fas fa-bolt"></i>
                                        Suggest
                                    </button>
                                </div>

                                <!-- Keywords Display -->
                                <div class="row mb-3 mt-3" id="keywordsContainer">
                                    @php
                                        $keywords = old('meta_keywords', $post->meta_keywords ?? []);
                                        if (is_string($keywords)) {
                                            $keywords = explode(',', $keywords);
                                        }
                                    @endphp

                                    @foreach($keywords as $keyword)
                                        @if(trim($keyword))
                                            <div class="col-auto mb-2">
                                                <span class="badge bg-primary bg-opacity-10 text-primary d-flex align-items-center">
                                                    {{ trim($keyword) }}
                                                    <input type="hidden" name="meta_keywords[]" value="{{ trim($keyword) }}">
                                                    <button type="button" class="btn-close btn-close-sm ms-2" onclick="removeKeyword(this)"></button>
                                                </span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Publishing Settings Card -->
                <div class="form-card">
                    <div class="card-header">
                        <i class="fas fa-cog"></i>
                        <h5>Publishing Settings</h5>
                    </div>
                    <div class="card-body">
                        <!-- Slug -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label">
                                    URL Slug
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-link"></i>
                                    </span>
                                    <input name="slug"
                                           type="text"
                                           class="form-control"
                                           value="{{ old('slug', $post->slug) }}"
                                           id="slugInput"
                                           placeholder="auto-generated-from-title">
                                </div>
                            </div>
                        </div>

                        <!-- Status & Publish Date -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="is_published" class="form-select">
                                    <option value="0" {{ !old('is_published', $post->is_published) ? 'selected' : '' }}>Draft</option>
                                    <option value="1" {{ old('is_published', $post->is_published) ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Publish Date</label>
                                <input type="datetime-local"
                                       name="published_at"
                                       class="form-control"
                                       value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
                            </div>
                        </div>

                        <!-- Order & Featured -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Order</label>
                                <input type="number"
                                       name="order_column"
                                       class="form-control"
                                       value="{{ old('order_column', $post->order_column ?? 0) }}"
                                       placeholder="0">
                            </div>

                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check form-switch w-100">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="is_featured"
                                           value="1"
                                           id="featuredSwitch"
                                           {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="featuredSwitch">
                                        Featured Post
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12">
                                <label class="form-label" for="relatedPostsSelect">Related Posts</label>
                                <select name="related_post_ids[]" id="relatedPostsSelect" class="form-select @error('related_post_ids') is-invalid @enderror @error('related_post_ids.*') is-invalid @enderror" multiple size="6">
                                    @php
                                        $selectedRelatedPosts = collect(old('related_post_ids', $post->related_post_ids ?? []))
                                            ->map(fn ($id) => (int) $id)
                                            ->all();
                                    @endphp
                                    @foreach(($relatedPosts ?? collect()) as $relatedPost)
                                        <option value="{{ $relatedPost->id }}" {{ in_array($relatedPost->id, $selectedRelatedPosts, true) ? 'selected' : '' }}>
                                            {{ $relatedPost->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold Ctrl or Cmd to select multiple related articles for the frontend.</small>
                                @error('related_post_ids')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('related_post_ids.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions Card -->
                <div class="form-card">
                    <div class="p-3">
                        <div class="row g-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-save me-2"></i>
                                    {{ $post->exists ? 'Update Post' : 'Create Post' }}
                                </button>
                            </div>

                            <div class="col-6">
                                <button type="submit" name="draft" value="1" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-file-alt me-2"></i>
                                    Save Draft
                                </button>
                            </div>

                            <div class="col-6">
                                <button type="submit" name="publish" value="1" class="btn btn-success w-100">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Publish Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- AI Loading Overlay -->
<div class="ai-loading-overlay" id="aiLoadingOverlay" style="display: none;">
    <div class="ai-loading-spinner"></div>
    <div class="ai-loading-text" id="aiLoadingText">Generating content...</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));

    // Slug generation
    const titleInput = document.getElementById('titleInput');
    const slugInput = document.getElementById('slugInput');

    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    }

    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            if (!slugInput.dataset.touched || slugInput.dataset.touched === 'false') {
                slugInput.value = slugify(this.value);
            }
        });

        slugInput.addEventListener('input', function() {
            this.dataset.touched = 'true';
        });

        slugInput.addEventListener('blur', function() {
            this.value = slugify(this.value);
        });
    }

    // Character counters
    function setupCharacterCounter(textarea, counter, maxLength, warningLength = null) {
        function updateCounter() {
            const length = textarea.value.length;
            counter.querySelector('.count').textContent = `${length}/${maxLength}`;

            counter.classList.remove('warning', 'danger');
            if (warningLength && length > warningLength) {
                counter.classList.add('warning');
            }
            if (length > maxLength) {
                counter.classList.add('danger');
            }
        }

        textarea.addEventListener('input', updateCounter);
        updateCounter();
    }

    // Set up character counters
    const excerptInput = document.getElementById('excerptInput');
    const excerptCounter = document.getElementById('excerptCounter');
    if (excerptInput && excerptCounter) {
        setupCharacterCounter(excerptInput, excerptCounter, 160, 150);
    }

    const metaTitleInput = document.getElementById('metaTitleInput');
    const metaTitleCounter = document.getElementById('metaTitleCounter');
    if (metaTitleInput && metaTitleCounter) {
        setupCharacterCounter(metaTitleInput, metaTitleCounter, 60, 50);
    }

    const metaDescriptionInput = document.getElementById('metaDescriptionInput');
    const metaDescriptionCounter = document.getElementById('metaDescriptionCounter');
    if (metaDescriptionInput && metaDescriptionCounter) {
        setupCharacterCounter(metaDescriptionInput, metaDescriptionCounter, 160, 150);
    }

    // Cover image preview
    const coverInput = document.getElementById('coverInput');
    const coverUpload = document.getElementById('coverUpload');
    const coverPreview = document.getElementById('coverPreview');
    const coverPreviewPlaceholder = document.getElementById('coverPreviewPlaceholder');

    function updateCoverPreview(src) {
        if (src) {
            coverPreview.src = src;
            coverPreview.style.display = 'block';
            coverPreviewPlaceholder.style.display = 'none';

            const testImage = new Image();
            testImage.onload = function() {
                coverPreview.src = src;
            };
            testImage.onerror = function() {
                showToast('Unable to load image from URL', 'warning');
            };
            testImage.src = src;
        } else {
            coverPreview.style.display = 'none';
            coverPreviewPlaceholder.style.display = 'block';
        }
    }

    if (coverInput) {
        coverInput.addEventListener('input', function() {
            updateCoverPreview(this.value.trim());
        });

        updateCoverPreview(coverInput.value.trim());
    }

    if (coverUpload) {
        coverUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    showToast('File size must be less than 2MB', 'warning');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    coverPreview.src = e.target.result;
                    coverPreview.style.display = 'block';
                    coverPreviewPlaceholder.style.display = 'none';
                    coverInput.value = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Clear cover image
    window.clearCoverImage = function() {
        coverInput.value = '';
        coverUpload.value = '';
        coverPreview.style.display = 'none';
        coverPreviewPlaceholder.style.display = 'block';
    };

    // Keywords management
    const keywordInput = document.getElementById('keywordInput');
    const keywordsContainer = document.getElementById('keywordsContainer');

    window.removeKeyword = function(button) {
        const colDiv = button.closest('.col-auto');
        colDiv.remove();
        showToast('Keyword removed', 'success');
    };

    // Style options selection
    document.querySelectorAll('.style-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.style-option').forEach(opt => {
                opt.classList.remove('active');
            });
            this.classList.add('active');
        });
    });

    // Content option buttons
    document.querySelectorAll('.content-option-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.content-option-btn').forEach(b => {
                b.classList.remove('active');
            });
            this.classList.add('active');
        });
    });

    // Form validation
    const form = document.getElementById('postForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();

                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalid.focus();
                }

                showToast('Please fill in all required fields', 'danger');
            }
            form.classList.add('was-validated');
        });
    }
});

// Toast notification
function showToast(message, type = 'info') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: type === 'success' ? '#10b981' :
                   type === 'warning' ? '#f59e0b' :
                   type === 'danger' ? '#ef4444' : '#0ea5e9',
        color: 'white',
        iconColor: 'white'
    });

    Toast.fire({
        icon: type,
        title: message
    });
}

// AI Functions
let currentImageStyle = 'photorealistic';

function openAIAssistant() {
    const aiModal = new bootstrap.Modal(document.getElementById('aiModal'));
    const aiModalTitle = document.getElementById('aiModalTitle');
    const aiModalContent = document.getElementById('aiModalContent');

    aiModalTitle.textContent = 'AI Content Assistant';
    aiModalContent.innerHTML = `
        <div class="text-center py-4">
            <i class="fas fa-robot text-ai-blue fs-1 mb-3"></i>
            <h4 class="mb-3">How can I help you today?</h4>
            <p class="text-muted mb-4">Select an AI-powered feature to enhance your article</p>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card h-100 text-center p-3 border-ai-blue">
                        <i class="fas fa-align-left text-ai-blue fs-3 mb-2"></i>
                        <h5>Generate Description</h5>
                        <p class="small text-muted">AI will create engaging excerpts based on your title</p>
                        <button class="btn btn-ai w-100" onclick="generateExcerpt()">
                            Generate
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 text-center p-3 border-ai-blue">
                        <i class="fas fa-image text-ai-blue fs-3 mb-2"></i>
                        <h5>Generate Images</h5>
                        <p class="small text-muted">Create custom images for your article cover</p>
                        <button class="btn btn-ai w-100" onclick="generateImages()">
                            Generate
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 text-center p-3 border-ai-blue">
                        <i class="fas fa-file-alt text-ai-blue fs-3 mb-2"></i>
                        <h5>Generate Content</h5>
                        <p class="small text-muted">Write article sections with AI assistance</p>
                        <button class="btn btn-ai w-100" onclick="generateContent()">
                            Generate
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 text-center p-3 border-ai-blue">
                        <i class="fas fa-search text-ai-blue fs-3 mb-2"></i>
                        <h5>SEO Optimization</h5>
                        <p class="small text-muted">Optimize meta tags and keywords for SEO</p>
                        <button class="btn btn-ai w-100" onclick="generateSEO()">
                            Optimize
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    aiModal.show();
}

function showLoading(message = 'Generating content...') {
    const overlay = document.getElementById('aiLoadingOverlay');
    const text = document.getElementById('aiLoadingText');
    text.textContent = message;
    overlay.style.display = 'flex';
}

function hideLoading() {
    const overlay = document.getElementById('aiLoadingOverlay');
    overlay.style.display = 'none';
}

function generateExcerpt() {
    const title = document.getElementById('titleInput').value;
    const category = document.getElementById('categorySelect').value;

    if (!title) {
        showToast('Please enter a title first', 'warning');
        return;
    }

    showLoading('Generating description...');

    // Simulate API call
    setTimeout(() => {
        const excerpts = [
            `Explore the latest insights on ${title}. This comprehensive guide covers essential information for better health management.`,
            `Discover expert advice about ${title}. Learn practical tips and strategies for maintaining optimal health and wellness.`,
            `${title}: A detailed overview of symptoms, prevention, and treatment options for better healthcare decision-making.`,
            `Get the facts about ${title}. This article provides evidence-based information to help you make informed health choices.`,
            `Understanding ${title}: Learn about causes, risk factors, and effective management strategies for improved health outcomes.`
        ];

        const excerptSuggestions = document.getElementById('excerptSuggestions');
        const excerptSuggestionList = document.getElementById('excerptSuggestionList');

        excerptSuggestionList.innerHTML = excerpts.map((excerpt, index) => `
            <div class="ai-suggestion-item" onclick="useExcerpt('${excerpt.replace(/'/g, "\\'")}')">
                ${excerpt}
            </div>
        `).join('');

        excerptSuggestions.style.display = 'block';
        hideLoading();
        showToast('Description suggestions generated!', 'success');
    }, 1500);
}

function generateExcerptWithPrompt() {
    const prompt = document.getElementById('excerptPrompt').value;
    const title = document.getElementById('titleInput').value;

    if (!prompt) {
        generateExcerpt();
        return;
    }

    showLoading('Generating description with your prompt...');

    setTimeout(() => {
        const excerpt = `This article explores ${prompt} in the context of ${title}. Learn essential information and practical guidance for effective health management.`;
        document.getElementById('excerptInput').value = excerpt;
        document.getElementById('excerptPrompt').value = '';

        // Update character counter
        const event = new Event('input');
        document.getElementById('excerptInput').dispatchEvent(event);

        hideLoading();
        showToast('Description generated successfully!', 'success');
    }, 1500);
}

function useExcerpt(excerpt) {
    document.getElementById('excerptInput').value = excerpt;
    const event = new Event('input');
    document.getElementById('excerptInput').dispatchEvent(event);
    showToast('Description applied!', 'success');
}

function openImageGenerator() {
    const aiImageOptions = document.getElementById('aiImageOptions');
    aiImageOptions.style.display = aiImageOptions.style.display === 'none' ? 'block' : 'none';
}

function generateAIImage() {
    const prompt = document.getElementById('imagePrompt').value;
    const title = document.getElementById('titleInput').value;
    const category = document.getElementById('categorySelect').value;

    if (!prompt) {
        showToast('Please describe the image you want to generate', 'warning');
        return;
    }

    showLoading('Generating images...');

    setTimeout(() => {
        const generatedImages = document.getElementById('aiGeneratedImages');
        const images = [];

        // Generate 4 sample images (in real implementation, these would come from AI API)
        for (let i = 1; i <= 4; i++) {
            images.push({
                url: `https://dummyimage.com/400x300/318069/ffffff&text=${encodeURIComponent(prompt.substring(0,20))}+${i}`
,
                prompt: prompt
            });
        }

        generatedImages.innerHTML = images.map((image, index) => `
            <div class="ai-generated-image" onclick="selectGeneratedImage('${image.url}')">
                <img src="${image.url}" alt="Generated image ${index + 1}">
                <button class="ai-image-select">
                    <i class="fas fa-check"></i>
                </button>
            </div>
        `).join('');

        hideLoading();
        showToast('Images generated successfully!', 'success');
    }, 2000);
}

function selectGeneratedImage(url) {
    document.getElementById('coverInput').value = url;
    updateCoverPreview(url);
    showToast('Image selected for cover!', 'success');
}

function generateContent() {
    const title = document.getElementById('titleInput').value;
    const category = document.getElementById('categorySelect').value;

    if (!title) {
        showToast('Please enter a title first', 'warning');
        return;
    }

    showLoading('Generating article content...');

    setTimeout(() => {
        const content = `
<h2>Introduction to ${title}</h2>
<p>${title} is an important health topic that requires careful consideration. Understanding the key aspects can help individuals make informed decisions about their health and wellness.</p>

<h3>Key Benefits and Features</h3>
<ul>
<li>Comprehensive information about ${title}</li>
<li>Evidence-based recommendations</li>
<li>Practical implementation strategies</li>
<li>Expert insights and tips</li>
</ul>

<h3>Important Considerations</h3>
<p>When exploring ${title}, it's essential to consider individual factors and consult with healthcare professionals for personalized advice.</p>

<h2>Conclusion</h2>
<p>${title} represents a significant aspect of health management. By staying informed and taking appropriate actions, individuals can achieve better health outcomes.</p>
        `;

        // Insert into Summernote editor
        if (typeof $('#contentEditor').summernote === 'function') {
            $('#contentEditor').summernote('pasteHTML', content);
        } else {
            document.getElementById('contentEditor').value = content;
        }

        hideLoading();
        showToast('Content generated successfully!', 'success');
    }, 2000);
}

function generateContentWithPrompt() {
    const prompt = document.getElementById('contentPrompt').value;

    if (!prompt) {
        generateContent();
        return;
    }

    showLoading('Writing content based on your prompt...');

    setTimeout(() => {
        const content = `
<h2>${prompt}: An Overview</h2>
<p>${prompt} is a crucial aspect of health management that deserves attention. This section explores the key concepts and practical applications.</p>

<h3>Understanding the Basics</h3>
<p>The fundamental principles of ${prompt} involve several key components that work together to support overall health and wellness.</p>

<h3>Practical Applications</h3>
<ul>
<li>Daily implementation strategies</li>
<li>Common challenges and solutions</li>
<li>Long-term benefits and outcomes</li>
<li>Expert recommendations</li>
</ul>

<h3>Key Takeaways</h3>
<p>${prompt} offers significant benefits when properly understood and implemented. Regular practice and attention to detail can lead to improved health outcomes.</p>
        `;

        if (typeof $('#contentEditor').summernote === 'function') {
            $('#contentEditor').summernote('pasteHTML', content);
        } else {
            document.getElementById('contentEditor').value += '\n\n' + content;
        }

        document.getElementById('contentPrompt').value = '';
        hideLoading();
        showToast('Content added successfully!', 'success');
    }, 2000);
}

function generateSection(section) {
    const title = document.getElementById('titleInput').value;

    if (!title) {
        showToast('Please enter a title first', 'warning');
        return;
    }

    showLoading(`Generating ${section} section...`);

    setTimeout(() => {
        let content = '';

        switch(section) {
            case 'introduction':
                content = `<h2>Introduction to ${title}</h2>
<p>Welcome to our comprehensive guide on ${title}. This article aims to provide you with valuable insights and practical information about this important health topic. Whether you're looking to enhance your understanding or seeking practical advice, this guide covers essential aspects to help you make informed decisions.</p>`;
                break;

            case 'body':
                content = `<h2>Detailed Analysis of ${title}</h2>
<h3>Key Components</h3>
<p>The main elements of ${title} include several interconnected factors that contribute to overall effectiveness. Understanding these components is essential for proper implementation.</p>

<h3>Implementation Strategies</h3>
<ul>
<li>Step-by-step approach to ${title}</li>
<li>Common practices and techniques</li>
<li>Timing and frequency considerations</li>
<li>Monitoring and adjustment methods</li>
</ul>`;
                break;

            case 'conclusion':
                content = `<h2>Conclusion</h2>
<p>In summary, ${title} represents an important aspect of health management that requires careful attention and proper implementation. By following the guidelines outlined in this article and consulting with healthcare professionals when needed, individuals can achieve better health outcomes and improved quality of life.</p>

<p>Remember that consistency and proper technique are key to success with ${title}. Stay informed, stay proactive, and prioritize your health and wellness.</p>`;
                break;

            case 'key_points':
                content = `<h2>Key Points Summary</h2>
<div class="alert alert-info">
<h4>Essential Takeaways:</h4>
<ul>
<li>${title} requires proper understanding and implementation</li>
<li>Consistency is crucial for achieving desired results</li>
<li>Individual factors may influence outcomes</li>
<li>Professional guidance is recommended when needed</li>
<li>Regular monitoring helps track progress</li>
</ul>
</div>`;
                break;
        }

        if (typeof $('#contentEditor').summernote === 'function') {
            $('#contentEditor').summernote('pasteHTML', content);
        } else {
            document.getElementById('contentEditor').value += '\n\n' + content;
        }

        hideLoading();
        showToast(`${section.charAt(0).toUpperCase() + section.slice(1)} section added!`, 'success');
    }, 1500);
}

function generateSEO() {
    const title = document.getElementById('titleInput').value;
    const category = document.getElementById('categorySelect').value;

    if (!title) {
        showToast('Please enter a title first', 'warning');
        return;
    }

    showLoading('Optimizing SEO...');

    setTimeout(() => {
        // Generate meta title
        const metaTitle = `${title} | ${category} Health Guide | Expert Advice`;
        document.getElementById('metaTitleInput').value = metaTitle;

        // Generate meta description
        const metaDescription = `Learn everything about ${title}. Get expert advice, practical tips, and comprehensive information about ${category} health management. Read our complete guide now.`;
        document.getElementById('metaDescriptionInput').value = metaDescription;

        // Generate keywords
        const keywords = [
            title.toLowerCase(),
            category.toLowerCase(),
            'health guide',
            'expert advice',
            'medical information',
            'wellness tips',
            'healthcare',
            'prevention',
            'treatment',
            'symptoms'
        ];

        const keywordsContainer = document.getElementById('keywordsContainer');
        keywordsContainer.innerHTML = keywords.map(keyword => `
            <div class="col-auto mb-2">
                <span class="badge bg-primary bg-opacity-10 text-primary d-flex align-items-center">
                    ${keyword}
                    <input type="hidden" name="meta_keywords[]" value="${keyword}">
                    <button type="button" class="btn-close btn-close-sm ms-2" onclick="removeKeyword(this)"></button>
                </span>
            </div>
        `).join('');

        // Update character counters
        ['input', 'input'].forEach(eventType => {
            ['metaTitleInput', 'metaDescriptionInput'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.dispatchEvent(new Event(eventType));
            });
        });

        hideLoading();
        showToast('SEO optimized successfully!', 'success');
    }, 1500);
}

function generateKeywords() {
    const prompt = document.getElementById('keywordsPrompt').value;
    const title = document.getElementById('titleInput').value;

    if (!prompt && !title) {
        showToast('Please enter a title or keyword prompt', 'warning');
        return;
    }

    showLoading('Generating keywords...');

    setTimeout(() => {
        const baseKeywords = prompt ? prompt.toLowerCase().split(' ') : [title.toLowerCase()];
        const relatedKeywords = [
            'health',
            'wellness',
            'medical',
            'doctor',
            'treatment',
            'prevention',
            'symptoms',
            'diagnosis',
            'therapy',
            'medicine',
            'care',
            'guide',
            'tips',
            'advice',
            'information'
        ];

        const generatedKeywords = [...new Set([
            ...baseKeywords,
            ...relatedKeywords.slice(0, 8 - baseKeywords.length)
        ])];

        const keywordsContainer = document.getElementById('keywordsContainer');
        keywordsContainer.innerHTML = generatedKeywords.map(keyword => `
            <div class="col-auto mb-2">
                <span class="badge bg-primary bg-opacity-10 text-primary d-flex align-items-center">
                    ${keyword}
                    <input type="hidden" name="meta_keywords[]" value="${keyword}">
                    <button type="button" class="btn-close btn-close-sm ms-2" onclick="removeKeyword(this)"></button>
                </span>
            </div>
        `).join('');

        document.getElementById('keywordsPrompt').value = '';
        hideLoading();
        showToast('Keywords generated successfully!', 'success');
    }, 1000);
}

function suggestTitles() {
    const currentTitle = document.getElementById('titleInput').value;
    const category = document.getElementById('categorySelect').value;

    if (!currentTitle && !category) {
        showToast('Please enter a title or select a category first', 'warning');
        return;
    }

    showLoading('Generating title suggestions...');

    setTimeout(() => {
        const titles = [
            `The Complete Guide to ${currentTitle || category}`,
            `${currentTitle || category}: Everything You Need to Know`,
            `Understanding ${currentTitle || category}: A Comprehensive Overview`,
            `${currentTitle || category} Explained: Expert Insights and Tips`,
            `Mastering ${currentTitle || category}: Practical Strategies for Success`
        ];

        Swal.fire({
            title: 'Suggested Titles',
            html: titles.map((title, index) => `
                <div class="card mb-2 cursor-pointer" onclick="selectTitle('${title.replace(/'/g, "\\'")}')">
                    <div class="card-body">
                        <h6 class="mb-0">${title}</h6>
                    </div>
                </div>
            `).join(''),
            showConfirmButton: false,
            showCloseButton: true
        });

        hideLoading();
    }, 1500);
}

function selectTitle(title) {
    document.getElementById('titleInput').value = title;
    Swal.close();
    showToast('Title applied!', 'success');
}

// Initialize Summernote with AI enhancements
$(document).ready(function() {
    if ($('#contentEditor').length && typeof $.fn.summernote !== 'undefined') {
        $('#contentEditor').summernote({
            height: 400,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']],
                ['ai', ['aiGenerate']]
            ],
            buttons: {
                aiGenerate: function(context) {
                    const ui = $.summernote.ui;
                    const button = ui.button({
                        contents: '<i class="fas fa-robot"></i> AI',
                        tooltip: 'AI Assistant',
                        click: function() {
                            openAIAssistant();
                        }
                    });
                    return button.render();
                }
            }
        });
    }
});
</script>

<style>
.btn-ai {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 0.5rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-ai:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    color: white;
}

.border-ai-blue {
    border: 1px solid rgba(59, 130, 246, 0.3) !important;
}

.cursor-pointer {
    cursor: pointer;
}
</style>
@endpush
