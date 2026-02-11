<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Barang Masuk</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
 table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;

        }
        thead{
        background:rgba(34, 170, 89, 0.99);
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Laporan Data Peminjaman</h2>
    <table>
        <thead>
           <tr>
                        <th>No</th>
                                <th>Kode Peminjaman</th>
                                <th>Nama</th>
                                <th>Judul Buku</th>
                                <th>Jumlah</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>


                        </tr>
        </thead>
        <tbody>
                @foreach ($data as $item)
                                <tr>
                                            <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode_peminjaman }}</td>
                                        <td>{{ $item->user->name ?? '-' }}</td>
                                        <td>{{ $item->details->pluck('buku.judul')->implode(', ') }}</td>
                                        <td>{{ $item->jumlah_keseluruhan ?? $item->details->sum('jumlah') }}</td>
                            <td>{{ $item->formatted_tanggal_pinjam ?? $item->tgl_pinjam }}</td>
                            <td>{{ $item->formatted_tanggal_kembali ?? $item->tenggat }}</td>
                                                                 <td>
                                                            <span class="badge {{ $item->status == 'Sedang Dipinjam' ? 'bg-danger' : 'bg-success' }}">
                                                                {{ $item->status }}
                                                            </span>
                                                        </td>
                                </tr>
                        @endforeach
        </tbody>
 </table>
</body>
</html>
