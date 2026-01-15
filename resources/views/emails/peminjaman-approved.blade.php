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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .success-box {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
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
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">✅</div>
            <h1>Peminjaman Disetujui!</h1>
        </div>

        <div class="content">
            <div class="success-box">
                <strong>Halo {{ $peminjaman->user->name }},</strong><br>
                Peminjaman buku Anda telah <strong>DISETUJUI</strong> oleh petugas perpustakaan.
            </div>

            <h3 style="color: #333;">Detail Peminjaman:</h3>
            <table class="info-table">
                <tr>
                    <td>Kode Peminjaman</td>
                    <td><strong>{{ $peminjaman->kode_peminjaman }}</strong></td>
                </tr>
                <tr>
                    <td colspan="2" style="background-color: #f8f9fa; font-weight: bold; text-align: center; font-size: 14px; color: #333;">Daftar Buku yang Dipinjam</td>
                </tr>
                @foreach($peminjaman->details as $detail)
                <tr>
                    <td style="font-weight: normal; color: #333;">
                        {{ optional($detail->buku)->judul ?? 'Buku tidak ditemukan' }}
                        <br><small style="color: #777;">Penulis: {{ optional($detail->buku)->penulis ?? '-' }}</small>
                    </td>
                    <td style="text-align: right;">{{ $detail->jumlah }} unit</td>
                </tr>
                @endforeach
                <tr style="border-top: 2px solid #667eea;">
                    <td>Total Keseluruhan</td>
                    <td style="text-align: right;"><strong>{{ $peminjaman->jumlah }} Item</strong></td>
                </tr>
                <tr>
                    <td>Tanggal Pinjam</td>
                    <td>{{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->translatedFormat('l, d F Y') }}</td>
                </tr>
                <tr>
                    <td>Tenggat Kembali</td>
                    <td style="color: #dc3545; font-weight: bold;">
                        {{ \Carbon\Carbon::parse($peminjaman->tenggat)->translatedFormat('l, d F Y') }}
                    </td>
                </tr>
            </table>

            <div class="warning">
                <strong>⚠️ Perhatian:</strong><br>
                • Harap kembalikan buku sebelum tanggal tenggat<br>
                • Keterlambatan dikenakan denda Rp 2.000/hari per buku<br>
                • Jaga kondisi buku dengan baik
            </div>

            <center>
                <a href="{{ route('peminjaman.index') }}" class="btn">Lihat Detail Peminjaman</a>
            </center>
        </div>

        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem.<br>
            Jangan balas email ini.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>