<x-admin-layout>
    <x-slot:sectionTitle>Management</x-slot>
    <x-slot:pageTitle>{{ isset($job) ? 'Edit Job Posting' : 'Add New Job' }}</x-slot>

    <div class="max-w-3xl">
        <form action="{{ isset($job) ? route('admin.jobs.update', $job) : route('admin.jobs.store') }}" 
              method="POST" 
              class="bg-white border border-[#E5E5E5] p-10 space-y-10 shadow-sm">
            @csrf
            @if(isset($job)) @method('PUT') @endif

            <div class="border-b border-neutral-100 pb-8">
                <h3 class="text-2xl font-serif font-bold tracking-tight">Job Details</h3>
                <p class="text-xs text-neutral-400 uppercase tracking-widest mt-2">Publish an open role on the Careers page. Set description, requirements, and status.</p>
            </div>

            <div class="space-y-8">
                {{-- Job Title --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Job Title</label>
                    <div class="md:col-span-2">
                        <input type="text" name="title" value="{{ old('title', $job->title ?? '') }}" required 
                               class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm" 
                               placeholder="e.g. Senior Business Editor">
                        @error('title') <p class="text-red-600 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Location --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Location</label>
                    <div class="md:col-span-2">
                        <input type="text" name="location" value="{{ old('location', $job->location ?? '') }}" required 
                               class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm" 
                               placeholder="e.g. Dubai / Hybrid or Riyadh / Remote">
                        @error('location') <p class="text-red-600 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Job Type --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Job Type</label>
                    <div class="md:col-span-2">
                        <select name="type" required 
                                class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm">
                            <option value="Full-time" {{ old('type', $job->type ?? '') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                            <option value="Part-time" {{ old('type', $job->type ?? '') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                            <option value="Contract" {{ old('type', $job->type ?? '') == 'Contract' ? 'selected' : '' }}>Contract</option>
                            <option value="Internship" {{ old('type', $job->type ?? '') == 'Internship' ? 'selected' : '' }}>Internship</option>
                        </select>
                        @error('type') <p class="text-red-600 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                <hr class="border-neutral-50">

                {{-- Description --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900 mt-4">Description & Requirements</label>
                    <div class="md:col-span-2">
                        <textarea name="description" required rows="10" 
                                  class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm resize-y" 
                                  placeholder="Describe the responsibilities, required qualifications, and details for candidates...">{{ old('description', $job->description ?? '') }}</textarea>
                        @error('description') <p class="text-red-600 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Sort Order --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Sort Order</label>
                    <div class="md:col-span-2">
                        <input type="number" name="sort_order" value="{{ old('sort_order', $job->sort_order ?? 0) }}" 
                               class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm" 
                               placeholder="e.g. 0 or 1 for sorting">
                        @error('sort_order') <p class="text-red-600 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                <hr class="border-neutral-50">

                {{-- Status Toggle --}}
                <div class="flex items-center justify-between p-6 bg-[#F8F8F8] border border-transparent hover:border-black transition-all group">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Active Status</span>
                        <p class="text-[9px] text-neutral-400 uppercase mt-1">Toggle visibility on the Careers page</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $job->is_active ?? true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-black"></div>
                    </label>
                </div>
            </div>

            <div class="pt-8 border-t border-neutral-100 flex items-center justify-between gap-4">
                <a href="{{ route('admin.jobs.index') }}" class="w-1/3 py-4 text-center text-[10px] font-bold uppercase tracking-widest border border-black hover:bg-neutral-50 transition-all">Cancel</a>
                <x-ui.button type="submit" variant="primary" class="w-2/3 py-4 text-[10px] font-bold uppercase tracking-widest shadow-xl">
                    {{ isset($job) ? 'Save Job Posting' : 'Publish Job Posting' }}
                </x-ui.button>
            </div>
        </form>
    </div>
</x-admin-layout>
