<x-admin-layout>
    <x-slot:section-title>Management</x-slot>
    <x-slot:page-title>Edit Category</x-slot>
    
    <x-slot:page-actions>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.categories.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Back
            </a>
            <a href="{{ route('frontend.category.show', $category->slug) }}" target="_blank"
               class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View Live
            </a>
        </div>
    </x-slot>
    
    <div class="mb-4 bg-white p-4 rounded-lg border border-gray-200">
        <div class="flex items-center gap-2">
            <h2 class="text-lg font-bold text-gray-900">{{ $category->getTranslation('name','en') }}</h2>
            @if($category->premium_badge)
                <span class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded">★ Premium</span>
            @endif
            @if(!$category->is_active)
                <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded">Inactive</span>
            @endif
        </div>
        <p class="text-sm text-gray-500 mt-0.5">
            /{{ $category->slug }} &nbsp;·&nbsp; {{ $category->posts()->count() }} posts
        </p>
    </div>
    <div class="px-6 py-6">
        @if(session('success'))
            <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 mb-5 text-sm font-medium">
                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 mb-5">
                <p class="text-sm font-semibold text-red-700 mb-1">Please fix:</p>
                <ul class="text-xs text-red-600 list-disc list-inside">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif
        @include('admin.categories.form', [
            'category'         => $category,
            'formAction'       => route('admin.categories.update', $category),
            'formMethod'       => 'PUT',
            'parentCategories' => $parentCategories,
            'layoutTypes'      => $layoutTypes,
        ])
    </div>
</x-admin-layout>
