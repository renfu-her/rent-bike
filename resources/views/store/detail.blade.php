@extends('layouts.app')

@section('title', $store->name . ' - 門市資訊')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h2 class="text-main">{{ $store->name }}</h2>
        <p>{{ $store->address }}</p>
    </div>
    <div class="row g-4">
        @forelse($store->bikes as $bike)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    @if($bike->image)
                        <img src="{{ asset('storage/' . $bike->image) }}" class="card-img-top" alt="{{ $bike->model }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fa-solid fa-motorcycle fa-3x text-muted"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $bike->model }}</h5>
                        <p class="card-text mb-1"><strong>車牌：</strong>{{ $bike->plate_no }}</p>
                        <p class="card-text mb-1"><strong>狀態：</strong>
                            @switch($bike->status)
                                @case('available')<span class="badge bg-success">待出租</span>@break
                                @case('rented')<span class="badge bg-warning text-dark">已出租</span>@break
                                @case('maintenance')<span class="badge bg-danger">維修中</span>@break
                                @case('disabled')<span class="badge bg-secondary">停用</span>@break
                                @default <span class="badge bg-light text-dark">{{ $bike->status }}</span>
                            @endswitch
                        </p>
                        @if(is_array($bike->accessories))
                            <div class="mt-2">
                                <strong>配件：</strong>
                                <ul class="list-unstyled mb-0">
                                    @foreach($bike->accessories as $acc)
                                        <li>{{ $acc['name'] ?? '' }} x{{ $acc['qty'] ?? 0 }}
                                            @if(!empty($acc['enabled']))<span class="badge bg-info text-dark ms-1">啟用</span>@endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">此門市暫無機車資料。</div>
            </div>
        @endforelse
    </div>
</div>
@endsection 