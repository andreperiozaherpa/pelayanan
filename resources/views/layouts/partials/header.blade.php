<!-- Top Row: Logo & Header -->
<div class="flex h-16 shrink-0 items-center justify-between z-50 mb-2 max-w-[2000px] mx-auto w-full">
    <!-- Mobile Menu Toggle -->
    <button @click="mobileSidebar = !mobileSidebar"
        class="lg:hidden p-2 text-slate-400 hover:text-primary-acorn transition">
        <iconify-icon icon="lucide:menu" class="text-2xl"></iconify-icon>
    </button>

    <!-- Logo Area (Width matches Sidebar on Desktop) -->
    <div class="w-auto lg:w-[280px] flex items-center px-2 lg:px-4">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 bg-primary-acorn rounded-xl flex items-center justify-center shadow-lg shadow-primary-acorn/20 text-white shrink-0">
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
    <header class="flex-1 flex justify-end lg:justify-between items-center px-2 lg:px-8">
        <!-- Breadcrumbs -->
        <div class="hidden md:flex items-center gap-3 text-slate-400">
            <iconify-icon icon="lucide:home" class="text-xs"></iconify-icon>
            <span class="text-[9px] font-black uppercase tracking-[0.2em] opacity-50">Home</span>
            <iconify-icon icon="lucide:chevron-right" class="text-[10px] opacity-30"></iconify-icon>
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">@yield('title', 'DASHBOARD')</span>
        </div>

        <!-- Quick Actions & Profile -->
        <div class="flex items-center gap-3 lg:gap-6 2xl:gap-10">
            <div
                class="flex items-center gap-2 lg:gap-4 2xl:gap-6 pr-3 lg:pr-6 border-r border-slate-200/50 dark:border-slate-800/50">
                <button @click="darkMode = !darkMode" class="text-slate-400 hover:text-primary-acorn transition">
                    <iconify-icon :icon="darkMode ? 'lucide:sun' : 'lucide:moon'"
                        class="text-xl 2xl:text-2xl"></iconify-icon>
                </button>
                <button class="hidden xs:block text-slate-400 hover:text-primary-acorn transition">
                    <iconify-icon icon="lucide:search" class="text-xl 2xl:text-2xl"></iconify-icon>
                </button>
                <button class="relative text-slate-400 hover:text-primary-acorn transition">
                    <iconify-icon icon="lucide:bell" class="text-xl 2xl:text-2xl"></iconify-icon>
                    <span
                        class="absolute -top-1 -right-1 w-4 h-4 bg-primary-acorn text-[8px] text-white font-bold rounded-full flex items-center justify-center border-2 border-white dark:border-slate-900">2</span>
                </button>
            </div>

            <div class="flex items-center gap-3 2xl:gap-5" x-data="{ open: false }">
                <div class="text-right hidden sm:block">
                    <p
                        class="text-[10px] 2xl:text-[12px] font-black text-slate-900 dark:text-white leading-none uppercase tracking-wide">
                        {{ Auth::user()->name }}</p>
                    <p class="text-[9px] 2xl:text-[10px] text-primary-acorn font-bold mt-1 uppercase tracking-tighter">
                        {{ Auth::user()->role->name ?? 'SUPER ADMIN' }}</p>
                </div>
                <div class="relative">
                    <button @click="open = !open"
                        class="h-10 w-10 2xl:h-12 2xl:w-12 rounded-full overflow-hidden border-2 border-white dark:border-slate-800 shadow-sm transition hover:border-primary-acorn">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=33ac1b&color=fff"
                            class="w-full h-full object-cover">
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                        class="absolute right-0 mt-4 w-64 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl rounded-[2rem] shadow-2xl border border-black/[0.03] dark:border-white/[0.05] py-3 z-50 origin-top-right">

                        <!-- User Info Header -->
                        <div
                            class="px-6 py-4 border-b border-black/[0.03] dark:border-white/[0.03] mb-2 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl overflow-hidden shadow-inner shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=33ac1b&color=fff"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase truncate">
                                    {{ Auth::user()->name }}</p>
                                <p class="text-[9px] text-slate-400 uppercase mt-0.5 truncate">{{ Auth::user()->email }}
                                </p>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="px-2 space-y-1">
                            <a href="#"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[9px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-white/[0.02] hover:text-primary-acorn transition group">
                                <iconify-icon icon="lucide:user"
                                    class="text-base group-hover:scale-110 transition"></iconify-icon>
                                Profil Saya
                            </a>
                            <a href="#"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[9px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-white/[0.02] hover:text-primary-acorn transition group">
                                <iconify-icon icon="lucide:settings"
                                    class="text-base group-hover:scale-110 transition"></iconify-icon>
                                Pengaturan
                            </a>
                            <a href="#"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[9px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-white/[0.02] hover:text-primary-acorn transition group">
                                <iconify-icon icon="lucide:shield-check"
                                    class="text-base group-hover:scale-110 transition"></iconify-icon>
                                Keamanan
                            </a>
                        </div>

                        <!-- Divider -->
                        <div class="my-2 border-t border-black/[0.03] dark:border-white/[0.03]"></div>

                        <!-- Logout -->
                        <div class="px-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2.5 rounded-xl text-[9px] text-rose-500 font-black uppercase tracking-widest hover:bg-rose-50 dark:hover:bg-rose-500/10 transition flex items-center gap-3 group">
                                    <iconify-icon icon="lucide:log-out"
                                        class="text-base group-hover:translate-x-1 transition"></iconify-icon>
                                    Keluar Sesi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>
