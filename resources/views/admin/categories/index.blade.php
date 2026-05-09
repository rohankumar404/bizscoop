<x-admin-layout>
    <x-slot:section-title>Management</x-slot>
    <x-slot:page-title>Category Management</x-slot>
    
    <x-slot:page-actions>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.categories.trash') }}"
               class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Trash
            </a>
            <a href="{{ route('admin.categories.create') }}"
               class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Category
            </a>
        </div>
    </x-slot>

    <div class="px-6 py-6">

        {{-- ── Alerts ─────────────────────────────────────────── --}}
        @if(session('success'))
            <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 mb-5 text-sm font-medium">
                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 mb-5 text-sm font-medium">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ── Stats Bar ──────────────────────────────────────── --}}
        @php
            $total      = $categories->count();
            $active     = $categories->where('is_active', true)->count();
            $inHeader   = $categories->where('show_in_header', true)->count();
            $inHero     = $categories->where('show_in_hero', true)->count();
        @endphp
        <div class="grid grid-cols-4 gap-4 mb-6">
            @foreach([
                ['Total',       $total,    'bg-slate-700',  'M4 6h16M4 12h16M4 18h16'],
                ['Active',      $active,   'bg-green-600',  'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['In Header',   $inHeader, 'bg-blue-600',   'M4 6h16M4 12h8m-8 6h16'],
                ['In Hero',     $inHero,   'bg-red-600',    'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.921-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
            ] as [$label, $count, $bg, $path])
            <div class="bg-white rounded-xl border border-gray-200 px-5 py-4 flex items-center gap-4">
                <div class="w-10 h-10 {{ $bg }} rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $count }}</p>
                    <p class="text-xs text-gray-500 font-medium">{{ $label }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- ── Bulk Action + Search ─────────────────────────────── --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-gray-50">
                <form method="GET" class="flex items-center gap-3">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search categories…"
                           class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 w-56 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                    <select name="status" class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-red-500 outline-none">
                        <option value="">All Status</option>
                        <option value="active"   {{ request('status')==='active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status')==='inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <button class="text-sm font-medium text-white bg-gray-700 hover:bg-gray-800 px-4 py-1.5 rounded-lg">Filter</button>
                </form>

                <form id="bulkForm" action="{{ route('admin.categories.bulk') }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    <select name="action" class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-red-500 outline-none">
                        <option value="">Bulk Actions</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="hero_on">Show in Hero</option>
                        <option value="hero_off">Hide from Hero</option>
                        <option value="header_on">Show in Header</option>
                        <option value="header_off">Hide from Header</option>
                        <option value="delete">Move to Trash</option>
                    </select>
                    <button type="submit" class="text-sm font-medium text-white bg-red-600 hover:bg-red-700 px-4 py-1.5 rounded-lg">Apply</button>
                </form>
            </div>

            {{-- ── Category Table ──────────────────────────────── --}}
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                        <th class="px-4 py-3 w-8">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        </th>
                        <th class="px-4 py-3 w-6">⠿</th>
                        <th class="px-4 py-3">Category</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3 text-center">Posts</th>
                        <th class="px-4 py-3 text-center">Layout</th>
                        <th class="px-4 py-3 text-center">Header</th>
                        <th class="px-4 py-3 text-center">Hero</th>
                        <th class="px-4 py-3 text-center">Active</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="sortable-categories" class="divide-y divide-gray-50">
                    @forelse($categories as $cat)
                        @php $name = $cat->getTranslation('name','en'); @endphp
                        <tr class="hover:bg-gray-50 transition" data-id="{{ $cat->id }}">
                            <td class="px-4 py-3">
                                <input type="checkbox" name="ids[]" value="{{ $cat->id }}" form="bulkForm"
                                       class="cat-checkbox rounded border-gray-300 text-red-600 focus:ring-red-500">
                            </td>
                            <td class="px-4 py-3 text-gray-400 cursor-move drag-handle text-lg">⠿</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($cat->getFirstMediaUrl('category_image'))
                                        <img src="{{ $cat->getFirstMediaUrl('category_image') }}"
                                             class="w-9 h-9 rounded-lg object-cover border border-gray-200">
                                    @else
                                        <div class="w-9 h-9 rounded-lg flex items-center justify-center text-sm font-bold text-white"
                                             style="background:{{ $cat->color ?: '#e60000' }};">
                                            {{ mb_substr($name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $name }}</p>
                                        @if($cat->parent)
                                            <p class="text-xs text-gray-400">↳ {{ $cat->parent->getTranslation('name','en') }}</p>
                                        @endif
                                        @if($cat->premium_badge)
                                            <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded">
                                                ★ Premium
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500 font-mono text-xs">/{{ $cat->slug }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-white text-gray-700 text-xs font-bold">
                                    {{ $cat->posts_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs font-medium px-2 py-1 rounded bg-blue-50 text-blue-700">{{ $cat->getLayoutLabel() }}</span>
                            </td>

                            {{-- Quick toggles --}}
                            @foreach([
                                ['show_in_header', 'H'],
                                ['show_in_hero',   '★'],
                                ['is_active',      '●'],
                            ] as [$field, $label])
                            <td class="px-4 py-3 text-center">
                                <button
                                    onclick="quickToggle({{ $cat->id }}, '{{ $field }}', this)"
                                    data-value="{{ $cat->$field ? '1' : '0' }}"
                                    class="w-10 h-5 rounded-full transition-colors duration-200 relative inline-flex items-center focus:outline-none
                                           {{ $cat->$field ? 'bg-green-500' : 'bg-gray-200' }}"
                                    title="{{ str_replace('_', ' ', ucfirst($field)) }}">
                                    <span class="inline-block w-4 h-4 bg-white rounded-full shadow transform transition-transform duration-200
                                                 {{ $cat->$field ? 'translate-x-5' : 'translate-x-0.5' }}"></span>
                                </button>
                            </td>
                            @endforeach

                            <td class="px-4 py-3 text-center">
                                @if($cat->is_featured)
                                    <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded">Featured</span>
                                @else
                                    <span class="text-xs text-gray-400">Standard</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('frontend.category.show', $cat->slug) }}" target="_blank"
                                       class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded transition" title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $cat) }}"
                                       class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                                          onsubmit="return confirm('Move to trash?')">
                                        @csrf @method('DELETE')
                                        <button class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-16 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <p class="font-medium">No categories found</p>
                                <a href="{{ route('admin.categories.create') }}" class="text-red-600 text-sm hover:underline mt-1 inline-block">Create your first category →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-admin-layout>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
// Select All
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.cat-checkbox').forEach(cb => cb.checked = this.checked);
});

// Drag-and-drop order
const el = document.getElementById('sortable-categories');
if (el) {
    Sortable.create(el, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'bg-red-50',
        onEnd: function() {
            const ids = [...el.querySelectorAll('tr[data-id]')].map(r => r.dataset.id);
            fetch('{{ route('admin.categories.update-order') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ ids }),
            });
        },
    });
}

// AJAX Quick Toggle
function quickToggle(id, field, btn) {
    fetch(`/admin/categories/${id}/toggle`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ field }),
    })
    .then(r => r.json())
    .then(data => {
        const isOn = data.value;
        btn.dataset.value = isOn ? '1' : '0';
        btn.className = btn.className
            .replace(/bg-green-500|bg-gray-200/, isOn ? 'bg-green-500' : 'bg-gray-200');
        const knob = btn.querySelector('span');
        knob.className = knob.className
            .replace(/translate-x-5|translate-x-0\.5/, isOn ? 'translate-x-5' : 'translate-x-0.5');
    });
}
</script>
@endpush
