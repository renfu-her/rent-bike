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

<!-- Modal 多步驟表單 -->
<div class="modal fade" id="rentModal" tabindex="-1" aria-labelledby="rentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rentModalLabel">我要出租</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- 商店與機車資訊 -->
        <div class="mb-3" id="modalStoreInfo">
          <div class="fw-bold text-main" id="modalStoreName">{{ $store->name }}</div>
          <div class="small text-muted" id="modalStorePhone"><i class="fa fa-phone me-1"></i>{{ $store->phone }}</div>
          <div class="small text-muted" id="modalStoreAddress"><i class="fa fa-location-dot me-1"></i>{{ $store->address }}</div>
          <div class="mt-2" id="modalBikeName"><span class="badge bg-info text-dark">機車：<span id="modalBikeModel"></span></span></div>
        </div>
        <!-- 多步驟表單內容 -->
        <form id="rentForm">
          <div class="step step-1">
            <div class="text-main fw-bold mb-2">基本資料(1/3)</div>
            <div class="mb-2">
              <label class="form-label">身份證號</label>
              <input type="text" class="form-control" name="id_number">
            </div>
            <div class="mb-2">
              <label class="form-label">姓名</label>
              <input type="text" class="form-control" name="name">
            </div>
            <div class="mb-2">
              <label class="form-label">電話號碼</label>
              <input type="text" class="form-control" name="phone">
            </div>
            <div class="mb-2">
              <label class="form-label">電子郵件</label>
              <input type="email" class="form-control" name="email">
            </div>
            <div class="d-flex justify-content-end mt-3">
              <button type="button" class="btn btn-warning next-step w-100">下一步</button>
            </div>
          </div>
          <div class="step step-2 d-none">
            <div class="text-main fw-bold mb-2">時間選擇(2/3)</div>
            <div class="mb-2">
              <label class="form-label">租借類型</label>
              <select class="form-select" name="rent_type">
                <option>日租</option>
                <option>時租</option>
              </select>
            </div>
            <div class="mb-2">
              <label class="form-label">門市</label>
              <select class="form-select" name="store">
                <option>{{ $store->name }}</option>
              </select>
            </div>
            <div class="mb-2">
              <label class="form-label">取車時間</label>
              <input type="date" class="form-control mb-1" name="pickup_date">
              <input type="time" class="form-control" name="pickup_time">
            </div>
            <div class="mb-2">
              <label class="form-label">還車時間</label>
              <input type="date" class="form-control mb-1" name="return_date">
              <input type="time" class="form-control" name="return_time">
            </div>
            <div class="d-flex justify-content-between mt-3">
              <button type="button" class="btn btn-secondary prev-step w-50 me-2">上一步</button>
              <button type="button" class="btn btn-warning next-step w-50">下一步</button>
            </div>
          </div>
          <div class="step step-3 d-none">
            <div class="text-main fw-bold mb-2">確認資料(3/3)</div>
            <div class="mb-3">請確認您的資料無誤後送出預約。</div>
            <!-- 這裡可顯示所有填寫資料的 summary -->
            <div id="summaryBox" class="mb-3"></div>
            <div class="d-flex justify-content-between mt-3">
              <button type="button" class="btn btn-secondary prev-step w-50 me-2">上一步</button>
              <button type="submit" class="btn btn-success w-50">送出預約</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection 

@push('scripts')
<script>
    // 動態帶入機車名稱
    $(document).on('show.bs.modal', '#rentModal', function (event) {
        var button = $(event.relatedTarget);
        var bikeId = button.data('bike-id');
        var bikeModel = button.closest('.card').find('.card-title').text();
        $('#modalBikeModel').text(bikeModel);
    });
    // 多步驟切換
    $(function(){
      var step = 1;
      function showStep(n) {
        $('.step').addClass('d-none');
        $('.step-' + n).removeClass('d-none');
      }
      $(document).on('click', '.next-step', function(){
        if(step < 3) step++;
        showStep(step);
        if(step === 3) {
          // summary
          var summary = '';
          $('#rentForm').serializeArray().forEach(function(item){
            summary += '<div><strong>' + item.name + '：</strong>' + item.value + '</div>';
          });
          $('#summaryBox').html(summary);
        }
      });
      $(document).on('click', '.prev-step', function(){
        if(step > 1) step--;
        showStep(step);
      });
      // reset step on modal close
      $('#rentModal').on('hidden.bs.modal', function(){
        step = 1;
        showStep(step);
        $('#rentForm')[0].reset();
        $('#summaryBox').empty();
      });
    });
</script>
@endpush