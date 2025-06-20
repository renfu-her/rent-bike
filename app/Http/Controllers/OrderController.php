<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 檢查會員是否已登入
        if (!Auth::guard('member')->check()) {
            return redirect()->route('login', ['return' => request()->fullUrl()]);
        }

        // 取得當前登入會員的訂單
        $orders = Order::where('member_id', Auth::guard('member')->id())
            ->with(['bike.store'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('order.index', compact('orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 檢查會員是否已登入
        if (!Auth::guard('member')->check()) {
            return redirect()->route('login', ['return' => request()->fullUrl()]);
        }

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

        // 從 rental_plan 字串中解析出價格和天數
        preg_match('/\$(\d{1,3}(,\d{3})*(\.\d{2})?)/', $validated['rental_plan'], $matches);
        $totalPrice = isset($matches[1]) ? (float)str_replace(',', '', $matches[1]) : 0;
        
        // 解析租賃天數
        preg_match('/(\d+)天/', $validated['rental_plan'], $dayMatches);
        $rentalDays = isset($dayMatches[1]) ? (int)$dayMatches[1] : 1;
        
        // 計算開始和結束時間
        $startTime = $validated['booking_date'] . ' 09:00:00'; // 假設早上9點開始
        $endTime = date('Y-m-d H:i:s', strtotime($startTime . ' +' . $rentalDays . ' days'));

        // 建立訂單
        $order = Order::create([
            'bike_id' => $validated['bike_id'],
            'member_id' => Auth::guard('member')->id(),
            'rental_plan' => $validated['rental_plan'],
            'booking_date' => $validated['booking_date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
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