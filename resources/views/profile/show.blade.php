@extends('layouts.app')

@section('title', '個人資料')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fa-solid fa-user me-2"></i>個人資料管理
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fa-solid fa-exclamation-triangle me-2"></i>請修正以下錯誤：
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf

                            <!-- 基本資料 -->
                            <div class="mb-4">
                                <h5 class="text-main mb-3">
                                    <i class="fa-solid fa-info-circle me-2"></i>基本資料
                                </h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">姓名 <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $member->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">電子郵件 <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $member->email) }}"
                                            required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">手機號碼 <span
                                                class="text-danger">*</span></label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', $member->phone) }}"
                                            required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="gender" class="form-label">性別 <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('gender') is-invalid @enderror" id="gender"
                                            name="gender" required>
                                            <option value="">請選擇性別</option>
                                            <option value="male"
                                                {{ old('gender', $member->gender) == 'male' ? 'selected' : '' }}>男性</option>
                                            <option value="female"
                                                {{ old('gender', $member->gender) == 'female' ? 'selected' : '' }}>女性
                                            </option>
                                            <option value="other"
                                                {{ old('gender', $member->gender) == 'other' ? 'selected' : '' }}>其他
                                            </option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="id_number" class="form-label">身份證字號 <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('id_number') is-invalid @enderror"
                                            id="id_number" name="id_number"
                                            value="{{ old('id_number', $member->id_number) }}" maxlength="10" required>
                                        @error('id_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="address" class="form-label">地址</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                                            id="address" name="address" value="{{ old('address', $member->address) }}">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- 密碼變更 -->
                            <div class="mb-4">
                                <h5 class="text-main mb-3">
                                    <i class="fa-solid fa-lock me-2"></i>密碼變更
                                    <small class="text-muted">（如不變更密碼請留空）</small>
                                </h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="new_password" class="form-label">新密碼</label>
                                        <input type="password"
                                            class="form-control @error('new_password') is-invalid @enderror"
                                            id="new_password" name="new_password" minlength="6">
                                        @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="new_password_confirmation" class="form-label">確認新密碼</label>
                                        <input type="password" class="form-control" id="new_password_confirmation"
                                            name="new_password_confirmation" minlength="6">
                                    </div>
                                </div>
                            </div>

                            <!-- 按鈕區域 -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                    <i class="fa-solid fa-arrow-left me-2"></i>返回首頁
                                </a>
                                <button type="submit" class="btn btn-primary"
                                    style="background-color: #3AC0D2; border-color: #3AC0D2;">
                                    <i class="fa-solid fa-save me-2"></i>儲存變更
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // 身份證字號自動轉大寫
        document.getElementById('id_number').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    </script>
@endpush
