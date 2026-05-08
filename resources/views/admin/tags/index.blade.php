<x-admin-layout>
    <x-slot:section-title>Editorial</x-slot>
    <x-slot:page-title>Tag Management</x-slot>
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        {{-- List --}}
        <div class="lg:col-span-8">
            <div class="bg-white border border-[#E5E5E5]">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 bg-[#F8F8F8] border-b border-[#E5E5E5]">
                                <th class="px-8 py-5">Tag Name</th>
                                <th class="px-8 py-5">Slug</th>
                                <th class="px-8 py-5">Articles</th>
                                <th class="px-8 py-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E5E5] text-sm">
                            @forelse($tags as $tag)
                                <tr class="hover:bg-[#F8F8F8] transition-colors">
                                    <td class="px-8 py-6 font-bold">{{ $tag->name }}</td>
                                    <td class="px-8 py-6 text-neutral-400 font-mono text-xs">{{ $tag->slug }}</td>
                                    <td class="px-8 py-6 text-neutral-500">{{ $tag->posts_count }}</td>
                                    <td class="px-8 py-6 text-right space-x-3">
                                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs font-bold uppercase tracking-widest text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center text-neutral-400 font-serif italic text-xl">No tags found. Add one to get started.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-8 py-6 border-t border-[#E5E5E5]">
                    {{ $tags->links() }}
                </div>
            </div>
        </div>

        {{-- Add New --}}
        <div class="lg:col-span-4">
            <div class="bg-white border border-[#E5E5E5] p-8 sticky top-32">
                <h3 class="text-xs font-bold uppercase tracking-widest mb-8 border-b pb-4">Quick Add Tag</h3>
                <form action="{{ route('admin.tags.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Tag Name</label>
                        <input type="text" name="name" required class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm" placeholder="e.g. Technology">
                        @error('name') <p class="text-red-600 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <x-ui.button type="submit" variant="primary" class="w-full">Create Tag</x-ui.button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
