<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Translatable\HasTranslations;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements Sortable, HasMedia
{
    use SoftDeletes, HasTranslations, SortableTrait, InteractsWithMedia;

    protected $fillable = [
        'parent_id', 
        'name', 
        'slug', 
        'description', 
        'order', 
        'is_active', 
        'is_featured', 
        'show_in_header', 
        'show_in_homepage',
        'show_in_hero',
        'show_in_footer',
        'show_in_mobile_menu',
        'allow_sponsored_posts',
        'enable_trending_section',
        'enable_category_slider',
        'hide_from_search',
        'premium_badge',
        'mega_menu',
        'layout_type',
        'posts_per_section',
        'hero_priority',
        'desktop_menu_order',
        'mobile_menu_order',
        'color',
        'icon'
    ];

    public $translatable = ['name', 'description'];

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'show_in_header' => 'boolean',
        'show_in_homepage' => 'boolean',
        'show_in_hero' => 'boolean',
        'show_in_footer' => 'boolean',
        'show_in_mobile_menu' => 'boolean',
        'allow_sponsored_posts' => 'boolean',
        'enable_trending_section' => 'boolean',
        'enable_category_slider' => 'boolean',
        'hide_from_search' => 'boolean',
        'premium_badge' => 'boolean',
        'mega_menu' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    /**
     * Register media collections for category images.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('category_image')->singleFile();
    }
}
