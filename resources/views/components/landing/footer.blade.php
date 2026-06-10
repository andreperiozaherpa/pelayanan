{{-- Footer Component --}}
{{-- $settings: array<string, string|null> --}}
@php
    $siteName  = $settings['site_name'] ?? config('app.name');
    $address   = $settings['contact_address'] ?? $settings['address'] ?? null;
    $phone     = $settings['contact_phone'] ?? $settings['phone'] ?? null;
    $email     = $settings['contact_email'] ?? $settings['email'] ?? null;
    $copyright = $settings['copyright'] ?? '© ' . date('Y') . ' ' . $siteName . '. All rights reserved.';
    $logo      = $settings['site_logo'] ?? $settings['logo'] ?? null;

    $socials = [
        'facebook'  => ['icon' => 'mdi:facebook',  'url' => $settings['social_facebook']  ?? null],
        'instagram' => ['icon' => 'mdi:instagram',  'url' => $settings['social_instagram'] ?? null],
        'twitter'   => ['icon' => 'mdi:twitter',    'url' => $settings['social_twitter']   ?? null],
        'youtube'   => ['icon' => 'mdi:youtube',    'url' => $settings['social_youtube']   ?? null],
    ];
@endphp

<footer id="footer" class="bg-[#5d1e25] text-rose-100/70 pt-16 pb-8 border-t border-white/10">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">

            {{-- Brand --}}
            <div class="lg:col-span-2">
                @if ($logo)
                    <img src="{{ asset('storage/' . $logo) }}" alt="{{ $siteName }}" class="h-10 mb-5" loading="lazy">
                @else
                    <span class="text-2xl font-black text-white">{{ $siteName }}</span>
                @endif
                <p class="text-rose-200/60 leading-relaxed mt-4 max-w-xs">
                    {{ $settings['site_tagline'] ?? '' }}
                </p>

                <div class="flex gap-3 mt-6">
                    @foreach ($socials as $platform => $social)
                        @if ($social['url'])
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer"
                               class="w-10 h-10 bg-[#7b323b] hover:bg-[#f4edd8] hover:text-[#5d1e25] rounded-full flex items-center justify-center transition-colors duration-200 text-white shadow-sm border border-white/5">
                                <iconify-icon icon="{{ $social['icon'] }}" class="text-lg"></iconify-icon>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="text-white font-bold mb-5 uppercase tracking-widest text-xs">Navigasi</h4>
                <ul class="space-y-3">
                    @if (isset($menus) && $menus->isNotEmpty())
                        @foreach ($menus->take(6) as $menu)
                            <li>
                                <a href="{{ $menu->url }}" class="text-rose-200/70 hover:text-white transition-colors duration-200 text-sm">
                                    {{ $menu->title }}
                                </a>
                            </li>
                        @endforeach
                    @else
                        @foreach (['#hero' => 'Beranda', '#about' => 'Tentang Kami', '#services' => 'Layanan', '#portfolio' => 'Portfolio', '#blog' => 'Blog', '#faq' => 'FAQ'] as $href => $label)
                            <li>
                                <a href="{{ $href }}" class="text-rose-200/70 hover:text-white transition-colors duration-200 text-sm">
                                    {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="text-white font-bold mb-5 uppercase tracking-widest text-xs">Kontak</h4>
                <ul class="space-y-4">
                    @if ($address)
                        <li class="flex items-start gap-3">
                            <iconify-icon icon="lucide:map-pin" class="text-[#f4edd8] mt-0.5 flex-shrink-0"></iconify-icon>
                            <span class="text-sm text-rose-200/70 leading-relaxed">{{ $address }}</span>
                        </li>
                    @endif
                    @if ($phone)
                        <li class="flex items-center gap-3">
                            <iconify-icon icon="lucide:phone" class="text-[#f4edd8] flex-shrink-0"></iconify-icon>
                            <a href="tel:{{ $phone }}" class="text-sm text-rose-200/70 hover:text-white transition-colors">{{ $phone }}</a>
                        </li>
                    @endif
                    @if ($email)
                        <li class="flex items-center gap-3">
                            <iconify-icon icon="lucide:mail" class="text-[#f4edd8] flex-shrink-0"></iconify-icon>
                            <a href="mailto:{{ $email }}" class="text-sm text-rose-200/70 hover:text-white transition-colors">{{ $email }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 pt-8 text-center">
            <p class="text-rose-200/30 text-sm">{{ $copyright }}</p>
        </div>
    </div>
</footer>
