@extends('layouts.app')

@section('title', '首頁 - 機車出租平台')

@section('content')
<!-- 輪播區塊 -->
<div id="mainCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80" class="d-block w-100" alt="...">
            <div class="carousel-caption d-none d-md-block">
                <h5>歡迎來到機車出租平台</h5>
                <p>輕鬆租車，安全出行</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=1200&q=80" class="d-block w-100" alt="...">
            <div class="carousel-caption d-none d-md-block">
                <h5>多元車款任你選</h5>
                <p>全台商店，隨時預約</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">上一張</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">下一張</span>
    </button>
</div>

<!-- 四步驟流程 -->
<div class="row text-center mb-5">
    <h3 class="mb-4">租借很簡單 4 步驟</h3>
    <div class="col-6 col-md-3 mb-4">
        <img src="https://www.zocha.com.tw/static/元素_步驟01-min.png" class="mb-2" style="height:64px;">
        <div>選擇旅遊地點</div>
    </div>
    <div class="col-6 col-md-3 mb-4">
        <img src="https://www.zocha.com.tw/static/元素_步驟02-min.png" class="mb-2" style="height:64px;">
        <div>選擇租借時間</div>
    </div>
    <div class="col-6 col-md-3 mb-4">
        <img src="https://www.zocha.com.tw/static/元素_步驟03-min.png" class="mb-2" style="height:64px;">
        <div>立即預約付訂</div>
    </div>
    <div class="col-6 col-md-3 mb-4">
        <img src="https://www.zocha.com.tw/static/元素_步驟04-min.png" class="mb-2" style="height:64px;">
        <div>前往門市取車</div>
    </div>
</div>

<!-- 特色區塊 -->
<div class="row text-center mb-5">
    <h3 class="mb-4">我們的特色</h3>
    <div class="col-6 col-md-3 mb-4">
        <img src="https://www.zocha.com.tw/static/元素_特色01-min.png" class="mb-2" style="height:48px;">
        <div>簡單<br><span class="text-muted">輕鬆簡單的操作介面</span></div>
    </div>
    <div class="col-6 col-md-3 mb-4">
        <img src="https://www.zocha.com.tw/static/元素n150_特色02-min.png" class="mb-2" style="height:48px;">
        <div>快速<br><span class="text-muted">快速流暢的租車體驗</span></div>
    </div>
    <div class="col-6 col-md-3 mb-4">
        <img src="https://www.zocha.com.tw/static/元素_特色03-min.png" class="mb-2" style="height:48px;">
        <div>透明<br><span class="text-muted">透明公開的租車資訊</span></div>
    </div>
    <div class="col-6 col-md-3 mb-4">
        <img src="https://www.zocha.com.tw/static/元素_特色04-min.png" class="mb-2" style="height:48px;">
        <div>安全<br><span class="text-muted">安全保障的旅遊體驗</span></div>
    </div>
</div>

<!-- CTA 區塊 -->
<div class="text-center mb-5">
    <a href="/bikes" class="btn btn-warning btn-lg px-5"><i class="fa-solid fa-magnifying-glass"></i> 立即搜尋機車</a>
</div>
@endsection 