<x-admin-layout>
    <x-slot:sectionTitle>Engagement</x-slot>
    <x-slot:pageTitle>{{ isset($poll) ? 'Edit Reader Poll' : 'Create New Poll' }}</x-slot>

    <div class="max-w-3xl">
        <form action="{{ isset($poll) ? route('admin.polls.update', $poll) : route('admin.polls.store') }}" 
              method="POST" 
              class="bg-white border border-[#E5E5E5] p-10 space-y-10 shadow-sm">
            @csrf
            @if(isset($poll)) @method('PUT') @endif

            <div class="border-b border-neutral-100 pb-8">
                <h3 class="text-2xl font-serif font-bold tracking-tight">Poll Configuration</h3>
                <p class="text-xs text-neutral-400 uppercase tracking-widest mt-2">Specify the user poll question and add at least two choice options.</p>
            </div>

            <div class="space-y-8">
                {{-- Question --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Poll Question</label>
                    <div class="md:col-span-2">
                        <input type="text" name="question" value="{{ old('question', $poll->question ?? '') }}" required 
                               class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm" 
                               placeholder="e.g. Which sector will drive GCC growth most in 2025?">
                        @error('question') <p class="text-red-600 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                <hr class="border-neutral-50">

                {{-- Options List with Alpine.js --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start" 
                     x-data="{ options: {{ json_encode(old('options', isset($poll) ? array_column($poll->options, 'text') : ['', ''])) }} }">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Choice Options</label>
                        <p class="text-[9px] text-neutral-400 uppercase mt-1">Minimum 2 choices required</p>
                    </div>
                    <div class="md:col-span-2 space-y-4">
                        <template x-for="(option, index) in options" :key="index">
                            <div class="flex items-center space-x-2">
                                <input type="text" name="options[]" x-model="options[index]" required
                                       class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm"
                                       placeholder="e.g. Tech & AI">
                                <button type="button" @click="if (options.length > 2) options.splice(index, 1)" 
                                        class="px-4 py-4 bg-red-50 text-red-600 hover:bg-red-100 font-bold text-xs uppercase tracking-widest transition-colors cursor-pointer">
                                    ✕
                                </button>
                            </div>
                        </template>

                        <button type="button" @click="options.push('')" 
                                class="w-full py-4 border border-dashed border-neutral-300 hover:border-black font-bold text-[10px] uppercase tracking-widest transition-colors cursor-pointer">
                            + Add Option
                        </button>
                        
                        @error('options') <p class="text-red-600 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p> @enderror
                        @error('options.*') <p class="text-red-600 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                <hr class="border-neutral-50">

                {{-- Status Toggle --}}
                <div class="flex items-center justify-between p-6 bg-[#F8F8F8] border border-transparent hover:border-black transition-all group">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Active Status</span>
                        <p class="text-[9px] text-neutral-400 uppercase mt-1">Make this the active site poll (will deactivate others)</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $poll->is_active ?? false) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-black"></div>
                    </label>
                </div>
            </div>

            <div class="pt-8 border-t border-neutral-100">
                <x-ui.button type="submit" variant="primary" class="w-full py-4 text-[10px] font-bold uppercase tracking-widest shadow-xl">
                    {{ isset($poll) ? 'Save Changes' : 'Create Reader Poll' }}
                </x-ui.button>
            </div>
        </form>
    </div>
</x-admin-layout>
