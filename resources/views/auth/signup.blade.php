@extends('layouts.app')

@section('title', '註冊')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow-sm p-4" style="max-width: 400px; width: 100%;">
            <h3 class="fw-bold mb-3">註冊</h3>
            <form method="POST" action="{{ url('/signup') }}">
                @csrf
                <div class="mb-3">
                    <label for="signupName" class="form-label">姓名 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="signupName"
                        name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="signupEmail" class="form-label">電子郵件 <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="signupEmail"
                        name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="signupPassword" class="form-label">密碼 <span class="text-danger">*</span></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="signupPassword"
                        name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="signupPasswordConfirmation" class="form-label">確認密碼 <span
                            class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="signupPasswordConfirmation" name="password_confirmation"
                        required>
                </div>
                <button type="submit" class="btn btn-success w-100 mb-2"
                    style="font-weight:500; font-size:1.1rem;">立即註冊</button>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <a href="{{ route('login') }}" class="small text-decoration-underline">已經有帳號了？登入</a>
                </div>
            </form>
            {{-- <div class="text-center text-muted mb-2">或使用以下方式註冊</div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-light border w-100 d-flex align-items-center justify-content-center" type="button" disabled style="background:#fff;"><i class="fa-brands fa-google text-danger me-2"></i>Google</button>
            <button class="btn btn-outline-light border w-100 d-flex align-items-center justify-content-center" type="button" disabled style="background:#fff;"><i class="fa-brands fa-facebook text-primary me-2"></i>Facebook</button>
            <button class="btn btn-outline-light border w-100 d-flex align-items-center justify-content-center" type="button" disabled style="background:#fff;"><i class="fa-brands fa-twitter text-info me-2"></i>Twitter</button>
        </div> --}}
        </div>
    </div>
@endsection
