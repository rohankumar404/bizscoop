<x-admin-layout>
    <x-slot:section-title>Performance</x-slot>
    <x-slot:page-title>Editorial Overview</x-slot>
    
    <x-slot:page-actions>
        <x-ui.button href="{{ route('admin.posts.create') }}" variant="primary" size="md">
            New Article
        </x-ui.button>
    </x-slot>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
        <x-admin.stat-card title="Total Monthly Views" value="1.2M" change="12.4%" trend="up" />
        <x-admin.stat-card title="Active Subscribers" value="48,204" change="3.1%" trend="up" />
        <x-admin.stat-card title="Avg. Reading Time" value="4m 32s" change="0.5%" trend="down" />
        <x-admin.stat-card title="Engagement Rate" value="18.6%" change="2.4%" trend="up" />
    </div>

    {{-- Recent Activity --}}
    <div class="bg-white border border-[#E5E5E5]">
        <div class="px-8 py-6 border-b border-[#E5E5E5] flex justify-between items-center">
            <h3 class="text-sm font-bold uppercase tracking-widest">Recent Editorial Activity</h3>
            <a href="{{ route('admin.posts.index') }}" class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-black transition-colors">View All Archive</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 bg-[#F8F8F8] border-b border-[#E5E5E5]">
                        <th class="px-8 py-5">Article Headline</th>
                        <th class="px-8 py-5">Section</th>
                        <th class="px-8 py-5">Author</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Last Edited</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5] text-sm">
                    <tr class="hover:bg-[#F8F8F8] transition-colors group">
                        <td class="px-8 py-6 font-serif text-lg font-bold group-hover:underline">The Great BizScoop Paradigm: Why Neutrality is Gold</td>
                        <td class="px-8 py-6"><span class="px-2 py-1 bg-neutral-100 text-neutral-500 text-[10px] font-bold uppercase tracking-widest">Business</span></td>
                        <td class="px-8 py-6 text-neutral-500">John Editorial</td>
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Published
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right text-neutral-400 text-xs">2h ago</td>
                    </tr>
                    <tr class="hover:bg-[#F8F8F8] transition-colors group">
                        <td class="px-8 py-6 font-serif text-lg font-bold group-hover:underline">Global indices hit record highs amidst tech surge</td>
                        <td class="px-8 py-6"><span class="px-2 py-1 bg-neutral-100 text-neutral-500 text-[10px] font-bold uppercase tracking-widest">Markets</span></td>
                        <td class="px-8 py-6 text-neutral-500">Jane Analyst</td>
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Review Pending
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right text-neutral-400 text-xs">Yesterday</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
