<x-admin-layout>
    <x-slot:section-title>Management</x-slot>
    <x-slot:page-title>Ads & Sponsors</x-slot>
    
    <x-slot:page-actions>
        <x-ui.button variant="primary" size="md" href="{{ route('admin.ads.create') }}">
            New Campaign
        </x-ui.button>
    </x-slot>

    <div class="bg-white border border-[#E5E5E5]">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 bg-[#F8F8F8] border-b border-[#E5E5E5]">
                        <th class="px-8 py-5">Campaign</th>
                        <th class="px-8 py-5">Position</th>
                        <th class="px-8 py-5">Performance</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5] text-sm">
                    @forelse($ads as $ad)
                        <tr class="hover:bg-[#F8F8F8] transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-10 bg-neutral-100 flex-shrink-0 overflow-hidden border">
                                        @if($ad->image)
                                            <img src="{{ Storage::url($ad->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-[8px] font-bold text-neutral-400 uppercase tracking-widest">Code</div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold">{{ $ad->title }}</p>
                                        <p class="text-[10px] text-neutral-400 uppercase tracking-widest mt-1">{{ $ad->type }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-2 py-1 bg-neutral-100 text-neutral-500 text-[10px] font-bold uppercase tracking-widest">
                                    {{ $ad->position }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-neutral-400 text-xs">
                                <div class="flex flex-col">
                                    <span>{{ number_format($ad->views) }} views</span>
                                    <span>{{ number_format($ad->clicks) }} clicks</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-2 py-1 {{ $ad->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-[8px] font-bold uppercase tracking-widest">
                                    {{ $ad->is_active ? 'Active' : 'Paused' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right space-x-3">
                                <a href="{{ route('admin.ads.edit', $ad) }}" class="text-xs font-bold uppercase tracking-widest hover:underline">Edit</a>
                                <form action="{{ route('admin.ads.destroy', $ad) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-bold uppercase tracking-widest text-red-600 hover:underline" onclick="return confirm('Delete campaign?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center text-neutral-400 font-serif italic text-xl">No advertising campaigns found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
