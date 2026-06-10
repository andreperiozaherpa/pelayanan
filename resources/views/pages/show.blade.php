@extends('layouts.public', ['seo' => $page->seo])

@section('content')
<main class="flex-grow pt-32 pb-24">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <nav class="flex text-xs text-[#7b323b] font-bold uppercase tracking-wider mb-4" aria-label="Breadcrumb">
                <a href="{{ route('landing.index') }}" class="hover:underline">Beranda</a>
                <span class="mx-2 text-[#3c2f2f]/40">/</span>
                <span class="text-[#3c2f2f]/60">{{ ucfirst(request()->segment(1)) }}</span>
            </nav>

            <h1 class="text-3xl md:text-5xl font-black text-[#5d1e25] mb-8 leading-tight">
                {{ $page->title }}
            </h1>

            <div class="glass-card p-8 md:p-12 border border-white/40 shadow-xl">
                <div class="prose prose-stone max-w-none text-[#3c2f2f]/85 leading-relaxed text-sm md:text-base space-y-6">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
