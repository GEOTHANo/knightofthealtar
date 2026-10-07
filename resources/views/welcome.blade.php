<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Knights of the Altar - Vatican & Community News Portal</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/koa-logo.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Anti-FOUC Dark Mode Theme Script -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 selection:bg-blue-600 selection:text-white transition-colors duration-200">

    <!-- Sticky Navigation Header -->
    <header class="sticky top-0 z-50 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Brand Logo & Name -->
            <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 rounded-xl overflow-hidden bg-white p-0.5 border border-slate-200 dark:border-slate-700 shadow-xs group-hover:scale-105 transition-transform">
                    <img src="{{ asset('images/koa-logo.jpg') }}" alt="KOA Logo" class="w-full h-full object-cover rounded-lg">
                </div>
                <div>
                    <span class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-slate-100 tracking-tight flex items-center">
                        Knights of the Altar
                        <span class="ml-2 hidden sm:inline-block px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider bg-blue-50 dark:bg-blue-950/80 text-blue-600 dark:text-blue-400 rounded-md border border-blue-200/50 dark:border-blue-800/50">Vatican Portal</span>
                    </span>
                    <p class="text-[11px] text-amber-500 font-semibold tracking-wider uppercase leading-none">Sto. Rosario Toril</p>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center space-x-8 text-sm font-medium">
                <a href="#hero" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Home</a>
                <a href="#featured" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Featured</a>
                <a href="#news" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Vatican News</a>
                <a href="#about" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">About</a>
            </nav>

            <!-- Right Controls (Theme Toggle + Login) -->
            <div class="flex items-center space-x-3">
                <!-- Theme Toggle Button -->
                <button id="landing-theme-toggle" type="button" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-amber-500 dark:text-sky-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all focus:outline-none" title="Toggle Light/Dark Theme">
                    <i data-lucide="sun" id="landing-light-icon" class="w-5 h-5 hidden"></i>
                    <i data-lucide="moon" id="landing-dark-icon" class="w-5 h-5 hidden"></i>
                </button>

                <!-- Auth Navigation -->
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl shadow-xs transition-all duration-200 flex items-center">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 mr-2"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-md shadow-blue-500/20 transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                        <i data-lucide="log-in" class="w-4 h-4 mr-2"></i> Log In
                    </a>
                @endauth

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 space-y-2">
            <a href="#hero" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Home</a>
            <a href="#featured" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Featured</a>
            <a href="#news" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Vatican News</a>
            <a href="#about" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">About</a>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="relative overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28 bg-gradient-to-b from-blue-900 via-indigo-950 to-slate-950 text-white">
        <!-- Background Decorative Ornaments -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-blue-500/15 via-transparent to-transparent opacity-80"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <!-- Top Badge -->
                <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-amber-300 text-xs font-semibold uppercase tracking-wider mb-6">
                    <i data-lucide="globe" class="w-3.5 h-3.5"></i>
                    <span>Official Vatican News Feed & Community Portal</span>
                </div>

                <!-- Main Headline -->
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight mb-6">
                    Connecting Faith, Truth & Community Service
                </h1>

                <!-- Subtitle Description -->
                <p class="text-base sm:text-lg text-slate-300 leading-relaxed mb-8 max-w-2xl mx-auto">
                    Stay inspired with real-time news from the Holy See, Papal teachings, and official announcements for the Knights of the Altar — Sto. Rosario Parish of Toril.
                </p>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="#news" class="w-full sm:w-auto px-7 py-3.5 bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-600/30 transition-all transform hover:-translate-y-0.5 flex items-center justify-center">
                        <i data-lucide="newspaper" class="w-4 h-4 mr-2"></i> Explore Vatican News
                    </a>
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-7 py-3.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 font-bold text-sm rounded-xl backdrop-blur-md transition-all flex items-center justify-center">
                        <i data-lucide="shield-check" class="w-4 h-4 mr-2 text-amber-300"></i> Member Portal Login
                    </a>
                </div>

                <!-- Quick Highlights Bar -->
                <div class="mt-14 pt-8 border-t border-white/10 grid grid-cols-2 sm:grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-2xl font-extrabold text-amber-300">Live RSS</p>
                        <p class="text-xs text-slate-400 mt-0.5">Vatican News Feed</p>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-white">Holy See</p>
                        <p class="text-xs text-slate-400 mt-0.5">Papal Messages & Events</p>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-2xl font-extrabold text-amber-300">Ave Maria</p>
                        <p class="text-xs text-slate-400 mt-0.5">Altar Ministry & Schedules</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured News Area -->
    @if(isset($newsItems) && count($newsItems) > 0)
    <section id="featured" class="py-12 lg:py-16 -mt-8 relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600 animate-pulse"></span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-slate-100">Featured Lead Story</h2>
                </div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Top Vatican Release</span>
            </div>

            @php $featured = $newsItems[0]; @endphp
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 group transition-all duration-300">
                <div class="lg:col-span-7 relative min-h-[260px] sm:min-h-[340px] overflow-hidden bg-slate-950">
                    <img src="{{ $featured['image'] }}" alt="{{ $featured['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent lg:hidden"></div>
                    <span class="absolute top-4 left-4 px-3 py-1 bg-blue-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-sm">
                        {{ $featured['category'] }}
                    </span>
                </div>
                <div class="lg:col-span-5 p-6 sm:p-8 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center space-x-2 text-xs text-slate-500 dark:text-slate-400 mb-3">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400"></i>
                            <span>{{ $featured['date_formatted'] }}</span>
                            <span>•</span>
                            <span class="text-amber-500 font-medium">Vatican News</span>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 leading-snug mb-3 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            <a href="{{ $featured['link'] }}" target="_blank" rel="noopener noreferrer">{{ $featured['title'] }}</a>
                        </h3>
                        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                            {{ $featured['description'] }}
                        </p>
                    </div>
                    <div>
                        <a href="{{ $featured['link'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-blue-600/20">
                            Read Full Article <i data-lucide="external-link" class="w-4 h-4 ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Vatican News Grid Section -->
    <section id="news" class="py-12 lg:py-16 bg-slate-100/60 dark:bg-slate-900/40 border-y border-slate-200/60 dark:border-slate-800/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-slate-100">Latest Vatican & Church News</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Live updates directly from Vatican Communications RSS feed.</p>
                </div>

                <!-- Category Filters -->
                <div class="flex flex-wrap gap-2" id="category-filters">
                    <button data-filter="all" class="filter-btn active px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-blue-600 text-white transition-all">All Topics</button>
                    <button data-filter="Pope" class="filter-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-all">Pope</button>
                    <button data-filter="Vatican" class="filter-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-all">Vatican</button>
                    <button data-filter="Church" class="filter-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-all">Church</button>
                    <button data-filter="World" class="filter-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-all">World</button>
                </div>
            </div>

            <!-- News Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="news-grid">
                @if(isset($newsItems) && count($newsItems) > 1)
                    @foreach(array_slice($newsItems, 1) as $item)
                    <div class="news-card bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col group" data-category="{{ $item['category'] }}">
                        <!-- Image Container -->
                        <div class="relative h-48 overflow-hidden bg-slate-950">
                            <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 px-2.5 py-0.5 bg-blue-600/90 backdrop-blur-xs text-white text-[11px] font-bold uppercase tracking-wider rounded-md">
                                {{ $item['category'] }}
                            </span>
                        </div>

                        <!-- Card Content -->
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center space-x-2 text-xs text-slate-400 dark:text-slate-500 mb-2">
                                    <i data-lucide="clock" class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400"></i>
                                    <span>{{ $item['date_formatted'] }}</span>
                                </div>
                                <h3 class="font-bold text-base text-slate-900 dark:text-slate-100 leading-snug mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                                    <a href="{{ $item['link'] }}" target="_blank" rel="noopener noreferrer">{{ $item['title'] }}</a>
                                </h3>
                                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed line-clamp-3 mb-4">
                                    {{ $item['description'] }}
                                </p>
                            </div>
                            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                <span class="text-[11px] font-semibold text-amber-500">Vatican News</span>
                                <a href="{{ $item['link'] }}" target="_blank" rel="noopener noreferrer" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 flex items-center">
                                    Read Article <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-span-3 text-center py-12 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800">
                        <i data-lucide="newspaper" class="w-10 h-10 text-slate-400 mx-auto mb-2"></i>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">Vatican News updates are loading...</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16 lg:py-24 bg-white dark:bg-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Left Image Visual -->
                <div class="lg:col-span-5 relative">
                    <div class="rounded-3xl overflow-hidden shadow-2xl border border-slate-200/80 dark:border-slate-800 bg-slate-900 relative">
                        <img src="{{ asset('images/koa-logo.jpg') }}" alt="Knights of the Altar Emblem" class="w-full h-80 lg:h-[400px] object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent flex items-end p-6">
                            <div class="text-white">
                                <h4 class="font-bold text-lg">Knights of the Altar</h4>
                                <p class="text-xs text-amber-300">Sto. Rosario Parish of Toril</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content Description -->
                <div class="lg:col-span-7 space-y-6">
                    <div>
                        <span class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">About Our Ministry</span>
                        <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-slate-100 mt-1 leading-tight">
                            Dedicated to Reverent Service & Faithful Communication
                        </h2>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed">
                        The <strong>Knights of the Altar - Sto. Rosario Parish of Toril</strong> is a Catholic altar servers ministry devoted to reverent service during Holy Mass, spiritual formation, and brotherhood.
                    </p>
                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed">
                        This portal connects our altar servers, officers, and parish community with official news from Vatican Communications. By integrating live Vatican News updates with our internal schedule portal, we ensure our members stay spiritually informed and united with the universal Catholic Church.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800">
                            <i data-lucide="shield-check" class="w-6 h-6 text-blue-600 dark:text-blue-400 mb-2"></i>
                            <h4 class="font-bold text-sm text-slate-900 dark:text-slate-100">Altar Ministry</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Orderly scheduling for weekday & Sunday masses.</p>
                        </div>
                        <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800">
                            <i data-lucide="rss" class="w-6 h-6 text-amber-500 mb-2"></i>
                            <h4 class="font-bold text-sm text-slate-900 dark:text-slate-100">Vatican Integration</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Live coverage of Papal messages & Vatican news.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call To Action Section -->
    <section class="py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-950 rounded-3xl p-8 sm:p-12 text-white shadow-2xl relative overflow-hidden border border-blue-800/40">
                <div class="max-w-2xl relative z-10 space-y-4">
                    <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight">Access the Knights of the Altar Portal</h2>
                    <p class="text-sm sm:text-base text-slate-300 leading-relaxed">
                        Log in to view weekly mass assignments, record attendance, check announcements, and manage member profiles.
                    </p>
                    <div class="pt-4 flex flex-wrap gap-4">
                        <a href="{{ route('login') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-600/30 transition-all flex items-center">
                            <i data-lucide="log-in" class="w-4 h-4 mr-2"></i> Log In to System
                        </a>
                        <a href="#news" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white border border-white/20 font-bold text-sm rounded-xl transition-all flex items-center">
                            Browse Latest News
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 border-t border-slate-800 text-sm py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1: Brand -->
                <div class="space-y-3 md:col-span-2">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg overflow-hidden bg-white p-0.5">
                            <img src="{{ asset('images/koa-logo.jpg') }}" alt="KOA Logo" class="w-full h-full object-cover rounded">
                        </div>
                        <span class="font-extrabold text-white text-base">Knights of the Altar</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-sm">
                        Sto. Rosario Parish of Toril. Dedicated to reverent altar service and fostering communion with the Catholic Church through Vatican News.
                    </p>
                </div>

                <!-- Col 2: Navigation -->
                <div>
                    <h4 class="font-bold text-white text-xs uppercase tracking-wider mb-3">Quick Navigation</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#hero" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="#featured" class="hover:text-white transition-colors">Featured News</a></li>
                        <li><a href="#news" class="hover:text-white transition-colors">Vatican News Feed</a></li>
                        <li><a href="#about" class="hover:text-white transition-colors">About Ministry</a></li>
                    </ul>
                </div>

                <!-- Col 3: System Access & Social -->
                <div>
                    <h4 class="font-bold text-white text-xs uppercase tracking-wider mb-3">System Access</h4>
                    <ul class="space-y-2 text-xs">
                        <li>
                            <a href="{{ route('login') }}" class="text-amber-400 hover:text-amber-300 font-semibold inline-flex items-center">
                                Member Login <i data-lucide="arrow-right" class="w-3 h-3 ml-1"></i>
                            </a>
                        </li>
                        <li class="pt-2">
                            <a href="https://www.facebook.com/search/top?q=knights%20of%20the%20altar%20-%20sto.%20rosario%20parish%20of%20toril" target="_blank" rel="noopener noreferrer" class="text-slate-400 hover:text-white flex items-center">
                                <svg class="w-4 h-4 mr-2 text-[#1877f2]" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                Facebook Page
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-2">
                <p>© 2026 Knights of the Altar - Sto. Rosario Parish of Toril. All rights reserved.</p>
                <p class="italic">News content integrated via Vatican News RSS</p>
            </div>
        </div>
    </footer>

    <!-- Interactive Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Theme toggle script
            const themeBtn = document.getElementById('landing-theme-toggle');
            const lightIcon = document.getElementById('landing-light-icon');
            const darkIcon = document.getElementById('landing-dark-icon');

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

            // Mobile menu toggle
            const mobileBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileBtn && mobileMenu) {
                mobileBtn.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Category Filtering
            const filterBtns = document.querySelectorAll('.filter-btn');
            const newsCards = document.querySelectorAll('.news-card');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    filterBtns.forEach(b => {
                        b.classList.remove('bg-blue-600', 'text-white', 'active');
                        b.classList.add('bg-white', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-300');
                    });
                    btn.classList.add('bg-blue-600', 'text-white', 'active');
                    btn.classList.remove('bg-white', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-300');

                    const filter = btn.getAttribute('data-filter');
                    newsCards.forEach(card => {
                        const cat = card.getAttribute('data-category');
                        if (filter === 'all' || cat === filter) {
                            card.style.display = 'flex';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
