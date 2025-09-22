<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Barang Masuk</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Laporan Barang Masuk</h2>

    <table>
        <thead>
            <tr>
                <th>Kode Masuk</th>
                <th>Judul Buku</th>
                <th>Jumlah</th>
                <th>Tanggal Masuk</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangmasuk as $bm)
            <tr>
                <td>{{ $bm->kode_masuk }}</td>
                <td>{{ $bm->buku->judul ?? '-' }}</td>
                <td>{{ $bm->jumlah }}</td>
                <td>{{ $bm->formatted_tanggal }}</td>
                <td>{{ $bm->ket }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
