@extends('layouts.backend')
@section('title', 'Edit Pengembalian')
@section('content')

<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-success text-white p-3">
            <h4 class="mb-0 text-white">
                <i class="bx bx-edit me-2"></i>Edit Data Pengembalian
            </h4>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-info d-flex align-items-center mb-4">
                <i class="bx bx-info-circle me-3 fs-3"></i>
                <div>
                    <strong>Info Transaksi:</strong> <br>
                    Peminjam: {{ $pengembalian->user->name }} | 
                    Buku: {{ $pengembalian->buku->judul }} | 
                    Kode PMJ: {{ $pengembalian->peminjaman->kode_peminjaman }}
                </div>
            </div>

            <form action="{{ route('pengembalian.update', $pengembalian->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tanggal Kembali</label>
                        <input type="date" name="tgl_kembali" class="form-control" value="{{ $pengembalian->tgl_kembali }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Kondisi Saat Kembali</label>
                        <select name="kondisi" class="form-select">
                            <option value="Bagus" {{ $pengembalian->kondisi == 'Bagus' ? 'selected' : '' }}>Bagus</option>
                            <option value="Rusak" {{ $pengembalian->kondisi == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                            <option value="Hilang" {{ $pengembalian->kondisi == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Denda (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="denda" class="form-control" value="{{ $pengembalian->denda }}" min="0">
                        </div>
                        <small class="text-muted italic">* Denda ini otomatis diakumulasikan ke total denda peminjaman.</small>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4 gap-2">
                    <a href="{{ route('pengembalian.index') }}" class="btn btn-label-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-success px-4 shadow">
                        <i class="bx bx-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
