<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bike extends Model
{
    use HasFactory;

    protected $primaryKey = 'bike_id';

    protected $fillable = [
        'store_id',
        'plate_no',
        'model',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function accessory()
    {
        return $this->hasOne(Accessory::class);
    }

    public function prices()
    {
        return $this->hasMany(BikePrice::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
