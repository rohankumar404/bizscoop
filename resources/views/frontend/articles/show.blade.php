@php
    $translation = $post->translate();
    $seo         = $post->seoMeta;
@endphp

<x-frontend-layout
    :title="$seo?->meta_title ?? $translation->title"
    :description="$seo?->meta_description ?? $translation->excerpt"
    :ogImage="$post->getFirstMediaUrl('featured_image')"
>
    <x-schema type="NewsArticle" :data="['post' => $post]" />
    <x-schema type="BreadcrumbList" :data="[
        'items' => [
            ['name' => 'Home',  'url' => route('frontend.home')],
            ['name' => $post->category->getTranslation('name', app()->getLocale()), 'url' => route('frontend.category.show', $post->category->slug)],
            ['name' => $translation->title, 'url' => route('frontend.article.show', $post->slug)],
        ]
    ]" />

    <div class="wrap py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- ─────────────── MAIN ARTICLE ─────────────── --}}
            <article class="lg:col-span-8">
                <div class="bg-white border border-[#E0E0E0] p-6">

                    {{-- Breadcrumb --}}
                    <nav class="text-[10px] font-semibold uppercase tracking-widest text-[#999] mb-4 flex items-center gap-2">
                        <a href="{{ route('frontend.home') }}" class="hover:text-black transition-colors">Home</a>
                        <span>›</span>
                        <a href="{{ route('frontend.category.show', $post->category->slug) }}" class="hover:text-black transition-colors">
                            {{ $post->category->getTranslation('name', app()->getLocale()) }}
                        </a>
                        <span>›</span>
                        <span class="text-[#555] normal-case">{{ Str::limit($translation->title, 40) }}</span>
                    </nav>

                    {{-- Category Tag --}}
                    <div class="mb-3">
                        <a href="{{ route('frontend.category.show', $post->category->slug) }}"
                           class="inline-block bg-[#111] text-white text-[9px] font-black uppercase tracking-widest px-3 py-1">
                            {{ $post->category->getTranslation('name', app()->getLocale()) }}
                        </a>
                    </div>

                    {{-- Title --}}
                    <h1 class="text-[26px] md:text-[32px] font-black leading-tight tracking-tight text-[#111] mb-4"
                        style="font-family:'Instrument Serif',serif;">
                        {{ $translation->title }}
                    </h1>

                    {{-- Excerpt --}}
                    @if($translation->excerpt)
                        <p class="text-[15px] text-[#555] italic border-l-4 border-[#111] pl-4 mb-5 leading-relaxed">
                            {{ $translation->excerpt }}
                        </p>
                    @endif

                    {{-- Meta Bar --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 border-y border-[#E5E5E5] py-3 mb-6">
                        <div class="flex items-center gap-3">
                            {{-- Author avatar --}}
                            <div class="w-9 h-9 rounded-full bg-[#ddd] overflow-hidden flex-shrink-0">
                                @if($post->author->hasMedia('avatar'))
                                    <img src="{{ $post->author->getFirstMediaUrl('avatar') }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-xs font-black text-[#999]">
                                        {{ strtoupper(substr($post->author->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-[#111]">{{ $post->author->name }}</p>
                                <p class="text-[9px] text-[#999]">{{ $post->published_at->format('d F Y') }} &bull; {{ $post->reading_time ?? '3' }} min read</p>
                            </div>
                        </div>

                        {{-- Social Share --}}
                        <div class="flex items-center gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank"
                               class="w-8 h-8 bg-[#3b5998] text-white flex items-center justify-center text-[10px] font-black hover:opacity-80 transition-opacity">f</a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}" target="_blank"
                               class="w-8 h-8 bg-[#1da1f2] text-white flex items-center justify-center text-[10px] font-black hover:opacity-80 transition-opacity">t</a>
                            <button onclick="navigator.clipboard.writeText(window.location.href)"
                               class="w-8 h-8 bg-[#555] text-white flex items-center justify-center text-[10px] font-black hover:opacity-80 transition-opacity" title="Copy link">🔗</button>
                        </div>
                    </div>

                    {{-- Featured Image --}}
                    @if($post->hasMedia('featured_image'))
                        <figure class="mb-6">
                            <img src="{{ $post->getFirstMediaUrl('featured_image') }}"
                                 alt="{{ $post->getFirstMedia('featured_image')->getCustomProperty('alt') ?? $translation->title }}"
                                 class="w-full object-cover max-h-[480px]">
                            @if($post->getFirstMedia('featured_image')->getCustomProperty('caption'))
                                <figcaption class="text-[10px] text-[#999] mt-2 italic">
                                    {{ $post->getFirstMedia('featured_image')->getCustomProperty('caption') }}
                                </figcaption>
                            @endif
                        </figure>
                    @endif

                    {{-- Body Content --}}
                    <div class="article-body prose prose-sm md:prose-base max-w-none
                                prose-headings:font-black prose-headings:tracking-tight prose-headings:text-[#111]
                                prose-p:text-[#333] prose-p:leading-relaxed
                                prose-a:text-[#111] prose-a:underline hover:prose-a:no-underline
                                prose-img:w-full prose-img:my-6
                                prose-blockquote:border-l-4 prose-blockquote:border-[#111] prose-blockquote:pl-4 prose-blockquote:italic">
                        {!! $translation->content !!}
                    </div>

                    {{-- Tags --}}
                    @if($post->tags->count() > 0)
                        <div class="mt-8 pt-6 border-t border-[#E5E5E5]">
                            <p class="text-[10px] font-black uppercase tracking-widest text-[#999] mb-3">Tags</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($post->tags as $tag)
                                    <a href="#" class="px-3 py-1 border border-[#ddd] text-[10px] font-bold uppercase tracking-widest text-[#555] hover:bg-[#111] hover:text-white hover:border-[#111] transition-all">
                                        {{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Author Bio --}}
                    <div class="mt-8 bg-[#F4F4F4] border border-[#E0E0E0] p-5 flex gap-4">
                        <div class="w-16 h-16 rounded-full bg-[#ddd] overflow-hidden flex-shrink-0">
                            @if($post->author->hasMedia('avatar'))
                                <img src="{{ $post->author->getFirstMediaUrl('avatar') }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-[#999] mb-1">Written by</p>
                            <h4 class="text-[15px] font-black text-[#111] mb-1">{{ $post->author->name }}</h4>
                            <p class="text-[12px] text-[#666] leading-relaxed">
                                Expert business journalist at BizScoop covering markets, finance, and global trends.
                            </p>
                        </div>
                    </div>

                    {{-- Prev / Next --}}
                    <div class="mt-6 grid grid-cols-2 gap-4 border-t border-[#E5E5E5] pt-6">
                        @if($prevPost)
                            <a href="{{ route('frontend.article.show', $prevPost->slug) }}" class="group">
                                <p class="text-[9px] font-black uppercase tracking-widest text-[#999] mb-1">‹ Previous</p>
                                <p class="text-[13px] font-bold text-[#111] leading-snug group-hover:underline line-clamp-2">
                                    {{ $prevPost->translate()->title }}
                                </p>
                            </a>
                        @else <div></div> @endif

                        @if($nextPost)
                            <a href="{{ route('frontend.article.show', $nextPost->slug) }}" class="group text-right">
                                <p class="text-[9px] font-black uppercase tracking-widest text-[#999] mb-1">Next ›</p>
                                <p class="text-[13px] font-bold text-[#111] leading-snug group-hover:underline line-clamp-2">
                                    {{ $nextPost->translate()->title }}
                                </p>
                            </a>
                        @else <div></div> @endif
                    </div>
                </div>
            </article>

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

                    {{-- Related Articles --}}
                    <div class="bg-white border border-[#E0E0E0] p-4">
                        <div class="sec-head"><h3>Related Articles</h3></div>
                        <div class="space-y-0">
                            @foreach($relatedPosts as $related)
                                <div class="list-card">
                                    <a href="{{ route('frontend.article.show', $related->slug) }}" class="list-card-img shrink-0">
                                        @if($related->hasMedia('featured_image'))
                                            <img src="{{ $related->getFirstMediaUrl('featured_image') }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-[#ddd]"></div>
                                        @endif
                                    </a>
                                    <div class="list-card-body">
                                        <p class="text-[9px] font-black text-[#111] uppercase tracking-widest">{{ $related->category->getTranslation('name', 'en') }}</p>
                                        <a href="{{ route('frontend.article.show', $related->slug) }}" class="card-title text-[12px] block mt-0.5 line-clamp-2">
                                            {{ $related->translate()->title }}
                                        </a>
                                        <p class="card-meta mt-1">{{ $related->published_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Most Popular --}}
                    <div class="bg-white border border-[#E0E0E0] p-4">
                        <div class="sec-head"><h3>Most Popular</h3></div>
                        <div class="space-y-0">
                            @foreach($sidebarTrendingArticles->take(5) as $i => $tp)
                                <div class="list-card">
                                    <span class="text-2xl font-black text-[#E0E0E0] leading-none w-6 shrink-0">{{ $i + 1 }}</span>
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
                </div>
            </aside>
        </div>
    </div>
</x-frontend-layout>
