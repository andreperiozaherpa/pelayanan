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

        <!-- Header Partial -->
        @include('layouts.partials.header')

        <!-- Main Row: Sidebars & Content -->
        <div class="flex flex-1 overflow-hidden gap-0 lg:gap-6 relative">

            <!-- Mobile Sidebar Overlay -->
            <div x-show="mobileSidebar" x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="mobileSidebar = false"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] lg:hidden">
            </div>

            <!-- Sidebar Area -->
            <aside :class="{ 'translate-x-0': mobileSidebar, '-translate-x-full lg:translate-x-0': !mobileSidebar }"
                class="fixed lg:relative inset-y-0 left-0 w-[280px] flex flex-col h-full shrink-0 z-[70] lg:z-0 transition-transform duration-300 ease-in-out bg-[var(--color-bg-page)] lg:bg-transparent p-4 lg:p-0 shadow-2xl lg:shadow-none">
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
                    class="flex-1 bg-white/95 dark:bg-slate-900/95 backdrop-blur-3xl rounded-3xl lg:rounded-[2.5rem] p-6 lg:p-14 overflow-y-auto custom-scrollbar border border-white/50 dark:border-white/10">
                    @yield('content')
                </main>
            </div>
        </div>

        <!-- Global Footer -->
        <footer
            class="h-auto lg:h-12 shrink-0 w-full flex flex-col lg:flex-row items-center justify-between px-4 lg:px-8 py-4 lg:py-0 gap-4 text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2 z-50">
            <div class="text-center lg:text-left">
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
