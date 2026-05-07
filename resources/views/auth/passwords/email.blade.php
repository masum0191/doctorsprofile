@extends('layouts.sass')
@section('title', 'Forgot Password')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
    
    <div class="w-full max-w-[90%] xs:max-w-sm sm:max-w-md">
        

        <!-- Status Message - Responsive -->
        @if (session('status'))
            <div class="mt-4 sm:mt-6 bg-green-50 border border-green-200 rounded-lg p-3 sm:p-4">
                <p class="text-xs sm:text-sm text-green-600">{{ session('status') }}</p>
            </div>
        @endif

        <!-- Form Card - Responsive -->
        <div class="mt-6 sm:mt-8 bg-white rounded-xl sm:rounded-2xl shadow-xl p-5 sm:p-6 md:p-8">
          <!-- Header - Responsive -->
        <div class="text-center">
          <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 bg-[#318069] rounded-xl sm:rounded-2xl shadow-lg mb-3 sm:mb-4">
                <i class="ri-lock-password-line text-xl sm:text-3xl text-white"></i>
            </div>
            <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Forgot Password?</h2>
            <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-gray-600 px-4">Enter your email to reset your password</p>
        </div>
            <form method="POST" action="{{ route('admin.password.email') }}" class="space-y-4 sm:space-y-5 md:space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <i class="ri-mail-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm sm:text-base"></i>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus
                            class="block w-full pl-9 sm:pl-10 pr-3 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:outline-none focus:ring-2 focus:ring-[#318069] focus:border-transparent"
                            placeholder="doctor@example.com"
                        >
                    </div>
                    @error('email') 
                        <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

              

                <!-- Submit Button -->
                <button type="submit" 
                    class="w-full bg-[#318069] text-white py-2.5 sm:py-3 rounded-lg sm:rounded-xl text-sm sm:text-base font-semibold hover:bg-[#276854] transition-colors duration-200 shadow-lg shadow-[#318069]/20 flex items-center justify-center gap-2">
                    <i class="ri-mail-send-line"></i>
                    Send Reset Link
                </button>

                <!-- Back to Login -->
                <div class="text-center mt-4">
                    <a href="{{ route('admin.login') }}" class="inline-flex items-center text-xs sm:text-sm text-gray-600 hover:text-[#318069] transition-colors">
                        <i class="ri-arrow-left-line mr-1"></i>
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Prevent zoom on iOS -->
<script>
    document.addEventListener('touchstart', function() {
        document.querySelectorAll('input').forEach(function(input) {
            input.style.fontSize = '16px';
        });
    });
</script>
@endsection