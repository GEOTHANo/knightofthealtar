<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Knights of the Altar</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/koa-logo.jpg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans antialiased min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 transition-colors duration-200">

    <div class="flex min-h-screen">
        <!-- Left Side (Branding) -->
        <div class="hidden lg:flex lg:w-3/5 bg-gradient-to-br from-blue-900 via-indigo-950 to-slate-950 relative flex-col items-center justify-center p-12 overflow-hidden border-r border-blue-900/40">
            <!-- Decorative light blurs -->
            <div class="absolute top-12 left-12 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-12 right-12 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl"></div>

            <div class="relative z-10 flex flex-col items-center text-center text-white max-w-lg">
                <!-- Logo -->
                <div class="mb-8 w-28 h-28 bg-white rounded-3xl p-1 shadow-2xl flex items-center justify-center overflow-hidden border-2 border-blue-400/30">
                    <img src="{{ asset('images/koa-logo.jpg') }}" alt="KOA Logo" class="w-full h-full object-cover rounded-2xl">
                </div>

                <h1 class="text-4xl font-extrabold tracking-tight mb-2 text-white">Knights of the Altar</h1>
                <p class="text-sm font-semibold text-amber-300 tracking-widest uppercase mb-10">STO. ROSARIO TORIL</p>

                <!-- Facebook Button -->
                <a href="https://www.facebook.com/search/top?q=knights%20of%20the%20altar%20-%20sto.%20rosario%20parish%20of%20toril" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="inline-flex items-center justify-center space-x-2.5 bg-white text-blue-900 hover:bg-slate-50 px-8 py-3.5 rounded-2xl font-bold shadow-lg hover:shadow-blue-500/20 transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 text-[#1877f2]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span>Visit our Facebook Page</span>
                </a>
                
                <p class="mt-16 text-sm text-slate-300 font-serif italic">Ave Maria, Gratia Plena</p>
            </div>
        </div>

        <!-- Right Side (Login Form) -->
        <div class="w-full lg:w-2/5 flex flex-col items-center justify-center p-8 sm:p-12 lg:p-16 bg-white dark:bg-slate-900 relative">
            
            <!-- Top Controls (Back Button & Theme Toggle) -->
            <a href="{{ route('landing') }}" class="absolute top-6 left-6 inline-flex items-center text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all p-2 px-3 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700/60 shadow-xs" title="Back to Landing Page">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> Back to Home
            </a>

            <!-- Dark Mode Toggle Button on Login Page -->
            <button id="login-theme-toggle" type="button" class="absolute top-6 right-6 p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-amber-500 dark:text-sky-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all focus:outline-none" title="Toggle Theme">
                <i data-lucide="sun" id="login-light-icon" class="w-5 h-5 hidden"></i>
                <i data-lucide="moon" id="login-dark-icon" class="w-5 h-5 hidden"></i>
            </button>

            <div class="w-full max-w-sm">
                <!-- Back Button for small screen layout alignment -->
                <div class="mb-4 sm:hidden">
                    <a href="{{ route('landing') }}" class="inline-flex items-center text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-1"></i> Back to Landing Page
                    </a>
                </div>

                <!-- Mobile Logo (visible only on small screens) -->
                <div class="lg:hidden flex justify-center mb-8">
                    <div class="w-20 h-20 bg-white rounded-2xl p-1 shadow-md flex items-center justify-center overflow-hidden border border-slate-200 dark:border-slate-800">
                        <img src="{{ asset('images/koa-logo.jpg') }}" alt="KOA Logo" class="w-full h-full object-cover rounded-xl">
                    </div>
                </div>

                <div class="mb-8 text-center sm:text-left">
                    <h2 class="text-2xl font-extrabold text-slate-900 dark:text-slate-100 mb-2">Welcome Back</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Sign in to your account to continue</p>
                </div>

                @if ($errors->any())
                    <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 px-4 py-3 rounded-xl mb-6 text-sm shadow-xs">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="username" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i data-lucide="user" class="h-4 w-4 text-slate-400 dark:text-slate-500"></i>
                            </div>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                autofocus
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-slate-400"
                                placeholder="Enter your username"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="h-4 w-4 text-slate-400 dark:text-slate-500"></i>
                            </div>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                class="w-full pl-10 pr-10 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-slate-400"
                                placeholder="Enter your password"
                            >
                            <button
                                type="button"
                                id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 focus:outline-none"
                            >
                                <i id="eyeIcon" data-lucide="eye" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition-all duration-200 shadow-md shadow-blue-500/20 transform hover:-translate-y-0.5 mt-2">
                        Sign In
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');
            const themeBtn = document.getElementById('login-theme-toggle');
            const lightIcon = document.getElementById('login-light-icon');
            const darkIcon = document.getElementById('login-dark-icon');

            function updateIcons() {
                const isDark = document.documentElement.classList.contains('dark');
                if (isDark) {
                    lightIcon.classList.remove('hidden');
                    darkIcon.classList.add('hidden');
                } else {
                    lightIcon.classList.add('hidden');
                    darkIcon.classList.remove('hidden');
                }
            }

            updateIcons();

            if (themeBtn) {
                themeBtn.addEventListener('click', () => {
                    const isDark = document.documentElement.classList.toggle('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                    updateIcons();
                });
            }

            if (toggleButton && passwordInput) {
                toggleButton.addEventListener('click', () => {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    eyeIcon.setAttribute('data-lucide', isPassword ? 'eye-off' : 'eye');
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });
            }

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
