<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'order_number',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'booking_date' => 'date',
        'status' => 'string',
        'total_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    /**
     * 生成訂單編號
     */
    public static function generateOrderNumber()
    {
        $today = Carbon::now()->format('Ymd');
        $prefix = "RENT{$today}";
        
        // 取得今天的最後一個訂單編號
        $lastOrder = self::where('order_number', 'like', $prefix . '%')
            ->orderBy('order_number', 'desc')
            ->first();
        
        if ($lastOrder) {
            // 提取流水號並加1
            $lastNumber = (int)substr($lastOrder->order_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function bike()
    {
        return $this->belongsTo(Bike::class, 'bike_id', 'bike_id');
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