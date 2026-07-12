<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Knights of the Altar</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/koa-logo.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 overflow-hidden flex flex-col h-screen">
    
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

    <!-- Top Navigation -->
    <header class="bg-[#246b9c] text-white shadow z-10 shrink-0">
        <div class="px-6 py-3 flex items-center justify-between">
            <div class="flex items-center">
                <!-- Hamburger Button for Mobile -->
                <button id="mobile-menu-toggle" class="lg:hidden text-white mr-3 hover:bg-white/10 p-1.5 rounded-lg transition-colors focus:outline-none">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div class="mr-3 w-10 h-10 rounded-lg overflow-hidden bg-white p-0.5">
                    <img src="{{ asset('images/koa-logo.jpg') }}" alt="Logo" class="w-full h-full object-cover rounded-md">
                </div>
                <div>
                    <h1 class="text-lg font-bold leading-tight">Knights of the Altar</h1>
                    <p class="text-xs text-yellow-400 leading-tight">
                        @if(auth()->check())
                            {{ auth()->user()->positions->first()?->position_name ?? 'Member' }} Tol {{ auth()->user()->last_name }}
                        @else
                            Welcome
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <a href="{{ route('notifications.index') }}" class="bg-white/20 p-2 rounded-full relative hover:bg-white/30 transition-colors block">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    @if($notificationCount > 0)
                        <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-4 w-4 items-center justify-center rounded-full bg-yellow-400 text-[10px] font-bold text-[#246b9c] animate-pulse">
                            {{ $notificationCount }}
                        </span>
                    @endif
                </a>
            </div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden relative">
        <!-- Sidebar Overlay (Mobile) -->
        <div id="sidebar-overlay" class="fixed inset-0 z-30 bg-black/40 hidden lg:hidden transition-opacity"></div>
        
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 flex flex-col h-full transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:h-auto lg:shrink-0">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">NAVIGATION</span>
                <button id="mobile-menu-close" class="lg:hidden text-gray-400 hover:text-gray-600 focus:outline-none p-1 rounded-lg hover:bg-gray-50">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                @if(auth()->user()->canAccess('dashboard') || auth()->user()->canAccess('member-dashboard'))
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 border-l-4 border-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i data-lucide="home" class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-700' : 'text-gray-400' }}"></i>
                    Dashboard
                </a>
                @endif

                @if(auth()->user()->canAccess('schedules'))
                <a href="{{ route('schedules.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('schedules.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i data-lucide="list" class="w-5 h-5 mr-3 {{ request()->routeIs('schedules.*') ? 'text-blue-700' : 'text-gray-400' }}"></i>
                    Schedules
                </a>
                @endif

                @if(auth()->user()->canAccess('members'))
                <a href="{{ route('members.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('members.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i data-lucide="users" class="w-5 h-5 mr-3 {{ request()->routeIs('members.*') ? 'text-blue-700' : 'text-gray-400' }}"></i>
                    Members
                </a>
                @endif

                @if(auth()->user()->canAccess('attendance'))
                <a href="{{ route('attendance.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('attendance.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i data-lucide="check-square" class="w-5 h-5 mr-3 {{ request()->routeIs('attendance.*') ? 'text-blue-700' : 'text-gray-400' }}"></i>
                    Attendance
                </a>
                @endif

                @if(auth()->user()->canAccess('notifications'))
                <a href="{{ route('notifications.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('notifications.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i data-lucide="bell" class="w-5 h-5 mr-3 {{ request()->routeIs('notifications.*') ? 'text-blue-700' : 'text-gray-400' }}"></i>
                    Notifications
                </a>
                @endif

                @if(auth()->user()->canAccess('mass'))
                <a href="{{ route('mass.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('mass.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i data-lucide="book-open" class="w-5 h-5 mr-3 {{ request()->routeIs('mass.*') ? 'text-blue-700' : 'text-gray-400' }}"></i>
                    Mass
                </a>
                @endif

                @if(auth()->user()->canAccess('settings'))
                <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('settings.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i data-lucide="shield" class="w-5 h-5 mr-3 {{ request()->routeIs('settings.*') ? 'text-blue-700' : 'text-gray-400' }}"></i>
                    Settings
                </a>
                @endif
            </nav>
            <div class="p-4 border-t border-gray-200">
                <a href="{{ route('logout') }}" class="flex items-center px-4 py-3 text-sm font-medium text-red-500 hover:bg-gray-100 rounded-lg">
                    <i data-lucide="log-out" class="w-5 h-5 mr-3"></i>
                    Logout
                </a>
                <div class="mt-4 bg-[#2b7bb5] text-white rounded-xl p-4 text-center">
                    <h3 class="font-bold text-sm italic font-serif">Ave Maria</h3>
                    <p class="text-xs text-yellow-400 mt-1">Gratia Plena</p>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-[#f8fafc]">
            @yield('content')
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const menuToggle = document.getElementById('mobile-menu-toggle');
            const menuClose = document.getElementById('mobile-menu-close');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (menuToggle && sidebar && overlay) {
                const toggleSidebar = () => {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                };

                menuToggle.addEventListener('click', toggleSidebar);
                if (menuClose) menuClose.addEventListener('click', toggleSidebar);
                overlay.addEventListener('click', toggleSidebar);
            }

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
