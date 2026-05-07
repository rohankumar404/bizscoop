<x-frontend-layout>
    @php $seo = $category->seoMeta; @endphp
    <x-seo 
        :title="$seo->meta_title ?? $category->getTranslation('name', app()->getLocale())" 
        :description="$seo->meta_description ?? $category->getTranslation('description', app()->getLocale())"
    />

    <x-container>
        <div class="py-12 border-b border-black mb-12">
            <h1 class="font-serif text-6xl font-bold tracking-tighter mb-4">
                {{ $category->getTranslation('name', app()->getLocale()) }}
            </h1>
            @if($category->description)
                <p class="max-w-2xl text-lg text-neutral-600 leading-relaxed italic">
                    {{ $category->getTranslation('description', app()->getLocale()) }}
                </p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            @forelse($posts as $post)
                <article class="group">
                    <a href="{{ route('frontend.article.show', $post->slug) }}" class="block mb-6 overflow-hidden aspect-[16/9] bg-neutral-100">
                        @if($post->hasMedia('featured_image'))
                            <img src="{{ $post->getFirstMediaUrl('featured_image') }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-neutral-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </a>
                    <div class="space-y-4">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">{{ $post->published_at->format('M d, Y') }}</p>
                        <h2 class="font-serif text-2xl font-bold group-hover:underline leading-tight">
                            <a href="{{ route('frontend.article.show', $post->slug) }}">
                                {{ $post->translate()->title }}
                            </a>
                        </h2>
                        <p class="text-sm text-neutral-600 line-clamp-2">
                            {{ $post->translate()->excerpt }}
                        </p>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-neutral-400 font-serif text-2xl italic">No articles found in this section yet.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-20 py-12 border-t border-neutral-100">
            {{ $posts->links() }}
        </div>
    </x-container>
</x-frontend-layout>
