<x-admin-layout>
    @section('section-title', 'Management')
    @section('page-title', 'Leads & Inquiries')

    @section('page-actions')
        <div class="flex space-x-2">
            <a href="{{ route('admin.leads.index') }}" class="px-4 py-2 text-xs font-bold uppercase tracking-widest {{ !request('type') ? 'bg-black text-white' : 'bg-white text-neutral-400' }} border border-black transition-colors">All</a>
            <a href="{{ route('admin.leads.index', ['type' => 'contact']) }}" class="px-4 py-2 text-xs font-bold uppercase tracking-widest {{ request('type') == 'contact' ? 'bg-black text-white' : 'bg-white text-neutral-400' }} border border-black transition-colors">Contact Messages</a>
            <a href="{{ route('admin.leads.index', ['type' => 'service_inquiry']) }}" class="px-4 py-2 text-xs font-bold uppercase tracking-widest {{ request('type') == 'service_inquiry' ? 'bg-black text-white' : 'bg-white text-neutral-400' }} border border-black transition-colors">Service Inquiries</a>
        </div>
    @endsection

    <div class="bg-white border border-[#E5E5E5] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#E5E5E5] bg-[#FAFAFA]">
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-neutral-400">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-neutral-400">Type</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-neutral-400">Lead Info</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-neutral-400">Subject/Service</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-neutral-400">Date</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-neutral-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5]">
                    @forelse($leads as $lead)
                        <tr class="hover:bg-[#FDFDFD] transition-colors {{ !$lead->is_read ? 'bg-blue-50/30' : '' }}">
                            <td class="px-6 py-4">
                                @if(!$lead->is_read)
                                    <span class="flex h-2 w-2 rounded-full bg-blue-600"></span>
                                @else
                                    <span class="flex h-2 w-2 rounded-full bg-neutral-200"></span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-[9px] font-black uppercase tracking-tighter rounded {{ $lead->type === 'service_inquiry' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ str_replace('_', ' ', $lead->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold">{{ $lead->name }}</div>
                                <div class="text-[11px] text-neutral-500">{{ $lead->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($lead->type === 'service_inquiry')
                                    <span class="font-medium text-neutral-600">Inquiry for:</span> {{ $lead->metadata['service'] ?? 'N/A' }}
                                @else
                                    {{ $lead->subject }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-[11px] text-neutral-500 font-medium">
                                {{ $lead->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.leads.show', $lead) }}" class="p-2 text-neutral-400 hover:text-black transition-colors" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.leads.destroy', $lead) }}" method="POST" onsubmit="return confirm('Archive this lead?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="p-2 text-neutral-400 hover:text-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-neutral-400 italic text-sm">No leads found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($leads->hasPages())
            <div class="px-6 py-4 bg-[#FAFAFA] border-t border-[#E5E5E5]">
                {{ $leads->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
