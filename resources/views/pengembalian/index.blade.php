@extends('layouts.backend')
@section('title', 'E-Perpus - Data Pengembalian')
@section('content')
<div class="col-md-12">
  <nav>
    <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-3">
      <li class="breadcrumb-item">
        <a href="{{ route('pengembalian.index') }}" class="text-decoration-none text-primary fw-semibold">
          Pengembalian
        </a>
      </li>
      <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
        Data Pengembalian
      </li>
    </ol>
  </nav>

  <div class="card shadow-lg">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Data Pengembalian</h5>
      <div class="d-flex align-items-center gap-2">
        {{-- Form Search --}}
        <form action="{{ route('pengembalian.index') }}" method="get" class="d-flex">
          <div class="input-group input-group-sm">
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari peminjaman...">
            <button class="btn btn-primary" type="submit">
              <i class="bx bx-search-alt"></i>
            </button>
          </div>
        </form>
        <a href="{{ route('pengembalian.export', request()->query()) }}" 
                target="_blank" 
                class="btn btn-danger btn-sm rounded-pill px-3">
                <i class="bx bx-file"></i> Buat PDF
                </a>
        
    </div>
    </div>

    <div class="card-body">

    @forelse($pengembalian as $i => $data)
    <div class="border rounded shadow-sm mb-4 p-3">

        {{-- HEADER STATUS --}}
        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
            <div class="text-primary fw-semibold">
                Pengembalian • {{ $data->peminjaman->kode_peminjaman }}
            </div>
            <span class="badge bg-success">DIKEMBALIKAN</span>
        </div>

        {{-- ISI DETAIL --}}
        <div class="d-flex">
            {{-- Foto Buku --}}
            <div>
                <img src="{{ asset('/storage/buku/'. $data->buku->foto) }}"
                     width="80" class="rounded border">
            </div>

            {{-- Detail --}}
            <div class="ms-3 flex-grow-1">
                <h6 class="fw-bold mb-1">{{ $data->buku->judul }}</h6>
                <div class="text-muted small">
                    Peminjam: <strong>{{ $data->user->name }}</strong>
                </div>
                <div class="text-muted small">
                    Tgl Pinjam: {{ $data->tgl_pinjam }}
                </div>
                <div class="text-muted small">
                    Tgl Kembali: {{ $data->tgl_kembali }}
                </div>
                <div class="text-muted small">
                    Jumlah: {{ $data->jumlah }}
                </div>
            </div>

            {{-- Harga / Denda --}}
            <div class="text-end">
                @if($data->denda > 0)
                    <div class="fw-bold text-danger">
                        Rp {{ number_format($data->denda, 0, ',', '.') }}
                    </div>
                    <small class="text-muted">Denda</small>
                @else
                    <div class="fw-bold text-success">Tidak Ada Denda</div>
                @endif
            </div>
        </div>

        {{-- FOOTER TERAKHIR --}}
        <div class="d-flex justify-content-between align-items-center border-top mt-3 pt-3">
            <div class="fw-bold">
                Total: 
                <span class="text-orange" style="color:#ff5a00;">
                    Rp {{ number_format($data->denda ?? 0, 0, ',', '.') }}
                </span>
            </div>

            <div class="d-flex gap-2">
                <a href="https://wa.me/" class="btn btn-outline-secondary btn-sm">Hubungi Admin</a>
                <button class="btn btn-primary btn-sm">Detail</button>
            </div>
        </div>

    </div>
    @empty
        <p class="text-center text-muted">Tidak ada data pengembalian</p>
    @endforelse

</div>

  </div>
</div>
<script>
  const searchInput = document.getElementById('searchInput');
  const table = document.getElementById('basic-datatables').getElementsByTagName('tbody')[0];
  searchInput.addEventListener('keyup', function() {
      const filter = this.value.toLowerCase();
      const tableRows = table.getElementsByTagName('tr');

      Array.from(tableRows).forEach(row => {
          const kodePeminjaman = row.cells[1].textContent.toLowerCase();
          const namaPeminjam = row.cells[2].textContent.toLowerCase();
          const judulBuku = row.cells[3].textContent.toLowerCase();

          if (kodePeminjaman.includes(filter) || namaPeminjam.includes(filter) || judulBuku.includes(filter)) {
              row.style.display = '';
          } else {
              row.style.display = 'none';
          }
      });
  });
</script>
@endsection
