<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bike extends Model
{
    use HasFactory;

    protected $primaryKey = 'bike_id';

    protected $fillable = [
        'store_store_id',
        'plate_no',
        'model',
        'image',
        'status',
        'accessories',
    ];

    protected $casts = [
        'status' => 'string',
        'accessories' => 'array',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_store_id', 'store_id');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(BikePrice::class, 'bike_id', 'bike_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'bike_id', 'bike_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'bike_id', 'bike_id');
    }

    public function accessories(): HasOne
    {
        return $this->hasOne(Accessory::class, 'bike_id', 'bike_id');
    }
}
