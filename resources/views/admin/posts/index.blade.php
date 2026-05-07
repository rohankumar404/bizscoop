<x-admin-layout>
    <x-slot:section-title>Editorial</x-slot>
    <x-slot:page-title>Article Management</x-slot>
    
    <x-slot:page-actions>
        <x-ui.button variant="primary" size="md" href="{{ route('admin.posts.create') }}">
            New Article
        </x-ui.button>
    </x-slot>

    <div class="bg-white border border-[#E5E5E5]">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 bg-[#F8F8F8] border-b border-[#E5E5E5]">
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
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-neutral-100 flex-shrink-0 overflow-hidden">
                                        @if($post->hasMedia('featured_image'))
                                            <img src="{{ $post->getFirstMediaUrl('featured_image') }}" class="w-full h-full object-cover">
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
</x-admin-layout>
