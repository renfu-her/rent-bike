<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $primaryKey = 'ticket_id';

    protected $fillable = [
        'ticket_number',
        'bike_id',
        'issued_time',
        'violation_location',
        'violation_description',
        'due_date',
        'fined_person_name',
        'fined_person_id_number',
        'amount',
        'issuer_name',
        'issuing_authority',
        'image',
        'is_resolved',
        'status',
        'handler_id',
        'related_order_id',
    ];

    protected $casts = [
        'issued_time' => 'datetime',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'is_resolved' => 'boolean',
    ];

    public function bike()
    {
        return $this->belongsTo(Bike::class, 'bike_id', 'bike_id');
    }

    public function relatedOrder()
    {
        return $this->belongsTo(Order::class, 'related_order_id', 'order_id');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handler_id', 'id');
    }
} 