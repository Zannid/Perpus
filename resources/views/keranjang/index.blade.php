@extends('layouts.frontend')

@section('title', 'E-Perpus - Keranjang Peminjaman')

@section('css')
<style>
/* ============================================================
   CART PAGE — tema seragam dengan halaman lain
   ============================================================ */
:root {
    --primary: #667eea;
    --primary-dark: #764ba2;
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --light-bg: #f5f6ff;
    --dark-text: #1e2459;
    --muted: #6b7280;
    --border: #e5e7eb;
    --shadow-sm: 0 2px 10px rgba(0,0,0,0.07);
    --shadow-md: 0 6px 24px rgba(0,0,0,0.10);
    --radius: 16px;
}

/* ── Page wrapper ── */
.cart-page {
    padding-top: 0;
    padding-bottom: 60px;
    background: #fafafa;
    min-height: 80vh;
}

/* ── Page Header (sama dengan katalog/detail) ── */
.page-header {
    background: var(--primary-gradient);
    padding: 90px 0 50px;
    position: relative;
    overflow: hidden;
    margin-bottom: 40px;
}
.page-header::before {
    content: '';
    position: absolute;
    width: 360px; height: 360px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
    top: -100px; right: -80px;
}
.page-header::after {
    content: '';
    position: absolute;
    width: 240px; height: 240px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
    bottom: -60px; left: 5%;
}
.header-content { text-align: center; position: relative; z-index: 2; }
.page-title { font-size: 40px; font-weight: 900; color: #fff; margin-bottom: 10px; }
.page-subtitle { font-size: 16px; color: rgba(255,255,255,0.88); margin-bottom: 16px; }
.breadcrumb {
    display: inline-flex;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    padding: 8px 18px;
    border-radius: 30px;
    margin: 0;
}
.breadcrumb-item { color: rgba(255,255,255,0.8); font-size: 13px; }
.breadcrumb-item a { color: #fff; text-decoration: none; font-weight: 600; }
.breadcrumb-item.active { color: rgba(255,255,255,0.95); }
.breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,0.6); }

/* ── Card wrapper ── */
.cart-card {
    background: #fff;
    border-radius: var(--radius);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    border: none;
}

/* ── Cart Header ── */
.cart-card-header {
    background: #fff;
    padding: 20px 24px;
    border-bottom: 1.5px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.cart-card-header h4 {
    font-size: 18px;
    font-weight: 700;
    color: var(--dark-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cart-card-header h4 i { color: var(--primary); }
.cart-count-badge {
    background: var(--primary-gradient);
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
}

/* ── Table ── */
.cart-table { width: 100%; border-collapse: collapse; }
.cart-table thead th {
    background: var(--light-bg);
    color: var(--muted);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    border-bottom: 1.5px solid var(--border);
}
.cart-table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background 0.2s;
}
.cart-table tbody tr:last-child { border-bottom: none; }
.cart-table tbody tr:hover { background: #fafbff; }
.cart-table td { padding: 18px 16px; vertical-align: middle; }

/* ── Book Cell ── */
.book-cell { display: flex; align-items: center; gap: 14px; }
.cart-item-img {
    width: 72px; height: 100px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: var(--shadow-sm);
    flex-shrink: 0;
}
.book-cell-info h6 {
    font-size: 14px; font-weight: 700;
    color: var(--dark-text); margin-bottom: 4px;
    line-height: 1.4;
}
.book-cell-code {
    font-size: 12px; color: var(--primary);
    font-weight: 600; margin-bottom: 6px;
    display: block;
}
.stock-pill {
    display: inline-flex; align-items: center; gap: 4px;
    background: var(--light-bg);
    color: var(--primary);
    border: 1px solid rgba(102,126,234,0.25);
    font-size: 11px; font-weight: 600;
    padding: 3px 10px; border-radius: 20px;
}

/* ── Quantity Control ── */
.qty-control {
    display: inline-flex;
    align-items: center;
    background: var(--light-bg);
    border-radius: 30px;
    padding: 4px 8px;
    gap: 6px;
    border: 1.5px solid var(--border);
}
.btn-qty {
    width: 28px; height: 28px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 50%; padding: 0; border: none;
    background: #fff;
    color: var(--primary);
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    transition: all 0.25s;
    font-size: 14px;
    cursor: pointer;
}
.btn-qty:hover:not(:disabled) {
    background: var(--primary-gradient);
    color: #fff;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(102,126,234,0.3);
}
.btn-qty:disabled { opacity: 0.4; cursor: not-allowed; }
.qty-input {
    width: 34px; text-align: center;
    border: none; background: transparent;
    font-weight: 700; font-size: 14px;
    color: var(--dark-text);
}
.max-stock-warn {
    font-size: 10px; color: #ef4444;
    font-weight: 600; display: block;
    margin-top: 5px; text-align: center;
}

/* ── Delete Button ── */
.btn-delete {
    width: 36px; height: 36px;
    border-radius: 10px; border: 1.5px solid #fecaca;
    background: #fff5f5; color: #ef4444;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; cursor: pointer;
    transition: all 0.25s;
    margin-left: auto;
}
.btn-delete:hover { background: #ef4444; color: #fff; border-color: #ef4444; transform: rotate(10deg); }

/* ── Alert ── */
.cart-alert {
    margin: 16px 20px 0;
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cart-alert-success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
.cart-alert-error { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

/* ── Terms Card ── */
.terms-card {
    background: #fff;
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    padding: 20px 22px;
    border: 1.5px solid var(--border);
    margin-top: 20px;
}
.terms-card h6 {
    font-size: 14px; font-weight: 700;
    color: var(--dark-text); margin-bottom: 14px;
    display: flex; align-items: center; gap: 7px;
}
.terms-card h6 i { color: var(--primary); }
.terms-list { list-style: none; padding: 0; margin: 0; }
.terms-list li {
    font-size: 13px; color: var(--muted);
    padding: 6px 0 6px 22px;
    position: relative;
    border-bottom: 1px dashed var(--border);
}
.terms-list li:last-child { border-bottom: none; }
.terms-list li::before {
    content: '✓';
    position: absolute; left: 0;
    color: var(--primary); font-weight: 700;
}

/* ── Summary Card ── */
.summary-card {
    background: #fff;
    border-radius: var(--radius);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    position: sticky;
    top: 100px;
}
.summary-header {
    background: var(--primary-gradient);
    padding: 18px 22px;
    color: #fff;
}
.summary-header h5 {
    font-size: 16px; font-weight: 700;
    margin: 0; display: flex; align-items: center; gap: 8px;
}
.summary-body { padding: 20px 22px; }
.summary-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 0; border-bottom: 1px solid var(--border);
    font-size: 14px; color: var(--muted);
}
.summary-row:last-of-type { border-bottom: none; }
.summary-row strong { color: var(--dark-text); }
.summary-total {
    display: flex; justify-content: space-between; align-items: center;
    padding: 14px 0 18px;
    font-size: 15px; font-weight: 700; color: var(--dark-text);
    border-top: 2px solid var(--border);
    margin-top: 4px;
}
.summary-total-value { color: var(--primary); font-size: 18px; }

.btn-submit-borrow {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%;
    padding: 14px;
    background: var(--primary-gradient);
    color: #fff;
    border: none; border-radius: 12px;
    font-size: 15px; font-weight: 700;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(102,126,234,0.3);
    transition: all 0.3s;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.btn-submit-borrow:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(102,126,234,0.4);
}

.btn-add-more {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    margin-top: 12px;
    font-size: 13px; font-weight: 600;
    color: var(--primary);
    text-decoration: none;
    padding: 9px;
    border-radius: 10px;
    border: 1.5px solid rgba(102,126,234,0.3);
    background: var(--light-bg);
    transition: all 0.25s;
}
.btn-add-more:hover { background: var(--primary); color: #fff; border-color: var(--primary); }

/* ── Empty State ── */
.empty-cart {
    padding: 70px 20px;
    text-align: center;
}
.empty-cart-icon {
    width: 100px; height: 100px;
    background: var(--light-bg);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 22px;
    font-size: 46px;
    color: var(--primary);
}
.empty-cart h3 { font-size: 22px; font-weight: 700; color: var(--dark-text); margin-bottom: 10px; }
.empty-cart p { font-size: 14px; color: var(--muted); margin-bottom: 24px; line-height: 1.7; }
.btn-browse {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 13px 28px;
    background: var(--primary-gradient);
    color: #fff; border-radius: 12px;
    font-size: 14px; font-weight: 700;
    text-decoration: none;
    box-shadow: 0 6px 18px rgba(102,126,234,0.3);
    transition: all 0.3s;
}
.btn-browse:hover { transform: translateY(-2px); box-shadow: 0 10px 26px rgba(102,126,234,0.4); color: #fff; }

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 991px) {
    .summary-card { position: relative; top: 0; }
    .page-title { font-size: 32px; }
}

@media (max-width: 767px) {
    .page-header { padding: 80px 0 38px; }
    .page-title { font-size: 26px; }
    .page-subtitle { font-size: 14px; }
    .cart-page { padding-bottom: 40px; }

    /* Table → card per item di HP */
    .cart-table thead { display: none; }
    .cart-table, .cart-table tbody,
    .cart-table tr, .cart-table td { display: block; width: 100%; }
    .cart-table tr {
        padding: 16px;
        border-bottom: 1.5px solid var(--border);
        position: relative;
    }
    .cart-table tr:last-child { border-bottom: none; }
    .cart-table td { padding: 0; border: none; }

    /* Buku cell full width */
    .book-cell { margin-bottom: 14px; }
    .cart-item-img { width: 60px; height: 84px; }
    .book-cell-info h6 { font-size: 13px; }

    /* Qty & hapus sejajar */
    .cart-row-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 10px;
    }
    .td-qty, .td-action { display: inline-flex; }

    .cart-card-header { padding: 16px 18px; }
    .cart-card-header h4 { font-size: 16px; }
    .terms-card { padding: 16px; }
    .summary-body { padding: 16px 18px; }
}

@media (max-width: 480px) {
    .page-title { font-size: 22px; }
    .page-subtitle { display: none; }
}
</style>
@endsection

@section('content')

{{-- Page Header --}}
<section class="page-header">
    <div class="container" data-aos="fade-up">
        <div class="header-content">
            <h1 class="page-title">Keranjang Peminjaman</h1>
            <p class="page-subtitle">Periksa kembali buku pilihanmu sebelum diajukan</p>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Home</a></li>
                    <li class="breadcrumb-item active">Keranjang</li>
                </ol>
            </nav>
        </div>
    </div>
</section>

<div class="cart-page">
    <div class="container">
        <div class="row g-4">

            {{-- ── KIRI: Daftar Buku ── --}}
            <div class="col-lg-8">
                <div class="cart-card" data-aos="fade-up">

                    {{-- Header --}}
                    <div class="cart-card-header">
                        <h4><i class="bi bi-cart3"></i> Keranjang Buku</h4>
                        <span class="cart-count-badge">{{ $cart->count() }} Buku</span>
                    </div>

                    {{-- Alerts --}}
                    @if(session('success'))
                        <div class="cart-alert cart-alert-success">
                            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="cart-alert cart-alert-error">
                            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                        </div>
                    @endif

                    @if($cart->count() > 0)
                        {{-- Desktop Table --}}
                        <div class="table-responsive d-none d-md-block">
                            <table class="cart-table">
                                <thead>
                                    <tr>
                                        <th class="ps-5">Buku</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-center pe-5">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $item)
                                    <tr>
                                        {{-- Buku --}}
                                        <td class="ps-5">
                                            <div class="book-cell">
                                                <img src="{{ $item->buku->foto ? asset('storage/buku/' . $item->buku->foto) : asset('assetsf/img/default-book.jpg') }}"
                                                     alt="{{ $item->buku->judul }}" class="cart-item-img">
                                                <div class="book-cell-info">
                                                    <h6>{{ $item->buku->judul }}</h6>
                                                    <span class="book-cell-code">Kode: {{ $item->buku->kode_buku }}</span>
                                                    <span class="stock-pill">
                                                        <i class="bi bi-bookmarks" style="font-size:10px;"></i>
                                                        Stok: {{ $item->buku->stok }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Qty --}}
                                        <td class="text-center">
                                            <div class="qty-control">
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
                                                        {{ $item->jumlah >= $item->buku->stok ? 'disabled' : '' }}>
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            @if($item->jumlah >= $item->buku->stok)
                                                <span class="max-stock-warn">Maks. stok</span>
                                            @endif
                                        </td>

                                        {{-- Hapus --}}
                                        <td class="text-center pe-5">
                                            <form action="{{ route('keranjang.hapus', $item->buku_id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-delete confirm-delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile Card List --}}
                        <div class="d-md-none">
                            @foreach($cart as $item)
                            <div style="padding:16px; border-bottom:1.5px solid var(--border);">
                                {{-- Info Buku --}}
                                <div class="book-cell" style="margin-bottom:12px;">
                                    <img src="{{ $item->buku->foto ? asset('storage/buku/' . $item->buku->foto) : asset('assetsf/img/default-book.jpg') }}"
                                         alt="{{ $item->buku->judul }}" class="cart-item-img">
                                    <div class="book-cell-info">
                                        <h6>{{ $item->buku->judul }}</h6>
                                        <span class="book-cell-code">Kode: {{ $item->buku->kode_buku }}</span>
                                        <span class="stock-pill">
                                            <i class="bi bi-bookmarks" style="font-size:10px;"></i>
                                            Stok: {{ $item->buku->stok }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Qty + Hapus sejajar --}}
                                <div style="display:flex; align-items:center; justify-content:space-between;">
                                    <div>
                                        <div class="qty-control">
                                            <form action="{{ route('keranjang.kurang', $item->buku_id) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn-qty"><i class="bi bi-dash"></i></button>
                                            </form>
                                            <input type="text" class="qty-input" value="{{ $item->jumlah }}" readonly>
                                            <form action="{{ route('keranjang.tambah', $item->buku_id) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn-qty"
                                                    {{ $item->jumlah >= $item->buku->stok ? 'disabled' : '' }}>
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </form>
                                        </div>
                                        @if($item->jumlah >= $item->buku->stok)
                                            <span class="max-stock-warn">Maks. stok</span>
                                        @endif
                                    </div>
                                    <form action="{{ route('keranjang.hapus', $item->buku_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-delete confirm-delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    @else
                        {{-- Empty State --}}
                        <div class="empty-cart">
                            <div class="empty-cart-icon">
                                <i class="bi bi-bag-x"></i>
                            </div>
                            <h3>Keranjang Kosong</h3>
                            <p>Rasanya masih hampa tanpa buku pilihanmu.<br>Yuk, jelajahi koleksi kami sekarang!</p>
                            <a href="{{ route('welcome') }}" class="btn-browse">
                                <i class="bi bi-search"></i> Mulai Cari Buku
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Syarat & Ketentuan --}}
                @if($cart->count() > 0)
                <div class="terms-card" data-aos="fade-up" data-aos-delay="100">
                    <h6><i class="bi bi-shield-check"></i> Syarat & Ketentuan Peminjaman</h6>
                    <ul class="terms-list">
                        <li>Maksimal durasi peminjaman adalah 7 hari sejak disetujui.</li>
                        <li>Pastikan membawa ID card saat pengambilan buku.</li>
                        <li>Petugas berhak menolak pengajuan jika data tidak valid.</li>
                        <li>Kerusakan atau kehilangan akan dikenakan denda sesuai aturan.</li>
                    </ul>
                </div>
                @endif
            </div>

            {{-- ── KANAN: Ringkasan ── --}}
            @if($cart->count() > 0)
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="150">
                <div class="summary-card">
                    <div class="summary-header">
                        <h5><i class="bi bi-receipt"></i> Ringkasan Peminjaman</h5>
                    </div>
                    <div class="summary-body">
                        <div class="summary-row">
                            <span>Jumlah Judul Buku</span>
                            <strong>{{ $cart->count() }} judul</strong>
                        </div>
                        <div class="summary-row">
                            <span>Total Eksemplar</span>
                            <strong>{{ $cart->sum('jumlah') }} buku</strong>
                        </div>
                        <div class="summary-row">
                            <span>Durasi Pinjam</span>
                            <strong>Maks. 7 hari</strong>
                        </div>

                        <div class="summary-total">
                            <span>Total Pinjam</span>
                            <span class="summary-total-value">{{ $cart->sum('jumlah') }} Buku</span>
                        </div>

                        <form action="{{ route('keranjang.submit') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-submit-borrow">
                                <i class="bi bi-send-check"></i>
                                Ajukan Sekarang
                            </button>
                        </form>

                        <a href="{{ route('welcome') }}" class="btn-add-more">
                            <i class="bi bi-plus-lg"></i> Tambah Buku Lagi
                        </a>
                    </div>
                </div>
            </div>
            @endif

        </div>
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
            text: 'Buku ini akan dihapus dari keranjang peminjaman Anda.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            borderRadius: '16px',
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endsection