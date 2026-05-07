@extends('layouts.admin')
@section('title', 'সাইট সেটিংস')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <!-- Page Header with Gradient Background -->
            <div class="card mb-4 border-0">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h4 class="mb-0 text-center bengali text-white">
                        <i class="fas fa-cogs me-2"></i> সাইট সেটিংস
                    </h4>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show bengali shadow-sm">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <!-- Main Tabs Navigation -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <ul class="nav nav-tabs card-header-tabs" id="mainTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active bengali" id="general-tab" data-bs-toggle="tab" href="#general"
                                    role="tab">
                                    <i class="fas fa-cog me-2"></i> সাধারণ
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bengali" id="fees-tab" data-bs-toggle="tab" href="#fees"
                                    role="tab">
                                    <i class="fas fa-money-bill-wave me-2"></i> ফি
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bengali" id="certificate-tab" data-bs-toggle="tab"
                                    href="#certificate" role="tab">
                                    <i class="fas fa-file-certificate me-2"></i> সনদপত্র
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bengali" id="seo-tab" data-bs-toggle="tab" href="#seo"
                                    role="tab">
                                    <i class="fas fa-search me-2"></i> এসইও
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bengali" id="message-tab" data-bs-toggle="tab" href="#message"
                                    role="tab">
                                    <i class="fa-solid fa-message"></i> বাণী
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bengali" id="social-tab" data-bs-toggle="tab" href="#social"
                                    role="tab">
                                    <i class="fas fa-share-alt me-2"></i> সোশ্যাল
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bengali" id="payment-tab" data-bs-toggle="tab" href="#payment"
                                    role="tab">
                                    <i class="fas fa-credit-card me-2"></i> পেমেন্ট
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bengali" id="sms-tab" data-bs-toggle="tab" href="#sms"
                                    role="tab">
                                    এস এম এস
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bengali" id="email-tab" data-bs-toggle="tab" href="#email"
                                    role="tab">
                                    <i class="fas fa-mail me-2"></i> ই-মেইল
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bengali" id="contact-tab" data-bs-toggle="tab" href="#contact" role="tab">
                                    <i class="fas fa-contact me-2"></i> যোগাযোগ
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bengali" id="otherinfo-tab" data-bs-toggle="tab" href="#otherinfo" role="tab">
                                    <i class="fas fa-contact me-2"></i> অন্যান্য তথ্য
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <!-- Tab Content -->
                        <div class="tab-content" id="mainTabsContent">
                            <!-- General Settings Tab -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel">
                                <h5 class="bengali mb-4 border-bottom pb-2">
                                    <i class="fas fa-info-circle me-2"></i> সাধারণ তথ্য
                                </h5>

                                <div class="row g-3">
                                    <!-- Basic Information -->
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-light border-bottom">
                                                <h6 class="mb-0 bengali">মৌলিক তথ্য</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group mb-3">
                                                    <label class="form-label bengali font-weight-bold">সাইটের নাম <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="site_name"
                                                        class="form-control bengali border-primary"
                                                        value="{{ @$setting->site_name }}">
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label
                                                        class="form-label bengali font-weight-bold">ট্যাগলাইন</label>
                                                    <input type="text" name="tagline"
                                                        class="form-control bengali border-primary"
                                                        value="{{ @$setting->tagline }}">
                                                </div>
                                                {{-- about --}}
                                                <div class="form-group mb-3">
                                                    <label class="form-label bengali font-weight-bold">সাইট সম্পর্কে</label>
                                                    <textarea name="about" id="" class="form-control">{{ @$setting->about }}</textarea>
                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-light border-bottom">
                                                <h6 class="mb-0 bengali">যোগাযোগ তথ্য</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group mb-3">
                                                    <label class="form-label bengali font-weight-bold">ইমেইল <span
                                                            class="text-danger">*</span></label>
                                                    <input type="email" name="email"
                                                        class="form-control bengali border-primary"
                                                        value="{{ @$setting->email }}">
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label class="form-label bengali font-weight-bold">ফোন <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="phone"
                                                        class="form-control bengali border-primary"
                                                        value="{{ @$setting->phone }}">
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label class="form-label bengali font-weight-bold">সাইটের নাম (English) <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="site_name_en"
                                                        class="form-control bengali border-primary"
                                                        value="{{ @$setting->site_name_en }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">ঠিকানা</label>
                                            <input type="text" name="address"
                                                class="form-control bengali border-primary"
                                                value="{{ @$setting->address }}">
                                        </div>
                                    </div>
                                    <!-- Logo Section -->
                                    <div class="col-md-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header bg-light border-bottom">
                                                <h6 class="mb-0 bengali">সাইট লোগো</h6>
                                            </div>
                                            <div class="card-body text-center">


                                                @if ($setting->site_logo && file_exists(public_path($setting->site_logo)))
                                                <img src="{{ url($setting->site_logo) }}" class="img-fluid mb-3" style="max-height:150px;">
                                                @else
                                                <div class="bg-light p-5 mb-3 rounded">
                                                    <i class="fas fa-image fa-3x text-muted"></i>
                                                </div>
                                                @endif


                                                <input type="file" name="site_logo"
                                                    class="form-control form-control-file">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header bg-light border-bottom">
                                                <h6 class="mb-0 bengali">সরকারি লোগো</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                @if (@$setting->govt_logo)
                                                <img src="{{ url(@$setting->govt_logo) }}"
                                                    class="img-fluid mb-3" style="max-height: 150px;">
                                                @else
                                                <div class="bg-light p-5 mb-3 rounded">
                                                    <i class="fas fa-image fa-3x text-muted"></i>
                                                </div>
                                                @endif
                                                <input type="file" name="govt_logo"
                                                    class="form-control form-control-file">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header bg-light border-bottom">
                                                <h6 class="mb-0 bengali">ওয়াটার মার্ক</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                @if (@$setting->watermark)
                                                <img src="{{ url(@$setting->watermark) }}"
                                                    class="img-fluid mb-3" style="max-height: 150px;">
                                                @else
                                                <div class="bg-light p-5 mb-3 rounded">
                                                    <i class="fas fa-image fa-3x text-muted"></i>
                                                </div>
                                                @endif
                                                <input type="file" name="watermark"
                                                    class="form-control form-control-file">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Signature Section -->
                                    <div class="col-md-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header bg-light border-bottom">
                                                <h6 class="mb-0 bengali">অনুমোদনকারী স্বাক্ষর</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                @if (@$setting->approving_officer_signature)
                                                <img src="{{ url(@$setting->approving_officer_signature) }}"
                                                    class="img-fluid mb-3" style="max-height: 150px;">
                                                @else
                                                <div class="bg-light p-5 mb-3 rounded">
                                                    <i class="fas fa-signature fa-3x text-muted"></i>
                                                </div>
                                                @endif
                                                <input type="file" name="approving_officer_signature"
                                                    class="form-control-file">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header bg-light border-bottom">
                                                <h6 class="mb-0 bengali">সচিবের স্বাক্ষর</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                @if (@$setting->secretary_officer_signature)
                                                <img src="{{ url(@$setting->secretary_officer_signature) }}"
                                                    class="img-fluid mb-3" style="max-height: 150px;">
                                                @else
                                                <div class="bg-light p-5 mb-3 rounded">
                                                    <i class="fas fa-signature fa-3x text-muted"></i>
                                                </div>
                                                @endif
                                                <input type="file" name="secretary_officer_signature"
                                                    class="form-control                      form-control-file">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header bg-light border-bottom">
                                                <h6 class="mb-0 bengali">চেয়ারম্যানের স্বাক্ষর</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                @if (@$setting->chairman_signature)
                                                <img src="{{ url(@$setting->chairman_signature) }}"
                                                    class="img-fluid mb-3" style="max-height: 150px;">
                                                @else
                                                <div class="bg-light p-5 mb-3 rounded">
                                                    <i class="fas fa-signature fa-3x text-muted"></i>
                                                </div>
                                                @endif
                                                <input type="file" name="chairman_signature"
                                                    class="form-control form-control-file">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">ওয়ার্কিং টাইম <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="worktime" id="" class="form-control">{{ @$setting->worktime }}</textarea>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">ফুটার টেক্সট</label>

                                            <textarea name="footer_text" id="" class="form-control">{{ @$setting->footer_text }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">Latitude <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="latitude"
                                                class="form-control bengali border-primary"
                                                value="{{ @$setting->latitude }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">Longitude <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="longitude"
                                                class="form-control bengali border-primary"
                                                value="{{ @$setting->longitude }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fees Settings Tab -->
                            <div class="tab-pane fade" id="fees" role="tabpanel">
                                <h5 class="bengali text-primary mb-4 border-bottom pb-2">
                                    <i class="fas fa-money-bill-wave me-2"></i> ফি সেটিংস
                                </h5>

                                <div class="row g-3">


                                    <!-- License and Renewal Fees -->
                                    <div class="col-md-12 mb-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header bg-light border-bottom">
                                                <h6 class="mb-0 bengali">লাইসেন্স ফি</h6>
                                            </div>
                                            <div class="card-body">
                                                {{-- <div class="form-group mb-3">
                                                        <label class="form-label bengali font-weight-bold">
                                                            ফি</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bengali">৳</span>
                                                            <input type="number" name="trade_license_fee"
                                                                class="form-control bengali border-primary"
                                                                value="{{ @$setting->trade_license_fee }}" min="0">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">নবায়ন
                                                ফি</label>
                                            <div class="input-group">
                                                <span class="input-group-text bengali">৳</span>
                                                <input type="number" name="renufee"
                                                    class="form-control bengali border-primary"
                                                    value="{{ @$setting->renufee }}" min="0">
                                            </div>
                                        </div> --}}
                                        {{-- সার্চ চার্জ  --}}

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">সার্চ চার্জ
                                                ফি</label>
                                            <div class="input-group">
                                                <span class="input-group-text bengali">৳</span>
                                                <input type="number" name="sach_fee"
                                                    class="form-control bengali border-primary"
                                                    value="{{ @$setting->sach_fee }}" min="0">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label bengali font-weight-bold">আয়কর
                                                ফি</label>
                                            <div class="input-group">
                                                <span class="input-group-text bengali">৳</span>
                                                <input type="number" name="aikor"
                                                    class="form-control bengali border-primary"
                                                    value="{{ @$setting->aikor }}"
                                                    min="0">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label bengali font-weight-bold">সাইনবোর্ড কর

                                                ফি</label>
                                            <div class="input-group">
                                                <span class="input-group-text bengali">৳</span>
                                                <input type="number" name="sindeboard_fee"
                                                    class="form-control bengali border-primary"
                                                    value="{{ @$setting->sindeboard_fee }}"
                                                    min="0">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label bengali font-weight-bold">ভ্যাট
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bengali">%</span>
                                                <input type="number" name="vat"
                                                    class="form-control bengali border-primary"
                                                    value="{{ @$setting->vat }}"
                                                    min="0">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label bengali font-weight-bold">পেশা ও বাণিজ্যিক কর

                                                ফি</label>
                                            <div class="input-group">
                                                <span class="input-group-text bengali">৳</span>
                                                <input type="number" name="ocation_trade_fee"
                                                    class="form-control bengali border-primary"
                                                    value="{{ @$setting->ocation_trade_fee }}"
                                                    min="0">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label bengali font-weight-bold">সংশোধনী
                                                ফি</label>
                                            <div class="input-group">
                                                <span class="input-group-text bengali">৳</span>
                                                <input type="number" name="correction_fee"
                                                    class="form-control bengali border-primary"
                                                    value="{{ @$setting->correction_fee }}"
                                                    min="0">
                                            </div>
                                        </div>
                                        {{-- অন্যান্য --}}
                                        <div class="form-group">
                                            <label class="form-label bengali font-weight-bold">অন্যান্য
                                                ফি</label>
                                            <div class="input-group">
                                                <span class="input-group-text bengali">৳</span>
                                                <input type="number" name="other_fee"
                                                    class="form-control bengali border-primary"
                                                    value="{{ @$setting->other_fee }}"
                                                    min="0">
                                            </div>
                                        </div>
                                        {{-- সার্ভিস --}}
                                        <div class="form-group">
                                            <label class="form-label bengali font-weight-bold">সার্ভিস
                                                ফি</label>
                                            <div class="input-group">
                                                <span class="input-group-text bengali">৳</span>
                                                <input type="number" name="service_fee"
                                                    class="form-control bengali border-primary"
                                                    value="{{ @$setting->service_fee }}"
                                                    min="0">
                                            </div>
                                        </div>
                                        {{-- বসত ভিটা ফি ? --}}
                                        <div class="form-group">
                                            <label class="form-label bengali font-weight-bold">বসত ভিটা
                                                ফি</label>
                                            <div class="input-group">
                                                <span class="input-group-text bengali">৳</span>
                                                <input type="number" name="live_fee"
                                                    class="form-control bengali border-primary"
                                                    value="{{ @$setting->live_fee }}"
                                                    min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="row">
                            <input type="hidden" name="fee" value="{{ @$fee->id }}">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">ভিজিএফ কার্ড ফি <span class="text-danger">*</span></label>
                                    <input name="vdf_fee" type="number" class="form-control"
                                        value="{{ old('vdf_fee', @$fee->vdf_fee ?? 0) }}" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">সার্ভিস চার্জ (%) <span class="text-danger">*</span></label>
                                    <input name="service_charge" type="number" class="form-control"
                                        value="{{ old('service_charge', @$fee->service_charge ?? 0) }}" min="0" max="100">
                                </div>
                            </div>

                            <!-- Death Certificate -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">মৃত্যু সনদ ফি <span class="text-danger">*</span></label>
                                    <input name="detention_fee" type="number" class="form-control"
                                        value="{{ old('detention_fee', @$fee->detention_fee ?? 0) }}" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">সার্ভিস চার্জ (%) <span class="text-danger">*</span></label>
                                    <input name="detention_service_charge" type="number" class="form-control"
                                        value="{{ old('detention_service_charge', @$fee->detention_service_charge ?? 0) }}" min="0" max="100">
                                </div>
                            </div>

                            <!-- Citizenship Certificate -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">নাগরিকত্ব সনদ ফি <span class="text-danger">*</span></label>
                                    <input name="citizen_fee" type="number" class="form-control"
                                        value="{{ old('citizen_fee', @$fee->citizen_fee ?? 0) }}" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">সার্ভিস চার্জ (%) <span class="text-danger">*</span></label>
                                    <input name="citezent_service_charge" type="number" class="form-control"
                                        value="{{ old('citezent_service_charge', @$fee->citezent_service_charge ?? 0) }}" min="0" max="100">
                                </div>
                            </div>

                            <!-- Road Permit -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">রোড পারমিট ফি <span class="text-danger">*</span></label>
                                    <input name="road_permit_fee" type="number" class="form-control"
                                        value="{{ old('road_permit_fee', @$fee->road_permit_fee ?? 0) }}" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">সার্ভিস চার্জ (%) <span class="text-danger">*</span></label>
                                    <input name="rode_permit_service_charge" type="number" class="form-control"
                                        value="{{ old('rode_permit_service_charge', @$fee->rode_permit_service_charge ?? 0) }}" min="0" max="100">
                                </div>
                            </div>

                            <!-- Construction Application -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">নির্মাণ কাজের আবেদন ফি <span class="text-danger">*</span></label>
                                    <input name="constartion_fee" type="number" class="form-control"
                                        value="{{ old('constartion_fee', @$fee->constartion_fee ?? 0) }}" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">সার্ভিস চার্জ (%) <span class="text-danger">*</span></label>
                                    <input name="constartion_service_charge" type="number" class="form-control"
                                        value="{{ old('constartion_service_charge', @$fee->constartion_service_charge ?? 0) }}" min="0" max="100">
                                </div>
                            </div>

                            <!-- Land Clearance -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">জমি ক্লিয়ারেন্স ফি <span class="text-danger">*</span></label>
                                    <input name="land_clearance_fee" type="number" class="form-control"
                                        value="{{ old('land_clearance_fee', @$fee->land_clearance_fee ?? 0) }}" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">সার্ভিস চার্জ (%) <span class="text-danger">*</span></label>
                                    <input name="land_clearance_service_charge" type="number" class="form-control"
                                        value="{{ old('land_clearance_service_charge', @$fee->land_clearance_service_charge ?? 0) }}" min="0" max="100">
                                </div>
                            </div>

                            <!-- Family Certificate -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">পারিবারিক সনদ ফি <span class="text-danger">*</span></label>
                                    <input name="family_fee" type="number" class="form-control"
                                        value="{{ old('family_fee', @$fee->family_fee ?? 0) }}" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">সার্ভিস চার্জ (%) <span class="text-danger">*</span></label>
                                    <input name="family_service_charge" type="number" class="form-control"
                                        value="{{ old('family_service_charge', @$fee->family_service_charge ?? 0) }}" min="0" max="100">
                                </div>
                            </div>

                            <!-- Monthly Income Certificate -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">মাসিক আয়ের সনদ ফি <span class="text-danger">*</span></label>
                                    <input name="monthly_income_fee" type="number" class="form-control"
                                        value="{{ old('monthly_income_fee', @$fee->monthly_income_fee ?? 0) }}" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">সার্ভিস চার্জ (%) <span class="text-danger">*</span></label>
                                    <input name="monthly_income_service_charge" type="number" class="form-control"
                                        value="{{ old('monthly_income_service_charge', @$fee->monthly_income_service_charge ?? 0) }}" min="0" max="100">
                                </div>
                            </div>
                            {{-- বার্ষিক আয়ের প্রত্যয়ন --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> বার্ষিক আয়ের প্রত্যয়ন ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="yearly_income_fee" type="number" class="form-control" value="{{old('yearly_income_fee', @$fee->yearly_income_fee ?? 0)}}" min="0">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="yearly_income_service_charge" type="number" class="form-control"
                                        value="{{old('yearly_income_service_charge', @$fee->yearly_income_service_charge ?? 0)}}" min="0">

                                </div>
                            </div>
                            {{-- বিবাহিত সনদ --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> বিবাহিত সনদ ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="marrige_fee" type="number" class="form-control"
                                        value="{{old('marrige_fee', @$fee->marrige_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="marrige_service_charge" type="number" id="" class="form-control"
                                        value="{{old('marrige_service_charge', @$fee->marrige_service_charge ?? 0)}}">

                                </div>
                            </div>
                            {{-- অবিবাহিত সনদ --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> অবিবাহিত সনদ ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="unmarrige_fee" type="number" class="form-control"
                                        value="{{old('unmarrige_fee', @$fee->unmarrige_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="unmarrige_service_charge" type="number" class="form-control"
                                        value="{{old('unmarrige_service_charge', @$fee->unmarrige_service_charge ?? 0)}}">

                                </div>
                            </div>
                            {{-- দ্বিতীয় বিবাহের অনুমতি পত্র --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> দ্বিতীয় বিবাহের অনুমতি পত্র ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="secound_fee" type="number" class="form-control"
                                        value="{{old('secound_fee', @$fee->secound_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="secound_service_charge" type="number" class="form-control"
                                        value="{{old('secound_service_charge', @$fee->secound_service_charge ?? 0)}}">

                                </div>
                            </div>
                            {{-- বিবিধ প্রত্যয়নপত্র --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> বিবিধ প্রত্যয়নপত্র ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="bibodo_fee" type="number" class="form-control"
                                        value="{{old('bibodo_fee', @$fee->bibodo_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="bibodo_service_charge" type="number" class="form-control"
                                        value="{{old('bibodo_service_charge', @$fee->bibodo_service_charge ?? 0)}}">

                                </div>
                            </div>
                            {{-- চারিত্রিক সনদ --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> চারিত্রিক সনদ ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="charecter_fee" type="number" class="form-control"
                                        value="{{old('charecter_fee', @$fee->charecter_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="charecter_service_charge" type="number" class="form-control"
                                        value="{{old('charecter_service_charge', @$fee->charecter_service_charge ?? 0)}}">

                                </div>
                            </div>
                            {{-- প্রতিবন্ধী সনদপত্র --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> প্রতিবন্ধী সনদপত্র ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="disability_fee" type="number" class="form-control"
                                        value="{{old('disability_fee', @$fee->disability_fee ?? 0)}}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="disability_service_charge" type="number" class="form-control"
                                        value="{{old('disability_service_charge', @$fee->disability_service_charge ?? 0)}}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> অনাপত্তি সনদপত্র ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="no_objection_fee" type="number" class="form-control"
                                        value="{{old('no_objection_fee', @$fee->no_objection_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="no_objection_service_charge" type="number" class="form-control"
                                        value="{{old('no_objection_service_charge', @$fee->no_objection_service_charge ?? 0)}}">

                                </div>
                            </div>
                            {{-- আর্থিক অস্বচ্ছলতার সনদপত্র --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> আর্থিক অস্বচ্ছলতার সনদপত্র ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="financial_insolvency_fee" type="number" class="form-control"
                                        value="{{old('financial_insolvency_fee', @$fee->financial_insolvency_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="financial_insolvency_service_charge" type="number" class="form-control"
                                        value="{{old('financial_insolvency_service_charge', @$fee->financial_insolvency_service_charge ?? 0)}}">

                                </div>
                            </div>
                            {{-- নতুন ভোটারের প্রত্যয়ন পত্র --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> নতুন ভোটারের প্রত্যয়ন পত্র ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="new_voter_fee" type="number" class="form-control"
                                        value="{{old('new_voter_fee', @$fee->new_voter_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="new_voter_service_charge" type="number" class="form-control"
                                        value="{{old('new_voter_service_charge', @$fee->new_voter_service_charge ?? 0)}}">

                                </div>
                            </div>
                            {{-- ভোটার স্থানান্তরের প্রত্যয়ন পত্র --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> ভোটার স্থানান্তরের প্রত্যয়ন পত্র ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="voter_transfer_fee" type="number" class="form-control"
                                        value="{{old('voter_transfer_fee', @$fee->voter_transfer_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="voter_transfer_service_charge" type="number" class="form-control"
                                        value="{{old('voter_transfer_service_charge', @$fee->voter_transfer_service_charge ?? 0)}}">

                                </div>
                            </div>
                            {{-- বেকারত্বের সনদপত্র --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> বেকারত্বের সনদপত্র ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="unemployment_fee" type="number" class="form-control"
                                        value="{{old('unemployment_fee', @$fee->unemployment_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="unemployment_service_charge" type="number" class="form-control"
                                        value="{{old('unemployment_service_charge', @$fee->unemployment_service_charge ?? 0)}}">

                                </div>
                            </div>
                            {{-- অস্থায়ীভাবে বসবাসের প্রত্যয়ন পত্র --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> অস্থায়ীভাবে বসবাসের প্রত্যয়ন পত্র ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="temporary_residence_fee" type="number" class="form-control"
                                        value="{{old('temporary_residence_fee', @$fee->temporary_residence_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="temporary_residence_service_charge" type="number" class="form-control"
                                        value="{{old('temporary_residence_service_charge', @$fee->temporary_residence_service_charge ?? 0)}}">

                                </div>
                            </div>
                            {{-- জাতীয়তা সনদ --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> জাতীয়তা সনদ ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="nationality_fee" type="number" class="form-control"
                                        value="{{old('nationality_fee', @$fee->nationality_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="nationality_service_charge" type="number" class="form-control"
                                        value="{{old('nationality_service_charge', @$fee->nationality_service_charge ?? 0)}}">

                                </div>
                            </div>
                            {{-- স্থায়ী বাসিন্দা সনদ --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> স্থায়ী বাসিন্দা সনদ ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="permanent_resident_fee" type="number" class="form-control"
                                        value="{{old('permanent_resident_fee', @$fee->permanent_resident_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="permanent_resident_service_charge" type="number" class="form-control"
                                        value="{{old('permanent_resident_service_charge', @$fee->permanent_resident_service_charge ?? 0)}}">

                                </div>
                            </div>
                            {{-- মাতৃত্যকালীন সনদ --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> মাতৃত্যকালীন সনদ ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="maternity_fee" type="number" class="form-control"
                                        value="{{old('maternity_fee', @$fee->maternity_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="maternity_service_charge" type="number" class="form-control"
                                        value="{{old('maternity_service_charge', @$fee->maternity_service_charge ?? 0)}}">

                                </div>
                            </div>
                            {{-- বিধবা ভাতা সনদ --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> বিধবা ভাতা সনদ ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="widow_allowance_fee" type="number" class="form-control"
                                        value="{{old('widow_allowance_fee', @$fee->widow_allowance_fee ?? 0)}}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="widow_allowance_service_charge" type="number" class="form-control"
                                        value="{{old('widow_allowance_service_charge', @$fee->widow_allowance_service_charge ?? 0)}}">

                                </div>
                            </div>
                            {{-- বয়স্ক ভাতা সনদ --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> বয়স্ক ভাতা সনদ ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="elderly_allowance_fee" type="number" class="form-control"
                                        value="{{old('elderly_allowance_fee', @$fee->elderly_allowance_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="elderly_allowance_service_charge" type="number" class="form-control"
                                        value="{{old('elderly_allowance_service_charge', @$fee->elderly_allowance_service_charge ?? 0)}}">

                                </div>
                            </div>
                            {{-- অতিদরিদ্রদের --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> অতিদরিদ্রদের সনদ ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="employment_poor_fee" type="number" class="form-control"
                                        value="{{old('employment_poor_fee', @$fee->employment_poor_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="employment_poor_service_charge" type="number" class="form-control"
                                        value="{{old('employment_poor_service_charge', @$fee->employment_poor_service_charge ?? 0)}}">

                                </div>
                            </div>
                            {{-- টিআর --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> টিআর সনদ ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="tr_fee" type="number" class="form-control"
                                        value="{{old('tr_fee', @$fee->tr_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="tr_service_charge" type="number" class="form-control"
                                        value="{{old('tr_service_charge', @$fee->tr_service_charge ?? 0)}}">

                                </div>
                            </div>
                            {{-- কাবিখা ভাতা --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> কাবিখা ভাতা ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="kaba_allowance_fee" type="number" class="form-control"
                                        value="{{old('kaba_allowance_fee', @$fee->kaba_allowance_fee ?? 0)}}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="kaba_allowance_service_charge" type="number" class="form-control"
                                        value="{{old('kaba_allowance_service_charge', @$fee->kaba_allowance_service_charge ?? 0)}}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> ভি জি ডি (VGD) ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="vgd_fee" type="number" class="form-control"
                                        value="{{old('vgd_fee', @$fee->vgd_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="vgd_service_charge" type="number" class="form-control"
                                        value="{{old('vgd_service_charge', @$fee->vgd_service_charge ?? 0)}}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> এতিম সনদ ফি <span
                                            class="text-danger">*</span></label>
                                    <input name="orphan_fee" type="number" class="form-control"
                                        value="{{old('orphan_fee', @$fee->orphan_fee ?? 0) }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold"> সার্ভিস চার্জ (%) (%) <span
                                            class="text-danger">*</span></label>
                                    <input name="orphan_service_charge" type="number" class="form-control"
                                        value="{{old('orphan_service_charge', @$fee->orphan_service_charge ?? 0)}}">

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate Text Tab -->
                    <div class="tab-pane fade" id="certificate" role="tabpanel">
                        <h5 class="bengali mb-4 border-bottom pb-2">
                            <i class="fas fa-file-certificate me-2"></i> সনদপত্রের টেক্সট
                        </h5>

                        <!-- Certificate Type Tabs -->
                        <ul class="nav nav-tabs mb-4" id="certificateTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link black-color active bengali" id="family-tab"
                                    data-bs-toggle="tab" href="#family" role="tab">
                                    <i class="fas fa-home me-2"></i> পারিবারিক
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link black-color bengali" id="other-tab" data-bs-toggle="tab"
                                    href="#other" role="tab">
                                    <i class="fas fa-list me-2"></i> অন্যান্য
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content custom-tab p-3 border rounded-bottom"
                            id="certificateTabsContent">
                            <!-- Family Certificate Tab -->
                            <div class="tab-pane fade show active" id="family" role="tabpanel">
                                <div class="form-group mb-4">
                                    <label class="bengal font-weight-bold text-black-50">পারিবারিক সনদ</label>
                                    <textarea name="family" class="form-control summernote bengali" rows="6">{!! @$setting->family !!}</textarea>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label bengali text-black-50 font-weight-bold">মাসিক আয়ের
                                        সনদ</label>
                                    <textarea name="income" class="form-control summernote bengali" rows="6">{!! @$setting->income !!}</textarea>
                                </div>

                                <div class="form-group">
                                    <label class="form-label bengali text-black-50 font-weight-bold">বার্ষিক
                                        আয়ের প্রত্যয়ন</label>
                                    <textarea name="anual_income" class="form-control summernote bengali" rows="6">{!! @$setting->anual_income !!}</textarea>
                                </div>
                            </div>

                            <!-- Other Certificate Tab -->
                            <div class="tab-pane fade" id="other" role="tabpanel">
                                <div class="form-group mb-4">
                                    <label class="form-label bengali text-black-50 font-weight-bold">বিবিধ
                                        প্রত্যয়নপত্র</label>
                                    <textarea name="bibidh" class="form-control summernote bengali" rows="6">{!! @$setting->bibidh !!}</textarea>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label text-black-50 bengali font-weight-bold">চারিত্রিক
                                        সনদ</label>
                                    <textarea name="character_certificate" class="form-control summernote bengali" rows="6">{!! @$setting->character_certificate !!}</textarea>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label text-black-50 bengali font-weight-bold">ভূমিহীন
                                        সনদ</label>
                                    <textarea name="landless" class="form-control summernote bengali" rows="6">{!! @$setting->landless !!}</textarea>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label text-black-50 bengali font-weight-bold">প্রতিবন্ধী
                                        সনদপত্র</label>
                                    <textarea name="disabled" class="form-control summernote bengali" rows="6">{!! @$setting->disabled !!}</textarea>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label text-black-50 bengali font-weight-bold">অনাপত্তি
                                        সনদপত্র</label>
                                    <textarea name="no_objection" class="form-control summernote bengali" rows="6">{!! @$setting->no_objection !!}</textarea>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label text-black-50 bengali font-weight-bold">আর্থিক
                                        অস্বচ্ছলতার সনদপত্র</label>
                                    <textarea name="financial_insolvency" class="form-control summernote bengali" rows="6">{!! @$setting->financial_insolvency !!}</textarea>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label  text-black-50 bengali font-weight-bold">নতুন
                                        ভোটারের প্রত্যয়ন পত্র</label>
                                    <textarea name="voter" class="form-control summernote bengali" rows="6">{!! @$setting->voter !!}</textarea>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label text-black-50 bengali font-weight-bold">ভোটার
                                        স্থানান্তরের প্রত্যয়ন পত্র</label>
                                    <textarea name="voter_transfer" class="form-control summernote bengali" rows="6">{!! @$setting->voter_transfer !!}</textarea>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label text-black-50 bengali font-weight-bold">বেকারত্বের
                                        সনদপত্র</label>
                                    <textarea name="unemployment" class="form-control summernote bengali" rows="6">{!! @$setting->unemployment !!}</textarea>
                                </div>

                                <div class="form-group mb-4">
                                    <label
                                        class="form-label text-black-50 bengali font-weight-bold">অস্থায়ীভাবে
                                        বসবাসের প্রত্যয়ন পত্র</label>
                                    <textarea name="temporary_residence" class="form-control summernote bengali" rows="6">{!! @$setting->temporary_residence !!}</textarea>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label text-black-50 bengali font-weight-bold">জাতীয়তা
                                        সনদ</label>
                                    <textarea name="nationality" class="form-control summernote bengali" rows="6">{!! @$setting->nationality !!}</textarea>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label text-black-50 bengali font-weight-bold">স্থায়ী
                                        বাসিন্দা সনদ</label>
                                    <textarea name="permanent_resident" class="form-control summernote bengali" rows="6">{!! @$setting->permanent_resident !!}</textarea>
                                </div>

                                <div class="form-group">
                                    <label class="form-label text-black-50 bengali font-weight-bold">এতিম
                                        সনদ</label>
                                    <textarea name="orphan" class="form-control summernote bengali" rows="6">{!! @$setting->orphan !!}</textarea>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label text-black-50 bengali font-weight-bold">ভি জি ডি
                                        সনদ</label>
                                    <textarea name="vgd" class="form-control summernote bengali" rows="6">{!! @$setting->vgd !!}</textarea>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="form-label text-black-50 bengali font-weight-bold">কাবিখা
                                        সনদ</label>
                                    <textarea name="kabikha" class="form-control summernote bengali" rows="6">{!! @$setting->kabikha !!}</textarea>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="form-label text-black-50 bengali font-weight-bold">টিআর
                                        সনদ</label>
                                    <textarea name="tr" class="form-control summernote bengali" rows="6">{!! @$setting->tr !!}</textarea>
                                </div>
                                <div class="form-group mb-4">
                                    <label
                                        class="form-label text-black-50 bengali font-weight-bold">অতিদরিদ্রদের
                                        জন্য কর্মসংস্থান সনদ</label>
                                    <textarea name="employment" class="form-control summernote bengali" rows="6">{!! @$setting->employment !!}</textarea>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="form-label text-black-50 bengali font-weight-bold">বয়স্ক ভাতা
                                        সনদ</label>
                                    <textarea name="elderly_allowance" class="form-control summernote bengali" rows="6">{!! @$setting->elderly_allowance !!}</textarea>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="form-label text-black-50 bengali font-weight-bold">বিধবা ভাতা
                                        সনদ</label>
                                    <textarea name="widow_allowance" class="form-control summernote bengali" rows="6">{!! @$setting->widow_allowance !!}</textarea>
                                </div>
                                <div class="form-group mb-4">
                                    <label
                                        class="form-label text-black-50 bengali font-weight-bold">মাতৃত্যকালীন
                                        ভাতার সনদ</label>
                                    <textarea name="maternity_allowance" class="form-control summernote bengali" rows="6">{!! @$setting->maternity_allowance !!}</textarea>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- SEO Settings Tab -->
                    <div class="tab-pane fade" id="seo" role="tabpanel">
                        <h5 class="bengali mb-4 border-bottom pb-2">
                            <i class="fas fa-search me-2"></i> এসইও সেটিংস
                        </h5>

                        <div class="row g-3">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali">মেটা ট্যাগ</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label
                                                class="form-label bengali font-weight-bold">Meta-Description</label>
                                            <textarea name="meta_description" class="form-control bengali" rows="3">{{ @$setting->meta_description }}</textarea>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">Keywords</label>
                                            <textarea name="keywords" class="form-control bengali" rows="3">{{ @$setting->keywords }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label bengali font-weight-bold">Robots</label>
                                            <input type="text" name="robots" class="form-control bengali"
                                                value="{{ @$setting->robots }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali">Open Graph ট্যাগ</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">ogtitle</label>
                                            <input type="text" name="ogtitle" class="form-control bengali"
                                                value="{{ @$setting->ogtitle }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label
                                                class="form-label bengali font-weight-bold">ogdescription</label>
                                            <textarea name="ogdescription" class="form-control bengali" rows="2">{{ @$setting->ogdescription }}</textarea>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">ogtype</label>
                                            <input type="text" name="ogtype" class="form-control bengali"
                                                value="{{ @$setting->ogtype }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">ogurl</label>
                                            <input type="text" name="ogurl" class="form-control bengali"
                                                value="{{ @$setting->ogurl }}">
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label bengali font-weight-bold">ogimage</label>
                                            @if (@$setting->ogimage)
                                            <img src="{{ url(@$setting->ogimage) }}"
                                                class="img-thumbnail mb-2 d-block"
                                                style="max-height: 100px;">
                                            @endif
                                            <input type="file" name="ogimage" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message Tab -->

                    <div class="tab-pane fade" id="message" role="tabpanel">
                        <h5 class="bengali mb-4 border-bottom pb-2">
                            <i class="fas fa-quote-left me-2"></i> বাণী সেটিংস
                        </h5>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">নাম</label>
                                    <input type="text" name="leader_name" class="form-control bengali"
                                        value="{{ @$setting->leader_name }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">পদবী</label>
                                    <input type="text" name="leader_title" class="form-control bengali"
                                        value="{{ @$setting->leader_title }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">ছবি <small
                                            class=""> ছবির সাইজ: 231 × 284 px</small></label>
                                    <!-- 231 × 284 px -->


                                    <input type="file" name="leader_image" class="form-control">
                                    @if (@$setting->leader_image)
                                    <img src="{{ url(@$setting->leader_image) }}"
                                        class="img-thumbnail mb-2 d-block" style="max-height: 100px;">
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label bengali font-weight-bold">বাণী</label>
                                    <textarea name="leader_message" class="form-control bengali summernote" rows="4">{!! @$setting->leader_message !!}</textarea>
                                </div>



                            </div>
                        </div>
                    </div>



                    <!-- Social Media Tab -->
                    <div class="tab-pane fade" id="social" role="tabpanel">
                        <h5 class="bengali mb-4 border-bottom pb-2">
                            <i class="fas fa-share-alt me-2"></i> সোশ্যাল মিডিয়া সেটিংস
                        </h5>

                        <div class="row g-3">
                            <!-- Facebook -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali"><i
                                                class="fab fa-facebook me-2 text-primary"></i> ফেসবুক</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">ফেসবুক পেজ
                                                লিংক</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="fas fa-link"></i></span>
                                                <input type="url" name="facebook_url"
                                                    class="form-control"
                                                    value="{{ @$setting->facebook_url }}"
                                                    placeholder="https://facebook.com/yourpage">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- YouTube -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali"><i
                                                class="fab fa-youtube me-2 text-danger"></i> ইউটিউব</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="form-label bengali font-weight-bold">ইউটিউব চ্যানেল
                                                লিংক</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="fas fa-link"></i></span>
                                                <input type="url" name="youtube_url" class="form-control"
                                                    value="{{ @$setting->youtube_url }}"
                                                    placeholder="https://youtube.com/yourchannel">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Twitter -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali"><i class="fab fa-twitter me-2 text-info"></i>
                                            টুইটার</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="form-label bengali font-weight-bold">টুইটার প্রোফাইল
                                                লিংক</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="fas fa-link"></i></span>
                                                <input type="url" name="twitter_url" class="form-control"
                                                    value="{{ @$setting->twitter_url }}"
                                                    placeholder="https://twitter.com/yourprofile">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Instagram -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali"><i
                                                class="fab fa-instagram me-2 text-danger"></i> ইনস্টাগ্রাম</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="form-label bengali font-weight-bold">ইনস্টাগ্রাম
                                                প্রোফাইল লিংক</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="fas fa-link"></i></span>
                                                <input type="url" name="instagram_url"
                                                    class="form-control"
                                                    value="{{ @$setting->instagram_url }}"
                                                    placeholder="https://instagram.com/yourprofile">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- LinkedIn -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali"><i
                                                class="fab fa-linkedin me-2 text-primary"></i> লিংকডইন</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="form-label bengali font-weight-bold">লিংকডইন প্রোফাইল
                                                লিংক</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="fas fa-link"></i></span>
                                                <input type="url" name="linkedin_url"
                                                    class="form-control"
                                                    value="{{ @$setting->linkedin_url }}"
                                                    placeholder="https://linkedin.com/yourprofile">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- WhatsApp -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali"><i
                                                class="fab fa-whatsapp me-2 text-success"></i> হোয়াটসঅ্যাপ
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="form-label bengali font-weight-bold">হোয়াটসঅ্যাপ
                                                নম্বর</label>
                                            <div class="input-group">
                                                <span class="input-group-text">+880</span>
                                                <input type="tel" name="whatsapp_number"
                                                    class="form-control"
                                                    value="{{ @$setting->whatsapp_number }}"
                                                    placeholder="1XXXXXXXXXX">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Gateway Tab -->
                    <div class="tab-pane fade" id="payment" role="tabpanel">
                        <h5 class="bengali mb-4 border-bottom pb-2">
                            <i class="fas fa-credit-card me-2"></i> SSLCommerz পেমেন্ট গেটওয়ে
                        </h5>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali"><i class="fas fa-lock me-2 text-success"></i>
                                            SSLCommerz কনফিগারেশন</h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Store Credentials -->
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">স্টোর আইডি <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="store_id"
                                                class="form-control"
                                                value="{{ @$setting->store_id }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">স্টোর পাসওয়ার্ড
                                                <span class="text-danger">*</span></label>
                                            <input type="text" name="store_pass"
                                                class="form-control"
                                                value="{{ @$setting->store_pass }}">
                                        </div>


                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali"><i
                                                class="fas fa-info-circle me-2 text-info"></i> নির্দেশনা</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled small bengali">
                                            <li class="mb-2"><i
                                                    class="fas fa-check-circle text-success me-2"></i>
                                                SSLCommerz ডেভেলপার অ্যাকাউন্ট থেকে ক্রেডেনশিয়াল সংগ্রহ করুন
                                            </li>
                                            <li class="mb-2"><i
                                                    class="fas fa-check-circle text-success me-2"></i>
                                                স্যান্ডবক্স মোডে টেস্ট করুন</li>
                                            <li class="mb-2"><i
                                                    class="fas fa-check-circle text-success me-2"></i> লাইভ
                                                মোডে যাওয়ার আগে SSLCommerz থেকে মার্চেন্ট অ্যাকাউন্ট ভেরিফাই
                                                করুন</li>
                                            <li class="mb-2"><i
                                                    class="fas fa-check-circle text-success me-2"></i> IPN URL
                                                সঠিকভাবে সেট আপ করুন</li>
                                            <li><i class="fas fa-check-circle text-success me-2"></i> সকল URL
                                                HTTPS হতে হবে</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- SMS Tab -->
                    <div class="tab-pane fade" id="sms" role="tabpanel">
                        <h5 class="bengali mb-4 border-bottom pb-2">
                            <i class="fas fa-sms me-2"></i> এসএমএস গেটওয়ে সেটিংস
                        </h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali"><i class="fas fa-cog me-2"></i> এসএমএস
                                            কনফিগারেশন</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">এসএমএস
                                                প্রোভাইডার নাম</label>
                                            <input type="text" name="sms_provider_name"
                                                class="form-control"
                                                value="{{ @$setting->sms_provider_name }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">এপিআই কী</label>
                                            <input type="password" name="sms_api_key" class="form-control"
                                                value="{{ @$setting->sms_api_key }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">সেন্ডার
                                                আইডি</label>
                                            <input type="text" name="sms_sender_id" class="form-control"
                                                value="{{ @$setting->sms_sender_id }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">এপিআই
                                                ইউআরএল</label>
                                            <input type="url" name="sms_api_url" class="form-control"
                                                value="{{ @$setting->sms_api_url }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali"><i class="fas fa-toggle-on me-2"></i>
                                            সক্রিয়তা সেটিংস</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" name="sms_active"
                                                id="smsActive" {{ @$setting->sms_active ? 'checked' : '' }}>
                                            <label class="form-check-label bengali" for="smsActive">এসএমএস
                                                সার্ভিস সক্রিয় করুন</label>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">টেস্ট মোবাইল
                                                নম্বর</label>
                                            <input type="text" name="sms_test_number" class="form-control"
                                                value="{{ @$setting->sms_test_number }}">
                                            <small class="text-muted bengali">টেস্টিং এর জন্য ব্যবহার করুন (কমা
                                                দ্বারা পৃথক করুন)</small>
                                        </div>

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                name="sms_test_mode" id="smsTestMode"
                                                {{ @$setting->sms_test_mode ? 'checked' : '' }}>
                                            <label class="form-check-label bengali" for="smsTestMode">টেস্ট
                                                মোড</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- E-mail Tab -->
                    <div class="tab-pane fade" id="email" role="tabpanel">
                        <h5 class="bengali mb-4 border-bottom pb-2">
                            <i class="fas fa-envelope me-2"></i> ইমেইল সেটিংস
                        </h5>

                        <div class="row g-3">
                            <!-- Email Provider Settings -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali"><i class="fas fa-server me-2"></i> মেইল
                                            সার্ভার কনফিগারেশন</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">মেইল
                                                হোস্ট</label>
                                            <input type="text" name="mail_host" class="form-control"
                                                value="{{ @$setting->mail_host ?? 'smtp.gmail.com' }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">মেইল
                                                পোর্ট</label>
                                            <input type="number" name="mail_port" class="form-control"
                                                value="{{ @$setting->mail_port ?? 587 }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">মেইল
                                                ইউজারনেম</label>
                                            <input type="text" name="mail_username" class="form-control"
                                                value="{{ @$setting->mail_username }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">মেইল
                                                পাসওয়ার্ড</label>
                                            <input type="password" name="mail_password" class="form-control"
                                                value="{{ @$setting->mail_password }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label
                                                class="form-label bengali font-weight-bold">এনক্রিপশন</label>
                                            <select name="mail_encryption" class="form-select">
                                                <option value="tls"
                                                    {{ (@$setting->mail_encryption ?? 'tls') == 'tls' ? 'selected' : '' }}>
                                                    TLS</option>
                                                <option value="ssl"
                                                    {{ (@$setting->mail_encryption ?? 'tls') == 'ssl' ? 'selected' : '' }}>
                                                    SSL</option>
                                                <option value=""
                                                    {{ empty(@$setting->mail_encryption) ? 'selected' : '' }}>
                                                    None</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Email From Settings -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali"><i class="fas fa-user-shield me-2"></i>
                                            প্রেরকের তথ্য</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">প্রেরকের
                                                ইমেইল</label>
                                            <input type="email" name="mail_from_address"
                                                class="form-control"
                                                value="{{ @$setting->mail_from_address }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">প্রেরকের
                                                নাম</label>
                                            <input type="text" name="mail_from_name"
                                                class="form-control bengali"
                                                value="{{ @$setting->mail_from_name ?? config('app.name') }}">
                                        </div>

                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox"
                                                name="mail_active" id="mailActive"
                                                {{ @$setting->mail_active ? 'checked' : '' }}>
                                            <label class="form-check-label bengali" for="mailActive">ইমেইল
                                                সার্ভিস সক্রিয় করুন</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Email Templates -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali"><i
                                                class="fas fa-envelope-open-text me-2"></i> ইমেইল টেমপ্লেট</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label
                                                        class="form-label bengali font-weight-bold">রেজিস্ট্রেশন
                                                        ইমেইল সাবজেক্ট</label>
                                                    <input type="text" name="email_register_subject"
                                                        class="form-control bengali"
                                                        value="{{ @$setting->email_register_subject ?? 'আপনার অ্যাকাউন্ট সফলভাবে তৈরি হয়েছে' }}">
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="form-label bengali font-weight-bold">রেজিস্ট্রেশন
                                                        ইমেইল বডি</label>
                                                    <textarea name="email_register_body" class="form-control bengali" rows="5">{{ @$setting->email_register_body ?? 'প্রিয় {name},\n\nআপনার অ্যাকাউন্ট সফলভাবে তৈরি হয়েছে। লগইন করতে নিচের লিংক ব্যবহার করুন:\n{login_url}\n\nধন্যবাদান্তে,\n{site_name}' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label
                                                        class="form-label bengali font-weight-bold">পাসওয়ার্ড
                                                        রিসেট ইমেইল সাবজেক্ট</label>
                                                    <input type="text" name="email_reset_subject"
                                                        class="form-control bengali"
                                                        value="{{ @$setting->email_reset_subject ?? 'আপনার পাসওয়ার্ড রিসেট লিংক' }}">
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="form-label bengali font-weight-bold">পাসওয়ার্ড
                                                        রিসেট ইমেইল বডি</label>
                                                    <textarea name="email_reset_body" class="form-control bengali" rows="5">{{ @$setting->email_reset_body ?? 'প্রিয় {name},\n\nআপনার পাসওয়ার্ড রিসেট করতে নিচের লিংক ব্যবহার করুন:\n{reset_url}\n\nএই লিংক 60 মিনিটের জন্য বৈধ থাকবে।\n\nধন্যবাদান্তে,\n{site_name}' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Tab -->
                    <div class="tab-pane fade" id="contact" role="tabpanel">
                        <h5 class="bengali mb-4 border-bottom pb-2">
                            <i class="fas fa-address-book me-2"></i> যোগাযোগ সেটিংস
                        </h5>

                        <div class="row g-3">
                            <!-- Office Address -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali"><i class="fas fa-building me-2"></i> অফিস
                                            ঠিকানা</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">অফিসের
                                                নাম</label>
                                            <input type="text" name="office_name"
                                                class="form-control bengali"
                                                value="{{ $setting->office_name ?? 'আপনার অফিসের নাম' }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">ঠিকানা (লাইন
                                                ১)</label>
                                            <input type="text" name="address_line1"
                                                class="form-control bengali"
                                                value="{{ $setting->address_line1 ?? 'আপনার অফিসের ঠিকানা লাইন ১' }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">ঠিকানা (লাইন
                                                ২)</label>
                                            <input type="text" name="address_line2"
                                                class="form-control bengali"
                                                value="{{ $setting->address_line2 ?? 'আপনার অফিসের ঠিকানা লাইন ২' }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">জেলা</label>
                                            <input type="text" name="district"
                                                class="form-control bengali"
                                                value="{{ $setting->district ?? 'আপনার জেলা' }}">
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label bengali font-weight-bold">পোস্ট
                                                কোড</label>
                                            <input type="text" name="post_code" class="form-control"
                                                value="{{ $setting->post_code ?? 'আপনার পোস্ট কোড' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali"><i class="fas fa-phone-alt me-2"></i> যোগাযোগ
                                            তথ্য</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">হেল্পলাইন
                                                নাম্বার</label>
                                            <div class="input-group">
                                                <span class="input-group-text">+880</span>
                                                <input type="text" name="helpline_number"
                                                    class="form-control"
                                                    value="{{ @$setting->helpline_number }}"
                                                    placeholder="1XXXXXXXXXX">
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">সাপোর্ট
                                                ইমেইল</label>
                                            <input type="email" name="support_email" class="form-control"
                                                value="{{ @$setting->support_email }}"
                                                placeholder="">
                                        </div>

                                        <div class=" form-group
                                                mb-3">
                                            <label class="form-label bengali font-weight-bold">সাধারণ
                                                ইমেইল</label>
                                            <input type="email" name="general_email" class="form-control"
                                                value="{{ @$setting->general_email }}"
                                                placeholder=" আপনার সাধারণ ইমেইল">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">ফ্যাক্স
                                                নাম্বার</label>
                                            <input type="text" name="fax_number" class="form-control"
                                                value="{{ @$setting->fax_number }}"
                                                placeholder="আপনার ফ্যাক্স নাম্বার">
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label bengali font-weight-bold">জরুরী
                                                যোগাযোগ</label>
                                            <div class="input-group">
                                                <span class="input-group-text">+880</span>
                                                <input type="text" name="emergency_number"
                                                    class="form-control"
                                                    value="{{ @$setting->emergency_number }}"
                                                    placeholder="1XXXXXXXXXX">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Other Info Tab -->
                    <div class="tab-pane fade" id="otherinfo" role="tabpanel">


                        <div class="row g-3">
                            <!-- Office Address -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali"><i class="fas fa-building me-2"></i>বিবিধ</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">জনসংখ্যা</label>
                                            <input type="text" name="population"
                                                class="form-control bengali"
                                                value="{{ $setting->population ?? 'জনসংখ্যা' }}">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">আয়তন</label>
                                            <input type="text" name="area"
                                                class="form-control bengali"
                                                value="{{ $setting->area ?? 'আয়তন' }}">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">গ্রাম সংখ্যা</label>
                                            <input type="text" name="villages"
                                                class="form-control bengali"
                                                value="{{ $setting->villages ?? 'গ্রাম সংখ্যা' }}">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">ওয়ার্ড সংখ্যা</label>
                                            <input type="text" name="wards"
                                                class="form-control bengali"
                                                value="{{ $setting->wards ?? 'ওয়ার্ড সংখ্যা' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 bengali"><i class="fas fa-phone-alt me-2"></i> উন্নয়ন তথ্য</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">শিক্ষার হার </label>
                                            <input type="text" name="education_rate"
                                                class="form-control bengali"
                                                value="{{ $setting->education_rate ?? 'শিক্ষার হার' }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">জনসংখ্যা ঘনত্ব </label>
                                            <input type="text" name="population_density"
                                                class="form-control bengali"
                                                value="{{ $setting->population_density ?? 'জনসংখ্যা ঘনত্ব' }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">বেকারত্বের হার </label>
                                            <input type="text" name="unemployment_rate"
                                                class="form-control bengali"
                                                value="{{ $setting->unemployment_rate ?? 'বেকারত্বের হার' }}">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label bengali font-weight-bold">মৌজা </label>
                                            <input type="text" name="mouza"
                                                class="form-control bengali"
                                                value="{{ $setting->mouza ?? 'মৌজা' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php $extra = $setting->extra_data ?? []; 
                        $institutions = old('institutions',
                        data_get($extra, 'education_institutions', data_get($extra, 'institutions', [[]]))
                        );
                        @endphp
                        <div class="card border-0 shadow-sm mt-3">
                            <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 bengali"><i class="fas fa-school me-2"></i> শিক্ষা প্রতিষ্ঠান</h6>
                                <button type="button" class="btn btn-sm btn-primary repeater-add" data-target="#institutions-list">+ যোগ করুন</button>
                            </div>
                            <div class="card-body">
                                <div id="institutions-list" class="repeater-list">
                                    @forelse($institutions as $idx => $row)
                                    <div class="repeater-item border rounded p-3 mb-3">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label bengali">শিক্ষা প্রতিষ্ঠানের নাম</label>
                                                <input type="text" name="institutions[name][]" class="form-control bengali" value="{{ $row['name'] ?? '' }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label bengali">শিক্ষা প্রতিষ্ঠানের ঠিকানা</label>
                                                <input type="text" name="institutions[address][]" class="form-control bengali" value="{{ $row['address'] ?? '' }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label bengali">ওয়েবসাইট</label>
                                                <input type="url" name="institutions[website][]" class="form-control" placeholder="https://…" value="{{ $row['website'] ?? '' }}">
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger w-100 repeater-remove">&times;</button>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label bengali">ফোন</label>
                                                <input type="text" name="institutions[phone][]" class="form-control bengali" value="{{ $row['phone'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    {{-- one empty row by default --}}
                                    <div class="repeater-item border rounded p-3 mb-3">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label bengali">শিক্ষা প্রতিষ্ঠানের নাম</label>
                                                <input type="text" name="institutions[name][]" class="form-control bengali">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label bengali">শিক্ষা প্রতিষ্ঠানের ঠিকানা</label>
                                                <input type="text" name="institutions[address][]" class="form-control bengali">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label bengali">ওয়েবসাইট</label>
                                                <input type="url" name="institutions[website][]" class="form-control" placeholder="https://…">
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger w-100 repeater-remove">&times;</button>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label bengali">ফোন</label>
                                                <input type="text" name="institutions[phone][]" class="form-control bengali">
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        @php
                        $villages = old('villages',
                        data_get($extra, 'villages', [[]])
                        );
                        @endphp
                        <div class="card border-0 shadow-sm mt-3">
                            <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 bengali"><i class="fas fa-home me-2"></i> গ্রাম</h6>
                                <button type="button" class="btn btn-sm btn-primary repeater-add" data-target="#villages-list">+ যোগ করুন</button>
                            </div>
                            <div class="card-body">
                                <div id="villages-list" class="repeater-list">
                                    @forelse($villages as $row)
                                    <div class="repeater-item border rounded p-3 mb-3">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label bengali">গ্রামের নাম</label>
                                                <input type="text" name="villages[name][]" class="form-control bengali" value="{{ $row['name'] ?? '' }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label bengali">গ্রামের আয়তন</label>
                                                <input type="text" name="villages[area][]" class="form-control bengali" value="{{ $row['area'] ?? '' }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label bengali">গ্রামের জনসংখ্যা</label>
                                                <input type="text" name="villages[population][]" class="form-control bengali" value="{{ $row['population'] ?? '' }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label bengali">শিক্ষার হার</label>
                                                <input type="text" name="villages[education_rate][]" class="form-control bengali" value="{{ $row['education_rate'] ?? '' }}">
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger w-100 repeater-remove">&times;</button>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="repeater-item border rounded p-3 mb-3">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label bengali">গ্রামের নাম</label>
                                                <input type="text" name="villages[name][]" class="form-control bengali">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label bengali">গ্রামের আয়তন</label>
                                                <input type="text" name="villages[area][]" class="form-control bengali">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label bengali">গ্রামের জনসংখ্যা</label>
                                                <input type="text" name="villages[population][]" class="form-control bengali">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label bengali">শিক্ষার হার</label>
                                                <input type="text" name="villages[education_rate][]" class="form-control bengali">
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger w-100 repeater-remove">&times;</button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>


                        @php
                        $markets = old('markets',
                        data_get($extra, 'markets', [[]])
                        );
                        @endphp

                        <div class="card border-0 shadow-sm mt-3">
                            <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 bengali"><i class="fas fa-store me-2"></i> হাট বাজার</h6>
                                <button type="button" class="btn btn-sm btn-primary repeater-add" data-target="#markets-list">+ যোগ করুন</button>
                            </div>
                            <div class="card-body">
                                <div id="markets-list" class="repeater-list">
                                    @forelse($markets as $row)
                                    <div class="repeater-item border rounded p-3 mb-3">
                                        <div class="row g-3">
                                            <div class="col-md-5">
                                                <label class="form-label bengali">হাট বাজার নাম</label>
                                                <input type="text" name="markets[name][]" class="form-control bengali" value="{{ $row['name'] ?? '' }}">
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label bengali">হাট বাজার ধরণ</label>
                                                <select name="markets[type][]" class="form-select bengali">
                                                    <option value="">—</option>
                                                    <option value="সরকারি" @selected(($row['type'] ?? '' )==='সরকারি' )>সরকারি</option>
                                                    <option value="বেসরকারি" @selected(($row['type'] ?? '' )==='বেসরকারি' )>বেসরকারি</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger w-100 repeater-remove">&times;</button>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="repeater-item border rounded p-3 mb-3">
                                        <div class="row g-3">
                                            <div class="col-md-5">
                                                <label class="form-label bengali">হাট বাজার নাম</label>
                                                <input type="text" name="markets[name][]" class="form-control bengali">
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label bengali">হাট বাজার ধরণ</label>
                                                <select name="markets[type][]" class="form-select bengali">
                                                    <option value="">—</option>
                                                    <option value="সরকারি">সরকারি</option>
                                                    <option value="বেসরকারি">বেসরকারি</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger w-100 repeater-remove">&times;</button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        @php
                        $orgs = old('orgs',
                        data_get($extra, 'other_organizations', data_get($extra, 'orgs', [[]]))
                        );
                        @endphp
                        <div class="card border-0 shadow-sm mt-3">
                            <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 bengali"><i class="fas fa-city me-2"></i> অন্য সরকারি/বেসরকারি প্রতিষ্ঠান</h6>
                                <button type="button" class="btn btn-sm btn-primary repeater-add" data-target="#orgs-list">+ যোগ করুন</button>
                            </div>
                            <div class="card-body">
                                <div id="orgs-list" class="repeater-list">
                                    @forelse($orgs as $row)
                                    <div class="repeater-item border rounded p-3 mb-3">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label bengali">প্রতিষ্ঠানের নাম</label>
                                                <input type="text" name="orgs[name][]" class="form-control bengali" value="{{ $row['name'] ?? '' }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label bengali">ধরণ</label>
                                                <select name="orgs[type][]" class="form-select bengali">
                                                    <option value="">—</option>
                                                    <option value="সরকারি" @selected(($row['type'] ?? '' )==='সরকারি' )>সরকারি</option>
                                                    <option value="বেসরকারি" @selected(($row['type'] ?? '' )==='বেসরকারি' )>বেসরকারি</option>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label bengali">সেবার ধরণ</label>
                                                <input type="text" name="orgs[service][]" class="form-control bengali" value="{{ $row['service'] ?? '' }}">
                                            </div>

                                            <div class="col-md-5">
                                                <label class="form-label bengali">প্রতিষ্ঠানের ঠিকানা</label>
                                                <input type="text" name="orgs[address][]" class="form-control bengali" value="{{ $row['address'] ?? '' }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">ওয়েবসাইট</label>
                                                <input type="url" name="orgs[website][]" class="form-control" placeholder="https://…" value="{{ $row['website'] ?? '' }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label bengali">ফোন</label>
                                                <input type="text" name="orgs[phone][]" class="form-control bengali" value="{{ $row['phone'] ?? '' }}">
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger w-100 repeater-remove">&times;</button>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="repeater-item border rounded p-3 mb-3">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label bengali">প্রতিষ্ঠানের নাম</label>
                                                <input type="text" name="orgs[name][]" class="form-control bengali">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label bengali">ধরণ</label>
                                                <select name="orgs[type][]" class="form-select bengali">
                                                    <option value="">—</option>
                                                    <option value="সরকারি">সরকারি</option>
                                                    <option value="বেসরকারি">বেসরকারি</option>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label bengali">সেবার ধরণ</label>
                                                <input type="text" name="orgs[service][]" class="form-control bengali">
                                            </div>

                                            <div class="col-md-5">
                                                <label class="form-label bengali">প্রতিষ্ঠানের ঠিকানা</label>
                                                <input type="text" name="orgs[address][]" class="form-control bengali">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">ওয়েবসাইট</label>
                                                <input type="url" name="orgs[website][]" class="form-control" placeholder="https://…">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label bengali">ফোন</label>
                                                <input type="text" name="orgs[phone][]" class="form-control bengali">
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger w-100 repeater-remove">&times;</button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        @php
                        $religious = old('religious',
                        data_get($extra, 'religious_places', data_get($extra, 'religious', [[]]))
                        );
                        @endphp
                        <div class="card border-0 shadow-sm mt-3">
                            <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 bengali"><i class="fas fa-mosque me-2"></i> গুরুত্বর্পূণ ধর্মীয় স্থান</h6>
                                <button type="button" class="btn btn-sm btn-primary repeater-add" data-target="#religious-list">+ যোগ করুন</button>
                            </div>
                            <div class="card-body">
                                <div id="religious-list" class="repeater-list">
                                    @forelse($religious as $row)
                                    <div class="repeater-item border rounded p-3 mb-3">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label bengali">নাম</label>
                                                <input type="text" name="religious[name][]" class="form-control bengali" value="{{ $row['name'] ?? '' }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label bengali">ধরণ</label>
                                                <input type="text" name="religious[type][]" class="form-control bengali" value="{{ $row['type'] ?? '' }}" placeholder="মসজিদ/মন্দির/গির্জা…">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label bengali">বিবরণ</label>
                                                <input type="text" name="religious[description][]" class="form-control bengali" value="{{ $row['description'] ?? '' }}">
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger w-100 repeater-remove">&times;</button>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="repeater-item border rounded p-3 mb-3">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label bengali">নাম</label>
                                                <input type="text" name="religious[name][]" class="form-control bengali">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label bengali">ধরণ</label>
                                                <input type="text" name="religious[type][]" class="form-control bengali" placeholder="মসজিদ/মন্দির/গির্জা…">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label bengali">বিবরণ</label>
                                                <input type="text" name="religious[description][]" class="form-control bengali">
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger w-100 repeater-remove">&times;</button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        @php
                        $tourism = old('tourism',
                        data_get($extra, 'heritage_sites', data_get($extra, 'tourism', [[]]))
                        );
                        @endphp
                        <div class="card border-0 shadow-sm mt-3 mb-2">
                            <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 bengali"><i class="fas fa-landmark me-2"></i> ঐতিহাসিক/পর্যটন স্থান</h6>
                                <button type="button" class="btn btn-sm btn-primary repeater-add" data-target="#tourism-list">+ যোগ করুন</button>
                            </div>
                            <div class="card-body">
                                <div id="tourism-list" class="repeater-list">
                                    @forelse($tourism as $row)
                                    <div class="repeater-item border rounded p-3 mb-3">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label bengali">নাম</label>
                                                <input type="text" name="tourism[name][]" class="form-control bengali" value="{{ $row['name'] ?? '' }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label bengali">ধরণ</label>
                                                <input type="text" name="tourism[type][]" class="form-control bengali" value="{{ $row['type'] ?? '' }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label bengali">বিবরণ</label>
                                                <input type="text" name="tourism[description][]" class="form-control bengali" value="{{ $row['description'] ?? '' }}">
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger w-100 repeater-remove">&times;</button>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="repeater-item border rounded p-3 mb-3">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label bengali">নাম</label>
                                                <input type="text" name="tourism[name][]" class="form-control bengali">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label bengali">ধরণ</label>
                                                <input type="text" name="tourism[type][]" class="form-control bengali">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label bengali">বিবরণ</label>
                                                <input type="text" name="tourism[description][]" class="form-control bengali">
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger w-100 repeater-remove">&times;</button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- ===== Generic repeater template (hidden) ===== --}}
                        <template id="repeater-template">
                            <div class="repeater-item border rounded p-3 mb-3">
                                <div class="row g-3" data-fields><!-- dynamic --></div>
                            </div>
                        </template>
                    </div>
                </div>
        </div>

        <div class="card-footer bg-white border-top py-3 text-end">
            <button type="submit" class="btn btn-primary bengali py-2 px-4">
                <i class="fas fa-save me-2"></i> সংরক্ষণ করুন
            </button>
        </div>
    </div>
    </form>
</div>
</div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    }

    .nav-tabs .nav-link {
        border: none;
        color: #495057;
        font-weight: 500;
        padding: 0.75rem 1.25rem;
        border-bottom: 3px solid transparent;
    }

    .nav-tabs .nav-link.active {
        color: #3b82f6;
        background-color: transparent;
        border-bottom: 3px solid #3b82f6 !important;
    }

    .nav-tabs .nav-link:hover:not(.active) {
        border-bottom: 3px solid #dee2e6;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
    }

    .summernote {
        min-height: 200px;
    }

    .card {
        border-radius: 10px;
        overflow: hidden;
    }

    .tab-content {
        /* background-color: #fff; */
    }

    .custom-tab {
        background-color: #fff;
    }

    .white-color {
        color: #fff !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Summernote if available
        if ($('.summernote').length > 0) {
            $('.summernote').summernote({
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        }
    });
</script>
<script>
    (function() {
        // add row
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.repeater-add');
            if (!btn) return;
            const listSelector = btn.getAttribute('data-target');
            const list = document.querySelector(listSelector);
            if (!list) return;

            // clone last row (keeps same input names) or first child as model
            const model = list.querySelector('.repeater-item:last-child') || list.querySelector('.repeater-item');
            if (!model) return;

            const clone = model.cloneNode(true);
            // clear values
            clone.querySelectorAll('input, select, textarea').forEach(el => {
                if (el.tagName === 'SELECT') el.selectedIndex = 0;
                else el.value = '';
            });
            list.appendChild(clone);
        });

        // remove row
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.repeater-remove');
            if (!btn) return;
            const item = btn.closest('.repeater-item');
            const list = btn.closest('.repeater-list');
            if (item && list) {
                // keep at least one row
                if (list.querySelectorAll('.repeater-item').length > 1) {
                    item.remove();
                } else {
                    // just clear fields if it's the last remaining
                    item.querySelectorAll('input, select, textarea').forEach(el => {
                        if (el.tagName === 'SELECT') el.selectedIndex = 0;
                        else el.value = '';
                    });
                }
            }
        });
    })();
</script>
@endsection