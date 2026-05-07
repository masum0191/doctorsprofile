@extends('layouts.supperadmin')
@section('title', 'Company Settings')

@section('content')

<style>
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.1);
        --primary-dark: #276854;
        --primary-soft: rgba(49, 128, 105, 0.05);
    }

    /* Form Card Styles */
    .settings-card {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .settings-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-soft) 0%, #ffffff 100%);
        border-bottom: 2px solid var(--primary-light);
        padding: 1rem 1.5rem;
    }

    .card-header h6 {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--primary-dark);
    }

    /* Form Controls */
    .form-label {
        font-weight: 600;
        font-size: 0.8rem;
        color: #374151;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .form-label i {
        color: var(--primary);
        font-size: 0.9rem;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: white;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    /* Image Preview */
    .image-preview {
        margin-top: 0.75rem;
        padding: 0.5rem;
        background: #f9fafb;
        border-radius: 10px;
        display: inline-block;
        border: 1px solid #e5e7eb;
    }

    .image-preview img {
        border-radius: 8px;
        object-fit: cover;
    }

    /* File Input Styling */
    input[type="file"] {
        padding: 0.5rem;
        background: #f9fafb;
        cursor: pointer;
    }

    input[type="file"]:hover {
        background: var(--primary-soft);
    }

    /* Section Divider */
    .section-divider {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .section-divider .line {
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-header {
            padding: 0.75rem 1rem;
        }
        
        .card-header h6 {
            font-size: 0.85rem;
        }
        
        .form-label {
            font-size: 0.75rem;
        }
        
        .form-control, .form-select {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }
    }
</style>

{{-- Page Header --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1 text-gray-800">
            <i class="ri-settings-4-line text-primary me-2"></i>
            Company Settings
        </h4>
        <p class="text-muted mb-0 small">
            Manage company profile, branding, social links and SEO settings
        </p>
    </div>
</div>

<form method="POST" action="{{ route('superadmin.company.settings.update') }}" enctype="multipart/form-data">
    @csrf

    {{-- BASIC INFO --}}
    <div class="settings-card card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h6 class="mb-0 fw-semibold">
                <i class="ri-building-line text-primary me-2"></i>
                Basic Information
            </h6>
        </div>

        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="ri-building-2-line"></i> Company Name
                    </label>
                    <input name="company_name" class="form-control" value="{{ $setting->company_name }}" placeholder="Enter company name">
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        <i class="ri-quote-text-line"></i> Tagline
                    </label>
                    <input name="tagline" class="form-control" value="{{ $setting->tagline }}" placeholder="Your company slogan">
                </div>

                <div class="col-md-4">
                    <label class="form-label">
                        <i class="ri-mail-line"></i> Email Address
                    </label>
                    <input name="email" class="form-control" value="{{ $setting->email }}" type="email" placeholder="info@company.com">
                </div>

                <div class="col-md-4">
                    <label class="form-label">
                        <i class="ri-phone-line"></i> Phone Number
                    </label>
                    <input name="phone" class="form-control" value="{{ $setting->phone }}" placeholder="+880xxxxxxxxx">
                </div>

                <div class="col-md-4">
                    <label class="form-label">
                        <i class="ri-global-line"></i> Website
                    </label>
                    <input name="website" class="form-control" value="{{ $setting->website }}" placeholder="https://example.com">
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        <i class="ri-map-pin-line"></i> Address
                    </label>
                    <input name="address" class="form-control" value="{{ $setting->address }}" placeholder="Full company address">
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        <i class="ri-money-dollar-circle-line"></i> Currency
                    </label>
                    <select name="currency" class="form-select">
                        <option value="">Select Currency</option>
                        @php
                            $currencies = [
                                '$' => '💵 US Dollar (USD)',
                                '€' => '💶 Euro (EUR)',
                                '£' => '💷 British Pound (GBP)',
                                '¥' => '💴 Yen / Yuan (JPY/CNY)',
                                '₹' => '₹ Indian Rupee (INR)',
                                '৳' => '৳ Bangladeshi Taka (BDT)'
                            ];
                        @endphp
                        @foreach($currencies as $symbol => $name)
                            <option value="{{ $symbol }}" {{ $setting->currency == $symbol ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- BRAND ASSETS --}}
    <div class="settings-card card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h6 class="mb-0 fw-semibold">
                <i class="ri-image-line text-primary me-2"></i>
                Brand Assets
            </h6>
        </div>

        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="ri-image-2-line"></i> Company Logo
                    </label>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    @if($setting->logo)
                        <div class="image-preview">
                            <img src="{{ asset($setting->logo) }}" height="50" alt="Logo">
                            <small class="text-muted ms-2">Current logo</small>
                        </div>
                    @endif
                    <small class="text-muted d-block mt-1">Recommended size: 200x50px</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        <i class="ri-star-line"></i> Favicon
                    </label>
                    <input type="file" name="favicon" class="form-control" accept="image/*">
                    @if($setting->favicon)
                        <div class="image-preview">
                            <img src="{{ asset($setting->favicon) }}" height="32" alt="Favicon">
                            <small class="text-muted ms-2">Current favicon</small>
                        </div>
                    @endif
                    <small class="text-muted d-block mt-1">Recommended size: 32x32px</small>
                </div>
            </div>
        </div>
    </div>

    {{-- ABOUT PAGE --}}
    <div class="settings-card card border-0 shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center gap-3">
            <h6 class="mb-0 fw-semibold">
                <i class="ri-information-line text-primary me-2"></i>
                About Page Content
            </h6>
            <a href="{{ url('/about') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                <i class="ri-external-link-line me-1"></i>
                Preview About Page
            </a>
        </div>

        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-12">
                    <label class="form-label">
                        <i class="ri-file-text-line"></i> About Page Description
                    </label>
                    <textarea name="about" rows="8" class="form-control" placeholder="Write your company story, mission, services, and why people should trust your platform...">{{ old('about', $setting->about) }}</textarea>
                    <small class="text-muted d-block mt-2">
                        This text appears on the public <code>/about</code> page.
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- SOCIAL LINKS --}}
    <div class="settings-card card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h6 class="mb-0 fw-semibold">
                <i class="ri-share-line text-primary me-2"></i>
                Social Media Links
            </h6>
        </div>

        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-4">
                    <label class="form-label">
                        <i class="ri-facebook-line text-primary"></i> Facebook
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">facebook.com/</span>
                        <input name="facebook_url" class="form-control border-start-0" value="{{ $setting->facebook_url }}" placeholder="username">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">
                        <i class="ri-twitter-x-line"></i> Twitter / X
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">twitter.com/</span>
                        <input name="twitter_url" class="form-control border-start-0" value="{{ $setting->twitter_url }}" placeholder="username">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">
                        <i class="ri-linkedin-line text-info"></i> LinkedIn
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">linkedin.com/in/</span>
                        <input name="linkedin_url" class="form-control border-start-0" value="{{ $setting->linkedin_url }}" placeholder="username">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">
                        <i class="ri-instagram-line text-danger"></i> Instagram
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">instagram.com/</span>
                        <input name="instagram_url" class="form-control border-start-0" value="{{ $setting->instagram_url }}" placeholder="username">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">
                        <i class="ri-youtube-line text-danger"></i> YouTube
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">youtube.com/@</span>
                        <input name="youtube_url" class="form-control border-start-0" value="{{ $setting->youtube_url }}" placeholder="channel">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">
                        <i class="ri-tiktok-line"></i> TikTok
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">tiktok.com/@</span>
                        <input name="tiktok_url" class="form-control border-start-0" value="{{ $setting->tiktok_url }}" placeholder="username">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SEO & OPEN GRAPH --}}
    <div class="settings-card card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h6 class="mb-0 fw-semibold">
                <i class="ri-seo-line text-primary me-2"></i>
                SEO & Open Graph Settings
            </h6>
        </div>

        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="ri-file-text-line"></i> Meta Title
                    </label>
                    <input name="meta_title" class="form-control" value="{{ $setting->meta_title }}" placeholder="Page title for search engines">
                    <small class="text-muted">Recommended: 50-60 characters</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        <i class="ri-file-copy-line"></i> Meta Description
                    </label>
                    <input name="meta_description" class="form-control" value="{{ $setting->meta_description }}" placeholder="Brief description for search results">
                    <small class="text-muted">Recommended: 150-160 characters</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        <i class="ri-price-tag-3-line"></i> Keywords
                    </label>
                    <input name="keywords" class="form-control" value="{{ $setting->keywords }}" placeholder="keyword1, keyword2, keyword3">
                    <small class="text-muted">Separate keywords with commas</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        <i class="ri-robot-line"></i> Robots
                    </label>
                    <select name="robots" class="form-select">
                        <option value="index, follow" {{ $setting->robots == 'index, follow' ? 'selected' : '' }}>Index, Follow (Default)</option>
                        <option value="noindex, follow" {{ $setting->robots == 'noindex, follow' ? 'selected' : '' }}>No Index, Follow</option>
                        <option value="index, nofollow" {{ $setting->robots == 'index, nofollow' ? 'selected' : '' }}>Index, No Follow</option>
                        <option value="noindex, nofollow" {{ $setting->robots == 'noindex, nofollow' ? 'selected' : '' }}>No Index, No Follow</option>
                    </select>
                </div>

                <div class="col-12">
                    <div class="section-divider">
                        <i class="ri-facebook-circle-line text-primary"></i>
                        <span class="small fw-semibold text-muted">Open Graph (Facebook / Social Media)</span>
                        <div class="line"></div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        <i class="ri-facebook-line"></i> OG Title
                    </label>
                    <input name="ogtitle" class="form-control" value="{{ $setting->ogtitle }}" placeholder="Title when shared on social media">
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        <i class="ri-file-text-line"></i> OG Description
                    </label>
                    <input name="ogdescription" class="form-control" value="{{ $setting->ogdescription }}" placeholder="Description when shared on social media">
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        <i class="ri-global-line"></i> OG Type
                    </label>
                    <select name="ogtype" class="form-select">
                        <option value="website" {{ $setting->ogtype == 'website' ? 'selected' : '' }}>Website</option>
                        <option value="article" {{ $setting->ogtype == 'article' ? 'selected' : '' }}>Article</option>
                        <option value="product" {{ $setting->ogtype == 'product' ? 'selected' : '' }}>Product</option>
                        <option value="profile" {{ $setting->ogtype == 'profile' ? 'selected' : '' }}>Profile</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        <i class="ri-link"></i> OG URL
                    </label>
                    <input name="ogurl" class="form-control" value="{{ $setting->ogurl }}" placeholder="https://example.com">
                </div>

                <div class="col-md-12">
                    <label class="form-label">
                        <i class="ri-image-line"></i> OG Image
                    </label>
                    <input type="file" name="ogimage" class="form-control" accept="image/*">
                    @if($setting->ogimage)
                        <div class="image-preview">
                            <img src="{{ asset($setting->ogimage) }}" height="70" alt="OG Image">
                            <small class="text-muted ms-2">Current OG image</small>
                        </div>
                    @endif
                    <small class="text-muted d-block mt-1">Recommended size: 1200x630px for best social sharing</small>
                </div>
            </div>
        </div>
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="d-flex justify-content-end gap-3 mb-4">
        <button type="reset" class="btn btn-light px-4">
            <i class="ri-refresh-line me-1"></i>
            Reset
        </button>
        <button type="submit" class="btn btn-primary px-4">
            <i class="ri-save-3-line me-1"></i>
            Save All Settings
        </button>
    </div>

</form> 

@endsection

@push('scripts')
<script>
    // Preview image before upload
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    // Find or create preview element
                    let preview = input.parentElement.querySelector('.image-preview');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.className = 'image-preview mt-2';
                        input.parentElement.appendChild(preview);
                    }
                    preview.innerHTML = `<img src="${event.target.result}" height="50" alt="Preview"> <small class="text-muted ms-2">New image preview</small>`;
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush
