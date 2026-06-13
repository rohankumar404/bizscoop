<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable(['name', 'email', 'password', 'google_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ── Interaction Relationships ────────────────────────────────────
    
    public function interactions()
    {
        return $this->belongsToMany(Post::class, 'post_user_interactions')
            ->withPivot(['is_bookmarked', 'is_favorite', 'is_liked', 'last_read_at'])
            ->withTimestamps();
    }

    public function bookmarkedPosts()
    {
        return $this->belongsToMany(Post::class, 'post_user_interactions')
            ->wherePivot('is_bookmarked', true)
            ->withPivot(['is_favorite', 'is_liked', 'last_read_at'])
            ->withTimestamps();
    }

    public function favoritePosts()
    {
        return $this->belongsToMany(Post::class, 'post_user_interactions')
            ->wherePivot('is_favorite', true)
            ->withPivot(['is_bookmarked', 'is_liked', 'last_read_at'])
            ->withTimestamps();
    }

    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'post_user_interactions')
            ->wherePivot('is_liked', true)
            ->withPivot(['is_bookmarked', 'is_favorite', 'last_read_at'])
            ->withTimestamps();
    }

    public function readPosts()
    {
        return $this->belongsToMany(Post::class, 'post_user_interactions')
            ->wherePivotNotNull('last_read_at')
            ->withPivot(['is_bookmarked', 'is_favorite', 'is_liked', 'last_read_at'])
            ->withTimestamps()
            ->orderByPivot('last_read_at', 'desc');
    }
}
