@extends('layouts.sass')
@section('title', 'Find Doctors Nearby')


@section('content')
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f9fafb;
        }
        .custom-title-line { line-height: 1.12 !important; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .sidebar-btn.active { 
            background-color: #318069 !important; 
            color: white !important; 
        }
        .sidebar-btn:hover:not(.active) { 
            background-color: #f9fafb !important; 
        }
        /* Professional hover states */
        .btn-hover:hover {
            background-color: #f8f9fa;
        }
        .btn-primary-hover:hover {
            background-color: #276854;
        }
        .btn-outline-hover:hover {
            background-color: #f8f9fa;
            border-color: #d1d5db;
        }
        /* Smooth transitions */
        .smooth-transition {
            transition: all 0.3s ease;
        }
    </style>
    

    <!-- Progress Header -->
    <div class="bg-[#f4f4f499] border-b border-gray-200 pt-28 pb-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl md:text-3xl font-bold text-gray-900 mb-2">Update Your Profile</h1>
                    <p class="text-gray-600">Complete your professional profile to help patients find and trust you more.</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border-2 border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="relative w-16 h-16">
                            <svg class="transform -rotate-90 w-16 h-16">
                                <circle cx="32" cy="32" r="28" stroke="#E5E7EB" stroke-width="6" fill="none"></circle>
                                <circle id="progress-circle" cx="32" cy="32" r="28" stroke="#318069" stroke-width="6" fill="none"
                                    stroke-dasharray="175.929" stroke-dashoffset="175.929" stroke-linecap="round" class="transition-all duration-500"></circle>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span id="progress-percent" class="text-lg font-bold text-[#318069]">0%</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Profile Complete</p>
                            <p class="text-xs text-gray-500">Start by filling your details</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 py-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex gap-8 flex-col lg:flex-row">
                <!-- Sidebar Navigation -->
                <div class="lg:w-80 flex-shrink-0">
                    <div class="bg-white rounded-xl border-2 border-gray-200 p-6 sticky top-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Profile Sections</h3>
                        <div class="space-y-2" id="sidebar-nav">
                            <button data-tab="1" class="sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-all text-gray-700 cursor-pointer active">
                                <i class="ri-stethoscope-line text-xl w-5 h-5 flex items-center justify-center"></i>
                                <span class="text-sm font-semibold flex-1">Medical Specialty</span>
                                <i class="ri-arrow-right-s-line w-5 h-5 flex items-center justify-center"></i>
                            </button>
                            <button data-tab="2" class="sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-all cursor-pointer text-gray-700 hover:bg-gray-50">
                                <i class="ri-user-line text-xl w-5 h-5 flex items-center justify-center"></i>
                                <span class="text-sm font-semibold flex-1">Personal Details</span>
                            </button>
                            <button data-tab="3" class="sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-all cursor-pointer text-gray-700 hover:bg-gray-50">
                                <i class="ri-camera-line text-xl w-5 h-5 flex items-center justify-center"></i>
                                <span class="text-sm font-semibold flex-1">Professional Photo</span>
                            </button>
                            <button data-tab="4" class="sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-all cursor-pointer text-gray-700 hover:bg-gray-50">
                                <i class="ri-calendar-line text-xl w-5 h-5 flex items-center justify-center"></i>
                                <span class="text-sm font-semibold flex-1">Availability & Location</span>
                            </button>
                            <button data-tab="5" class="sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-all cursor-pointer text-gray-700 hover:bg-gray-50">
                                <i class="ri-graduation-cap-line text-xl w-5 h-5 flex items-center justify-center"></i>
                                <span class="text-sm font-semibold flex-1">Education</span>
                            </button>
                            <button data-tab="6" class="sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-all cursor-pointer text-gray-700 hover:bg-gray-50">
                                <i class="ri-briefcase-line text-xl w-5 h-5 flex items-center justify-center"></i>
                                <span class="text-sm font-semibold flex-1">Experience</span>
                            </button>
                            <button data-tab="7" class="sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-all cursor-pointer text-gray-700 hover:bg-gray-50">
                                <i class="ri-team-line text-xl w-5 h-5 flex items-center justify-center"></i>
                                <span class="text-sm font-semibold flex-1">Affiliations</span>
                            </button>
                            <button data-tab="8" class="sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-all cursor-pointer text-gray-700 hover:bg-gray-50">
                                <i class="ri-file-list-line text-xl w-5 h-5 flex items-center justify-center"></i>
                                <span class="text-sm font-semibold flex-1">Additional Info</span>
                            </button>
                        </div>
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <i class="ri-save-line w-4 h-4 flex items-center justify-center"></i>
                                <span>Auto-saved</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Contents -->
                <div class="flex-1">
                    <!-- Tab 1: Medical Specialty -->
                    <div id="tab1" class="tab-content active bg-white rounded-xl border-2 border-gray-200 p-8 shadow-sm">
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-[#318069]/10 rounded-lg flex items-center justify-center">
                                    <i class="ri-stethoscope-line text-2xl text-[#318069]"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Choose Your Medical Specialty</h2>
                                    <p class="text-sm text-gray-600">Select one or more specialties</p>
                                </div>
                            </div>
                            <div class="bg-[#FFC107]/10 border-l-4 border-[#FFC107] rounded-lg p-4 mt-4">
                                <div class="flex gap-3">
                                    <i class="ri-lightbulb-line text-[#FFC107] text-xl flex-shrink-0 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 mb-1">Pro Tip</p>
                                        <p class="text-sm text-gray-700">Adding multiple specialties helps patients find you more easily. You can always update this later.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6">
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                    <i class="ri-search-line text-gray-400 text-xl"></i>
                                </div>
                                <input id="specialty-search" placeholder="Search specialties..."
                                    class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base" type="text">
                            </div>
                        </div>
                        <div class="mb-6" id="selected-specialties-container">
                            <p class="text-sm font-semibold text-gray-900 mb-3">Selected (<span id="selected-count">0</span>)</p>
                            <div id="selected-specialties" class="flex flex-wrap gap-2">
                                <p class="text-gray-500 text-sm italic">No specialties selected yet</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 mb-3">Available Specialties</p>
                            <div id="specialties-list" class="flex flex-wrap gap-2">
                                <!-- Specialties will be populated by JavaScript -->
                            </div>
                        </div>
                        <div class="flex gap-4 mt-8 pt-8 border-t-2 border-gray-200">
                            <button onclick="switchTab(2)" class="next-btn flex-1 bg-[#318069] hover:bg-[#276854] text-white px-6 py-3 rounded-lg font-semibold transition-all whitespace-nowrap shadow-lg hover:shadow-xl cursor-pointer">
                                Save & Continue<i class="ri-arrow-right-line ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tab 2: Personal Details -->
                    <div id="tab2" class="tab-content bg-white rounded-xl border-2 border-gray-200 p-8 shadow-sm">
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-[#318069]/10 rounded-lg flex items-center justify-center">
                                    <i class="ri-user-line text-2xl text-[#318069]"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Personal & Professional Details</h2>
                                    <p class="text-sm text-gray-600">Help patients verify your credentials</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Country <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select id="country" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base appearance-none cursor-pointer">
                                        <option value="">Select your country</option>
                                        <option value="US">🇺🇸 United States</option>
                                        <option value="GB">🇬🇧 United Kingdom</option>
                                        <option value="CA">🇨🇦 Canada</option>
                                        <option value="AU">🇦🇺 Australia</option>
                                        <option value="IN">🇮🇳 India</option>
                                        <option value="PK">🇵🇰 Pakistan</option>
                                        <option value="BD">🇧🇩 Bangladesh</option>
                                        <option value="AE">🇦🇪 United Arab Emirates</option>
                                        <option value="SA">🇸🇦 Saudi Arabia</option>
                                        <option value="SG">🇸🇬 Singapore</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                        <i class="ri-arrow-down-s-line text-gray-400 text-xl"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Medical License Number <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                            <i class="ri-shield-check-line text-gray-400 text-xl"></i>
                                        </div>
                                        <input id="license" placeholder="e.g., ML-123456" class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base" type="text">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">This helps verify your credentials</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Doctor's Qualification <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select id="qualification" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base appearance-none cursor-pointer">
                                            <option value="">Select qualification</option>
                                            <option value="MBBS">MBBS</option>
                                            <option value="MD">MD</option>
                                            <option value="DO">DO</option>
                                            <option value="FCPS">FCPS</option>
                                            <option value="FRCS">FRCS</option>
                                            <option value="MS">MS</option>
                                            <option value="DNB">DNB</option>
                                            <option value="DM">DM</option>
                                            <option value="MCh">MCh</option>
                                            <option value="PhD">PhD</option>
                                        </select>
                                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                            <i class="ri-arrow-down-s-line text-gray-400 text-xl"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Years of Experience <span class="text-gray-400 text-xs font-normal">(Recommended)</span></label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                        <i class="ri-time-line text-gray-400 text-xl"></i>
                                    </div>
                                    <input id="experience" placeholder="e.g., 10" min="0" max="60" class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base" type="number">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Experienced doctors get more patient trust</p>
                            </div>
                            <div class="bg-[#318069]/5 border-2 border-[#318069]/20 rounded-lg p-4 mt-4">
                                <div class="flex gap-3">
                                    <i class="ri-information-line text-[#318069] text-xl flex-shrink-0 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 mb-1">Why we need this</p>
                                        <p class="text-sm text-gray-700">Your license number and qualifications help patients verify your credentials and build trust. All information is kept secure and private.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-4 mt-8 pt-8 border-t-2 border-gray-200">
                            <button onclick="switchTab(1)" class="prev-btn px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all whitespace-nowrap cursor-pointer btn-outline-hover">
                                <i class="ri-arrow-left-line mr-2"></i>Back
                            </button>
                            <button onclick="switchTab(3)" class="next-btn flex-1 bg-[#318069] hover:bg-[#276854] text-white px-6 py-3 rounded-lg font-semibold transition-all whitespace-nowrap shadow-lg hover:shadow-xl cursor-pointer btn-primary-hover">
                                Save & Continue<i class="ri-arrow-right-line ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tab 3: Professional Photo -->
                    <div id="tab3" class="tab-content bg-white rounded-xl border-2 border-gray-200 p-8 shadow-sm">
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-[#318069]/10 rounded-lg flex items-center justify-center">
                                    <i class="ri-camera-line text-2xl text-[#318069]"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Professional Photo</h2>
                                    <p class="text-sm text-gray-600">Upload a clear, professional photo</p>
                                </div>
                            </div>
                            <div class="bg-[#FFC107]/10 border-l-4 border-[#FFC107] rounded-lg p-4 mt-4">
                                <div class="flex gap-3">
                                    <i class="ri-lightbulb-line text-[#FFC107] text-xl flex-shrink-0 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 mb-1">Photo Tips</p>
                                        <ul class="text-sm text-gray-700 space-y-1">
                                            <li>• Use a clear, recent photo with your face visible</li>
                                            <li>• Professional attire recommended (white coat preferred)</li>
                                            <li>• Good lighting and neutral background</li>
                                            <li>• JPG or PNG format, max 5MB</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="max-w-2xl mx-auto">
                            <div id="photo-upload-area" class="border-3 border-dashed rounded-2xl p-12 text-center transition-all border-gray-300 hover:border-[#318069] hover:bg-gray-50">
                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="ri-image-add-line text-5xl text-gray-400"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Upload Your Professional Photo</h3>
                                <p class="text-gray-600 mb-6">Drag and drop your photo here, or click to browse</p>
                                <label class="inline-block">
                                    <input id="photo-input" accept="image/jpeg,image/png" class="hidden" type="file">
                                    <span class="inline-flex items-center gap-2 px-6 py-3 bg-[#318069] hover:bg-[#276854] text-white rounded-lg font-semibold transition-all cursor-pointer whitespace-nowrap shadow-lg btn-primary-hover">
                                        <i class="ri-upload-2-line"></i>Choose Photo
                                    </span>
                                </label>
                                <p class="text-xs text-gray-500 mt-4">Supported formats: JPG, PNG (Max 5MB)</p>
                            </div>
                            <div id="photo-preview-container" class="hidden border-2 border-gray-200 rounded-2xl p-8 mt-4">
                                <div class="flex flex-col items-center">
                                    <div class="relative mb-6">
                                        <img id="photo-preview" alt="Professional photo" class="w-64 h-64 object-cover rounded-2xl shadow-lg" src="">
                                        <div class="absolute -top-2 -right-2 w-10 h-10 bg-green-500 rounded-full flex items-center justify-center shadow-lg">
                                            <i class="ri-check-line text-white text-xl"></i>
                                        </div>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-900 mb-6">Photo uploaded successfully!</p>
                                    <div class="flex gap-3">
                                        <label>
                                            <input id="replace-photo-input" accept="image/jpeg,image/png" class="hidden" type="file">
                                            <span class="replace-photo inline-flex items-center gap-2 px-5 py-2.5 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all cursor-pointer whitespace-nowrap btn-outline-hover">
                                                <i class="ri-refresh-line"></i>Replace Photo
                                            </span>
                                        </label>
                                        <button class="remove-photo inline-flex items-center gap-2 px-5 py-2.5 border-2 border-red-300 text-red-600 rounded-lg font-semibold hover:bg-red-50 transition-all cursor-pointer whitespace-nowrap">
                                            <i class="ri-delete-bin-line"></i>Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center mx-auto mb-3 shadow-sm">
                                    <i class="ri-user-heart-line text-2xl text-[#318069]"></i>
                                </div>
                                <h4 class="text-sm font-bold text-gray-900 mb-1">Build Trust</h4>
                                <p class="text-xs text-gray-600">Patients prefer doctors with photos</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center mx-auto mb-3 shadow-sm">
                                    <i class="ri-eye-line text-2xl text-[#318069]"></i>
                                </div>
                                <h4 class="text-sm font-bold text-gray-900 mb-1">More Views</h4>
                                <p class="text-xs text-gray-600">Profiles with photos get 3x more views</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center mx-auto mb-3 shadow-sm">
                                    <i class="ri-star-line text-2xl text-[#318069]"></i>
                                </div>
                                <h4 class="text-sm font-bold text-gray-900 mb-1">Higher Ratings</h4>
                                <p class="text-xs text-gray-600">Complete profiles rank higher</p>
                            </div>
                        </div>
                        <div class="flex gap-4 mt-8 pt-8 border-t-2 border-gray-200">
                            <button onclick="switchTab(2)" class="prev-btn px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all whitespace-nowrap cursor-pointer btn-outline-hover">
                                <i class="ri-arrow-left-line mr-2"></i>Back
                            </button>
                            <button onclick="switchTab(4)" class="next-btn flex-1 bg-[#318069] hover:bg-[#276854] text-white px-6 py-3 rounded-lg font-semibold transition-all whitespace-nowrap shadow-lg hover:shadow-xl cursor-pointer btn-primary-hover">
                                Save & Continue<i class="ri-arrow-right-line ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tab 4: Availability & Location -->
                    <div id="tab4" class="tab-content bg-white rounded-xl border-2 border-gray-200 p-8 shadow-sm">
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-[#318069]/10 rounded-lg flex items-center justify-center">
                                    <i class="ri-calendar-line text-2xl text-[#318069]"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Availability & Location</h2>
                                    <p class="text-sm text-gray-600">Let patients know when and where to find you</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-8">
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-3">Consultation Mode <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <button data-mode="online" class="mode-btn p-4 rounded-lg border-2 transition-all cursor-pointer border-gray-300 hover:border-gray-400">
                                        <i class="ri-video-line text-3xl mb-2 mx-auto text-gray-400"></i>
                                        <p class="text-sm font-semibold text-gray-700">Online Only</p>
                                    </button>
                                    <button data-mode="in-person" class="mode-btn p-4 rounded-lg border-2 transition-all cursor-pointer border-gray-300 hover:border-gray-400 ">
                                        <i class="ri-hospital-line text-3xl mb-2 mx-auto text-gray-400"></i>
                                        <p class="text-sm font-semibold text-gray-700">In-Person Only</p>
                                    </button>
                                    <button data-mode="both" class="mode-btn p-4 rounded-lg border-2 transition-all cursor-pointer border-gray-300 hover:border-gray-400">
                                        <i class="ri-global-line text-3xl mb-2 mx-auto text-gray-400"></i>
                                        <p class="text-sm font-semibold text-gray-700">Both</p>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-3">Available Days <span class="text-red-500">*</span></label>
                                <div class="flex flex-wrap gap-2">
                                    <button data-day="Mon" class="day-btn px-4 py-2 rounded-lg border-2 font-semibold text-sm transition-all cursor-pointer whitespace-nowrap border-gray-300 text-gray-700 hover:border-gray-400 hover:bg-gray-50">Mon</button>
                                    <button data-day="Tue" class="day-btn px-4 py-2 rounded-lg border-2 font-semibold text-sm transition-all cursor-pointer whitespace-nowrap border-gray-300 text-gray-700 hover:border-gray-400 hover:bg-gray-50">Tue</button>
                                    <button data-day="Wed" class="day-btn px-4 py-2 rounded-lg border-2 font-semibold text-sm transition-all cursor-pointer whitespace-nowrap border-gray-300 text-gray-700 hover:border-gray-400 hover:bg-gray-50">Wed</button>
                                    <button data-day="Thu" class="day-btn px-4 py-2 rounded-lg border-2 font-semibold text-sm transition-all cursor-pointer whitespace-nowrap border-gray-300 text-gray-700 hover:border-gray-400 hover:bg-gray-50">Thu</button>
                                    <button data-day="Fri" class="day-btn px-4 py-2 rounded-lg border-2 font-semibold text-sm transition-all cursor-pointer whitespace-nowrap border-gray-300 text-gray-700 hover:border-gray-400 hover:bg-gray-50">Fri</button>
                                    <button data-day="Sat" class="day-btn px-4 py-2 rounded-lg border-2 font-semibold text-sm transition-all cursor-pointer whitespace-nowrap border-gray-300 text-gray-700 hover:border-gray-400 hover:bg-gray-50">Sat</button>
                                    <button data-day="Sun" class="day-btn px-4 py-2 rounded-lg border-2 font-semibold text-sm transition-all cursor-pointer whitespace-nowrap border-gray-300 text-gray-700 hover:border-gray-400 hover:bg-gray-50">Sun</button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-3">Time Slots <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <button data-slot="morning" class="slot-btn p-4 rounded-lg border-2 font-semibold text-sm transition-all cursor-pointer border-gray-300 text-gray-700 hover:border-gray-400 hover:bg-gray-50">Morning (8AM-12PM)</button>
                                    <button data-slot="afternoon" class="slot-btn p-4 rounded-lg border-2 font-semibold text-sm transition-all cursor-pointer border-gray-300 text-gray-700 hover:border-gray-400 hover:bg-gray-50">Afternoon (12PM-5PM)</button>
                                    <button data-slot="evening" class="slot-btn p-4 rounded-lg border-2 font-semibold text-sm transition-all cursor-pointer border-gray-300 text-gray-700 hover:border-gray-400 hover:bg-gray-50">Evening (5PM-9PM)</button>
                                </div>
                            </div>
                            <div class="border-t-2 border-gray-200 my-8"></div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Clinic / Hospital Location</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">Clinic / Hospital Name <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                                <i class="ri-hospital-line text-gray-400 text-xl"></i>
                                            </div>
                                            <input id="clinic-name" placeholder="e.g., City Medical Center" class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base" type="text">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">Address <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute left-4 top-4">
                                                <i class="ri-map-pin-line text-gray-400 text-xl"></i>
                                            </div>
                                            <textarea id="address" placeholder="Enter full address" rows="3" class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base resize-none"></textarea>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">City <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                                <i class="ri-building-line text-gray-400 text-xl"></i>
                                            </div>
                                            <input id="city" placeholder="e.g., New York" class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base" type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-4 mt-8 pt-8 border-t-2 border-gray-200">
                            <button onclick="switchTab(3)" class="prev-btn px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all whitespace-nowrap cursor-pointer btn-outline-hover">
                                <i class="ri-arrow-left-line mr-2"></i>Back
                            </button>
                            <button onclick="switchTab(5)" class="next-btn flex-1 bg-[#318069] hover:bg-[#276854] text-white px-6 py-3 rounded-lg font-semibold transition-all whitespace-nowrap shadow-lg hover:shadow-xl cursor-pointer btn-primary-hover">
                                Save & Continue<i class="ri-arrow-right-line ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tab 5: Education -->
                    <div id="tab5" class="tab-content bg-white rounded-xl border-2 border-gray-200 p-8 shadow-sm">
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-[#318069]/10 rounded-lg flex items-center justify-center">
                                    <i class="ri-graduation-cap-line text-2xl text-[#318069]"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Education & Training</h2>
                                    <p class="text-sm text-gray-600">Add your medical education background</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-6" id="education-container">
                            <div id="education-entry-1" class="relative bg-gray-50 rounded-xl p-6 border-2 border-gray-200">
                                <div class="absolute left-0 top-0 bottom-0 w-1  rounded-l-xl"></div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">Degree <span class="text-red-500">*</span></label>
                                        <input placeholder="e.g., MBBS, MD, PhD" class="education-degree w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base bg-white" type="text">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">Institution <span class="text-red-500">*</span></label>
                                        <input placeholder="e.g., Harvard Medical School" class="education-institution w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base bg-white" type="text">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">Year <span class="text-red-500">*</span></label>
                                        <input placeholder="e.g., 2015 or 2010-2015" class="education-year w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base bg-white" type="text">
                                    </div>
                                </div>
                            </div>
                            <div id="education-list"></div>
                        </div>
                        <button id="add-education-btn" class="w-full py-4 border-2 border-dashed border-gray-300 rounded-xl text-gray-600 font-semibold hover:border-[#318069] hover:text-[#318069] hover:bg-[#318069]/5 transition-all cursor-pointer whitespace-nowrap mt-6">
                            <i class="ri-add-line inline-flex items-center justify-center mr-2"></i>Add Another Education
                        </button>
                        <div class="flex gap-4 mt-8 pt-8 border-t-2 border-gray-200">
                            <button onclick="switchTab(4)" class="prev-btn px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all whitespace-nowrap cursor-pointer btn-outline-hover">
                                <i class="ri-arrow-left-line mr-2"></i>Back
                            </button>
                            <button onclick="switchTab(6)" class="next-btn flex-1 bg-[#318069] hover:bg-[#276854] text-white px-6 py-3 rounded-lg font-semibold transition-all whitespace-nowrap shadow-lg hover:shadow-xl cursor-pointer btn-primary-hover">
                                Save & Continue<i class="ri-arrow-right-line ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tab 6: Experience -->
                    <div id="tab6" class="tab-content bg-white rounded-xl border-2 border-gray-200 p-8 shadow-sm">
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-[#318069]/10 rounded-lg flex items-center justify-center">
                                    <i class="ri-briefcase-line text-2xl text-[#318069]"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Professional Experience</h2>
                                    <p class="text-sm text-gray-600">Share your work history and achievements</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-6" id="experience-container">
                            <div id="experience-entry-1" class="relative bg-gray-50 rounded-xl p-6 border-2 border-gray-200">
                                <div class="absolute left-0 top-0 bottom-0 w-1  rounded-l-xl"></div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">Position / Role <span class="text-red-500">*</span></label>
                                        <input placeholder="e.g., Senior Cardiologist" class="experience-position w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base bg-white" type="text">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">Organization <span class="text-red-500">*</span></label>
                                        <input placeholder="e.g., City General Hospital" class="experience-organization w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base bg-white" type="text">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">Duration <span class="text-red-500">*</span></label>
                                        <input placeholder="e.g., 2018 - Present or 2015 - 2018" class="experience-duration w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base bg-white" type="text">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">Description <span class="text-gray-400 text-xs font-normal">(Optional)</span></label>
                                        <textarea placeholder="Brief description of your role and achievements..." rows="3" class="experience-description w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base bg-white resize-none"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div id="experience-list"></div>
                        </div>
                        <button id="add-experience-btn" class="w-full py-4 border-2 border-dashed border-gray-300 rounded-xl text-gray-600 font-semibold hover:border-[#318069] hover:text-[#318069] hover:bg-[#318069]/5 transition-all cursor-pointer whitespace-nowrap mt-6">
                            <i class="ri-add-line inline-flex items-center justify-center mr-2"></i>Add Another Experience
                        </button>
                        <div class="flex gap-4 mt-8 pt-8 border-t-2 border-gray-200">
                            <button onclick="switchTab(5)" class="prev-btn px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all whitespace-nowrap cursor-pointer btn-outline-hover">
                                <i class="ri-arrow-left-line mr-2"></i>Back
                            </button>
                            <button onclick="switchTab(7)" class="next-btn flex-1 bg-[#318069] hover:bg-[#276854] text-white px-6 py-3 rounded-lg font-semibold transition-all whitespace-nowrap shadow-lg hover:shadow-xl cursor-pointer btn-primary-hover">
                                Save & Continue<i class="ri-arrow-right-line ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tab 7: Affiliations -->
                    <div id="tab7" class="tab-content bg-white rounded-xl border-2 border-gray-200 p-8 shadow-sm">
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-[#318069]/10 rounded-lg flex items-center justify-center">
                                    <i class="ri-team-line text-2xl text-[#318069]"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Professional Affiliations</h2>
                                    <p class="text-sm text-gray-600">Medical associations, councils, and societies</p>
                                </div>
                            </div>
                            <div class="bg-[#FFC107]/10 border-l-4 border-[#FFC107] rounded-lg p-4 mt-4">
                                <div class="flex gap-3">
                                    <i class="ri-lightbulb-line text-[#FFC107] text-xl flex-shrink-0 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 mb-1">Examples</p>
                                        <p class="text-sm text-gray-700">American Medical Association, Royal College of Physicians, World Health Organization, etc.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Add Affiliation</label>
                            <div class="flex gap-3">
                                <div class="relative flex-1">
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                        <i class="ri-building-2-line text-gray-400 text-xl"></i>
                                    </div>
                                    <input id="affiliation-input" placeholder="e.g., American Medical Association" class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base" type="text" autocomplete="off">
                                </div>
                                <button id="add-affiliation-btn" class="px-6 py-3 bg-[#318069] hover:bg-[#276854] text-white rounded-lg font-semibold transition-all cursor-pointer whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed btn-primary-hover" disabled>
                                    <i class="ri-add-line"></i>
                                </button>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">Press Enter or click + to add</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 mb-3">Your Affiliations (<span id="affiliation-count">0</span>)</p>
                            <div id="affiliations-list" class="space-y-2">
                                <p class="text-gray-500 text-center py-4">No affiliations added yet</p>
                            </div>
                        </div>
                        <div class="flex gap-4 mt-8 pt-8 border-t-2 border-gray-200">
                            <button onclick="switchTab(6)" class="prev-btn px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all whitespace-nowrap cursor-pointer btn-outline-hover">
                                <i class="ri-arrow-left-line mr-2"></i>Back
                            </button>
                            <button onclick="switchTab(8)" class="next-btn flex-1 bg-[#318069] hover:bg-[#276854] text-white px-6 py-3 rounded-lg font-semibold transition-all whitespace-nowrap shadow-lg hover:shadow-xl cursor-pointer btn-primary-hover">
                                Save & Continue<i class="ri-arrow-right-line ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tab 8: Additional Info -->
                    <div id="tab8" class="tab-content bg-white rounded-xl border-2 border-gray-200 p-8 shadow-sm">
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-[#318069]/10 rounded-lg flex items-center justify-center">
                                    <i class="ri-file-list-line text-2xl text-[#318069]"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Additional Information</h2>
                                    <p class="text-sm text-gray-600">Complete your profile with extra details</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-8">
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-3">Languages Spoken <span class="text-gray-400 text-xs font-normal">(Recommended)</span></label>
                                <div class="flex flex-wrap gap-2 mb-4" id="languages-list">
                                    <!-- Languages will be populated by JavaScript -->
                                </div>
                                <div class="flex gap-3">
                                    <input id="custom-language" placeholder="Add other language..." class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-sm" type="text">
                                    <button id="add-language-btn" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold text-sm transition-all cursor-pointer whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed" disabled>Add</button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Consultation Fee <span class="text-gray-400 text-xs font-normal">(Optional)</span></label>
                                <div class="relative max-w-md">
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                        <i class="ri-money-dollar-circle-line text-gray-400 text-xl"></i>
                                    </div>
                                    <input id="consultation-fee" placeholder="e.g., $50 or Free" class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base" type="text">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">This helps patients plan their visit</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">About You <span class="text-gray-400 text-xs font-normal">(Recommended)</span></label>
                                <textarea id="about-you" placeholder="Write a brief introduction about yourself, your approach to patient care, and what makes you unique..." rows="5" maxlength="500" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base resize-none"></textarea>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-xs text-gray-500">A good bio helps patients connect with you</p>
                                    <p id="char-count" class="text-xs text-gray-500">0/500</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Awards & Recognitions <span class="text-gray-400 text-xs font-normal">(Optional)</span></label>
                                <div class="flex gap-3 mb-4">
                                    <input id="award-input" placeholder="e.g., Best Doctor Award 2023" class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base" type="text">
                                    <button id="add-award-btn" class="px-6 py-3 bg-[#318069] hover:bg-[#276854] text-white rounded-lg font-semibold transition-all cursor-pointer whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed btn-primary-hover" disabled>
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>
                                <div id="awards-container">
                                    <div id="awards-empty" class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                        <i class="ri-award-line text-3xl text-gray-400 mx-auto mb-2"></i>
                                        <p class="text-sm text-gray-600">No awards added yet</p>
                                    </div>
                                    <div id="awards-list" class="hidden space-y-2"></div>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-[#318069]/10 to-[#FFC107]/10 border-2 border-[#318069]/20 rounded-xl p-6 mt-8">
                                <div class="flex gap-4">
                                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <i class="ri-rocket-line text-[#318069] text-2xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-900 mb-2">Almost Done!</h4>
                                        <p class="text-sm text-gray-700">You're doing great! Click "Complete Profile" below to finish setting up your professional profile. Patients will be able to find and book appointments with you right away.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-4 mt-8 pt-8 border-t-2 border-gray-200">
                            <button onclick="switchTab(7)" class="prev-btn px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all whitespace-nowrap cursor-pointer btn-outline-hover">
                                <i class="ri-arrow-left-line mr-2"></i>Back
                            </button>
                            <button id="complete-profile-btn" class="flex-1 bg-[#318069] hover:bg-[#276854] text-white px-6 py-3 rounded-lg font-semibold transition-all whitespace-nowrap shadow-lg hover:shadow-xl cursor-pointer btn-primary-hover">
                                Complete Profile<i class="ri-check-line ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-check-line text-3xl text-green-600"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Profile Complete!</h3>
            <p class="text-gray-600 text-center mb-6">Your profile has been successfully updated. Patients can now find and book appointments with you.</p>
            <button id="close-modal" class="w-full bg-[#318069] hover:bg-[#276854] text-white px-6 py-3 rounded-lg font-semibold transition-all shadow-lg hover:shadow-xl">
                Return to Dashboard
            </button>
        </div>
    </div>

    <script>
    // Application State
    const state = {
        currentTab: 1,
        totalTabs: 8,
        completedTabs: new Set(), // Fixed: Added this line
        data: {
            specialties: [],
            personalDetails: {
                country: '',
                license: '',
                qualification: '',
                experience: ''
            },
            photo: null,
            photoPreview: null,
            availability: {
                mode: '',
                days: [],
                timeSlots: [],
                clinicName: '',
                address: '',
                city: ''
            },
            education: [],
            experience: [],
            affiliations: [],
            additionalInfo: {
                languages: ['English'],
                fee: '',
                about: '',
                awards: []
            }
        },
        availableSpecialties: [
            'Cardiology', 'Neurology', 'Pulmonology', 'Pediatrics', 'Orthopedics', 'Ophthalmology',
            'Psychiatry', 'Dermatology', 'Dentistry', 'Gynecology', 'Endocrinology', 'General Practice',
            'Gastroenterology', 'Nephrology', 'Oncology', 'Urology', 'ENT', 'Rheumatology',
            'Anesthesiology', 'Radiology'
        ],
        availableLanguages: [
            'English', 'Spanish', 'French', 'German', 'Chinese', 'Arabic', 
            'Hindi', 'Portuguese', 'Russian', 'Japanese', 'Italian', 'Korean'
        ]
    };

    // Initialize Application
    function init() {
        loadSavedData();
        renderAvailableSpecialties();
        renderLanguages();
        updateSelectedSpecialties();
        updateAffiliationsList();
        updateAwardsList();
        attachEventListeners();
        updateProgress();
        updateSidebar();
    }

    // Load Saved Data
    function loadSavedData() {
        const savedData = JSON.parse(localStorage.getItem('medconnect_profile'));
        if (savedData) {
            state.data.specialties = savedData.specialties || [];
            state.data.personalDetails = savedData.personalDetails || state.data.personalDetails;
            state.data.photoPreview = savedData.photoPreview || null;
            state.data.availability = savedData.availability || state.data.availability;
            state.data.education = savedData.education || [];
            state.data.experience = savedData.experience || [];
            state.data.affiliations = savedData.affiliations || [];
            state.data.additionalInfo = savedData.additionalInfo || state.data.additionalInfo;
            
            if (!state.data.additionalInfo.languages.includes('English')) {
                state.data.additionalInfo.languages.push('English');
            }
            
            updateUIWithLoadedData();
        }
    }

    // Update UI with loaded data
    function updateUIWithLoadedData() {
        if (document.getElementById('country')) {
            document.getElementById('country').value = state.data.personalDetails.country || '';
            document.getElementById('license').value = state.data.personalDetails.license || '';
            document.getElementById('qualification').value = state.data.personalDetails.qualification || '';
            document.getElementById('experience').value = state.data.personalDetails.experience || '';
        }
        
        if (state.data.photoPreview && document.getElementById('photo-preview')) {
            document.getElementById('photo-preview').src = state.data.photoPreview;
            document.getElementById('photo-upload-area').classList.add('hidden');
            document.getElementById('photo-preview-container').classList.remove('hidden');
        }
        
        if (document.getElementById('char-count')) {
            document.getElementById('char-count').textContent = `${state.data.additionalInfo.about?.length || 0}/500`;
        }
    }

    // Render Available Specialties
    function renderAvailableSpecialties() {
        const specialtiesList = document.getElementById('specialties-list');
        if (!specialtiesList) return;
        
        specialtiesList.innerHTML = '';
        
        const availableToShow = state.availableSpecialties.filter(spec => 
            !state.data.specialties.includes(spec)
        );
        
        availableToShow.forEach(spec => {
            const button = document.createElement('button');
            button.className = 'specialty-btn inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-semibold hover:bg-gray-200 transition-all cursor-pointer whitespace-nowrap smooth-transition';
            button.dataset.specialty = spec;
            button.innerHTML = `<i class="ri-add-line"></i>${spec}`;
            button.onclick = function() {
                if (!state.data.specialties.includes(spec)) {
                    state.data.specialties.push(spec);
                    updateSelectedSpecialties();
                    renderAvailableSpecialties();
                    saveTabData();
                }
            };
            specialtiesList.appendChild(button);
        });
    }

    // Render Languages
    function renderLanguages() {
        const languagesList = document.getElementById('languages-list');
        if (!languagesList) return;
        
        languagesList.innerHTML = '';
        
        state.availableLanguages.forEach(lang => {
            const isSelected = state.data.additionalInfo.languages.includes(lang);
            const button = document.createElement('button');
            button.className = `language-btn px-4 py-2 rounded-full text-sm font-semibold transition-all cursor-pointer whitespace-nowrap smooth-transition ${isSelected ? 'bg-[#318069] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'}`;
            button.dataset.language = lang;
            button.textContent = lang;
            button.onclick = function() {
                if (lang === 'English' && state.data.additionalInfo.languages.length === 1) {
                    return;
                }
                
                if (state.data.additionalInfo.languages.includes(lang)) {
                    state.data.additionalInfo.languages = state.data.additionalInfo.languages.filter(l => l !== lang);
                    button.classList.remove('bg-[#318069]', 'text-white');
                    button.classList.add('bg-gray-100', 'text-gray-700');
                } else {
                    state.data.additionalInfo.languages.push(lang);
                    button.classList.add('bg-[#318069]', 'text-white');
                    button.classList.remove('bg-gray-100', 'text-gray-700');
                }
                saveTabData();
            };
            languagesList.appendChild(button);
        });
    }

    // Update Selected Specialties Display
    function updateSelectedSpecialties() {
        const container = document.getElementById('selected-specialties-container');
        const list = document.getElementById('selected-specialties');
        const count = document.getElementById('selected-count');
        
        if (!container || !list || !count) return;
        
        if (state.data.specialties.length === 0) {
            list.innerHTML = '<p class="text-gray-500 text-sm italic">No specialties selected yet</p>';
            count.textContent = '0';
        } else {
            list.innerHTML = state.data.specialties.map(spec => `
                <button data-specialty="${spec}" class="selected-specialty inline-flex items-center gap-2 px-4 py-2 bg-[#318069] text-white rounded-full text-sm font-semibold hover:bg-[#276854] transition-all cursor-pointer whitespace-nowrap smooth-transition">
                    ${spec}<i class="ri-close-line"></i>
                </button>
            `).join('');
            count.textContent = state.data.specialties.length;
            
            document.querySelectorAll('.selected-specialty').forEach(btn => {
                btn.onclick = function() {
                    const specialty = btn.dataset.specialty;
                    state.data.specialties = state.data.specialties.filter(s => s !== specialty);
                    updateSelectedSpecialties();
                    renderAvailableSpecialties();
                    saveTabData();
                };
            });
        }
    }

    // Update Affiliations List
    function updateAffiliationsList() {
        const list = document.getElementById('affiliations-list');
        const count = document.getElementById('affiliation-count');
        
        if (!list || !count) return;
        
        if (state.data.affiliations.length === 0) {
            list.innerHTML = '<p class="text-gray-500 text-center py-4">No affiliations added yet</p>';
        } else {
            list.innerHTML = state.data.affiliations.map(aff => `
                <div class="flex items-center justify-between bg-gray-50 rounded-lg p-4 border-2 border-gray-200">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="w-10 h-10 bg-[#318069]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="ri-shield-check-line text-[#318069] text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-900">${aff}</p>
                    </div>
                    <button data-affiliation="${aff}" class="remove-affiliation w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg flex items-center justify-center transition-all cursor-pointer flex-shrink-0 smooth-transition">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            `).join('');
            
            document.querySelectorAll('.remove-affiliation').forEach(btn => {
                btn.onclick = function() {
                    const affiliation = btn.dataset.affiliation;
                    state.data.affiliations = state.data.affiliations.filter(a => a !== affiliation);
                    updateAffiliationsList();
                    saveTabData();
                };
            });
        }
        count.textContent = state.data.affiliations.length;
    }

    // Update Awards List
    function updateAwardsList() {
        const empty = document.getElementById('awards-empty');
        const list = document.getElementById('awards-list');
        
        if (!empty || !list) return;
        
        if (state.data.additionalInfo.awards.length === 0) {
            empty.classList.remove('hidden');
            list.classList.add('hidden');
            list.innerHTML = '';
        } else {
            empty.classList.add('hidden');
            list.classList.remove('hidden');
            list.innerHTML = state.data.additionalInfo.awards.map(award => `
                <div class="flex items-center justify-between bg-gray-50 rounded-lg p-4 border-2 border-gray-200">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="ri-award-line text-yellow-600 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-900">${award}</p>
                    </div>
                    <button data-award="${award}" class="remove-award w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg flex items-center justify-center transition-all cursor-pointer flex-shrink-0 smooth-transition">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            `).join('');
            
            document.querySelectorAll('.remove-award').forEach(btn => {
                btn.onclick = function() {
                    const award = btn.dataset.award;
                    state.data.additionalInfo.awards = state.data.additionalInfo.awards.filter(a => a !== award);
                    updateAwardsList();
                    saveTabData();
                };
            });
        }
    }

    // Update Progress Circle
    function updateProgress() {
        // Calculate completed tabs
        let completed = 0;
        for (let i = 1; i <= state.totalTabs; i++) {
            if (hasTabData(i)) completed++;
        }
        
        const progress = (completed / state.totalTabs) * 100;
        const circle = document.getElementById('progress-circle');
        const percent = document.getElementById('progress-percent');
        
        const circumference = 2 * Math.PI * 28;
        const offset = circumference - (progress / 100) * circumference;
        
        if (circle) circle.style.strokeDashoffset = offset;
        if (percent) percent.textContent = `${Math.round(progress)}%`;
    }

    // Update Sidebar Active State
    function updateSidebar() {
        document.querySelectorAll('.sidebar-btn').forEach(btn => {
            const tabId = parseInt(btn.dataset.tab);
            const arrowIcon = btn.querySelector('.ri-arrow-right-s-line');
            
            if (tabId === state.currentTab) {
                btn.classList.add('active');
                if (arrowIcon) arrowIcon.classList.remove('hidden');
            } else {
                btn.classList.remove('active');
                if (arrowIcon) arrowIcon.classList.add('hidden');
            }
        });
    }

    // Switch Tab Function
    window.switchTab = function(tabNumber) {
        saveTabData();
        
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        const selectedTab = document.getElementById(`tab${tabNumber}`);
        if (selectedTab) {
            selectedTab.classList.add('active');
            state.currentTab = tabNumber;
            updateSidebar();
            loadTabData();
        }
    }

    // Load Tab Data
    function loadTabData() {
        switch(state.currentTab) {
            case 4:
                if (state.data.availability.mode) {
                    document.querySelectorAll('.mode-btn').forEach(btn => {
                        btn.classList.remove('border-[#318069]', 'bg-[#318069]', 'text-white');
                        btn.querySelector('i')?.classList.remove('text-white');
                        btn.querySelector('p')?.classList.remove('text-white');
                    });
                    
                    const selectedModeBtn = document.querySelector(`[data-mode="${state.data.availability.mode}"]`);
                    if (selectedModeBtn) {
                        selectedModeBtn.classList.add('border-[#318069]', 'bg-[#318069]', 'text-white');
                        selectedModeBtn.querySelector('i')?.classList.add('text-white');
                        selectedModeBtn.querySelector('p')?.classList.add('text-white');
                    }
                }
                
                document.querySelectorAll('.day-btn').forEach(btn => {
                    const day = btn.dataset.day;
                    if (state.data.availability.days.includes(day)) {
                        btn.classList.add('border-[#318069]', 'bg-[#318069]', 'text-white');
                        btn.classList.remove('border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                    } else {
                        btn.classList.remove('border-[#318069]', 'bg-[#318069]', 'text-white');
                        btn.classList.add('border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                    }
                });
                
                document.querySelectorAll('.slot-btn').forEach(btn => {
                    const slot = btn.dataset.slot;
                    if (state.data.availability.timeSlots.includes(slot)) {
                        btn.classList.add('border-[#318069]', 'bg-[#318069]', 'text-white');
                        btn.classList.remove('border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                    } else {
                        btn.classList.remove('border-[#318069]', 'bg-[#318069]', 'text-white');
                        btn.classList.add('border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                    }
                });
                
                if (document.getElementById('clinic-name')) {
                    document.getElementById('clinic-name').value = state.data.availability.clinicName || '';
                    document.getElementById('address').value = state.data.availability.address || '';
                    document.getElementById('city').value = state.data.availability.city || '';
                }
                break;
                
            case 8:
                if (document.getElementById('consultation-fee')) {
                    document.getElementById('consultation-fee').value = state.data.additionalInfo.fee || '';
                    document.getElementById('about-you').value = state.data.additionalInfo.about || '';
                    document.getElementById('char-count').textContent = `${state.data.additionalInfo.about?.length || 0}/500`;
                }
                break;
        }
    }

    // Save Current Tab Data
    function saveTabData() {
        switch(state.currentTab) {
            case 1:
                break;
            case 2:
                if (document.getElementById('country')) {
                    state.data.personalDetails = {
                        country: document.getElementById('country').value,
                        license: document.getElementById('license').value,
                        qualification: document.getElementById('qualification').value,
                        experience: document.getElementById('experience').value
                    };
                }
                break;
            case 4:
                if (document.getElementById('clinic-name')) {
                    state.data.availability.clinicName = document.getElementById('clinic-name').value || '';
                    state.data.availability.address = document.getElementById('address').value || '';
                    state.data.availability.city = document.getElementById('city').value || '';
                }
                break;
            case 8:
                if (document.getElementById('consultation-fee')) {
                    state.data.additionalInfo.fee = document.getElementById('consultation-fee').value || '';
                    state.data.additionalInfo.about = document.getElementById('about-you').value || '';
                }
                break;
        }
        
        localStorage.setItem('medconnect_profile', JSON.stringify(state.data));
        updateProgress();
    }

    // Check if tab has data
    function hasTabData(tabId) {
        switch(tabId) {
            case 1: return state.data.specialties.length > 0;
            case 2: 
                return state.data.personalDetails.country && 
                       state.data.personalDetails.license && 
                       state.data.personalDetails.qualification;
            case 3: return state.data.photoPreview !== null;
            case 4: 
                return state.data.availability.mode && 
                       state.data.availability.days.length > 0 &&
                       state.data.availability.timeSlots.length > 0 &&
                       state.data.availability.clinicName &&
                       state.data.availability.address &&
                       state.data.availability.city;
            case 5: return state.data.education.length > 0;
            case 6: return state.data.experience.length > 0;
            case 7: return state.data.affiliations.length > 0;
            case 8: return state.data.additionalInfo.about.length > 0;
            default: return false;
        }
    }

    // Handle photo upload
    function handlePhotoUpload(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                state.data.photoPreview = e.target.result;
                document.getElementById('photo-preview').src = e.target.result;
                document.getElementById('photo-upload-area').classList.add('hidden');
                document.getElementById('photo-preview-container').classList.remove('hidden');
                saveTabData();
            };
            reader.readAsDataURL(file);
        }
    }

    // Attach Event Listeners
    function attachEventListeners() {
        // Sidebar navigation
        document.querySelectorAll('.sidebar-btn').forEach(btn => {
            btn.onclick = function() {
                const tabId = parseInt(btn.dataset.tab);
                if (tabId !== state.currentTab) {
                    switchTab(tabId);
                }
            };
        });
        
        // Photo upload
        document.getElementById('photo-input')?.addEventListener('change', handlePhotoUpload);
        document.getElementById('replace-photo-input')?.addEventListener('change', handlePhotoUpload);
        
        // Remove photo
        document.querySelector('.remove-photo')?.addEventListener('click', function() {
            state.data.photoPreview = null;
            document.getElementById('photo-upload-area').classList.remove('hidden');
            document.getElementById('photo-preview-container').classList.add('hidden');
            document.getElementById('photo-input').value = '';
            document.getElementById('replace-photo-input').value = '';
            saveTabData();
        });
        
        // Availability buttons
        document.querySelectorAll('.mode-btn').forEach(btn => {
            btn.onclick = function() {
                const mode = btn.dataset.mode;
                
                document.querySelectorAll('.mode-btn').forEach(b => {
                    b.classList.remove('border-[#318069]', 'bg-[#318069]', 'text-white');
                    b.querySelector('i')?.classList.remove('text-white');
                    b.querySelector('p')?.classList.remove('text-white');
                });
                
                btn.classList.add('border-[#318069]', 'bg-[#318069]', 'text-white');
                btn.querySelector('i')?.classList.add('text-white');
                btn.querySelector('p')?.classList.add('text-white');
                
                state.data.availability.mode = mode;
                saveTabData();
            };
        });
        
        document.querySelectorAll('.day-btn').forEach(btn => {
            btn.onclick = function() {
                const day = btn.dataset.day;
                
                if (state.data.availability.days.includes(day)) {
                    state.data.availability.days = state.data.availability.days.filter(d => d !== day);
                    btn.classList.remove('border-[#318069]', 'bg-[#318069]', 'text-white');
                    btn.classList.add('border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                } else {
                    state.data.availability.days.push(day);
                    btn.classList.add('border-[#318069]', 'bg-[#318069]', 'text-white');
                    btn.classList.remove('border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                }
                saveTabData();
            };
        });
        
        document.querySelectorAll('.slot-btn').forEach(btn => {
            btn.onclick = function() {
                const slot = btn.dataset.slot;
                
                if (state.data.availability.timeSlots.includes(slot)) {
                    state.data.availability.timeSlots = state.data.availability.timeSlots.filter(s => s !== slot);
                    btn.classList.remove('border-[#318069]', 'bg-[#318069]', 'text-white');
                    btn.classList.add('border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                } else {
                    state.data.availability.timeSlots.push(slot);
                    btn.classList.add('border-[#318069]', 'bg-[#318069]', 'text-white');
                    btn.classList.remove('border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                }
                saveTabData();
            };
        });
        
        // Add education
        document.getElementById('add-education-btn')?.addEventListener('click', function() {
            const educationCount = document.querySelectorAll('.education-degree').length;
            const newId = `education-entry-${educationCount + 1}`;
            
            const newEntry = document.createElement('div');
            newEntry.id = newId;
            newEntry.className = 'relative bg-gray-50 rounded-xl p-6 border-2 border-gray-200 mb-4';
            newEntry.innerHTML = `
                <div class="absolute left-0 top-0 bottom-0 w-1  rounded-l-xl"></div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Degree <span class="text-red-500">*</span></label>
                        <input placeholder="e.g., MBBS, MD, PhD" class="education-degree w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base bg-white" type="text">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Institution <span class="text-red-500">*</span></label>
                        <input placeholder="e.g., Harvard Medical School" class="education-institution w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base bg-white" type="text">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Year <span class="text-red-500">*</span></label>
                        <input placeholder="e.g., 2015 or 2010-2015" class="education-year w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base bg-white" type="text">
                    </div>
                </div>
            `;
            
            document.getElementById('education-list').appendChild(newEntry);
        });
        
        // Add experience
        document.getElementById('add-experience-btn')?.addEventListener('click', function() {
            const experienceCount = document.querySelectorAll('.experience-position').length;
            const newId = `experience-entry-${experienceCount + 1}`;
            
            const newEntry = document.createElement('div');
            newEntry.id = newId;
            newEntry.className = 'relative bg-gray-50 rounded-xl p-6 border-2 border-gray-200 mb-4';
            newEntry.innerHTML = `
                <div class="absolute left-0 top-0 bottom-0 w-1  rounded-l-xl"></div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Position / Role <span class="text-red-500">*</span></label>
                        <input placeholder="e.g., Senior Cardiologist" class="experience-position w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base bg-white" type="text">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Organization <span class="text-red-500">*</span></label>
                        <input placeholder="e.g., City General Hospital" class="experience-organization w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base bg-white" type="text">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Duration <span class="text-red-500">*</span></label>
                        <input placeholder="e.g., 2018 - Present or 2015 - 2018" class="experience-duration w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base bg-white" type="text">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Description <span class="text-gray-400 text-xs font-normal">(Optional)</span></label>
                        <textarea placeholder="Brief description of your role and achievements..." rows="3" class="experience-description w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base bg-white resize-none"></textarea>
                    </div>
                </div>
            `;
            
            document.getElementById('experience-list').appendChild(newEntry);
        });
        
        // Add affiliation
        document.getElementById('add-affiliation-btn')?.addEventListener('click', function() {
            const input = document.getElementById('affiliation-input');
            const value = input.value.trim();
            if (value && !state.data.affiliations.includes(value)) {
                state.data.affiliations.push(value);
                updateAffiliationsList();
                input.value = '';
                document.getElementById('add-affiliation-btn').disabled = true;
                saveTabData();
            }
        });
        
        // Add custom language
        document.getElementById('add-language-btn')?.addEventListener('click', function() {
            const input = document.getElementById('custom-language');
            const value = input.value.trim();
            if (value && !state.data.additionalInfo.languages.includes(value)) {
                state.data.additionalInfo.languages.push(value);
                
                if (!state.availableLanguages.includes(value)) {
                    state.availableLanguages.push(value);
                }
                
                renderLanguages();
                input.value = '';
                document.getElementById('add-language-btn').disabled = true;
                saveTabData();
            }
        });
        
        // Add award
        document.getElementById('add-award-btn')?.addEventListener('click', function() {
            const input = document.getElementById('award-input');
            const value = input.value.trim();
            if (value && !state.data.additionalInfo.awards.includes(value)) {
                state.data.additionalInfo.awards.push(value);
                updateAwardsList();
                input.value = '';
                document.getElementById('add-award-btn').disabled = true;
                saveTabData();
            }
        });
        
        // Complete profile
        document.getElementById('complete-profile-btn')?.addEventListener('click', function() {
            saveTabData();
            document.getElementById('success-modal').classList.remove('hidden');
        });
        
        // Close modal
        document.getElementById('close-modal')?.addEventListener('click', function() {
            document.getElementById('success-modal').classList.add('hidden');
        });
        
        // Input event listeners
        document.getElementById('affiliation-input')?.addEventListener('input', function(e) {
            const btn = document.getElementById('add-affiliation-btn');
            if (btn) btn.disabled = e.target.value.trim() === '';
        });
        
        document.getElementById('about-you')?.addEventListener('input', function(e) {
            const charCount = document.getElementById('char-count');
            if (charCount) charCount.textContent = `${e.target.value.length}/500`;
        });
        
        document.getElementById('custom-language')?.addEventListener('input', function(e) {
            const btn = document.getElementById('add-language-btn');
            if (btn) btn.disabled = e.target.value.trim() === '';
        });
        
        document.getElementById('award-input')?.addEventListener('input', function(e) {
            const btn = document.getElementById('add-award-btn');
            if (btn) btn.disabled = e.target.value.trim() === '';
        });
        
        // Keypress event listeners
        document.getElementById('affiliation-input')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const btn = document.getElementById('add-affiliation-btn');
                if (btn && !btn.disabled) btn.click();
            }
        });
        
        document.getElementById('award-input')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const btn = document.getElementById('add-award-btn');
                if (btn && !btn.disabled) btn.click();
            }
        });
        
        document.getElementById('custom-language')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const btn = document.getElementById('add-language-btn');
                if (btn && !btn.disabled) btn.click();
            }
        });
    }

    // Initialize the application
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
</script>
  
@endsection