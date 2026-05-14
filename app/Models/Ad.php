<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = [
        'title',
        'position',
        'type',
        'content',
        'image',
        'link',
        'is_active',
        'starts_at',
        'expires_at',
        'views',
        'clicks',
    ];

    protected $casts = [
        'position' => 'array',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}
