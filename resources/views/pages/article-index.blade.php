@extends('layouts.public', ['seo' => $page->seo ?? null])

@section('content')
<main class="flex-grow pt-32 pb-24">
    <div class="container mx-auto px-6">
        <div class="max-w-6xl mx-auto">
            <nav class="flex text-xs text-[#7b323b] font-bold uppercase tracking-wider mb-4" aria-label="Breadcrumb">
                <a href="{{ route('landing.index') }}" class="hover:underline">Beranda</a>
                <span class="mx-2 text-[#3c2f2f]/40">/</span>
                <span class="text-[#3c2f2f]/60">{{ ucfirst(request()->segment(1)) }}</span>
                <span class="mx-2 text-[#3c2f2f]/40">/</span>
                <span class="text-[#3c2f2f]/60">{{ $page->title }}</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl md:text-5xl font-black text-[#5d1e25] leading-tight">
                        {{ $page->title }}
                    </h1>
                    <p class="text-xs text-[#3c2f2f]/60 mt-1 font-bold uppercase tracking-wider">
                        Kumpulan artikel, berita, dan pengumuman resmi.
                    </p>
                </div>
                
                <!-- Search bar -->
                <form action="{{ url()->current() }}" method="GET" class="w-full md:w-80">
                    <div class="relative">
                        <input type="text" 
                               name="q" 
                               value="{{ request('q') }}"
                               placeholder="Cari artikel..." 
                               class="w-full pl-4 pr-10 py-2.5 bg-white/40 backdrop-blur-md border border-white/40 rounded-2xl text-sm text-[#3c2f2f] placeholder-[#3c2f2f]/40 focus:outline-none focus:ring-2 focus:ring-[#7b323b]/20 focus:border-[#7b323b] shadow-sm">
                        <button type="submit" class="absolute right-3 top-3 text-[#3c2f2f]/50 hover:text-[#7b323b] transition-colors">
                            <iconify-icon icon="lucide:search" class="text-base"></iconify-icon>
                        </button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($articles as $article)
                    <article class="group bg-white/40 backdrop-blur-md rounded-3xl overflow-hidden border border-white/40 shadow-sm hover:shadow-xl hover:bg-white/60 transition-all duration-300 hover:-translate-y-1 flex flex-col h-full">
                        @if ($article->featured_image)
                            <div class="aspect-video overflow-hidden relative">
                                @php
                                    $imageUrl = $article->featured_image;
                                    if (!str_starts_with($imageUrl, 'http://') && !str_starts_with($imageUrl, 'https://')) {
                                        $imageUrl = str_starts_with($imageUrl, 'storage/') ? asset($imageUrl) : asset('storage/' . $imageUrl);
                                    }
                                @endphp
                                <img src="{{ $imageUrl }}"
                                     alt="{{ $article->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     loading="lazy">
                            </div>
                        @else
                            <div class="aspect-video bg-gradient-to-br from-[#7b323b]/20 to-[#5d1e25]/20 flex items-center justify-center relative">
                                <iconify-icon icon="lucide:file-text" class="text-5xl text-[#5d1e25]/30"></iconify-icon>
                            </div>
                        @endif

                        <div class="p-6 flex flex-col flex-grow">
                            @if ($article->category)
                                <span class="inline-block self-start text-[10px] font-black text-[#5d1e25] bg-[#f4edd8] px-3 py-1 rounded-full uppercase tracking-wider mb-3">
                                    {{ $article->category->name }}
                                </span>
                            @endif

                            <h3 class="font-bold text-[#5d1e25] mb-2 leading-snug line-clamp-2 group-hover:text-[#7b323b] transition-colors">
                                <a href="{{ route('landing.page', ['section' => request()->segment(1) ?: 'informasi', 'slug' => $article->slug]) }}">
                                    {{ $article->title }}
                                </a>
                            </h3>

                            @if ($article->excerpt)
                                <p class="text-xs text-[#3c2f2f]/70 line-clamp-3 mb-4 leading-relaxed flex-grow">{{ $article->excerpt }}</p>
                            @else
                                <p class="text-xs text-[#3c2f2f]/70 line-clamp-3 mb-4 leading-relaxed flex-grow">{{ Str::limit(strip_tags($article->content), 120) }}</p>
                            @endif

                            <div class="flex items-center justify-between text-[10px] text-[#3c2f2f]/50 border-t border-black/[0.03] pt-4 mt-auto">
                                <span class="font-bold uppercase tracking-wider">
                                    Oleh: {{ $article->author ? $article->author->name : 'Admin' }}
                                </span>
                                <time datetime="{{ optional($article->published_at)->toISOString() }}" class="font-semibold">
                                    {{ optional($article->published_at)->translatedFormat('d M Y') ?? '-' }}
                                </time>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full py-24 text-center">
                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-white/40 border border-white/20 shadow-sm mb-4">
                            <iconify-icon icon="lucide:file-question" class="text-3xl text-[#5d1e25]/30"></iconify-icon>
                        </div>
                        <h3 class="text-sm font-black text-[#5d1e25] uppercase tracking-widest">Artikel Tidak Ditemukan</h3>
                        <p class="text-xs text-[#3c2f2f]/60 mt-1 uppercase font-bold tracking-widest">Maaf, tidak ada artikel yang sesuai dengan kriteria pencarian Anda.</p>
                        @if (request('q'))
                            <a href="{{ url()->current() }}" class="inline-block mt-4 px-4 py-2 bg-[#7b323b] text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-[#5d1e25] transition-colors">
                                Reset Pencarian
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            @if ($articles->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
