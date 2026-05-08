<x-admin-layout>
    <x-slot:section-title>Management</x-slot>
    <x-slot:page-title>Newsletter Subscribers</x-slot>
    
    <div class="bg-white border border-[#E5E5E5]">
        <div class="px-8 py-6 border-b border-[#E5E5E5] flex justify-between items-center bg-[#F8F8F8]">
            <h3 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Manage Email Subscriptions</h3>
            <div class="flex space-x-4">
                <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Total: {{ $subscribers->total() }}</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 border-b border-[#E5E5E5]">
                        <th class="px-8 py-5">Email Address</th>
                        <th class="px-8 py-5">Joined Date</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5] text-sm">
                    @forelse($subscribers as $subscriber)
                        <tr class="hover:bg-[#F8F8F8] transition-colors">
                            <td class="px-8 py-6 font-bold">{{ $subscriber->email }}</td>
                            <td class="px-8 py-6 text-neutral-500">{{ $subscriber->created_at->format('M d, Y') }}</td>
                            <td class="px-8 py-6">
                                <span class="px-2 py-1 {{ $subscriber->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-[8px] font-bold uppercase tracking-widest">
                                    {{ $subscriber->is_active ? 'Subscribed' : 'Unsubscribed' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right space-x-3">
                                <form action="{{ route('admin.newsletters.toggle', $subscriber) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold uppercase tracking-widest {{ $subscriber->is_active ? 'text-red-600' : 'text-green-600' }} hover:underline">
                                        {{ $subscriber->is_active ? 'Unsubscribe' : 'Re-subscribe' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.newsletters.destroy', $subscriber) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-bold uppercase tracking-widest text-neutral-400 hover:text-black transition-colors" onclick="return confirm('Remove permanently?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-neutral-400 font-serif italic text-xl">No subscribers yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-8 py-6 border-t border-[#E5E5E5]">
            {{ $subscribers->links() }}
        </div>
    </div>
</x-admin-layout>
