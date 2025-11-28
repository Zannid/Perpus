
<!DOCTYPE html>
<html>
<head>
  <title>Lupa Password</title>
</head>
<body>
  <h2>Lupa Password</h2>
  @if(session('success')) <p style="color:green;">{{ session('success') }}</p> @endif
  <form method="POST" action="{{ route('password.email') }}">
    @csrf
    <label>Email:</label>
    <input type="email" name="email" required>
    <button type="submit">Kirim Kode Reset</button>
  </form>
</body>
</html>
