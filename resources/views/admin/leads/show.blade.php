<x-admin-layout>
    @section('section-title', 'Lead Management')
    @section('page-title', 'Inquiry Details')

    @section('page-actions')
        <a href="{{ route('admin.leads.index') }}" class="px-6 py-2 text-xs font-bold uppercase tracking-widest bg-white border border-black hover:bg-neutral-50 transition-colors">Back to List</a>
    @endsection

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            {{-- Message Content --}}
            <div class="bg-white border border-[#E5E5E5] p-8 lg:p-12">
                <div class="mb-8">
                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded {{ $lead->type === 'service_inquiry' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }} mb-4 inline-block">
                        {{ str_replace('_', ' ', $lead->type) }}
                    </span>
                    <h1 class="font-serif text-3xl font-bold mb-2">
                        @if($lead->type === 'service_inquiry')
                            Inquiry for {{ $lead->metadata['service'] ?? 'Unknown Service' }}
                        @else
                            {{ $lead->subject }}
                        @endif
                    </h1>
                    <div class="text-neutral-400 text-sm font-medium">Received on {{ $lead->created_at->format('F d, Y \a\t H:i') }}</div>
                </div>

                <div class="prose prose-neutral max-w-none">
                    <p class="text-neutral-600 leading-relaxed whitespace-pre-line">{{ $lead->message }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            {{-- Lead Info Card --}}
            <div class="bg-white border border-[#E5E5E5] p-6">
                <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400 mb-6">Sender Information</h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-neutral-400 mb-1">Full Name</label>
                        <div class="font-bold text-lg">{{ $lead->name }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-neutral-400 mb-1">Email Address</label>
                        <a href="mailto:{{ $lead->email }}" class="font-bold text-blue-600 hover:underline break-all">{{ $lead->email }}</a>
                    </div>

                    @if($lead->type === 'service_inquiry')
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-neutral-400 mb-1">Selected Service</label>
                            <div class="font-bold">{{ $lead->metadata['service'] ?? 'N/A' }}</div>
                        </div>
                    @endif
                </div>

                <div class="mt-10 pt-6 border-t border-[#E5E5E5]">
                    <a href="mailto:{{ $lead->email }}?subject=Re: {{ $lead->subject ?? 'Your Inquiry to Bizscoop' }}" 
                       class="w-full inline-flex justify-center items-center px-6 py-4 bg-black text-white text-xs font-bold uppercase tracking-widest hover:bg-neutral-800 transition-colors">
                        Reply via Email
                    </a>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="bg-red-50 border border-red-100 p-6">
                <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] text-red-600 mb-4">Danger Zone</h3>
                <form action="{{ route('admin.leads.destroy', $lead) }}" method="POST" onsubmit="return confirm('Permanently delete this lead?')">
                    @csrf
                    @method('DELETE')
                    <button class="w-full text-left text-xs font-bold uppercase tracking-widest text-red-600 hover:text-red-700 transition-colors">
                        Archive & Delete Record
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
