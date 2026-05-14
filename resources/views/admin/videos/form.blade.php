<x-admin-layout>
    <x-slot:sectionTitle>Media</x-slot>
    <x-slot:pageTitle>{{ isset($video) ? 'Edit Video' : 'Add New Video' }}</x-slot>

    <div class="max-w-3xl">
        <form action="{{ isset($video) ? route('admin.videos.update', $video) : route('admin.videos.store') }}" 
              method="POST" enctype="multipart/form-data" 
              class="bg-white border border-[#E5E5E5] p-10 space-y-10 shadow-sm">
            @csrf
            @if(isset($video)) @method('PUT') @endif

            <div class="border-b border-neutral-100 pb-8">
                <h3 class="text-2xl font-serif font-bold tracking-tight">Video Content</h3>
                <p class="text-xs text-neutral-400 uppercase tracking-widest mt-2">Add your YouTube video link and a custom thumbnail for the home page gallery.</p>
            </div>

            <div class="space-y-8">
                {{-- Title --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Video Title</label>
                    <div class="md:col-span-2">
                        <input type="text" name="title" value="{{ old('title', $video->title ?? '') }}" required 
                               class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm" 
                               placeholder="e.g. Exclusive Interview with Tech CEO">
                        @error('title') <p class="text-red-600 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- YouTube URL --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">YouTube URL</label>
                    <div class="md:col-span-2">
                        <input type="url" name="youtube_url" value="{{ old('youtube_url', $video->youtube_url ?? '') }}" required 
                               class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm" 
                               placeholder="https://www.youtube.com/watch?v=...">
                        @error('youtube_url') <p class="text-red-600 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                <hr class="border-neutral-50">

                {{-- Thumbnail Image --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Thumbnail Image</label>
                        <p class="text-[9px] text-neutral-400 uppercase mt-1">Visible in the gallery</p>
                    </div>
                    <div class="md:col-span-2">
                        @if(isset($video) && $video->hasMedia('thumbnail'))
                            <div class="mb-4 w-40 aspect-video border overflow-hidden bg-neutral-50">
                                <img src="{{ $video->getFirstMediaUrl('thumbnail') }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <input type="file" name="thumbnail" {{ isset($video) ? '' : 'required' }}
                               class="text-xs text-neutral-500 file:mr-6 file:py-3 file:px-6 file:border-0 file:text-[10px] file:font-bold file:uppercase file:tracking-widest file:bg-black file:text-white hover:file:bg-neutral-800 transition-all cursor-pointer w-full">
                        @error('thumbnail') <p class="text-red-600 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                <hr class="border-neutral-50">

                {{-- Status Toggle --}}
                <div class="flex items-center justify-between p-6 bg-[#F8F8F8] border border-transparent hover:border-black transition-all group">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Active Status</span>
                        <p class="text-[9px] text-neutral-400 uppercase mt-1">Toggle visibility on the homepage</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $video->is_active ?? true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-black"></div>
                    </label>
                </div>
            </div>

            <div class="pt-8 border-t border-neutral-100">
                <x-ui.button type="submit" variant="primary" class="w-full py-4 text-[10px] font-bold uppercase tracking-widest shadow-xl">
                    {{ isset($video) ? 'Save Changes' : 'Add Video to Gallery' }}
                </x-ui.button>
            </div>
        </form>
    </div>
</x-admin-layout>
