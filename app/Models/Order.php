<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'bike_id',
        'user_id',
        'member_id',
        'rental_plan',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'total_price',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'booking_date' => 'date',
        'status' => 'string',
        'total_price' => 'decimal:2',
    ];

    public function bike()
    {
        return $this->belongsTo(Bike::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class, 'related_order_id');
    }
} 