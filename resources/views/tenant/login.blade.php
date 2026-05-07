<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Doctor Directory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .bg-primary {
            background: linear-gradient(135deg, #318069 0%, #276854 100%);
        }
        .bg-primary-soft {
            background: rgba(49, 128, 105, 0.05);
        }
        .text-primary {
            color: #318069;
        }
        .border-primary {
            border-color: #318069;
        }
        .focus\:ring-primary:focus {
            ring-color: #318069;
        }
        .focus\:border-primary:focus {
            border-color: #318069;
        }
        .btn-primary {
            background: linear-gradient(135deg, #318069 0%, #276854 100%);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #276854 0%, #1f5443 100%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-white to-teal-50/30 flex items-center justify-center min-h-screen px-4">
    
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-[#318069]/10 to-[#318069]/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-gradient-to-tr from-[#FFC107]/5 to-[#FFC107]/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-[#318069]/3 rounded-full blur-2xl"></div>
        <div class="absolute top-20 right-1/4 w-32 h-32 border-2 border-[#318069]/10 rounded-2xl rotate-12"></div>
        <div class="absolute bottom-40 left-1/3 w-24 h-24 border-2 border-[#FFC107]/10 rounded-full"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(49,128,105,0.04)_1px,transparent_1px),linear-gradient(90deg,rgba(49,128,105,0.04)_1px,transparent_1px)] bg-[size:64px_64px]"></div>
    </div>

    <!-- Main Card -->
    <div class="relative z-10 w-full max-w-md">
        
      

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 md:p-8">
            
          <!-- Logo Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-[#318069] to-[#276854] rounded-2xl shadow-lg mb-4">
                <i class="ri-hospital-line text-3xl text-white"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome</h2>
            <p class="text-gray-500 text-sm">Sign in to access your dashboard</p>
        </div>

            <!-- Session Status -->
            @if (session('error'))
                <div class="mb-6 p-3 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <div class="flex items-center">
                        <i class="ri-error-warning-line text-red-500 mr-2"></i>
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-3 bg-green-50 border-l-4 border-green-500 rounded-lg">
                    <div class="flex items-center">
                        <i class="ri-checkbox-circle-line text-green-500 mr-2"></i>
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <!-- Email / NID Field -->
                <div class="mb-5">
                    <label for="nid" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="ri-mail-line mr-1 text-[#318069]"></i> Email Address
                    </label>
                    <div class="relative">
                        <i class="ri-mail-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input id="nid" type="email" name="nid" value="{{ old('nid') }}" required autofocus
                            placeholder="doctor@example.com"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 placeholder:text-gray-400 focus:border-[#318069] focus:ring-2 focus:ring-[#318069]/20 focus:bg-white transition-all duration-200">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="ri-error-warning-line mr-1 text-xs"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-5">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="ri-lock-line mr-1 text-[#318069]"></i> Password
                    </label>
                    <div class="relative">
                        <i class="ri-lock-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input id="password" type="password" name="password" required
                            placeholder="••••••••"
                            class="w-full pl-10 pr-12 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 placeholder:text-gray-400 focus:border-[#318069] focus:ring-2 focus:ring-[#318069]/20 focus:bg-white transition-all duration-200">
                        <button type="button" onclick="togglePassword()" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#318069] transition-colors">
                            <i id="passwordToggleIcon" class="ri-eye-line text-xl"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="ri-error-warning-line mr-1 text-xs"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" id="remember_me"
                            class="w-4 h-4 rounded border-gray-300 text-[#318069] focus:ring-[#318069]/20 focus:ring-2">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>

                    @if (Route::has('reset-password'))
                        <a href="{{ route('reset-password') }}" 
                            class="text-sm text-[#318069] hover:text-[#276854] font-medium transition-colors">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <button type="submit"
                    class="w-full bg-gradient-to-r from-[#318069] to-[#276854] text-white py-3 rounded-xl font-semibold hover:shadow-lg hover:shadow-[#318069]/20 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                    <i class="ri-login-circle-line text-xl"></i>
                    Sign In
                </button>

                
            </form>
        </div>

        <!-- Footer Text -->
        <p class="text-center text-xs text-gray-400 mt-6">
            © {{ date('Y') }} Doctor Directory. All rights reserved.
        </p>
    </div>

    <!-- Password Toggle Script -->
    <script>
        function togglePassword() {
            const input = document.getElementById("password");
            const icon = document.getElementById("passwordToggleIcon");
            
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("ri-eye-line");
                icon.classList.add("ri-eye-off-line");
            } else {
                input.type = "password";
                icon.classList.remove("ri-eye-off-line");
                icon.classList.add("ri-eye-line");
            }
        }
    </script>
</body>
</html>