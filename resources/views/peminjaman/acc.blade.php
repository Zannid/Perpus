@extends('layouts.backend')

@section('content')
<div class="container">
  <div class="row">
    <div class="col">
      <h3 class="mb-4">Daftar Peminjaman Pending</h3>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Peminjam</th>
            <th>Buku</th>
            <th>Jumlah</th>
            <th>Tanggal Pinjam</th>
            <th>Tenggat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @php $no = 1; @endphp
          @forelse($peminjaman as $data)
          <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $data->kode_peminjaman }}</td>
            <td>{{ $data->user->name ?? '-' }}</td>
            <td>{{ $data->buku->judul ?? '-' }}</td>
            <td>{{ $data->jumlah }}</td>
            <td>{{ $data->formatted_tanggal_pinjam ?? $data->tgl_pinjam }}</td>
            <td>{{ $data->formatted_tanggal_kembali ?? $data->tenggat }}</td>
            <td>
              <form action="{{ route('petugas.peminjaman.approve', $data->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Setujui peminjaman ini?')">
                  <i class="lni lni-checkmark"></i> ACC
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center">Tidak ada peminjaman pending</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
