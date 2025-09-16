@extends('layouts.backend')
@section('content')

<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header text-white">
            <h4 class="mb-0">
                {{ isset($peminjaman) ? 'Edit Data Barang Keluar' : 'Tambah Data Barang Keluar' }}
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
        <ul class="dropdown-menu w-100" aria-labelledby="bukuDropdown">
            @foreach ($buku as $b)
                <li style="display: flex; gap: 10px;" class="m-3">
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
    // Event untuk pilih buku
    document.querySelectorAll('.pilih-buku').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();

            let id = this.dataset.id;
            let judul = this.dataset.judul;
            let stok = this.dataset.stok;

            // isi value ke hidden input
            document.getElementById('id_buku').value = id;

            // ubah tulisan tombol dropdown
            document.getElementById('bukuDropdown').textContent = `${judul} (Stok: ${stok})`;
        });
    });
</script>
<script>
document.getElementById('tgl_pinjam').addEventListener('change', function() {
    let tglPinjam = new Date(this.value);
    if (!isNaN(tglPinjam.getTime())) {
        // tambah 7 hari
        tglPinjam.setDate(tglPinjam.getDate() + 7);

        // format ke yyyy-mm-dd
        let year = tglPinjam.getFullYear();
        let month = String(tglPinjam.getMonth() + 1).padStart(2, '0');
        let day = String(tglPinjam.getDate()).padStart(2, '0');
        let formatted = `${year}-${month}-${day}`;

        document.getElementById('tenggat').value = formatted;
    }
});
</script>

@endsection
