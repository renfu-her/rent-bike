<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'name',
        'password',
        'email',
        'phone',
        'address',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];
}
