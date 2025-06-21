@extends('layouts.app')

@section('title', '註冊')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow-sm p-4" style="max-width: 400px; width: 100%;">
            <h3 class="fw-bold mb-3">註冊</h3>
            <form method="POST" action="{{ url('/signup') }}" id="signupForm">
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
                    <div id="emailFeedback" class="form-text"></div>
                </div>
                <div class="mb-3">
                    <label for="signupPassword" class="form-label">密碼 <span class="text-danger">*</span></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="signupPassword"
                        name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        <small class="text-muted">密碼要求：</small>
                        <ul class="list-unstyled small mt-1">
                            <li id="lengthCheck"><i class="fa fa-times text-danger"></i> 至少 8 個字元</li>
                            <li id="lowercaseCheck"><i class="fa fa-times text-danger"></i> 包含小寫字母</li>
                            <li id="uppercaseCheck"><i class="fa fa-times text-danger"></i> 包含大寫字母</li>
                            <li id="numberCheck"><i class="fa fa-times text-danger"></i> 包含數字</li>
                            <li id="symbolCheck"><i class="fa fa-times text-danger"></i> 包含標點符號</li>
                        </ul>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="signupPasswordConfirmation" class="form-label">確認密碼 <span
                            class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="signupPasswordConfirmation" name="password_confirmation"
                        required>
                    <div id="passwordMatchFeedback" class="form-text"></div>
                </div>
                <button type="submit" class="btn btn-success w-100 mb-2" id="submitBtn"
                    style="font-weight:500; font-size:1.1rem;" disabled>立即註冊</button>
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

@push('scripts')
<script>
$(document).ready(function() {
    let emailValid = false;
    let passwordValid = false;
    let passwordMatch = false;

    // Email 即時驗證
    $('#signupEmail').on('blur', function() {
        const email = $(this).val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && emailRegex.test(email)) {
            // 檢查 Email 是否已存在
            $.ajax({
                url: '{{ route("check.email") }}',
                method: 'POST',
                data: {
                    email: email,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.exists) {
                        $('#signupEmail').addClass('is-invalid');
                        $('#emailFeedback').html('<span class="text-danger">此 Email 已經存在，請重新輸入</span>');
                        emailValid = false;
                    } else {
                        $('#signupEmail').removeClass('is-invalid').addClass('is-valid');
                        $('#emailFeedback').html('<span class="text-success">此 Email 可以使用</span>');
                        emailValid = true;
                    }
                    updateSubmitButton();
                },
                error: function() {
                    $('#emailFeedback').html('<span class="text-warning">無法驗證 Email，請稍後再試</span>');
                    emailValid = false;
                    updateSubmitButton();
                }
            });
        } else if (email) {
            $('#signupEmail').addClass('is-invalid');
            $('#emailFeedback').html('<span class="text-danger">請輸入有效的 Email 格式</span>');
            emailValid = false;
            updateSubmitButton();
        } else {
            $('#signupEmail').removeClass('is-invalid is-valid');
            $('#emailFeedback').html('');
            emailValid = false;
            updateSubmitButton();
        }
    });

    // 密碼強度檢查
    $('#signupPassword').on('input', function() {
        const password = $(this).val();
        
        // 檢查長度
        if (password.length >= 8) {
            $('#lengthCheck').html('<i class="fa fa-check text-success"></i> 至少 8 個字元');
        } else {
            $('#lengthCheck').html('<i class="fa fa-times text-danger"></i> 至少 8 個字元');
        }
        
        // 檢查小寫字母
        if (/[a-z]/.test(password)) {
            $('#lowercaseCheck').html('<i class="fa fa-check text-success"></i> 包含小寫字母');
        } else {
            $('#lowercaseCheck').html('<i class="fa fa-times text-danger"></i> 包含小寫字母');
        }
        
        // 檢查大寫字母
        if (/[A-Z]/.test(password)) {
            $('#uppercaseCheck').html('<i class="fa fa-check text-success"></i> 包含大寫字母');
        } else {
            $('#uppercaseCheck').html('<i class="fa fa-times text-danger"></i> 包含大寫字母');
        }
        
        // 檢查數字
        if (/\d/.test(password)) {
            $('#numberCheck').html('<i class="fa fa-check text-success"></i> 包含數字');
        } else {
            $('#numberCheck').html('<i class="fa fa-times text-danger"></i> 包含數字');
        }
        
        // 檢查標點符號
        if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
            $('#symbolCheck').html('<i class="fa fa-check text-success"></i> 包含標點符號');
        } else {
            $('#symbolCheck').html('<i class="fa fa-times text-danger"></i> 包含標點符號');
        }
        
        // 檢查所有條件
        passwordValid = password.length >= 8 && /[a-z]/.test(password) && /[A-Z]/.test(password) && /\d/.test(password) && /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
        
        // 檢查密碼確認
        checkPasswordMatch();
        updateSubmitButton();
    });

    // 密碼確認檢查
    $('#signupPasswordConfirmation').on('input', function() {
        checkPasswordMatch();
        updateSubmitButton();
    });

    function checkPasswordMatch() {
        const password = $('#signupPassword').val();
        const confirmation = $('#signupPasswordConfirmation').val();
        
        if (confirmation) {
            if (password === confirmation) {
                $('#signupPasswordConfirmation').removeClass('is-invalid').addClass('is-valid');
                $('#passwordMatchFeedback').html('<span class="text-success">密碼確認正確</span>');
                passwordMatch = true;
            } else {
                $('#signupPasswordConfirmation').removeClass('is-valid').addClass('is-invalid');
                $('#passwordMatchFeedback').html('<span class="text-danger">密碼確認不匹配</span>');
                passwordMatch = false;
            }
        } else {
            $('#signupPasswordConfirmation').removeClass('is-valid is-invalid');
            $('#passwordMatchFeedback').html('');
            passwordMatch = false;
        }
    }

    function updateSubmitButton() {
        if (emailValid && passwordValid && passwordMatch) {
            $('#submitBtn').prop('disabled', false);
        } else {
            $('#submitBtn').prop('disabled', true);
        }
    }
});
</script>
@endpush
