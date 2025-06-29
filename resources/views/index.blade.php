@extends('layouts.app')

@section('title', '首頁 - 機車出租平台')

@section('content')
    <!-- 輪播區塊 -->
    <div id="mainCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach ($carousels as $index => $carousel)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $carousel->image) }}" class="d-block w-100" alt="{{ $carousel->title }}"
                        style="height: 600px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block"
                        style="top:50%; bottom:auto; transform:translateY(-50%);">
                        <h5
                            style="font-size:32px; color:#fff; text-align:center; -webkit-text-stroke: 1px #333; text-shadow: 2px 2px 8px #000, 0 0 2px #000; font-weight:bold; display:inline-block; padding:0.25em 1em; border-radius:0.5em;">
                            {{ $carousel->title }}</h5>
                        @if ($carousel->url)
                            <a href="{{ $carousel->url }}" class="btn btn-light">了解更多</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev"
            style="width: 48px; height: 48px; background: #fff; border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: auto 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(1);"></span>
            <span class="visually-hidden">上一張</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next"
            style="width: 48px; height: 48px; background: #fff; border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: auto 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <span class="carousel-control-next-icon" aria-hidden="true" style="filter: invert(1);"></span>
            <span class="visually-hidden">下一張</span>
        </button>
    </div>

    <!-- 四步驟流程 -->
    <div class="row text-center mb-5">
        <h3 class="mb-4">租借很簡單 4 步驟</h3>
        <div class="col-6 col-md-3 mb-4">
            <img src="{{ asset('images/flow/flow-1.webp') }}" class="mb-2" style="height:200px;">
            <div>選擇旅遊地點</div>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <img src="{{ asset('images/flow/flow-2.webp') }}" class="mb-2" style="height:200px;">
            <div>選擇租借時間</div>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <img src="{{ asset('images/flow/flow-3.webp') }}" class="mb-2" style="height:200px;">
            <div>立即預約付訂</div>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <img src="{{ asset('images/flow/flow-4.webp') }}" class="mb-2" style="height:200px;">
            <div>前往門市取車</div>
        </div>
    </div>

    <!-- 特色區塊 -->
    <div class="row text-center mb-5">
        <h3 class="mb-4">我們的特色</h3>
        <div class="col-6 col-md-3 mb-4">
            <img src="{{ asset('images/feature/feature-1.webp') }}" class="mb-2" style="height:200px;">
            <div>簡單<br><span class="text-muted">輕鬆簡單的操作介面</span></div>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <img src="{{ asset('images/feature/feature-2.webp') }}" class="mb-2" style="height:200px;">
            <div>快速<br><span class="text-muted">快速流暢的租車體驗</span></div>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <img src="{{ asset('images/feature/feature-3.webp') }}" class="mb-2" style="height:200px;">
            <div>透明<br><span class="text-muted">透明公開的租車資訊</span></div>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <img src="{{ asset('images/feature/feature-4.webp') }}" class="mb-2" style="height:200px;">
            <div>安全<br><span class="text-muted">安全保障的旅遊體驗</span></div>
        </div>
    </div>

    <!-- 商店列表 -->
    <div class="row mb-5">
        <h3 class="text-center mb-4">熱門租車門市</h3>
        @foreach ($stores as $store)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if ($store->image)
                        <img src="{{ asset('storage/' . $store->image) }}" class="card-img-top" alt="{{ $store->name }}"
                            style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                            style="height: 200px;">
                            <i class="fa-solid fa-store fa-3x text-muted"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $store->name }}</h5>
                        <p class="card-text text-muted">
                            <i class="fa-solid fa-location-dot me-1"></i> {{ $store->address }}<br>
                            <i class="fa-solid fa-phone me-1"></i> {{ $store->phone }}
                        </p>
                        <a href="{{ route('store.detail', $store) }}" class="btn btn-primary">查看車款</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
