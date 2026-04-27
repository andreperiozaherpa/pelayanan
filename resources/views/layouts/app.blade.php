<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    darkMode: localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    sidebarOpen: true,
    mobileSidebar: false,
    userMenu: false
}" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Verifikasi Pelayanan Dokumen') }} - @yield('title', 'Layanan Utama')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,300..900;1,300..900&family=Urbanist:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">

    <!-- Vite & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

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
            border-radius: 1.5rem !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        .light .swal2-popup {
            background: #ffffff !important;
            color: #2c3e50 !important;
            border-radius: 1.5rem !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1) !important;
        }

        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #334155;
        }
    </style>
</head>

<body class="font-sans antialiased bg-[var(--color-bg-page)] text-[var(--color-surface-text)] overflow-hidden">

    <div class="flex flex-col h-screen overflow-hidden p-4 lg:p-6">

        <!-- Top Row: Logo & Header -->
        <div class="flex h-16 shrink-0 items-center justify-between z-50 mb-2">
            <!-- Logo Area (Width matches Sidebar) -->
            <div class="w-[280px] flex items-center px-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-primary-acorn rounded-xl flex items-center justify-center shadow-lg shadow-primary-acorn/20 text-white">
                        <iconify-icon icon="lucide:leaf" class="text-xl"></iconify-icon>
                    </div>
                    <div class="hidden sm:block">
                        <h1
                            class="text-[11px] font-black text-slate-800 dark:text-white uppercase leading-snug tracking-widest">
                            Sistem Verifikasi<br><span class="text-primary-acorn">Pelayanan Dokumen</span></h1>
                    </div>
                </div>
            </div>

            <!-- Main Header -->
            <header class="flex-1 flex justify-between items-center px-8">
                <!-- Breadcrumbs -->
                <div class="hidden md:flex items-center gap-3 text-slate-400">
                    <iconify-icon icon="lucide:home" class="text-xs"></iconify-icon>
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] opacity-50">Home</span>
                    <iconify-icon icon="lucide:chevron-right" class="text-[10px] opacity-30"></iconify-icon>
                    <span
                        class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">@yield('title', 'DASHBOARD')</span>
                </div>

                <!-- Quick Actions & Profile -->
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-4 pr-6 border-r border-slate-200/50 dark:border-slate-800/50">
                        <button @click="darkMode = !darkMode"
                            class="text-slate-400 hover:text-primary-acorn transition">
                            <iconify-icon :icon="darkMode ? 'lucide:sun' : 'lucide:moon'"
                                class="text-xl"></iconify-icon>
                        </button>
                        <button class="text-slate-400 hover:text-primary-acorn transition">
                            <iconify-icon icon="lucide:search" class="text-xl"></iconify-icon>
                        </button>
                        <button class="relative text-slate-400 hover:text-primary-acorn transition">
                            <iconify-icon icon="lucide:bell" class="text-xl"></iconify-icon>
                            <span
                                class="absolute -top-1 -right-1 w-4 h-4 bg-primary-acorn text-[8px] text-white font-bold rounded-full flex items-center justify-center border-2 border-white dark:border-slate-900">2</span>
                        </button>
                    </div>

                    <div class="flex items-center gap-3" x-data="{ open: false }">
                        <div class="text-right hidden sm:block">
                            <p
                                class="text-[10px] font-black text-slate-900 dark:text-white leading-none uppercase tracking-wide">
                                {{ Auth::user()->name }}</p>
                            <p class="text-[9px] text-primary-acorn font-bold mt-1 uppercase tracking-tighter">
                                {{ Auth::user()->role->name ?? 'SUPER ADMIN' }}</p>
                        </div>
                        <div class="relative">
                            <button @click="open = !open"
                                class="h-10 w-10 rounded-full overflow-hidden border-2 border-white dark:border-slate-800 shadow-sm transition hover:border-primary-acorn">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=33ac1b&color=fff"
                                    class="w-full h-full object-cover">
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute right-0 mt-3 w-56 premium-card py-2 z-50">
                                <div class="px-4 py-3 border-b border-black/[0.03] dark:border-white/[0.03] mb-2">
                                    <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase">
                                        {{ Auth::user()->name }}</p>
                                    <p class="text-[9px] text-slate-400 uppercase mt-0.5">{{ Auth::user()->email }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2.5 text-[9px] text-rose-500 font-black uppercase tracking-widest hover:bg-rose-50 transition flex items-center gap-3">
                                        <iconify-icon icon="lucide:log-out"></iconify-icon>
                                        Keluar Sesi
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
        </div>

        <!-- Main Row: Sidebars & Content -->
        <div class="flex flex-1 overflow-hidden gap-6">

            <!-- Sidebar Area -->
            <aside class="w-[280px] flex flex-col h-full shrink-0">
                <div class="flex flex-1 min-h-0">

                    <!-- Primary Sidebar (Icons) -->
                    <div class="w-[60px] flex flex-col items-center py-4 gap-4">
                        <a href="{{ route('dashboard.index') }}"
                            class="flex items-center justify-center w-12 h-12 rounded-2xl transition-all duration-300 {{ request()->routeIs('dashboard.*') ? 'bg-white shadow-sm dark:bg-slate-800 text-primary-acorn' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }}">
                            <iconify-icon icon="lucide:layout-grid" class="text-xl"></iconify-icon>
                        </a>
                        <a href="{{ route('citizens.index') }}"
                            class="flex items-center justify-center w-12 h-12 rounded-2xl transition-all duration-300 {{ request()->routeIs('citizens.*') ? 'bg-white shadow-sm dark:bg-slate-800 text-primary-acorn' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }}">
                            <iconify-icon icon="lucide:users-2" class="text-xl"></iconify-icon>
                        </a>
                        @if (Auth::user()->hasPermission('users.manage'))
                            <a href="{{ route('users.index') }}"
                                class="flex items-center justify-center w-12 h-12 rounded-2xl transition-all duration-300 {{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('districts.*') || request()->routeIs('villages.*') ? 'bg-white shadow-sm dark:bg-slate-800 text-primary-acorn' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }}">
                                <iconify-icon icon="lucide:settings-2" class="text-xl"></iconify-icon>
                            </a>
                        @endif
                        <button
                            class="mt-auto flex items-center justify-center w-12 h-12 rounded-2xl text-slate-400 hover:bg-white/50 dark:hover:bg-slate-800 transition-all duration-300">
                            <iconify-icon icon="lucide:code-2" class="text-xl"></iconify-icon>
                        </button>
                    </div>

                    <!-- Secondary Sidebar (Menu Card) -->
                    <div class="flex-1 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-3xl shadow-sm p-4 flex flex-col custom-scrollbar overflow-y-auto"
                        x-show="sidebarOpen">
                        <nav class="flex-grow space-y-0.5">
                            @if (request()->routeIs('dashboard.*'))
                                <div class="px-4 py-4">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Dashboards</p>
                                </div>
                                <x-nav-link href="{{ route('dashboard.index') }}" :active="request()->routeIs('dashboard.index')"
                                    icon="lucide:layout-dashboard">Default</x-nav-link>
                                <x-nav-link href="{{ route('dashboard.verify') }}" :active="request()->routeIs('dashboard.verify')"
                                    icon="lucide:scan-line">Analytics</x-nav-link>
                                <x-nav-link href="{{ route('dashboard.history') }}" :active="request()->routeIs('dashboard.history')"
                                    icon="lucide:history">History</x-nav-link>
                            @elseif(request()->routeIs('citizens.*'))
                                <div class="px-4 py-4">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Citizens
                                    </p>
                                </div>
                                <x-nav-link href="{{ route('citizens.index') }}" :active="request()->routeIs('citizens.index')"
                                    icon="lucide:users">Directory</x-nav-link>
                                <x-nav-link href="{{ route('citizens.create') }}" :active="request()->routeIs('citizens.create')"
                                    icon="lucide:user-plus">Registration</x-nav-link>
                            @elseif(request()->routeIs('users.*') ||
                                    request()->routeIs('roles.*') ||
                                    request()->routeIs('districts.*') ||
                                    request()->routeIs('villages.*'))
                                <div class="px-4 py-4">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Settings
                                    </p>
                                </div>
                                <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')"
                                    icon="lucide:user-cog">Users</x-nav-link>
                                <x-nav-link href="{{ route('roles.index') }}" :active="request()->routeIs('roles.*')"
                                    icon="lucide:shield-check">Roles</x-nav-link>
                                <x-nav-link href="{{ route('districts.index') }}" :active="request()->routeIs('districts.*')"
                                    icon="lucide:map">Districts</x-nav-link>
                                <x-nav-link href="{{ route('villages.index') }}" :active="request()->routeIs('villages.*')"
                                    icon="lucide:home">Villages</x-nav-link>
                            @endif
                        </nav>
                    </div>
                </div>


            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0">
                <main
                    class="flex-1 bg-white/95 dark:bg-slate-900/95 backdrop-blur-3xl rounded-[2.5rem] p-10 lg:p-14 overflow-y-auto custom-scrollbar border border-white/50 dark:border-white/10">
                    @yield('content')
                </main>
            </div>
        </div>

        <!-- Global Footer -->
        <footer
            class="h-12 shrink-0 w-full flex items-center justify-between px-8 text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2 z-50">
            <div>
                <span>&copy; {{ date('Y') }} Sistem Verifikasi Pelayanan Dokumen</span>
            </div>
            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-primary-acorn transition">About</a>
                <a href="#" class="hover:text-primary-acorn transition">Docs</a>
                <a href="#" class="hover:text-primary-acorn transition">Purchase</a>
            </div>
        </footer>
    </div>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'BERHASIL!',
                    text: '{!! session('success') !!}',
                    confirmButtonText: 'OKE',
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-[1.5rem] border-none shadow-2xl',
                        confirmButton: 'bg-primary-acorn text-white rounded-xl px-8 py-3 font-bold transition'
                    },
                    buttonsStyling: false
                });
            @endif
        });
    </script>
</body>

</html>
