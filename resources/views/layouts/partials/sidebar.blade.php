            <!-- Mobile Sidebar Overlay -->
            <div x-show="mobileSidebar" x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="mobileSidebar = false"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] lg:hidden">
            </div>

            <!-- Sidebar Area -->
            <aside :class="{ 
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
                            <iconify-icon :icon="sidebarOpen ? 'lucide:panel-left-close' : 'lucide:panel-left-open'" class="text-xl"></iconify-icon>
                        </button>

                        <a href="{{ route('dashboard.index') }}"
                            class="flex items-center justify-center w-12 h-12 rounded-xl transition-all duration-300 {{ request()->routeIs('dashboard.*') || request()->routeIs('verification.*') ? 'bg-white shadow-sm dark:bg-slate-800 text-primary-acorn' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }}">
                            <iconify-icon icon="lucide:layout-grid" class="text-xl"></iconify-icon>
                        </a>
                        <a href="{{ route('citizens.index') }}"
                            class="flex items-center justify-center w-12 h-12 rounded-xl transition-all duration-300 {{ request()->routeIs('citizens.*') ? 'bg-white shadow-sm dark:bg-slate-800 text-primary-acorn' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }}">
                            <iconify-icon icon="lucide:users-2" class="text-xl"></iconify-icon>
                        </a>
                        @if (Auth::user()->hasPermission('users.manage'))
                            <a href="{{ route('users.index') }}"
                                class="flex items-center justify-center w-12 h-12 rounded-xl transition-all duration-300 {{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('districts.*') || request()->routeIs('villages.*') || request()->routeIs('admin.certificates.*') ? 'bg-white shadow-sm dark:bg-slate-800 text-primary-acorn' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }}">
                                <iconify-icon icon="lucide:settings-2" class="text-xl"></iconify-icon>
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
                            @if (request()->routeIs('dashboard.*') || request()->routeIs('verification.*'))
                                <div class="px-4 py-4">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Dashboards</p>
                                </div>
                                <x-nav-link href="{{ route('dashboard.index') }}" :active="request()->routeIs('dashboard.index')"
                                    icon="lucide:layout-dashboard">Default</x-nav-link>
                                <x-nav-link href="{{ route('verification.index') }}" :active="request()->routeIs('verification.index')"
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
                                    request()->routeIs('villages.*') ||
                                    request()->routeIs('admin.certificates.*'))
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
                                @if (Auth::user()->isSuperAdmin())
                                    <x-nav-link href="{{ route('admin.certificates.index') }}" :active="request()->routeIs('admin.certificates.*')"
                                        icon="lucide:badge-check">Sertifikat TTE</x-nav-link>
                                @endif
                            @endif
                        </nav>
                    </div>
                </div>


            </aside>
