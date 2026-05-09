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
        // ── Status ──────────────────────────────────────
        'is_active',
        'is_featured',
        // ── Display toggles ─────────────────────────────
        'show_in_header',
        'show_in_homepage',
        'show_in_hero',
        'show_in_footer',
        'show_in_mobile_menu',
        // ── Content toggles ─────────────────────────────
        'allow_sponsored_posts',
        'enable_trending_section',
        'enable_category_slider',
        'hide_from_search',
        'premium_badge',
        'mega_menu',
        // ── Layout controls ──────────────────────────────
        'layout_type',
        'posts_per_section',
        'hero_priority',
        'desktop_menu_order',
        'mobile_menu_order',
        // ── Style ────────────────────────────────────────
        'color',
        'icon',
    ];

    public $translatable = ['name', 'description'];

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    protected $casts = [
        'is_active'               => 'boolean',
        'is_featured'             => 'boolean',
        'show_in_header'          => 'boolean',
        'show_in_homepage'        => 'boolean',
        'show_in_hero'            => 'boolean',
        'show_in_footer'          => 'boolean',
        'show_in_mobile_menu'     => 'boolean',
        'allow_sponsored_posts'   => 'boolean',
        'enable_trending_section' => 'boolean',
        'enable_category_slider'  => 'boolean',
        'hide_from_search'        => 'boolean',
        'premium_badge'           => 'boolean',
        'mega_menu'               => 'boolean',
        'posts_per_section'       => 'integer',
        'hero_priority'           => 'integer',
        'desktop_menu_order'      => 'integer',
        'mobile_menu_order'       => 'integer',
    ];

    // ── Layout type options ────────────────────────────────────
    public const LAYOUT_TYPES = [
        'grid'     => 'Grid',
        'slider'   => 'Slider / Carousel',
        'featured' => 'Featured + Cards',
        'mixed'    => 'Mixed Editorial',
    ];

    // ── Relationships ──────────────────────────────────────────
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

    // ── Scopes ────────────────────────────────────────────────
    public function scopeActive($q)        { return $q->where('is_active', true); }
    public function scopeTopLevel($q)      { return $q->whereNull('parent_id'); }
    public function scopeInHeader($q)      { return $q->where('show_in_header', true); }
    public function scopeInHero($q)        { return $q->where('show_in_hero', true)->orderBy('hero_priority'); }
    public function scopeInFooter($q)      { return $q->where('show_in_footer', true); }
    public function scopeFeatured($q)      { return $q->where('is_featured', true); }
    public function scopePremium($q)       { return $q->where('premium_badge', true); }

    // ── Helpers ───────────────────────────────────────────────
    public function getLayoutLabel(): string
    {
        return self::LAYOUT_TYPES[$this->layout_type] ?? 'Grid';
    }

    public function getThumbnailUrl(): string
    {
        return $this->getFirstMediaUrl('category_image') ?: '';
    }

    public function getBannerUrl(): string
    {
        return $this->getFirstMediaUrl('category_banner') ?: '';
    }

    public function getIconUrl(): string
    {
        return $this->getFirstMediaUrl('category_icon') ?: '';
    }

    // ── Media collections ─────────────────────────────────────
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('category_image')->singleFile();
        $this->addMediaCollection('category_banner')->singleFile();
        $this->addMediaCollection('category_icon')->singleFile();
    }

    // ── Post stats ────────────────────────────────────────────
    public function getPostsCountAttribute(): int
    {
        return $this->posts()->where('status', 'published')->count();
    }
}
