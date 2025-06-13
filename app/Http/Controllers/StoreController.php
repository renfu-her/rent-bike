<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function detail(Store $store)
    {
        $store->load('bikes');
        return view('store.detail', compact('store'));
    }
} 