<x-admin-layout>
    <x-slot:sectionTitle>Media</x-slot>
    <x-slot:pageTitle>Video Gallery</x-slot>

    <x-slot:pageActions>
        <x-ui.button href="{{ route('admin.videos.create') }}" variant="primary" size="md">
            Add New Video
        </x-ui.button>
    </x-slot>

    <div class="bg-white border border-[#E5E5E5] shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400 bg-[#F8F8F8] border-b border-[#E5E5E5]">
                        <th class="px-8 py-5">Thumbnail</th>
                        <th class="px-8 py-5">Video Title</th>
                        <th class="px-8 py-5">YouTube Link</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5] text-sm">
                    @forelse($videos as $video)
                        <tr class="hover:bg-[#F8F8F8] transition-colors group">
                            <td class="px-8 py-6">
                                <div class="w-20 h-12 bg-neutral-100 border overflow-hidden shadow-sm relative">
                                    @if($video->hasMedia('thumbnail'))
                                        <img src="{{ $video->getFirstMediaUrl('thumbnail') }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-[8px] font-bold text-neutral-300">NO IMAGE</div>
                                    @endif
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-serif text-lg font-bold group-hover:text-[#e60000] transition-colors">{{ $video->title }}</p>
                            </td>
                            <td class="px-8 py-6 text-neutral-500 text-xs">
                                <a href="{{ $video->youtube_url }}" target="_blank" class="hover:underline flex items-center">
                                    {{ Str::limit($video->youtube_url, 30) }}
                                    <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $video->is_active ? 'bg-green-50 text-green-700' : 'bg-neutral-100 text-neutral-400' }}">
                                    {{ $video->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end items-center space-x-4">
                                    <a href="{{ route('admin.videos.edit', $video) }}" class="text-[10px] font-bold uppercase tracking-widest hover:underline">Edit</a>
                                    <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" onsubmit="return confirm('Delete this video?')">
                                        @csrf @method('DELETE')
                                        <button class="text-[10px] font-bold uppercase tracking-widest text-red-600 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center text-neutral-400">
                                <p class="text-xs font-bold uppercase tracking-widest">No videos found</p>
                                <a href="{{ route('admin.videos.create') }}" class="text-[#e60000] underline mt-2 block">Add your first video</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($videos->hasPages())
            <div class="px-8 py-6 border-t border-[#E5E5E5]">
                {{ $videos->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
