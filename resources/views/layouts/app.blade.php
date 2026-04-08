<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    <style>
    .page-loading-overlay {
        position: fixed;
        inset: 0;
        z-index: 3000;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(15, 23, 42, 0.78);
        color: #ffffff;
        transition: opacity 0.25s ease, visibility 0.25s ease;
    }
    .page-loading-overlay.hidden {
        opacity: 0;
        visibility: hidden;
    }
    .page-loading-card {
        max-width: 300px;
        width: 100%;
        padding: 22px 24px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.09);
        border: 1px solid rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(14px);
        text-align: center;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    }
    .page-loader-ring {
        width: 55px;
        height: 55px;
        margin: 0 auto 16px;
        border: 6px solid rgba(255, 255, 255, 0.22);
        border-top-color: #0d6efd;
        border-radius: 50%;
        animation: pageLoaderSpin 1s linear infinite;
    }
    .page-loading-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 6px;
        color: #ffffff;
    }
    .page-loading-text {
        font-size: 0.92rem;
        color: rgba(255, 255, 255, 0.78);
        line-height: 1.6;
    }
    @keyframes pageLoaderSpin {
        to { transform: rotate(360deg); }
    }
    </style>
</head>
<body>
    <div id="pageLoadingOverlay" class="page-loading-overlay">
      <div class="page-loading-card">
        <div class="page-loader-ring"></div>
        <div class="page-loading-title">Memuat halaman...</div>
        <div class="page-loading-text">Silakan tunggu, kami sedang menyiapkan konten untuk Anda.</div>
      </div>
    </div>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const overlay = document.getElementById('pageLoadingOverlay');
        if (overlay) {
            overlay.classList.add('hidden');
            setTimeout(function () {
                overlay.remove();
            }, 250);
        }
    });
    </script>
</body>
</html>
