<!DOCTYPE html>
<html>
  <body>
    <h2>Halo, {{ $user->name }}</h2>

    @if($jenis == 'H-1')
      <p>Pengingat: Buku <b>{{ $buku->judul }}</b> yang Anda pinjam akan jatuh tempo besok.</p>
    @elseif($jenis == 'H')
      <p>Perhatian: Buku <b>{{ $buku->judul }}</b> yang Anda pinjam sudah jatuh tempo hari ini.</p>
    @endif

    <p>Harap segera mengembalikan buku agar tidak terkena denda.</p>
  </body>
</html>
