<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessoryBike extends Model
{
    protected $table = 'accessory_bike';

    protected $fillable = [
        'bike_id',
        'accessory_id',
        'qty',
        'price',
        'status',
    ];

    public function bike()
    {
        return $this->belongsTo(Bike::class, 'bike_id', 'bike_id');
    }

    public function accessory()
    {
        return $this->belongsTo(Accessory::class, 'accessory_id', 'accessory_id');
    }
} 