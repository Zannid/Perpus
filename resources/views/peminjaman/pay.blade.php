@extends('layouts.backend')
@section('title', 'E-Perpus - Pembayaran Denda')
@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-danger text-white">
            <h4 class="mb-0">Pembayaran Denda</h4>
        </div>
        <div class="card-body">
            <h5>Detail Peminjaman</h5>
            <p><strong>Kode:</strong> {{ $peminjaman->kode_peminjaman }}</p>
            <p><strong>Buku:</strong> {{ $peminjaman->buku->judul }}</p>
            <p><strong>Jumlah:</strong> {{ $peminjaman->jumlah }}</p>
            <p><strong>Kondisi:</strong> {{ $peminjaman->kondisi }}</p>
            <p><strong>Denda:</strong>
                <span class="text-danger fw-bold">
                    Rp {{ number_format($peminjaman->due_denda, 0, ',', '.') }}
                </span>
            </p>

            {{-- Tombol Bayar di Tempat --}}
            <form action="{{ route('peminjaman.confirmPay', $peminjaman->id) }}" method="POST" class="mb-3">
                @csrf
                <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Apakah Anda akan membayar denda ini di tempat/perpustakaan?')">
                    <i class="bx bx-money"></i> Bayar di Tempat/Perpustakaan
                </button>
            </form>
            <p class="text-muted small mb-3">Pilih opsi ini jika Anda akan membayar denda secara tunai di perpustakaan.</p>

            {{-- Tombol Bayar QRIS --}}
            <a href="{{ route('peminjaman.pay.qris', $peminjaman->id) }}"
               class="btn btn-primary w-100">
                <i class="bx bx-qr"></i> Bayar dengan QRIS
            </a>
            <p class="text-muted small">Pilih opsi ini untuk pembayaran digital melalui QRIS.</p>
        </div>
    </div>
</div>
@endsection
