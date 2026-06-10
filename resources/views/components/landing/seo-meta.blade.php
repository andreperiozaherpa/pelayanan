{{-- SEO Meta Component --}}
{{-- Props: $settings (array), $seo (optional CmsSeo model) --}}
@php
    $metaTitle       = optional($seo)->meta_title ?? $settings['site_name'] ?? config('app.name');
    $metaDescription = optional($seo)->meta_description ?? $settings['site_description'] ?? '';
    $metaKeywords    = optional($seo)->meta_keywords ?? $settings['site_keywords'] ?? '';
    $ogImage         = optional($seo)->og_image ?? $settings['logo'] ?? '';
    $canonicalUrl    = optional($seo)->canonical_url ?? url('/');
    $siteName        = $settings['site_name'] ?? config('app.name');
    $phone           = $settings['phone'] ?? null;
    $address         = $settings['address'] ?? null;

    $jsonLd = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Organization',
        'name'        => $siteName,
        'url'         => url('/'),
        'description' => $metaDescription,
    ];
    if ($phone) {
        $jsonLd['telephone'] = $phone;
    }
    if ($address) {
        $jsonLd['address'] = ['@type' => 'PostalAddress', 'streetAddress' => $address];
    }
    if ($ogImage) {
        $jsonLd['logo'] = asset('storage/' . $ogImage);
    }
@endphp

<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
@if ($metaKeywords)
    <meta name="keywords" content="{{ $metaKeywords }}">
@endif
<link rel="canonical" href="{{ $canonicalUrl }}">

{{-- Open Graph --}}
<meta property="og:type" content="website">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
@if ($ogImage)
    <meta property="og:image" content="{{ asset('storage/' . $ogImage) }}">
@endif
<meta property="og:site_name" content="{{ $siteName }}">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
@if ($ogImage)
    <meta name="twitter:image" content="{{ asset('storage/' . $ogImage) }}">
@endif

{{-- JSON-LD Structured Data --}}
<script type="application/ld+json">
{!! json_encode($jsonLd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
