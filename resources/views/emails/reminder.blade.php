<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <p>Halo {{ $peminjaman->user->name }},</p>
    <p>Buku <strong>{{ $peminjaman->buku->judul }}</strong> yang anda pinjam akan jatuh tempo pada <strong>{{ \Carbon\Carbon::parse($peminjaman->tenggat)->translatedFormat('d F Y') }}</strong>.</p>
    <p>Silakan kembalikan tepat waktu agar tidak terkena denda.</p>
    <p>Terima kasih.</p>
</body>
</html>
