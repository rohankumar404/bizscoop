<x-admin-layout>
    <x-slot:section-title>Editorial</x-slot>
    <x-slot:page-title>New Article</x-slot>
    
    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-12 pb-20">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            {{-- Main Content Area --}}
            <div class="lg:col-span-8 space-y-12">
                <div class="bg-white border border-[#E5E5E5] p-10">
                    <div class="space-y-8">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400 mb-4">Article Headline (English)</label>
                            <input type="text" name="title[en]" id="article-title-en" value="{{ old('title.en') }}" class="w-full font-serif text-4xl font-bold border-none bg-[#F8F8F8] p-6 focus:ring-1 focus:ring-black" placeholder="Enter headline..." required>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400 mb-4">Slug</label>
                            <div class="flex items-center bg-[#F8F8F8] px-4 py-3">
                                <span class="text-neutral-400 text-xs mr-2">{{ url('/article') }}/</span>
                                <input type="text" name="slug" id="article-slug" value="{{ old('slug') }}" class="flex-grow bg-transparent border-none p-0 text-xs font-mono focus:ring-0" placeholder="article-slug-here" required>
                                <button type="button" onclick="unlockSlug()" class="ml-2 text-neutral-400 hover:text-black">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400 mb-4">Excerpt (Summary)</label>
                            <textarea name="excerpt[en]" rows="3" class="w-full px-6 py-4 bg-[#F8F8F8] border-none text-sm leading-relaxed focus:ring-1 focus:ring-black">{{ old('excerpt.en') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400 mb-4">Body Content</label>
                            <x-tinymce name="content[en]" id="article-content-en">{{ old('content.en') }}</x-tinymce>
                        </div>
                    </div>
                </div>

                {{-- SEO & Metadata --}}
                <div class="bg-white border border-[#E5E5E5] p-10">
                    <h3 class="text-xs font-bold uppercase tracking-widest mb-10 border-b pb-4">Search Engine Optimization</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Meta Title</label>
                                <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="w-full px-4 py-3 bg-[#F8F8F8] border-none text-sm focus:ring-1 focus:ring-black">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Canonical URL</label>
                                <input type="text" name="canonical_url" value="{{ old('canonical_url') }}" class="w-full px-4 py-3 bg-[#F8F8F8] border-none text-sm focus:ring-1 focus:ring-black">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Meta Description</label>
                            <textarea name="meta_description" rows="5" class="w-full px-4 py-3 bg-[#F8F8F8] border-none text-sm focus:ring-1 focus:ring-black">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Area --}}
            <div class="lg:col-span-4 space-y-8">
                {{-- Publishing Settings --}}
                <div class="bg-white border border-[#E5E5E5] p-8">
                    <h3 class="text-xs font-bold uppercase tracking-widest mb-8 border-b pb-4">Publishing</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-3 bg-[#F8F8F8] border-none text-xs font-bold uppercase tracking-widest focus:ring-1 focus:ring-black">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="scheduled">Scheduled</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Article Type</label>
                            <select name="type" class="w-full px-4 py-3 bg-[#F8F8F8] border-none text-xs font-bold uppercase tracking-widest focus:ring-1 focus:ring-black">
                                <option value="article" {{ $defaultType == 'article' ? 'selected' : '' }}>Standard Article</option>
                                <option value="news" {{ $defaultType == 'news' ? 'selected' : '' }}>Breaking News</option>
                                <option value="magazine" {{ $defaultType == 'magazine' ? 'selected' : '' }}>Magazine Feature</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Publish Date</label>
                            <input type="datetime-local" name="published_at" value="{{ date('Y-m-d\TH:i') }}" class="w-full px-4 py-3 bg-[#F8F8F8] border-none text-xs focus:ring-1 focus:ring-black">
                        </div>
                    </div>
                </div>

                {{-- Classification --}}
                <div class="bg-white border border-[#E5E5E5] p-8">
                    <h3 class="text-xs font-bold uppercase tracking-widest mb-8 border-b pb-4">Classification</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Section / Category</label>
                            <select name="category_id" class="w-full px-4 py-3 bg-[#F8F8F8] border-none text-xs font-bold uppercase tracking-widest focus:ring-1 focus:ring-black" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->getTranslation('name', 'en') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Primary Author</label>
                            <select name="author_id" class="w-full px-4 py-3 bg-[#F8F8F8] border-none text-xs font-bold uppercase tracking-widest focus:ring-1 focus:ring-black" required>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ $author->id == auth()->id() ? 'selected' : '' }}>{{ $author->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Tags</label>
                            <div class="grid grid-cols-2 gap-2 mt-4 max-h-40 overflow-y-auto p-4 bg-[#F8F8F8]">
                                @foreach($tags as $tag)
                                    <label class="flex items-center space-x-2 text-[10px] uppercase font-bold text-neutral-500 cursor-pointer">
                                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="text-black focus:ring-black border-neutral-300">
                                        <span>{{ $tag->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Flags --}}
                <div class="bg-white border border-[#E5E5E5] p-8">
                    <h3 class="text-xs font-bold uppercase tracking-widest mb-8 border-b pb-4">Promotions</h3>
                    <div class="space-y-4">
                        <label class="flex items-center justify-between cursor-pointer group">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 group-hover:text-black">Featured Article</span>
                            <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 text-black focus:ring-black border-neutral-300">
                        </label>
                        <label class="flex items-center justify-between cursor-pointer group">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 group-hover:text-black">Trending Now</span>
                            <input type="checkbox" name="is_trending" value="1" class="w-4 h-4 text-black focus:ring-black border-neutral-300">
                        </label>
                        <label class="flex items-center justify-between cursor-pointer group">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 group-hover:text-black">Sponsored Content</span>
                            <input type="checkbox" name="is_sponsored" value="1" class="w-4 h-4 text-black focus:ring-black border-neutral-300">
                        </label>
                    </div>
                </div>

                {{-- Featured Image --}}
                <div class="bg-white border border-[#E5E5E5] p-8">
                    <h3 class="text-xs font-bold uppercase tracking-widest mb-8 border-b pb-4">Featured Image</h3>
                    <div class="space-y-6">
                        <input type="file" name="featured_image" class="text-xs text-neutral-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-[10px] file:font-bold file:uppercase file:tracking-widest file:bg-black file:text-white hover:file:bg-neutral-800">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Image Alt Text (SEO)</label>
                            <input type="text" name="featured_image_alt" value="{{ old('featured_image_alt') }}" class="w-full px-4 py-3 bg-[#F8F8F8] border-none text-xs focus:ring-1 focus:ring-black" placeholder="Describe the image for accessibility...">
                        </div>
                    </div>
                </div>

                <div class="sticky bottom-8">
                    <x-ui.button type="submit" variant="primary" class="w-full shadow-2xl">
                        Publish Article
                    </x-ui.button>
                </div>
            </div>
        </div>
    </form>
    @push('scripts')
    <script>
        const titleInput = document.getElementById('article-title-en');
        const slugInput = document.getElementById('article-slug');
        let slugManuallyEdited = false;

        titleInput.addEventListener('input', function() {
            if (!slugManuallyEdited) {
                slugInput.value = this.value
                    .toLowerCase()
                    .replace(/[^\w ]+/g, '')
                    .replace(/ +/g, '-');
            }
        });

        slugInput.addEventListener('input', function() {
            slugManuallyEdited = true;
        });

        function unlockSlug() {
            slugManuallyEdited = false;
            titleInput.dispatchEvent(new Event('input'));
        }
    </script>
    @endpush
</x-admin-layout>
