<x-frontend-layout>
    <x-seo :title="'Search Results for: ' . $query" />

    <x-container>
        <div class="py-12 border-b border-black mb-12">
            <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-neutral-400 mb-4">Search Results</p>
            <h1 class="font-serif text-5xl md:text-7xl font-bold tracking-tighter">
                &ldquo;{{ $query }}&rdquo;
            </h1>
            <p class="mt-6 text-sm text-neutral-500 font-bold uppercase tracking-widest">
                Found {{ $results->total() ?? 0 }} results
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
            {{-- Results Column --}}
            <div class="lg:col-span-8 space-y-16">
                @forelse($results as $post)
                    @php 
                        $translation = $post->translate();
                        // Simple highlighting logic
                        $highlightedTitle = str_ireplace($query, "<mark class='bg-yellow-100 text-black px-1'>$query</mark>", $translation->title);
                        $highlightedExcerpt = str_ireplace($query, "<mark class='bg-yellow-100 text-black px-1'>$query</mark>", $translation->excerpt);
                    @endphp
                    <article class="group">
                        <div class="flex flex-col md:flex-row md:space-x-8">
                            <a href="{{ route('frontend.article.show', $post->slug) }}" class="w-full md:w-1/3 aspect-[16/9] bg-neutral-100 mb-6 md:mb-0 overflow-hidden">
                                @if($post->hasMedia('featured_image'))
                                    <img src="{{ $post->getFirstMediaUrl('featured_image') }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                                @endif
                            </a>
                            <div class="flex-grow">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">
                                    {{ $post->category->getTranslation('name', app()->getLocale()) }} &bull; {{ $post->published_at->format('M d, Y') }}
                                </p>
                                <h2 class="font-serif text-3xl font-bold mb-4 group-hover:underline">
                                    <a href="{{ route('frontend.article.show', $post->slug) }}">
                                        {!! $highlightedTitle !!}
                                    </a>
                                </h2>
                                <p class="text-neutral-600 text-sm leading-relaxed line-clamp-3">
                                    {!! $highlightedExcerpt !!}
                                </p>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="py-20 text-center border-2 border-dashed border-neutral-100">
                        <h3 class="font-serif text-3xl italic text-neutral-300 mb-4">No paradigms found matching your search.</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Try adjusting your keywords or browsing by section.</p>
                        <div class="mt-12">
                            <x-ui.button variant="outline" size="md" href="/">Back to Home</x-ui.button>
                        </div>
                    </div>
                @endforelse

                <div class="pt-12 border-t border-neutral-100">
                    {{ $results->appends(['q' => $query])->links() }}
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-4">
                <div class="sticky top-32 space-y-12">
                    <div class="bg-[#F8F8F8] p-8">
                        <h3 class="text-xs font-bold uppercase tracking-widest mb-6 border-b border-black pb-2">Filter Results</h3>
                        <p class="text-[10px] text-neutral-500 leading-relaxed uppercase tracking-wider">
                            Currently showing all relevant articles and analyses. Use the main menu to narrow down by specific editorial sections.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </x-container>
</x-frontend-layout>
