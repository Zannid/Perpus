<!DOCTYPE html>
<html>
<head>
  <title>Verifikasi Kode</title>
</head>
<body>
  <h2>Masukkan Kode Verifikasi</h2>
  @if(session('error')) <p style="color:red;">{{ session('error') }}</p> @endif
  <form method="POST" action="{{ route('password.verify.post') }}">
    @csrf
    <input type="hidden" name="email" value="{{ $email }}">
    <label>Kode:</label>
    <input type="text" name="token" required>
    <label>Password Baru:</label>
    <input type="password" name="password" required>
    <label>Konfirmasi Password:</label>
    <input type="password" name="password_confirmation" required>
    <button type="submit">Reset Password</button>
  </form>
</body>
</html>
