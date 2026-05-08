<x-admin-layout>
    <x-slot:section-title>Editorial</x-slot>
    <x-slot:page-title>Article Management</x-slot>
    
    <x-slot:page-actions>
        <x-ui.button variant="primary" size="md" href="{{ route('admin.posts.create') }}">
            New Article
        </x-ui.button>
    </x-slot>

    <div class="bg-white border border-[#E5E5E5] mb-8">
        <form action="{{ route('admin.posts.index') }}" method="GET" class="p-6 grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search articles..." class="w-full px-4 py-3 bg-[#F8F8F8] border-none text-xs focus:ring-1 focus:ring-black">
            </div>
            <div>
                <select name="category" class="w-full px-4 py-3 bg-[#F8F8F8] border-none text-xs font-bold uppercase tracking-widest focus:ring-1 focus:ring-black">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->getTranslation('name', 'en') }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="w-full px-4 py-3 bg-[#F8F8F8] border-none text-xs font-bold uppercase tracking-widest focus:ring-1 focus:ring-black">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full bg-black text-white py-3 text-[10px] font-bold uppercase tracking-widest hover:bg-neutral-800 transition-colors">Filter</button>
            </div>
            <div>
                <a href="{{ route('admin.posts.index') }}" class="w-full block text-center border border-black py-3 text-[10px] font-bold uppercase tracking-widest hover:bg-neutral-50 transition-colors">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white border border-[#E5E5E5]">
        <div class="px-8 py-4 border-b border-[#E5E5E5] flex justify-between items-center bg-[#F8F8F8]">
            <div class="flex items-center space-x-4">
                <select id="bulk-actions" class="px-4 py-2 bg-white border border-neutral-200 text-[10px] font-bold uppercase tracking-widest focus:ring-1 focus:ring-black">
                    <option value="">Bulk Actions</option>
                    <option value="delete">Move to Trash</option>
                    <option value="publish">Publish</option>
                </select>
                <button onclick="applyBulkAction()" class="px-4 py-2 bg-white border border-black text-[10px] font-bold uppercase tracking-widest hover:bg-neutral-50 transition-all">Apply</button>
            </div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Showing {{ $posts->firstItem() }}-{{ $posts->lastItem() }} of {{ $posts->total() }} results</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 border-b border-[#E5E5E5]">
                        <th class="px-8 py-5 w-10"><input type="checkbox" id="select-all" class="text-black focus:ring-black border-neutral-300"></th>
                        <th class="px-8 py-5">Article</th>
                        <th class="px-8 py-5">Category</th>
                        <th class="px-8 py-5">Author</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5">Metrics</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5] text-sm">
                    @forelse($posts as $post)
                        @php $translation = $post->translate(); @endphp
                        <tr class="hover:bg-[#F8F8F8] transition-colors group">
                            <td class="px-8 py-6"><input type="checkbox" name="ids[]" value="{{ $post->id }}" class="post-checkbox text-black focus:ring-black border-neutral-300"></td>
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-neutral-100 flex-shrink-0 overflow-hidden border border-neutral-200">
                                        @if($post->hasMedia('featured_image'))
                                            <img src="{{ $post->getFirstMedia('featured_image')->getUrl() }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-[8px] font-bold text-neutral-300">NO IMG</div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-serif text-lg font-bold group-hover:underline">{{ $translation->title ?? 'Untitled' }}</p>
                                        <p class="text-[10px] text-neutral-400 uppercase tracking-widest mt-1">{{ $post->type }} &bull; {{ $post->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-2 py-1 bg-neutral-100 text-neutral-500 text-[10px] font-bold uppercase tracking-widest">
                                    {{ $post->category->getTranslation('name', 'en') }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-neutral-500">{{ $post->author->name }}</td>
                            <td class="px-8 py-6">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-neutral-100 text-neutral-500',
                                        'published' => 'bg-green-100 text-green-800',
                                        'scheduled' => 'bg-blue-100 text-blue-800',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $statusColors[$post->status] }}">
                                    {{ $post->status }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-neutral-400 text-xs">
                                <div class="flex flex-col">
                                    <span>{{ number_format($post->views) }} views</span>
                                    <span>{{ $post->reading_time }} min read</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right space-x-3">
                                <a href="{{ route('admin.posts.edit', $post) }}" class="text-xs font-bold uppercase tracking-widest hover:underline">Edit</a>
                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-bold uppercase tracking-widest text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center text-neutral-400 font-serif italic text-xl">No articles found. Write your first story.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-8 py-6 border-t border-[#E5E5E5]">
            {{ $posts->links() }}
        </div>
    </div>
    @push('scripts')
    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.post-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        function applyBulkAction() {
            const action = document.getElementById('bulk-actions').value;
            const selected = Array.from(document.querySelectorAll('.post-checkbox:checked')).map(cb => cb.value);

            if (!action || selected.length === 0) {
                alert('Please select an action and at least one article.');
                return;
            }

            if (confirm(`Are you sure you want to ${action} the selected articles?`)) {
                // Here you would normally submit a hidden form
                console.log(`Applying ${action} to IDs:`, selected);
                // For now, alert the user or redirect with a query string
                alert('Bulk actions logic ready. Implementation requires a dedicated route/controller method.');
            }
        }
    </script>
    @endpush
</x-admin-layout>
