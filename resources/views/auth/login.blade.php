@extends('layouts.app')

@section('title', '登入')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow-sm p-4" style="max-width: 400px; width: 100%;">
            <h3 class="fw-bold mb-3">登入</h3>
            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <input type="hidden" name="return" value="{{ request('return') }}">
                @if ($errors->has('login'))
                    <div class="alert alert-danger">{{ $errors->first('login') }}</div>
                @endif
                <div class="mb-3">
                    <label for="loginEmail" class="form-label">電子郵件 <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="loginEmail"
                        name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="loginPassword" class="form-label">密碼 <span class="text-danger">*</span></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="loginPassword"
                        name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">保持登入狀態</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-2"
                    style="font-weight:500; font-size:1.1rem;">立即登入</button>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <a href="{{ route('signup') }}" class="small text-decoration-none">註冊帳號</a>
                    <a href="#" class="small text-decoration-none">忘記密碼？</a>
                </div>
            </form>
            {{-- <div class="text-center text-muted mb-2">或使用以下方式登入</div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-light border w-100 d-flex align-items-center justify-content-center" type="button" disabled style="background:#fff;"><i class="fa-brands fa-google text-danger me-2"></i>Google</button>
            <button class="btn btn-outline-light border w-100 d-flex align-items-center justify-content-center" type="button" disabled style="background:#fff;"><i class="fa-brands fa-facebook text-primary me-2"></i>Facebook</button>
            <button class="btn btn-outline-light border w-100 d-flex align-items-center justify-content-center" type="button" disabled style="background:#fff;"><i class="fa-brands fa-twitter text-info me-2"></i>Twitter</button>
        </div> --}}
        </div>
    </div>
@endsection
