<!DOCTYPE html>
<html>
<head>
    <title>Laporan Barang Keluar</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Laporan Barang Keluar</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Keluar</th>
                <th>Judul Buku</th>
                <th>Jumlah</th>
                <th>Tanggal Keluar</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bk as $index => $item)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $item->kode_keluar }}</td>
                    <td>{{ $item->buku->judul ?? '-' }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ $item->tgl_keluar }}</td>
                    <td>{{ $item->ket }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
w