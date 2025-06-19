@extends('layouts.app')

@section('title', $store->name . ' - 門市資訊')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h2 class="text-main">{{ $store->name }}</h2>
        <p>商家地址：{{ $store->address }}</p>
        <p>聯絡電話：{{ $store->phone }}</p>
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
                    <div class="card-body d-flex flex-column">
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
                        @php $accessoryBikes = $bike->accessoryBikes ?? []; @endphp
                        <div class="mt-2">
                            <strong>配件：</strong>
                            @if(count($accessoryBikes) > 0)
                                <ul class="list-unstyled mb-0">
                                    @foreach($accessoryBikes as $ab)
                                        @if($ab->status)
                                            <li>{{ $ab->accessory->name ?? '' }} x {{ $ab->qty ?? 0 }}
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                                @if($accessoryBikes->where('status', 1)->count() == 0)
                                    <span class="text-muted">沒有配件</span>
                                @endif
                            @else
                                <span class="text-muted">沒有配件</span>
                            @endif
                        </div>
                        
                        <!-- 按鈕區域 -->
                        <div class="mt-auto pt-3">
                            @switch($bike->status)
                                @case('available')
                                    @if(Auth::guard('member')->check())
                                        <button class="btn btn-primary w-100 rent-btn" style="background-color: #3AC0D2; border-color: #3AC0D2;" data-bs-toggle="modal" data-bs-target="#rentModal" data-bike-id="{{ $bike->id }}">
                                            <i class="fa-solid fa-motorcycle me-2"></i>我要出租
                                        </button>
                                    @else
                                        <a class="btn btn-primary w-100" style="background-color: #3AC0D2; border-color: #3AC0D2;" href="{{ route('login', ['return' => request()->fullUrl()]) }}">
                                            <i class="fa-solid fa-motorcycle me-2"></i>我要出租
                                        </a>
                                    @endif
                                    @break
                                @case('rented')
                                    <button class="btn btn-warning w-100 text-dark" disabled>
                                        <i class="fa-solid fa-clock me-2"></i>已出租
                                    </button>
                                    @break
                                @case('maintenance')
                                    <button class="btn btn-danger w-100" disabled>
                                        <i class="fa-solid fa-wrench me-2"></i>維修中
                                    </button>
                                    @break
                                @case('disabled')
                                    <button class="btn btn-secondary w-100" disabled>
                                        <i class="fa-solid fa-ban me-2"></i>停用
                                    </button>
                                    @break
                                @default
                                    <button class="btn btn-light w-100 text-dark" disabled>
                                        <i class="fa-solid fa-question me-2"></i>{{ $bike->status }}
                                    </button>
                            @endswitch
                        </div>
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

@push('scripts')
<script>
    // 可根據 data-bike-id 帶入不同內容
    $(document).on('show.bs.modal', '#rentModal', function (event) {
        var button = $(event.relatedTarget);
        var bikeId = button.data('bike-id');
        // 這裡可根據 bikeId 動態載入內容
        $('#rentModal .modal-title').text('我要出租 - 車輛ID：' + bikeId);
    });
</script>
@endpush

<!-- Modal 靜態內容，之後可換成多步驟表單 -->
<div class="modal fade" id="rentModal" tabindex="-1" aria-labelledby="rentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rentModalLabel">我要出租</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center py-4">
          <i class="fa-solid fa-motorcycle fa-3x mb-3 text-main"></i>
          <div class="mb-2">這裡是多步驟租車表單的彈窗（靜態內容）</div>
          <div class="text-muted">（之後可串接真實表單）</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection 