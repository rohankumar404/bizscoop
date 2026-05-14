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
    siteLoaded: false,
    serviceModalOpen: false,
    serviceModalTitle: '',
    serviceModalContent: '',
    serviceModalButton: '',
    serviceFormOpen: false,
    serviceSent: false,
    serviceLoading: false,
    serviceErrors: {},
    serviceForm: { name: '', email: '', message: '' },
    openServiceModal(service) {
        this.serviceFormOpen = false;
        this.serviceSent = false;
        this.serviceLoading = false;
        this.serviceErrors = {};
        this.serviceForm = { name: '', email: '', message: '' };
        const data = {
            'Daily Newsletter': {
                title: 'Daily Newsletter Briefing',
                content: 'Stay ahead of the curve with BizScoop’s Daily Newsletter. Delivered every morning at 7:00 AM UTC, our briefing provides a high-integrity summary of the most critical business stories, market shifts, and emerging trends across the GCC and MENA region. Join 50,000+ professionals who rely on our curated intelligence to start their day.',
                button: 'Subscribe Now'
            },
            'RSS Feeds': {
                title: 'Real-Time RSS Feeds',
                content: 'Integrate BizScoop’s high-integrity business journalism directly into your workflow. Our RSS feeds offer real-time updates across multiple categories including Global Markets, GCC Economy, FinTech, and Energy. Whether you are using a personal news aggregator or a corporate intranet, our clean, structured data ensures you never miss a scoop.',
                button: 'Get XML Links'
            },
            'Press Releases': {
                title: 'Corporate Press Distribution',
                content: 'Maximize your brand\'s visibility by distributing your press releases through BizScoop. Our platform reaches a premium audience of decision-makers, investors, and industry leaders. We offer both standard distribution and premium \'Featured\' placement to ensure your corporate announcements get the attention they deserve.',
                button: 'Submit Release'
            },
            'Syndication': {
                title: 'Content Syndication Partnership',
                content: 'Expand your reach by syndicating your content with BizScoop. We partner with world-class publications and industry experts to provide our readers with a broader range of insights. Our syndication partners benefit from our high-authority domain, boosting their content\'s visibility and professional impact across the region.',
                button: 'Partner With Us'
            },
            'Digital Edition': {
                title: 'Digital Edition Experience',
                content: 'Experience the depth of our investigative journalism in a beautifully designed digital format. BizScoop’s Digital Edition offers an immersive, ad-light reading experience optimized for tablets and mobile devices. Access exclusive monthly deep-dives, interactive charts, and high-resolution photography that brings business stories to life.',
                button: 'Explore Edition'
            },
            'Media Kit': {
                title: '2026 Advertising Media Kit',
                content: 'Download the BizScoop 2026 Media Kit to explore our audience demographics, reach, and strategic advertising opportunities. From high-impact display units to sponsored content series and event partnerships, we offer a range of solutions to connect your brand with the region\'s most influential business leaders.',
                button: 'Download Kit'
            }
        };
        const item = data[service];
        if (item) {
            this.serviceModalTitle = item.title;
            this.serviceModalContent = item.content;
            this.serviceModalButton = item.button;
            this.serviceModalOpen = true;
        }
    },
    async submitServiceInquiry() {
        this.serviceLoading = true;
        this.serviceErrors = {};
        try {
            const response = await fetch('{{ route("frontend.service-inquiry.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    ...this.serviceForm,
                    service: this.serviceModalTitle
                })
            });
            const result = await response.json();
            if (response.ok) {
                this.serviceSent = true;
                this.serviceForm = { name: '', email: '', message: '' };
            } else {
                this.serviceErrors = result.errors || {};
            }
        } catch (e) {
            console.error(e);
        } finally {
            this.serviceLoading = false;
        }
    },
    newsletterEmail: '',
    newsletterLoading: false,
    newsletterMessage: '',
    async submitNewsletter() {
        if (!this.newsletterEmail) return;
        this.newsletterLoading = true;
        this.newsletterMessage = '';
        try {
            const response = await fetch('{{ route("frontend.newsletter.subscribe") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email: this.newsletterEmail })
            });
            const result = await response.json();
            this.newsletterMessage = result.message;
            if (response.ok) {
                this.newsletterEmail = '';
            }
        } catch (e) {
            this.newsletterMessage = 'Something went wrong.';
        } finally {
            this.newsletterLoading = false;
        }
    }
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

    {{-- ════════════════════════════════════════════════════
         3. PROFESSIONAL ENTERPRISE NAVIGATION BAR
    ════════════════════════════════════════════════════ --}}

    {{-- NAV STYLES --}}
    <style>
        /* ── NAV BASE ── */
        #biz-nav {
            background: #e60000;
            position: sticky;
            top: 0;
            z-index: 9000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.25);
        }
        #biz-nav .nav-inner {
            max-width: 1260px;
            margin: 0 auto;
            padding: 0 12px;
            display: flex;
            align-items: stretch;
            justify-content: space-between;
            height: 42px;
        }

        /* ── DESKTOP MENU ── */
        .nav-desktop {
            display: flex;
            align-items: stretch;
            flex: 1;
        }
        .nav-item {
            position: relative;
            display: flex;
            align-items: stretch;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 0 14px;
            font-size: 10.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: rgba(255,255,255,0.95);
            white-space: nowrap;
            border-right: 1px solid rgba(255,255,255,0.12);
            transition: background 0.18s ease;
            cursor: pointer;
            text-decoration: none;
            background: transparent;
            border-top: none;
            border-bottom: none;
            border-left: none;
        }
        .nav-link:hover,
        .nav-link.active {
            background: rgba(0,0,0,0.2);
            color: #fff;
        }
        .nav-link-home {
            font-weight: 900;
            border-right: 1px solid rgba(255,255,255,0.15);
        }

        /* ── CHEVRON ── */
        .nav-chevron {
            width: 9px;
            height: 9px;
            flex-shrink: 0;
            transition: transform 0.28s cubic-bezier(0.4,0,0.2,1);
            opacity: 0.75;
        }
        .nav-item.is-open .nav-chevron {
            transform: rotate(180deg);
        }

        /* ── DROPDOWN PANEL ── */
        .nav-dropdown {
            position: absolute;
            top: calc(100% + 0px);
            left: 0;
            min-width: 220px;
            background: #fff;
            border-top: 3px solid #e60000;
            box-shadow: 0 20px 60px rgba(0,0,0,0.18), 0 4px 12px rgba(0,0,0,0.08);
            z-index: 9999;
            opacity: 0;
            transform: translateY(-8px);
            pointer-events: none;
            transition: opacity 0.22s cubic-bezier(0.4,0,0.2,1),
                        transform 0.22s cubic-bezier(0.4,0,0.2,1);
            border-radius: 0 0 4px 4px;
            overflow: hidden;
        }
        .nav-item.is-open .nav-dropdown {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        .dropdown-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 11px 18px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.13em;
            color: #3a3a3a;
            border-bottom: 1px solid #f2f2f2;
            text-decoration: none;
            transition: background 0.15s ease, color 0.15s ease, padding-left 0.18s ease;
            position: relative;
        }
        .dropdown-item:last-child { border-bottom: none; }
        .dropdown-item:hover {
            background: #fdf3f3;
            color: #e60000;
            padding-left: 22px;
        }
        .dropdown-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #e60000;
            transform: scaleY(0);
            transition: transform 0.18s ease;
        }
        .dropdown-item:hover::before { transform: scaleY(1); }
        .dropdown-item .di-arrow {
            width: 10px;
            height: 10px;
            opacity: 0;
            transform: translateX(-6px);
            transition: opacity 0.18s ease, transform 0.18s ease;
            color: #e60000;
            flex-shrink: 0;
        }
        .dropdown-item:hover .di-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        /* ── NAV RIGHT (search + hamburger) ── */
        .nav-right {
            display: flex;
            align-items: stretch;
            flex-shrink: 0;
        }
        .nav-search-btn, .nav-hamburger {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            background: rgba(0,0,0,0.22);
            border: none;
            border-left: 1px solid rgba(255,255,255,0.14);
            color: #fff;
            cursor: pointer;
            transition: background 0.18s ease;
        }
        .nav-search-btn:hover, .nav-hamburger:hover { background: rgba(0,0,0,0.38); }
        .nav-hamburger { display: none; }

        /* ── MOBILE OVERLAY ── */
        .mobile-menu-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 8998;
            backdrop-filter: blur(3px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .mobile-menu-overlay.visible { opacity: 1; }

        /* ── MOBILE PANEL ── */
        .mobile-menu-panel {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 300px;
            max-width: 85vw;
            height: 100%;
            background: #111;
            z-index: 8999;
            transform: translateX(-100%);
            transition: transform 0.32s cubic-bezier(0.4,0,0.2,1);
            overflow-y: auto;
            flex-direction: column;
        }
        .mobile-menu-panel.open { transform: translateX(0); }

        .mobile-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            background: #e60000;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }
        .mobile-panel-logo {
            font-family: 'Merriweather', serif;
            font-size: 20px;
            font-weight: 900;
            color: #fff;
            font-style: italic;
            letter-spacing: -1px;
        }
        .mobile-close-btn {
            width: 34px;
            height: 34px;
            background: rgba(0,0,0,0.25);
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            line-height: 1;
            transition: background 0.15s;
        }
        .mobile-close-btn:hover { background: rgba(0,0,0,0.45); }

        .mobile-nav-list {
            padding: 8px 0;
            flex: 1;
        }
        .mobile-nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 22px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #ccc;
            border-bottom: 1px solid #1e1e1e;
            text-decoration: none;
            background: none;
            border-left: none;
            border-right: none;
            border-top: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            transition: color 0.15s, background 0.15s;
        }
        .mobile-nav-link:hover,
        .mobile-nav-link.active { color: #fff; background: #1a1a1a; }
        .mobile-nav-link.home-link { color: #fff; border-left: 3px solid #e60000; }

        .mobile-chevron {
            width: 12px;
            height: 12px;
            transition: transform 0.28s cubic-bezier(0.4,0,0.2,1);
            color: #666;
            flex-shrink: 0;
        }
        .mobile-accordion.is-open .mobile-chevron { transform: rotate(180deg); }

        .mobile-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.32s cubic-bezier(0.4,0,0.2,1);
            background: #0d0d0d;
        }
        .mobile-accordion.is-open .mobile-submenu {
            max-height: 800px;
        }
        .mobile-sub-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 22px 10px 32px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #888;
            text-decoration: none;
            border-bottom: 1px solid #1a1a1a;
            transition: color 0.15s, background 0.15s;
        }
        .mobile-sub-link::before {
            content: '›';
            color: #e60000;
            font-size: 14px;
            font-weight: 900;
        }
        .mobile-sub-link:hover { color: #fff; background: #151515; }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            .nav-desktop { display: none; }
            .nav-hamburger { display: flex; }
            .mobile-menu-overlay,
            .mobile-menu-panel { display: flex; }
        }

        /* ── FOCUS STYLES (Accessibility) ── */
        .nav-link:focus-visible,
        .dropdown-item:focus-visible,
        .mobile-nav-link:focus-visible,
        .mobile-sub-link:focus-visible {
            outline: 2px solid #fff;
            outline-offset: -2px;
        }
    </style>

    <nav id="biz-nav" role="navigation" aria-label="Main Navigation">
        <div class="nav-inner">

            {{-- ── DESKTOP MENU ── --}}
            <div class="nav-desktop" role="menubar">

                {{-- Home --}}
                @php $homeActive = request()->routeIs('frontend.home'); @endphp
                <div class="nav-item">
                    <a href="{{ route('frontend.home') }}"
                       class="nav-link nav-link-home {{ $homeActive ? 'active' : '' }}"
                       role="menuitem">Home</a>
                </div>

                {{-- Categories --}}
                @foreach($headerCategories as $cat)
                    @php
                        $catActive   = request()->fullUrl() == route('frontend.category.show', $cat->slug);
                        $hasChildren = $cat->children->count() > 0;
                        $catName     = $cat->getTranslation('name', app()->getLocale());
                    @endphp
                    <div class="nav-item {{ $hasChildren ? 'has-dropdown' : '' }}"
                         role="none"
                         data-nav-item>

                        <a href="{{ route('frontend.category.show', $cat->slug) }}"
                           class="nav-link {{ $catActive ? 'active' : '' }}"
                           role="menuitem"
                           aria-haspopup="{{ $hasChildren ? 'true' : 'false' }}"
                           aria-expanded="false">
                            {{ $catName }}
                            @if($hasChildren)
                                <svg class="nav-chevron" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            @endif
                        </a>

                        @if($hasChildren)
                            <div class="nav-dropdown" role="menu" aria-label="{{ $catName }} submenu">
                                @foreach($cat->children as $child)
                                    <a href="{{ route('frontend.category.show', $child->slug) }}"
                                       class="dropdown-item"
                                       role="menuitem">
                                        <span>{{ $child->getTranslation('name', app()->getLocale()) }}</span>
                                        <svg class="di-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- ── RIGHT CONTROLS ── --}}
            <div class="nav-right">
                {{-- Search --}}
                <button class="nav-search-btn"
                        @click="searchOpen = true"
                        aria-label="Open search">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                </button>

                {{-- Hamburger (mobile) --}}
                <button class="nav-hamburger"
                        id="nav-hamburger-btn"
                        aria-label="Open menu"
                        aria-expanded="false"
                        aria-controls="mobile-menu-panel">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" d="M3 6h18M3 12h18M3 18h18"/>
                    </svg>
                </button>
            </div>

        </div>
    </nav>

    {{-- ── MOBILE OVERLAY ── --}}
    <div class="mobile-menu-overlay" id="mobile-overlay" aria-hidden="true"></div>

    {{-- ── MOBILE PANEL ── --}}
    <div class="mobile-menu-panel"
         id="mobile-menu-panel"
         role="dialog"
         aria-modal="true"
         aria-label="Mobile Navigation">

        <div class="mobile-panel-header">
            <span class="mobile-panel-logo">BizScoop</span>
            <button class="mobile-close-btn"
                    id="mobile-close-btn"
                    aria-label="Close menu">✕</button>
        </div>

        <nav class="mobile-nav-list">
            <a href="{{ route('frontend.home') }}"
               class="mobile-nav-link home-link {{ request()->routeIs('frontend.home') ? 'active' : '' }}">
                Home
            </a>

            @foreach($headerCategories as $cat)
                @php
                    $catName     = $cat->getTranslation('name', app()->getLocale());
                    $hasChildren = $cat->children->count() > 0;
                    $catActive   = request()->fullUrl() == route('frontend.category.show', $cat->slug);
                @endphp

                @if($hasChildren)
                    <div class="mobile-accordion" data-accordion>
                        <button class="mobile-nav-link {{ $catActive ? 'active' : '' }}"
                                data-accordion-trigger
                                aria-expanded="false"
                                aria-label="Toggle {{ $catName }}">
                            <span>{{ $catName }}</span>
                            <svg class="mobile-chevron" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="mobile-submenu" role="menu">
                            <a href="{{ route('frontend.category.show', $cat->slug) }}"
                               class="mobile-sub-link"
                               role="menuitem">All {{ $catName }}</a>
                            @foreach($cat->children as $child)
                                <a href="{{ route('frontend.category.show', $child->slug) }}"
                                   class="mobile-sub-link"
                                   role="menuitem">
                                    {{ $child->getTranslation('name', app()->getLocale()) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ route('frontend.category.show', $cat->slug) }}"
                       class="mobile-nav-link {{ $catActive ? 'active' : '' }}">
                        {{ $catName }}
                    </a>
                @endif
            @endforeach
        </nav>

        {{-- Mobile bottom --}}
        <div style="padding:20px;border-top:1px solid #1e1e1e;flex-shrink:0;">
            <form action="{{ route('frontend.search') }}" method="GET" style="display:flex;gap:0;">
                <input name="q" type="text" placeholder="Search articles…"
                       style="flex:1;background:#222;border:none;color:#fff;font-size:12px;padding:10px 12px;outline:none;">
                <button type="submit" style="background:#e60000;color:#fff;border:none;padding:0 16px;font-size:10px;font-weight:900;text-transform:uppercase;cursor:pointer;">
                    Go
                </button>
            </form>
        </div>
    </div>

    {{-- ── NAV JAVASCRIPT ── --}}
    <script>
    (function() {
        'use strict';

        // ── Desktop dropdown hover logic ──
        const navItems = document.querySelectorAll('[data-nav-item]');

        navItems.forEach(item => {
            const link     = item.querySelector('.nav-link');
            const dropdown = item.querySelector('.nav-dropdown');
            if (!dropdown) return;

            let closeTimer;

            const openMenu = () => {
                clearTimeout(closeTimer);
                // Close all others
                navItems.forEach(other => {
                    if (other !== item) {
                        other.classList.remove('is-open');
                        const otherLink = other.querySelector('.nav-link');
                        if (otherLink) otherLink.setAttribute('aria-expanded', 'false');
                    }
                });
                item.classList.add('is-open');
                link.setAttribute('aria-expanded', 'true');
            };

            const closeMenu = () => {
                closeTimer = setTimeout(() => {
                    item.classList.remove('is-open');
                    link.setAttribute('aria-expanded', 'false');
                }, 120);
            };

            item.addEventListener('mouseenter', openMenu);
            item.addEventListener('mouseleave', closeMenu);

            // Keyboard: Enter/Space opens, Escape closes
            link.addEventListener('keydown', e => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    item.classList.contains('is-open') ? closeMenu() : openMenu();
                }
                if (e.key === 'Escape') closeMenu();
            });
        });

        // Click outside to close all
        document.addEventListener('click', e => {
            if (!e.target.closest('#biz-nav')) {
                navItems.forEach(item => {
                    item.classList.remove('is-open');
                    const link = item.querySelector('.nav-link');
                    if (link) link.setAttribute('aria-expanded', 'false');
                });
            }
        });

        // ── Mobile menu ──
        const hamburger  = document.getElementById('nav-hamburger-btn');
        const closeBtn   = document.getElementById('mobile-close-btn');
        const overlay    = document.getElementById('mobile-overlay');
        const panel      = document.getElementById('mobile-menu-panel');

        const openMobile = () => {
            panel.classList.add('open');
            overlay.classList.add('visible');
            hamburger.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
            closeBtn.focus();
        };

        const closeMobile = () => {
            panel.classList.remove('open');
            overlay.classList.remove('visible');
            hamburger.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
            hamburger.focus();
        };

        hamburger.addEventListener('click', openMobile);
        closeBtn.addEventListener('click', closeMobile);
        overlay.addEventListener('click', closeMobile);

        // Escape key closes mobile menu
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && panel.classList.contains('open')) closeMobile();
        });

        // ── Mobile accordion ──
        const accordions = document.querySelectorAll('[data-accordion]');
        accordions.forEach(acc => {
            const trigger = acc.querySelector('[data-accordion-trigger]');
            trigger.addEventListener('click', () => {
                const isOpen = acc.classList.contains('is-open');
                // Close all
                accordions.forEach(a => {
                    a.classList.remove('is-open');
                    a.querySelector('[data-accordion-trigger]').setAttribute('aria-expanded', 'false');
                });
                // Toggle this
                if (!isOpen) {
                    acc.classList.add('is-open');
                    trigger.setAttribute('aria-expanded', 'true');
                }
            });
        });

    })();
    </script>

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
                        @php
                            $links = [
                                'About BizScoop' => 'frontend.pages.about',
                                'Editorial Standards' => 'frontend.pages.editorial',
                                'Advertise With Us' => 'frontend.pages.advertise',
                                'Careers' => 'frontend.pages.careers',
                                'Contact Us' => 'frontend.pages.contact',
                                'Privacy Policy' => 'frontend.pages.privacy',
                            ];
                        @endphp
                        @foreach($links as $label => $route)
                            <li><a href="{{ route($route) }}">{{ $label }}</a></li>
                        @endforeach
                    </ul>
                </div>
            {{-- Services --}}
                <div class="footer-col">
                    <h4>Services</h4>
                    <ul>
                        @foreach(['Daily Newsletter','RSS Feeds','Press Releases','Syndication','Digital Edition','Media Kit'] as $l)
                            <li><a href="javascript:void(0)" @click="openServiceModal('{{ $l }}')">{{ $l }}</a></li>
                        @endforeach
                    </ul>
                </div>
                {{-- Newsletter --}}
                <div class="footer-col">
                    <h4>Newsletter</h4>
                    <p style="font-size:11px;color:#888;margin-bottom:10px;line-height:1.6;">Get top business stories delivered every morning.</p>
                    <form @submit.prevent="submitNewsletter" style="display:flex;flex-direction:column;gap:8px;">
                        <div style="display:flex;gap:0;">
                            <input type="email" x-model="newsletterEmail" required placeholder="Your email…"
                                   style="flex:1;background:#2a2a2a;border:none;color:#fff;font-size:11px;padding:8px 10px;outline:none;">
                            <button type="submit" :disabled="newsletterLoading" style="background:#e60000;color:#fff;font-size:9px;font-weight:900;text-transform:uppercase;padding:0 12px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:5px;"
                                    onmouseover="this.style.background='#c00'" onmouseout="this.style.background='#e60000'">
                                <svg x-show="newsletterLoading" width="10" height="10" viewBox="0 0 24 24" style="animation: spin 1s linear infinite;"><path fill="currentColor" d="M12 4V2A10 10 0 0 0 2 12h2a8 8 0 0 1 8-8Z"/></svg>
                                <span x-text="newsletterLoading ? '...' : 'Join'"></span>
                            </button>
                        </div>
                        <template x-if="newsletterMessage">
                            <span x-text="newsletterMessage" style="font-size:9px;font-weight:bold;color:#e60000;"></span>
                        </template>
                    </form>
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

{{-- Service Modal --}}
<div x-show="serviceModalOpen"
     x-transition:enter="transition duration-300 ease-out"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition duration-200 ease-in"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="position:fixed !important;top:0 !important;left:0 !important;width:100% !important;height:100% !important;background:rgba(0,0,0,0.85) !important;z-index:99999 !important;display:grid !important;place-items:center !important;padding:20px;backdrop-filter:blur(10px);"
     @click.self="serviceModalOpen = false"
     x-cloak>
    
    <div style="background:#fff;width:100%;max-width:550px;border-radius:16px;overflow:hidden;box-shadow:0 40px 120px rgba(0,0,0,0.6);position:relative;margin:auto;"
         x-show="serviceModalOpen"
         x-transition:enter="transition duration-500 cubic-bezier(0.175, 0.885, 0.32, 1.275)"
         x-transition:enter-start="scale-95 opacity-0 translate-y-10"
         x-transition:enter-end="scale-100 opacity-100 translate-y-0">
        
        {{-- Modal Header --}}
        <div style="background:#111;padding:30px 40px;position:relative;">
            <div style="font-size:10px;font-weight:900;text-transform:uppercase;color:#e60000;letter-spacing:0.25em;margin-bottom:8px;">BizScoop Enterprise</div>
            <h3 x-text="serviceFormOpen ? 'Service Inquiry' : serviceModalTitle" style="font-family:'Merriweather',serif;font-size:28px;font-weight:900;color:#fff;margin:0;letter-spacing:-0.02em;"></h3>
            <button @click="serviceModalOpen = false" style="position:absolute;top:25px;right:25px;background:none;border:none;color:#666;font-size:24px;cursor:pointer;transition:color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#666'">✕</button>
        </div>

        {{-- Modal Body: Information --}}
        <div x-show="!serviceFormOpen" style="padding:45px 40px 35px 40px;">
            <div x-text="serviceModalContent" style="font-size:17px;line-height:1.8;color:#444;margin-bottom:40px;"></div>
            
            <div style="display:flex;gap:15px;">
                <button @click="serviceFormOpen = true"
                   style="flex:1;background:#e60000;color:#fff;text-align:center;padding:18px;font-size:14px;font-weight:900;text-transform:uppercase;border:none;cursor:pointer;border-radius:6px;transition:all 0.3s;box-shadow:0 12px 24px rgba(230,0,0,0.25);"
                   onmouseover="this.style.background='#c00';this.style.transform='translateY(-2px)'" 
                   onmouseout="this.style.background='#e60000';this.style.transform='translateY(0)'">
                    <span x-text="serviceModalButton"></span>
                </button>
                <button @click="serviceModalOpen = false" 
                        style="flex:1;background:#f8f8f8;color:#666;text-align:center;padding:18px;font-size:14px;font-weight:900;text-transform:uppercase;border:1px solid #eee;cursor:pointer;border-radius:6px;transition:all 0.2s;"
                        onmouseover="this.style.background='#fff';this.style.borderColor='#ccc'" 
                        onmouseout="this.style.background='#f8f8f8';this.style.borderColor='#eee'">
                    Close
                </button>
            </div>
        </div>

        {{-- Modal Body: Form --}}
        <div x-show="serviceFormOpen" style="padding:45px 40px 35px 40px;">
            <template x-if="!serviceSent">
                <form @submit.prevent="submitServiceInquiry" style="display:flex;flex-direction:column;gap:20px;">
                    <div>
                        <label style="display:block;font-size:11px;font-weight:900;text-transform:uppercase;color:#999;margin-bottom:8px;letter-spacing:0.05em;">Full Name</label>
                        <input type="text" x-model="serviceForm.name" required placeholder="John Doe" style="width:100%;padding:14px;border:1px solid #eee;border-radius:6px;outline:none;font-size:15px;background:#fafafa;transition:border-color 0.3s;" onfocus="this.style.borderColor='#e60000';this.style.background='#fff'" onblur="this.style.borderColor='#eee';this.style.background='#fafafa'">
                        <template x-if="serviceErrors.name">
                            <span x-text="serviceErrors.name[0]" style="color:#e60000;font-size:10px;font-weight:bold;margin-top:4px;display:block;"></span>
                        </template>
                    </div>
                    <div>
                        <label style="display:block;font-size:11px;font-weight:900;text-transform:uppercase;color:#999;margin-bottom:8px;letter-spacing:0.05em;">Work Email</label>
                        <input type="email" x-model="serviceForm.email" required placeholder="john@company.com" style="width:100%;padding:14px;border:1px solid #eee;border-radius:6px;outline:none;font-size:15px;background:#fafafa;transition:border-color 0.3s;" onfocus="this.style.borderColor='#e60000';this.style.background='#fff'" onblur="this.style.borderColor='#eee';this.style.background='#fafafa'">
                        <template x-if="serviceErrors.email">
                            <span x-text="serviceErrors.email[0]" style="color:#e60000;font-size:10px;font-weight:bold;margin-top:4px;display:block;"></span>
                        </template>
                    </div>
                    <div>
                        <label style="display:block;font-size:11px;font-weight:900;text-transform:uppercase;color:#999;margin-bottom:8px;letter-spacing:0.05em;">Inquiry Details</label>
                        <textarea x-model="serviceForm.message" required placeholder="How can we help your business thrive?" rows="4" style="width:100%;padding:14px;border:1px solid #eee;border-radius:6px;outline:none;font-size:15px;background:#fafafa;resize:none;transition:border-color 0.3s;" onfocus="this.style.borderColor='#e60000';this.style.background='#fff'" onblur="this.style.borderColor='#eee';this.style.background='#fafafa'"></textarea>
                        <template x-if="serviceErrors.message">
                            <span x-text="serviceErrors.message[0]" style="color:#e60000;font-size:10px;font-weight:bold;margin-top:4px;display:block;"></span>
                        </template>
                    </div>
                    <div style="display:flex;gap:12px;margin-top:10px;">
                        <button type="submit" :disabled="serviceLoading" style="flex:1;background:#111;color:#fff;padding:18px;font-weight:900;text-transform:uppercase;font-size:13px;border:none;border-radius:6px;cursor:pointer;transition:all 0.3s;display:flex;align-items:center;justify-content:center;gap:10px;" onmouseover="this.style.background='#e60000'" onmouseout="this.style.background='#111'">
                            <svg x-show="serviceLoading" width="16" height="16" viewBox="0 0 24 24" style="animation: spin 1s linear infinite;"><path fill="currentColor" d="M12 4V2A10 10 0 0 0 2 12h2a8 8 0 0 1 8-8Z"/></svg>
                            <span x-text="serviceLoading ? 'Sending...' : 'Send Inquiry'"></span>
                        </button>
                        <button type="button" @click="serviceFormOpen = false" :disabled="serviceLoading" style="padding:0 25px;background:#f5f5f5;color:#999;font-weight:900;text-transform:uppercase;font-size:12px;border:none;border-radius:6px;cursor:pointer;">
                            Back
                        </button>
                    </div>
                </form>
            </template>

            {{-- Success Message --}}
            <template x-if="serviceSent">
                <div style="text-align:center;padding:40px 0;">
                    <div style="width:80px;height:80px;background:#e6000015;color:#e60000;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 25px;">
                        <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h4 style="font-family:'Merriweather',serif;font-size:26px;font-weight:900;color:#111;margin-bottom:12px;">Inquiry Received</h4>
                    <p style="color:#666;line-height:1.6;margin-bottom:30px;">Thank you for reaching out. A BizScoop strategist will contact you within 24 business hours.</p>
                    <button @click="serviceModalOpen = false" style="background:#111;color:#fff;padding:14px 40px;font-weight:900;text-transform:uppercase;font-size:12px;border:none;border-radius:6px;cursor:pointer;">Got it</button>
                </div>
            </template>
        </div>

        {{-- Modal Footer --}}
        <div style="padding:22px 40px;background:#fafafa;border-top:1px solid #f0f0f0;display:flex;align-items:center;justify-content:center;gap:12px;">
            <div style="width:8px;height:8px;background:#e60000;border-radius:50%;box-shadow:0 0 8px rgba(230,0,0,0.5);"></div>
            <span style="font-size:10px;font-weight:800;color:#bbb;text-transform:uppercase;letter-spacing:0.15em;">High-Integrity Professional Network</span>
        </div>
    </div>
</div>

@stack('scripts')
</body>
</html>
