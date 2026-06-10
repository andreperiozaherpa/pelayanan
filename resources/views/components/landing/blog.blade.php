{{-- Latest Blog Posts Component --}}
{{-- $articles: Collection<CmsArticle>|null --}}
@if ($articles && $articles->isNotEmpty())
<section id="blog" class="py-24 bg-transparent">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-4">
            <div>
                <p class="text-[#7b323b] font-bold text-xs uppercase tracking-widest mb-3">Kabar Terkini</p>
                <h2 class="text-3xl md:text-4xl font-black text-[#5d1e25]">Berita & Pengumuman</h2>
            </div>
            <a href="{{ route('landing.index') }}#blog"
               class="inline-flex items-center gap-2 text-[#7b323b] hover:text-[#5d1e25] font-bold hover:gap-4 transition-all duration-300 text-sm flex-shrink-0">
                Lihat Semua Artikel
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($articles as $article)
                <article class="group bg-white/40 backdrop-blur-md rounded-3xl overflow-hidden border border-white/40 shadow-sm hover:shadow-xl hover:bg-white/60 transition-all duration-300 hover:-translate-y-1">
                    @if ($article->featured_image)
                        <div class="aspect-video overflow-hidden">
                            <img src="{{ asset('storage/' . $article->featured_image) }}"
                                 alt="{{ $article->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                 loading="lazy">
                        </div>
                    @else
                        <div class="aspect-video bg-gradient-to-br from-[#7b323b]/20 to-[#5d1e25]/20 flex items-center justify-center">
                            <iconify-icon icon="lucide:file-text" class="text-5xl text-[#5d1e25]/30"></iconify-icon>
                        </div>
                    @endif

                    <div class="p-6">
                        @if ($article->category)
                            <span class="inline-block text-xs font-bold text-[#5d1e25] bg-[#f4edd8] px-3 py-1 rounded-full uppercase tracking-wider mb-3">
                                {{ $article->category->name }}
                            </span>
                        @endif

                        <h3 class="font-bold text-[#5d1e25] mb-2 leading-snug line-clamp-2 group-hover:text-[#7b323b] transition-colors">
                            {{ $article->title }}
                        </h3>

                        @if ($article->excerpt)
                            <p class="text-sm text-[#3c2f2f]/70 line-clamp-2 mb-4">{{ $article->excerpt }}</p>
                        @endif

                        <div class="flex items-center justify-between text-xs text-[#3c2f2f]/50">
                            <time datetime="{{ optional($article->published_at)->toISOString() }}">
                                {{ optional($article->published_at)->translatedFormat('d M Y') ?? '-' }}
                            </time>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
