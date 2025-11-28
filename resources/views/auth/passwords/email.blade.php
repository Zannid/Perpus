<!DOCTYPE html>
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets/') }}"
  data-template="vertical-menu-template-free"
>
<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
  />
  <title>Forgot Password | E-Perpustakaan</title>

  <meta name="description" content="Forgot Password E-Perpustakaan" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
    rel="stylesheet"
  />

  <!-- Icons -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

  <!-- Page CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />

  <!-- Helpers -->
  <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
  <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">

        <!-- Forgot Password Card -->
        <div class="card shadow-lg rounded-3">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-3">
              <a href="{{ url('/') }}" class="app-brand-link gap-2">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo E-Perpustakaan" width="120" />
              </a>
            </div>
            <!-- /Logo -->

            <h4 class="mb-2 text-center">Forgot Your Password? 🔑</h4>
            <p class="mb-4 text-center">Enter your email address to receive a password reset link.</p>

            <!-- Forgot Password Form -->
            <form method="POST" action="{{ route('password.email') }}">
              @csrf

              @if (session('status'))
                <div class="alert alert-success" role="alert">
                  {{ session('status') }}
                </div>
              @endif

              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                  id="email"
                  type="email"
                  class="form-control @error('email') is-invalid @enderror"
                  name="email"
                  value="{{ old('email') }}"
                  required
                  autocomplete="email"
                  autofocus
                  placeholder="Enter your email"
                />
                @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>

              <!-- Submit Button -->
              <div class="mb-3">
                <button type="submit" class="btn btn-primary d-grid w-100">
                  Send Password Reset Link
                </button>
              </div>

              <!-- Back to Login -->
              <div class="text-center">
                <a href="{{ route('login') }}" class="btn btn-link p-0">
                  Back to Login
                </a>
              </div>
            </form>
            <!-- /Forgot Password Form -->
          </div>
        </div>
        <!-- /Forgot Password Card -->

      </div>
    </div>
  </div>

  <!-- Core JS -->
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
