@extends('layouts.backend')
@section('title', 'E-Perpus - Detail Peminjaman')
@section('content')

<style>
/* ============================================================
   DETAIL PEMINJAMAN — Responsive
   ============================================================ */
.detail-page { padding: 0 4px; }

/* ── Breadcrumb ── */
.breadcrumb-wrap {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 10px 16px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.breadcrumb-wrap a { color: #667eea; font-weight: 600; text-decoration: none; }
.breadcrumb-wrap a:hover { text-decoration: underline; }
.breadcrumb-sep { color: #aaa; }
.breadcrumb-current { color: #333; font-weight: 700; }

/* ── Main Card ── */
.detail-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.09);
    overflow: hidden;
    border: none;
}

/* ── Card Header ── */
.detail-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}
.detail-card-header h5 {
    color: #fff;
    font-size: 16px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.detail-card-header h5 i { font-size: 18px; }

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.3px;
}
.status-dipinjam  { background: #dcfce7; color: #16a34a; }
.status-pending   { background: #fef9c3; color: #a16207; }
.status-ditolak   { background: #fee2e2; color: #dc2626; }
.status-dikembalikan { background: #e0f2fe; color: #0369a1; }
.status-default   { background: #f3f4f6; color: #6b7280; }

/* ── Card Body ── */
.detail-card-body { padding: 24px; }

/* ── Section Title ── */
.section-title {
    font-size: 14px;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
    padding-bottom: 8px;
    border-bottom: 2px solid #f0f2ff;
}
.section-title i { font-size: 16px; }

/* ── Info Grid ── */
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 28px;
}

/* ── Info Table ── */
.info-table { width: 100%; }
.info-table tr td { padding: 8px 0; vertical-align: top; font-size: 14px; border-bottom: 1px dashed #f0f0f0; }
.info-table tr:last-child td { border-bottom: none; }
.info-table .info-label { color: #6b7280; font-weight: 500; white-space: nowrap; padding-right: 10px; width: 45%; }
.info-table .info-sep { color: #aaa; padding-right: 8px; }
.info-table .info-value { color: #1e2459; font-weight: 600; }

/* ── Books Table ── */
.books-section { margin-bottom: 28px; }
.table-wrapper {
    border-radius: 10px;
    overflow: hidden;
    border: 1.5px solid #e5e7eb;
}
.books-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.books-table thead th {
    background: #f5f6ff;
    color: #6b7280;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    padding: 11px 14px;
    border-bottom: 1.5px solid #e5e7eb;
    white-space: nowrap;
}
.books-table tbody tr { border-bottom: 1px solid #f0f0f0; transition: background 0.2s; }
.books-table tbody tr:last-child { border-bottom: none; }
.books-table tbody tr:hover { background: #fafbff; }
.books-table td { padding: 12px 14px; color: #1e2459; vertical-align: middle; }
.books-table tfoot td {
    background: #f5f6ff;
    font-weight: 700;
    padding: 10px 14px;
    font-size: 13px;
    color: #1e2459;
    border-top: 1.5px solid #e5e7eb;
}
.qty-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    min-width: 28px;
    height: 28px;
    border-radius: 8px;
    padding: 0 8px;
}

/* ── Mobile Book Cards ── */
.book-card-mobile {
    background: #f8f9ff;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    padding: 14px;
    margin-bottom: 10px;
}
.book-card-mobile:last-child { margin-bottom: 0; }
.book-card-mobile-title { font-size: 14px; font-weight: 700; color: #1e2459; margin-bottom: 6px; }
.book-card-mobile-row {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 3px;
}
.book-card-mobile-row span:last-child { font-weight: 600; color: #1e2459; }
.book-num { display: inline-block; background: #667eea; color: #fff; font-size: 11px; font-weight: 700; border-radius: 5px; padding: 1px 7px; margin-bottom: 6px; }

/* ── Tanggal Section ── */
.date-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 12px;
    margin-bottom: 28px;
}
.date-card {
    background: #f5f6ff;
    border-radius: 10px;
    padding: 14px 16px;
    border: 1.5px solid #e0e4ff;
}
.date-card-label { font-size: 11px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; display: flex; align-items: center; gap: 5px; }
.date-card-label i { color: #667eea; font-size: 13px; }
.date-card-value { font-size: 14px; font-weight: 700; color: #1e2459; }

/* ── Rejection Alert ── */
.reject-alert {
    background: #fef2f2;
    border: 1.5px solid #fecaca;
    border-left: 4px solid #ef4444;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 24px;
}
.reject-alert h6 { font-size: 14px; font-weight: 700; color: #dc2626; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
.reject-alert p { font-size: 13px; color: #7f1d1d; margin: 0; line-height: 1.6; }

/* ── Footer Buttons ── */
.detail-footer {
    padding-top: 20px;
    border-top: 1.5px solid #f0f0f0;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 22px;
    background: #f3f4f6;
    color: #374151;
    border: 1.5px solid #d1d5db;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.25s;
}
.btn-back:hover { background: #e5e7eb; color: #111; }

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 767px) {
    .detail-card-body { padding: 16px; }
    .detail-card-header { padding: 16px 18px; }
    .detail-card-header h5 { font-size: 15px; }
    .info-grid { grid-template-columns: 1fr; gap: 0; }
    .info-section { margin-bottom: 22px; }
    /* Sembunyikan tabel desktop, tampilkan card mobile */
    .books-table-desktop { display: none; }
    .books-cards-mobile { display: block; }
    .date-grid { grid-template-columns: 1fr 1fr; }
}

@media (min-width: 768px) {
    .books-cards-mobile { display: none; }
    .books-table-desktop { display: block; }
}

@media (max-width: 400px) {
    .date-grid { grid-template-columns: 1fr; }
    .detail-card-body { padding: 14px; }
}
</style>

<div class="detail-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb-wrap">
        <a href="{{ route('peminjaman.index') }}">
            <i class="bx bx-book-content"></i> Peminjaman
        </a>
        <span class="breadcrumb-sep">›</span>
        <span class="breadcrumb-current">Detail</span>
    </div>

    <div class="detail-card">

        {{-- Header --}}
        <div class="detail-card-header">
            <h5>
                <i class="bx bx-book-content"></i>
                Detail Peminjaman
            </h5>
            @php
                $statusClass = match($peminjaman->status) {
                    'Dipinjam'     => 'status-dipinjam',
                    'Pending'      => 'status-pending',
                    'Ditolak'      => 'status-ditolak',
                    'Dikembalikan' => 'status-dikembalikan',
                    default        => 'status-default',
                };
                $statusIcon = match($peminjaman->status) {
                    'Dipinjam'     => 'bx-check-circle',
                    'Pending'      => 'bx-time',
                    'Ditolak'      => 'bx-x-circle',
                    'Dikembalikan' => 'bx-undo',
                    default        => 'bx-help-circle',
                };
            @endphp
            <span class="status-badge {{ $statusClass }}">
                <i class="bx {{ $statusIcon }}"></i>
                {{ $peminjaman->status }}
            </span>
        </div>

        <div class="detail-card-body">

            {{-- Info Peminjaman & Peminjam (grid 2 kolom di desktop, 1 kolom di HP) --}}
            <div class="info-grid">

                {{-- Info Peminjaman --}}
                <div class="info-section">
                    <div class="section-title">
                        <i class="bx bx-info-circle"></i> Informasi Peminjaman
                    </div>
                    <table class="info-table">
                        <tr>
                            <td class="info-label">Kode Peminjaman</td>
                            <td class="info-sep">:</td>
                            <td class="info-value">{{ $peminjaman->kode_peminjaman }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Status</td>
                            <td class="info-sep">:</td>
                            <td class="info-value">
                                <span class="status-badge {{ $statusClass }}" style="font-size:11px; padding:3px 10px;">
                                    {{ $peminjaman->status }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>

                {{-- Info Peminjam --}}
                <div class="info-section">
                    <div class="section-title">
                        <i class="bx bx-user"></i> Informasi Peminjam
                    </div>
                    <table class="info-table">
                        <tr>
                            <td class="info-label">Nama</td>
                            <td class="info-sep">:</td>
                            <td class="info-value">{{ $peminjaman->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Email</td>
                            <td class="info-sep">:</td>
                            <td class="info-value" style="word-break:break-all;">{{ $peminjaman->user->email ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Daftar Buku --}}
            <div class="books-section">
                <div class="section-title">
                    <i class="bx bx-book"></i>
                    Daftar Buku
                    <span style="background:#667eea;color:#fff;font-size:11px;font-weight:700;padding:2px 9px;border-radius:10px;margin-left:4px;">
                        {{ $peminjaman->details->count() }}
                    </span>
                </div>

                {{-- Desktop Table --}}
                <div class="books-table-desktop">
                    <div class="table-wrapper">
                        <table class="books-table">
                            <thead>
                                <tr>
                                    <th style="width:40px;">No</th>
                                    <th>Judul Buku</th>
                                    <th>Penulis</th>
                                    <th>Penerbit</th>
                                    <th style="text-align:center; width:80px;">Jml</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjaman->details as $index => $detail)
                                <tr>
                                    <td style="color:#aaa; font-weight:600;">{{ $index + 1 }}</td>
                                    <td style="font-weight:600;">{{ optional($detail->buku)->judul ?? '-' }}</td>
                                    <td>{{ optional($detail->buku)->penulis ?? '-' }}</td>
                                    <td>{{ optional($detail->buku)->penerbit ?? '-' }}</td>
                                    <td style="text-align:center;">
                                        <span class="qty-chip">{{ $detail->jumlah }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" style="text-align:right;">Total Jumlah :</td>
                                    <td style="text-align:center;">
                                        <span class="qty-chip">{{ $peminjaman->jumlah }}</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Mobile Card per Buku --}}
                <div class="books-cards-mobile">
                    @foreach($peminjaman->details as $index => $detail)
                    <div class="book-card-mobile">
                        <span class="book-num">#{{ $index + 1 }}</span>
                        <div class="book-card-mobile-title">{{ optional($detail->buku)->judul ?? '-' }}</div>
                        <div class="book-card-mobile-row">
                            <span>Penulis</span>
                            <span>{{ optional($detail->buku)->penulis ?? '-' }}</span>
                        </div>
                        <div class="book-card-mobile-row">
                            <span>Penerbit</span>
                            <span>{{ optional($detail->buku)->penerbit ?? '-' }}</span>
                        </div>
                        <div class="book-card-mobile-row">
                            <span>Jumlah</span>
                            <span><span class="qty-chip">{{ $detail->jumlah }}</span></span>
                        </div>
                    </div>
                    @endforeach
                    <div style="text-align:right; margin-top:10px; font-size:13px; font-weight:700; color:#1e2459;">
                        Total: <span class="qty-chip">{{ $peminjaman->jumlah }}</span>
                    </div>
                </div>
            </div>

            {{-- Tanggal --}}
            <div class="section-title">
                <i class="bx bx-calendar"></i> Informasi Tanggal
            </div>
            <div class="date-grid">
                <div class="date-card">
                    <div class="date-card-label">
                        <i class="bx bx-calendar-plus"></i> Tanggal Pinjam
                    </div>
                    <div class="date-card-value">
                        {{ $peminjaman->formatted_tanggal_pinjam ?? $peminjaman->tgl_pinjam ?? '-' }}
                    </div>
                </div>
                <div class="date-card">
                    <div class="date-card-label">
                        <i class="bx bx-calendar-x"></i> Tenggat Kembali
                    </div>
                    <div class="date-card-value">
                        {{ $peminjaman->formatted_tanggal_kembali ?? $peminjaman->tenggat ?? '-' }}
                    </div>
                </div>
                @if($peminjaman->tgl_kembali)
                <div class="date-card">
                    <div class="date-card-label">
                        <i class="bx bx-calendar-check"></i> Tanggal Kembali
                    </div>
                    <div class="date-card-value">
                        {{ $peminjaman->formatted_tgl_kembali ?? $peminjaman->tgl_kembali }}
                    </div>
                </div>
                @endif
            </div>

            {{-- Alasan Tolak --}}
            @if($peminjaman->status == 'Ditolak' && $peminjaman->alasan_tolak)
            <div class="reject-alert">
                <h6><i class="bx bx-error-circle"></i> Alasan Penolakan</h6>
                <p>{{ $peminjaman->alasan_tolak }}</p>
            </div>
            @endif

            {{-- Tombol Kembali --}}
            <div class="detail-footer">
                <a href="{{ url()->previous() }}" class="btn-back">
                    <i class="bx bx-arrow-back"></i> Kembali
                </a>
            </div>

        </div>
    </div>
</div>

@endsection