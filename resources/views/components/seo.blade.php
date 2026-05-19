@props(['title' => null, 'description' => null, 'ogImage' => null])

@php
    $siteName = config('app.name', 'BizScoop');
    $displayTitle = $title ?? setting('default_meta_title', $siteName);
    $displayDescription = $description ?? setting('default_meta_description', 'Professional news platform delivering high-integrity journalism.');
@endphp

<title>{{ $displayTitle }}</title>
<meta name="description" content="{{ $displayDescription }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $displayTitle }}">
<meta property="og:description" content="{{ $displayDescription }}">
<meta property="og:image" content="{{ $ogImage ?? (setting('site_logo') ? Storage::url(setting('site_logo')) : '') }}">
@if(!$ogImage && setting('site_logo_alt'))
<meta property="og:image:alt" content="{{ setting('site_logo_alt') }}">
@endif
<meta property="og:site_name" content="{{ $siteName }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:title" content="{{ $displayTitle }}">
<meta property="twitter:description" content="{{ $displayDescription }}">

<link rel="canonical" href="{{ url()->current() }}">
