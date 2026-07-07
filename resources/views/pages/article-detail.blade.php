@extends('layouts.public', ['seo' => $article->seo ?? null])

@section('content')
<style>
    .article-content p {
        margin-top: 0.75rem !important;
        margin-bottom: 0.75rem !important;
        line-height: 1.7 !important;
    }
</style>

<main class="flex-grow pt-32 pb-24">
    <div class="container mx-auto px-6">
        <div class="max-w-6xl mx-auto">
            <nav class="flex text-xs text-[#7b323b] font-bold uppercase tracking-wider mb-4" aria-label="Breadcrumb">
                <a href="{{ route('landing.index') }}" class="hover:underline">Beranda</a>
                <span class="mx-2 text-[#3c2f2f]/40">/</span>
                <a href="{{ route('landing.page', ['section' => request()->segment(1) ?: 'informasi', 'slug' => $article->category ? $article->category->slug : 'berita']) }}" class="hover:underline">
                    {{ $article->category ? $article->category->name : 'Artikel' }}
                </a>
                <span class="mx-2 text-[#3c2f2f]/40">/</span>
                <span class="text-[#3c2f2f]/60 line-clamp-1">{{ $article->title }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Main Content Left (8 columns) -->
                <div class="lg:col-span-8">
                    <article>
                        <header class="mb-6">
                            @if ($article->category)
                                <span class="inline-block text-[10px] font-black text-[#5d1e25] bg-[#f4edd8] px-3 py-1 rounded-full uppercase tracking-wider mb-3">
                                    {{ $article->category->name }}
                                </span>
                            @endif

                            <h1 class="text-2xl md:text-4xl font-black text-[#5d1e25] leading-tight mb-4">
                                {{ $article->title }}
                            </h1>

                            <div class="flex flex-wrap items-center gap-4 text-xs text-[#3c2f2f]/60 font-bold uppercase tracking-wider">
                                <div class="flex items-center gap-1.5">
                                    <iconify-icon icon="lucide:user" class="text-sm"></iconify-icon>
                                    <span>Oleh: {{ $article->author ? $article->author->name : 'Admin' }}</span>
                                </div>
                                <span class="text-[#3c2f2f]/20">•</span>
                                <div class="flex items-center gap-1.5">
                                    <iconify-icon icon="lucide:calendar" class="text-sm"></iconify-icon>
                                    <span>{{ optional($article->published_at)->translatedFormat('d M Y H:i') ?? '-' }}</span>
                                </div>
                            </div>
                        </header>

                        @if ($article->featured_image)
                            <div class="glass-card p-2 border border-white/40 shadow-lg rounded-3xl overflow-hidden mb-6">
                                <div class="aspect-video w-full rounded-2xl overflow-hidden">
                                    @php
                                        $imageUrl = $article->featured_image;
                                        if (!str_starts_with($imageUrl, 'http://') && !str_starts_with($imageUrl, 'https://')) {
                                            $imageUrl = str_starts_with($imageUrl, 'storage/') ? asset($imageUrl) : asset('storage/' . $imageUrl);
                                        }
                                    @endphp
                                    <img src="{{ $imageUrl }}" 
                                         class="w-full h-full object-cover" 
                                         alt="{{ $article->title }}">
                                </div>
                            </div>
                        @endif

                        <div class="glass-card p-6 md:p-10 border border-white/40 shadow-lg rounded-3xl">
                            <div class="article-content prose prose-stone max-w-none text-[#3c2f2f]/85 leading-relaxed text-sm md:text-base space-y-4">
                                {!! $article->content !!}
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Sidebar Right (4 columns) -->
                <div class="lg:col-span-4 space-y-8">
                    <!-- Publisher Info Card -->
                    <div class="glass-card p-6 border border-white/40 shadow-md rounded-3xl">
                        <h3 class="text-xs font-black text-[#5d1e25] uppercase tracking-wider mb-4 pb-2 border-b border-black/[0.05]">Informasi Penerbit</h3>
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-[#5d1e25]/10 flex items-center justify-center text-[#5d1e25]">
                                <iconify-icon icon="lucide:user" class="text-xl"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-[#5d1e25]">{{ $article->author ? $article->author->name : 'Administrator' }}</p>
                                <p class="text-[10px] text-[#3c2f2f]/60 font-bold uppercase tracking-wider">Kontributor Resmi</p>
                            </div>
                        </div>
                    </div>

                    <!-- Related Articles Sidebar -->
                    @if ($relatedArticles && $relatedArticles->isNotEmpty())
                        <div class="glass-card p-6 border border-white/40 shadow-md rounded-3xl">
                            <h3 class="text-xs font-black text-[#5d1e25] uppercase tracking-wider mb-4 pb-2 border-b border-black/[0.05]">Artikel Lainnya</h3>
                            <div class="space-y-4">
                                @foreach ($relatedArticles as $related)
                                    <div class="flex gap-3 items-center group">
                                        @if ($related->featured_image)
                                            <div class="h-14 w-20 rounded-xl overflow-hidden flex-shrink-0 border border-white/40">
                                                @php
                                                    $relImageUrl = $related->featured_image;
                                                    if (!str_starts_with($relImageUrl, 'http://') && !str_starts_with($relImageUrl, 'https://')) {
                                                        $relImageUrl = str_starts_with($relImageUrl, 'storage/') ? asset($relImageUrl) : asset('storage/' . $relImageUrl);
                                                    }
                                                @endphp
                                                <img src="{{ $relImageUrl }}" 
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                                                     alt="{{ $related->title }}">
                                            </div>
                                        @else
                                            <div class="h-14 w-20 rounded-xl bg-gradient-to-br from-[#7b323b]/20 to-[#5d1e25]/20 flex items-center justify-center flex-shrink-0">
                                                <iconify-icon icon="lucide:file-text" class="text-2xl text-[#5d1e25]/30"></iconify-icon>
                                            </div>
                                        @endif
                                        <div class="flex-grow min-w-0">
                                            <h4 class="font-bold text-xs text-[#5d1e25] leading-snug line-clamp-2 group-hover:text-[#7b323b] transition-colors">
                                                <a href="{{ route('landing.page', ['section' => request()->segment(1) ?: 'informasi', 'slug' => $related->slug]) }}">
                                                    {{ $related->title }}
                                                </a>
                                            </h4>
                                            <time datetime="{{ optional($related->published_at)->toISOString() }}" class="text-[9px] text-[#3c2f2f]/50 font-semibold uppercase mt-1 block">
                                                {{ optional($related->published_at)->translatedFormat('d M Y') ?? '-' }}
                                            </time>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
