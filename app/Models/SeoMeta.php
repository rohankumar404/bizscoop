<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'seoable_id', 
        'seoable_type', 
        'meta_title', 
        'meta_description', 
        'meta_keywords', 
        'canonical_url', 
        'og_title', 
        'og_description', 
        'og_image', 
        'twitter_card'
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
