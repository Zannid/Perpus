@extends('layouts.frontend')

@section('title', 'E-Perpus - Keranjang Peminjaman')

@section('css')
<style>
    .cart-container {
        padding-top: 120px;
        padding-bottom: 60px;
        min-height: 80vh;
    }
    .cart-item-img {
        width: 80px;
        height: 110px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .qty-control {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border-radius: 30px;
        padding: 5px 10px;
        width: fit-content;
        margin: 0 auto;
    }
    .qty-input {
        width: 40px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 700;
        font-size: 1rem;
        color: #333;
    }
    .btn-qty {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        padding: 0;
        border: none;
        background: #fff;
        color: #007bff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.2s;
    }
    .btn-qty:hover:not(:disabled) {
        background: #007bff;
        color: #fff;
        transform: scale(1.1);
    }
    .btn-qty:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .empty-cart-container {
        padding: 80px 20px;
        text-align: center;
    }
    .empty-cart-icon {
        font-size: 100px;
        background: linear-gradient(45deg, #f3f3f3, #e9ecef);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 25px;
    }
    .card-summary {
        position: sticky;
        top: 100px;
    }
    @media (max-width: 768px) {
        .cart-item-img {
            width: 60px;
            height: 85px;
        }
        .qty-control {
            padding: 2px 5px;
        }
        .qty-input {
            width: 30px;
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('content')
<div class="cart-container container">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white py-4 px-4 d-flex justify-content-between align-items-center border-bottom">
                    <h4 class="mb-0 fw-bold">
                        <i class="bi bi-cart3 me-2 text-primary"></i>Keranjang
                    </h4>
                    <span class="badge bg-primary rounded-pill px-3">{{ $cart->count() }} Buku</span>
                </div>
                
                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($cart->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 py-3">Buku</th>
                                        <th class="text-center py-3">Jumlah</th>
                                        <th class="text-end pe-4 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $item)
                                        <tr>
                                            <td class="ps-4 py-4">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item->buku->foto ? asset('storage/buku/' . $item->buku->foto) : asset('assetsf/img/default-book.jpg') }}" 
                                                         alt="{{ $item->buku->judul }}" class="cart-item-img me-3">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold text-dark">{{ $item->buku->judul }}</h6>
                                                        <p class="mb-1 small text-muted">Kode: <span class="text-primary fw-semibold">{{ $item->buku->kode_buku }}</span></p>
                                                        <span class="badge bg-light text-primary border border-primary-subtle rounded-pill">Stok: {{ $item->buku->stok }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center py-4">
                                                <div class="qty-control shadow-sm">
                                                    <form action="{{ route('keranjang.kurang', $item->buku_id) }}" method="POST" class="m-0">
                                                        @csrf
                                                        <button type="submit" class="btn-qty" title="Kurangi">
                                                            <i class="bi bi-dash"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <input type="text" class="qty-input" value="{{ $item->jumlah }}" readonly>
                                                    
                                                    <form action="{{ route('keranjang.tambah', $item->buku_id) }}" method="POST" class="m-0">
                                                        @csrf
                                                        <button type="submit" class="btn-qty" 
                                                            {{ $item->jumlah >= $item->buku->stok ? 'disabled' : '' }} title="Tambah">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                @if($item->jumlah >= $item->buku->stok)
                                                    <div class="mt-2">
                                                        <small class="text-danger fw-600" style="font-size: 0.7rem;">Maksimal Stok</small>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4 py-4">
                                                <form action="{{ route('keranjang.hapus', $item->buku_id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill confirm-delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-cart-container">
                            <i class="bi bi-bag-x empty-cart-icon"></i>
                            <h3 class="fw-bold text-dark">Keranjang Kosong</h3>
                            <p class="text-muted mb-4 px-5">Rasanya masih hampa tanpa buku pilihanmu. <br> Yuk, jelajahi koleksi kami sekarang!</p>
                            <a href="{{ route('welcome') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow">
                                <i class="bi bi-search me-2"></i>Mulai Cari Buku
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            @if($cart->count() > 0)
            <div class="mt-4 p-4 bg-white rounded-4 shadow-sm border">
                <h6 class="fw-bold mb-3 d-flex align-items-center">
                    <i class="bi bi-info-circle text-primary me-2"></i>Syarat & Ketentuan Peminjaman
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="mb-0 small text-muted ps-3">
                            <li class="mb-2">Maksimal durasi peminjaman adalah 7 hari sejak disetujui.</li>
                            <li class="mb-2">Pastikan membawa ID card saat pengambilan buku.</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="mb-0 small text-muted ps-3">
                            <li class="mb-2">Petugas berhak menolak pengajuan jika data tidak valid.</li>
                            <li class="mb-2">Kerusakan atau kehilangan akan dikenakan denda sesuai aturan.</li>
                        </ul>
                    </div>
                </div>
            </div>
            @endif
        </div>

        @if($cart->count() > 0)
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden card-summary">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 border-bottom pb-3">Ringkasan Peminjaman</h5>
                    
                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Jumlah Judul Buku</span>
                        <span class="fw-bold text-dark">{{ $cart->count() }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-4 fs-5 fw-bold text-dark border-bottom pb-3">
                        <span>Total Qty</span>
                        <span class="text-primary">{{ $cart->sum('jumlah') }} Buku</span>
                    </div>

                    <form action="{{ route('keranjang.submit') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-4 shadow hover-lift">
                            <span class="fw-bold">AJUKAN SEKARANG</span>
                            <i class="bi bi-arrow-right-circle ms-2"></i>
                        </button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('welcome') }}" class="text-decoration-none small fw-bold">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Buku Lagi
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.confirm-delete').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            
            Swal.fire({
                title: 'Hapus Buku?',
                text: 'Buku ini akan dihapus dari antrean peminjaman Anda.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'px-4 py-2 rounded-pill',
                    cancelButton: 'px-4 py-2 rounded-pill'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection