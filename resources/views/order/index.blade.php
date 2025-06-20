@extends('layouts.app')

@section('title', '我的訂單')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-main mb-0">我的訂單</h2>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>返回首頁
                </a>
            </div>

            @if($orders->count() > 0)
                <div class="row g-4">
                    @foreach($orders as $order)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                @if($order->bike && $order->bike->image)
                                    <img src="{{ asset('storage/' . $order->bike->image) }}" 
                                         class="card-img-top" 
                                         alt="{{ $order->bike->model ?? '機車圖片' }}"
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                         style="height: 200px;">
                                        <i class="fa-solid fa-motorcycle fa-3x text-muted"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $order->bike->model ?? '未知機車' }}</h5>
                                    
                                    <div class="mb-3">
                                        <p class="card-text mb-1">
                                            <strong>訂單編號：</strong>
                                            <span class="text-primary fw-bold">{{ $order->order_number ?? 'N/A' }}</span>
                                        </p>
                                        <p class="card-text mb-1">
                                            <strong>租賃方案：</strong>
                                            <span class="text-primary">{{ $order->rental_plan }}</span>
                                        </p>
                                        <p class="card-text mb-1">
                                            <strong>預約日期：</strong>
                                            <span class="text-info">{{ $order->booking_date->format('Y-m-d') }}</span>
                                        </p>
                                        <p class="card-text mb-1">
                                            <strong>取車門市：</strong>
                                            <span class="text-secondary">{{ $order->bike->store->name ?? '未知門市' }}</span>
                                        </p>
                                        <p class="card-text mb-1">
                                            <strong>門市地址：</strong>
                                            <small class="text-muted">{{ $order->bike->store->address ?? '未知地址' }}</small>
                                        </p>
                                        <p class="card-text mb-1">
                                            <strong>總金額：</strong>
                                            <span class="text-danger fw-bold">${{ number_format($order->total_price) }}</span>
                                        </p>
                                    </div>

                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge 
                                                @switch($order->status)
                                                    @case('pending')
                                                        bg-warning text-dark
                                                        @break
                                                    @case('active')
                                                        bg-success
                                                        @break
                                                    @case('completed')
                                                        bg-info
                                                        @break
                                                    @case('cancelled')
                                                        bg-danger
                                                        @break
                                                    @default
                                                        bg-secondary
                                                @endswitch
                                                fs-6">
                                                @switch($order->status)
                                                    @case('pending')
                                                        待處理
                                                        @break
                                                    @case('active')
                                                        使用中
                                                        @break
                                                    @case('completed')
                                                        已完成
                                                        @break
                                                    @case('cancelled')
                                                        已取消
                                                        @break
                                                    @default
                                                        {{ $order->status }}
                                                @endswitch
                                            </span>
                                            <small class="text-muted">
                                                {{ $order->created_at->format('Y-m-d H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fa-solid fa-clipboard-list fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">目前沒有訂單</h4>
                    <p class="text-muted">您還沒有任何租車預約記錄</p>
                    <a href="{{ route('home') }}" class="btn btn-primary" style="background-color: #3AC0D2; border-color: #3AC0D2;">
                        <i class="fa-solid fa-motorcycle me-2"></i>立即租車
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 