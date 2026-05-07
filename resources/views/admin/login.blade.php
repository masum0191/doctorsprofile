@extends('layouts.sass')
@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#318069]/5 via-white to-[#FFC107]/5 py-16 sm:py-20 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    
    <!-- Background Decorative Elements - Responsive -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-64 sm:w-80 h-64 sm:h-80 bg-[#318069]/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-64 sm:w-80 h-64 sm:h-80 bg-[#FFC107]/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-72 sm:w-96 h-72 sm:h-96 bg-gradient-to-r from-[#318069]/5 to-[#FFC107]/5 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-[90%] xs:max-w-sm sm:max-w-md md:max-w-lg lg:max-w-md relative z-10">
      

        <!-- Flash Messages - Responsive -->
        @if (session('success'))
            <div class="mb-4 sm:mb-6 rounded-lg sm:rounded-xl bg-emerald-50 border border-emerald-200 p-3 sm:p-4 flex items-center gap-2 sm:gap-3">
                <div class="flex-shrink-0">
                    <i class="ri-checkbox-circle-line text-emerald-600 text-base sm:text-xl"></i>
                </div>
                <p class="text-xs sm:text-sm text-emerald-700">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any() && !($errors->has('email') || $errors->has('password')))
            <div class="mb-4 sm:mb-6 rounded-lg sm:rounded-xl bg-rose-50 border border-rose-200 p-3 sm:p-4 flex items-center gap-2 sm:gap-3">
                <div class="flex-shrink-0">
                    <i class="ri-error-warning-line text-rose-600 text-base sm:text-xl"></i>
                </div>
                <p class="text-xs sm:text-sm text-rose-700">{{ __('These credentials do not match our records.') }}</p>
            </div>
        @endif

        <!-- Login Card - Responsive -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Card Header - Responsive Gradient -->
            <div class="text-center bg-gradient-to-r from-[#318069] to-[#276854] px-4 sm:px-6 py-4">
                 <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-white">Welcome!</h2>
                 <p class="text-xs sm:text-sm text-white mt-1 sm:mt-2 px-4">Sign in to access your doctor dashboard</p>
            </div>

            <!-- Card Body - Responsive Padding -->
            <div class="p-4 sm:p-6 md:p-8">
                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4 sm:space-y-5 md:space-y-6">
                    @csrf

                    <div >
                        <label for="email" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2 sm:pl-3 flex items-center pointer-events-none">
                                <i class="ri-mail-line text-gray-400 text-sm sm:text-base"></i>
                            </div>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                class="block w-full pl-8 sm:pl-10 pr-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-200 rounded-lg sm:rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#318069] focus:border-transparent transition-all duration-200"
                                placeholder="doctor@example.com"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-rose-600 flex items-center gap-1">
                                <i class="ri-information-line"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field - Responsive with Toggle -->
                    <div>
                        <label for="password" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2 sm:pl-3 flex items-center pointer-events-none">
                                <i class="ri-lock-line text-gray-400 text-sm sm:text-base"></i>
                            </div>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                class="block w-full pl-8 sm:pl-10 pr-10 py-2 sm:py-3 text-sm sm:text-base border border-gray-200 rounded-lg sm:rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#318069] focus:border-transparent transition-all duration-200"
                                placeholder="••••••••"
                            >
                            <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-2 sm:pr-3 flex items-center">
                                <i id="passwordToggleIcon" class="ri-eye-line text-gray-400 hover:text-[#318069] text-sm sm:text-base"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-rose-600 flex items-center gap-1">
                                <i class="ri-information-line"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember & Forgot - Responsive Stack on Mobile -->
                    <div class="flex items-center justify-between gap-3 xs:gap-0">
                        <label class="flex items-center gap-1 sm:gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" value="1" class="w-3 h-3 sm:w-4 sm:h-4 rounded border-gray-300 text-[#318069] focus:ring-[#318069] focus:ring-offset-0 transition">
                            <span class="text-xs sm:text-sm text-gray-600">Remember me</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('admin.password.request') }}" class="text-xs sm:text-sm text-[#318069] hover:text-[#276854] font-medium flex items-center gap-1">
                                Forgot password?
                                <i class="ri-arrow-right-s-line text-sm sm:text-base"></i>
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button - Responsive -->
                    <button type="submit" 
                        class="w-full bg-gradient-to-r from-[#318069] to-[#276854] text-white py-2.5 sm:py-3.5 rounded-lg sm:rounded-xl text-sm sm:text-base font-semibold hover:shadow-lg hover:shadow-[#318069]/20 transition-all duration-200 flex items-center justify-center gap-1 sm:gap-2 group">
                        <i class="ri-login-circle-line text-base sm:text-xl group-hover:scale-110 transition-transform"></i>
                        <span>Sign In</span>
                    </button>
                    <p class="text-sm text-gray-600 mt-2 text-center">
                        Don’t have a doctor profile? 
                        <a href="#" class="text-[#318069] font-medium hover:underline">
                            Create one
                        </a>
                    </p>
                   
                </form>
            </div>
        </div>

        <!-- Footer Links - Responsive -->
        <p class="text-center text-xs sm:text-sm text-gray-600 mt-4 sm:mt-6">
            Need help? 
            <a href="" class="text-[#318069] hover:text-[#276854] font-medium hover:underline">
                Contact Support
            </a>
        </p>
    </div>
</div>

<!-- Password Toggle Script -->
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('passwordToggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('ri-eye-line');
            toggleIcon.classList.add('ri-eye-off-line');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('ri-eye-off-line');
            toggleIcon.classList.add('ri-eye-line');
        }
    }

    // Prevent zoom on input focus for iOS
    document.addEventListener('touchstart', function() {
        document.querySelectorAll('input, select, textarea').forEach(function(element) {
            element.style.fontSize = '16px';
        });
    });
</script>

<style>
    @media (max-width: 360px) {
        .xs\:max-w-sm {
            max-width: 90%;
        }
        .xs\:flex-row {
            flex-direction: column;
        }
        .xs\:items-center {
            align-items: flex-start;
        }
    }

    /* Small devices (phones, 640px and down) */
    @media (max-width: 640px) {
        input, select, textarea {
            font-size: 16px !important; /* Prevents zoom on iOS */
        }
    }

    /* Landscape mode */
    @media (max-height: 480px) and (orientation: landscape) {
        .min-h-screen {
            min-height: auto;
            padding: 2rem 1rem;
        }
    }
</style>
@endsection