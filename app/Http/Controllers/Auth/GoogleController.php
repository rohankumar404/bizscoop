<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google and log them in.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Google authentication failed. Please try again.']);
        }

        // 1. Find user by google_id
        $user = User::where('google_id', $googleUser->id)->first();

        if ($user) {
            Auth::login($user, true);
            $redirectTo = ($user->email === 'admin@bizscoop.com' || $user->hasRole('admin') || str_contains($user->email, 'admin'))
                ? route('admin.dashboard')
                : route('frontend.profile.dashboard');
            return redirect()->intended($redirectTo);
        }

        // 2. Find user by email
        $user = User::where('email', $googleUser->email)->first();

        if ($user) {
            // Update user to link Google Account
            $user->update([
                'google_id' => $googleUser->id,
            ]);
            Auth::login($user, true);
            $redirectTo = ($user->email === 'admin@bizscoop.com' || $user->hasRole('admin') || str_contains($user->email, 'admin'))
                ? route('admin.dashboard')
                : route('frontend.profile.dashboard');
            return redirect()->intended($redirectTo);
        }

        // 3. Create a new user
        $newUser = User::create([
            'name' => $googleUser->name ?? $googleUser->nickname ?? 'Google User',
            'email' => $googleUser->email,
            'google_id' => $googleUser->id,
            'password' => Hash::make(Str::random(24)), // Random password for security
        ]);

        Auth::login($newUser, true);

        $redirectTo = ($newUser->email === 'admin@bizscoop.com' || $newUser->hasRole('admin') || str_contains($newUser->email, 'admin'))
            ? route('admin.dashboard')
            : route('frontend.profile.dashboard');

        return redirect()->intended($redirectTo);
    }
}
