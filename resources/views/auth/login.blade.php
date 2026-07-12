<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Knights of the Altar</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/koa-logo.jpg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans antialiased min-h-screen bg-white">

    <div class="flex min-h-screen">
        <!-- Left Side (Branding) -->
        <div class="hidden lg:flex lg:w-3/5 bg-[#246b9c] relative flex-col items-center justify-center p-12 overflow-hidden">
            <!-- Decorative circles -->
            <div class="absolute top-12 left-12 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
            <div class="absolute bottom-12 right-12 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>

            <div class="relative z-10 flex flex-col items-center text-center text-white max-w-lg">
                <!-- Logo -->
                <div class="mb-8 w-28 h-28 bg-white rounded-3xl p-1 shadow-2xl flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/koa-logo.jpg') }}" alt="KOA Logo" class="w-full h-full object-cover rounded-2xl">
                </div>

                <h1 class="text-4xl font-extrabold tracking-tight mb-2">Knights of the Altar</h1>
                <p class="text-lg font-medium text-white/90 tracking-widest uppercase mb-10">STO. ROSARIO TORIL</p>

                <!-- Facebook Button -->
                <a href="https://www.facebook.com/search/top?q=knights%20of%20the%20altar%20-%20sto.%20rosario%20parish%20of%20toril" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="inline-flex items-center justify-center space-x-2 bg-white text-[#246b9c] hover:bg-gray-50 px-8 py-3 rounded-xl font-bold shadow-lg transition-all duration-200 transform hover:-translate-y-1">
                    <svg class="w-5 h-5 text-[#1877f2]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span>Visit our Facebook Page</span>
                </a>
                
                <p class="mt-16 text-sm text-white/60 font-medium italic">Ave Maria, Gratia Plena</p>
            </div>
        </div>

        <!-- Right Side (Login Form) -->
        <div class="w-full lg:w-2/5 flex flex-col items-center justify-center p-8 sm:p-12 lg:p-16">
            <div class="w-full max-w-sm">
                <!-- Mobile Logo (visible only on small screens) -->
                <div class="lg:hidden flex justify-center mb-8">
                    <div class="w-20 h-20 bg-white rounded-2xl p-1 shadow-md flex items-center justify-center overflow-hidden border border-gray-100">
                        <img src="{{ asset('images/koa-logo.jpg') }}" alt="KOA Logo" class="w-full h-full object-cover rounded-xl">
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                    <p class="text-sm text-gray-500">Sign in to your account to continue</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="username" class="block text-xs font-semibold text-gray-700 mb-1.5">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="user" class="h-4 w-4 text-gray-400"></i>
                            </div>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                autofocus
                                class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none transition-all placeholder-gray-400"
                                placeholder="Enter your username"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-700 mb-1.5">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="h-4 w-4 text-gray-400"></i>
                            </div>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                class="w-full pl-10 pr-10 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none transition-all placeholder-gray-400"
                                placeholder="Enter your password"
                            >
                            <button
                                type="button"
                                id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                            >
                                <i id="eyeIcon" data-lucide="eye" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#246b9c] hover:bg-[#1a547b] text-white font-semibold py-3 rounded-lg transition-all duration-200 shadow-md transform hover:-translate-y-0.5 mt-2">
                        Sign In
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');

            if (toggleButton && passwordInput) {
                toggleButton.addEventListener('click', () => {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';

                    // Toggle icon attribute
                    eyeIcon.setAttribute('data-lucide', isPassword ? 'eye-off' : 'eye');

                    // Re-create lucide icons to update the UI
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });
            }
        });
    </script>
</body>
</html>
