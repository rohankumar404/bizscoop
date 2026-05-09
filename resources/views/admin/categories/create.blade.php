<x-admin-layout>
    <x-slot:section-title>Management</x-slot>
    <x-slot:page-title>Create Category</x-slot>
    
    <x-slot:page-actions>
        <a href="{{ route('admin.categories.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Back
        </a>
    </x-slot>
    <div class="px-6 py-6">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 mb-5">
                <p class="text-sm font-semibold text-red-700 mb-1">Please fix the following errors:</p>
                <ul class="text-xs text-red-600 list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif
        @include('admin.categories.form', [
            'category'          => null,
            'formAction'        => route('admin.categories.store'),
            'formMethod'        => 'POST',
            'parentCategories'  => $parentCategories,
            'layoutTypes'       => $layoutTypes,
        ])
    </div>
</x-admin-layout>
