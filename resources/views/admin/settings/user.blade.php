@extends('layouts.admin')
@section('title', 'ব্যবহারকারী সেটিংস')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header with Gradient Background -->
            <div class="card mb-4 border-0 shadow">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h4 class="mb-0 text-center bengali text-white">
                        <i class="fas fa-user-cog me-2"></i> ব্যবহারকারী সেটিংস
                    </h4>
                </div>
            </div>
            
            <!-- Success Message -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show bengali shadow-sm">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            <div class="row g-4">
                <!-- Profile Update Section -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 bengali">
                                <i class="fas fa-user-edit me-2"></i> প্রোফাইল তথ্য
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.settings.profile.update') }}">
                             @csrf
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label bengali">নাম <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control bengali border-primary" id="name" name="name" value="{{$user->name}}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label bengali">ইমেইল <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control border-primary" id="email" name="email" value="{{$user->email}}" required>
                                </div>
                                
                                <!-- Non-editable Mobile Field -->
                                <div class="mb-3">
                                    <label class="form-label bengali">মোবাইল নম্বর</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light bengali">+৮৮০</span>
                                        <input type="text" class="form-control bengali bg-light" value="{{$user->mobile}}" readonly>
                                    </div>
                                    <small class="text-muted bengali">পরিবর্তনের জন্য অ্যাডমিনের সাথে যোগাযোগ করুন</small>
                                </div>
                                
                                <!-- Non-editable NID Field -->
                                <div class="mb-4">
                                    <label class="form-label bengali">জাতীয় পরিচয়পত্র নম্বর (NID)</label>
                                    <input type="text" class="form-control bengali bg-light" value="{{$user->nid}}" readonly>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary bengali py-2">
                                        <i class="fas fa-save me-2"></i> প্রোফাইল আপডেট করুন
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Password Change Section -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 bengali">
                                <i class="fas fa-lock me-2"></i> পাসওয়ার্ড পরিবর্তন
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.settings.password.update') }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="current_password" class="form-label bengali">বর্তমান পাসওয়ার্ড <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control border-warning" id="current_password" name="current_password" required>
                                        <button class="btn btn-outline-warning toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="new_password" class="form-label bengali">নতুন পাসওয়ার্ড <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control border-warning" id="new_password" name="new_password" required>
                                        <button class="btn btn-outline-warning toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="new_password_confirmation" class="form-label bengali">পাসওয়ার্ড নিশ্চিত করুন <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control border-warning" id="new_password_confirmation" name="new_password_confirmation" required>
                                        <button class="btn btn-outline-warning toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-warning bengali py-2">
                                        <i class="fas fa-key me-2"></i> পাসওয়ার্ড পরিবর্তন করুন
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Account Information Section -->
                <div class="col-12 mt-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 bengali text-info">
                                <i class="fas fa-info-circle me-2"></i> অ্যাকাউন্ট তথ্য
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 border rounded text-center bg-light">
                                        <h6 class="bengali text-muted mb-2">অ্যাকাউন্ট টাইপ</h6>
                                        <p class="mb-0 bengali text-dark fw-bold">@if($user->acount_type=='institutional')
                                            <i class="fas fa-user-shield me-1 text-primary"></i> ব্যবসায়ী
                                            @else
                                            <i class="fas fa-user-tie me-1 text-secondary"></i> ব্যক্তি
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="p-3 border rounded text-center bg-light">
                                        <h6 class="bengali text-muted mb-2">নিবন্ধনের তারিখ</h6>
                                        <p class="mb-0 bengali text-dark fw-bold">{{$user->created_at}}</p>
                                    </div>
                                </div>
                                
                                {{--<div class="col-md-3">
                                    <div class="p-3 border rounded text-center bg-light">
                                        <h6 class="bengali text-muted mb-2">সর্বশেষ লগইন</h6>
                                        <p class="mb-0 bengali text-dark fw-bold">১৫ জুন, ২০২৩</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="p-3 border rounded text-center bg-light">
                                        <h6 class="bengali text-muted mb-2">স্ট্যাটাস</h6>
                                        <p class="mb-0 bengali text-success fw-bold">
                                            <i class="fas fa-check-circle me-1"></i> সক্রিয়
                                        </p>
                                    </div>
                                </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    }
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
    }
    .toggle-password {
        border-radius: 0 5px 5px 0;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(function(button) {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });
    });
</script>
@endsection