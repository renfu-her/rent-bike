<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>機車出租首頁</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .navbar-brand { font-weight: bold; }
        .carousel-item img { object-fit: cover; height: 400px; width: 100%; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">首頁</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/bikes"><i class="fa-solid fa-motorcycle me-1"></i>機車出租</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/login"><i class="fa-solid fa-right-to-bracket me-1"></i>登入</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">
    {{-- Banner 輪播區塊 --}}
    <div id="mainCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach(App\Models\Carousel::where('is_active', true)->orderBy('sort_order')->get() as $i => $carousel)
                <div class="carousel-item @if($i === 0) active @endif">
                    <a href="{{ $carousel->url ?? '#' }}" target="_blank">
                        <img src="{{ asset('storage/' . $carousel->image) }}" class="d-block w-100 rounded" alt="{{ $carousel->title }}">
                    </a>
                    @if($carousel->title)
                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                            <h5>{{ $carousel->title }}</h5>
                        </div>
                    @endif
                </div>
            @endforeach
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

    {{-- 門市卡片區塊 --}}
    <div class="row g-4">
        @foreach(App\Models\Store::all() as $store)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    @if($store->image)
                        <img src="{{ asset('storage/' . $store->image) }}" class="card-img-top" alt="{{ $store->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title text-main">{{ $store->name }}</h5>
                        <p class="card-text">{{ $store->address }}</p>
                        <a href="{{ route('store.detail', $store->id ?? $store->store_id) }}" class="btn btn-main w-100">查看門市</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- jQuery 3.7.1 -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
