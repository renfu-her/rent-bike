<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '機車出租平台')</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css?v=' . time()) }}" rel="stylesheet">
    
    <style>
        body { background: #f8f9fa; }
        .navbar-brand { font-weight: bold; }
        .nav-link.active { color: #f8b803 !important; }
        .main-footer { background: #222; color: #fff; padding: 2rem 0; margin-top: 3rem; }
        .main-footer a { color: #f8b803; }
        .avatar-circle {
            width: 38px; height: 38px; border-radius: 50%; background: #e0e0e0; display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; color: #555; cursor: pointer;
        }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="background-color: #3AC0D2 !important;">
    <div class="container">
        <a class="navbar-brand" style="color: #fff !important;" href="/">機車出租</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse main-menu" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link @if(request()->is('/')) active @endif" href="/">首頁</a>
                </li>
                @if(Auth::check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="avatar-circle me-1">
                                <i class="fa-solid fa-user"></i>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="/bikes"><i class="fa-solid fa-motorcycle me-1"></i>出租的車</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ url('/logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="fa-solid fa-right-from-bracket me-1"></i>登出</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('login')) active @endif" href="/login"><i class="fa-solid fa-right-to-bracket me-1"></i>登入</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    @yield('content')
</main>

<footer class="main-footer text-center" style="background-color: #3AC0D2 !important;">
    <div class="container">
        <div class="mb-2">
            <a href="/">首頁</a> | <a href="/bikes">機車出租</a> | <a href="/login">登入</a>
        </div>
        <div>Copyright &copy; {{ date('Y') }} 機車出租平台. All rights reserved.</div>
    </div>
</footer>

<!-- jQuery 3.7.1 -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html> 