<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
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
