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
  <title>Reset Password | E-Perpustakaan</title>

  <meta name="description" content="Reset Password E-Perpustakaan" />

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

        <!-- Reset Password Card -->
        <div class="card shadow-lg rounded-3">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-3">
              <a href="{{ url('/') }}" class="app-brand-link gap-2">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo E-Perpustakaan" width="120" />
              </a>
            </div>
            <!-- /Logo -->

            <h4 class="mb-2 text-center">Reset Your Password 🔒</h4>
            <p class="mb-4 text-center">Enter your new password below</p>

            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.update') }}">
              @csrf
              <input type="hidden" name="token" value="{{ $token }}">

              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                  id="email"
                  type="email"
                  class="form-control @error('email') is-invalid @enderror"
                  name="email"
                  value="{{ $email ?? old('email') }}"
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

              <!-- New Password -->
              <div class="mb-3 form-password-toggle">
                <label for="password" class="form-label">New Password</label>
                <div class="input-group input-group-merge">
                  <input
                    id="password"
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••••••"
                  />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  @error('password')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <!-- Confirm Password -->
              <div class="mb-3 form-password-toggle">
                <label for="password-confirm" class="form-label">Confirm Password</label>
                <div class="input-group input-group-merge">
                  <input
                    id="password-confirm"
                    type="password"
                    class="form-control"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••••••"
                  />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="mb-3">
                <button type="submit" class="btn btn-primary d-grid w-100">
                  Reset Password
                </button>
              </div>

              <!-- Back to Login -->
              <div class="text-center">
                <a href="{{ route('login') }}" class="btn btn-link p-0">
                  Back to Login
                </a>
              </div>
            </form>
            <!-- /Reset Password Form -->
          </div>
        </div>
        <!-- /Reset Password Card -->

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
