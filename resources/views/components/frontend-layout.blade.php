<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <x-seo 
        :title="$title ?? setting('default_meta_title')" 
        :description="$description ?? setting('default_meta_description')" 
    />

    <x-schema type="Organization" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[var(--color-background)] text-[var(--color-primary)] selection:bg-black selection:text-white overflow-x-hidden" 
      x-data="{ 
        scrolled: false, 
        mobileMenuOpen: false, 
        searchOpen: false,
        langOpen: false
      }" 
      @scroll.window="scrolled = (window.pageYOffset > 50)"
      :class="mobileMenuOpen || searchOpen ? 'overflow-hidden' : ''">

    <div id="app" class="flex flex-col min-h-screen">
        {{-- Breaking News Ticker --}}
        @if(count($breakingNews ?? []) > 0)
            <div class="bg-black text-white py-2 px-4 overflow-hidden relative h-10 flex items-center">
                <div class="container-landing flex items-center">
                    <span class="text-[8px] font-bold uppercase tracking-[0.3em] bg-red-600 px-2 py-0.5 mr-6 flex-shrink-0">Breaking</span>
                    <div class="flex-grow overflow-hidden whitespace-nowrap relative">
                        <div class="inline-block animate-marquee group">
                            @foreach($breakingNews as $news)
                                <a href="{{ route('frontend.article.show', $news->slug) }}" class="text-[10px] font-bold uppercase tracking-widest mr-12 hover:text-red-400 transition-colors">
                                    {{ $news->translate()->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Header --}}
        <header class="sticky top-0 z-50 transition-all duration-500"
                :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-sm border-b' : 'bg-transparent border-b border-neutral-100'">
            
            <x-container class="flex justify-between items-center h-24">
                {{-- Left: Mobile Toggle & Logo --}}
                <div class="flex items-center space-x-8">
                    <button @click="mobileMenuOpen = true" class="md:hidden text-black p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    
                    <a href="/" class="font-serif text-3xl font-bold tracking-tighter hover:opacity-80 transition-opacity">
                        @if(setting('site_logo'))
                            <img src="{{ Storage::url(setting('site_logo')) }}" class="h-10">
                        @else
                            {{ setting('site_name', 'BizScoop') }}<span class="text-red-600">.</span>
                        @endif
                    </a>

                    <nav class="hidden md:flex space-x-8 text-[10px] font-bold uppercase tracking-[0.2em]">
                        @foreach($headerCategories as $cat)
                            <a href="{{ route('frontend.category.show', $cat->slug) }}" 
                               class="relative py-2 group {{ request()->is('section/' . $cat->slug) ? 'text-black' : 'text-neutral-400 hover:text-black' }} transition-colors">
                                {{ $cat->getTranslation('name', app()->getLocale()) }}
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-black transition-all duration-300 group-hover:w-full {{ request()->is('section/' . $cat->slug) ? 'w-full' : '' }}"></span>
                            </a>
                        @endforeach
                    </nav>
                </div>

                {{-- Right: Search & Actions --}}
                <div class="flex items-center space-x-6">
                    {{-- Language Switcher --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-[10px] font-bold uppercase tracking-widest flex items-center hover:text-neutral-500 transition-colors">
                            {{ strtoupper(app()->getLocale()) }}
                            <svg class="w-3 h-3 ml-1 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute right-0 mt-4 w-32 bg-white border border-neutral-100 shadow-2xl p-2 z-[60]">
                            @foreach($availableLanguages ?? [] as $lang)
                                <a href="{{ route('locale.switch', $lang->code) }}" class="block px-4 py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-neutral-50 {{ app()->getLocale() == $lang->code ? 'text-red-600' : '' }}">
                                    {{ $lang->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <button @click="searchOpen = true" class="p-2 text-neutral-400 hover:text-black transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>

                    <a href="#" class="hidden sm:inline-block px-6 py-2.5 bg-black text-white text-[10px] font-bold uppercase tracking-widest hover:bg-neutral-800 transition-colors">
                        Subscribe
                    </a>
                </div>
            </x-container>
        </header>

        {{-- Mobile Menu Overlay --}}
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 z-[100] bg-white/98 backdrop-blur-xl flex flex-col p-12">
            
            <div class="flex justify-between items-center mb-20">
                <span class="font-serif text-3xl font-bold tracking-tighter">BizScoop.</span>
                <button @click="mobileMenuOpen = false" class="text-black p-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <nav class="space-y-8">
                @foreach($headerCategories as $cat)
                    <a href="{{ route('frontend.category.show', $cat->slug) }}" @click="mobileMenuOpen = false" class="block font-serif text-5xl font-bold tracking-tighter hover:text-neutral-500 transition-colors">
                        {{ $cat->getTranslation('name', app()->getLocale()) }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto pt-12 border-t border-neutral-100 flex flex-col space-y-6">
                <a href="#" class="text-sm font-bold uppercase tracking-widest text-red-600">Premium Membership</a>
                <div class="flex space-x-6 text-neutral-400">
                    <a href="#" class="hover:text-black">Twitter</a>
                    <a href="#" class="hover:text-black">LinkedIn</a>
                    <a href="#" class="hover:text-black">Instagram</a>
                </div>
            </div>
        </div>

        <div x-show="searchOpen" 
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 -translate-y-full"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-data="{ 
                searchQuery: '', 
                liveResults: [],
                fetchResults() {
                    if (this.searchQuery.length < 2) {
                        this.liveResults = [];
                        return;
                    }
                    fetch('{{ route('frontend.search.live') }}?q=' + this.searchQuery)
                        .then(res => res.json())
                        .then(data => { this.liveResults = data.results; });
                }
             }"
             class="fixed inset-0 z-[110] bg-white/95 backdrop-blur-md flex flex-col items-center justify-center p-8">
            
            <button @click="searchOpen = false" class="absolute top-12 right-12 text-black p-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="w-full max-w-4xl text-center">
                <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-neutral-400 mb-8">Type to search BizScoop</p>
                <form action="{{ route('frontend.search') }}" method="GET">
                    <input type="text" name="q" x-model="searchQuery" @input.debounce.300ms="fetchResults()" autofocus 
                           placeholder="Search paradigms, markets, news..." 
                           class="w-full bg-transparent border-b-2 border-black font-serif text-5xl md:text-7xl font-bold tracking-tighter text-center focus:outline-none placeholder:text-neutral-200">
                </form>

                {{-- Live Results --}}
                <div x-show="liveResults.length > 0" class="mt-12 text-left max-w-2xl mx-auto space-y-4">
                    <template x-for="result in liveResults" :key="result.url">
                        <a :href="result.url" class="block p-4 hover:bg-neutral-50 transition-colors border-l-2 border-transparent hover:border-black">
                            <p class="text-[8px] font-bold uppercase tracking-widest text-neutral-400" x-text="result.type + (result.category ? ' in ' + result.category : '')"></p>
                            <h4 class="font-serif text-xl font-bold" x-text="result.title"></h4>
                        </a>
                    </template>
                </div>

                <div x-show="liveResults.length === 0" class="mt-12 flex flex-wrap justify-center gap-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-neutral-400 mr-4 self-center">Trending:</p>
                    <a href="#" class="px-4 py-2 bg-neutral-100 text-[10px] font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-colors">#AI Regulation</a>
                    <a href="#" class="px-4 py-2 bg-neutral-100 text-[10px] font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-colors">#Crypto Fall</a>
                    <a href="#" class="px-4 py-2 bg-neutral-100 text-[10px] font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-colors">#Market Boom</a>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <main class="flex-grow">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="bg-[var(--color-surface)] border-t border-[var(--color-border)] py-24">
            <x-container>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-16">
                    <div class="md:col-span-5">
                        <h2 class="font-serif text-4xl font-bold tracking-tighter mb-8">{{ setting('site_name', 'BizScoop') }}<span class="text-red-600">.</span></h2>
                        <p class="text-neutral-500 max-w-sm leading-relaxed mb-8">
                            {{ setting('default_meta_description') }}
                        </p>
                        <div class="flex space-x-6">
                            @if(setting('social_twitter'))
                                <a href="{{ setting('social_twitter') }}" class="text-[10px] font-bold uppercase tracking-widest hover:text-black">Twitter</a>
                            @endif
                            @if(setting('social_linkedin'))
                                <a href="{{ setting('social_linkedin') }}" class="text-[10px] font-bold uppercase tracking-widest hover:text-black">LinkedIn</a>
                            @endif
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] mb-8 text-black">Company</h3>
                        <ul class="space-y-4 text-[10px] font-bold uppercase tracking-widest text-neutral-400">
                            <li><a href="#" class="hover:text-black transition-colors">About Us</a></li>
                            <li><a href="#" class="hover:text-black transition-colors">Careers</a></li>
                            <li><a href="#" class="hover:text-black transition-colors">Manifesto</a></li>
                            <li><a href="#" class="hover:text-black transition-colors">Contact</a></li>
                        </ul>
                    </div>

                    <div class="md:col-span-2">
                        <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] mb-8 text-black">Sections</h3>
                        <ul class="space-y-4 text-[10px] font-bold uppercase tracking-widest text-neutral-400">
                            @foreach($headerCategories->take(4) as $cat)
                                <li><a href="{{ route('frontend.category.show', $cat->slug) }}" class="hover:text-black transition-colors">{{ $cat->getTranslation('name', 'en') }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="md:col-span-3">
                        <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] mb-8 text-black">Subscribe to the scoop</h3>
                        <form class="space-y-4">
                            <input type="email" placeholder="Your work email..." class="w-full px-4 py-3 bg-white border border-neutral-200 text-[10px] font-bold uppercase tracking-widest focus:ring-1 focus:ring-black">
                            <button class="w-full py-3 bg-black text-white text-[10px] font-bold uppercase tracking-widest hover:bg-neutral-800 transition-colors">Join 50,000+ Readers</button>
                        </form>
                    </div>
                </div>
                
                <div class="mt-24 pt-12 border-t border-[var(--color-border)] flex flex-col md:flex-row justify-between items-center text-[8px] font-bold uppercase tracking-[0.3em] text-neutral-400">
                    <p>&copy; {{ date('Y') }} BizScoop Media Group. High Integrity Journalism Since 2024.</p>
                    <div class="flex space-x-8 mt-6 md:mt-0">
                        <a href="#" class="hover:text-black transition-colors">Privacy</a>
                        <a href="#" class="hover:text-black transition-colors">Terms</a>
                        <a href="#" class="hover:text-black transition-colors">Cookies</a>
                    </div>
                </div>
            </x-container>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
