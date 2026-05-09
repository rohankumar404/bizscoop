<x-admin-layout>
    <x-slot:section-title>Management</x-slot>
    <x-slot:page-title>Category Trash</x-slot>
    
    <x-slot:page-actions>
        <a href="{{ route('admin.categories.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-white rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Back
        </a>
    </x-slot>
    <div class="px-6 py-6">
        @if(session('success'))
            <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 mb-5 text-sm font-medium">
                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100 bg-gray-50">
                        <th class="px-5 py-3">Category</th>
                        <th class="px-5 py-3">Slug</th>
                        <th class="px-5 py-3">Deleted At</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3">
                                <p class="font-semibold text-gray-900">{{ $cat->getTranslation('name','en') }}</p>
                                @if($cat->parent)<p class="text-xs text-gray-400">↳ {{ $cat->parent->getTranslation('name','en') }}</p>@endif
                            </td>
                            <td class="px-5 py-3 font-mono text-xs text-gray-500">/{{ $cat->slug }}</td>
                            <td class="px-5 py-3 text-gray-400 text-xs">{{ $cat->deleted_at->format('d M Y, H:i') }}</td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.categories.restore', $cat->id) }}" method="POST">
                                        @csrf
                                        <button class="px-3 py-1.5 text-xs font-bold text-green-700 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition">
                                            ↩ Restore
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.categories.force-delete', $cat->id) }}" method="POST"
                                          onsubmit="return confirm('Permanently delete? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button class="px-3 py-1.5 text-xs font-bold text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition">
                                            🗑 Delete Forever
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <p class="font-medium">Trash is empty</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
