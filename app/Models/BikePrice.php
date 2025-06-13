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
        'original_price',
        'price_amount',
        'image'
    ];

    protected $casts = [
        'rental_days' => 'integer',
        'original_price' => 'decimal:2',
        'price_amount' => 'decimal:2',
        'price_type' => 'string',
    ];

    public function bike()
    {
        return $this->belongsTo(Bike::class);
    }

    public function getFinalPriceAttribute()
    {
        if ($this->price_type === 'discount') {
            // 將折扣轉換為百分比（例如：95折 = 95%）
            return $this->original_price * ($this->price_amount / 100);
        }
        return $this->price_amount;
    }
}
