<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @props(['title' => null, 'description' => null, 'ogImage' => null])
    <x-seo :title="$title" :description="$description" :ogImage="$ogImage" />
    <x-schema type="Organization" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Merriweather:wght@400;700;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    {{-- Ticker keyframe — inline guarantee --}}
    <style>
        @keyframes ticker-scroll {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .ticker-track {
            display: inline-block !important;
            white-space: nowrap !important;
            will-change: transform;
            animation: ticker-scroll 38s linear infinite !important;
        }
        .ticker-wrap {
            flex: 1;
            overflow: hidden;
            position: relative;
        }
        body, html, #app, .min-h-screen {
            background-color: #ffffff !important;
            background: #ffffff !important;
        }
        .ticker-track:hover { animation-play-state: paused !important; }

        /* ── Global Loading Spinner ── */
        @keyframes spin { 100% { transform: rotate(360deg); } }
        @keyframes pulse-pop {
            0%, 100% { transform: scale(0.85); opacity: 0.8; }
            50% { transform: scale(1.15); opacity: 1; }
        }
        .loading-spinner {
            width: 52px; height: 52px;
            border: 4px solid rgba(230,0,0,0.1);
            border-top: 4px solid #e60000;
            border-radius: 50%;
            animation: spin 0.8s linear infinite, pulse-pop 0.8s ease-in-out infinite;
            position: relative;
            margin: auto;
        }
        .loading-spinner::after {
            content: '';
            position: absolute;
            inset: 4px;
            border-radius: 50%;
            background: #e60000;
            opacity: 0.15;
            animation: pulse-pop 0.8s ease-in-out infinite;
        }
    </style>
</head>
<body x-data="{ 
    mobileMenuOpen: false, 
    searchOpen: false, 
    siteLoaded: false 
}" x-init="setTimeout(() => { siteLoaded = true }, 700)" style="background: #fff !important;">

    {{-- ════ GLOBAL PRELOADER ════ --}}
    <div x-show="!siteLoaded" 
         x-transition.opacity.duration.400ms
         style="position:fixed;inset:0;background:#fff;z-index:999999;">
        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);">
            <div class="loading-spinner"></div>
        </div>
    </div>

<div id="app" class="flex flex-col min-h-screen" style="background: #fff !important;">

    {{-- ═══════════════════════════════════
         1. TOP BLACK UTILITY BAR
    ═══════════════════════════════════ --}}
    <div style="background:#111;color:#ccc;font-size:10px;font-weight:600;border-bottom:1px solid #2a2a2a;" class="hidden lg:block">
        <div class="wrap flex justify-between items-center" style="padding-top:7px;padding-bottom:7px;">
            {{-- Left: Date / Weather --}}
            <div class="flex items-center gap-5">
                <span style="color:#aaa;">📅 {{ now()->format('l, d F Y') }}</span>
                <span style="color:#aaa;">☁️ New York &nbsp; 21°C</span>
            </div>
            {{-- Right: Links + Social --}}
            <div class="flex items-center gap-5">
                <div class="flex items-center gap-4" style="border-right:1px solid #333;padding-right:16px;margin-right:4px;">
                    <a href="#" style="color:#ccc;" onmouseover="this.style.color='#e60000'" onmouseout="this.style.color='#ccc'">Login</a>
                    <span style="color:#444;">|</span>
                    <a href="#" style="color:#ccc;" onmouseover="this.style.color='#e60000'" onmouseout="this.style.color='#ccc'">Register</a>
                    <span style="color:#444;">|</span>
                    <span style="color:#aaa;cursor:pointer;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#aaa'">🌐 English ▾</span>
                </div>
                <div class="flex items-center gap-3" style="color:#888;">
                    <a href="#" style="color:#888;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#888'" title="Facebook">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                    </a>
                    <a href="#" style="color:#888;" onmouseover="this.style.color='#38bdf8'" onmouseout="this.style.color='#888'" title="Twitter">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg>
                    </a>
                    <a href="#" style="color:#888;" onmouseover="this.style.color='#e60000'" onmouseout="this.style.color='#888'" title="YouTube">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 00-1.95 1.96A29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="#111"/></svg>
                    </a>
                    <a href="#" style="color:#888;" onmouseover="this.style.color='#f97316'" onmouseout="this.style.color='#888'" title="RSS">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M4 11a9 9 0 019 9"/><path d="M4 4a16 16 0 0116 16"/><circle cx="5" cy="19" r="1"/></svg>
                    </a>
                    <a href="#" style="color:#888;" onmouseover="this.style.color='#a78bfa'" onmouseout="this.style.color='#888'" title="Instagram">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="0.5" fill="currentColor"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════
         2. LOGO + AD BAR
    ═══════════════════════════════════ --}}
    <div style="background:#fff;border-bottom:1px solid #e0e0e0;">
        <div class="wrap flex justify-between items-center" style="padding-top:14px;padding-bottom:14px;">
            {{-- Logo --}}
            <a href="{{ route('frontend.home') }}">
                @if(setting('site_logo'))
                    <img src="{{ Storage::url(setting('site_logo')) }}" style="height:60px;width:auto;object-fit:contain;">
                @else
                    <div style="line-height:1;">
                        <span style="font-family:'Merriweather',Georgia,serif;font-size:38px;font-weight:900;color:#e60000;font-style:italic;letter-spacing:-2px;">BIZ</span><span style="font-family:'Merriweather',Georgia,serif;font-size:38px;font-weight:900;color:#111;font-style:italic;letter-spacing:-2px;">SCOOP</span>
                        <div style="font-size:8px;font-weight:700;text-transform:uppercase;letter-spacing:0.25em;color:#999;margin-top:2px;">High Integrity Business Journalism</div>
                    </div>
                @endif
            </a>
            {{-- 728×90 Ad --}}
            <div class="hidden lg:flex ad-box" style="width:728px;height:90px;font-size:10px;">
                ADVERTISEMENT — 728 × 90
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════
         3. RED NAVIGATION BAR
    ═══════════════════════════════════ --}}
    <nav style="background:#e60000;position:sticky;top:0;z-index:100;box-shadow:0 2px 8px rgba(0,0,0,0.3);">
        <div class="wrap flex justify-between items-stretch">
            {{-- Menu items --}}
            <div class="flex items-stretch overflow-x-auto" style="scrollbar-width:none;">
                @php $homeActive = request()->routeIs('frontend.home'); @endphp
                <a href="{{ route('frontend.home') }}"
                   style="padding:11px 14px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.06em;color:#fff;white-space:nowrap;border-right:1px solid rgba(255,255,255,0.15);display:flex;align-items:center;background: {{ $homeActive ? '#0000001c' : 'transparent' }};"
                   onmouseover="this.style.background='rgba(0,0,0,0.2)'" 
                   onmouseout="this.style.background='{{ $homeActive ? '#0000001c' : 'transparent' }}'">
                    Home
                </a>
                @foreach($headerCategories as $cat)
                    @php $catActive = request()->fullUrl() == route('frontend.category.show', $cat->slug); @endphp
                    <a href="{{ route('frontend.category.show', $cat->slug) }}"
                       style="padding:11px 13px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:rgba(255,255,255,0.92);white-space:nowrap;border-right:1px solid rgba(255,255,255,0.12);display:flex;align-items:center;gap:3px;background: {{ $catActive ? '#0000001c' : 'transparent' }};"
                       onmouseover="this.style.background='rgba(0,0,0,0.2)'" 
                       onmouseout="this.style.background='{{ $catActive ? '#0000001c' : 'transparent' }}'">
                        {{ $cat->getTranslation('name', app()->getLocale()) }}
                        @if($cat->children->count() > 0)
                            <svg width="8" height="8" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" style="opacity:0.6;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        @endif
                    </a>
                @endforeach
            </div>
            {{-- Search --}}
            <button @click="searchOpen = true"
                    style="padding:0 16px;color:#fff;background:rgba(0,0,0,0.25);border-left:1px solid rgba(255,255,255,0.15);flex-shrink:0;"
                    onmouseover="this.style.background='rgba(0,0,0,0.4)'" onmouseout="this.style.background='rgba(0,0,0,0.25)'">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
            </button>
        </div>
    </nav>

    {{-- ═══════════════════════════════════
         4. TRENDING TABS SUB-NAV
    ═══════════════════════════════════ --}}
    <div style="background:#fff;border-bottom:2px solid #e0e0e0;" class="hidden lg:block">
        <div class="wrap flex justify-center">
            @php
                $tabs = [
                    ['icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.921-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z', 'label' => 'Featured News'],
                    ['icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'label' => 'Most Popular'],
                    ['icon' => 'M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.657 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z', 'label' => 'Hot News'],
                    ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'label' => 'Trending News'],
                    ['icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z', 'label' => 'Most Watched'],
                ];
            @endphp
            @foreach($tabs as $tab)
                <a href="#" style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:10px 30px;font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;color:#777;border-right:1px solid #f0f0f0;gap:5px;"
                   onmouseover="this.style.color='#e60000'" onmouseout="this.style.color='#777'">
                    <svg style="width:16px;height:16px;color:#ccc;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $tab['icon'] }}"/>
                    </svg>
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- ═══════════════════════════════════
         5. BREAKING NEWS TICKER
    ═══════════════════════════════════ --}}
    <div style="background:#f5f5f5;border-bottom:1px solid #e0e0e0;padding:6px 0;">
        <div class="wrap" style="display:flex;align-items:center;gap:0;">
            {{-- Red Label --}}
            <div style="background:#e60000;color:#fff;font-size:9px;font-weight:900;text-transform:uppercase;letter-spacing:0.15em;padding:6px 14px;white-space:nowrap;flex-shrink:0;line-height:1.4;">
                📡 News Updates
            </div>
            {{-- Divider arrow --}}
            <div style="width:0;height:0;border-top:14px solid transparent;border-bottom:14px solid transparent;border-left:10px solid #e60000;flex-shrink:0;"></div>
            {{-- Scrolling area --}}
            <div class="ticker-wrap" style="flex:1;overflow:hidden;position:relative;">
                <div class="ticker-track" style="display:inline-block;white-space:nowrap;will-change:transform;animation:ticker-scroll 38s linear infinite;padding-left:20px;">
                    @foreach($breakingNews as $news)
                        <span style="display:inline-block;">
                            <a href="{{ route('frontend.article.show', $news->slug) }}"
                               style="font-size:11px;font-weight:600;color:#333;margin-right:50px;display:inline-block;"
                               onmouseover="this.style.color='#e60000'" onmouseout="this.style.color='#333'">
                                <span style="color:#e60000;margin-right:5px;font-weight:900;">●</span>{{ $news->translate()->title }}
                            </a>
                        </span>
                    @endforeach
                    {{-- Duplicate for seamless loop --}}
                    @foreach($breakingNews as $news)
                        <span style="display:inline-block;">
                            <a href="{{ route('frontend.article.show', $news->slug) }}"
                               style="font-size:11px;font-weight:600;color:#333;margin-right:50px;display:inline-block;"
                               onmouseover="this.style.color='#e60000'" onmouseout="this.style.color='#333'">
                                <span style="color:#e60000;margin-right:5px;font-weight:900;">●</span>{{ $news->translate()->title }}
                            </a>
                        </span>
                    @endforeach
                </div>
            </div>
            {{-- Timestamp --}}
            <div style="background:#333;color:#aaa;font-size:8px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;padding:6px 12px;white-space:nowrap;flex-shrink:0;border-left:1px solid #444;">
                {{ now()->format('H:i') }} UTC
            </div>
        </div>
    </div>

    {{-- Floating Social Bar --}}
    <div style="position:fixed;right:0;top:50%;transform:translateY(-50%);z-index:200;display:flex;flex-direction:column;" class="hidden xl:flex">
        <a href="#" style="width:36px;height:36px;background:#3b5998;color:#fff;font-size:11px;font-weight:900;display:flex;align-items:center;justify-content:center;border-bottom:1px solid rgba(255,255,255,0.2);">f</a>
        <a href="#" style="width:36px;height:36px;background:#1da1f2;color:#fff;font-size:11px;font-weight:900;display:flex;align-items:center;justify-content:center;border-bottom:1px solid rgba(255,255,255,0.2);">t</a>
        <a href="#" style="width:36px;height:36px;background:#dd4b39;color:#fff;font-size:10px;font-weight:900;display:flex;align-items:center;justify-content:center;border-bottom:1px solid rgba(255,255,255,0.2);">g+</a>
        <a href="#" style="width:36px;height:36px;background:#ff6600;color:#fff;font-size:9px;font-weight:900;display:flex;align-items:center;justify-content:center;border-bottom:1px solid rgba(255,255,255,0.2);">rss</a>
        <a href="#" style="width:36px;height:36px;background:#00aabb;color:#fff;font-size:9px;font-weight:900;display:flex;align-items:center;justify-content:center;border-bottom:1px solid rgba(255,255,255,0.2);">vm</a>
        <a href="#" style="width:36px;height:36px;background:#ff0000;color:#fff;font-size:12px;font-weight:900;display:flex;align-items:center;justify-content:center;">▶</a>
    </div>

    {{-- ═══════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════ --}}
    <main style="flex:1;">
        {{ $slot }}
    </main>

    {{-- ═══════════════════════════════════
         FOOTER
    ═══════════════════════════════════ --}}
    <footer style="background:#1a1a1a;color:#fff;margin-top:20px;">
        {{-- Footer top bar --}}
        <div style="background:#111;border-bottom:3px solid #e60000;padding:10px 0;">
            <div class="wrap flex justify-between items-center">
                <span style="font-family:'Merriweather',serif;font-size:20px;font-weight:900;color:#e60000;font-style:italic;">BizScoop</span>
                <span style="font-size:9px;color:#666;text-transform:uppercase;letter-spacing:0.2em;">High Integrity Business Journalism</span>
            </div>
        </div>
        {{-- Footer columns --}}
        <div class="wrap" style="padding-top:28px;padding-bottom:28px;">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
                {{-- Brand col --}}
                <div class="col-span-2 md:col-span-1 footer-col">
                    <h4>About</h4>
                    <p style="font-size:11px;color:#888;line-height:1.7;margin-bottom:14px;">
                        {{ setting('default_meta_description', 'BizScoop delivers high-integrity business journalism for professionals across the GCC and MENA region.') }}
                    </p>
                    <div style="display:flex;gap:6px;flex-wrap:wrap;">
                        @foreach([['#3b5998','f'],['#1da1f2','t'],['#dd4b39','g+'],['#ff6600','rss'],['#ff0000','▶']] as $s)
                            <a href="#" style="width:28px;height:28px;background:{{$s[0]}};color:#fff;font-size:9px;font-weight:900;display:flex;align-items:center;justify-content:center;">{{$s[1]}}</a>
                        @endforeach
                    </div>
                </div>
                {{-- Sections --}}
                <div class="footer-col">
                    <h4>Sections</h4>
                    <ul>
                        @foreach($headerCategories->take(6) as $cat)
                            <li><a href="{{ route('frontend.category.show', $cat->slug) }}">{{ $cat->getTranslation('name', 'en') }}</a></li>
                        @endforeach
                    </ul>
                </div>
                {{-- Company --}}
                <div class="footer-col">
                    <h4>Company</h4>
                    <ul>
                        @foreach(['About BizScoop','Editorial Standards','Advertise With Us','Careers','Contact Us','Privacy Policy'] as $l)
                            <li><a href="#">{{ $l }}</a></li>
                        @endforeach
                    </ul>
                </div>
                {{-- Services --}}
                <div class="footer-col">
                    <h4>Services</h4>
                    <ul>
                        @foreach(['Daily Newsletter','RSS Feeds','Press Releases','Syndication','Digital Edition','Media Kit'] as $l)
                            <li><a href="#">{{ $l }}</a></li>
                        @endforeach
                    </ul>
                </div>
                {{-- Newsletter --}}
                <div class="footer-col">
                    <h4>Newsletter</h4>
                    <p style="font-size:11px;color:#888;margin-bottom:10px;line-height:1.6;">Get top business stories delivered every morning.</p>
                    <div style="display:flex;gap:0;">
                        <input type="email" placeholder="Your email…"
                               style="flex:1;background:#2a2a2a;border:none;color:#fff;font-size:11px;padding:8px 10px;outline:none;">
                        <button style="background:#e60000;color:#fff;font-size:9px;font-weight:900;text-transform:uppercase;padding:0 12px;border:none;cursor:pointer;"
                                onmouseover="this.style.background='#c00'" onmouseout="this.style.background='#e60000'">
                            Join
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Footer bottom --}}
        <div style="border-top:1px solid #2a2a2a;">
            <div class="wrap flex flex-col md:flex-row justify-between items-center gap-3" style="padding-top:12px;padding-bottom:12px;">
                <p style="font-size:9px;color:#555;text-transform:uppercase;letter-spacing:0.2em;">
                    © {{ date('Y') }} BizScoop Media Group. All Rights Reserved.
                </p>
                <div style="display:flex;gap:20px;font-size:9px;color:#555;text-transform:uppercase;letter-spacing:0.1em;">
                    <a href="#" onmouseover="this.style.color='#e60000'" onmouseout="this.style.color='#555'">Privacy</a>
                    <a href="#" onmouseover="this.style.color='#e60000'" onmouseout="this.style.color='#555'">Terms</a>
                    <a href="#" onmouseover="this.style.color='#e60000'" onmouseout="this.style.color='#555'">Cookies</a>
                    <a href="#" onmouseover="this.style.color='#e60000'" onmouseout="this.style.color='#555'">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

</div>{{-- /#app --}}

{{-- Search Overlay --}}
<div x-show="searchOpen"
     x-transition:enter="transition duration-150"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     style="position:fixed;inset:0;background:rgba(0,0,0,0.75);z-index:500;display:flex;align-items:flex-start;justify-content:center;padding-top:80px;"
     @click.self="searchOpen = false"
     x-cloak>
    <div style="background:#fff;width:100%;max-width:640px;margin:0 16px;padding:24px;box-shadow:0 20px 60px rgba(0,0,0,0.4);">
        <form action="{{ route('frontend.search') }}" method="GET" style="display:flex;gap:0;">
            <input name="q" type="text" autofocus placeholder="Search articles, topics, people…"
                   style="flex:1;border:none;border-bottom:3px solid #e60000;padding:10px 4px;font-size:16px;font-weight:600;outline:none;color:#111;">
            <button type="submit"
                    style="background:#e60000;color:#fff;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:0.1em;padding:0 20px;border:none;cursor:pointer;">
                Search
            </button>
        </form>
        <button @click="searchOpen = false"
                style="margin-top:12px;font-size:9px;color:#999;text-transform:uppercase;letter-spacing:0.15em;background:none;border:none;cursor:pointer;">
            ✕ Close
        </button>
    </div>
</div>

@stack('scripts')
</body>
</html>
