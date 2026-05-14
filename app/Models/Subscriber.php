<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'email',
        'first_name',
        'is_active',
        'unsubscribed_at',
    ];
}
