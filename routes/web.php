<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;

Route::get('/', [StoreController::class, 'index'])->name('store.index');

Route::get('/store/{store}', [StoreController::class, 'detail'])->name('store.detail');

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::get('/signup', [LoginController::class, 'showSignUp'])->name('signup');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/signup', [LoginController::class, 'signUp']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
