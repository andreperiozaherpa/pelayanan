<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    darkMode: localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    sidebarOpen: localStorage.getItem('sidebarOpen') === 'false' ? false : true,
    mobileSidebar: false,
    userMenu: false
}" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val));
$watch('sidebarOpen', val => localStorage.setItem('sidebarOpen', val));"
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

    <title>{{ config('app.name', 'SIBERUGO MPP') }} - @yield('title', 'Layanan Utama')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-tubaba.png') }}">

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

        .swal2-popup {
            border-radius: 2.5rem !important;
            padding: 2.5rem !important;
            font-family: var(--font-sans) !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1) !important;
        }

        .dark .swal2-popup {
            background: #0f172a !important;
            color: #f1f5f9 !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        .swal2-title {
            font-family: var(--font-heading) !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: -0.02em !important;
            color: inherit !important;
        }

        .swal2-html-container {
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            line-height: 1.6 !important;
            color: #64748b !important;
        }

        .dark .swal2-html-container {
            color: #94a3b8 !important;
        }

        .swal2-confirm,
        .swal2-cancel {
            border-radius: 1.25rem !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            padding: 1.1rem 2.5rem !important;
            font-size: 10px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .swal2-confirm {
            background-color: #33ac1b !important;
            box-shadow: 0 10px 20px -5px rgba(51, 172, 27, 0.3) !important;
        }

        .swal2-confirm:hover {
            transform: translateY(-2px) scale(1.02) !important;
            box-shadow: 0 15px 25px -5px rgba(51, 172, 27, 0.4) !important;
        }

        .swal2-cancel {
            background-color: #f1f5f9 !important;
            color: #64748b !important;
        }

        .dark .swal2-cancel {
            background-color: #1e293b !important;
            color: #94a3b8 !important;
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

    <div class="flex flex-col h-screen overflow-hidden p-4 lg:p-6 max-w-[2000px] mx-auto w-full gap-4 lg:gap-6">

        <!-- Header Partial -->
        @include('layouts.partials.header')

        <!-- Main Row: Sidebars & Content -->
        <div class="flex flex-1 overflow-hidden gap-0 lg:gap-6 relative px-2">

            @include('layouts.partials.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0">
                <main
                    class="flex-1 bg-white/95 dark:bg-slate-900/95 backdrop-blur-3xl rounded-2xl p-6 lg:p-14 overflow-y-auto custom-scrollbar border border-white/50 dark:border-white/10">
                    @yield('content')
                </main>
            </div>
        </div>

        <!-- Global Footer -->
        @include('layouts.partials.footer')
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
                        popup: 'rounded-[2.5rem] border-none shadow-2xl p-8',
                        confirmButton: 'bg-primary-acorn text-white rounded-2xl px-10 py-4 font-black text-[10px] uppercase tracking-widest transition-all hover:scale-105'
                    },
                    buttonsStyling: false
                });
            @endif
        });
    </script>
</body>

</html>
