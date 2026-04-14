@extends('layouts.backend')
@section('title', 'E-Perpus - Perpanjangan')

@section('content')

<div class="container-xxl container-p-y">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-4">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a>
            </li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Perpanjangan</li>
        </ol>
    </nav>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-xl bg-label-primary rounded">
                    <i class="bx bx-time fs-2 text-primary"></i>
                </div>
                <div>
                    <h4 class="mb-1">Perpanjangan Peminjaman</h4>
                    <p class="mb-0 text-muted">Lihat status perpanjangan peminjaman buku Anda</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Perpanjangan --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bx bx-history me-2"></i>Riwayat Perpanjangan
            </h5>
        </div>
        <div class="card-body">
            @if($riwayatPerpanjangan->count() > 0)
                <div class="row g-3">
                    @foreach($riwayatPerpanjangan as $perpanjangan)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border">
                                <div class="card-body">
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <img src="{{ asset('storage/buku/' . ($perpanjangan->peminjaman->details->first()->buku->foto ?? 'default-book.png')) }}"
                                             alt="Buku" class="rounded" width="50" height="70" style="object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 text-truncate" style="font-size: 0.9rem;">
                                                {{ $perpanjangan->peminjaman->details->first()->buku->judul ?? 'Buku' }}
                                            </h6>
                                            <small class="text-muted">
                                                Kode: {{ $perpanjangan->peminjaman->kode_peminjaman }}
                                            </small>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Durasi</small>
                                            <span class="fw-semibold">{{ $perpanjangan->durasi }} hari</span>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Status</small>
                                            <span class="badge
                                                @if(in_array($perpanjangan->status, ['approved', 'Disetujui'])) bg-success
                                                @elseif(in_array($perpanjangan->status, ['rejected', 'Ditolak'])) bg-danger
                                                @else bg-warning text-dark @endif">
                                                @if(in_array($perpanjangan->status, ['approved', 'Disetujui'])) Disetujui
                                                @elseif(in_array($perpanjangan->status, ['rejected', 'Ditolak'])) Ditolak
                                                @else Menunggu @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <small class="text-muted d-block">Alasan</small>
                                        <p class="mb-0 small">{{ Str::limit($perpanjangan->alasan, 80) }}</p>
                                    </div>

                                    <small class="text-muted">
                                        <i class="bx bx-calendar me-1"></i>
                                        Diajukan: {{ $perpanjangan->created_at->format('d M Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bx bx-time fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada riwayat perpanjangan</h5>
                    <p class="text-muted mb-0">Anda belum pernah mengajukan perpanjangan peminjaman.</p>
                </div>
            @endif
        </div>
    </div>

</div>

@endsection