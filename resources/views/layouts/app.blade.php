<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ 
        darkMode: localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
      }" 
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode, 'light': !darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SVLDK') }} - @yield('title', 'Front Office')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs" defer></script>

    @stack('styles')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .dark .swal2-popup {
            background: #1e293b !important;
            color: #f1f5f9 !important;
            border-radius: 2rem !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        
        .light .swal2-popup {
            background: #ffffff !important;
            color: #0f172a !important;
            border-radius: 2rem !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1) !important;
        }

        .dark .swal2-title {
            color: #f1f5f9 !important;
        }
        
        .light .swal2-title {
            color: #0f172a !important;
        }

        .swal2-confirm {
            background: #10b981 !important;
            border-radius: 0.75rem !important;
            padding: 10px 24px !important;
            font-weight: bold !important;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 10px;
        }

        .light ::-webkit-scrollbar-thumb {
            background: #e2e8f0;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #334155;
        }
    </style>
</head>

<body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-200 overflow-hidden" 
      x-data="{ sidebarOpen: false, userMenu: false }">

    <div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950">

        <!-- Sidebar Navigation (Desktop Persistent) -->
        <aside
            class="hidden md:flex md:flex-col w-64 lg:w-72 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800/50 flex-shrink-0 shadow-2xl z-20">
            <div class="flex flex-col h-full">
                <!-- Branding -->
                <div class="flex items-center gap-4 px-8 h-24 border-b border-slate-100 dark:border-white/5">
                    <div
                        class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-xl shadow-emerald-500/20 text-white transform hover:rotate-12 transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04 M12 21.355r 0 0 0 0-1.618-3.041z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-black tracking-tighter text-slate-900 dark:text-white block leading-none">SVLDK</span>
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest leading-none">Intelligence</span>
                    </div>
                </div>

                <!-- Nav Menu -->
                <nav class="flex-grow py-8 px-6 space-y-1.5 overflow-y-auto">
                    <p class="text-[10px] font-black text-slate-500 dark:text-slate-600 uppercase tracking-[0.2em] px-4 mb-6">Core Operations</p>

                    <x-nav-link href="{{ route('dashboard.index') }}" :active="request()->routeIs('dashboard.index')"
                        icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        Dashboard
                    </x-nav-link>

                    @if(Auth::user()->hasPermission('citizens.manage'))
                    <x-nav-link href="{{ route('citizens.index') }}" :active="request()->routeIs('citizens.*')"
                        icon="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        Master Warga
                    </x-nav-link>
                    @endif

                    <div class="pt-8">
                        <p class="text-[10px] font-black text-slate-500 dark:text-slate-600 uppercase tracking-[0.2em] px-4 mb-6">Data Governance</p>
                        <x-nav-link href="{{ route('dashboard.verify') }}" :active="request()->routeIs('dashboard.verify')"
                            icon="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.041M12 21.355r 0 0 0 0">
                            Verifikasi NIK
                        </x-nav-link>
                        <x-nav-link href="{{ route('dashboard.history') }}" :active="request()->routeIs('dashboard.history')"
                            icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                            Activity Logs
                        </x-nav-link>
                    </div>

                    @if(Auth::user()->hasPermission('users.manage'))
                    <div class="pt-8">
                        <p class="text-[10px] font-black text-slate-500 dark:text-slate-600 uppercase tracking-[0.2em] px-4 mb-6">Administrative Control</p>
                        <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')"
                            icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            Manajemen User
                        </x-nav-link>
                        @if(Auth::user()->hasPermission('roles.manage'))
                        <x-nav-link href="{{ route('roles.index') }}" :active="request()->routeIs('roles.*')"
                            icon="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.041M12 21.355r 0 0 0 0">
                            Manajemen Role
                        </x-nav-link>
                        @endif
                        @if(Auth::user()->hasPermission('districts.manage'))
                        <x-nav-link href="{{ route('districts.index') }}" :active="request()->routeIs('districts.*')"
                            icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            Manajemen Kecamatan
                        </x-nav-link>
                        @endif
                        @if(Auth::user()->hasPermission('villages.manage'))
                        <x-nav-link href="{{ route('villages.index') }}" :active="request()->routeIs('villages.*')"
                            icon="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                            Manajemen Desa
                        </x-nav-link>
                        @endif
                    </div>
                    @endif
                </nav>

                <!-- Status Panel -->
                <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-slate-900/50">
                    <div class="bg-white dark:bg-slate-800/40 rounded-2xl p-4 border border-slate-200 dark:border-white/5 backdrop-blur-sm">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Environment</span>
                            <span
                                class="flex h-2 w-2 rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/50"></span>
                        </div>
                        <p class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">PRODUCTION</p>
                        <p class="text-[9px] text-slate-500 mt-1">v{{ config('app.version', '2.1.0') }} &bull; SVLDK Core</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Sidebar Navigation (Mobile Off-canvas) -->
        <div x-show="sidebarOpen" class="fixed inset-0 z-[100] md:hidden" role="dialog" aria-modal="true">
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md"
                @click="sidebarOpen = false"></div>
            <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                class="relative flex-col w-full max-w-xs bg-white dark:bg-slate-900 h-full flex shadow-2xl border-r border-slate-200 dark:border-white/5">
                <!-- Mobile Header -->
                <div class="flex items-center justify-between px-8 h-24 border-b border-slate-100 dark:border-white/5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04 M12 21.355r 0 0 0 0-1.618-3.041z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-slate-900 dark:text-white italic">SVLDK</span>
                    </div>
                    <button @click="sidebarOpen = false" class="p-2 text-slate-500 hover:text-slate-900 dark:hover:text-white">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <nav class="p-6 space-y-1.5">
                    <x-nav-link href="{{ route('dashboard.index') }}" :active="request()->routeIs('dashboard.index')"
                        icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">Dashboard</x-nav-link>
                    @if(Auth::user()->hasPermission('citizens.manage'))
                    <x-nav-link href="{{ route('citizens.index') }}" :active="request()->routeIs('citizens.*')"
                        icon="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">Master Warga</x-nav-link>
                    @endif
                    <x-nav-link href="{{ route('dashboard.verify') }}" :active="request()->routeIs('dashboard.verify')"
                        icon="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.041M12 21.355r 0 0 0 0">Verifikasi
                        NIK</x-nav-link>
                    <x-nav-link href="{{ route('dashboard.history') }}" :active="request()->routeIs('dashboard.history')"
                        icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">History Log</x-nav-link>
                    @if(Auth::user()->hasPermission('users.manage'))
                    <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')"
                        icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        Manajemen User
                    </x-nav-link>
                    @endif
                    @if(Auth::user()->hasPermission('roles.manage'))
                    <x-nav-link href="{{ route('roles.index') }}" :active="request()->routeIs('roles.*')"
                        icon="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.041M12 21.355r 0 0 0 0">
                        Manajemen Role
                    </x-nav-link>
                    @endif
                    @if(Auth::user()->hasPermission('districts.manage'))
                    <x-nav-link href="{{ route('districts.index') }}" :active="request()->routeIs('districts.*')"
                        icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        Manajemen Kecamatan
                    </x-nav-link>
                    @endif
                    @if(Auth::user()->hasPermission('villages.manage'))
                    <x-nav-link href="{{ route('villages.index') }}" :active="request()->routeIs('villages.*')"
                        icon="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                        Manajemen Desa
                    </x-nav-link>
                    @endif
                </nav>
            </div>
        </div>

        <!-- Main Viewport -->
        <div class="flex-1 flex flex-col min-w-0 bg-slate-50 dark:bg-slate-950 relative">

            <!-- Global Top Bar -->
            <header
                class="flex items-center justify-between h-24 px-8 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-white/5 flex-shrink-0 z-10 sticky top-0 shadow-sm">
                <div class="flex items-center gap-6">
                    <button @click="sidebarOpen = true"
                        class="p-3 bg-slate-100 dark:bg-slate-800 rounded-xl text-slate-400 hover:text-slate-900 dark:hover:text-white md:hidden transition border border-slate-200 dark:border-white/5">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">@yield('title', 'Front Office')</h2>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5 whitespace-nowrap">Sistem Verifikasi & Layanan Data Kemiskinan</p>
                    </div>
                </div>

                <!-- Global Actions & User -->
                <div class="flex items-center gap-4 lg:gap-8">
                    <!-- Theme Toggle -->
                    <button @click="darkMode = !darkMode" 
                            class="p-3 bg-slate-100 dark:bg-slate-800 rounded-2xl text-slate-400 hover:text-indigo-500 transition border border-slate-200 dark:border-white/5">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>

                    <div class="hidden lg:flex flex-col text-right">
                        <span class="text-sm font-black text-slate-900 dark:text-white leading-tight">{{ Auth::user()->name }}</span>
                        <div class="flex items-center justify-end gap-2 mt-0.5">
                            @if (Auth::user()->desa_id)
                                <span
                                    class="bg-emerald-500/10 text-emerald-500 text-[8px] font-black uppercase tracking-widest px-1.5 py-0.5 rounded border border-emerald-500/20">
                                    {{ Auth::user()->village->name ?? 'Wilayah' }}
                                </span>
                            @endif
                            <span
                                class="text-[10px] text-slate-500 font-bold uppercase tracking-widest italic">{{ Auth::user()->role->name ?? 'Staff' }}</span>
                        </div>
                    </div>

                    <div class="relative">
                        <button type="button" @click.stop="userMenu = !userMenu" @click.away="userMenu = false"
                            class="group flex items-center p-1 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800/50 transition duration-300">
                            <div
                                class="h-12 w-12 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-white/5 flex items-center justify-center text-emerald-400 font-black shadow-inner transform group-hover:scale-95 transition">
                                {{ substr(Auth::user()->name ?? 'P', 0, 1) }}
                            </div>
                            <svg class="w-4 h-4 text-slate-500 ml-3 transition transform"
                                :class="userMenu ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="userMenu" x-cloak x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                            x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
                            class="absolute right-0 mt-4 w-64 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-3xl shadow-2xl overflow-hidden z-[100] backdrop-blur-xl">
                            <div class="px-6 py-6 bg-slate-50 dark:bg-slate-800/40 border-b border-slate-100 dark:border-white/5">
                                <p class="text-[10px] text-slate-500 uppercase tracking-[0.2em] mb-2 font-black">Account Information</p>
                                <p class="text-sm font-black text-slate-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="p-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-4 text-xs text-rose-400 hover:bg-rose-500/10 rounded-2xl transition flex items-center gap-3 group">
                                        <div class="p-2 bg-rose-500/10 rounded-lg group-hover:scale-110 transition">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                        </div>
                                        <span class="font-black uppercase tracking-widest text-[10px]">Terminate Session</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Page Content -->
            <main class="flex-grow overflow-y-auto overflow-x-hidden">
                <div class="min-h-full flex flex-col p-6 sm:p-10 lg:p-12">
                    <div class="flex-grow w-full pb-12">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{!! session('success') !!}',
                    confirmButtonText: 'Oke',
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-3xl border border-white/10 shadow-2xl',
                        confirmButton: 'bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl px-8 py-3 font-black tracking-widest shadow-lg shadow-emerald-500/30 transition'
                    },
                    buttonsStyling: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak!',
                    text: '{!! session('error') !!}',
                    confirmButtonText: 'Tutup',
                    customClass: {
                        popup: 'rounded-3xl border border-white/10 shadow-2xl',
                        confirmButton: 'bg-rose-500 hover:bg-rose-600 text-white rounded-xl px-8 py-3 font-black tracking-widest shadow-lg shadow-rose-500/30 transition'
                    },
                    buttonsStyling: false
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'warning',
                    title: 'Validasi Data Gagal',
                    html: `
                        <div class="text-sm text-slate-500 font-medium text-left bg-slate-100/50 dark:bg-slate-800/50 p-6 rounded-2xl border border-slate-200 dark:border-white/5 mt-4">
                            <p class="font-bold mb-2 text-slate-700 dark:text-slate-300">Mohon perbaiki formulir berikut:</p>
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    `,
                    confirmButtonText: 'Perbaiki',
                    customClass: {
                        popup: 'rounded-3xl border border-white/10 shadow-2xl',
                        confirmButton: 'bg-indigo-500 hover:bg-indigo-600 text-white rounded-xl px-8 py-3 font-black tracking-widest shadow-lg shadow-indigo-500/30 transition mt-4'
                    },
                    buttonsStyling: false
                });
            @endif
        });
    </script>
</body>

</html>
