<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Str;

class Post extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'author_id', 
        'category_id', 
        'slug', 
        'status', 
        'type',
        'published_at', 
        'views', 
        'trending_score', 
        'reading_time', 
        'is_sponsored', 
        'is_trending',
        'is_featured'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_sponsored' => 'boolean',
        'is_trending' => 'boolean',
        'is_featured' => 'boolean',
        'views' => 'integer',
        'trending_score' => 'decimal:2',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(PostTranslation::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Calculate and update trending score.
     * Formula: (Views) / (HoursSincePublished + 2)^1.5
     * Manual boost if is_trending is true.
     */
    public function updateTrendingScore()
    {
        $hours = max(1, $this->published_at->diffInHours(now()));
        $baseScore = $this->views / pow($hours + 2, 1.5);
        
        // Manual boost
        if ($this->is_trending) {
            $baseScore *= 5; 
        }

        if ($this->is_featured) {
            $baseScore *= 2;
        }

        $this->update(['trending_score' => $baseScore]);
    }

    public function scopeTrending($query, $limit = 5)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('trending_score', 'desc')
            ->limit($limit);
    }

    /**
     * Helper to get translated content for current locale.
     */
    public function translate(?string $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations()->where('locale', $locale)->first() ?? $this->translations()->first();
    }

    /**
     * Calculate reading time based on content.
     */
    public static function calculateReadingTime($content)
    {
        $wordsPerMinute = 200;
        $wordCount = str_word_count(strip_tags($content));
        return (int) ceil($wordCount / $wordsPerMinute);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')->singleFile();
        $this->addMediaCollection('gallery');
    }
}
