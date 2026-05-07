<x-admin-layout>
    <x-slot:section-title>Editorial</x-slot>
    <x-slot:page-title>Category Management</x-slot>
    
    <x-slot:page-actions>
        <x-ui.button variant="primary" size="md" href="{{ route('admin.categories.create') }}">
            Add Category
        </x-ui.button>
    </x-slot>

    <div class="bg-white border border-[#E5E5E5]">
        <div class="px-8 py-6 border-b border-[#E5E5E5] flex justify-between items-center bg-[#F8F8F8]">
            <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Drag rows to reorder</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="sortable-table">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 border-b border-[#E5E5E5]">
                        <th class="px-8 py-5 w-10"></th>
                        <th class="px-8 py-5">Name</th>
                        <th class="px-8 py-5">Slug</th>
                        <th class="px-8 py-5">Parent</th>
                        <th class="px-8 py-5">Visibility</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5] text-sm" id="category-rows">
                    @forelse($categories as $category)
                        <tr class="hover:bg-[#F8F8F8] transition-colors cursor-move" data-id="{{ $category->id }}">
                            <td class="px-8 py-6 text-neutral-300">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7 2a2 2 0 100 4h6a2 2 0 100-4H7zM7 8a2 2 0 100 4h6a2 2 0 100-4H7zM7 14a2 2 0 100 4h6a2 2 0 100-4H7z"></path></svg>
                            </td>
                            <td class="px-8 py-6 font-bold">{{ $category->getTranslation('name', app()->getLocale()) }}</td>
                            <td class="px-8 py-6 text-neutral-400 font-mono text-xs">{{ $category->slug }}</td>
                            <td class="px-8 py-6 text-neutral-500">
                                {{ $category->parent ? $category->parent->getTranslation('name', app()->getLocale()) : '—' }}
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex space-x-2">
                                    @if($category->show_in_header)
                                        <span class="px-2 py-0.5 bg-black text-white text-[8px] font-bold uppercase tracking-widest">Header</span>
                                    @endif
                                    @if($category->is_featured)
                                        <span class="px-2 py-0.5 bg-red-600 text-white text-[8px] font-bold uppercase tracking-widest">Featured</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right space-x-3">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-xs font-bold uppercase tracking-widest hover:underline">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-bold uppercase tracking-widest text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center text-neutral-400">No categories found. Start by creating one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        const el = document.getElementById('category-rows');
        if (el) {
            new Sortable(el, {
                animation: 150,
                ghostClass: 'bg-[#F8F8F8]',
                onEnd: function() {
                    const ids = Array.from(el.querySelectorAll('tr')).map(tr => tr.dataset.id);
                    fetch('{{ route("admin.categories.update-order") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ ids: ids })
                    });
                }
            });
        }
    </script>
    @endpush
</x-admin-layout>
