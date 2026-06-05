<x-admin-layout>
    <x-slot:sectionTitle>Engagement</x-slot>
    <x-slot:pageTitle>Reader Polls</x-slot>

    <x-slot:pageActions>
        <x-ui.button href="{{ route('admin.polls.create') }}" variant="primary" size="md">
            Create New Poll
        </x-ui.button>
    </x-slot>

    <div class="bg-white border border-[#E5E5E5] shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400 bg-[#F8F8F8] border-b border-[#E5E5E5]">
                        <th class="px-8 py-5">Question</th>
                        <th class="px-8 py-5">Options & Votes</th>
                        <th class="px-8 py-5 text-center">Total Votes</th>
                        <th class="px-8 py-5 text-center">Status</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5] text-sm">
                    @forelse($polls as $poll)
                        <tr class="hover:bg-[#F8F8F8] transition-colors group">
                            <td class="px-8 py-6 max-w-sm">
                                <p class="font-serif text-base font-bold group-hover:text-[#000] transition-colors">{{ $poll->question }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <div class="space-y-1.5 max-w-xs">
                                    @foreach($poll->options_with_percentages as $opt)
                                        <div class="flex items-center justify-between text-xs text-neutral-600">
                                            <span class="truncate pr-4">{{ $opt['text'] }}</span>
                                            <span class="font-bold flex-shrink-0">{{ $opt['votes'] }} votes ({{ $opt['percentage'] }}%)</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center font-bold text-neutral-700">
                                {{ $poll->total_votes }}
                            </td>
                            <td class="px-8 py-6 text-center">
                                <form action="{{ route('admin.polls.toggle-active', $poll) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest transition-all {{ $poll->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-neutral-100 text-neutral-400 hover:bg-neutral-200' }}">
                                        {{ $poll->is_active ? 'Active' : 'Inactive (Set Active)' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end items-center space-x-4">
                                    <a href="{{ route('admin.polls.edit', $poll) }}" class="text-[10px] font-bold uppercase tracking-widest hover:underline">Edit</a>
                                    <form action="{{ route('admin.polls.destroy', $poll) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this poll?')">
                                        @csrf @method('DELETE')
                                        <button class="text-[10px] font-bold uppercase tracking-widest text-red-600 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center text-neutral-400">
                                <p class="text-xs font-bold uppercase tracking-widest">No polls found</p>
                                <a href="{{ route('admin.polls.create') }}" class="text-[#000] underline mt-2 block">Create your first poll</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($polls->hasPages())
            <div class="px-8 py-6 border-t border-[#E5E5E5]">
                {{ $polls->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
