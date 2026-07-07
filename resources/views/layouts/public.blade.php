<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-tubaba.png') }}">
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @include('components.landing.seo-meta', ['settings' => $settings, 'seo' => $seo ?? null])

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Icons --}}
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@2.1.0/dist/iconify-icon.min.js" defer></script>

    {{-- AlpineJS --}}
    <script defer src="https://unpkg.com/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css'])

    <style>
        [x-cloak] { display: none !important; }
        html { font-family: 'Outfit', sans-serif; }
        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at top, #e8dec9 0%, #d6c5b3 100%);
            color: #3c2f2f;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 8px 32px 0 rgba(93, 30, 37, 0.08);
            border-radius: 1.5rem;
        }
        .btn-premium {
            background: linear-gradient(135deg, #f4edd8 0%, #e8dec9 100%);
            color: #5d1e25;
            box-shadow: 0 10px 30px -5px rgba(244, 237, 216, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px -5px rgba(244, 237, 216, 0.5);
        }
        .premium-border {
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(244, 237, 216, 0.3);
            transform: translateY(-4px);
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen antialiased flex flex-col justify-between">

    {{-- Navbar --}}
    <nav x-data="{ scrolled: {{ Route::is('landing.index') ? 'false' : 'true' }}, open: false }"
         @scroll.window="scrolled = window.scrollY > 50 || {{ Route::is('landing.index') ? 'false' : 'true' }}"
         :class="scrolled ? 'bg-[#6d272e]/95 dark:bg-[#5d1e25]/95 backdrop-blur-md shadow-lg' : 'bg-[#6d272e] border-b border-white/10'"
         class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('landing.index') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl overflow-hidden bg-white/10 flex items-center justify-center border border-white/20">
                    <img src="{{ asset('assets/images/logo-tubaba.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                </div>
                <div>
                    <h1 class="text-sm font-black uppercase tracking-wider text-white">DPMPTSP</h1>
                    <p class="text-[9px] text-rose-200/70 font-bold uppercase tracking-widest">Kab. Tulang Bawang Barat</p>
                </div>
            </a>

            {{-- Desktop nav --}}
            <div class="hidden lg:flex items-center gap-8">
                @foreach ($menus as $menu)
                    @if ($menu->children->isNotEmpty())
                        @if ($menu->title === 'Pelayanan')
                            {{-- Mega Menu for Pelayanan --}}
                            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                                <button class="font-semibold text-sm text-white/80 hover:text-white transition-colors duration-200 flex items-center gap-1 py-4">
                                    {{ $menu->title }}
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-1"
                                     class="absolute left-1/2 -translate-x-1/2 mt-0 w-[800px] bg-gradient-to-br from-[#7b323b] to-[#5d1e25] rounded-2xl shadow-2xl border border-white/10 p-6 z-50 grid grid-cols-2 gap-4">
                                    @foreach ($menu->children as $child)
                                        <a href="{{ $child->url }}" target="{{ $child->target }}"
                                           class="group/item flex items-start gap-3 p-3 rounded-xl hover:bg-white/10 transition-colors">
                                            @if ($child->icon)
                                                <iconify-icon icon="{{ $child->icon }}" class="text-xl text-[#f4edd8] mt-1"></iconify-icon>
                                            @else
                                                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-[#f4edd8] flex-shrink-0">
                                                    <span class="text-xs font-bold">{{ substr($child->title, 0, 2) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-semibold text-white group-hover/item:text-[#f4edd8] transition-colors">
                                                    {{ $child->title }}
                                                </div>
                                                @if ($child->description)
                                                    <p class="text-xs text-rose-200/70 mt-1 leading-relaxed">
                                                        {{ $child->description }}
                                                    </p>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            {{-- Standard Dropdown --}}
                            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                                <button class="font-semibold text-sm text-white/80 hover:text-white transition-colors duration-200 flex items-center gap-1 py-4">
                                    {{ $menu->title }}
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-1"
                                     class="absolute left-0 mt-0 w-56 bg-gradient-to-b from-[#7b323b] to-[#5d1e25] rounded-xl shadow-xl border border-white/10 py-2 z-50">
                                    @foreach ($menu->children as $child)
                                        <a href="{{ $child->url }}" target="{{ $child->target }}"
                                           class="block px-4 py-2.5 text-sm text-rose-100 hover:bg-white/10 hover:text-[#f4edd8] transition-colors">
                                            {{ $child->title }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        {{-- Normal Link --}}
                        <a href="{{ $menu->url }}" target="{{ $menu->target }}"
                           class="font-semibold text-sm text-white/80 hover:text-white transition-colors duration-200">
                            {{ $menu->title }}
                        </a>
                    @endif
                @endforeach
                <a href="{{ route('login') }}"
                   class="text-xs font-black uppercase tracking-wider text-[#6d272e] bg-[#f4edd8] px-5 py-2.5 rounded-full shadow-sm hover:scale-105 transition-transform duration-200">
                    Masuk
                </a>
            </div>

            {{-- Mobile toggle --}}
            <button @click="open = !open" class="lg:hidden p-2 rounded-xl text-white">
                <svg x-show="!open" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div x-show="open"
             x-transition:enter="transition duration-200 ease-out"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="lg:hidden bg-[#6d272e] border-t border-white/10 px-6 py-4 space-y-3 max-h-[80vh] overflow-y-auto">
            @foreach ($menus as $menu)
                @if ($menu->children->isNotEmpty())
                    <div x-data="{ subOpen: false }" class="py-1">
                        <button @click="subOpen = !subOpen"
                                class="w-full flex items-center justify-between py-2 font-semibold text-rose-100 hover:text-white transition-colors">
                             <span>{{ $menu->title }}</span>
                            <svg class="w-4 h-4 transition-transform" :class="subOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="subOpen" class="pl-4 border-l border-white/10 space-y-2 mt-1">
                            @foreach ($menu->children as $child)
                                <a href="{{ $child->url }}" target="{{ $child->target }}" @click="open = false"
                                   class="block py-2 text-sm text-rose-200/70 hover:text-[#f4edd8] transition-colors">
                                    {{ $child->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $menu->url }}" target="{{ $menu->target }}" @click="open = false"
                       class="block py-2 font-semibold text-rose-100 hover:text-white transition-colors">
                        {{ $menu->title }}
                    </a>
                @endif
            @endforeach
            <a href="{{ route('login') }}" class="block text-center font-bold py-3 rounded-xl bg-[#f4edd8] text-[#5d1e25] transition-colors">
                Masuk
            </a>
        </div>
    </nav>

    @yield('content')

    @include('components.landing.footer', ['settings' => $settings, 'menus' => $menus])

    @stack('scripts')
</body>
</html>
