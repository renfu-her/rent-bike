<?php

namespace App\Http\Controllers;

use App\Models\Carousel;
use App\Models\Store;
use Illuminate\Http\Request;

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
        $bikes = $store->bikes()
            ->with(['prices', 'accessories'])
            ->get();

        return view('store.detail', compact('store', 'bikes'));
    }
} 