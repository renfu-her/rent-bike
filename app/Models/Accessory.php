<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    use HasFactory;

    protected $primaryKey = 'accessory_id';

    protected $fillable = [
        'bike_id',
        'helmet_count',
        'has_lock',
        'has_toolkit',
    ];

    protected $casts = [
        'helmet_count' => 'integer',
        'has_lock' => 'boolean',
        'has_toolkit' => 'boolean',
    ];

    public function bike()
    {
        return $this->belongsTo(Bike::class);
    }
}
