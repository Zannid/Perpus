@extends('layouts.backend')
@section('content')
<div class="col-md-12">
  <div class="card shadow-lg">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Data Pengembalian</h5>
      {{-- Jika nanti ada fitur tambah, tinggal aktifkan --}}
      {{-- <a href="{{ route('pengembalian.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
        <i class="lni lni-plus me-1"></i> Tambah Pengembalian
      </a> --}}
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="basic-datatables" class="display table table-striped table-hover">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Kode Peminjaman</th>
              <th>Nama Peminjam</th>
              <th>Judul Buku</th>
              <th>Tanggal Pinjam</th>
              <th>Tanggal Kembali</th>
              <th>Jumlah</th>
              <th>Denda</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pengembalian as $i => $data)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $data->peminjaman->kode_peminjaman }}</td>
              <td>{{ $data->user->name ?? '-' }}</td>
              <td>{{ $data->buku->judul ?? '-' }}</td>
              <td>{{ $data->tgl_pinjam }}</td>
              <td>{{ $data->tgl_kembali }}</td>
              <td>{{ $data->jumlah }}</td>
              <td>
                @if($data->denda > 0)
                  <span class="badge bg-danger">Rp {{ number_format($data->denda, 0, ',', '.') }}</span>
                @else
                  <span class="badge bg-success">-</span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
