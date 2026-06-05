<x-admin-layout>
    <x-slot:section-title>Publications</x-slot>
    <x-slot:page-title>Magazines & Issues</x-slot>

    <x-slot:page-actions>
        <x-ui.button href="{{ route('admin.magazines.create') }}" variant="primary" size="md">
            Publish New Issue
        </x-ui.button>
    </x-slot>

    <div class="bg-white border border-[#E5E5E5] shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400 bg-[#F8F8F8] border-b border-[#E5E5E5]">
                        <th class="px-8 py-5">Cover</th>
                        <th class="px-8 py-5">Issue Title</th>
                        <th class="px-8 py-5">Issue #</th>
                        <th class="px-8 py-5">Pub. Date</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5] text-sm">
                    @forelse($magazines as $magazine)
                        <tr class="hover:bg-[#F8F8F8] transition-colors group">
                            <td class="px-8 py-6">
                                <div class="w-12 h-16 bg-neutral-100 border overflow-hidden shadow-sm">
                                    @if($magazine->hasMedia('cover_image'))
                                        <img src="{{ $magazine->getFirstMediaUrl('cover_image') }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-[8px] font-bold text-neutral-300">NO COVER</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-serif text-lg font-bold group-hover:text-[#000] transition-colors">{{ $magazine->title }}</p>
                                @if($magazine->hasMedia('pdf_file'))
                                    <a href="{{ $magazine->getFirstMediaUrl('pdf_file') }}" target="_blank" class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 hover:text-black flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        View PDF Document
                                    </a>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-neutral-500 font-mono text-xs">{{ $magazine->issue_number ?? 'N/A' }}</td>
                            <td class="px-8 py-6 text-neutral-500 text-xs">{{ $magazine->published_at->format('M d, Y') }}</td>
                            <td class="px-8 py-6">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $magazine->is_active ? 'bg-green-50 text-green-700' : 'bg-neutral-100 text-neutral-400' }}">
                                    {{ $magazine->is_active ? 'Active' : 'Draft' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end items-center space-x-4">
                                    <a href="{{ route('admin.magazines.edit', $magazine) }}" class="text-[10px] font-bold uppercase tracking-widest hover:underline">Edit</a>
                                    <form action="{{ route('admin.magazines.destroy', $magazine) }}" method="POST" onsubmit="return confirm('Archive this issue?')">
                                        @csrf @method('DELETE')
                                        <button class="text-[10px] font-bold uppercase tracking-widest text-red-600 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center text-neutral-400">
                                <p class="text-xs font-bold uppercase tracking-widest">No magazine issues found</p>
                                <a href="{{ route('admin.magazines.create') }}" class="text-[#000] underline mt-2 block">Publish your first issue</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($magazines->hasPages())
            <div class="px-8 py-6 border-t border-[#E5E5E5]">
                {{ $magazines->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
