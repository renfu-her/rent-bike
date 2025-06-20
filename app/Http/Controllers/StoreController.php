<?php

namespace App\Http\Controllers;

use App\Models\Carousel;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function index()
    {
        $carousels = Carousel::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        $stores = Store::with('bikes')
            ->take(6)
            ->get();

        return view('index', compact('carousels', 'stores'));
    }

    public function detail(Store $store)
    {
        // 載入店家所有機車，並預先載入價格與配件資訊
        $store->load(['bikes.prices', 'bikes.accessoryBikes.accessory']);

        // 為每台機車計算配件總價
        foreach ($store->bikes as $bike) {
            // 計算可用配件的總價
            $accessoriesPrice = $bike->accessoryBikes
                ->where('status', 1)
                ->sum(fn ($item) => $item->price * $item->qty);
            
            // 將計算好的配件總價附加到機車物件上
            $bike->accessories_price = $accessoriesPrice;
        }

        // 將處理好的店家資訊傳到 view
        return view('store.detail', [
            'store' => $store,
            'member' => Auth::guard('member')->user()
        ]);
    }
} 