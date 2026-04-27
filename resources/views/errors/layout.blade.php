<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    darkMode: localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
}" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    :class="{ 'dark': darkMode, 'light': !darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name', 'Sistem Verifikasi Pelayanan Dokumen') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<body
    class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-200 overflow-hidden flex items-center justify-center min-h-screen relative">

    <!-- Theme Toggle Floating -->
    <button @click="darkMode = !darkMode"
        class="absolute top-8 right-8 p-3 bg-white/50 dark:bg-slate-800/50 backdrop-blur-md rounded-2xl text-slate-400 hover:text-indigo-500 transition border border-white/50 dark:border-white/5 shadow-lg shadow-black/5 z-50">
        <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
        <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
    </button>

    <!-- Background Decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div
            class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500/10 dark:bg-indigo-500/20 rounded-full blur-[100px]">
        </div>
        <div
            class="absolute -bottom-40 -left-40 w-96 h-96 bg-rose-500/10 dark:bg-rose-500/20 rounded-full blur-[100px]">
        </div>
    </div>

    <!-- Main Content -->
    <div class="w-full max-w-2xl px-6 relative z-10 w-full">
        <div
            class="bg-white/60 dark:bg-slate-800/40 backdrop-blur-3xl border border-white/50 dark:border-slate-700/50 rounded-[3rem] shadow-2xl p-10 sm:p-16 text-center transform transition-all hover:scale-[1.01]">

            <div class="mb-8 flex justify-center">
                <div
                    class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-3xl flex items-center justify-center text-slate-800 dark:text-white shadow-inner @yield('icon_color')">
                    @yield('icon')
                </div>
            </div>

            <h1 class="text-6xl sm:text-8xl font-black text-slate-900 dark:text-white tracking-tighter mb-4">
                @yield('code')</h1>
            <h2 class="text-2xl font-bold text-slate-700 dark:text-slate-300 tracking-tight mb-4">@yield('title')</h2>

            <p class="text-slate-500 dark:text-slate-400 font-medium max-w-md mx-auto mb-10 text-sm leading-relaxed">
                @yield('message')
            </p>

            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard.index') }}"
                class="inline-flex items-center gap-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-8 py-4 rounded-2xl font-black shadow-xl shadow-slate-900/20 dark:shadow-white/20 transition-all hover:-translate-y-1 hover:shadow-2xl">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>

            <div
                class="mt-12 pt-8 border-t border-slate-200 dark:border-slate-700/50 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-bold text-slate-400 uppercase tracking-widest">
                <span>Sistem Verifikasi Pelayanan Dokumen</span>
                <span>Error Code: @yield('code')</span>
            </div>
        </div>
    </div>
</body>

</html>
