<x-admin-layout>
    <x-slot:section-title>Management</x-slot:section-title>
    <x-slot:page-title>Categories</x-slot:page-title>
    
    <x-slot:page-actions>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.categories.create') }}"
               class="flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-black rounded hover:bg-neutral-800 transition shadow-sm uppercase tracking-widest">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add New
            </a>
        </div>
    </x-slot:page-actions>

    <div class="px-8 py-8">

        {{-- ── Alerts ─────────────────────────────────────────── --}}
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 mb-8 text-xs font-bold uppercase tracking-widest flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button @click="$el.parentElement.remove()" class="text-green-500">✕</button>
            </div>
        @endif

        {{-- ── Tabs ──────────────────────────────────────────── --}}
        <div x-data="{ activeTab: 'list' }">

            <div class="flex items-center gap-0 mb-6 border-b border-[#E5E5E5]">
                <button @click="activeTab = 'list'"
                        :class="activeTab === 'list' ? 'border-b-2 border-black text-black' : 'text-neutral-400 hover:text-black'"
                        class="px-6 py-3 text-[11px] font-bold uppercase tracking-widest transition-colors -mb-px">
                    All Categories
                </button>
                <button @click="activeTab = 'reorder'"
                        :class="activeTab === 'reorder' ? 'border-b-2 border-black text-black' : 'text-neutral-400 hover:text-black'"
                        class="px-6 py-3 text-[11px] font-bold uppercase tracking-widest transition-colors -mb-px flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    Drag & Reorder
                </button>
                <a href="{{ route('admin.categories.trash') }}"
                   class="ml-auto px-6 py-3 text-[11px] font-bold uppercase tracking-widest text-neutral-400 hover:text-red-600 flex items-center gap-2 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Archive
                </a>
            </div>

            {{-- ══ TAB: List ══════════════════════════════════════ --}}
            <div x-show="activeTab === 'list'">
                <div class="bg-white border border-[#E5E5E5] overflow-hidden">
                    <div class="flex items-center justify-between px-8 py-5 border-b border-[#E5E5E5] bg-[#F8F8F8]">
                        <form method="GET" class="flex items-center gap-4">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Search categories..."
                                   class="text-xs font-medium border border-[#E5E5E5] px-4 py-2.5 w-64 focus:border-black outline-none bg-white">
                            <button class="text-[10px] font-bold uppercase tracking-widest text-white bg-black px-6 py-2.5 hover:bg-neutral-800 transition">Filter</button>
                            @if(request('search'))
                                <a href="{{ route('admin.categories.index') }}" class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-black">Clear</a>
                            @endif
                        </form>
                    </div>

                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-bold uppercase tracking-[0.15em] text-neutral-400 border-b border-[#E5E5E5]">
                                <th class="px-8 py-5">Order</th>
                                <th class="px-8 py-5">Section Name</th>
                                <th class="px-8 py-5">Url Slug</th>
                                <th class="px-8 py-5 text-center">Articles</th>
                                <th class="px-8 py-5 text-center">Visibility</th>
                                <th class="px-8 py-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E5E5]">
                            @forelse($categories as $cat)
                                @php $name = $cat->getTranslation('name','en'); @endphp
                                <tr class="hover:bg-[#F8F8F8] transition-colors group">
                                    <td class="px-8 py-6">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded bg-neutral-100 text-[11px] font-bold text-neutral-500">
                                            {{ $cat->order ?: '—' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            @if($cat->parent)
                                                <span class="text-neutral-400 font-mono text-base pl-6 flex-shrink-0">↳</span>
                                            @endif
                                            <div class="w-10 h-10 flex items-center justify-center text-xs font-bold text-white shadow-sm flex-shrink-0"
                                                 style="background:{{ $cat->color ?: '#111' }};">
                                                {{ mb_substr($name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-serif text-lg font-bold text-black">{{ $name }}</p>
                                                @if($cat->parent)
                                                    <p class="text-[10px] text-neutral-400 font-bold uppercase tracking-widest mt-0.5">Parent: {{ $cat->parent->getTranslation('name','en') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-neutral-400 font-mono text-xs">/{{ $cat->slug }}</td>
                                    <td class="px-8 py-6 text-center">
                                        <span class="inline-block px-3 py-1 bg-neutral-100 text-black text-[10px] font-bold rounded-full">
                                            {{ $cat->posts_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <button onclick="toggleActive({{ $cat->id }}, this)"
                                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest transition-all {{ $cat->is_active ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-red-50 text-red-700 border border-red-100' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $cat->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                            {{ $cat->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('admin.categories.edit', $cat) }}"
                                               class="text-[10px] font-bold uppercase tracking-widest text-black hover:underline">Edit</a>
                                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                                                  onsubmit="return confirm('Delete this section?')">
                                                @csrf @method('DELETE')
                                                <button class="text-[10px] font-bold uppercase tracking-widest text-red-600 hover:underline">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-20 text-center">
                                        <p class="text-sm font-medium text-neutral-400">No sections discovered yet.</p>
                                        <a href="{{ route('admin.categories.create') }}" class="text-xs font-bold text-black uppercase tracking-widest hover:underline mt-2 inline-block">Create One Now</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ══ TAB: Drag & Reorder ════════════════════════════ --}}
            <div x-show="activeTab === 'reorder'">
                <div class="bg-white border border-[#E5E5E5] overflow-hidden">
                    <div class="px-8 py-5 border-b border-[#E5E5E5] bg-[#F8F8F8] flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-black uppercase tracking-widest">Drag rows to set display order</p>
                            <p class="text-[10px] text-neutral-400 mt-0.5">This controls the order categories appear on the homepage and in the menu.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span id="reorder-status" class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest hidden">Saving…</span>
                            <span id="reorder-saved" class="text-[10px] font-bold text-green-600 uppercase tracking-widest hidden">✓ Saved</span>
                        </div>
                    </div>

                    <ul id="sortable-categories" class="divide-y divide-[#E5E5E5]">
                        @foreach($categories as $cat)
                            @php $name = $cat->getTranslation('name','en'); @endphp
                            <li class="sortable-item flex items-center gap-5 px-8 py-5 hover:bg-[#F8F8F8] cursor-grab active:cursor-grabbing select-none transition-colors"
                                data-id="{{ $cat->id }}">

                                {{-- Drag Handle --}}
                                <div class="drag-handle text-neutral-300 hover:text-neutral-500 transition-colors flex-shrink-0">
                                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="9" cy="5" r="1.5"/><circle cx="15" cy="5" r="1.5"/>
                                        <circle cx="9" cy="12" r="1.5"/><circle cx="15" cy="12" r="1.5"/>
                                        <circle cx="9" cy="19" r="1.5"/><circle cx="15" cy="19" r="1.5"/>
                                    </svg>
                                </div>

                                {{-- Order Badge --}}
                                <span class="order-badge inline-flex items-center justify-center w-8 h-8 rounded bg-neutral-100 text-[11px] font-black text-neutral-500 flex-shrink-0">
                                    {{ $loop->iteration }}
                                </span>

                                {{-- Color Dot + Name --}}
                                @if($cat->parent)
                                    <span class="text-neutral-400 font-mono text-base pl-6 flex-shrink-0">↳</span>
                                @endif
                                <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center text-xs font-bold text-white"
                                     style="background:{{ $cat->color ?: '#111' }};">
                                    {{ mb_substr($name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-black">{{ $name }}</p>
                                    @if($cat->parent)
                                        <p class="text-[10px] text-neutral-400 font-semibold mt-0.5">Sub-category of: {{ $cat->parent->getTranslation('name','en') }}</p>
                                    @endif
                                </div>

                                {{-- Slug --}}
                                <span class="text-xs font-mono text-neutral-400 hidden md:block">/{{ $cat->slug }}</span>

                                {{-- Status --}}
                                <span class="text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded {{ $cat->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                    {{ $cat->is_active ? 'Active' : 'Inactive' }}
                                </span>

                                {{-- Menu badge --}}
                                @if($cat->show_in_header)
                                    <span class="text-[9px] font-bold uppercase tracking-widest px-2 py-1 rounded bg-blue-50 text-blue-600">In Menu</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>

                <p class="mt-4 text-[10px] text-neutral-400 text-center">
                    Order is saved automatically after each drag. Categories with <span class="font-bold text-blue-600">In Menu</span> badge appear in the site navigation.
                </p>
            </div>

        </div>{{-- /x-data tabs --}}
    </div>

    @push('scripts')
{{-- SortableJS CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
// ── Toggle Active ────────────────────────────────────────────
function toggleActive(id, btn) {
    fetch(`/admin/categories/${id}/toggle`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ field: 'is_active' }),
    })
    .then(r => r.json())
    .then(data => {
        const isOn = data.value;
        btn.className = `inline-flex items-center gap-2 px-3 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest transition-all ${isOn ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-red-50 text-red-700 border border-red-100'}`;
        btn.innerHTML = `<span class="w-1.5 h-1.5 rounded-full ${isOn ? 'bg-green-500' : 'bg-red-500'}"></span> ${isOn ? 'Active' : 'Inactive'}`;
    });
}

// ── Drag & Reorder ───────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('sortable-categories');
    if (!list) return;

    const statusEl = document.getElementById('reorder-status');
    const savedEl  = document.getElementById('reorder-saved');
    let saveTimer  = null;

    Sortable.create(list, {
        handle: '.drag-handle',
        animation: 180,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        onEnd: function () {
            // Update order badge numbers after drag
            list.querySelectorAll('.sortable-item').forEach((row, idx) => {
                const badge = row.querySelector('.order-badge');
                if (badge) badge.textContent = idx + 1;
            });

            // Collect new order
            const ids = [...list.querySelectorAll('.sortable-item')].map(el => el.dataset.id);

            // Show saving indicator
            statusEl.classList.remove('hidden');
            savedEl.classList.add('hidden');
            clearTimeout(saveTimer);

            fetch('{{ route('admin.categories.update-order') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids: ids })
            })
            .then(r => r.json())
            .then(data => {
                statusEl.classList.add('hidden');
                savedEl.classList.remove('hidden');
                saveTimer = setTimeout(() => savedEl.classList.add('hidden'), 3000);
            })
            .catch(() => {
                statusEl.textContent = 'Error saving. Refresh and try again.';
                statusEl.classList.remove('hidden');
                statusEl.classList.add('text-red-500');
            });
        }
    });
});
</script>

<style>
.sortable-ghost {
    opacity: 0.4;
    background: #f0f0f0 !important;
}
.sortable-chosen {
    background: #fafafa !important;
    box-shadow: 0 4px 24px rgba(0,0,0,0.10);
}
</style>
@endpush
</x-admin-layout>
