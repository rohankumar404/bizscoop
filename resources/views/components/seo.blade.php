@props([
    'title'       => null,
    'description' => null,
    'ogImage'     => null,
    'canonical'   => null,
    'robots'      => null,
    'ogType'      => 'website',
])

@php
    /** @var \App\Services\SeoService $seoSvc */
    $seoSvc       = app(\App\Services\SeoService::class);
    $siteName     = setting('site_name', config('app.name', 'BizScoopMENA'));

    // ── Title & Description ────────────────────────────────────────
    $displayTitle       = $title       ?? setting('default_meta_title', $siteName);
    $displayDescription = $description ?? setting('default_meta_description', '');

    // ── Canonical ─────────────────────────────────────────────────
    // Priority: prop passed from controller → auto-generated from current path
    $displayCanonical = $canonical ?? $seoSvc->currentCanonical();

    // ── Robots ────────────────────────────────────────────────────
    $displayRobots = $robots ?? $seoSvc->defaultRobots('default');

    // ── OG Image ─────────────────────────────────────────────────
    $displayOgImage = $ogImage ?: (setting('site_logo') ? Storage::url(setting('site_logo')) : '');
@endphp

<title>{{ $displayTitle }}</title>
<meta name="description" content="{{ $displayDescription }}">
<meta name="robots" content="{{ $displayRobots }}">

<!-- Canonical -->
@if(setting('seo_canonical_tags_enabled', '1'))
<link rel="canonical" href="{{ $displayCanonical }}">
@endif

<!-- Open Graph / Facebook -->
<meta property="og:type"        content="{{ $ogType }}">
<meta property="og:url"         content="{{ $displayCanonical }}">
<meta property="og:title"       content="{{ $displayTitle }}">
<meta property="og:description" content="{{ $displayDescription }}">
<meta property="og:image"       content="{{ $displayOgImage }}">
<meta property="og:site_name"   content="{{ $siteName }}">
@if(!$ogImage && setting('site_logo_alt'))
<meta property="og:image:alt"   content="{{ setting('site_logo_alt') }}">
@endif

<!-- Twitter -->
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:url"         content="{{ $displayCanonical }}">
<meta name="twitter:title"       content="{{ $displayTitle }}">
<meta name="twitter:description" content="{{ $displayDescription }}">
<meta name="twitter:image"       content="{{ $displayOgImage }}">
@if(setting('social_twitter'))
<meta name="twitter:site" content="{{ setting('social_twitter') }}">
@endif
