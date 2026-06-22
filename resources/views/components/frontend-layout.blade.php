<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @props(['title' => null, 'description' => null, 'ogImage' => null])
    <x-seo :title="$title" :description="$description" :ogImage="$ogImage" />
    @if(setting('site_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ Storage::url(setting('site_favicon')) }}">
    @endif
    <x-schema type="Organization" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Merriweather:wght@400;700;900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/responsive-home.css') }}">
    @stack('styles')
    {{-- Ticker keyframe — inline guarantee --}}
    <style>
        @keyframes ticker-scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
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

        body,
        html,
        #app,
        .min-h-screen {
            background-color: #ffffff !important;
            background: #ffffff !important;
        }

        .ticker-track:hover {
            animation-play-state: paused !important;
        }

        /* ── Global Loading Spinner ── */
        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes pulse-pop {

            0%,
            100% {
                transform: scale(0.85);
                opacity: 0.8;
            }

            50% {
                transform: scale(1.15);
                opacity: 1;
            }
        }

        .loading-spinner {
            width: 52px;
            height: 52px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-top: 4px solid #000;
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
            background: #000;
            opacity: 0.15;
            animation: pulse-pop 0.8s ease-in-out infinite;
        }

        @media (max-width: 768px) {
            .resp-warap {
                display: flex !important;
                flex-direction: column !important;
            }
        }
    </style>
</head>

<body x-data="{ 
    mobileMenuOpen: false, 
    searchOpen: false, 
    siteLoaded: false,
    newsletterModalOpen: false,
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
                content: 'Stay ahead of the curve with Bizscoop’s Daily Newsletter. Delivered every morning at 7:00 AM UTC, our briefing provides a high-integrity summary of the most critical business stories, market shifts, and emerging trends across the GCC and MENA region. Join 50,000+ professionals who rely on our curated intelligence to start their day.',
                button: 'Subscribe Now'
            },
            'RSS Feeds': {
                title: 'Real-Time RSS Feeds',
                content: 'Integrate Bizscoop’s high-integrity business journalism directly into your workflow. Our RSS feeds offer real-time updates across multiple categories including Global Markets, GCC Economy, FinTech, and Energy. Whether you are using a personal news aggregator or a corporate intranet, our clean, structured data ensures you never miss a scoop.',
                button: 'Get XML Links'
            },
            'Press Releases': {
                title: 'Corporate Press Distribution',
                content: 'Maximize your brand\'s visibility by distributing your press releases through Bizscoop. Our platform reaches a premium audience of decision-makers, investors, and industry leaders. We offer both standard distribution and premium \'Featured\' placement to ensure your corporate announcements get the attention they deserve.',
                button: 'Submit Release'
            },
            'Syndication': {
                title: 'Content Syndication Partnership',
                content: 'Expand your reach by syndicating your content with Bizscoop. We partner with world-class publications and industry experts to provide our readers with a broader range of insights. Our syndication partners benefit from our high-authority domain, boosting their content\'s visibility and professional impact across the region.',
                button: 'Partner With Us'
            },
            'Digital Edition': {
                title: 'Digital Edition Experience',
                content: 'Experience the depth of our investigative journalism in a beautifully designed digital format. Bizscoop’s Digital Edition offers an immersive, ad-light reading experience optimized for tablets and mobile devices. Access exclusive monthly deep-dives, interactive charts, and high-resolution photography that brings business stories to life.',
                button: 'Explore Edition'
            },
            'Media Kit': {
                title: '2026 Advertising Media Kit',
                content: 'Download the Bizscoop 2026 Media Kit to explore our audience demographics, reach, and strategic advertising opportunities. From high-impact display units to sponsored content series and event partnerships, we offer a range of solutions to connect your brand with the region\'s most influential business leaders.',
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
    <div x-show="!siteLoaded" x-transition.opacity.duration.400ms
        style="position:fixed;inset:0;background:#fff;z-index:999999;">
        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);">
            <div class="loading-spinner"></div>
        </div>
    </div>

    <div id="app" class="flex flex-col min-h-screen" style="background: #fff !important;">

        {{-- ═══════════════════════════════════
        1. TOP BLACK UTILITY BAR
        ═══════════════════════════════════ --}}
        <div style="background:#111;color:#ccc;font-size:11px;font-weight:600;border-bottom:1px solid #2a2a2a;"
            class="hidden lg:block">
            <div class="wrap flex justify-between items-center" style="padding-top:7px;padding-bottom:7px;">
                {{-- Left: Date / Weather --}}
                <div class="flex items-center gap-5">
                    <span style="color:#aaa;">📅 {{ now()->format('l, d F Y') }}</span>
                    <span id="weather-widget" style="color:#aaa;">☀️ Dubai &nbsp; 32°C</span>
                </div>
                {{-- Right: Links + Social --}}
                <div class="flex items-center gap-5">
                    <div class="flex items-center gap-4"
                        style="border-right:1px solid #333;padding-right:16px;margin-right:4px;">                        
                        <span style="color:#aaa;" onmouseover="this.style.color='#aaa'"
                            onmouseout="this.style.color='#aaa'">🌐 English ▾</span>
                    </div>
                    <div class="flex items-center gap-3" style="color:#888;">
                        <a href="https://www.linkedin.com/company/bizscoop-mena/" style="color:#888;" onmouseover="this.style.color='#3b82f6'"
                            onmouseout="this.style.color='#888'" title="Linkedin">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-4 0v7h-4v-7a6 6 0 016-6zM2 9h4v12H2zM4 3a2 2 0 110 4 2 2 0 010-4z" />
                            </svg>
                        </a>
                        <a href="https://www.facebook.com/share/1Ay66dEyNf/" style="color:#888;" onmouseover="this.style.color='#3b82f6'"
                            onmouseout="this.style.color='#888'" title="Facebook">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                            </svg>
                        </a>
                        <a href="https://www.threads.com/@bizscoopmena" rel="noopener noreferrer" style="color:#888;" onmouseover="this.style.color='#38bdf8'" onmouseout="this.style.color='#888'" title="Threads">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M16.3 11.2c-.2-.1-.4-.2-.6-.3-.1-2.2-1.3-3.5-3.4-3.5-2 0-3.4 1.2-3.4 3 0 1.7 1.2 2.8 3.1 3 .4.1.8.1 1.2.2.1.4.1.8.1 1.2 0 1.5-.7 2.3-2.1 2.3-1.2 0-2-.7-2.2-1.9H6.8c.2 2.3 1.9 3.8 4.4 3.8 2.8 0 4.5-1.7 4.5-4.5 0-.4 0-.8-.1-1.2 1.1.3 1.7.9 1.7 1.8 0 1.2-1.1 2.1-2.9 2.3v1.8c3-.1 5-1.8 5-4.2 0-1.8-1.1-3.2-3.1-3.8zm-3.1.5c-.3 0-.6-.1-.9-.1-1.1-.1-1.7-.5-1.7-1.2 0-.7.6-1.2 1.5-1.2.9 0 1.5.5 1.6 1.5-.2 0-.3 0-.5 0z"/>
                                </svg>
                            </a>
                        <a href="https://www.instagram.com/bizscoopmena?igsh=MWNqY3F6NWdoZTA0ZA==" style="color:#888;" onmouseover="this.style.color='#a78bfa'"
                            onmouseout="this.style.color='#888'" title="Instagram">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <rect x="2" y="2" width="20" height="20" rx="5" />
                                <circle cx="12" cy="12" r="4" />
                                <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════
        2. LOGO + AD BAR
        ═══════════════════════════════════ --}}
        <div id="site-logo-bar" style="background:#fff;border-bottom:1px solid #e0e0e0;">
            <div class="wrap flex justify-between items-center" style="padding-top:8px;padding-bottom:8px;">
                {{-- Left column: Logo --}}
                <div style="flex:1 1 0%; min-width:0; display:flex; align-items:center; justify-content:flex-start;">
                    <a href="{{ route('frontend.home') }}" title="{{ setting('site_name', 'Bizscoop') }}">
                        @if(setting('site_logo'))
                            <img src="{{ Storage::url(setting('site_logo')) }}"
                                alt="{{ setting('site_logo_alt', setting('site_name', 'Bizscoop') . ' Logo') }}"
                                title="{{ setting('site_logo_alt', setting('site_name', 'Bizscoop') . ' Logo') }}"
                                style="height:40px;width:auto;object-fit:contain;">
                        @else
                            <div style="line-height:1; display:flex; flex-direction:column; align-items:flex-start;">
                                <span
                                    style="font-family:'Merriweather',Georgia,serif;font-size:12px;font-weight:300;color:#555;letter-spacing:0.18em;text-transform:uppercase;margin-bottom:2px;display:block;">MENA</span>
                                <span
                                    style="font-family:'Merriweather',Georgia,serif;font-size:32px;font-weight:900;color:#000;letter-spacing:-1px;text-transform:uppercase;line-height:0.9;">BIZSCOOP</span>
                                <div
                                    style="font-size:8px;font-weight:700;text-transform:uppercase;letter-spacing:0.25em;color:#999;margin-top:5px;">
                                    High Integrity Business Journalism</div>
                            </div>
                        @endif
                    </a>
                </div>

                {{-- Center column: Ad Slot (Desktop only) --}}
                <div class="hidden lg:flex" style="flex:2 1 0%; min-width:0; justify-content:center; align-items:center;">
                    <div style="width:580px;height:75px;overflow:hidden;">
                        <x-ad-banner position="header" />
                    </div>
                </div>

                {{-- Right column: Subscribe Button --}}
                <div style="flex:1 1 0%; min-width:0; display:flex; align-items:center; justify-content:flex-end;">
                    <button @click="newsletterModalOpen = true" class="subscribe-btn" style="background:#000; color:#fff; border:1px solid #000; padding:10px 22px; font-weight:700; text-transform:uppercase; letter-spacing:0.12em; font-size:11px; cursor:pointer; border-radius:0px; transition: all 0.2s ease-in-out;" onmouseover="this.style.background='#fff'; this.style.color='#000'" onmouseout="this.style.background='#000'; this.style.color='#fff'">
                        Subscribe
                    </button>
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
                background: #000;
                position: sticky;
                top: 0;
                z-index: 9000; /* Keeping high for desktop but mobile menu will be higher */
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
            }

            /* Scroll Logo & Centering Styles */
            .nav-scroll-logo {
                opacity: 0;
                max-width: 0;
                overflow: hidden;
                display: none;
                align-items: center;
                transition: max-width 0.4s ease, opacity 0.4s ease, margin-right 0.4s ease;
                margin-right: 0;
            }

            @media (min-width: 901px) {
                .nav-scroll-logo {
                    display: flex !important;
                }
                #biz-nav.nav-scrolled .nav-scroll-logo {
                    opacity: 1;
                    max-width: 180px;
                    margin-right: 20px;
                }
                #biz-nav .nav-desktop {
                    justify-content: flex-start;
                    transition: justify-content 0.4s ease;
                }
                #biz-nav.nav-scrolled .nav-desktop {
                    justify-content: center;
                }
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
                font-size: 14px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.07em;
                color: rgba(255, 255, 255, 0.95);
                white-space: nowrap;
                border-right: 1px solid rgba(255, 255, 255, 0.12);
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
                background: rgba(0, 0, 0, 0.2);
                color: #fff;
            }

            .nav-link-home {
                font-weight: 900;
                border-right: 1px solid rgba(255, 255, 255, 0.15);
                padding-left: 0 !important;
            }
            #biz-nav.nav-scrolled .nav-link-home {
                padding-left: 14px !important;
            }

            /* ── CHEVRON ── */
            .nav-chevron {
                width: 9px;
                height: 9px;
                flex-shrink: 0;
                transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
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
                border-top: 3px solid #000;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18), 0 4px 12px rgba(0, 0, 0, 0.08);
                z-index: 9999;
                opacity: 0;
                transform: translateY(-8px);
                pointer-events: none;
                transition: opacity 0.22s cubic-bezier(0.4, 0, 0.2, 1),
                    transform 0.22s cubic-bezier(0.4, 0, 0.2, 1);
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

            .dropdown-item:last-child {
                border-bottom: none;
            }

            .dropdown-item:hover {
                background: #f5f5f5;
                color: #000;
                padding-left: 22px;
            }

            .dropdown-item::before {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                width: 3px;
                background: #000;
                transform: scaleY(0);
                transition: transform 0.18s ease;
            }

            .dropdown-item:hover::before {
                transform: scaleY(1);
            }

            .dropdown-item .di-arrow {
                width: 10px;
                height: 10px;
                opacity: 0;
                transform: translateX(-6px);
                transition: opacity 0.18s ease, transform 0.18s ease;
                color: #000;
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

            .nav-search-btn,
            .nav-hamburger {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 46px;
                background: rgba(0, 0, 0, 0.22);
                border: none;
                border-left: 1px solid rgba(255, 255, 255, 0.14);
                color: #fff;
                cursor: pointer;
                transition: background 0.18s ease;
            }

            .nav-search-btn:hover,
            .nav-hamburger:hover {
                background: rgba(0, 0, 0, 0.38);
            }

            .nav-hamburger {
                display: none;
            }

            /* ── MOBILE OVERLAY ── */
            .mobile-menu-overlay {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.55);
                z-index: 10000; /* Higher than biz-nav */
                backdrop-filter: blur(3px);
            }

            /* ── MOBILE PANEL ── */
            .mobile-menu-panel {
                display: flex;
                position: fixed;
                top: 0;
                left: 0;
                width: 300px;
                max-width: 85vw;
                height: 100%;
                background: #111;
                z-index: 10001; /* Higher than overlay */
                overflow-y: auto;
                flex-direction: column;
            }

            .mobile-panel-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 16px 20px;
                background: #000;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
                background: rgba(0, 0, 0, 0.25);
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

            .mobile-close-btn:hover {
                background: rgba(0, 0, 0, 0.45);
            }

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
            .mobile-nav-link.active {
                color: #fff;
                background: #1a1a1a;
            }

            .mobile-nav-link.home-link {
                color: #fff;
                border-left: 3px solid #000;
            }

            .mobile-chevron {
                width: 12px;
                height: 12px;
                transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
                color: #666;
                flex-shrink: 0;
            }

            .mobile-accordion.is-open .mobile-chevron {
                transform: rotate(180deg);
            }

            .mobile-submenu {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.32s cubic-bezier(0.4, 0, 0.2, 1);
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
                color: #000;
                font-size: 14px;
                font-weight: 900;
            }

            .mobile-sub-link:hover {
                color: #fff;
                background: #151515;
            }

            /* ── RESPONSIVE ── */
            @media (max-width: 900px) {
                .nav-desktop {
                    display: none !important;
                }

                .nav-inner {
                    position: relative; /* Setup relative positioning for absolute centering of logo */
                }

                /* Mobile default state: hide logo in nav strip */
                .nav-scroll-logo {
                    display: none !important;
                    opacity: 0 !important;
                    transition: opacity 0.3s ease;
                }

                .nav-scroll-logo img {
                    height: 28px !important; /* Slightly taller, extra height compared to default */
                    width: auto !important;
                    object-fit: contain !important;
                }

                .nav-right {
                    width: 100% !important;
                    display: flex !important;
                    justify-content: space-between !important;
                    align-items: stretch;
                }

                .nav-search-btn {
                    border-left: none !important;
                    border-right: 1px solid rgba(255, 255, 255, 0.14) !important;
                }

                /* Mobile Scrolled Sticky Header Layout (Logo in Center, Search Left, Hamburger Right) */
                #biz-nav.nav-scrolled .nav-scroll-logo {
                    display: flex !important;
                    position: absolute !important;
                    left: 50% !important;
                    top: 50% !important;
                    transform: translate(-50%, -50%) !important;
                    opacity: 1 !important;
                    max-width: 150px !important;
                    margin-right: 0 !important;
                    z-index: 10;
                }

                #biz-nav.nav-scrolled .nav-scroll-logo img {
                    height: 28px !important;
                }

                #biz-nav.nav-scrolled .nav-right {
                    width: 100% !important;
                    justify-content: space-between !important;
                }

                #biz-nav.nav-scrolled .nav-search-btn {
                    border-left: none !important;
                    border-right: 1px solid rgba(255, 255, 255, 0.14) !important;
                }
            }

            /* Mobile main content wrap padding override */
            @media (max-width: 768px) {
                main .wrap {
                    padding-bottom: 28px !important;
                }
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
                {{-- Scroll-only dynamic white logo --}}
                <div class="nav-scroll-logo">
                    <a href="{{ route('frontend.home') }}" title="{{ setting('site_name', 'Bizscoop') }}" style="display: flex; align-items: center;">
                        @if(setting('site_footer_logo'))
                            <img src="{{ Storage::url(setting('site_footer_logo')) }}"
                                alt="{{ setting('site_footer_logo_alt', setting('site_name', 'Bizscoop') . ' Logo') }}"
                                style="height: 24px; width: auto; object-fit: contain;">
                        @else
                            <img src="{{ Storage::url(setting('site_logo')) }}"
                                alt="{{ setting('site_logo_alt', setting('site_name', 'Bizscoop') . ' Logo') }}"
                                style="height: 24px; width: auto; object-fit: contain; filter: brightness(0) invert(1);">
                        @endif
                    </a>
                </div>

                {{-- ── DESKTOP MENU ── --}}
                <div class="nav-desktop" role="menubar">

                    {{-- Home --}}
                    @php $homeActive = request()->routeIs('frontend.home'); @endphp
                    <div class="nav-item">
                        <a href="{{ route('frontend.home') }}"
                            class="nav-link nav-link-home {{ $homeActive ? 'active' : '' }}" role="menuitem">Home</a>
                    </div>

                    {{-- Categories --}}
                    @foreach($headerCategories->take(7) as $cat)
                        @php
                            $catActive = request()->fullUrl() == route('frontend.category.show', $cat->slug);
                            $hasChildren = $cat->children->count() > 0;
                            $catName = $cat->getTranslation('name', app()->getLocale());
                        @endphp
                        <div class="nav-item {{ $hasChildren ? 'has-dropdown' : '' }}" role="none" data-nav-item>

                            <a href="{{ route('frontend.category.show', $cat->slug) }}"
                                class="nav-link {{ $catActive ? 'active' : '' }}" role="menuitem"
                                aria-haspopup="{{ $hasChildren ? 'true' : 'false' }}" aria-expanded="false">
                                {{ $catName }}
                                @if($hasChildren)
                                    <svg class="nav-chevron" fill="none" stroke="currentColor" stroke-width="3"
                                        viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            </a>

                            @if($hasChildren)
                                <div class="nav-dropdown" role="menu" aria-label="{{ $catName }} submenu">
                                    @foreach($cat->children as $child)
                                        <a href="{{ route('frontend.category.show', $child->slug) }}" class="dropdown-item"
                                            role="menuitem">
                                            <span>{{ $child->getTranslation('name', app()->getLocale()) }}</span>
                                            <svg class="di-arrow" fill="none" stroke="currentColor" stroke-width="2.5"
                                                viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- ── RIGHT CONTROLS ── --}}
                <div class="nav-right" style="display: flex; align-items: stretch;">
                    {{-- Search --}}
                    <button class="nav-search-btn" @click="searchOpen = true" aria-label="Open search">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="11" cy="11" r="8" />
                            <path d="M21 21l-4.35-4.35" />
                        </svg>
                    </button>

                    {{-- Group: Profile + Hamburger --}}
                    <div class="mobile-right-group" style="display: flex; align-items: stretch;">
                        {{-- Profile Dropdown --}}
                        @auth
                            <div class="nav-item has-dropdown" role="none" data-nav-item style="display: flex; align-items: stretch; position: relative;">
                                <button class="nav-link" aria-haspopup="true" aria-expanded="false" style="border-left: 1px solid rgba(255, 255, 255, 0.12); display: flex; align-items: center; justify-content: center; background: transparent; border-top: none; border-bottom: none; cursor: pointer; color: rgba(255, 255, 255, 0.95); width: 46px; padding: 0;">
                                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                </button>
                                <div class="nav-dropdown" role="menu" aria-label="User menu" style="right: 0; left: auto; min-width: 180px;">
                                    @if(auth()->user()->email === 'admin@bizscoop.com' || auth()->user()->hasRole('admin') || str_contains(auth()->user()->email, 'admin'))
                                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item" role="menuitem">
                                            <span>Admin Panel</span>
                                            <svg class="di-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true" style="width: 10px; height: 10px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @endif
                                    <a href="{{ route('frontend.profile.dashboard') }}" class="dropdown-item" role="menuitem">
                                        <span>Dashboard</span>
                                        <svg class="di-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true" style="width: 10px; height: 10px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                                        @csrf
                                    </form>
                                    <a href="#" class="dropdown-item" role="menuitem" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <span>Logout</span>
                                        <svg class="di-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true" style="width: 10px; height: 10px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="nav-link" aria-label="Login" style="border-left: 1px solid rgba(255, 255, 255, 0.12); display: flex; align-items: center; justify-content: center; width: 46px; padding: 0;">
                                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            </a>
                        @endauth

                        {{-- Hamburger --}}
                        @if($headerCategories->count() > 8)
                            <button class="nav-hamburger" id="nav-hamburger-btn" aria-label="Open menu" aria-expanded="false"
                                aria-controls="mobile-menu-panel"
                                @click="mobileMenuOpen = true"
                                style="border-left: 1px solid rgba(255, 255, 255, 0.14); display: flex;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" d="M3 6h18M3 12h18M3 18h18" />
                                </svg>
                            </button>
                        @else
                            <button class="nav-hamburger lg:hidden" id="nav-hamburger-btn" aria-label="Open menu" aria-expanded="false"
                                aria-controls="mobile-menu-panel"
                                @click="mobileMenuOpen = true"
                                style="border-left: 1px solid rgba(255, 255, 255, 0.14); display: flex;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" d="M3 6h18M3 12h18M3 18h18" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>

            </div>
        </nav>

        {{-- ── MOBILE OVERLAY ── --}}
        <div class="mobile-menu-overlay" 
             id="mobile-overlay" 
             aria-hidden="true"
             x-show="mobileMenuOpen"
             x-transition:enter="transition opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition opacity ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileMenuOpen = false"
             x-cloak></div>

        {{-- ── MOBILE PANEL ── --}}
        <div class="mobile-menu-panel" 
             id="mobile-menu-panel" 
             role="dialog" 
             aria-modal="true"
             aria-label="Mobile Navigation"
             x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             x-cloak>

            <div class="mobile-panel-header">
                @if(setting('site_logo'))
                    <img src="{{ Storage::url(setting('site_logo')) }}"
                        alt="{{ setting('site_logo_alt', setting('site_name', 'Bizscoop') . ' Logo') }}"
                        style="height:32px;width:auto;object-fit:contain;">
                @else
                    <div class="mobile-panel-logo" style="font-family:'Merriweather',serif;font-style:normal;letter-spacing:0.05em;line-height:1.2;">
                        <span style="font-weight:300;font-size:10px;display:block;color:#aaa;text-transform:uppercase;letter-spacing:0.18em;margin-bottom:2px;">MENA</span>
                        <span style="font-weight:900;font-size:18px;text-transform:uppercase;color:#fff;">BIZSCOOP</span>
                    </div>
                @endif
                <button class="mobile-close-btn" id="mobile-close-btn" aria-label="Close menu" @click="mobileMenuOpen = false">✕</button>
            </div>

            <nav class="mobile-nav-list">
                <a href="{{ route('frontend.home') }}"
                    class="mobile-nav-link home-link {{ request()->routeIs('frontend.home') ? 'active' : '' }}">
                    Home
                </a>

                @foreach($headerCategories as $cat)
                    @php
                        $catName = $cat->getTranslation('name', app()->getLocale());
                        $hasChildren = $cat->children->count() > 0;
                        $catActive = request()->fullUrl() == route('frontend.category.show', $cat->slug);
                    @endphp

                    @if($hasChildren)
                        <div class="mobile-accordion" data-accordion>
                            <button class="mobile-nav-link {{ $catActive ? 'active' : '' }}" data-accordion-trigger
                                aria-expanded="false" aria-label="Toggle {{ $catName }}">
                                <span>{{ $catName }}</span>
                                <svg class="mobile-chevron" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="mobile-submenu" role="menu">
                                <a href="{{ route('frontend.category.show', $cat->slug) }}" class="mobile-sub-link"
                                    role="menuitem">All {{ $catName }}</a>
                                @foreach($cat->children as $child)
                                    <a href="{{ route('frontend.category.show', $child->slug) }}" class="mobile-sub-link"
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

                @auth
                    <a href="{{ route('frontend.profile.dashboard') }}" class="mobile-nav-link" style="border-top: 1px solid #1e1e1e; font-weight: bold; color: #fff;">
                        Dashboard ({{ auth()->user()->name }})
                    </a>
                    <a href="#" class="mobile-nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #fca5a5;">
                        Logout
                    </a>
                @else
                    <a href="{{ route('login') }}" class="mobile-nav-link" style="border-top: 1px solid #1e1e1e; font-weight: bold; color: #fff;">
                        Login / Sign Up
                    </a>
                @endauth
            </nav>

            {{-- Mobile bottom --}}
            <div style="padding:20px;border-top:1px solid #1e1e1e;flex-shrink:0;">
                <form action="{{ route('frontend.search') }}" method="GET" style="display:flex;align-items:stretch;height:44px;border:1px solid #333;border-radius:0px;overflow:hidden;background:#222;width:100%;">
                    <input name="q" type="text" placeholder="Search articles…"
                        style="flex:1;background:#222;border:none;color:#fff;font-size:13px;padding:0 14px;outline:none;height:100%;">
                    <button type="submit"
                        style="background:#000;color:#fff;border:none;padding:0 18px;font-size:10px;font-weight:900;text-transform:uppercase;cursor:pointer;height:100%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        Go
                    </button>
                </form>
            </div>
        </div>

        {{-- ── NAV JAVASCRIPT ── --}}
        <script>
            (function () {
                'use strict';

                // ── Desktop dropdown hover logic ──
                const navItems = document.querySelectorAll('[data-nav-item]');

                navItems.forEach(item => {
                    const link = item.querySelector('.nav-link');
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
                const hamburger = document.getElementById('nav-hamburger-btn');
                const closeBtn = document.getElementById('mobile-close-btn');
                const overlay = document.getElementById('mobile-overlay');
                const panel = document.getElementById('mobile-menu-panel');

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

                // ── Sticky navbar scroll state (IntersectionObserver-based) ──
                const nav = document.getElementById('biz-nav');
                const logoBar = document.getElementById('site-logo-bar');
                if (nav) {
                    if (logoBar && typeof IntersectionObserver !== 'undefined') {
                        // Show logo in nav only when the white logo bar has scrolled out of view
                        const logoObserver = new IntersectionObserver(function(entries) {
                            if (!entries[0].isIntersecting) {
                                nav.classList.add('nav-scrolled');
                            } else {
                                nav.classList.remove('nav-scrolled');
                            }
                        }, { threshold: 0, rootMargin: '0px' });
                        logoObserver.observe(logoBar);
                    } else {
                        // Fallback for older browsers
                        const toggleNavScroll = () => {
                            if (window.scrollY > 150) {
                                nav.classList.add('nav-scrolled');
                            } else {
                                nav.classList.remove('nav-scrolled');
                            }
                        };
                        window.addEventListener('scroll', toggleNavScroll, { passive: true });
                        toggleNavScroll();
                    }
                }

                // ── Real-time weather widget ──
                const weatherWidget = document.getElementById('weather-widget');
                if (weatherWidget) {
                    fetch('https://ipapi.co/json/')
                        .then(response => {
                            if (!response.ok) throw new Error('IP lookup failed');
                            return response.json();
                        })
                        .then(geo => {
                            const lat = geo.latitude;
                            const lon = geo.longitude;
                            const city = geo.city || 'Dubai';
                            
                            return fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true`)
                                .then(res => {
                                    if (!res.ok) throw new Error('Weather lookup failed');
                                    return res.json();
                                })
                                .then(weather => {
                                    if (weather && weather.current_weather) {
                                        const temp = Math.round(weather.current_weather.temperature);
                                        const code = weather.current_weather.weathercode;
                                        
                                        // Determine emoji based on WMO weather interpretation codes
                                        let emoji = '☀️';
                                        if (code >= 1 && code <= 3) {
                                            emoji = '🌤️';
                                        } else if (code === 45 || code === 48) {
                                            emoji = '🌫️';
                                        } else if ((code >= 51 && code <= 67) || (code >= 80 && code <= 82)) {
                                            emoji = '🌧️';
                                        } else if ((code >= 71 && code <= 77) || (code >= 85 && code <= 86)) {
                                            emoji = '❄️';
                                        } else if (code >= 95) {
                                            emoji = '⛈️';
                                        }
                                        
                                        weatherWidget.innerHTML = `${emoji} ${city} &nbsp; ${temp}°C`;
                                    }
                                });
                        })
                        .catch(err => {
                            console.warn('Real-time weather fetch failed, keeping default location:', err);
                        });
                }

            })();
        </script>

        {{-- ═══════════════════════════════════
        4. PREMIUM MARKET TICKER SUB-NAV
        ═══════════════════════════════════ --}}
        @php
            $marketTickerEnabled = setting('market_ticker_enabled', '1') === '1';
            $defaultTab = setting('market_ticker_default_tab', 'markets');
            $initialQuotes = [];
            if ($marketTickerEnabled) {
                try {
                    $initialQuotes = resolve(\App\Services\MarketCacheService::class)->getTabQuotes($defaultTab);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Error loading initial market quotes: ' . $e->getMessage());
                }
            }
        @endphp

        @if($marketTickerEnabled && request()->routeIs('frontend.home'))
        <div style="background:#111111; border-bottom:1px solid #222222; color:#ffffff; font-family:'Inter', sans-serif;" 
             x-data="{
                 activeTab: '{{ $defaultTab }}',
                 quotes: {{ json_encode($initialQuotes) }},
                 loading: false,
                 paused: false,
                 async changeTab(tab) {
                     if (this.activeTab === tab || this.loading) return;
                     this.loading = true;
                     this.activeTab = tab;
                     try {
                         const response = await fetch('{{ route('frontend.market.ticker') }}?tab=' + tab);
                         const result = await response.json();
                         if (result.success) {
                             this.quotes = result.data;
                         }
                     } catch (e) {
                         console.error('Failed to fetch market quotes:', e);
                     } finally {
                         this.loading = false;
                     }
                 }
             }">
            
            <style>
                @keyframes market-ticker-scroll {
                    0% { transform: translateX(0); }
                    100% { transform: translateX(-50%); }
                }
                .market-ticker-track {
                    display: flex;
                    animation: market-ticker-scroll 35s linear infinite;
                }
                .market-ticker-track.paused {
                    animation-play-state: paused !important;
                }
                @keyframes pulse-light {
                    0%, 100% { opacity: 0.6; }
                    50% { opacity: 0.25; }
                }
                .pulse-anim {
                    animation: pulse-light 1.5s infinite;
                }
                
                @media (max-width: 900px) {
                    .market-ticker-bar {
                        flex-direction: column !important;
                        height: auto !important;
                        border: 1px solid #ffffffff !important;
                    }
                    .market-ticker-tabs {
                        width: 100% !important;
                        border-right: none !important;
                        border-bottom: 1px solid #222222 !important;
                        justify-content: center !important;
                        padding-right: 0 !important;
                        height: 38px !important;
                        gap: 10px !important;
                    }
                    .market-ticker-track-wrapper {
                        padding: 8px 12px !important;
                        overflow-x: auto !important;
                        -webkit-overflow-scrolling: touch !important;
                    }
                    .market-ticker-track {
                        gap: 25px !important;
                    }
                }
            </style>

            <div class="wrap flex items-stretch market-ticker-bar" style="height:44px; overflow:hidden; border:1px solid #242424; border-left:none; border-right:none; border-bottom:none;">
                {{-- Category Navigation Tabs --}}
                <div class="flex items-center market-ticker-tabs" style="background:#000000; border-right:1px solid #222222; padding-right:10px; flex-shrink:0; gap:10px;">
                    <button @click="changeTab('markets')" :style="activeTab === 'markets' ? 'color:#ffffff; border-bottom:2px solid #ffffff;' : 'color:#888888; border-bottom:2px solid transparent;'" style="background:none; border:none; padding:0 12px 0 0; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; cursor:pointer; transition:all 0.2s; height:100%; display:flex; align-items:center;">Markets</button>
                    <button @click="changeTab('forex')" :style="activeTab === 'forex' ? 'color:#ffffff; border-bottom:2px solid #ffffff;' : 'color:#888888; border-bottom:2px solid transparent;'" style="background:none; border:none; padding:0 12px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; cursor:pointer; transition:all 0.2s; height:100%; display:flex; align-items:center;">Forex</button>
                    <button @click="changeTab('commodities')" :style="activeTab === 'commodities' ? 'color:#ffffff; border-bottom:2px solid #ffffff;' : 'color:#888888; border-bottom:2px solid transparent;'" style="background:none; border:none; padding:0 12px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; cursor:pointer; transition:all 0.2s; height:100%; display:flex; align-items:center;">Commodities</button>
                    <button @click="changeTab('crypto')" :style="activeTab === 'crypto' ? 'color:#ffffff; border-bottom:2px solid #ffffff;' : 'color:#888888; border-bottom:2px solid transparent;'" style="background:none; border:none; padding:0 12px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; cursor:pointer; transition:all 0.2s; height:100%; display:flex; align-items:center;">Crypto</button>
                </div>

                {{-- Scrolling Ticker Stream --}}
                <div class="flex-grow flex items-center market-ticker-track-wrapper" style="position:relative; overflow:hidden; padding-left:15px;" @mouseover="paused = true" @mouseout="paused = false">
                    {{-- Skeleton Loading view --}}
                    <div x-show="loading" style="display:flex; gap:30px; width:100%; align-items:center;" class="pulse-anim">
                        <div style="display:flex; gap:10px; align-items:center; flex-shrink:0;">
                            <div style="width:50px; height:10px; background:#2a2a2a; border-radius:2px;"></div>
                            <div style="width:70px; height:10px; background:#2a2a2a; border-radius:2px;"></div>
                            <div style="width:45px; height:10px; background:#2a2a2a; border-radius:2px;"></div>
                        </div>
                        <div style="display:flex; gap:10px; align-items:center; flex-shrink:0;" class="hidden md:flex">
                            <div style="width:50px; height:10px; background:#2a2a2a; border-radius:2px;"></div>
                            <div style="width:70px; height:10px; background:#2a2a2a; border-radius:2px;"></div>
                            <div style="width:45px; height:10px; background:#2a2a2a; border-radius:2px;"></div>
                        </div>
                        <div style="display:flex; gap:10px; align-items:center; flex-shrink:0;" class="hidden lg:flex">
                            <div style="width:50px; height:10px; background:#2a2a2a; border-radius:2px;"></div>
                            <div style="width:70px; height:10px; background:#2a2a2a; border-radius:2px;"></div>
                            <div style="width:45px; height:10px; background:#2a2a2a; border-radius:2px;"></div>
                        </div>
                    </div>

                    {{-- Dynamic Quotes List --}}
                    <div x-show="!loading" class="market-ticker-track" :class="paused ? 'paused' : ''" style="display:flex; white-space:nowrap; gap:40px; will-change: transform;">
                        {{-- First Loop --}}
                        <template x-for="item in quotes" :key="item.symbol">
                            <div style="display:inline-flex; align-items:center; gap:8px; font-size:11px; font-weight:700;">
                                <span style="color:#888888;" x-text="item.symbol"></span>
                                <span style="color:#ffffff;" x-text="item.name"></span>
                                <span style="color:#ffffff; font-family:monospace;" x-text="item.price"></span>
                                <span :style="item.is_gain ? 'color:#22c55e;' : 'color:#ef4444;'" style="display:inline-flex; align-items:center; gap:3px;">
                                    <span x-text="item.is_gain ? '▲' : '▼'"></span>
                                    <span x-text="item.change"></span>
                                    <span style="font-size:10px;" x-text="'(' + item.percent_change + ')'"></span>
                                </span>
                            </div>
                        </template>

                        {{-- Second Loop (Seamless infinite scroll anchor) --}}
                        <template x-for="item in quotes" :key="item.symbol + '_dup'">
                            <div style="display:inline-flex; align-items:center; gap:8px; font-size:11px; font-weight:700;">
                                <span style="color:#888888;" x-text="item.symbol"></span>
                                <span style="color:#ffffff;" x-text="item.name"></span>
                                <span style="color:#ffffff; font-family:monospace;" x-text="item.price"></span>
                                <span :style="item.is_gain ? 'color:#22c55e;' : 'color:#ef4444;'" style="display:inline-flex; align-items:center; gap:3px;">
                                    <span x-text="item.is_gain ? '▲' : '▼'"></span>
                                    <span x-text="item.change"></span>
                                    <span style="font-size:10px;" x-text="'(' + item.percent_change + ')'"></span>
                                </span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ═══════════════════════════════════
        5. BREAKING NEWS TICKER
        ═══════════════════════════════════ --}}
        <div style="background:#f5f5f5;border-bottom:1px solid #e0e0e0;padding:6px 0;">
            <div class="wrap" style="display:flex;align-items:center;gap:0;">
                {{-- Red Label --}}
                <div
                    style="background:#000;color:#fff;font-size:9px;font-weight:900;text-transform:uppercase;letter-spacing:0.15em;padding:6px 14px;white-space:nowrap;flex-shrink:0;line-height:1.4;">
                    📡 News Updates
                </div>
                {{-- Divider arrow --}}
                <div
                    style="width:0;height:0;border-top:14px solid transparent;border-bottom:14px solid transparent;border-left:10px solid #000;flex-shrink:0;">
                </div>
                {{-- Scrolling area --}}
                <div class="ticker-wrap" style="flex:1;overflow:hidden;position:relative;">
                    <div class="ticker-track"
                        style="display:inline-block;white-space:nowrap;will-change:transform;animation:ticker-scroll 38s linear infinite;padding-left:20px;">
                        @foreach($breakingNews as $news)
                            <span style="display:inline-block;">
                                <a href="{{ route('frontend.article.show', $news->slug) }}"
                                    style="font-size:11px;font-weight:600;color:#333;margin-right:50px;display:inline-block;"
                                    onmouseover="this.style.color='#000'" onmouseout="this.style.color='#333'">
                                    <span
                                        style="color:#000;margin-right:5px;font-weight:900;">●</span>{{ $news->translate()->title }}
                                </a>
                            </span>
                        @endforeach
                        {{-- Duplicate for seamless loop --}}
                        @foreach($breakingNews as $news)
                            <span style="display:inline-block;">
                                <a href="{{ route('frontend.article.show', $news->slug) }}"
                                    style="font-size:11px;font-weight:600;color:#333;margin-right:50px;display:inline-block;"
                                    onmouseover="this.style.color='#000'" onmouseout="this.style.color='#333'">
                                    <span
                                        style="color:#000;margin-right:5px;font-weight:900;">●</span>{{ $news->translate()->title }}
                                </a>
                            </span>
                        @endforeach
                    </div>
                </div>
                {{-- Timestamp --}}
                <div
                    style="background:#333;color:#aaa;font-size:8px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;padding:6px 12px;white-space:nowrap;flex-shrink:0;border-left:1px solid #444;">
                    {{ now()->format('H:i') }} UTC
                </div>
            </div>
        </div>

        {{-- Floating Social Bar --}}
        <div style="position:fixed;right:0;top:50%;transform:translateY(-50%);z-index:200;display:flex;flex-direction:column; display:none;"
            class="hidden xl:flex">
            <a href="#"
                style="width:36px;height:36px;background:#3b5998;color:#fff;font-size:11px;font-weight:900;display:flex;align-items:center;justify-content:center;border-bottom:1px solid rgba(255,255,255,0.2);">f</a>
            <a href="#"
                style="width:36px;height:36px;background:#1da1f2;color:#fff;font-size:11px;font-weight:900;display:flex;align-items:center;justify-content:center;border-bottom:1px solid rgba(255,255,255,0.2);">t</a>
            <a href="#"
                style="width:36px;height:36px;background:#dd4b39;color:#fff;font-size:10px;font-weight:900;display:flex;align-items:center;justify-content:center;border-bottom:1px solid rgba(255,255,255,0.2);">g+</a>
            <a href="#"
                style="width:36px;height:36px;background:#ff6600;color:#fff;font-size:9px;font-weight:900;display:flex;align-items:center;justify-content:center;border-bottom:1px solid rgba(255,255,255,0.2);">rss</a>
            <a href="#"
                style="width:36px;height:36px;background:#00aabb;color:#fff;font-size:9px;font-weight:900;display:flex;align-items:center;justify-content:center;border-bottom:1px solid rgba(255,255,255,0.2);">vm</a>
            <a href="#"
                style="width:36px;height:36px;background:#ff0000;color:#fff;font-size:12px;font-weight:900;display:flex;align-items:center;justify-content:center;">▶</a>
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
        <footer style="background:#000;color:#fff;margin-top:20px;font-family:'Inter',sans-serif;">
            {{-- Newsletter Bar --}}
            <div style="background:#000;border-bottom:1px solid #222;padding:24px 0;">
                <div class="wrap flex flex-col md:flex-row justify-between items-center gap-4">
                    <div style="font-size:20px;font-weight:700;letter-spacing:-0.02em;color:#fff;">
                        Subscribe Newsletter
                    </div>
                    <div style="width:100%;max-width:450px;">
                        <form @submit.prevent="submitNewsletter" style="display:flex;gap:0;background:#fff;padding:2px;">
                            <input type="email" x-model="newsletterEmail" required placeholder="Enter your email address"
                                style="flex:1;background:#fff;border:none;color:#000;font-size:13px;padding:12px 16px;outline:none;">
                            <button type="submit" :disabled="newsletterLoading"
                                style="background:#3e3e3e;color:#fff;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;padding:0 24px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:5px;transition:background 0.2s;"
                                onmouseover="this.style.background='#000'"
                                onmouseout="this.style.background='#3e3e3e'">
                                <svg x-show="newsletterLoading" width="10" height="10" viewBox="0 0 24 24"
                                    style="animation: spin 1s linear infinite;margin-right:4px;">
                                    <path fill="currentColor" d="M12 4V2A10 10 0 0 0 2 12h2a8 8 0 0 1 8-8Z" />
                                </svg>
                                <span x-text="newsletterLoading ? '...' : 'SUBMIT'"></span>
                            </button>
                        </form>
                        <template x-if="newsletterMessage">
                            <div style="font-size:11px;font-weight:600;color:#3b82f6;margin-top:8px;" x-text="newsletterMessage"></div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Main Content Bar --}}
            <div class="wrap flex flex-col items-center" style="padding:40px 0 32px 0;">
                {{-- Logo --}}
                <div style="margin-bottom:28px;">
                    @if(setting('site_footer_logo'))
                        <img src="{{ Storage::url(setting('site_footer_logo')) }}"
                            alt="{{ setting('site_footer_logo_alt', setting('site_name', 'Bizscoop') . ' Footer Logo') }}"
                            style="height:36px;width:auto;object-fit:contain;">
                    @else
                        <img src="{{ Storage::url(setting('site_logo')) }}"
                            alt="{{ setting('site_logo_alt', setting('site_name', 'Bizscoop') . ' Logo') }}"
                            style="height:36px;width:auto;object-fit:contain;filter:brightness(0) invert(1);">
                    @endif
                </div>

                {{-- Center Navigation Links --}}
                <div class="flex flex-wrap justify-center gap-x-8 gap-y-3" style="margin-bottom:28px;font-family:'Inter',sans-serif;">
                    <a href="{{ route('frontend.home') }}" 
                       style="font-size:12px;font-weight:700;color:#fff;text-transform:uppercase;letter-spacing:0.08em;transition:color 0.2s;"
                       onmouseover="this.style.color='#aaa'"
                       onmouseout="this.style.color='#fff'">Home</a>
                    @foreach($headerCategories as $cat)
                        <a href="{{ route('frontend.category.show', $cat->slug) }}" 
                           style="font-size:12px;font-weight:700;color:#fff;text-transform:uppercase;letter-spacing:0.08em;transition:color 0.2s;"
                           onmouseover="this.style.color='#aaa'"
                           onmouseout="this.style.color='#fff'">
                            {{ $cat->getTranslation('name', app()->getLocale()) }}
                        </a>
                    @endforeach
                </div>

                {{-- Social Icons --}}
                <div class="flex justify-center items-center gap-6" style="margin-bottom:32px;">                    
                    <a href="https://www.linkedin.com/company/bizscoop-mena/" target="_blank" style="color:#fff;transition:opacity 0.2s;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </a>
                    <a href="https://www.instagram.com/bizscoopmena?igsh=MWNqY3F6NWdoZTA0ZA==" target="_blank" style="color:#fff;transition:opacity 0.2s;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="https://www.facebook.com/share/1Ay66dEyNf/" target="_blank" style="color:#fff;transition:opacity 0.2s;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                    </a>
                    <a href="https://www.threads.com/@bizscoopmena" target="_blank" style="color:#fff;transition:opacity 0.2s;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M16.3 11.2c-.2-.1-.4-.2-.6-.3-.1-2.2-1.3-3.5-3.4-3.5-2 0-3.4 1.2-3.4 3 0 1.7 1.2 2.8 3.1 3 .4.1.8.1 1.2.2.1.4.1.8.1 1.2 0 1.5-.7 2.3-2.1 2.3-1.2 0-2-.7-2.2-1.9H6.8c.2 2.3 1.9 3.8 4.4 3.8 2.8 0 4.5-1.7 4.5-4.5 0-.4 0-.8-.1-1.2 1.1.3 1.7.9 1.7 1.8 0 1.2-1.1 2.1-2.9 2.3v1.8c3-.1 5-1.8 5-4.2 0-1.8-1.1-3.2-3.1-3.8zm-3.1.5c-.3 0-.6-.1-.9-.1-1.1-.1-1.7-.5-1.7-1.2 0-.7.6-1.2 1.5-1.2.9 0 1.5.5 1.6 1.5-.2 0-.3 0-.5 0z"/></svg>
                    </a>
                    <a href="https://news.google.com" target="_blank" style="color:#fff;transition:opacity 0.2s;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M22 2H2C.9 2 2 .9 2 2v20c0 1.1.9 2 2 2h20c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM8.5 17.5c-2.5 0-4.5-2-4.5-4.5s2-4.5 4.5-4.5c1.2 0 2.3.4 3.1 1.2l-1.3 1.3c-.4-.4-1.1-.8-1.8-.8-1.5 0-2.8 1.2-2.8 2.8s1.3 2.8 2.8 2.8c1.7 0 2.4-1.2 2.5-1.8H8.5v-2.3h4.9c.1.3.1.6.1 1 0 3.1-2.1 5.3-5 5.3zm11-4.2h-2.5v2.5h-1.6v-2.5h-2.5v-1.6h2.5V9.2h1.6v2.5h2.5v1.6z"/></svg>
                    </a>
                </div>

                {{-- Copyright Notice --}}
                <div style="font-size:11px;color:#555;margin-bottom:14px;letter-spacing:0.05em;text-align:center;">
                    © {{ date('Y') }} {{ setting('site_name', 'Bizscoop') }}. Published by Bizscoop MENA. All Rights Reserved.
                </div>

                {{-- Policy/Utility Links --}}
                <div class="flex flex-wrap justify-center gap-x-6 gap-y-2" style="font-size:11px;color:#777;">
                    <a href="{{ route('frontend.pages.about') }}" style="transition:color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#777'">About us</a>
                    <a href="{{ route('frontend.pages.privacy') }}" style="transition:color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#777'">Privacy Policy</a>
                    <a href="{{ route('frontend.pages.editorial') }}" style="transition:color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#777'">Editorial Standards</a>
                    <a href="{{ route('frontend.pages.contact') }}" style="transition:color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#777'">Contact Us</a>
                </div>
            </div>
        </footer>

    </div>{{-- /#app --}}

    {{-- Search Overlay --}}
    <div x-show="searchOpen" x-transition:enter="transition duration-150" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        style="position:fixed;inset:0;background:rgba(0,0,0,0.75);z-index:99999;display:flex;align-items:flex-start;justify-content:center;padding-top:80px;"
        @click.self="searchOpen = false"
        x-effect="if (searchOpen) { $nextTick(() => { $refs.searchInput.focus(); }) }"
        x-cloak>
        <div
            style="background:#fff;width:100%;max-width:640px;margin:0 16px;padding:24px;box-shadow:0 20px 60px rgba(0,0,0,0.4);">
            <form action="{{ route('frontend.search') }}" method="GET" style="display:flex;align-items:stretch;height:52px;border:2.5px solid #000;border-radius:0px;overflow:hidden;background:#fff;width:100%;">
                <input name="q" type="text" x-ref="searchInput" placeholder="Search articles, topics, people…"
                    style="flex:1;border:none;padding:0 16px;font-size:15px;font-weight:600;outline:none;color:#111;height:100%;background:#fff;">
                <button type="submit"
                    style="background:#000;color:#fff;font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:0.1em;padding:0 24px;border:none;cursor:pointer;height:100%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    Search
                </button>
            </form>
            <button @click="searchOpen = false"
                style="margin-top:16px;font-size:10px;color:#777;text-transform:uppercase;letter-spacing:0.15em;background:none;border:none;cursor:pointer;font-weight:800;display:flex;align-items:center;gap:4px;"
                onmouseover="this.style.color='#000'" onmouseout="this.style.color='#777'">
                ✕ Close
            </button>
        </div>
    </div>

    {{-- Service Modal --}}
    <div x-show="serviceModalOpen" x-transition:enter="transition duration-300 ease-out"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="position:fixed !important;top:0 !important;left:0 !important;width:100% !important;height:100% !important;background:rgba(0,0,0,0.85) !important;z-index:99999 !important;display:grid !important;place-items:center !important;padding:20px;backdrop-filter:blur(10px);"
        @click.self="serviceModalOpen = false" x-cloak>

        <div style="background:#fff;width:100%;max-width:550px;border-radius:16px;overflow:hidden;box-shadow:0 40px 120px rgba(0,0,0,0.6);position:relative;margin:auto;"
            x-show="serviceModalOpen"
            x-transition:enter="transition duration-500 cubic-bezier(0.175, 0.885, 0.32, 1.275)"
            x-transition:enter-start="scale-95 opacity-0 translate-y-10"
            x-transition:enter-end="scale-100 opacity-100 translate-y-0">

            {{-- Modal Header --}}
            <div style="background:#111;padding:30px 40px;position:relative;">
                <div
                    style="font-size:10px;font-weight:900;text-transform:uppercase;color:#000;letter-spacing:0.25em;margin-bottom:8px;">
                    Bizscoop Enterprise</div>
                <h3 x-text="serviceFormOpen ? 'Service Inquiry' : serviceModalTitle"
                    style="font-family:'Merriweather',serif;font-size:28px;font-weight:900;color:#fff;margin:0;letter-spacing:-0.02em;">
                </h3>
                <button @click="serviceModalOpen = false"
                    style="position:absolute;top:25px;right:25px;background:none;border:none;color:#666;font-size:24px;cursor:pointer;transition:color 0.2s;"
                    onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#666'">✕</button>
            </div>

            {{-- Modal Body: Information --}}
            <div x-show="!serviceFormOpen" style="padding:45px 40px 35px 40px;">
                <div x-text="serviceModalContent" style="font-size:17px;line-height:1.8;color:#444;margin-bottom:40px;">
                </div>

                <div style="display:flex;gap:15px;">
                    <button @click="serviceFormOpen = true"
                        style="flex:1;background:#000;color:#fff;text-align:center;padding:18px;font-size:14px;font-weight:900;text-transform:uppercase;border:none;cursor:pointer;border-radius:6px;transition:all 0.3s;box-shadow:0 12px 24px rgba(0,0,0,0.25);"
                        onmouseover="this.style.background='#333';this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.background='#000';this.style.transform='translateY(0)'">
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
                            <label
                                style="display:block;font-size:11px;font-weight:900;text-transform:uppercase;color:#999;margin-bottom:8px;letter-spacing:0.05em;">Full
                                Name</label>
                            <input type="text" x-model="serviceForm.name" required placeholder="John Doe"
                                style="width:100%;padding:14px;border:1px solid #eee;border-radius:6px;outline:none;font-size:15px;background:#fafafa;transition:border-color 0.3s;"
                                onfocus="this.style.borderColor='#000';this.style.background='#fff'"
                                onblur="this.style.borderColor='#eee';this.style.background='#fafafa'">
                            <template x-if="serviceErrors.name">
                                <span x-text="serviceErrors.name[0]"
                                    style="color:#000;font-size:10px;font-weight:bold;margin-top:4px;display:block;"></span>
                            </template>
                        </div>
                        <div>
                            <label
                                style="display:block;font-size:11px;font-weight:900;text-transform:uppercase;color:#999;margin-bottom:8px;letter-spacing:0.05em;">Work
                                Email</label>
                            <input type="email" x-model="serviceForm.email" required placeholder="john@company.com"
                                style="width:100%;padding:14px;border:1px solid #eee;border-radius:6px;outline:none;font-size:15px;background:#fafafa;transition:border-color 0.3s;"
                                onfocus="this.style.borderColor='#000';this.style.background='#fff'"
                                onblur="this.style.borderColor='#eee';this.style.background='#fafafa'">
                            <template x-if="serviceErrors.email">
                                <span x-text="serviceErrors.email[0]"
                                    style="color:#000;font-size:10px;font-weight:bold;margin-top:4px;display:block;"></span>
                            </template>
                        </div>
                        <div>
                            <label
                                style="display:block;font-size:11px;font-weight:900;text-transform:uppercase;color:#999;margin-bottom:8px;letter-spacing:0.05em;">Inquiry
                                Details</label>
                            <textarea x-model="serviceForm.message" required
                                placeholder="How can we help your business thrive?" rows="4"
                                style="width:100%;padding:14px;border:1px solid #eee;border-radius:6px;outline:none;font-size:15px;background:#fafafa;resize:none;transition:border-color 0.3s;"
                                onfocus="this.style.borderColor='#000';this.style.background='#fff'"
                                onblur="this.style.borderColor='#eee';this.style.background='#fafafa'"></textarea>
                            <template x-if="serviceErrors.message">
                                <span x-text="serviceErrors.message[0]"
                                    style="color:#000;font-size:10px;font-weight:bold;margin-top:4px;display:block;"></span>
                            </template>
                        </div>
                        <div style="display:flex;gap:12px;margin-top:10px;">
                            <button type="submit" :disabled="serviceLoading"
                                style="flex:1;background:#111;color:#fff;padding:18px;font-weight:900;text-transform:uppercase;font-size:13px;border:none;border-radius:6px;cursor:pointer;transition:all 0.3s;display:flex;align-items:center;justify-content:center;gap:10px;"
                                onmouseover="this.style.background='#000'" onmouseout="this.style.background='#111'">
                                <svg x-show="serviceLoading" width="16" height="16" viewBox="0 0 24 24"
                                    style="animation: spin 1s linear infinite;">
                                    <path fill="currentColor" d="M12 4V2A10 10 0 0 0 2 12h2a8 8 0 0 1 8-8Z" />
                                </svg>
                                <span x-text="serviceLoading ? 'Sending...' : 'Send Inquiry'"></span>
                            </button>
                            <button type="button" @click="serviceFormOpen = false" :disabled="serviceLoading"
                                style="padding:0 25px;background:#f5f5f5;color:#999;font-weight:900;text-transform:uppercase;font-size:12px;border:none;border-radius:6px;cursor:pointer;">
                                Back
                            </button>
                        </div>
                    </form>
                </template>

                {{-- Success Message --}}
                <template x-if="serviceSent">
                    <div style="text-align:center;padding:40px 0;">
                        <div
                            style="width:80px;height:80px;background:#00015;color:#000;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 25px;">
                            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="3"
                                viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h4
                            style="font-family:'Merriweather',serif;font-size:26px;font-weight:900;color:#111;margin-bottom:12px;">
                            Inquiry Received</h4>
                        <p style="color:#666;line-height:1.6;margin-bottom:30px;">Thank you for reaching out. A Bizscoop
                            strategist will contact you within 24 business hours.</p>
                        <button @click="serviceModalOpen = false"
                            style="background:#111;color:#fff;padding:14px 40px;font-weight:900;text-transform:uppercase;font-size:12px;border:none;border-radius:6px;cursor:pointer;">Got
                            it</button>
                    </div>
                </template>
            </div>

            {{-- Modal Footer --}}
            <div
                style="padding:22px 40px;background:#fafafa;border-top:1px solid #f0f0f0;display:flex;align-items:center;justify-content:center;gap:12px;">
                <div
                    style="width:8px;height:8px;background:#000;border-radius:50%;box-shadow:0 0 8px rgba(0,0,0,0.5);">
                </div>
                <span
                    style="font-size:10px;font-weight:800;color:#bbb;text-transform:uppercase;letter-spacing:0.15em;">High-Integrity
                    Professional Network</span>
            </div>
        </div>
    </div>

    {{-- Dedicated Newsletter Subscription Modal --}}
    <div x-show="newsletterModalOpen" x-transition:enter="transition duration-300 ease-out"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="position:fixed !important;top:0 !important;left:0 !important;width:100% !important;height:100% !important;background:rgba(0,0,0,0.85) !important;z-index:99999 !important;display:flex !important;align-items:center !important;justify-content:center !important;padding:20px;backdrop-filter:blur(5px);"
        @click.self="newsletterModalOpen = false"
        @keydown.escape.window="newsletterModalOpen = false" x-cloak>

        <div style="background:#fff;width:100%;max-width:480px;border-radius:0px;overflow:hidden;box-shadow:0 30px 100px rgba(0,0,0,0.5);position:relative;margin:auto;"
            x-show="newsletterModalOpen"
            x-transition:enter="transition duration-500 cubic-bezier(0.175, 0.885, 0.32, 1.275)"
            x-transition:enter-start="scale-95 opacity-0 translate-y-10"
            x-transition:enter-end="scale-100 opacity-100 translate-y-0">

            {{-- Close Button --}}
            <button @click="newsletterModalOpen = false"
                style="position:absolute;top:20px;right:20px;background:none;border:none;color:#999;font-size:24px;cursor:pointer;transition:color 0.2s;line-height:1;z-index:10;"
                onmouseover="this.style.color='#000'" onmouseout="this.style.color='#999'">✕</button>

            <div style="padding:40px 35px 35px 35px;">
                {{-- Header / Introduction --}}
                <div style="text-align:center;margin-bottom:28px;">
                    <div style="font-size:10px;font-weight:900;text-transform:uppercase;color:#888;letter-spacing:0.2em;margin-bottom:6px;">Bizscoop Intel</div>
                    <h3 style="font-family:'Merriweather',serif;font-size:24px;font-weight:900;color:#111;margin:0 0 10px 0;letter-spacing:-0.01em;text-transform:uppercase;">Subscribe to Newsletter</h3>
                    <p style="color:#666;font-size:12.5px;line-height:1.5;margin:0;">Get top business stories and exclusive analysis delivered straight to your inbox daily.</p>
                </div>

                {{-- Form Component --}}
                <form x-data="{
                        name: '',
                        email: '',
                        loading: false,
                        sent: false,
                        message: '',
                        async submit() {
                            this.loading = true;
                            this.message = '';
                            try {
                                const response = await fetch('{{ route('frontend.newsletter.subscribe') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({ email: this.email, name: this.name })
                                });
                                const result = await response.json();
                                this.message = result.message;
                                if (response.ok) {
                                    this.sent = true;
                                    this.email = '';
                                    this.name = '';
                                }
                            } catch (e) {
                                this.message = 'Something went wrong. Please try again.';
                            } finally {
                                this.loading = false;
                            }
                        }
                    }"
                    @submit.prevent="submit"
                    style="display:flex;flex-direction:column;gap:16px;">

                    <template x-if="!sent">
                        <div style="display:flex;flex-direction:column;gap:16px;">
                            <div>
                                <label style="display:block;font-size:10px;font-weight:900;text-transform:uppercase;color:#555;margin-bottom:8px;letter-spacing:0.05em;">Your Name</label>
                                <input type="text" placeholder="John Doe" x-model="name" required
                                    style="width:100%;padding:14px;border:1px solid #e0e0e0;border-radius:0px;outline:none;font-size:14px;background:#fcfcfc;transition:all 0.3s;box-sizing:border-box;"
                                    onfocus="this.style.borderColor='#000';this.style.background='#fff'"
                                    onblur="this.style.borderColor='#e0e0e0';this.style.background='#fcfcfc'">
                            </div>

                            <div>
                                <label style="display:block;font-size:10px;font-weight:900;text-transform:uppercase;color:#555;margin-bottom:8px;letter-spacing:0.05em;">Your Email</label>
                                <input type="email" placeholder="john@example.com" x-model="email" required
                                    style="width:100%;padding:14px;border:1px solid #e0e0e0;border-radius:0px;outline:none;font-size:14px;background:#fcfcfc;transition:all 0.3s;box-sizing:border-box;"
                                    onfocus="this.style.borderColor='#000';this.style.background='#fff'"
                                    onblur="this.style.borderColor='#e0e0e0';this.style.background='#fcfcfc'">
                            </div>

                            <button type="submit" :disabled="loading"
                                style="background:#000;color:#fff;padding:16px;font-weight:900;text-transform:uppercase;font-size:12px;letter-spacing:0.12em;border:none;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:10px;"
                                onmouseover="this.style.background='#222'" onmouseout="this.style.background='#000'">
                                <svg x-show="loading" width="14" height="14" viewBox="0 0 24 24" style="animation: spin 1s linear infinite; display:none;" :style="{ display: loading ? 'block' : 'none' }">
                                    <path fill="currentColor" d="M12 4V2A10 10 0 0 0 2 12h2a8 8 0 0 1 8-8Z" />
                                </svg>
                                <span x-text="loading ? 'Subscribing...' : 'Subscribe Now →'"></span>
                            </button>
                        </div>
                    </template>

                    {{-- Success State --}}
                    <template x-if="sent">
                        <div style="text-align:center;padding:20px 0;">
                            <div style="width:64px;height:64px;background:#f0fdf4;color:#16a34a;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                                <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <h4 style="font-family:'Merriweather',serif;font-size:22px;font-weight:900;color:#111;margin-bottom:10px;text-transform:uppercase;">Subscription Confirmed</h4>
                            <p style="color:#555;font-size:13.5px;line-height:1.6;margin-bottom:25px;" x-text="message"></p>
                            <button type="button" @click="newsletterModalOpen = false; sent = false;"
                                style="background:#f5f5f5;color:#666;padding:12px 30px;font-weight:900;text-transform:uppercase;font-size:11px;letter-spacing:0.1em;border:1px solid #e0e0e0;cursor:pointer;transition:all 0.2s;"
                                onmouseover="this.style.background='#eee';this.style.borderColor='#ccc'" onmouseout="this.style.background='#f5f5f5';this.style.borderColor='#e0e0e0'">
                                Close
                            </button>
                        </div>
                    </template>
                </form>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>

</html>