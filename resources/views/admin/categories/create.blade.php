<x-admin-layout>
    <x-slot:section-title>Management</x-slot:section-title>
    <x-slot:page-title>New Section</x-slot:page-title>
    
    <x-slot:page-actions>
        <a href="{{ route('admin.categories.index') }}" class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-black flex items-center gap-2">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to List
        </a>
    </x-slot:page-actions>

    <div class="px-8 py-8">
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 mb-8 text-[10px] font-bold uppercase tracking-widest">
                <p>Validation Failed. Please check the form below.</p>
            </div>
        @endif

        @include('admin.categories.form', [
            'category'          => null,
            'formAction'        => route('admin.categories.store'),
            'formMethod'        => 'POST',
            'parentCategories'  => $parentCategories,
        ])
    </div>
</x-admin-layout>
