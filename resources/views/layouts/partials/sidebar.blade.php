            <!-- Mobile Sidebar Overlay -->
            <div x-show="mobileSidebar" x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="mobileSidebar = false"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] lg:hidden">
            </div>

            <!-- Sidebar Area -->
            <aside
                :class="{
                    'translate-x-0': mobileSidebar,
                    '-translate-x-full lg:translate-x-0': !mobileSidebar,
                    'lg:w-[280px]': sidebarOpen,
                    'lg:w-[60px]': !sidebarOpen
                }"
                class="fixed lg:relative inset-y-0 left-0 w-[280px] flex flex-col h-full shrink-0 z-[70] lg:z-0 transition-all duration-300 ease-in-out bg-[var(--color-bg-page)] lg:bg-transparent p-4 lg:p-0 shadow-2xl lg:shadow-none">
                <div class="flex flex-1 min-h-0">

                    <!-- Primary Sidebar (Icons) -->
                    <div class="w-[60px] flex flex-col items-center py-4 gap-4">
                        <!-- Desktop Sidebar Toggle -->
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="hidden lg:flex items-center justify-center w-12 h-12 rounded-xl transition-all duration-300 text-slate-400 hover:bg-white/50 dark:hover:bg-slate-800"
                            title="Toggle Menu">
                            <iconify-icon :icon="sidebarOpen ? 'lucide:panel-left-close' : 'lucide:panel-left-open'"
                                class="text-xl"></iconify-icon>
                        </button>

                        <a href="{{ route('dashboard.index') }}"
                            class="flex items-center justify-center w-12 h-12 rounded-xl transition-all duration-300 {{ request()->routeIs('dashboard.*') ? 'bg-white shadow-sm dark:bg-slate-800 text-primary-acorn' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }}">
                            <iconify-icon icon="lucide:layout-grid" class="text-xl"></iconify-icon>
                        </a>
                        @can('citizens.manage')
                        <a href="{{ route('citizens.index') }}"
                            class="flex items-center justify-center w-12 h-12 rounded-xl transition-all duration-300 {{ request()->routeIs('citizens.*') ? 'bg-white shadow-sm dark:bg-slate-800 text-primary-acorn' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }}">
                            <iconify-icon icon="lucide:users-2" class="text-xl"></iconify-icon>
                        </a>
                        @endcan

                        @can('service.verify')
                            <a href="{{ route('services.verification') }}"
                                class="flex items-center justify-center w-12 h-12 rounded-xl transition-all duration-300 {{ request()->routeIs('services.verification') ? 'bg-white shadow-sm dark:bg-slate-800 text-primary-acorn' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }}">
                                <iconify-icon icon="lucide:clipboard-list" class="text-xl"></iconify-icon>
                            </a>
                        @endcan

                        @if (Auth::user()->hasAnyPermission(['users.manage', 'roles.manage', 'villages.manage', 'districts.manage']))
                            <a href="{{ route('users.index') }}"
                                class="flex items-center justify-center w-12 h-12 rounded-xl transition-all duration-300 {{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('districts.*') || request()->routeIs('villages.*') || request()->routeIs('admin.certificates.*') ? 'bg-white shadow-sm dark:bg-slate-800 text-primary-acorn' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }}">
                                <iconify-icon icon="lucide:settings-2" class="text-xl"></iconify-icon>
                            </a>
                        @endcan
                        <button
                            class="mt-auto flex items-center justify-center w-12 h-12 rounded-xl text-slate-400 hover:bg-white/50 dark:hover:bg-slate-800 transition-all duration-300">
                            <iconify-icon icon="lucide:code-2" class="text-xl"></iconify-icon>
                        </button>
                    </div>

                    <!-- Secondary Sidebar (Menu Card) -->
                    <div class="flex-1 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl shadow-sm p-4 flex flex-col custom-scrollbar overflow-y-auto"
                        x-show="sidebarOpen">
                        <nav class="flex-grow space-y-0.5">
                            @if (request()->routeIs('dashboard.*'))
                                <div class="px-4 py-4">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Dashboards</p>
                                </div>
                                <x-nav-link href="{{ route('dashboard.index') }}" :active="request()->routeIs('dashboard.index')"
                                    icon="lucide:layout-dashboard">Default</x-nav-link>
                                @if (Auth::user()->isOperatorDesa())
                                    <x-nav-link href="{{ route('dashboard.desa') }}" :active="request()->routeIs('dashboard.desa')"
                                        icon="lucide:building-2">Dashboard Desa</x-nav-link>
                                @endif
                            @elseif(request()->routeIs('services.*'))
                                <div class="px-4 py-4">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Layanan
                                    </p>
                                </div>
                                <x-nav-link href="{{ route('services.verification') }}" :active="request()->routeIs('services.verification')"
                                    icon="lucide:scan-line">Cek Data & Dokumen</x-nav-link>

                                @can('service.report')
                                    <x-nav-link href="{{ route('services.arrival.create') }}" :active="request()->routeIs('services.arrival.create')"
                                        icon="lucide:user-plus">Lapor Datang Warga</x-nav-link>
                                @endcan
                                
                                @can('service.manage')
                                    <div class="px-4 py-3 mt-4">
                                        <p class="text-[9px] font-black text-slate-400/60 uppercase tracking-widest">Antrean Permohonan</p>
                                    </div>
                                    
                                    <x-nav-link href="{{ route('services.requests.index') }}" :active="request()->routeIs('services.requests.index') && !request()->has('type')"
                                        icon="lucide:inbox">
                                        <div class="flex items-center justify-between w-full">
                                            <span>Semua Antrean</span>
                                            @if(($pendingBadges['all'] ?? 0) > 0)
                                                <span class="bg-primary-acorn text-white text-[9px] font-black px-2 py-0.5 rounded-full">{{ $pendingBadges['all'] }}</span>
                                            @endif
                                        </div>
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('services.requests.index', ['type' => 'KETERANGAN KEMISKINAN']) }}" :active="request()->query('type') === 'KETERANGAN KEMISKINAN'"
                                        icon="lucide:coins">
                                        <div class="flex items-center justify-between w-full">
                                            <span>Surat Miskin</span>
                                            @if(($pendingBadges['KETERANGAN KEMISKINAN'] ?? 0) > 0)
                                                <span class="bg-orange-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full">{{ $pendingBadges['KETERANGAN KEMISKINAN'] }}</span>
                                            @endif
                                        </div>
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('services.requests.index', ['type' => 'PENGANTAR PINDAH']) }}" :active="request()->query('type') === 'PENGANTAR PINDAH'"
                                        icon="lucide:truck">
                                        <div class="flex items-center justify-between w-full">
                                            <span>Pengantar Pindah</span>
                                            @if(($pendingBadges['PENGANTAR PINDAH'] ?? 0) > 0)
                                                <span class="bg-blue-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full">{{ $pendingBadges['PENGANTAR PINDAH'] }}</span>
                                            @endif
                                        </div>
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('services.requests.index', ['type' => 'KETERANGAN DOMISILI']) }}" :active="request()->query('type') === 'KETERANGAN DOMISILI'"
                                        icon="lucide:home">
                                        <div class="flex items-center justify-between w-full">
                                            <span>Domisili</span>
                                            @if(($pendingBadges['KETERANGAN DOMISILI'] ?? 0) > 0)
                                                <span class="bg-emerald-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full">{{ $pendingBadges['KETERANGAN DOMISILI'] }}</span>
                                            @endif
                                        </div>
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('services.requests.index', ['type' => 'SURAT KEMATIAN']) }}" :active="request()->query('type') === 'SURAT KEMATIAN'"
                                        icon="lucide:file-heart">
                                        <div class="flex items-center justify-between w-full">
                                            <span>Surat Kematian</span>
                                            @if(($pendingBadges['SURAT KEMATIAN'] ?? 0) > 0)
                                                <span class="bg-rose-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full">{{ $pendingBadges['SURAT KEMATIAN'] }}</span>
                                            @endif
                                        </div>
                                    </x-nav-link>
                                @endcan
                                
                                <div class="px-4 py-3 mt-4">
                                    <p class="text-[9px] font-black text-slate-400/60 uppercase tracking-widest">Lainnya</p>
                                </div>
                                <x-nav-link href="{{ route('services.history') }}" :active="request()->routeIs('services.history')"
                                    icon="lucide:history">History Pelayanan</x-nav-link>
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
                                    request()->routeIs('villages.*') ||
                                    request()->routeIs('admin.certificates.*'))
                                <div class="px-4 py-4">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Settings
                                    </p>
                                </div>
                                @can('users.manage')
                                    <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')"
                                        icon="lucide:user-cog">Users</x-nav-link>
                                @endcan
                                @can('roles.manage')
                                    <x-nav-link href="{{ route('roles.index') }}" :active="request()->routeIs('roles.*')"
                                        icon="lucide:shield-check">Roles</x-nav-link>
                                @endcan
                                @can('districts.manage')
                                    <x-nav-link href="{{ route('districts.index') }}" :active="request()->routeIs('districts.*')"
                                        icon="lucide:map">Districts</x-nav-link>
                                @endcan
                                @can('villages.manage')
                                    <x-nav-link href="{{ route('villages.index') }}" :active="request()->routeIs('villages.*')"
                                        icon="lucide:home">Villages</x-nav-link>
                                @endcan
                                @if (Auth::user()->isSuperAdmin())
                                    <x-nav-link href="{{ route('admin.certificates.index') }}" :active="request()->routeIs('admin.certificates.*')"
                                        icon="lucide:badge-check">Sertifikat TTE</x-nav-link>
                                @endif
                            @endif
                        </nav>
                    </div>
                </div>


            </aside>
