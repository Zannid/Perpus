@extends('layouts.backend')
@section('content')

<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
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
                    <select name="id_buku" id="id_buku" class="form-control" required>
                        <option value="">-- Pilih Buku --</option>
                        @foreach($buku as $b)
                            <option value="{{ $b->id }}" 
                                {{ (isset($peminjaman) && $peminjaman->id_buku == $b->id) ? 'selected' : '' }}>
                                {{ $b->judul }}
                            </option>
                        @endforeach
                    </select>
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
