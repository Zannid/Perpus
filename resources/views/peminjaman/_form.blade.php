@extends('layouts.backend')
@section('title', isset($peminjaman) ? 'E-Perpus - Edit Peminjaman' : 'E-Perpus - Tambah Peminjaman')
@section('content')

<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header text-white">
            <h4 class="mb-0">
                {{ isset($peminjaman) ? 'Edit Data Peminjaman' : 'Tambah Data Peminjaman' }}
            </h4>
        </div>
        <div class="card-body">
            <form action="{{ isset($peminjaman) ? route('peminjaman.update', $peminjaman->id) : route('peminjaman.store') }}" method="POST">
                @csrf
                @if(isset($peminjaman))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="id_buku" class="form-label">Buku</label>
                    <div class="dropdown">
                        <button class="text-start dropdown-toggle form-control" type="button" id="bukuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ isset($peminjaman) ? $peminjaman->buku->judul : '-- Pilih Buku --' }}
                        </button>
                        <ul class="dropdown-menu w-100 p-2" aria-labelledby="bukuDropdown" id="bukuList" style="max-height: 300px; overflow-y: auto;">
                            <!-- Search input di dalam dropdown -->
                            <li class="px-2 mb-2">
                                <input type="text" id="searchBuku" class="form-control" placeholder="Cari buku...">
                            </li>
                            <hr class="my-0">
                            @foreach ($buku as $b)
                                <li style="display: flex; gap: 10px;" class="m-2 buku-item" data-judul="{{ strtolower($b->judul) }}">
                                    <img src="{{ asset('storage/buku/' . $b->foto) }}" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                    <a class="dropdown-item pilih-buku" href="#" 
                                       data-id="{{ $b->id }}" 
                                       data-judul="{{ $b->judul }}" 
                                       data-stok="{{ $b->stok }}">
                                        <div>
                                            <strong>{{ $b->judul }}</strong><br>
                                            <small>Stok: {{ $b->stok }}</small>
                                        </div>
                                    </a>
                                </li>
                                <hr class="my-0">
                            @endforeach
                        </ul>
                    </div>
                    <input type="hidden" name="id_buku" id="id_buku" value="{{ old('id_buku', isset($peminjaman) ? $peminjaman->id_buku : '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control"
                        value="{{ old('jumlah', isset($peminjaman) ? $peminjaman->jumlah : '') }}" required>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="tgl_pinjam" class="form-label">Tanggal Pinjam</label>
                            <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control"
                                value="{{ old('tgl_pinjam', isset($peminjaman) ? $peminjaman->tgl_pinjam : '') }}" required>
                        </div>  
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="tenggat" class="form-label">Tanggal Kembali</label>
                            <input type="date" name="tenggat" id="tenggat" class="form-control"
                                value="{{ old('tenggat', isset($peminjaman) ? $peminjaman->tenggat : '') }}" readonly required>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($peminjaman) ? 'Update' : 'Simpan' }}
                    </button>
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Event pilih buku
    document.querySelectorAll('.pilih-buku').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            let id = this.dataset.id;
            let judul = this.dataset.judul;
            let stok = this.dataset.stok;

            document.getElementById('id_buku').value = id;
            document.getElementById('bukuDropdown').textContent = `${judul} (Stok: ${stok})`;
        });
    });

    // Search/filter buku di dropdown
    const searchInput = document.getElementById('searchBuku');
    const bukuItems = document.querySelectorAll('.buku-item');

    searchInput.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        bukuItems.forEach(item => {
            const judul = item.dataset.judul;
            item.style.display = judul.includes(filter) ? 'flex' : 'none';
        });
    });

    // Auto hitung tanggal kembali +7 hari
    document.getElementById('tgl_pinjam').addEventListener('change', function() {
        let tglPinjam = new Date(this.value);
        if (!isNaN(tglPinjam.getTime())) {
            tglPinjam.setDate(tglPinjam.getDate() + 7);
            let year = tglPinjam.getFullYear();
            let month = String(tglPinjam.getMonth() + 1).padStart(2, '0');
            let day = String(tglPinjam.getDate()).padStart(2, '0');
            document.getElementById('tenggat').value = `${year}-${month}-${day}`;
        }
    });
</script>

@endsection
