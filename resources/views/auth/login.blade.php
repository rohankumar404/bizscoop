<x-guest-layout>
    <!-- Header of the Form -->
    <div class="mb-8 select-none">
        <h2 class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-2">Access Portal</h2>
        <h1 class="text-3xl font-black font-serif text-black leading-tight uppercase">Admin Sign In</h1>
        <p class="text-xs text-gray-400 mt-2 font-medium">Please log in to your BizScoop editor account to manage content.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 text-xs font-semibold rounded-none" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <label for="email" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-800 select-none">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="w-full px-4 py-3.5 bg-neutral-50 border border-neutral-200 focus:border-black focus:bg-white focus:ring-1 focus:ring-black outline-none transition-all text-sm rounded-none">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-600 font-semibold" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <div class="flex justify-between items-center select-none">
                <label for="password" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-800">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-black transition-colors" href="{{ route('password.request') }}">
                        Forgot?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full px-4 py-3.5 bg-neutral-50 border border-neutral-200 focus:border-black focus:bg-white focus:ring-1 focus:ring-black outline-none transition-all text-sm rounded-none">
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-600 font-semibold" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center select-none">
            <input id="remember_me" type="checkbox" name="remember" 
                   class="w-4 h-4 text-black border-neutral-300 rounded-none focus:ring-black focus:ring-offset-0">
            <label for="remember_me" class="ml-2 text-xs text-gray-400 font-semibold select-none cursor-pointer">
                Keep me signed in on this device
            </label>
        </div>

        <div>
            <button type="submit" class="w-full py-4 bg-black text-white hover:bg-neutral-900 text-xs font-bold uppercase tracking-widest transition-all rounded-none hover:shadow-lg flex items-center justify-center space-x-2 cursor-pointer">
                <span>Sign In</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </button>
        </div>
    </form>
</x-guest-layout>
