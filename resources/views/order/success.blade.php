@extends('layouts.app')

@section('title', '租車成功')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body text-center p-5">
                        <!-- 成功圖示 -->
                        <div class="mb-4">
                            <i class="fa-solid fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>

                        <!-- 成功標題 -->
                        <h2 class="text-main mb-4">租車預約成功！</h2>

                        <!-- 成功訊息 -->
                        <div class="alert alert-success mb-4">
                            <p class="mb-2"><strong>您已成功預約一台機車</strong></p>
                            <p class="mb-0">請在預約日期到指定店家取車</p>
                        </div>

                        <!-- 訂單資訊 -->
                        @if (isset($order))
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">預約詳情</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>訂單編號：</strong>{{ $order->order_number ?? 'N/A' }}</p>
                                            <p><strong>機車型號：</strong>{{ $order->bike->model ?? 'N/A' }}</p>
                                            <p><strong>租賃方案：</strong>{{ $order->rental_plan }}</p>
                                            <p><strong>預約日期：</strong>{{ $order->booking_date }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>取車門市：</strong>{{ $order->bike->store->name ?? 'N/A' }}</p>
                                            <p><strong>門市地址：</strong>{{ $order->bike->store->address ?? 'N/A' }}</p>
                                            <p><strong>總金額：</strong>${{ number_format($order->total_price) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- 提醒訊息 -->
                        <div class="alert alert-info mb-4">
                            <p class="mb-2"><strong>重要提醒：</strong></p>
                            <ul class="mb-0 text-start">
                                <li>請記得攜帶身份證件到門市取車</li>
                                <li>如果忘了預約日期，可以查詢「我的訂單」了解出租機車的狀況</li>
                                <li>如有任何問題，請聯繫門市或客服</li>
                            </ul>
                        </div>

                        <!-- 按鈕區域 -->
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('home') }}" class="btn btn-primary"
                                style="background-color: #3AC0D2; border-color: #3AC0D2;">
                                <i class="fa-solid fa-home me-2"></i>返回首頁
                            </a>
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-list me-2"></i>我的訂單
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
