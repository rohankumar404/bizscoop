<x-frontend-layout
    :title="$category->seoMeta?->meta_title ?? $category->getTranslation('name', app()->getLocale())"
    :description="$category->seoMeta?->meta_description ?? $category->getTranslation('description', app()->getLocale())"
>
    <div class="wrap py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- ─────────────── MAIN CONTENT ─────────────── --}}
            <div class="lg:col-span-8">

                {{-- Section Header --}}
                <div class="bg-white border border-[#E0E0E0] px-4 pt-4 pb-2 mb-4">
                    <div class="sec-head mb-2">
                        <h1 class="text-[13px] font-black uppercase tracking-[0.15em] text-[#111] leading-none">
                            {{ $category->getTranslation('name', app()->getLocale()) }}
                        </h1>
                        <span class="text-[9px] font-bold uppercase tracking-widest text-[#aaa]">
                            {{ $posts->total() }} Articles
                        </span>
                    </div>
                    @if($category->getTranslation('description', app()->getLocale()))
                        <p class="text-[12px] text-[#666] leading-relaxed pb-2">
                            {{ $category->getTranslation('description', app()->getLocale()) }}
                        </p>
                    @endif
                </div>

                {{-- Articles Grid --}}
                <div class="bg-white border border-[#E0E0E0] p-4">

                    @forelse($posts as $index => $post)

                        @if($index === 0)
                            {{-- First post: large featured --}}
                            <div class="group mb-6 pb-6 border-b border-[#E5E5E5]">
                                <a href="{{ route('frontend.article.show', $post->slug) }}" class="card-img block aspect-video mb-3">
                                    @if($post->hasMedia('featured_image'))
                                        <img src="{{ $post->getFirstMediaUrl('featured_image') }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-[#ddd]"></div>
                                    @endif
                                    <span class="card-cat">{{ $post->category->getTranslation('name', 'en') }}</span>
                                    <span class="card-flash">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </span>
                                </a>
                                <p class="card-meta mb-1">{{ $post->author->name }} &bull; {{ $post->published_at->format('d M Y') }}</p>
                                <a href="{{ route('frontend.article.show', $post->slug) }}" class="block text-[20px] font-black text-[#111] leading-snug group-hover:underline mb-2"
                                   style="font-family:'Instrument Serif',serif;">
                                    {{ $post->translate()->title }}
                                </a>
                                <p class="text-[13px] text-[#666] leading-relaxed line-clamp-2">{{ $post->translate()->excerpt }}</p>
                            </div>

                        @elseif($index % 6 === 1)
                            {{-- Every 7th post onwards: 2-column grid opener --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                                <div class="group">
                                    <a href="{{ route('frontend.article.show', $post->slug) }}" class="card-img block aspect-video mb-2">
                                        @if($post->hasMedia('featured_image'))
                                            <img src="{{ $post->getFirstMediaUrl('featured_image') }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-[#ddd]"></div>
                                        @endif
                                        <span class="card-cat">{{ $post->category->getTranslation('name', 'en') }}</span>
                                    </a>
                                    <p class="card-meta">{{ $post->author->name }} · {{ $post->published_at->format('d M Y') }}</p>
                                    <a href="{{ route('frontend.article.show', $post->slug) }}" class="card-title block mt-0.5">{{ $post->translate()->title }}</a>
                                </div>

                                @if($posts[$index + 1] ?? false)
                                    <div class="group">
                                        <a href="{{ route('frontend.article.show', $posts[$index + 1]->slug) }}" class="card-img block aspect-video mb-2">
                                            @if($posts[$index + 1]->hasMedia('featured_image'))
                                                <img src="{{ $posts[$index + 1]->getFirstMediaUrl('featured_image') }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-[#ddd]"></div>
                                            @endif
                                            <span class="card-cat">{{ $posts[$index + 1]->category->getTranslation('name', 'en') }}</span>
                                        </a>
                                        <p class="card-meta">{{ $posts[$index + 1]->author->name }} · {{ $posts[$index + 1]->published_at->format('d M Y') }}</p>
                                        <a href="{{ route('frontend.article.show', $posts[$index + 1]->slug) }}" class="card-title block mt-0.5">{{ $posts[$index + 1]->translate()->title }}</a>
                                    </div>
                                @endif
                            </div>

                        @elseif(in_array($index % 6, [2, 3]))
                            {{-- Skip — rendered above in pair --}}
                            @continue

                        @else
                            {{-- List card layout for remaining --}}
                            <div class="list-card">
                                <a href="{{ route('frontend.article.show', $post->slug) }}" class="list-card-img shrink-0">
                                    @if($post->hasMedia('featured_image'))
                                        <img src="{{ $post->getFirstMediaUrl('featured_image') }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-[#ddd]"></div>
                                    @endif
                                </a>
                                <div class="list-card-body">
                                    <span class="text-[8px] font-black uppercase tracking-widest bg-[#111] text-white px-2 py-0.5 inline-block mb-1">{{ $post->category->getTranslation('name', 'en') }}</span>
                                    <a href="{{ route('frontend.article.show', $post->slug) }}" class="card-title text-[13px] block mt-0.5">{{ $post->translate()->title }}</a>
                                    <p class="card-meta mt-1">{{ $post->author->name }} &bull; {{ $post->published_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        @endif

                    @empty
                        <div class="py-20 text-center">
                            <p class="text-[#999] text-sm font-medium">No articles found in this section yet.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($posts->hasPages())
                    <div class="mt-4 bg-white border border-[#E0E0E0] p-4">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>

            {{-- ─────────────── SIDEBAR ─────────────── --}}
            <aside class="lg:col-span-4">
                <div class="sticky top-20 space-y-5">

                    {{-- Social Join --}}
                    <div class="bg-white border border-[#E0E0E0] p-4">
                        <div class="sec-head"><h3>Join Us</h3></div>
                        <div class="grid grid-cols-3 gap-2 text-center text-[8px] font-black uppercase">
                            <a href="#" class="bg-[#3b5998] text-white py-2 hover:opacity-80"><div class="text-lg">f</div>Facebook</a>
                            <a href="#" class="bg-[#1da1f2] text-white py-2 hover:opacity-80"><div class="text-lg">t</div>Twitter</a>
                            <a href="#" class="bg-[#dd4b39] text-white py-2 hover:opacity-80"><div class="text-lg">g+</div>Google+</a>
                            <a href="#" class="bg-[#bd081c] text-white py-2 hover:opacity-80"><div class="text-lg">P</div>Pinterest</a>
                            <a href="#" class="bg-[#ff6600] text-white py-2 hover:opacity-80"><div class="text-lg">rss</div>RSS</a>
                            <a href="#" class="bg-[#ff0000] text-white py-2 hover:opacity-80"><div class="text-lg">▶</div>YouTube</a>
                        </div>
                    </div>

                    {{-- Ad --}}
                    <div class="ad-slot w-full h-[280px] border border-[#ddd]">300 × 280 AD</div>

                    {{-- Trending in this section --}}
                    <div class="bg-white border border-[#E0E0E0] p-4">
                        <div class="sec-head">
                            <h3>Trending in {{ $category->getTranslation('name', 'en') }}</h3>
                        </div>
                        <div class="space-y-0">
                            @foreach($sidebarTrendingArticles->take(6) as $i => $tp)
                                <div class="list-card">
                                    <a href="{{ route('frontend.article.show', $tp->slug) }}" class="list-card-img shrink-0">
                                        @if($tp->hasMedia('featured_image'))
                                            <img src="{{ $tp->getFirstMediaUrl('featured_image') }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-[#ddd] flex items-center justify-center text-xl font-black text-[#bbb]">{{ $i+1 }}</div>
                                        @endif
                                    </a>
                                    <div class="list-card-body">
                                        <a href="{{ route('frontend.article.show', $tp->slug) }}" class="card-title text-[12px] block line-clamp-2">{{ $tp->translate()->title }}</a>
                                        <p class="card-meta mt-1">{{ $tp->published_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Newsletter --}}
                    <div class="bg-[#111] text-white p-5">
                        <div class="text-[11px] font-black uppercase tracking-[0.15em] border-t-2 border-white pt-2 mb-3">Newsletter</div>
                        <p class="text-[11px] text-[#aaa] mb-4 leading-relaxed">Get top business stories in your inbox every morning.</p>
                        <form class="flex gap-0">
                            <input type="email" placeholder="Your email…"
                                   class="flex-grow bg-[#333] text-white text-xs px-3 py-2.5 placeholder:text-[#666] focus:outline-none border-none">
                            <button class="bg-white text-black text-[9px] font-black uppercase tracking-widest px-4 hover:bg-[#ddd] transition-colors">Go</button>
                        </form>
                    </div>

                    {{-- Second Ad --}}
                    <div class="ad-slot w-full h-[250px] border border-[#ddd]">300 × 250 AD</div>
                </div>
            </aside>
        </div>
    </div>
</x-frontend-layout>
