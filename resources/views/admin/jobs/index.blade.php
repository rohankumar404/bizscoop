<x-admin-layout>
    <x-slot:sectionTitle>Management</x-slot>
    <x-slot:pageTitle>Job Postings</x-slot>

    <x-slot:pageActions>
        <x-ui.button href="{{ route('admin.jobs.create') }}" variant="primary" size="md">
            Add New Job
        </x-ui.button>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-700 font-bold uppercase tracking-wider text-xs border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-[#E5E5E5] shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400 bg-[#F8F8F8] border-b border-[#E5E5E5]">
                        <th class="px-8 py-5">Job Title</th>
                        <th class="px-8 py-5">Location</th>
                        <th class="px-8 py-5">Type</th>
                        <th class="px-8 py-5">Order</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5] text-sm">
                    @forelse($jobs as $job)
                        <tr class="hover:bg-[#F8F8F8] transition-colors group">
                            <td class="px-8 py-6">
                                <p class="font-serif text-lg font-bold group-hover:text-[#e60000] transition-colors">{{ $job->title }}</p>
                                <span class="text-[10px] text-neutral-400 font-mono">{{ $job->slug }}</span>
                            </td>
                            <td class="px-8 py-6 text-neutral-500 font-bold">
                                {{ $job->location }}
                            </td>
                            <td class="px-8 py-6 text-neutral-500">
                                {{ $job->type }}
                            </td>
                            <td class="px-8 py-6 text-neutral-400 font-mono">
                                {{ $job->sort_order }}
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $job->is_active ? 'bg-green-50 text-green-700' : 'bg-neutral-100 text-neutral-400' }}">
                                    {{ $job->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end items-center space-x-4">
                                    <a href="{{ route('admin.jobs.edit', $job) }}" class="text-[10px] font-bold uppercase tracking-widest hover:underline">Edit</a>
                                    <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" onsubmit="return confirm('Delete this job posting?')">
                                        @csrf @method('DELETE')
                                        <button class="text-[10px] font-bold uppercase tracking-widest text-red-600 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center text-neutral-400">
                                <p class="text-xs font-bold uppercase tracking-widest">No job postings found</p>
                                <a href="{{ route('admin.jobs.create') }}" class="text-[#e60000] underline mt-2 block">Create your first job posting</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($jobs->hasPages())
            <div class="px-8 py-6 border-t border-[#E5E5E5]">
                {{ $jobs->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
