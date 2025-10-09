@extends('layouts.backend')
@section('title', 'E-Perpus - Peminjaman Pending')
@section('content')
<div class="container">

  {{-- Breadcrumb --}}
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-3">
      <li class="breadcrumb-item">
        <a href="{{ route('peminjaman.index') }}" class="text-decoration-none text-primary fw-semibold">
          Peminjaman
        </a>
      </li>
      <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
        Pending
      </li>
    </ol>
  </nav>

  <div class="card shadow-lg border-0 rounded-3">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Daftar Peminjaman Pending</h5>
      <div class="d-flex flex-wrap align-items-center gap-2">
          {{-- Search Input JS --}}
          <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari Pengajuan...">
      </div>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle" id="peminjamanTable">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>Nama Peminjam</th>
              <th>Buku</th>
              <th>Jumlah</th>
              <th>Tanggal Pinjam</th>
              <th>Tenggat</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php $no = 1; @endphp
            @forelse($peminjaman as $data)
              <tr>
                <td>{{ $no++ }}</td>
                <td class="kode">{{ $data->kode_peminjaman }}</td>
                <td class="nama">{{ $data->user->name ?? '-' }}</td>
                <td class="buku">{{ $data->buku->judul ?? '-' }}</td>
                <td>{{ $data->jumlah }}</td>
                <td>{{ $data->formatted_tanggal_pinjam ?? $data->tgl_pinjam }}</td>
                <td>{{ $data->formatted_tanggal_kembali ?? $data->tenggat }}</td>
                <td class="text-center">
                  <form action="{{ route('petugas.peminjaman.approve', $data->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3"
                            onclick="return confirm('Setujui peminjaman ini?')">
                      ACC
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-muted">Tidak ada peminjaman pending</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('peminjamanTable').getElementsByTagName('tbody')[0];

    searchInput.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = table.getElementsByTagName('tr');

        Array.from(rows).forEach(row => {
            const kode = row.querySelector('.kode')?.textContent.toLowerCase() ?? '';
            const nama = row.querySelector('.nama')?.textContent.toLowerCase() ?? '';
            const buku = row.querySelector('.buku')?.textContent.toLowerCase() ?? '';

            if(kode.includes(filter) || nama.includes(filter) || buku.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

@endsection
