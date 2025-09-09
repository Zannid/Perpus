@extends('layouts.backend')
@section('content')
<div class="container">
    <h2>{{ isset($bm) ? 'Edit Data Barang Masuk' : 'Tambah Data Barang Masuk' }}</h2>

    <form action="{{ isset($bm) ? route('barangmasuk.update', $bm->id) : route('barangmasuk.store') }}" method="POST">
        @csrf
        @if(isset($bm))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="id_buku" class="form-label">Buku</label>
            <select name="id_buku" id="id_buku" class="form-control" required>
                <option value="">-- Pilih Buku --</option>
                @foreach($buku as $b)
                    <option value="{{ $b->id }}" 
                        {{ (isset($bm) && $bm->id_buku == $b->id) ? 'selected' : '' }}>
                        {{ $b->judul }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control"
                value="{{ old('jumlah', isset($bm) ? $bm->jumlah : '') }}" required>
        </div>

        <div class="mb-3">
            <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
            <input type="date" name="tgl_masuk" id="tgl_masuk" class="form-control"
                value="{{ old('tgl_masuk', isset($bm) ? $bm->tgl_masuk : '') }}" required>
        </div>

        <div class="mb-3">
            <label for="ket" class="form-label">Keterangan</label>
            <textarea name="ket" id="ket" class="form-control">{{ old('ket', isset($bm) ? $bm->ket : '') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            {{ isset($bm) ? 'Update' : 'Simpan' }}
        </button>
        <a href="{{ route('barangmasuk.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

@endsection
