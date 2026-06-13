<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostInteractionController extends Controller
{
    /**
     * Toggle bookmark state (Save for later)
     */
    public function toggleBookmark(Request $request, Post $post)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $interaction = DB::table('post_user_interactions')
            ->where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        if ($interaction) {
            $newValue = !$interaction->is_bookmarked;
            DB::table('post_user_interactions')
                ->where('user_id', $user->id)
                ->where('post_id', $post->id)
                ->update([
                    'is_bookmarked' => $newValue,
                    'updated_at' => now(),
                ]);
        } else {
            $newValue = true;
            DB::table('post_user_interactions')->insert([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'is_bookmarked' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'bookmarked' => $newValue
        ]);
    }

    /**
     * Toggle favorite state
     */
    public function toggleFavorite(Request $request, Post $post)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $interaction = DB::table('post_user_interactions')
            ->where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        if ($interaction) {
            $newValue = !$interaction->is_favorite;
            DB::table('post_user_interactions')
                ->where('user_id', $user->id)
                ->where('post_id', $post->id)
                ->update([
                    'is_favorite' => $newValue,
                    'updated_at' => now(),
                ]);
        } else {
            $newValue = true;
            DB::table('post_user_interactions')->insert([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'is_favorite' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'favorite' => $newValue
        ]);
    }

    /**
     * Toggle like state
     */
    public function toggleLike(Request $request, Post $post)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $interaction = DB::table('post_user_interactions')
            ->where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        if ($interaction) {
            $newValue = !$interaction->is_liked;
            DB::table('post_user_interactions')
                ->where('user_id', $user->id)
                ->where('post_id', $post->id)
                ->update([
                    'is_liked' => $newValue,
                    'updated_at' => now(),
                ]);
        } else {
            $newValue = true;
            DB::table('post_user_interactions')->insert([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'is_liked' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'liked' => $newValue
        ]);
    }

    /**
     * Record post in reading history
     */
    public function recordHistory(Request $request, Post $post)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => 'guest']);
        }

        $interaction = DB::table('post_user_interactions')
            ->where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        if ($interaction) {
            DB::table('post_user_interactions')
                ->where('user_id', $user->id)
                ->where('post_id', $post->id)
                ->update([
                    'last_read_at' => now(),
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('post_user_interactions')->insert([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'last_read_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['status' => 'success']);
    }
}
