<x-admin-layout>
    <x-slot:section-title>Management</x-slot:section-title>
    <x-slot:page-title>Edit Section: {{ $category->getTranslation('name','en') }}</x-slot:page-title>
    
    <x-slot:page-actions>
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.categories.index') }}" class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-black flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to List
            </a>
            <a href="{{ route('frontend.category.show', $category->slug) }}" target="_blank"
               class="px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-black bg-white border border-[#E5E5E5] hover:bg-[#F8F8F8] transition">
                View Live
            </a>
        </div>
    </x-slot:page-actions>
    
    <div class="px-8 py-8">
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 mb-8 text-[10px] font-bold uppercase tracking-widest">
                <p>Validation Failed. Please check the form below.</p>
            </div>
        @endif

        @include('admin.categories.form', [
            'category'         => $category,
            'formAction'       => route('admin.categories.update', $category),
            'formMethod'       => 'PUT',
            'parentCategories' => $parentCategories,
        ])
    </div>
</x-admin-layout>
