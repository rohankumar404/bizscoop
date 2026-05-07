<x-frontend-layout>
    <x-seo title="The Future of Business Journalism" />

    <x-container>
        {{-- Hero Section --}}
        <div class="border-b border-black pb-20 mb-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-end">
                <div class="lg:col-span-8">
                    <p class="text-xs font-bold uppercase tracking-widest mb-6 flex items-center">
                        <span class="w-2 h-2 bg-red-600 rounded-full mr-2 animate-pulse"></span>
                        Trending Now
                    </p>
                    <x-editorial.heading level="1" size="2xl" class="mb-8 leading-[0.9]">
                        The Great <span class="italic font-serif">BizScoop</span> Paradigm: Why Neutrality is the New Gold.
                    </x-editorial.heading>
                </div>
                <div class="lg:col-span-4">
                    <p class="text-lg leading-relaxed text-neutral-600 mb-8 font-serif italic">
                        In an era of polarized media, we're returning to the roots of high-integrity journalism. Discover how data-driven insights are reshaping the corporate landscape.
                    </p>
                    <x-ui.button variant="outline" size="md">
                        Read the Manifesto
                    </x-ui.button>
                </div>
            </div>
        </div>

        {{-- Main Feed + Trending Sidebar --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
            {{-- Left Content --}}
            <div class="lg:col-span-8 space-y-20">
                <section>
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.3em] mb-12 border-b border-neutral-100 pb-4">Latest Analysis</h3>
                    <div class="space-y-16">
                        {{-- This would normally be a loop of latest posts --}}
                        <article class="group">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="aspect-video bg-neutral-100 overflow-hidden">
                                    {{-- Post Image --}}
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Market Insights &bull; 5 min read</p>
                                    <h2 class="font-serif text-3xl font-bold mb-4 group-hover:underline leading-tight">Global supply chains are entering a new phase of localization.</h2>
                                    <p class="text-neutral-600 text-sm leading-relaxed mb-6">Explore how geopolitical shifts are forcing companies to rethink their global footprint and move production closer to home.</p>
                                    <a href="#" class="text-[10px] font-bold uppercase tracking-widest border-b-2 border-black pb-1">Read Analysis</a>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>
            </div>

            {{-- Trending Sidebar --}}
            <div class="lg:col-span-4">
                <div class="sticky top-32">
                    <div class="border-t-4 border-black pt-8">
                        <h3 class="text-xs font-bold uppercase tracking-[0.2em] mb-12">The Trending Scoop</h3>
                        
                        <div class="space-y-10">
                            @forelse($trendingPosts as $index => $post)
                                <a href="{{ route('frontend.article.show', $post->slug) }}" class="flex space-x-6 group">
                                    <span class="font-serif text-4xl font-bold text-neutral-200 group-hover:text-black transition-colors">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <div>
                                        <p class="text-[8px] font-bold uppercase tracking-widest text-neutral-400 mb-1">
                                            {{ $post->category->getTranslation('name', app()->getLocale()) }}
                                            @if($post->is_trending)
                                                <span class="ml-2 text-red-600">&bull; Featured</span>
                                            @endif
                                        </p>
                                        <h4 class="font-serif text-xl font-bold group-hover:underline leading-tight">
                                            {{ $post->translate()->title }}
                                        </h4>
                                    </div>
                                </a>
                            @empty
                                <p class="text-neutral-400 font-serif italic">The algorithm is still crunching the numbers...</p>
                            @endforelse
                        </div>

                        <div class="mt-16 p-8 bg-neutral-50 border border-neutral-100">
                            <h4 class="text-[10px] font-bold uppercase tracking-widest mb-4">Newsletter</h4>
                            <p class="text-xs text-neutral-500 mb-6 leading-relaxed">Get the most important business paradigms delivered to your inbox every morning.</p>
                            <form class="space-y-3">
                                <input type="email" placeholder="Email address" class="w-full px-4 py-3 bg-white border border-neutral-200 text-[10px] font-bold uppercase tracking-widest focus:ring-1 focus:ring-black">
                                <button class="w-full py-3 bg-black text-white text-[10px] font-bold uppercase tracking-widest hover:bg-neutral-800 transition-colors">Subscribe</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-container>
</x-frontend-layout>
