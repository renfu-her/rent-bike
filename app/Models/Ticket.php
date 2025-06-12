<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $primaryKey = 'ticket_id';

    protected $fillable = [
        'bike_id',
        'issued_time',
        'amount',
        'is_resolved',
        'related_order_id',
    ];

    protected $casts = [
        'issued_time' => 'datetime',
        'amount' => 'decimal:2',
        'is_resolved' => 'boolean',
    ];

    public function bike()
    {
        return $this->belongsTo(Bike::class);
    }

    public function relatedOrder()
    {
        return $this->belongsTo(Order::class, 'related_order_id');
    }
} 