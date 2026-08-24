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
                            class="flex flex-col items-center justify-center w-12 h-12 rounded-xl transition-all duration-300 {{ request()->routeIs('dashboard.*') ? 'bg-white shadow-sm dark:bg-slate-800 text-primary-acorn' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }}"
                            title="Dashboard">
                            <iconify-icon icon="lucide:layout-grid" class="text-xl"></iconify-icon>
                            <span class="text-[8px] font-semibold mt-0.5 leading-none">Dash</span>
                        </a>
                        @php
                            $servicesActive = request()->routeIs('services.*') || request()->routeIs('citizens.*');
                            $hasServicesAccess =
                                Auth::user()->can('service.verify') ||
                                Auth::user()->can('citizens.manage') ||
                                Auth::user()->can('service.report') ||
                                Auth::user()->can('service.manage');

                            $servicesUrl = '#';
                            if (Auth::user()->can('service.verify')) {
                                $servicesUrl = route('services.verification');
                            } elseif (Auth::user()->can('citizens.manage')) {
                                $servicesUrl = route('citizens.index');
                            } elseif (Auth::user()->can('service.manage')) {
                                $servicesUrl = route('services.requests.index');
                            } elseif (Auth::user()->can('service.report')) {
                                $servicesUrl = route('services.arrival.create');
                            }
                        @endphp

                        @if (Auth::user()->hasAnyPermission([
                                'cms.articles.view',
                                'cms.pages.view',
                                'cms.banners.view',
                                'cms.faqs.view',
                                'cms.testimonials.view',
                                'cms.teams.view',
                                'cms.settings.view',
                                'cms.complaints.view',
                            ]))
                            <a href="{{ route('cms-articles.index') }}"
                                class="flex flex-col items-center justify-center w-12 h-12 rounded-xl transition-all duration-300 {{ request()->routeIs('cms-articles.*') || request()->routeIs('cms-categories.*') || request()->routeIs('cms-pages.*') || request()->routeIs('cms-banners.*') || request()->routeIs('cms-faqs.*') || request()->routeIs('cms-testimonials.*') || request()->routeIs('cms-teams.*') || request()->routeIs('cms-settings.*') || request()->routeIs('cms-complaints.*') ? 'bg-white shadow-sm dark:bg-slate-800 text-primary-acorn' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }}"
                                title="CMS Website">
                                <iconify-icon icon="lucide:layout-template" class="text-xl"></iconify-icon>
                                <span class="text-[8px] font-semibold mt-0.5 leading-none">CMS</span>
                            </a>
                        @endif

                        @if (Auth::user()->can('service.report'))
                            <a href="{{ route('mpp-requests.index') }}"
                                class="flex flex-col items-center justify-center w-12 h-12 rounded-xl transition-all duration-300 {{ request()->routeIs('mpp-requests.*') || request()->routeIs('mpp-services.*') || request()->routeIs('counter-users.*') || request()->routeIs('counters.*') || request()->routeIs('gerais.*') || request()->routeIs('skm.*') || request()->routeIs('display-settings.*') ? 'bg-white shadow-sm dark:bg-slate-800 text-primary-acorn' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }}"
                                title="MPP - Mal Pelayanan Publik">
                                <iconify-icon icon="lucide:building-2" class="text-xl"></iconify-icon>
                                <span class="text-[8px] font-semibold mt-0.5 leading-none">MPP</span>
                            </a>
                        @endif

                        @if ($hasServicesAccess)
                            <a href="{{ $servicesUrl }}"
                                class="flex flex-col items-center justify-center w-12 h-12 rounded-xl transition-all duration-300 {{ $servicesActive ? 'bg-white shadow-sm dark:bg-slate-800 text-primary-acorn' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }}"
                                title="Pelayanan Warga & Surat (Cek Dokumen, Lapor Datang, Permohonan Surat, Kependudukan)">
                                <iconify-icon icon="lucide:file-signature" class="text-xl"></iconify-icon>
                                <span class="text-[8px] font-semibold mt-0.5 leading-none">Pelayanan</span>
                            </a>
                        @endif

                        @can('maps.manage')
                            <a href="{{ route('map-regions.index') }}"
                                class="flex flex-col items-center justify-center w-12 h-12 rounded-xl transition-all duration-300 {{ request()->routeIs('map-regions.*') || request()->routeIs('map-zones.*') || request()->routeIs('map-locations.*') || request()->routeIs('map-zone-types.*') || request()->routeIs('map-location-categories.*') ? 'bg-white shadow-sm dark:bg-slate-800 text-primary-acorn' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }}"
                                title="SIBERUGO GIS">
                                <iconify-icon icon="lucide:map" class="text-xl"></iconify-icon>
                                <span class="text-[8px] font-semibold mt-0.5 leading-none">GIS</span>
                            </a>
                        @endcan

                        @if (Auth::user()->hasAnyPermission([
                                'users.manage',
                                'roles.manage',
                                'villages.manage',
                                'districts.manage',
                                'opds.manage',
                            ]) || Auth::user()->can('system.manage'))
                            <a href="{{ route('users.index') }}"
                                class="flex flex-col items-center justify-center w-12 h-12 rounded-xl transition-all duration-300 {{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('districts.*') || request()->routeIs('villages.*') || request()->routeIs('opds.*') || request()->routeIs('admin.certificates.*') || request()->routeIs('mpp-services.*') ? 'bg-white shadow-sm dark:bg-slate-800 text-primary-acorn' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }}"
                                title="Pengaturan Sistem">
                                <iconify-icon icon="lucide:settings-2" class="text-xl"></iconify-icon>
                                <span class="text-[8px] font-semibold mt-0.5 leading-none">Sistem</span>
                            </a>
                        @endif

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
                            @elseif(request()->routeIs('mpp-requests.*') ||
                                    request()->routeIs('mpp-services.*') ||
                                    request()->routeIs('counter-users.*') ||
                                    request()->routeIs('counters.*') ||
                                    request()->routeIs('gerais.*') ||
                                    request()->routeIs('skm.*') ||
                                    request()->routeIs('display-settings.*'))
                                <div class="px-4 py-4">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">MPP
                                        & Pelayanan</p>
                                </div>
                                @can('service.report')
                                    <x-nav-link href="{{ route('mpp-requests.index') }}" :active="request()->routeIs('mpp-requests.index')"
                                        icon="lucide:layout-list">Daftar Pengajuan MPP</x-nav-link>

                                    {{-- @php
                                        $activeMppServices = \App\Models\MppService::where('is_active', true)
                                            ->orderBy('name')
                                            ->get();
                                    @endphp
                                    @foreach ($activeMppServices as $mppSvc)
                                        <x-nav-link href="{{ route('mpp-requests.create', $mppSvc->slug) }}"
                                            :active="request()->routeIs('mpp-requests.create') &&
                                                request()->route('mppService')?->slug === $mppSvc->slug" icon="lucide:file-plus">{{ $mppSvc->name }}</x-nav-link>
                                    @endforeach --}}
                                @endcan

                                @can('system.manage')
                                    <div class="px-4 py-3 mt-4">
                                        <p class="text-[9px] font-black text-slate-400/60 uppercase tracking-widest">
                                            Konfigurasi Layanan</p>
                                    </div>
                                    <x-nav-link href="{{ route('gerais.index') }}" :active="request()->routeIs('gerais.*')"
                                        icon="lucide:store">Daftar Gerai</x-nav-link>
                                    <x-nav-link href="{{ route('counters.index') }}" :active="request()->routeIs('counters.*')"
                                        icon="lucide:layout-grid">Daftar Loket</x-nav-link>
                                    <x-nav-link href="{{ route('mpp-services.index') }}" :active="request()->routeIs('mpp-services.*')"
                                        icon="lucide:file-text">Pelayanan MPP</x-nav-link>
                                    <x-nav-link href="{{ route('counter-users.index') }}" :active="request()->routeIs('counter-users.*')"
                                        icon="lucide:user-cog">Penugasan Loket</x-nav-link>
                                @endcan

                                @can('mpp.display.settings')
                                    <div class="px-4 py-3 mt-4">
                                        <p class="text-[9px] font-black text-slate-400/60 uppercase tracking-widest">
                                            Display Caller</p>
                                    </div>
                                    <x-nav-link href="{{ route('display-settings.index') }}" :active="request()->routeIs('display-settings.*')"
                                        icon="lucide:monitor-play">Pengaturan Display</x-nav-link>
                                @endcan

                                @can('service.report')
                                    <div class="px-4 py-3 mt-4">
                                        <p class="text-[9px] font-black text-slate-400/60 uppercase tracking-widest">
                                            Survei Kepuasan Masyarakat</p>
                                    </div>
                                    <x-nav-link href="{{ route('skm.index') }}" :active="request()->routeIs('skm.*')"
                                        icon="lucide:clipboard-check">Laporan Survei (SKM)</x-nav-link>
                                @endcan
                            @elseif(request()->routeIs('services.*') || request()->routeIs('citizens.*'))
                                <div class="px-4 py-4">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pelayanan
                                        & Loket MPP</p>
                                </div>
                                @can('service.verify')
                                    <x-nav-link href="{{ route('services.verification') }}" :active="request()->routeIs('services.verification') &&
                                        !request()->has('service_type')"
                                        icon="lucide:scan-line">Cek Data & Dokumen</x-nav-link>
                                @endcan

                                @can('service.manage')
                                    <div class="px-4 py-3 mt-4">
                                        <p class="text-[9px] font-black text-slate-400/60 uppercase tracking-widest">Antrean
                                            Verifikasi Surat Warga</p>
                                    </div>

                                    <x-nav-link href="{{ route('services.requests.index') }}" :active="request()->routeIs('services.requests.index') && !request()->has('type')"
                                        icon="lucide:inbox">
                                        <div class="flex items-center justify-between w-full">
                                            <span>Semua Antrean</span>
                                            @if (($pendingBadges['all'] ?? 0) > 0)
                                                <span
                                                    class="bg-primary-acorn text-white text-[9px] font-black px-2 py-0.5 rounded-full">{{ $pendingBadges['all'] }}</span>
                                            @endif
                                        </div>
                                    </x-nav-link>
                                    <x-nav-link
                                        href="{{ route('services.requests.index', ['type' => 'KETERANGAN KEMISKINAN']) }}"
                                        :active="request()->query('type') === 'KETERANGAN KEMISKINAN'" icon="lucide:coins">
                                        <div class="flex items-center justify-between w-full">
                                            <span>Surat Miskin</span>
                                            @if (($pendingBadges['KETERANGAN KEMISKINAN'] ?? 0) > 0)
                                                <span
                                                    class="bg-orange-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full">{{ $pendingBadges['KETERANGAN KEMISKINAN'] }}</span>
                                            @endif
                                        </div>
                                    </x-nav-link>
                                    <x-nav-link
                                        href="{{ route('services.requests.index', ['type' => 'PENGANTAR PINDAH']) }}"
                                        :active="request()->query('type') === 'PENGANTAR PINDAH'" icon="lucide:truck">
                                        <div class="flex items-center justify-between w-full">
                                            <span>Pengantar Pindah</span>
                                            @if (($pendingBadges['PENGANTAR PINDAH'] ?? 0) > 0)
                                                <span
                                                    class="bg-blue-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full">{{ $pendingBadges['PENGANTAR PINDAH'] }}</span>
                                            @endif
                                        </div>
                                    </x-nav-link>
                                    <x-nav-link
                                        href="{{ route('services.requests.index', ['type' => 'KETERANGAN DOMISILI']) }}"
                                        :active="request()->query('type') === 'KETERANGAN DOMISILI'" icon="lucide:home">
                                        <div class="flex items-center justify-between w-full">
                                            <span>Domisili</span>
                                            @if (($pendingBadges['KETERANGAN DOMISILI'] ?? 0) > 0)
                                                <span
                                                    class="bg-emerald-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full">{{ $pendingBadges['KETERANGAN DOMISILI'] }}</span>
                                            @endif
                                        </div>
                                    </x-nav-link>
                                    <x-nav-link
                                        href="{{ route('services.requests.index', ['type' => 'SURAT KEMATIAN']) }}"
                                        :active="request()->query('type') === 'SURAT KEMATIAN'" icon="lucide:file-heart">
                                        <div class="flex items-center justify-between w-full">
                                            <span>Surat Kematian</span>
                                            @if (($pendingBadges['SURAT KEMATIAN'] ?? 0) > 0)
                                                <span
                                                    class="bg-rose-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full">{{ $pendingBadges['SURAT KEMATIAN'] }}</span>
                                            @endif
                                        </div>
                                    </x-nav-link>
                                @endcan

                                @can('citizens.manage')
                                    <div class="px-4 py-3 mt-4">
                                        <p class="text-[9px] font-black text-slate-400/60 uppercase tracking-widest">
                                            Kependudukan Warga</p>
                                    </div>
                                    <x-nav-link href="{{ route('citizens.index') }}" :active="request()->routeIs('citizens.index') ||
                                        request()->routeIs('citizens.show') ||
                                        request()->routeIs('citizens.edit')"
                                        icon="lucide:users">Data Warga (Direktori)</x-nav-link>
                                    <x-nav-link href="{{ route('citizens.create') }}" :active="request()->routeIs('citizens.create')"
                                        icon="lucide:user-plus">Pendaftaran Warga Baru</x-nav-link>
                                @endcan

                                <div class="px-4 py-3 mt-4">
                                    <p class="text-[9px] font-black text-slate-400/60 uppercase tracking-widest">
                                        Riwayat
                                        & Arsip</p>
                                </div>
                                <x-nav-link href="{{ route('services.history') }}" :active="request()->routeIs('services.history')"
                                    icon="lucide:history">Riwayat Pelayanan</x-nav-link>
                            @elseif(request()->routeIs('users.*') ||
                                    request()->routeIs('roles.*') ||
                                    request()->routeIs('districts.*') ||
                                    request()->routeIs('villages.*') ||
                                    request()->routeIs('opds.*') ||
                                    request()->routeIs('admin.certificates.*') ||
                                    request()->routeIs('mpp-services.*'))
                                <div class="px-4 py-4">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Pengaturan Sistem</p>
                                </div>

                                @if (Auth::user()->can('users.manage') || Auth::user()->can('roles.manage') || Auth::user()->isSuperAdmin())
                                    <div class="px-4 py-3 mt-2">
                                        <p class="text-[9px] font-black text-slate-400/60 uppercase tracking-widest">
                                            Keamanan & Pengguna</p>
                                    </div>
                                    @can('users.manage')
                                        <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')"
                                            icon="lucide:user-cog">Pengguna Sistem</x-nav-link>
                                    @endcan
                                    @can('roles.manage')
                                        <x-nav-link href="{{ route('roles.index') }}" :active="request()->routeIs('roles.*')"
                                            icon="lucide:shield-check">Hak Akses (Roles)</x-nav-link>
                                    @endcan
                                    @if (Auth::user()->isSuperAdmin())
                                        <x-nav-link href="{{ route('admin.certificates.index') }}" :active="request()->routeIs('admin.certificates.*')"
                                            icon="lucide:badge-check">Sertifikat TTE</x-nav-link>
                                    @endif
                                @endif

                                @if (Auth::user()->can('districts.manage') || Auth::user()->can('villages.manage') || Auth::user()->can('opds.manage'))
                                    <div class="px-4 py-3 mt-4">
                                        <p class="text-[9px] font-black text-slate-400/60 uppercase tracking-widest">
                                            Wilayah & Instansi</p>
                                    </div>
                                    @can('districts.manage')
                                        <x-nav-link href="{{ route('districts.index') }}" :active="request()->routeIs('districts.*')"
                                            icon="lucide:map">Wilayah Kecamatan</x-nav-link>
                                    @endcan
                                    @can('villages.manage')
                                        <x-nav-link href="{{ route('villages.index') }}" :active="request()->routeIs('villages.*')"
                                            icon="lucide:home">Wilayah Desa / Tiyuh</x-nav-link>
                                    @endcan
                                    @can('opds.manage')
                                        <x-nav-link href="{{ route('opds.index') }}" :active="request()->routeIs('opds.*')"
                                            icon="lucide:building-2">Instansi OPD</x-nav-link>
                                    @endcan
                                @endif
                            @elseif(request()->routeIs('map-regions.*') ||
                                    request()->routeIs('map-zones.*') ||
                                    request()->routeIs('map-locations.*') ||
                                    request()->routeIs('map-zone-types.*') ||
                                    request()->routeIs('map-location-categories.*'))
                                <div class="px-4 py-4">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">SIBERUGO
                                        GIS
                                    </p>
                                </div>
                                @can('maps.manage')
                                    <x-nav-link href="{{ route('map-regions.index') }}" :active="request()->routeIs('map-regions.*')"
                                        icon="lucide:map">Batas Wilayah</x-nav-link>
                                    <x-nav-link href="{{ route('map-zones.index') }}" :active="request()->routeIs('map-zones.*')"
                                        icon="lucide:layers">Zonasi Fungsi</x-nav-link>
                                    <x-nav-link href="{{ route('map-locations.index') }}" :active="request()->routeIs('map-locations.*')"
                                        icon="lucide:map-pin">Titik Lokasi (POI)</x-nav-link>
                                    <x-nav-link href="{{ route('map-zone-types.index') }}" :active="request()->routeIs('map-zone-types.*')"
                                        icon="lucide:palette">Tipe Zonasi</x-nav-link>
                                    <x-nav-link href="{{ route('map-location-categories.index') }}" :active="request()->routeIs('map-location-categories.*')"
                                        icon="lucide:tags">Kategori POI</x-nav-link>
                                @endcan
                            @elseif(request()->routeIs('cms-articles.*') ||
                                    request()->routeIs('cms-categories.*') ||
                                    request()->routeIs('cms-pages.*') ||
                                    request()->routeIs('cms-menus.*') ||
                                    request()->routeIs('cms-banners.*') ||
                                    request()->routeIs('cms-faqs.*') ||
                                    request()->routeIs('cms-testimonials.*') ||
                                    request()->routeIs('cms-teams.*') ||
                                    request()->routeIs('cms-settings.*') ||
                                    request()->routeIs('cms-complaints.*'))
                                <div class="px-4 py-4">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">CMS
                                        Website</p>
                                </div>
                                @can('cms.articles.view')
                                    <x-nav-link href="{{ route('cms-articles.index') }}" :active="request()->routeIs('cms-articles.*')"
                                        icon="lucide:file-text">Artikel / Blog</x-nav-link>
                                    <x-nav-link href="{{ route('cms-categories.index') }}" :active="request()->routeIs('cms-categories.*')"
                                        icon="lucide:folder-open">Kategori Artikel</x-nav-link>
                                @endcan
                                @can('cms.pages.view')
                                    <x-nav-link href="{{ route('cms-pages.index') }}" :active="request()->routeIs('cms-pages.*')"
                                        icon="lucide:layout">Halaman Statis</x-nav-link>
                                @endcan
                                @can('cms.menus.view')
                                    <x-nav-link href="{{ route('cms-menus.index') }}" :active="request()->routeIs('cms-menus.*')"
                                        icon="lucide:menu">Menu Navigasi</x-nav-link>
                                @endcan
                                @can('cms.banners.view')
                                    <x-nav-link href="{{ route('cms-banners.index') }}" :active="request()->routeIs('cms-banners.*')"
                                        icon="lucide:image">Banner Slider</x-nav-link>
                                @endcan
                                @can('cms.faqs.view')
                                    <x-nav-link href="{{ route('cms-faqs.index') }}" :active="request()->routeIs('cms-faqs.*')"
                                        icon="lucide:help-circle">FAQ</x-nav-link>
                                @endcan
                                @can('cms.testimonials.view')
                                    <x-nav-link href="{{ route('cms-testimonials.index') }}" :active="request()->routeIs('cms-testimonials.*')"
                                        icon="lucide:message-square">Testimoni</x-nav-link>
                                @endcan
                                @can('cms.teams.view')
                                    <x-nav-link href="{{ route('cms-teams.index') }}" :active="request()->routeIs('cms-teams.*')"
                                        icon="lucide:users">Struktur Organisasi</x-nav-link>
                                @endcan
                                @can('cms.complaints.view')
                                    <x-nav-link href="{{ route('cms-complaints.index') }}" :active="request()->routeIs('cms-complaints.*')"
                                        icon="lucide:message-square-warning">Pengaduan Masyarakat</x-nav-link>
                                @endcan
                                @can('cms.settings.view')
                                    <x-nav-link href="{{ route('cms-settings.index') }}" :active="request()->routeIs('cms-settings.*')"
                                        icon="lucide:settings">Pengaturan Website</x-nav-link>
                                @endcan
                            @endif
                        </nav>
                    </div>
                </div>
            </aside>
