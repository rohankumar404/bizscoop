<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserDashboardController extends Controller
{
    /**
     * Display the user dashboard with saved articles, favorites, likes, and history.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $savedPosts = $user->bookmarkedPosts()
            ->with(['translations', 'category', 'media', 'author'])
            ->latest('post_user_interactions.updated_at')
            ->get();

        $favoritePosts = $user->favoritePosts()
            ->with(['translations', 'category', 'media', 'author'])
            ->latest('post_user_interactions.updated_at')
            ->get();

        $likedPosts = $user->likedPosts()
            ->with(['translations', 'category', 'media', 'author'])
            ->latest('post_user_interactions.updated_at')
            ->get();

        $readHistory = $user->readPosts()
            ->with(['translations', 'category', 'media', 'author'])
            ->get();

        return view('frontend.profile.dashboard', compact('savedPosts', 'favoritePosts', 'likedPosts', 'readHistory'));
    }

    /**
     * Update user details or password from dashboard settings tab.
     */
    public function updateSettings(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->name = $validated['name'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('status', 'settings-updated');
    }
}
