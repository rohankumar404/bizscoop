<x-admin-layout>
    <x-slot:section-title>Settings</x-slot:section-title>
    <x-slot:page-title>Admin Credentials</x-slot:page-title>

    <div class="max-w-2xl">
        @if(session('success'))
            <div class="mb-8 p-4 bg-green-50 border border-green-100 text-green-800 text-xs font-bold uppercase tracking-widest">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.profile.update') }}" method="POST" class="bg-white border border-[#E5E5E5] p-10 space-y-10">
            @csrf
            @method('PATCH')

            <div class="border-b border-neutral-100 pb-8">
                <h3 class="text-2xl font-serif font-bold tracking-tight">Security & Access</h3>
                <p class="text-xs text-neutral-400 uppercase tracking-widest mt-2">Update your administrative login credentials below.</p>
            </div>

            <div class="space-y-8">
                {{-- Email --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Login ID (Email)</label>
                    <div class="md:col-span-2">
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                               class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm">
                        @error('email') <p class="text-red-600 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                <hr class="border-neutral-50">

                {{-- Current Password --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Current Password</label>
                        <p class="text-[9px] text-neutral-400 uppercase mt-1">Required to verify identity</p>
                    </div>
                    <div class="md:col-span-2">
                        <input type="password" name="current_password" required 
                               class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm">
                        @error('current_password') <p class="text-red-600 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                <hr class="border-neutral-50">

                {{-- New Password --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">New Password</label>
                        <p class="text-[9px] text-neutral-400 uppercase mt-1">Leave blank to keep current</p>
                    </div>
                    <div class="md:col-span-2">
                        <input type="password" name="password" 
                               class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm">
                        @error('password') <p class="text-red-600 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Confirm Password</label>
                    <div class="md:col-span-2">
                        <input type="password" name="password_confirmation" 
                               class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm">
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-neutral-100">
                <x-ui.button type="submit" variant="primary" class="w-full py-4 text-[10px] font-bold uppercase tracking-widest">
                    Update Credentials
                </x-ui.button>
            </div>
        </form>
    </div>
</x-admin-layout>
