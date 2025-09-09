<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Form Barang Keluar</title>
  </head>
  <body>

<div class="container mt-4">
    <h2>{{ isset($bk) ? 'Edit Data Barang Keluar' : 'Tambah Data Barang Keluar' }}</h2>

    <form action="{{ isset($bk) ? route('barangkeluar.update', $bk->id) : route('barangkeluar.store') }}" method="POST">
        @csrf
        @if(isset($bk))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="id_buku" class="form-label">Buku</label>
            <select name="id_buku" id="id_buku" class="form-control" required>
                <option value="">-- Pilih Buku --</option>
                @foreach($buku as $b)
                    <option value="{{ $b->id }}" 
                        {{ (isset($bk) && $bk->id_buku == $b->id) ? 'selected' : '' }}>
                        {{ $b->judul }}
                    </option>
                @endforeach
            </select>
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

        <button type="submit" class="btn btn-primary">
            {{ isset($bk) ? 'Update' : 'Simpan' }}
        </button>
        <a href="{{ route('barangkeluar.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

  </body>
</html>
