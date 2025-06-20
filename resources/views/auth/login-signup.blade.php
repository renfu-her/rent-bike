@extends('layouts.app')

@section('title', '登入 / 註冊')

@section('content')
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-lg-7 col-xl-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold mb-1">Sign in</h2>
                        <div class="mb-3 small">
                            Don't have an account?
                            <a href="#" id="show-signup" class="fw-bold text-decoration-underline">Sign up</a>
                        </div>
                    </div>
                    <div class="row g-0">
                        <!-- 登入表單 -->
                        <div class="col-md-6 pe-md-4 border-end">
                            <form method="POST" action="{{ url('/login') }}">
                                @csrf
                                @if ($errors->has('login'))
                                    <div class="alert alert-danger">{{ $errors->first('login') }}</div>
                                @endif
                                <div class="mb-3">
                                    <label for="loginEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="loginEmail" name="email" value="{{ old('email') }}" required autofocus>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="loginPassword" class="form-label">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="loginPassword" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                    <a href="#" class="small text-decoration-underline">Forgot password?</a>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Log in</button>
                            </form>
                        </div>
                        <!-- or -->
                        <div
                            class="col-md-6 ps-md-4 pt-4 pt-md-0 d-flex flex-column align-items-center justify-content-center">
                            <div class="w-100 mb-3">
                                <button
                                    class="btn btn-light w-100 mb-2 border d-flex align-items-center justify-content-center"
                                    type="button" disabled>
                                    <i class="fa-brands fa-google text-danger me-2"></i> Sign in with Google
                                </button>
                                <button
                                    class="btn btn-light w-100 mb-2 border d-flex align-items-center justify-content-center"
                                    type="button" disabled>
                                    <i class="fa-brands fa-facebook text-primary me-2"></i> Sign in with Facebook
                                </button>
                                <button class="btn btn-light w-100 border d-flex align-items-center justify-content-center"
                                    type="button" disabled>
                                    <i class="fa-brands fa-apple text-dark me-2"></i> Sign in with Apple
                                </button>
                            </div>
                            <div class="text-muted small">or</div>
                        </div>
                    </div>
                    <!-- 註冊表單（隱藏，點 sign up 顯示） -->
                    <div id="signup-section" class="mt-4" style="display:none;">
                        <h4 class="fw-bold mb-3 text-center">Sign up</h4>
                        <form method="POST" action="{{ url('/signup') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="signupName" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="signupName" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="signupEmail" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="signupEmail" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="signupPassword" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="signupPassword" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="signupPasswordConfirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="signupPasswordConfirmation"
                                    name="password_confirmation" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Sign up</button>
                        </form>
                        <div class="text-center mt-3">
                            已經有帳號了嗎？<a href="#" id="show-login" class="fw-bold text-decoration-underline">Sign
                                in</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            // 切換註冊/登入表單
            document.getElementById('show-signup').addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector('.row .card-body > .row').style.display = 'none';
                document.getElementById('signup-section').style.display = 'block';
            });
            document.getElementById('show-login').addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector('.row .card-body > .row').style.display = '';
                document.getElementById('signup-section').style.display = 'none';
            });
            // 若有註冊表單錯誤，自動顯示註冊表單
            @if ($errors->has('name') || $errors->has('email') || $errors->has('password'))
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelector('.row .card-body > .row').style.display = 'none';
                    document.getElementById('signup-section').style.display = 'block';
                });
            @endif
        </script>
    @endpush
@endsection
