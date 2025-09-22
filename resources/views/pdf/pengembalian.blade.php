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
        @foreach ($data as $i => $data)
                <tr>
                      <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->kode_kembali }}</td>
                    <td>{{ $data->user->name ?? '-' }}</td>
                    <td>{{ $data->buku->judul }}</td>
                    <td>{{ $data->jumlah }}</td>
              <td>{{ $data->formatted_tanggal_pinjam ?? $data->tgl_pinjam }}</td>
              <td>{{ $data->formatted_tanggal_kembali ?? $data->tenggat }}</td>
                                 <td>
                              <span class="badge {{ $data->status == 'Sedang Dipinjam' ? 'bg-danger' : 'bg-success' }}">
                                {{ $data->status }}
                              </span>
                            </td>
                </tr>
            @endforeach
        </tbody>
 </table>
</body>
</html>
