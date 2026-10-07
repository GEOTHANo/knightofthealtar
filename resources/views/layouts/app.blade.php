<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Knights of the Altar</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/koa-logo.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Anti-FOUC Dark Mode Theme Script -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 overflow-hidden flex flex-col h-screen transition-colors duration-200">
    
    @php
        $notificationCount = 0;
        if (auth()->check()) {
            $userRoleIds = auth()->user()->positions()->where('is_current', true)->pluck('positions.id')->toArray();
            $notificationCount = \App\Models\Notification::where(function($q) use ($userRoleIds) {
                foreach ($userRoleIds as $id) {
                    $q->orWhereJsonContains('target_roles', (int)$id)
                      ->orWhereJsonContains('target_roles', (string)$id);
                }
            })
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        }
    @endphp

    <!-- Top Navigation Header -->
    <header class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 dark:from-slate-950 dark:via-blue-950 dark:to-slate-950 text-white shadow-md z-30 shrink-0 border-b border-blue-800/40 dark:border-slate-800">
        <div class="px-4 sm:px-6 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl overflow-hidden bg-white p-0.5 shadow-sm border border-blue-200/30 shrink-0">
                    <img src="{{ asset('images/koa-logo.jpg') }}" alt="Logo" class="w-full h-full object-cover rounded-lg">
                </div>
                <div>
                    <h1 class="text-base sm:text-lg font-bold leading-tight tracking-wide text-white">Knights of the Altar</h1>
                    <p class="text-xs text-amber-300 font-medium leading-tight">
                        @if(auth()->check())
                            {{ auth()->user()->positions->first()?->position_name ?? 'Member' }} Tol {{ auth()->user()->last_name }}
                        @else
                            Welcome
                        @endif
                    </p>
                </div>
            </div>

            <!-- Header Right Controls -->
            <div class="flex items-center space-x-2 sm:space-x-3">
                <!-- Theme Toggle Button -->
                <button id="theme-toggle" type="button" class="p-2 rounded-xl bg-white/10 dark:bg-slate-800/90 text-amber-300 dark:text-sky-300 hover:bg-white/20 dark:hover:bg-slate-700/80 transition-all focus:outline-none focus:ring-2 focus:ring-blue-400" title="Toggle Light/Dark Theme">
                    <i data-lucide="sun" id="theme-toggle-light-icon" class="w-5 h-5 hidden"></i>
                    <i data-lucide="moon" id="theme-toggle-dark-icon" class="w-5 h-5 hidden"></i>
                </button>

                <!-- Notifications Button -->
                <a href="{{ route('notifications.index') }}" class="bg-white/10 dark:bg-slate-800/90 p-2 rounded-xl relative hover:bg-white/20 dark:hover:bg-slate-700/80 transition-all block text-white focus:outline-none focus:ring-2 focus:ring-blue-400" title="Notifications">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    @if($notificationCount > 0)
                        <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-amber-400 text-[10px] font-bold text-blue-950 animate-pulse ring-2 ring-blue-900">
                            {{ $notificationCount }}
                        </span>
                    @endif
                </a>
            </div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden relative">
        
        <!-- Desktop Sidebar (Visible ONLY on lg screens and up) -->
        <aside id="sidebar" class="hidden lg:flex lg:flex-col lg:w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 h-full shrink-0 transition-colors duration-200">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 dark:text-slate-400 tracking-wider uppercase">NAVIGATION</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200/50 dark:border-blue-800/40">Portal</span>
            </div>
            <nav class="flex-1 p-3 space-y-1.5 overflow-y-auto">
                @if(auth()->user()->canAccess('dashboard') || auth()->user()->canAccess('member-dashboard'))
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 font-semibold border-l-4 border-blue-600 dark:border-blue-500 shadow-xs' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/80' }}">
                    <i data-lucide="home" class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-400' }}"></i>
                    Dashboard
                </a>
                @endif

                @if(auth()->user()->canAccess('schedules'))
                <a href="{{ route('schedules.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('schedules.*') ? 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 font-semibold border-l-4 border-blue-600 dark:border-blue-500 shadow-xs' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/80' }}">
                    <i data-lucide="calendar" class="w-5 h-5 mr-3 {{ request()->routeIs('schedules.*') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-400' }}"></i>
                    Schedules
                </a>
                @endif

                @if(auth()->user()->canAccess('members'))
                <a href="{{ route('members.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('members.*') ? 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 font-semibold border-l-4 border-blue-600 dark:border-blue-500 shadow-xs' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/80' }}">
                    <i data-lucide="users" class="w-5 h-5 mr-3 {{ request()->routeIs('members.*') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-400' }}"></i>
                    Members
                </a>
                @endif

                @if(auth()->user()->canAccess('attendance'))
                <a href="{{ route('attendance.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('attendance.*') ? 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 font-semibold border-l-4 border-blue-600 dark:border-blue-500 shadow-xs' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/80' }}">
                    <i data-lucide="check-square" class="w-5 h-5 mr-3 {{ request()->routeIs('attendance.*') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-400' }}"></i>
                    Attendance
                </a>
                @endif

                @if(auth()->user()->canAccess('notifications'))
                <a href="{{ route('notifications.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('notifications.*') ? 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 font-semibold border-l-4 border-blue-600 dark:border-blue-500 shadow-xs' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/80' }}">
                    <i data-lucide="bell" class="w-5 h-5 mr-3 {{ request()->routeIs('notifications.*') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-400' }}"></i>
                    Notifications
                </a>
                @endif

                @if(auth()->user()->canAccess('mass'))
                <a href="{{ route('mass.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('mass.*') ? 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 font-semibold border-l-4 border-blue-600 dark:border-blue-500 shadow-xs' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/80' }}">
                    <i data-lucide="book-open" class="w-5 h-5 mr-3 {{ request()->routeIs('mass.*') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-400' }}"></i>
                    Mass
                </a>
                @endif

                @if(auth()->user()->canAccess('settings'))
                <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('settings.*') ? 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 font-semibold border-l-4 border-blue-600 dark:border-blue-500 shadow-xs' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/80' }}">
                    <i data-lucide="shield" class="w-5 h-5 mr-3 {{ request()->routeIs('settings.*') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-400' }}"></i>
                    Settings
                </a>
                @endif
            </nav>
            <div class="p-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                <a href="{{ route('logout') }}" class="flex items-center px-4 py-3 text-sm font-medium text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition-colors">
                    <i data-lucide="log-out" class="w-5 h-5 mr-3"></i>
                    Logout
                </a>
                <div class="bg-gradient-to-br from-blue-900 to-indigo-950 text-white rounded-xl p-4 text-center shadow-sm border border-blue-800/50">
                    <h3 class="font-bold text-sm italic font-serif tracking-wide">Ave Maria</h3>
                    <p class="text-xs text-amber-300 font-medium mt-0.5">Gratia Plena</p>
                </div>
            </div>
        </aside>

        <!-- Main Content Container -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50 dark:bg-slate-950 pb-24 lg:pb-8 transition-colors duration-200">
            @yield('content')
        </main>

        <!-- Mobile Bottom Navigation Bar (Visible ONLY on Phone / Mobile screens < lg) -->
        <nav id="mobile-bottom-nav" class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-t border-slate-200/80 dark:border-slate-800/80 shadow-lg px-2 h-16 flex items-center justify-around transition-colors duration-200">
            @if(auth()->user()->canAccess('dashboard') || auth()->user()->canAccess('member-dashboard'))
            <a href="{{ route('dashboard') }}" title="Dashboard" class="flex flex-col items-center justify-center p-2 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 scale-105' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400' }}">
                <i data-lucide="home" class="w-6 h-6"></i>
            </a>
            @endif

            @if(auth()->user()->canAccess('schedules'))
            <a href="{{ route('schedules.index') }}" title="Schedules" class="flex flex-col items-center justify-center p-2 rounded-xl transition-all duration-200 {{ request()->routeIs('schedules.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 scale-105' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400' }}">
                <i data-lucide="calendar" class="w-6 h-6"></i>
            </a>
            @endif

            @if(auth()->user()->canAccess('members'))
            <a href="{{ route('members.index') }}" title="Members" class="flex flex-col items-center justify-center p-2 rounded-xl transition-all duration-200 {{ request()->routeIs('members.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 scale-105' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400' }}">
                <i data-lucide="users" class="w-6 h-6"></i>
            </a>
            @endif

            @if(auth()->user()->canAccess('attendance'))
            <a href="{{ route('attendance.index') }}" title="Attendance" class="flex flex-col items-center justify-center p-2 rounded-xl transition-all duration-200 {{ request()->routeIs('attendance.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 scale-105' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400' }}">
                <i data-lucide="check-square" class="w-6 h-6"></i>
            </a>
            @endif

            @if(auth()->user()->canAccess('notifications'))
            <a href="{{ route('notifications.index') }}" title="Notifications" class="flex flex-col items-center justify-center p-2 rounded-xl transition-all duration-200 relative {{ request()->routeIs('notifications.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 scale-105' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400' }}">
                <i data-lucide="bell" class="w-6 h-6"></i>
                @if($notificationCount > 0)
                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-amber-400 rounded-full ring-2 ring-white dark:ring-slate-900"></span>
                @endif
            </a>
            @endif

            @if(auth()->user()->canAccess('mass'))
            <a href="{{ route('mass.index') }}" title="Mass" class="flex flex-col items-center justify-center p-2 rounded-xl transition-all duration-200 {{ request()->routeIs('mass.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 scale-105' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400' }}">
                <i data-lucide="book-open" class="w-6 h-6"></i>
            </a>
            @endif

            @if(auth()->user()->canAccess('settings'))
            <a href="{{ route('settings.index') }}" title="Settings" class="flex flex-col items-center justify-center p-2 rounded-xl transition-all duration-200 {{ request()->routeIs('settings.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 scale-105' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400' }}">
                <i data-lucide="shield" class="w-6 h-6"></i>
            </a>
            @endif

            <a href="{{ route('logout') }}" title="Logout" class="flex flex-col items-center justify-center p-2 rounded-xl text-rose-500 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-all">
                <i data-lucide="log-out" class="w-6 h-6"></i>
            </a>
        </nav>
    </div>

    <!-- Theme Switcher & System Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const themeToggleBtn = document.getElementById('theme-toggle');
            const lightIcon = document.getElementById('theme-toggle-light-icon');
            const darkIcon = document.getElementById('theme-toggle-dark-icon');

            function updateThemeIcons() {
                const isDark = document.documentElement.classList.contains('dark');
                if (isDark) {
                    lightIcon.classList.remove('hidden');
                    darkIcon.classList.add('hidden');
                } else {
                    lightIcon.classList.add('hidden');
                    darkIcon.classList.remove('hidden');
                }
            }

            updateThemeIcons();

            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', () => {
                    const isDark = document.documentElement.classList.toggle('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                    updateThemeIcons();
                });
            }

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
