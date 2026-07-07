@extends('layouts.public', ['seo' => $page->seo ?? null])

@section('content')
<main class="flex-grow pt-32 pb-24">
    <div class="container mx-auto px-6">
        <div class="max-w-6xl mx-auto">
            <nav class="flex text-xs text-[#7b323b] font-bold uppercase tracking-wider mb-4" aria-label="Breadcrumb">
                <a href="{{ route('landing.index') }}" class="hover:underline">Beranda</a>
                <span class="mx-2 text-[#3c2f2f]/40">/</span>
                <span class="text-[#3c2f2f]/60">{{ ucfirst(request()->segment(1)) }}</span>
            </nav>

            <h1 class="text-3xl md:text-5xl font-black text-[#5d1e25] mb-8 leading-tight">
                {{ $page->title }}
            </h1>

            <div class="glass-card p-8 md:p-12 border border-white/40 shadow-xl">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @forelse ($team as $member)
                        @php $socials = $member->social_links ?? []; @endphp
                        <div class="group text-center">
                            <div class="relative mx-auto w-32 h-32 mb-4">
                                <div class="absolute inset-0 bg-[#5d1e25] rounded-full scale-110 group-hover:scale-125 transition-transform duration-300 opacity-10"></div>
                                @if ($member->image)
                                    <img src="{{ asset('storage/' . $member->image) }}"
                                         alt="{{ $member->name }}"
                                         class="w-32 h-32 rounded-full object-cover border-4 border-[#f4edd8] shadow-lg relative z-10"
                                         loading="lazy">
                                @else
                                    <div class="w-32 h-32 rounded-full bg-white/40 backdrop-blur-md flex items-center justify-center relative z-10 border-4 border-[#f4edd8] shadow-lg">
                                        <iconify-icon icon="lucide:user" class="text-4xl text-[#5d1e25]/40"></iconify-icon>
                                    </div>
                                @endif
                            </div>
                            <h4 class="font-bold text-[#5d1e25]">{{ $member->name }}</h4>
                            <p class="text-xs text-[#7b323b] font-bold uppercase tracking-wider mb-3">{{ $member->position }}</p>

                            @if (!empty($socials))
                                <div class="flex justify-center gap-2">
                                    @foreach ($socials as $platform => $url)
                                        @if ($url)
                                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                               class="w-8 h-8 bg-[#f4edd8] rounded-full flex items-center justify-center text-[#5d1e25] hover:bg-[#5d1e25] hover:text-[#f4edd8] transition-all duration-200 shadow-sm">
                                                <iconify-icon icon="mdi:{{ $platform }}" class="text-sm"></iconify-icon>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center">
                            <p class="text-sm text-slate-400 font-bold uppercase tracking-widest">Data Struktur Organisasi belum tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
