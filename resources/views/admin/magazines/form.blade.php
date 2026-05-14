<x-admin-layout>
    <x-slot:section-title>Publications</x-slot>
    <x-slot:page-title>{{ isset($magazine) ? 'Edit Issue' : 'Publish New Issue' }}</x-slot>

    <div class="max-w-3xl">
        <form action="{{ isset($magazine) ? route('admin.magazines.update', $magazine) : route('admin.magazines.store') }}" 
              method="POST" enctype="multipart/form-data" 
              class="bg-white border border-[#E5E5E5] p-10 space-y-10 shadow-sm">
            @csrf
            @if(isset($magazine)) @method('PUT') @endif

            <div class="border-b border-neutral-100 pb-8">
                <h3 class="text-2xl font-serif font-bold tracking-tight">Issue Metadata</h3>
                <p class="text-xs text-neutral-400 uppercase tracking-widest mt-2">Manage the identity and files of this magazine publication.</p>
            </div>

            <div class="space-y-8">
                {{-- Title --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Issue Title</label>
                    <div class="md:col-span-2">
                        <input type="text" name="title" value="{{ old('title', $magazine->title ?? '') }}" required 
                               class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm" 
                               placeholder="e.g. The Innovation Issue 2026">
                        @error('title') <p class="text-red-600 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Issue Number & Date --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Issue Number</label>
                        <input type="text" name="issue_number" value="{{ old('issue_number', $magazine->issue_number ?? '') }}" 
                               class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm mt-2" 
                               placeholder="e.g. Vol. 12">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Publication Date</label>
                        <input type="date" name="published_at" value="{{ old('published_at', isset($magazine) ? $magazine->published_at->format('Y-m-d') : date('Y-m-d')) }}" required 
                               class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm mt-2">
                    </div>
                </div>

                <hr class="border-neutral-50">

                {{-- Cover Image --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Cover Image</label>
                        <p class="text-[9px] text-neutral-400 uppercase mt-1">Recommended: 800x1100px</p>
                    </div>
                    <div class="md:col-span-2">
                        @if(isset($magazine) && $magazine->hasMedia('cover_image'))
                            <div class="mb-4 w-24 h-32 border overflow-hidden bg-neutral-50">
                                <img src="{{ $magazine->getFirstMediaUrl('cover_image') }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <input type="file" name="cover_image" {{ isset($magazine) ? '' : 'required' }}
                               class="text-xs text-neutral-500 file:mr-6 file:py-3 file:px-6 file:border-0 file:text-[10px] file:font-bold file:uppercase file:tracking-widest file:bg-black file:text-white hover:file:bg-neutral-800 transition-all cursor-pointer w-full">
                        @error('cover_image') <p class="text-red-600 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- PDF File --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Magazine PDF</label>
                        <p class="text-[9px] text-neutral-400 uppercase mt-1">Digital Edition Document</p>
                    </div>
                    <div class="md:col-span-2">
                        @if(isset($magazine) && $magazine->hasMedia('pdf_file'))
                            <p class="text-[10px] font-bold text-green-600 uppercase mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                Current PDF: {{ $magazine->getFirstMedia('pdf_file')->file_name }}
                            </p>
                        @endif
                        <input type="file" name="pdf_file" {{ isset($magazine) ? '' : 'required' }} accept=".pdf"
                               class="text-xs text-neutral-500 file:mr-6 file:py-3 file:px-6 file:border-0 file:text-[10px] file:font-bold file:uppercase file:tracking-widest file:bg-black file:text-white hover:file:bg-neutral-800 transition-all cursor-pointer w-full">
                        @error('pdf_file') <p class="text-red-600 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                <hr class="border-neutral-50">

                {{-- Status Toggle --}}
                <div class="flex items-center justify-between p-6 bg-[#F8F8F8] border border-transparent hover:border-black transition-all group">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Publish Status</span>
                        <p class="text-[9px] text-neutral-400 uppercase mt-1">Make this issue visible to the public</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $magazine->is_active ?? true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-black"></div>
                    </label>
                </div>
            </div>

            <div class="pt-8 border-t border-neutral-100">
                <x-ui.button type="submit" variant="primary" class="w-full py-4 text-[10px] font-bold uppercase tracking-widest shadow-xl">
                    {{ isset($magazine) ? 'Save Changes' : 'Publish Magazine Issue' }}
                </x-ui.button>
            </div>
        </form>
    </div>
</x-admin-layout>
