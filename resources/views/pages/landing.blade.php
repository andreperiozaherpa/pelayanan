@extends('layouts.public')

@section('content')
<main>
    @include('components.landing.hero', ['hero' => $hero])
    @include('components.landing.statistics', ['statistics' => $statistics])
    @include('components.landing.about', ['about' => $about, 'vision' => $vision, 'mission' => $mission])
    @include('components.landing.services', ['services' => $services])
    @include('components.landing.why-choose-us', ['whyChooseUs' => $whyChooseUs])
    @include('components.landing.portfolio', ['portfolios' => $portfolios])
    @include('components.landing.team', ['team' => $team])
    @include('components.landing.testimonials', ['testimonials' => $testimonials])
    @include('components.landing.blog', ['articles' => $articles])
    @include('components.landing.faq', ['faqs' => $faqs])
    @include('components.landing.cta', ['cta' => $cta])
</main>
@endsection
