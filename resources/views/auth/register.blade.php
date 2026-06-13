<x-guest-layout>
    <!-- Header of the Form -->
    <div class="mb-8 select-none">
        <h2 class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-2">Registration Portal</h2>
        <h1 class="text-3xl font-black font-serif text-black leading-tight uppercase">Create Account</h1>
        <p class="text-xs text-gray-400 mt-2 font-medium">Join BizScoop to save articles, sync your reading history, and customize your experience.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div class="space-y-2">
            <label for="name" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-800 select-none">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="w-full px-4 py-3.5 bg-neutral-50 border border-neutral-200 focus:border-black focus:bg-white focus:ring-1 focus:ring-black outline-none transition-all text-sm rounded-none">
            <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs text-red-600 font-semibold" />
        </div>

        <!-- Email Address -->
        <div class="space-y-2">
            <label for="email" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-800 select-none">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="w-full px-4 py-3.5 bg-neutral-50 border border-neutral-200 focus:border-black focus:bg-white focus:ring-1 focus:ring-black outline-none transition-all text-sm rounded-none">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-600 font-semibold" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <label for="password" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-800 select-none">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full px-4 py-3.5 bg-neutral-50 border border-neutral-200 focus:border-black focus:bg-white focus:ring-1 focus:ring-black outline-none transition-all text-sm rounded-none">
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-600 font-semibold" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2">
            <label for="password_confirmation" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-800 select-none">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full px-4 py-3.5 bg-neutral-50 border border-neutral-200 focus:border-black focus:bg-white focus:ring-1 focus:ring-black outline-none transition-all text-sm rounded-none">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs text-red-600 font-semibold" />
        </div>

        <div>
            <button type="submit" class="w-full py-4 bg-black text-white hover:bg-neutral-900 text-xs font-bold uppercase tracking-widest transition-all rounded-none hover:shadow-lg flex items-center justify-center space-x-2 cursor-pointer">
                <span>Register</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </button>
        </div>

        <!-- Google Sign In Button -->
        <div class="relative flex py-2 items-center">
            <div class="flex-grow border-t border-neutral-200"></div>
            <span class="flex-shrink mx-4 text-[10px] font-bold uppercase tracking-widest text-neutral-400">or</span>
            <div class="flex-grow border-t border-neutral-200"></div>
        </div>

        <div>
            <a href="{{ route('frontend.auth.google') }}" class="w-full py-3.5 bg-white border border-neutral-200 hover:border-black text-black text-xs font-bold uppercase tracking-widest transition-all rounded-none hover:shadow-md flex items-center justify-center space-x-2 cursor-pointer no-underline">
                <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" width="16" height="16">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.85z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.85c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <span>Continue with Google</span>
            </a>
        </div>
    </form>

    <div class="mt-8 pt-6 border-t border-neutral-100 text-center">
        <p class="text-xs text-gray-400 font-semibold">
            Already registered? 
            <a href="{{ route('login') }}" class="text-black underline font-bold hover:text-neutral-800 transition-colors">
                Sign In
            </a>
        </p>
    </div>
</x-guest-layout>
