<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 驗證表單資料
        $validated = $request->validate([
            'bike_id' => 'required|exists:bikes,bike_id',
            'rental_plan' => 'required|string',
            'booking_date' => 'required|date',
            // ... 其他第一步的個人資料驗證
            'id_number' => 'required|string',
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
        ]);

        // 從 rental_plan 字串中解析出價格
        preg_match('/\$(\d{1,3}(,\d{3})*(\.\d{2})?)/', $validated['rental_plan'], $matches);
        $totalPrice = isset($matches[1]) ? (float)str_replace(',', '', $matches[1]) : 0;

        // 建立訂單
        $order = Order::create([
            'bike_id' => $validated['bike_id'],
            'member_id' => Auth::guard('member')->id(),
            'rental_plan' => $validated['rental_plan'],
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['booking_date'], // 暫時只用預約日期
            'end_time' => $validated['booking_date'],   // 結束時間可再根據天數計算
            'status' => 'pending', // 預設為待處理
            'total_price' => $totalPrice,
            // ... 其他需要儲存的欄位
        ]);

        // 載入相關資料
        $order->load(['bike.store']);

        // 導向到成功頁面
        return view('order.success', compact('order'));
    }
} 