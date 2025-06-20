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
        // 載入店家資訊，並只載入有關聯價格的機車，同時預載配件資訊
        $store->load(['bikes' => function ($query) {
            $query->whereHas('prices');
        }, 'bikes.prices', 'bikes.accessoryBikes.accessory']);

        // 將處理好的店家資訊傳到 view
        return view('store.detail', [
            'store' => $store,
            'member' => Auth::guard('member')->user()
        ]);
    }
} 