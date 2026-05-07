@props(['title' => null, 'description' => null])

@php
    $siteName = config('app.name', 'BizScoop');
    $displayTitle = $title ? "$title | $siteName" : $siteName;
    $displayDescription = $description ?? 'Professional news platform delivering high-integrity journalism.';
@endphp

<title>{{ $displayTitle }}</title>
<meta name="description" content="{{ $displayDescription }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $displayTitle }}">
<meta property="og:description" content="{{ $displayDescription }}">
<meta property="og:site_name" content="{{ $siteName }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:title" content="{{ $displayTitle }}">
<meta property="twitter:description" content="{{ $displayDescription }}">

<link rel="canonical" href="{{ url()->current() }}">
