@extends('layouts.backend')
@section('title', isset($bk) ? 'E-Perpus - Edit Barang Keluar' : 'E-Perpus - Tambah Barang Keluar')
@section('content')

<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header text-white">
            <h4 class="mb-0">
                {{ isset($bk) ? 'Edit Data Barang Keluar' : 'Tambah Data Barang Keluar' }}
            </h4>
        </div>
        <div class="card-body">
            <form action="{{ isset($bk) ? route('barangkeluar.update', $bk->id) : route('barangkeluar.store') }}" method="POST">
                @csrf
                @if(isset($bk))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="id_buku" class="form-label">Buku</label>
                    <div class="dropdown">
                        <button class="text-start dropdown-toggle form-control" type="button" id="bukuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ isset($bk) ? $bk->buku->judul : '-- Pilih Buku --' }}
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
                    <input type="hidden" name="id_buku" id="id_buku" value="{{ old('id_buku', isset($bk) ? $bk->id_buku : '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control"
                        value="{{ old('jumlah', isset($bk) ? $bk->jumlah : '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="tgl_keluar" class="form-label">Tanggal Keluar</label>
                    <input type="date" name="tgl_keluar" id="tgl_keluar" class="form-control"
                        value="{{ old('tgl_keluar', isset($bk) ? $bk->tgl_keluar : '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="ket" class="form-label">Keterangan</label>
                    <textarea name="ket" id="ket" class="form-control">{{ old('ket', isset($bk) ? $bk->ket : '') }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($bk) ? 'Update' : 'Simpan' }}
                    </button>
                    <a href="{{ route('barangkeluar.index') }}" class="btn btn-secondary">Batal</a>
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
</script>

@endsection
