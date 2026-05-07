<x-admin-layout>
    <x-slot:page-title>Dashboard Overview</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white p-6 border border-[var(--color-border)]">
            <p class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-2">Total Articles</p>
            <p class="text-3xl font-serif font-bold">1,284</p>
        </div>
        <div class="bg-white p-6 border border-[var(--color-border)]">
            <p class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-2">Total Views</p>
            <p class="text-3xl font-serif font-bold">84.2K</p>
        </div>
        <div class="bg-white p-6 border border-[var(--color-border)]">
            <p class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-2">New Subscribers</p>
            <p class="text-3xl font-serif font-bold">342</p>
        </div>
        <div class="bg-white p-6 border border-[var(--color-border)]">
            <p class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-2">Engagement Rate</p>
            <p class="text-3xl font-serif font-bold">4.8%</p>
        </div>
    </div>

    <div class="bg-white border border-[var(--color-border)]">
        <div class="p-6 border-b border-[var(--color-border)] flex justify-between items-center">
            <h2 class="font-bold">Recent Articles</h2>
            <x-ui.button variant="primary" size="sm">Create New</x-ui.button>
        </div>
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 bg-neutral-50 border-b border-[var(--color-border)]">
                    <th class="px-6 py-4">Title</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Author</th>
                    <th class="px-6 py-4">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--color-border)] text-sm">
                <tr>
                    <td class="px-6 py-4 font-bold">The Future of AI Governance</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase tracking-tighter">Published</span></td>
                    <td class="px-6 py-4 text-neutral-500">John Doe</td>
                    <td class="px-6 py-4 text-neutral-500">2 hours ago</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 font-bold">Market Trends 2026</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-bold uppercase tracking-tighter">Draft</span></td>
                    <td class="px-6 py-4 text-neutral-500">Jane Smith</td>
                    <td class="px-6 py-4 text-neutral-500">Yesterday</td>
                </tr>
            </tbody>
        </table>
    </div>
</x-admin-layout>
