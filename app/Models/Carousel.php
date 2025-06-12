<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    protected $fillable = [
        'title', 'image', 'url', 'sort_order', 'is_active'
    ];
}
