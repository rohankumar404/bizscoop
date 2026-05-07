<x-frontend-layout>
    @php 
        $translation = $post->translate();
        $seo = $post->seoMeta;
    @endphp

    <x-seo 
        :title="$seo->meta_title ?? $translation->title" 
        :description="$seo->meta_description ?? $translation->excerpt"
        :ogImage="$post->getFirstMediaUrl('featured_image')"
    />

    <x-schema type="NewsArticle" :data="['post' => $post]" />
    <x-schema type="BreadcrumbList" :data="[
        'items' => [
            ['name' => 'Home', 'url' => route('frontend.home')],
            ['name' => $post->category->getTranslation('name', app()->getLocale()), 'url' => route('frontend.category.show', $post->category->slug)],
            ['name' => $translation->title, 'url' => route('frontend.article.show', $post->slug)],
        ]
    ]" />

    <article class="pt-12 pb-24">
        <x-container>
            {{-- Breadcrumbs --}}
            <nav class="flex space-x-2 text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-12">
                <a href="/" class="hover:text-black">Home</a>
                <span>/</span>
                <a href="{{ route('frontend.category.show', $post->category->slug) }}" class="hover:text-black">
                    {{ $post->category->getTranslation('name', app()->getLocale()) }}
                </a>
            </nav>

            {{-- Header --}}
            <header class="max-w-5xl mx-auto text-center mb-24">
                <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-neutral-400 mb-8 flex items-center justify-center">
                    @if($post->is_sponsored)
                        <span class="bg-black text-white px-2 py-0.5 mr-4">Sponsored Intelligence</span>
                    @endif
                    {{ $post->published_at->format('M d, Y') }} &bull; {{ $post->reading_time }} Min Read
                </p>
                <h1 class="font-serif text-6xl md:text-8xl font-bold tracking-tighter leading-[0.9] mb-12">
                    {{ $translation->title }}<span class="text-red-600">.</span>
                </h1>
                
                <div class="flex items-center justify-center space-x-6 border-y border-neutral-100 py-10">
                    <div class="w-14 h-14 bg-neutral-100 rounded-full overflow-hidden grayscale border border-neutral-200">
                        @if($post->author->hasMedia('avatar'))
                            <img src="{{ $post->author->getFirstMediaUrl('avatar') }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="text-left">
                        <p class="text-[10px] font-bold uppercase tracking-[0.3em]">Written by {{ $post->author->name }}</p>
                        <p class="text-[8px] text-neutral-400 font-bold uppercase tracking-[0.2em] mt-1">Editorial Analysis &bull; BizScoop Staff</p>
                    </div>
                </div>
            </header>

            {{-- Featured Image --}}
            @if($post->hasMedia('featured_image'))
                <div class="mb-24 -mx-4 md:mx-0 shadow-2xl">
                    <img src="{{ $post->getFirstMediaUrl('featured_image') }}" class="w-full aspect-[21/9] object-cover bg-neutral-100">
                </div>
            @endif

            {{-- Body Content --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-20 relative">
                {{-- Social Share (Sticky) --}}
                <div class="hidden lg:block lg:col-span-1 sticky top-40 h-fit">
                    <div class="flex flex-col space-y-8 text-neutral-300">
                        <button class="hover:text-black transition-colors transform hover:scale-110"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></button>
                        <button class="hover:text-black transition-colors transform hover:scale-110"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.761 0 5-2.239 5-5v-14c0-2.761-2.239-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></button>
                    </div>
                </div>

                {{-- Text Content --}}
                <div class="lg:col-span-8">
                    <div class="prose prose-neutral prose-2xl max-w-none font-sans leading-[1.6] selection:bg-black selection:text-white prose-headings:font-serif prose-headings:tracking-tighter prose-a:text-red-600 prose-a:no-underline hover:prose-a:underline">
                        {!! $translation->content !!}
                    </div>

                    {{-- Tags --}}
                    @if($post->tags->count() > 0)
                        <div class="mt-16 pt-8 border-t border-neutral-100 flex flex-wrap gap-3">
                            @foreach($post->tags as $tag)
                                <a href="#" class="px-4 py-2 bg-neutral-100 text-[10px] font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-colors">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    {{-- Author Bio --}}
                    <div class="mt-20 p-12 bg-neutral-50 border border-neutral-100 flex flex-col md:flex-row items-center md:items-start text-center md:text-left space-y-6 md:space-y-0 md:space-x-8">
                        <div class="w-24 h-24 bg-neutral-200 rounded-full flex-shrink-0 overflow-hidden grayscale">
                            {{-- Avatar here --}}
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-2">Written by</p>
                            <h4 class="font-serif text-2xl font-bold mb-4">{{ $post->author->name }}</h4>
                            <p class="text-sm text-neutral-600 leading-relaxed">
                                Expert analyst covering global markets and business paradigms. Contributing to BizScoop since 2024.
                            </p>
                        </div>
                    </div>

                    {{-- Prev/Next --}}
                    <div class="mt-16 grid grid-cols-2 gap-8 border-y border-neutral-100 py-12">
                        @if($prevPost)
                            <a href="{{ route('frontend.article.show', $prevPost->slug) }}" class="group">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">&larr; Previous</p>
                                <p class="font-serif text-lg font-bold group-hover:underline">{{ $prevPost->translate()->title }}</p>
                            </a>
                        @else
                            <div></div>
                        @endif
                        
                        @if($nextPost)
                            <a href="{{ route('frontend.article.show', $nextPost->slug) }}" class="text-right group">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Next &rarr;</p>
                                <p class="font-serif text-lg font-bold group-hover:underline">{{ $nextPost->translate()->title }}</p>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Sidebar (Related) --}}
                <div class="lg:col-span-3">
                    <div class="sticky top-32 space-y-12">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-widest mb-8 border-b border-black pb-2">Related Analysis</h3>
                            <div class="space-y-8">
                                @foreach($relatedPosts as $related)
                                    <a href="{{ route('frontend.article.show', $related->slug) }}" class="block group">
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">{{ $related->category->getTranslation('name', 'en') }}</p>
                                        <h4 class="font-serif text-xl font-bold group-hover:underline leading-tight">
                                            {{ $related->translate()->title }}
                                        </h4>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-container>
    </article>
</x-frontend-layout>
