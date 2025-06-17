<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    use HasFactory;

    protected $primaryKey = 'accessory_id';

    protected $fillable = [
        'name',
        'price',
        'qty',
    ];

    protected $casts = [
        'helmet_count' => 'integer',
        'has_lock' => 'boolean',
        'has_toolkit' => 'boolean',
    ];

    public function bike()
    {
        return $this->belongsTo(Bike::class, 'bike_id', 'bike_id');
    }

    public function bikes()
    {
        return $this->belongsToMany(Bike::class, 'accessory_bike', 'accessory_id', 'bike_id')
            ->withPivot('qty', 'price', 'status')
            ->withTimestamps();
    }

    public function accessoryBikes()
    {
        return $this->hasMany(AccessoryBike::class, 'accessory_id', 'accessory_id');
    }
}
