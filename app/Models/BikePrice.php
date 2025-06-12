<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikePrice extends Model
{
    use HasFactory;

    protected $primaryKey = 'price_id';

    protected $fillable = [
        'bike_id',
        'rental_days',
        'price_type',
        'price_amount',
    ];

    protected $casts = [
        'rental_days' => 'integer',
        'price_amount' => 'decimal:2',
        'price_type' => 'string',
    ];

    public function bike()
    {
        return $this->belongsTo(Bike::class);
    }
}
