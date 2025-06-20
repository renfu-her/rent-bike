<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;

Route::get('/', [StoreController::class, 'index'])->name('home');

Route::get('/store/{store}', [StoreController::class, 'detail'])->name('store.detail');

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::get('/signup', [LoginController::class, 'showSignUp'])->name('signup');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/signup', [LoginController::class, 'signUp']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth:member')->group(function () {

    // 訂單相關路由
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // 個人資料相關路由
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

});
