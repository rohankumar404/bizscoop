<x-frontend-layout>
    <x-seo :title="setting('default_meta_title')" />

    {{-- Hero Spotlight --}}
    <section class="pt-12 pb-24 border-b border-neutral-100">
        <x-container>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-end">
                <div class="lg:col-span-8">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-red-600 mb-6 flex items-center">
                        <span class="w-2 h-2 bg-red-600 rounded-full mr-3 animate-pulse"></span>
                        Spotlight Analysis
                    </p>
                    <h1 class="font-serif text-6xl md:text-8xl font-bold tracking-tighter leading-[0.9] mb-0">
                        The Great <span class="italic">BizScoop</span> Paradigm: Why Neutrality is the New Gold<span class="text-red-600">.</span>
                    </h1>
                </div>
                <div class="lg:col-span-4">
                    <p class="text-xl leading-relaxed text-neutral-500 mb-10 font-serif italic">
                        &ldquo;In an era of polarized media, we're returning to the roots of high-integrity journalism. Discover how data-driven insights are reshaping the corporate landscape.&rdquo;
                    </p>
                    <a href="#" class="editorial-button-outline">Read the Manifesto</a>
                </div>
            </div>
        </x-container>
    </section>

    <x-container class="py-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-20">
            {{-- Primary Feed --}}
            <div class="lg:col-span-8 space-y-24">
                <section>
                    <div class="flex items-center justify-between mb-16 border-b border-neutral-100 pb-4">
                        <h3 class="text-[10px] font-bold uppercase tracking-[0.3em]">Latest Intelligence</h3>
                        <a href="#" class="editorial-link">View All Sections</a>
                    </div>
                    
                    <div class="space-y-20">
                        @foreach(range(1, 3) as $i)
                            <article class="editorial-card">
                                <div class="grid grid-cols-1 md:grid-cols-5 gap-10">
                                    <div class="md:col-span-2">
                                        <div class="editorial-card-image aspect-[4/3]">
                                            {{-- Dynamic Image Here --}}
                                        </div>
                                    </div>
                                    <div class="md:col-span-3 flex flex-col justify-center">
                                        <p class="editorial-card-meta">Global Markets &bull; 6 min read</p>
                                        <h2 class="editorial-card-title">How decentralization is redrawing the map of global production.</h2>
                                        <p class="text-neutral-500 text-sm leading-relaxed mb-8 line-clamp-3">
                                            As logistical bottlenecks and geopolitical tensions mount, corporate leaders are shifting from "Just-in-Time" to "Just-in-Case" manufacturing strategies.
                                        </p>
                                        <a href="#" class="editorial-link self-start">Full Report</a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            </div>

            {{-- Secondary Intelligence --}}
            <div class="lg:col-span-4">
                <div class="sticky top-32 space-y-20">
                    {{-- Trending --}}
                    <div>
                        <div class="mb-12 border-b-2 border-black pb-4">
                            <h3 class="text-[10px] font-bold uppercase tracking-[0.3em]">The Trending Scoop</h3>
                        </div>
                        
                        <div class="space-y-10">
                            @forelse($sidebarTrendingArticles as $index => $trendingItem)
                                <a href="{{ route('frontend.article.show', $trendingItem->slug) }}" class="flex space-x-6 group">
                                    <span class="font-serif text-5xl font-bold text-neutral-100 group-hover:text-black transition-colors">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <div class="pt-1">
                                        <p class="text-[8px] font-bold uppercase tracking-[0.2em] text-neutral-400 mb-2">
                                            {{ $trendingItem->category->getTranslation('name', app()->getLocale()) }}
                                        </p>
                                        <h4 class="font-serif text-xl font-bold group-hover:underline leading-[1.2]">
                                            {{ $trendingItem->translate()->title }}
                                        </h4>
                                    </div>
                                </a>
                            @empty
                                <p class="text-neutral-400 font-serif italic">Analyzing global trends...</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Newsletter --}}
                    <div class="bg-black text-white p-10">
                        <p class="text-[10px] font-bold uppercase tracking-[0.3em] mb-6 text-red-500">Newsletter</p>
                        <h3 class="font-serif text-3xl font-bold mb-6 tracking-tight leading-none">The Daily Briefing.</h3>
                        <p class="text-neutral-400 text-xs leading-relaxed mb-10">
                            Join 50,000+ professionals receiving curated business intelligence every morning.
                        </p>
                        <form class="space-y-4">
                            <input type="email" placeholder="work@company.com" class="w-full bg-neutral-900 border-none px-4 py-4 text-[10px] font-bold uppercase tracking-widest text-white focus:ring-1 focus:ring-red-500">
                            <button class="w-full bg-white text-black py-4 text-[10px] font-bold uppercase tracking-widest hover:bg-neutral-200 transition-colors">Join the Scoop</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-container>
</x-frontend-layout>
