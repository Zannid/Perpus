<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .danger-box {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .info-table tr {
            border-bottom: 1px solid #e0e0e0;
        }
        .info-table td {
            padding: 12px;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 40%;
            color: #555;
        }
        .reason-box {
            background-color: #fff3cd;
            border: 2px dashed #ffc107;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }
        .reason-box h3 {
            margin-top: 0;
            color: #856404;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin-top: 20px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .info-box {
            background-color: #d1ecf1;
            border-left: 4px solid #0dcaf0;
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">❌</div>
            <h1>Peminjaman Ditolak</h1>
        </div>

        <div class="content">
            <div class="danger-box">
                <strong>Halo {{ $peminjaman->user->name }},</strong><br>
                Mohon maaf, peminjaman buku Anda <strong>DITOLAK</strong> oleh petugas perpustakaan.
            </div>

            <h3 style="color: #333;">Detail Peminjaman:</h3>
            <table class="info-table">
                <tr>
                    <td>Kode Peminjaman</td>
                    <td><strong>{{ $peminjaman->kode_peminjaman }}</strong></td>
                </tr>
                <tr>
                    <td>Judul Buku</td>
                    <td>{{ $peminjaman->buku->judul }}</td>
                </tr>
                <tr>
                    <td>Penulis</td>
                    <td>{{ $peminjaman->buku->penulis }}</td>
                </tr>
                <tr>
                    <td>Jumlah</td>
                    <td>{{ $peminjaman->jumlah }} eksemplar</td>
                </tr>
            </table>

            @if(!empty($peminjaman->alasan_tolak))
            <div class="reason-box">
                <h3>📋 Alasan Penolakan:</h3>
                <p style="font-size: 16px; color: #333; margin: 0;">
                    {{ $peminjaman->alasan_tolak }}
                </p>
            </div>
            @else
            <div class="reason-box">
                <h3>📋 Alasan Penolakan:</h3>
                <p style="font-size: 16px; color: #333; margin: 0;">
                    Peminjaman ditolak oleh petugas
                </p>
            </div>
            @endif

            <div class="info-box">
                <strong>💡 Apa yang bisa dilakukan?</strong><br>
                • Hubungi petugas perpustakaan untuk informasi lebih lanjut<br>
                • Coba ajukan peminjaman buku lain yang tersedia<br>
                • Periksa ketersediaan buku secara berkala
            </div>

            <center>
                <a href="{{ route('peminjaman.index') }}" class="btn">Lihat Riwayat Peminjaman</a>
            </center>
        </div>

        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem.<br>
            Jangan balas email ini.</p>
            <p>Untuk pertanyaan, silakan hubungi petugas perpustakaan.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>