@extends('layouts.backend')

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
            <p><strong>Denda:</strong> <span class="text-danger fw-bold">Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</span></p>

            <form action="{{ route('peminjaman.confirmPay', $peminjaman->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success w-100">Bayar Sekarang</button>
            </form>
        </div>
    </div>
</div>
@endsection
