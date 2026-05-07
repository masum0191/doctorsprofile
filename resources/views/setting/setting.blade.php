{{--@extends('layouts.admin')
@section('title', 'System Settings')

@section('content')
<style>
    /* Settings Page Styles */
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.1);
        --primary-dark: #2a6d5a;
        --primary-soft: rgba(49, 128, 105, 0.05);
        --primary-hover: rgba(49, 128, 105, 0.15);
    }

    .settings-container {
        margin: 0 auto;
    }

    /* Horizontal Tabs */
    .horizontal-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        padding: 0.5rem;
        background: white;
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        overflow-x: auto;
    }

    .horizontal-tab {
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
        border: none;
        background: transparent;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .horizontal-tab:hover {
        background: var(--primary-soft);
        color: var(--primary);
    }

    .horizontal-tab.active {
        background: var(--primary);
        color: white;
        font-weight: 600;
    }

    .tab-icon {
        font-size: 1rem;
    }

    .settings-section {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .settings-section.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .settings-card {
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        background: white;
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .settings-card:hover {
        box-shadow: 0 8px 25px rgba(49, 128, 105, 0.1);
    }

    .settings-card-header {
        background: var(--primary-soft);
        border-bottom: 2px solid var(--primary-light);
        padding: 1rem 1.5rem;
        font-weight: 600;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .settings-card-header-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
    }

    .settings-card-body {
        padding: 1.5rem;
    }

    .form-group-compact {
        margin-bottom: 1.25rem;
    }

    .form-label-compact {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label-compact .form-label-icon {
        color: var(--primary);
    }

    .form-control-compact {
        width: 100%;
        border: 1px solid rgba(49, 128, 105, 0.2);
        border-radius: 8px;
        padding: 0.75rem 0.875rem;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: white;
        height: 38px;
    }

    .form-control-compact:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    textarea.form-control-compact {
        min-height: 100px;
        resize: vertical;
    }

    .form-hint {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.375rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .form-hint i {
        color: var(--primary);
    }

    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
    }

    .payment-method-card {
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 10px;
        padding: 1.25rem;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .payment-method-card:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(49, 128, 105, 0.1);
    }

    .payment-method-card.active {
        border-color: var(--primary);
        background: var(--primary-soft);
    }

    .payment-method-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .payment-method-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1.25rem;
    }

    .payment-method-title {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.25rem;
    }

    .payment-method-status {
        font-size: 0.75rem;
        color: #6b7280;
    }

    .status-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.625rem;
        border-radius: 20px;
        font-weight: 500;
    }

    .status-active {
        background: rgba(49, 128, 105, 0.1);
        color: #065f46;
        border: 1px solid rgba(49, 128, 105, 0.2);
    }

    .status-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #991b1b;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #e5e7eb;
        transition: .4s;
        border-radius: 34px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .toggle-slider {
        background-color: var(--primary);
    }

    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }

    .preview-image {
        width: 120px;
        height: 120px;
        border: 2px dashed rgba(49, 128, 105, 0.3);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-soft);
        color: var(--primary);
        cursor: pointer;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .preview-image:hover {
        border-color: var(--primary);
        background: var(--primary-light);
    }

    .preview-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .action-buttons {
        position: sticky;
        bottom: 1rem;
        background: white;
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-top: 2rem;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    /* Social Media Colors */
    .social-facebook { color: #1877F2; }
    .social-youtube { color: #FF0000; }
    .social-twitter { color: #1DA1F2; }
    .social-linkedin { color: #0A66C2; }
    .social-tiktok { color: #000000; }
    .social-instagram { color: #E4405F; }
    .social-whatsapp { color: #25D366; }

    .password-strength {
        margin-top: 0.5rem;
        height: 4px;
        background: #e5e7eb;
        border-radius: 2px;
        overflow: hidden;
    }

    .strength-meter {
        height: 100%;
        width: 0%;
        transition: all 0.3s ease;
    }

    .strength-weak { background: #dc2626; }
    .strength-medium { background: #f59e0b; }
    .strength-strong { background: #10b981; }

    .strength-text {
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .horizontal-tabs {
            flex-wrap: wrap;
        }
    }
</style>

<div class="settings-container">


    <!-- Horizontal Tabs -->
    <div class="horizontal-tabs">
        <button class="horizontal-tab active" data-section="seo">
            <i class="fas fa-search tab-icon"></i>
            SEO Settings
        </button>
        <button class="horizontal-tab" data-section="email">
            <i class="fas fa-envelope tab-icon"></i>
            Email Settings
        </button>
        <button class="horizontal-tab" data-section="sms">
            <i class="fas fa-sms tab-icon"></i>
            SMS Settings
        </button>
        <button class="horizontal-tab" data-section="payment">
            <i class="fas fa-credit-card tab-icon"></i>
            Payment Settings
        </button>
        <!-- New Tabs -->
        <button class="horizontal-tab" data-section="social">
            <i class="fas fa-share-alt tab-icon"></i>
            Social Media
        </button>
        <button class="horizontal-tab" data-section="security">
            <i class="fas fa-lock tab-icon"></i>
            Security
        </button>
    </div>

    <form id="settingsForm" method="POST" action="#">
        @csrf

        <!-- SEO Settings -->
        <div class="settings-section active" id="seo-section">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-header-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <span>SEO Configuration</span>
                </div>
                <div class="settings-card-body">
                    <div class="settings-grid">
                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-heading form-label-icon"></i>
                                Meta Title
                            </label>
                            <input type="text"
                                   name="meta_title"
                                   class="form-control-compact"
                                   placeholder="Website Meta Title"
                                   maxlength="60">
                            <div class="form-hint">
                                <i class="fas fa-info-circle"></i>
                                Recommended: 50-60 characters
                            </div>
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-tags form-label-icon"></i>
                                Meta Keywords
                            </label>
                            <input type="text"
                                   name="meta_keywords"
                                   class="form-control-compact"
                                   placeholder="keyword1, keyword2, keyword3">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-align-left form-label-icon"></i>
                                Tagline
                            </label>
                            <input type="text"
                                   name="tagline"
                                   class="form-control-compact"
                                   placeholder="Your website tagline">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-chart-line form-label-icon"></i>
                                Google Analytics ID
                            </label>
                            <input type="text"
                                   name="ga_id"
                                   class="form-control-compact"
                                   placeholder="UA-XXXXX-Y">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fab fa-facebook form-label-icon"></i>
                                Facebook Pixel ID
                            </label>
                            <input type="text"
                                   name="pixel_id"
                                   class="form-control-compact"
                                   placeholder="123456789012345">
                        </div>
                    </div>

                    <div class="form-group-compact mt-3">
                        <label class="form-label-compact">
                            <i class="fas fa-file-alt form-label-icon"></i>
                            Meta Description
                        </label>
                        <textarea name="meta_description"
                                  class="form-control-compact"
                                  rows="3"
                                  placeholder="Website meta description"
                                  maxlength="160"></textarea>
                        <div class="form-hint">
                            <i class="fas fa-info-circle"></i>
                            Recommended: 150-160 characters
                        </div>
                    </div>

                    <div class="form-group-compact">
                        <label class="form-label-compact">
                            <i class="fas fa-image form-label-icon"></i>
                            OG Image URL
                        </label>
                        <div class="d-flex align-items-start gap-3">
                            <div class="preview-image" id="ogImagePreview">
                                <i class="fas fa-image fa-2x"></i>
                                <input type="file" id="og-image-input" class="d-none" accept="image/*">
                            </div>
                            <div class="flex-grow-1">
                                <input type="text"
                                       name="og_image"
                                       class="form-control-compact"
                                       placeholder="/images/og-image.jpg">
                                <div class="form-hint">
                                    <i class="fas fa-info-circle"></i>
                                    Recommended: 1200x630px (Facebook)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Settings -->
        <div class="settings-section" id="email-section">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-header-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <span>Email Configuration</span>
                </div>
                <div class="settings-card-body">
                    <div class="settings-grid">
                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-mail-bulk form-label-icon"></i>
                                Mail Type
                            </label>
                            <select name="mail_type" class="form-control-compact">
                                <option value="" selected disabled>Select mail type</option>
                                <option value="smtp">SMTP</option>
                                <option value="sendmail">Sendmail</option>
                                <option value="mailgun">Mailgun</option>
                                <option value="ses">Amazon SES</option>
                            </select>
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-at form-label-icon"></i>
                                From Address
                            </label>
                            <input type="email"
                                   name="mail_from"
                                   class="form-control-compact"
                                   placeholder="noreply@example.com">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-user-tie form-label-icon"></i>
                                Sender Name
                            </label>
                            <input type="text"
                                   name="mail_sender_name"
                                   class="form-control-compact"
                                   placeholder="Your Company Name">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-server form-label-icon"></i>
                                SMTP Host
                            </label>
                            <input type="text"
                                   name="smtp_host"
                                   class="form-control-compact"
                                   placeholder="smtp.gmail.com">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-network-wired form-label-icon"></i>
                                Port
                            </label>
                            <input type="number"
                                   name="smtp_port"
                                   class="form-control-compact"
                                   placeholder="587">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-lock form-label-icon"></i>
                                Encryption
                            </label>
                            <select name="smtp_encryption" class="form-control-compact">
                                <option value="" selected disabled>Select encryption</option>
                                <option value="tls">TLS</option>
                                <option value="ssl">SSL</option>
                                <option value="">None</option>
                            </select>
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-user form-label-icon"></i>
                                Username
                            </label>
                            <input type="text"
                                   name="smtp_username"
                                   class="form-control-compact"
                                   placeholder="your-email@gmail.com">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-key form-label-icon"></i>
                                Password
                            </label>
                            <input type="password"
                                   name="smtp_password"
                                   class="form-control-compact"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <div class="form-group-compact mt-3">
                        <label class="form-label-compact">
                            <i class="fas fa-code form-label-icon"></i>
                            Email Template (HTML)
                        </label>
                        <textarea name="mail_template"
                                  class="form-control-compact"
                                  rows="4"
                                  placeholder="<!DOCTYPE html>
<html>
<head>
    <title>Appointment Confirmation</title>
</head>
<body>
    <h1>Appointment Confirmed</h1>
    <p>Dear [Patient Name],</p>
    <p>Your appointment is confirmed for [Date] at [Time].</p>
    <p>Thank you for choosing our service.</p>
</body>
</html>"></textarea>
                        <div class="form-hint">
                            <i class="fas fa-info-circle"></i>
                            Use [variable] for dynamic content
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SMS Settings -->
        <div class="settings-section" id="sms-section">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-header-icon">
                        <i class="fas fa-sms"></i>
                    </div>
                    <span>SMS Configuration</span>
                </div>
                <div class="settings-card-body">
                    <div class="settings-grid">
                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-code form-label-icon"></i>
                                API Key
                            </label>
                            <input type="text"
                                   name="sms_api_key"
                                   class="form-control-compact"
                                   placeholder="Your SMS API Key">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-key form-label-icon"></i>
                                Access Token
                            </label>
                            <input type="text"
                                   name="sms_access_token"
                                   class="form-control-compact"
                                   placeholder="Access Token">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-hashtag form-label-icon"></i>
                                Sender ID
                            </label>
                            <input type="text"
                                   name="sms_sender_id"
                                   class="form-control-compact"
                                   placeholder="CompanyName">
                        </div>
                    </div>

                    <div class="form-group-compact mt-3">
                        <label class="form-label-compact">
                            <i class="fas fa-comment-dots form-label-icon"></i>
                            Default SMS Template
                        </label>
                        <textarea name="sms_template"
                                  class="form-control-compact"
                                  rows="4"
                                  placeholder="Hello [Patient Name], your appointment with Dr. [Doctor Name] is confirmed for [Date] at [Time]. Clinic: [Clinic Name]"></textarea>
                        <div class="form-hint">
                            <i class="fas fa-info-circle"></i>
                            Available variables: [name], [date], [time], [doctor], [clinic]
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Settings -->
        <div class="settings-section" id="payment-section">
            <!-- Payment Method Selector -->
            <div class="settings-card mb-3">
                <div class="settings-card-header">
                    <div class="settings-card-header-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <span>Payment Gateways</span>
                </div>
                <div class="settings-card-body">
                    <div class="settings-grid">
                        <div class="payment-method-card active" data-method="sslcommerz">
                            <div class="payment-method-header">
                                <div class="payment-method-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div>
                                    <div class="payment-method-title">SSLCOMMERZ</div>
                                    <div class="payment-method-status">
                                        <span class="status-badge status-inactive">Inactive</span>
                                    </div>
                                </div>
                            </div>
                            <div class="toggle-switch">
                                <input type="checkbox" name="sslcommerz_enabled">
                                <span class="toggle-slider"></span>
                            </div>
                        </div>

                        <div class="payment-method-card" data-method="stripe">
                            <div class="payment-method-header">
                                <div class="payment-method-icon">
                                    <i class="fab fa-stripe"></i>
                                </div>
                                <div>
                                    <div class="payment-method-title">Stripe</div>
                                    <div class="payment-method-status">
                                        <span class="status-badge status-inactive">Inactive</span>
                                    </div>
                                </div>
                            </div>
                            <div class="toggle-switch">
                                <input type="checkbox" name="stripe_enabled">
                                <span class="toggle-slider"></span>
                            </div>
                        </div>

                        <div class="payment-method-card" data-method="bkash">
                            <div class="payment-method-header">
                                <div class="payment-method-icon">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <div>
                                    <div class="payment-method-title">bKash</div>
                                    <div class="payment-method-status">
                                        <span class="status-badge status-inactive">Inactive</span>
                                    </div>
                                </div>
                            </div>
                            <div class="toggle-switch">
                                <input type="checkbox" name="bkash_enabled">
                                <span class="toggle-slider"></span>
                            </div>
                        </div>

                        <div class="payment-method-card" data-method="paypal">
                            <div class="payment-method-header">
                                <div class="payment-method-icon">
                                    <i class="fab fa-paypal"></i>
                                </div>
                                <div>
                                    <div class="payment-method-title">PayPal</div>
                                    <div class="payment-method-status">
                                        <span class="status-badge status-inactive">Inactive</span>
                                    </div>
                                </div>
                            </div>
                            <div class="toggle-switch">
                                <input type="checkbox" name="paypal_enabled">
                                <span class="toggle-slider"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SSLCOMMERZ Settings -->
            <div class="settings-card payment-method-settings active" id="sslcommerz-settings">
                <div class="settings-card-header">
                    <div class="settings-card-header-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <span>SSLCOMMERZ Configuration</span>
                </div>
                <div class="settings-card-body">
                    <div class="settings-grid">
                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-code form-label-icon"></i>
                                API Key
                            </label>
                            <input type="text"
                                   name="sslcommerz_api_key"
                                   class="form-control-compact"
                                   placeholder="Your SSLCOMMERZ API Key">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-key form-label-icon"></i>
                                Access Token
                            </label>
                            <input type="text"
                                   name="sslcommerz_access_token"
                                   class="form-control-compact"
                                   placeholder="Access Token">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-id-badge form-label-icon"></i>
                                Merchant ID
                            </label>
                            <input type="text"
                                   name="sslcommerz_merchant_id"
                                   class="form-control-compact"
                                   placeholder="Merchant ID">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-lock form-label-icon"></i>
                                Secret Key
                            </label>
                            <input type="password"
                                   name="sslcommerz_secret_key"
                                   class="form-control-compact"
                                   placeholder="••••••••">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stripe Settings -->
            <div class="settings-card payment-method-settings" id="stripe-settings">
                <div class="settings-card-header">
                    <div class="settings-card-header-icon">
                        <i class="fab fa-stripe"></i>
                    </div>
                    <span>Stripe Configuration</span>
                </div>
                <div class="settings-card-body">
                    <div class="settings-grid">
                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-code form-label-icon"></i>
                                API Key
                            </label>
                            <input type="text"
                                   name="stripe_api_key"
                                   class="form-control-compact"
                                   placeholder="pk_live_...">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-key form-label-icon"></i>
                                Access Token
                            </label>
                            <input type="text"
                                   name="stripe_access_token"
                                   class="form-control-compact"
                                   placeholder="Access Token">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-id-badge form-label-icon"></i>
                                Merchant ID
                            </label>
                            <input type="text"
                                   name="stripe_merchant_id"
                                   class="form-control-compact"
                                   placeholder="Merchant ID">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-lock form-label-icon"></i>
                                Secret Key
                            </label>
                            <input type="password"
                                   name="stripe_secret_key"
                                   class="form-control-compact"
                                   placeholder="••••••••">
                        </div>
                    </div>
                </div>
            </div>

            <!-- bKash Settings -->
            <div class="settings-card payment-method-settings" id="bkash-settings">
                <div class="settings-card-header">
                    <div class="settings-card-header-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <span>bKash Configuration</span>
                </div>
                <div class="settings-card-body">
                    <div class="settings-grid">
                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-code form-label-icon"></i>
                                API Key
                            </label>
                            <input type="text"
                                   name="bkash_api_key"
                                   class="form-control-compact"
                                   placeholder="Your bKash API Key">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-key form-label-icon"></i>
                                Access Token
                            </label>
                            <input type="text"
                                   name="bkash_access_token"
                                   class="form-control-compact"
                                   placeholder="Access Token">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-id-badge form-label-icon"></i>
                                Merchant ID
                            </label>
                            <input type="text"
                                   name="bkash_merchant_id"
                                   class="form-control-compact"
                                   placeholder="Merchant ID">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-lock form-label-icon"></i>
                                Secret Key
                            </label>
                            <input type="password"
                                   name="bkash_secret_key"
                                   class="form-control-compact"
                                   placeholder="••••••••">
                        </div>
                    </div>
                </div>
            </div>

            <!-- PayPal Settings -->
            <div class="settings-card payment-method-settings" id="paypal-settings">
                <div class="settings-card-header">
                    <div class="settings-card-header-icon">
                        <i class="fab fa-paypal"></i>
                    </div>
                    <span>PayPal Configuration</span>
                </div>
                <div class="settings-card-body">
                    <div class="settings-grid">
                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-code form-label-icon"></i>
                                API Key
                            </label>
                            <input type="text"
                                   name="paypal_api_key"
                                   class="form-control-compact"
                                   placeholder="Your PayPal API Key">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-key form-label-icon"></i>
                                Access Token
                            </label>
                            <input type="text"
                                   name="paypal_access_token"
                                   class="form-control-compact"
                                   placeholder="Access Token">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-id-badge form-label-icon"></i>
                                Merchant ID
                            </label>
                            <input type="text"
                                   name="paypal_merchant_id"
                                   class="form-control-compact"
                                   placeholder="Merchant ID">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-lock form-label-icon"></i>
                                Secret Key
                            </label>
                            <input type="password"
                                   name="paypal_secret_key"
                                   class="form-control-compact"
                                   placeholder="••••••••">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Media Settings -->
        <div class="settings-section" id="social-section">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-header-icon">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <span>Social Media Links</span>
                </div>
                <div class="settings-card-body">
                    <div class="settings-grid">
                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fab fa-facebook social-facebook form-label-icon"></i>
                                Facebook Page
                            </label>
                            <input type="url"
                                   name="facebook_url"
                                   class="form-control-compact"
                                   placeholder="https://facebook.com/yourpage">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fab fa-youtube social-youtube form-label-icon"></i>
                                YouTube Channel
                            </label>
                            <input type="url"
                                   name="youtube_url"
                                   class="form-control-compact"
                                   placeholder="https://youtube.com/channel/yourchannel">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fab fa-twitter social-twitter form-label-icon"></i>
                                Twitter/X Profile
                            </label>
                            <input type="url"
                                   name="twitter_url"
                                   class="form-control-compact"
                                   placeholder="https://twitter.com/yourprofile">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fab fa-linkedin social-linkedin form-label-icon"></i>
                                LinkedIn Page
                            </label>
                            <input type="url"
                                   name="linkedin_url"
                                   class="form-control-compact"
                                   placeholder="https://linkedin.com/company/yourcompany">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fab fa-tiktok social-tiktok form-label-icon"></i>
                                TikTok Profile
                            </label>
                            <input type="url"
                                   name="tiktok_url"
                                   class="form-control-compact"
                                   placeholder="https://tiktok.com/@yourprofile">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fab fa-instagram social-instagram form-label-icon"></i>
                                Instagram Profile
                            </label>
                            <input type="url"
                                   name="instagram_url"
                                   class="form-control-compact"
                                   placeholder="https://instagram.com/yourprofile">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fab fa-whatsapp social-whatsapp form-label-icon"></i>
                                WhatsApp Number
                            </label>
                            <div class="d-flex gap-2">
                                <select name="whatsapp_country_code" class="form-control-compact" style="width: 100px;">
                                    <option value="+880">+880 (BD)</option>
                                    <option value="+1">+1 (USA)</option>
                                    <option value="+44">+44 (UK)</option>
                                    <option value="+91">+91 (IN)</option>
                                    <option value="+971">+971 (UAE)</option>
                                </select>
                                <input type="tel"
                                       name="whatsapp_number"
                                       class="form-control-compact flex-grow-1"
                                       placeholder="Phone number">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="settings-section" id="security-section">
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-header-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <span>Security Settings</span>
                </div>
                <div class="settings-card-body">
                    <div class="settings-grid">
                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-key form-label-icon"></i>
                                Current Password
                            </label>
                            <input type="password"
                                   name="current_password"
                                   class="form-control-compact"
                                   placeholder="Enter current password">
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-lock form-label-icon"></i>
                                New Password
                            </label>
                            <input type="password"
                                   name="new_password"
                                   id="newPassword"
                                   class="form-control-compact"
                                   placeholder="Enter new password">
                            <div class="password-strength">
                                <div class="strength-meter" id="passwordStrength"></div>
                            </div>
                            <div class="strength-text" id="passwordStrengthText"></div>
                        </div>

                        <div class="form-group-compact">
                            <label class="form-label-compact">
                                <i class="fas fa-lock form-label-icon"></i>
                                Confirm New Password
                            </label>
                            <input type="password"
                                   name="confirm_password"
                                   id="confirmPassword"
                                   class="form-control-compact"
                                   placeholder="Confirm new password">
                            <div class="form-hint" id="passwordMatchHint">
                                <i class="fas fa-info-circle"></i>
                                Passwords must match
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="settings-card-header" style="background: transparent; padding: 0; margin-bottom: 1rem;">
                            <div class="settings-card-header-icon" style="background: #f3f4f6; color: #374151;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <span style="color: #374151;">Password Requirements</span>
                        </div>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li class="package-feature">
                                <i class="fas fa-check-circle text-success"></i>
                                At least 8 characters long
                            </li>
                            <li class="package-feature">
                                <i class="fas fa-check-circle text-success"></i>
                                Contains uppercase and lowercase letters
                            </li>
                            <li class="package-feature">
                                <i class="fas fa-check-circle text-success"></i>
                                Includes at least one number (0-9)
                            </li>
                            <li class="package-feature">
                                <i class="fas fa-check-circle text-success"></i>
                                Contains at least one special character (!@#$%^&*)
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button type="button" class="btn btn-primary-outline" onclick="resetForm()">
                <i class="fas fa-redo me-2"></i>Reset
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Save All Settings
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Horizontal Tabs
        const tabs = document.querySelectorAll('.horizontal-tab');
        const sections = document.querySelectorAll('.settings-section');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const sectionId = this.dataset.section;

                // Update active tab
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                // Show active section
                sections.forEach(section => section.classList.remove('active'));
                document.getElementById(`${sectionId}-section`).classList.add('active');

                // Scroll to top of section
                document.getElementById(`${sectionId}-section`).scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });

        // Payment Method Cards
        const paymentCards = document.querySelectorAll('.payment-method-card');
        const paymentSettings = document.querySelectorAll('.payment-method-settings');

        paymentCards.forEach(card => {
            card.addEventListener('click', function() {
                const method = this.dataset.method;

                // Update active payment card
                paymentCards.forEach(pc => pc.classList.remove('active'));
                this.classList.add('active');

                // Show corresponding settings
                paymentSettings.forEach(ps => ps.classList.remove('active'));
                document.getElementById(`${method}-settings`).classList.add('active');

                // Check the toggle switch
                const toggleInput = this.querySelector('input[type="checkbox"]');
                if (toggleInput) {
                    toggleInput.checked = true;
                }
            });
        });

        // Toggle Switch Update Status Text
        document.querySelectorAll('.toggle-switch input').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const card = this.closest('.payment-method-card');
                const statusBadge = card.querySelector('.status-badge');

                if (this.checked) {
                    statusBadge.classList.remove('status-inactive');
                    statusBadge.classList.add('status-active');
                    statusBadge.textContent = 'Active';
                } else {
                    statusBadge.classList.remove('status-active');
                    statusBadge.classList.add('status-inactive');
                    statusBadge.textContent = 'Inactive';
                }
            });
        });

        // Character Count for SEO Fields
        const metaTitle = document.querySelector('input[name="meta_title"]');
        const metaDesc = document.querySelector('textarea[name="meta_description"]');

        function updateCharCount(element, max) {
            const count = element.value.length;
            let hint = element.nextElementSibling;

            if (hint && hint.classList.contains('form-hint')) {
                const currentText = hint.textContent.split('(')[0].trim();
                hint.innerHTML = '<i class="fas fa-info-circle"></i> ' + currentText + ' (' + count + '/' + max + ')';

                if (count > max) {
                    hint.style.color = '#dc2626';
                } else if (count > max * 0.9) {
                    hint.style.color = '#f59e0b';
                } else {
                    hint.style.color = '#6b7280';
                }
            }
        }

        if (metaTitle) {
            updateCharCount(metaTitle, 60);
            metaTitle.addEventListener('input', function() {
                updateCharCount(metaTitle, 60);
            });
        }

        if (metaDesc) {
            updateCharCount(metaDesc, 160);
            metaDesc.addEventListener('input', function() {
                updateCharCount(metaDesc, 160);
            });
        }

        // Image Preview
        const imageInput = document.getElementById('og-image-input');
        const previewImage = document.getElementById('ogImagePreview');

        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        // Password Strength Checker
        const newPasswordInput = document.getElementById('newPassword');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const passwordStrength = document.getElementById('passwordStrength');
        const passwordStrengthText = document.getElementById('passwordStrengthText');
        const passwordMatchHint = document.getElementById('passwordMatchHint');

        if (newPasswordInput) {
            newPasswordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                let text = '';

                // Check password strength
                if (password.length >= 8) strength++;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
                if (/\d/.test(password)) strength++;
                if (/[!@#$%^&*]/.test(password)) strength++;

                // Update strength meter
                const width = strength * 25;
                passwordStrength.style.width = width + '%';

                // Update text and color
                if (strength === 0) {
                    passwordStrength.className = 'strength-meter';
                    text = '';
                } else if (strength <= 1) {
                    passwordStrength.className = 'strength-meter strength-weak';
                    text = 'Weak';
                } else if (strength <= 2) {
                    passwordStrength.className = 'strength-meter strength-medium';
                    text = 'Medium';
                } else if (strength <= 3) {
                    passwordStrength.className = 'strength-meter strength-strong';
                    text = 'Strong';
                } else {
                    passwordStrength.className = 'strength-meter strength-strong';
                    text = 'Very Strong';
                }

                passwordStrengthText.textContent = text;
                passwordStrengthText.style.color = passwordStrength.style.backgroundColor;

                // Check password match
                checkPasswordMatch();
            });
        }

        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        }

        function checkPasswordMatch() {
            const newPassword = newPasswordInput?.value || '';
            const confirmPassword = confirmPasswordInput?.value || '';

            if (!confirmPassword) {
                passwordMatchHint.innerHTML = '<i class="fas fa-info-circle"></i> Passwords must match';
                passwordMatchHint.style.color = '#6b7280';
                return;
            }

            if (newPassword === confirmPassword) {
                passwordMatchHint.innerHTML = '<i class="fas fa-check-circle" style="color: #10b981;"></i> Passwords match';
                passwordMatchHint.style.color = '#10b981';
            } else {
                passwordMatchHint.innerHTML = '<i class="fas fa-times-circle" style="color: #dc2626;"></i> Passwords do not match';
                passwordMatchHint.style.color = '#dc2626';
            }
        }

        // Form Validation
        const form = document.getElementById('settingsForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent form submission for now (static)

            // Validate password change if security section is active
            if (document.getElementById('security-section').classList.contains('active')) {
                const currentPassword = document.querySelector('input[name="current_password"]').value;
                const newPassword = document.querySelector('input[name="new_password"]').value;
                const confirmPassword = document.querySelector('input[name="confirm_password"]').value;

                if (newPassword && newPassword !== confirmPassword) {
                    alert('New passwords do not match!');
                    return;
                }

                if (newPassword && newPassword.length < 8) {
                    alert('Password must be at least 8 characters long!');
                    return;
                }
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
            submitBtn.disabled = true;

            // Simulate API call
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                showSuccessMessage('Settings saved successfully!');
            }, 1500);
        });
    });

    function resetForm() {
        if (confirm('Are you sure you want to reset all settings? All entered data will be cleared.')) {
            document.getElementById('settingsForm').reset();

            // Reset active states
            document.querySelectorAll('.horizontal-tab').forEach((tab, index) => {
                tab.classList.remove('active');
                if (index === 0) tab.classList.add('active');
            });

            document.querySelectorAll('.settings-section').forEach((section, index) => {
                section.classList.remove('active');
                if (index === 0) section.classList.add('active');
            });

            // Reset payment method visibility
            document.querySelectorAll('.payment-method-settings').forEach((setting, index) => {
                setting.classList.remove('active');
                if (index === 0) setting.classList.add('active');
            });

            document.querySelectorAll('.payment-method-card').forEach((card, index) => {
                card.classList.remove('active');
                if (index === 0) card.classList.add('active');
            });

            // Reset all toggle switches to inactive
            document.querySelectorAll('.toggle-switch input').forEach(toggle => {
                toggle.checked = false;
                const card = toggle.closest('.payment-method-card');
                const statusBadge = card.querySelector('.status-badge');
                statusBadge.classList.remove('status-active');
                statusBadge.classList.add('status-inactive');
                statusBadge.textContent = 'Inactive';
            });

            // Reset password strength meter
            const passwordStrength = document.getElementById('passwordStrength');
            if (passwordStrength) {
                passwordStrength.style.width = '0%';
                passwordStrength.className = 'strength-meter';
            }

            const passwordStrengthText = document.getElementById('passwordStrengthText');
            if (passwordStrengthText) {
                passwordStrengthText.textContent = '';
            }

            const passwordMatchHint = document.getElementById('passwordMatchHint');
            if (passwordMatchHint) {
                passwordMatchHint.innerHTML = '<i class="fas fa-info-circle"></i> Passwords must match';
                passwordMatchHint.style.color = '#6b7280';
            }

            showSuccessMessage('All fields have been reset');
        }
    }

    function showSuccessMessage(message) {
        // Create toast message
        const toast = document.createElement('div');
        toast.className = 'position-fixed top-0 end-0 m-4 p-3 rounded shadow-lg';
        toast.style.backgroundColor = '#10b981';
        toast.style.color = 'white';
        toast.style.zIndex = '9999';
        toast.innerHTML = '<div class="d-flex align-items-center gap-2"><i class="fas fa-check-circle"></i><span>' + message + '</span></div>';

        document.body.appendChild(toast);

        // Remove after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
@endsection--}}
@extends('layouts.admin')
@section('title','System Settings')
<style>
    /* Settings Page Styles */
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.1);
        --primary-dark: #2a6d5a;
        --primary-soft: rgba(49, 128, 105, 0.05);
        --primary-hover: rgba(49, 128, 105, 0.15);
    }

    .settings-container {
        margin: 0 auto;
    }

    /* Horizontal Tabs */
    .horizontal-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        padding: 0.5rem;
        background: white;
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        overflow-x: auto;
    }

    .horizontal-tab {
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
        border: none;
        background: transparent;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .horizontal-tab:hover {
        background: var(--primary-soft);
        color: var(--primary);
    }

    .horizontal-tab.active {
        background: var(--primary);
        color: white;
        font-weight: 600;
    }

    .tab-icon {
        font-size: 1rem;
    }

    .settings-section {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .settings-section.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .settings-card {
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        background: white;
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .settings-card:hover {
        box-shadow: 0 8px 25px rgba(49, 128, 105, 0.1);
    }

    .settings-card-header {
        background: var(--primary-soft);
        border-bottom: 2px solid var(--primary-light);
        padding: 1rem 1.5rem;
        font-weight: 600;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .settings-card-header-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
    }

    .settings-card-body {
        padding: 1.5rem;
    }

    .form-group-compact {
        margin-bottom: 1.25rem;
    }

    .form-label-compact {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label-compact .form-label-icon {
        color: var(--primary);
    }

    .form-control-compact {
        width: 100%;
        border: 1px solid rgba(49, 128, 105, 0.2);
        border-radius: 8px;
        padding: 0.75rem 0.875rem;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: white;
        height: 38px;
    }

    .form-control-compact:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    textarea.form-control-compact {
        min-height: 100px;
        resize: vertical;
    }

    .form-hint {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.375rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .form-hint i {
        color: var(--primary);
    }

    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
    }

    .payment-method-card {
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 10px;
        padding: 1.25rem;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .payment-method-card:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(49, 128, 105, 0.1);
    }

    .payment-method-card.active {
        border-color: var(--primary);
        background: var(--primary-soft);
    }

    .payment-method-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .payment-method-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1.25rem;
    }

    .payment-method-title {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.25rem;
    }

    .payment-method-status {
        font-size: 0.75rem;
        color: #6b7280;
    }

    .status-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.625rem;
        border-radius: 20px;
        font-weight: 500;
    }

    .status-active {
        background: rgba(49, 128, 105, 0.1);
        color: #065f46;
        border: 1px solid rgba(49, 128, 105, 0.2);
    }

    .status-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #991b1b;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #e5e7eb;
        transition: .4s;
        border-radius: 34px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .toggle-slider {
        background-color: var(--primary);
    }

    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }

    .preview-image {
        width: 120px;
        height: 120px;
        border: 2px dashed rgba(49, 128, 105, 0.3);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-soft);
        color: var(--primary);
        cursor: pointer;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .preview-image:hover {
        border-color: var(--primary);
        background: var(--primary-light);
    }

    .preview-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .action-buttons {
        position: sticky;
        bottom: 1rem;
        background: white;
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-top: 2rem;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    /* Social Media Colors */
    .social-facebook { color: #1877F2; }
    .social-youtube { color: #FF0000; }
    .social-twitter { color: #1DA1F2; }
    .social-linkedin { color: #0A66C2; }
    .social-tiktok { color: #000000; }
    .social-instagram { color: #E4405F; }
    .social-whatsapp { color: #25D366; }

    .password-strength {
        margin-top: 0.5rem;
        height: 4px;
        background: #e5e7eb;
        border-radius: 2px;
        overflow: hidden;
    }

    .strength-meter {
        height: 100%;
        width: 0%;
        transition: all 0.3s ease;
    }

    .strength-weak { background: #dc2626; }
    .strength-medium { background: #f59e0b; }
    .strength-strong { background: #10b981; }

    .strength-text {
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .horizontal-tabs {
            flex-wrap: wrap;
        }
    }
</style>
@section('content')
@php
    $setting = $setting ?? null;
    $extra = $setting->extra_data ?? [];
@endphp

<form method="POST"
      action="{{ route('admin.setting.update') }}"
      enctype="multipart/form-data">
@csrf

<div class="container-fluid">

    {{-- ================= TABS ================= --}}
    <ul class="nav nav-pills mb-3 bg-white p-2 rounded shadow-sm" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" type="button" data-bs-target="#seo">SEO</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" type="button" data-bs-target="#email">Email</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" type="button" data-bs-target="#sms">SMS</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" type="button" data-bs-target="#payment">Payment</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" type="button" data-bs-target="#social">Social</button></li>
    </ul>

    <div class="tab-content">

        {{-- ================= SEO ================= --}}
        <div class="tab-pane fade show active" id="seo">
            <div class="card mb-3">
                <div class="card-header fw-bold">SEO Settings</div>
                <div class="card-body row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Meta Title</label>
                        <input class="form-control" name="meta_title" value="{{ $setting->meta_title ?? '' }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Meta Keywords</label>
                        <input class="form-control" name="keywords" value="{{ $setting->keywords ?? '' }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Meta Description</label>
                        <textarea class="form-control" name="meta_description">{{ $setting->meta_description ?? '' }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">OG Image</label>
                        <input type="file" class="form-control" name="ogimage">
                        @if(!empty($setting?->ogimage))
                            <img src="{{ url($setting->ogimage) }}" class="mt-2" height="70">
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- ================= EMAIL ================= --}}
        <div class="tab-pane fade" id="email">
            <div class="card mb-3">
                <div class="card-header fw-bold">Email Settings</div>
                <div class="card-body row g-3">

                    <div class="col-md-4">
                        <label class="form-label">From Email</label>
                        <input class="form-control" name="mail_from" value="{{ $extra['email']['from'] ?? '' }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Sender Name</label>
                        <input class="form-control" name="mail_sender_name" value="{{ $extra['email']['sender'] ?? '' }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">SMTP Host</label>
                        <input class="form-control" name="smtp_host" value="{{ $extra['email']['host'] ?? '' }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Port</label>
                        <input class="form-control" name="smtp_port" value="{{ $extra['email']['port'] ?? '' }}">
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">Username</label>
                        <input class="form-control" name="smtp_username" value="{{ $extra['email']['username'] ?? '' }}">
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">Password</label>
                        <input class="form-control" name="smtp_password" type="password">
                    </div>

                </div>
            </div>
        </div>

        {{-- ================= SMS ================= --}}
        <div class="tab-pane fade" id="sms">
            <div class="card mb-3">
                <div class="card-header fw-bold">SMS Settings</div>
                <div class="card-body row g-3">

                    <div class="col-md-4">
                        <label class="form-label">SMS Provider</label>
                        <select class="form-select" name="sms_provider">
                            <option value="sslwireless" @selected(($extra['sms']['provider'] ?? '')=='sslwireless')>SSL Wireless</option>
                            <option value="revesms" @selected(($extra['sms']['provider'] ?? '')=='revesms')>Reve SMS</option>
                            <option value="twilio" @selected(($extra['sms']['provider'] ?? '')=='twilio')>Twilio</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">API Key</label>
                        <input class="form-control" name="sms_api_key" value="{{ $extra['sms']['api_key'] ?? '' }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Sender ID</label>
                        <input class="form-control" name="sms_sender_id" value="{{ $extra['sms']['sender_id'] ?? '' }}">
                    </div>

                    <div class="col-md-12">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="sms_enabled" value="1"
                                   @checked($extra['sms']['enabled'] ?? false)>
                            <label class="form-check-label">Enable SMS</label>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ================= PAYMENT ================= --}}
        <div class="tab-pane fade" id="payment">
            <div class="card mb-3">
                <div class="card-header fw-bold">Payment Gateways</div>
                <div class="card-body">

                    {{-- SSLCOMMERZ --}}
                    <div class="border rounded p-3 mb-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="sslcommerz_enabled" value="1"
                                   @checked($extra['payment']['sslcommerz']['enabled'] ?? false)>
                            <label class="form-check-label fw-bold">SSLCOMMERZ</label>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <input class="form-control" name="sslcommerz_store_id"
                                       placeholder="Store ID"
                                       value="{{ $extra['payment']['sslcommerz']['store_id'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <input class="form-control" name="sslcommerz_secret"
                                       placeholder="Secret Key"
                                       value="{{ $extra['payment']['sslcommerz']['secret'] ?? '' }}">
                            </div>
                        </div>
                    </div>

                    {{-- STRIPE --}}
                    <div class="border rounded p-3 mb-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="stripe_enabled" value="1"
                                   @checked($extra['payment']['stripe']['enabled'] ?? false)>
                            <label class="form-check-label fw-bold">Stripe</label>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <input class="form-control" name="stripe_key"
                                       placeholder="Publishable Key"
                                       value="{{ $extra['payment']['stripe']['key'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <input class="form-control" name="stripe_secret"
                                       placeholder="Secret Key"
                                       value="{{ $extra['payment']['stripe']['secret'] ?? '' }}">
                            </div>
                        </div>
                    </div>

                    {{-- BKASH --}}
                    <div class="border rounded p-3 mb-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="bkash_enabled" value="1"
                                   @checked($extra['payment']['bkash']['enabled'] ?? false)>
                            <label class="form-check-label fw-bold">bKash</label>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <input class="form-control" name="bkash_app_key"
                                       placeholder="App Key"
                                       value="{{ $extra['payment']['bkash']['app_key'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <input class="form-control" name="bkash_secret"
                                       placeholder="Secret"
                                       value="{{ $extra['payment']['bkash']['secret'] ?? '' }}">
                            </div>
                        </div>
                    </div>

                    {{-- PAYPAL --}}
                    <div class="border rounded p-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="paypal_enabled" value="1"
                                   @checked($extra['payment']['paypal']['enabled'] ?? false)>
                            <label class="form-check-label fw-bold">PayPal</label>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <input class="form-control" name="paypal_client_id"
                                       placeholder="Client ID"
                                       value="{{ $extra['payment']['paypal']['client_id'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <input class="form-control" name="paypal_secret"
                                       placeholder="Secret"
                                       value="{{ $extra['payment']['paypal']['secret'] ?? '' }}">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ================= SOCIAL ================= --}}
        <div class="tab-pane fade" id="social">
            <div class="card mb-3">
                <div class="card-header fw-bold">Social Media</div>
                <div class="card-body row g-3">
                    <input class="form-control" name="facebook_url" placeholder="Facebook" value="{{ $setting->facebook_url ?? '' }}">
                    <input class="form-control" name="youtube_url" placeholder="YouTube" value="{{ $setting->youtube_url ?? '' }}">
                    <input class="form-control" name="instagram_url" placeholder="Instagram" value="{{ $setting->instagram_url ?? '' }}">
                    <input class="form-control" name="linkedin_url" placeholder="LinkedIn" value="{{ $setting->linkedin_url ?? '' }}">
                </div>
            </div>
        </div>

    </div>

    <div class="text-end mt-3">
        <button class="btn btn-primary px-4">Save Settings</button>
    </div>

</div>
</form>
@endsection

