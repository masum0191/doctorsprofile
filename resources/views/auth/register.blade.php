@extends('layouts.forntend')
@section('title', 'নিবন্ধন ফর্ম')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-primary rounded-lg shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 text-center bengali text-white">ঈশ্বরগঞ্জ পৌরসভা - রেজিস্টার ফর্ম</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" id="registrationForm">
                        @csrf

                        <!-- Form Notice -->
                        <div class="alert alert-info bengali">
                            <strong>দ্রষ্টব্য:</strong> সকল ফিল্ড পূরণ করা বাধ্যতামূলক। সঠিক তথ্য প্রদান করুন।
                        </div>

                        <div class="row g-3">
                            <!-- Name Field -->
                            <div class="col-md-6">
                                <label for="name" class="form-label bengali">নাম <span class="text-danger">*</span></label>
                                <input type="text" class="form-control bengali" id="name" name="name" required
                                       placeholder="আপনার পূর্ণ নাম লিখুন">
                                @error('name')
                                    <div class="invalid-feedback d-block bengali">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="col-md-6">
                                <label for="email" class="form-label bengali">ইমেইল <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required
                                       placeholder="example@domain.com">
                                @error('email')
                                    <div class="invalid-feedback d-block bengali">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- NID Field -->
                            <div class="col-md-6">
                                <label for="nid" class="form-label bengali">জাতীয় পরিচয়পত্র নম্বর (NID) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control bengali" id="nid" name="nid" required
                                       placeholder="১৭ বা ১৩ ডিজিটের NID নম্বর">
                                <small class="text-muted bengali">শুধুমাত্র সংখ্যা লিখুন</small>
                                @error('nid')
                                    <div class="invalid-feedback d-block bengali">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mobile Field -->
                            <div class="col-md-6">
                                <label for="mobile" class="form-label bengali">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">+৮৮০</span>
                                    <input type="number" class="form-control bengali" id="mobile" name="mobile" required
                                           placeholder="১XXXXXXXXX">
                                </div>
                                @error('mobile')
                                    <div class="invalid-feedback d-block bengali">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Account Type Field -->
                            <div class="col-md-12">
                                <label class="form-label bengali">অ্যাকাউন্ট টাইপ <span class="text-danger">*</span></label>
                                <div class="border rounded p-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="account_type" id="individual" value="individual" checked>
                                        <label class="form-check-label bengali" for="individual">
                                            ব্যক্তি (ব্যক্তিগত ব্যবহার)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="account_type" id="institutional" value="institutional">
                                        <label class="form-check-label bengali" for="institutional">
                                            প্রাতিষ্ঠানিক (সংস্থা/প্রতিষ্ঠান)
                                        </label>
                                    </div>
                                </div>
                                @error('account_type')
                                    <div class="invalid-feedback d-block bengali">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="col-md-6">
                                <label for="password" class="form-label bengali">পাসওয়ার্ড <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required
                                       placeholder="অন্তত ৮ ক্যারেক্টার">
                                @error('password')
                                    <div class="invalid-feedback d-block bengali">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label bengali">পাসওয়ার্ড নিশ্চিত করুন <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required
                                       placeholder="পাসওয়ার্ড পুনরায় লিখুন">
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label bengali" for="terms" style="font-size: 15px">
                                        আমি <a href="#" class="text-primary">শর্তাবলী</a> এবং <a href="#" class="text-primary">গোপনীয়তা নীতি</a> পড়েছি এবং সম্মত হয়েছি
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg bengali py-2">
                                <i class="fas fa-user-plus me-2"></i> নিবন্ধন করুন
                            </button>
                        </div>

                        <!-- Login Link -->
                        <div class="text-center mt-3">
                            <p class="mb-0 bengali">ইতিমধ্যে একটি অ্যাকাউন্ট আছে? <a href="{{ route('login') }}" class="text-primary">লগইন করুন</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const progressBar = document.querySelector('.progress-bar');
    const strengthText = document.querySelector('.strength-text');

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            // Check length
            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;

            // Check for mixed case
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 1;

            // Check for numbers
            if (/\d/.test(password)) strength += 1;

            // Check for special chars
            if (/[^a-zA-Z0-9]/.test(password)) strength += 1;

            // Update UI
            let width = 0;
            let text = 'দুর্বল';
            let color = 'bg-danger';

            if (strength > 3) {
                width = 100;
                text = 'শক্তিশালী';
                color = 'bg-success';
            } else if (strength > 1) {
                width = 66;
                text = 'মধ্যম';
                color = 'bg-warning';
            } else if (password.length > 0) {
                width = 33;
                text = 'দুর্বল';
                color = 'bg-danger';
            }

            progressBar.style.width = width + '%';
            progressBar.className = 'progress-bar ' + color;
            strengthText.textContent = text;
        });
    }

    // NID number validation (Bangladeshi format)
    const nidInput = document.getElementById('nid');
    if (nidInput) {
        nidInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    }

    // Mobile number validation
    const mobileInput = document.getElementById('mobile');
    if (mobileInput) {
        mobileInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length > 10) {
                this.value = this.value.substring(0, 10);
            }
        });
    }
});
</script>
@endpush

@push('styles')
<style>
    .bengali {
        font-family: 'Noto Sans Bengali', 'SolaimanLipi', 'Siyam Rupali', Arial, sans-serif;
    }

    .card {
        border-width: 2px;
    }

    .card-header {
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .form-control, .form-select, .input-group-text {
        border-radius: 0.25rem !important;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .input-group-text {
        background-color: #e9ecef;
        color: #495057;
        font-weight: 500;
    }

    .progress {
        background-color: #e9ecef;
    }

    .progress-bar {
        transition: width 0.3s ease, background-color 0.3s ease;
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }

    @media (max-width: 768px) {
        .card {
            border-width: 1px;
        }

        .card-header {
            font-size: 1.1rem;
        }
    }
</style>
@endpush
