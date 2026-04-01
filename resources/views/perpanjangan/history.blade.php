@extends('layouts.backend')

@section('title', 'Riwayat Perpanjangan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-time me-2 text-primary"></i>
                        Riwayat Perpanjangan - {{ $peminjaman->details->first()->buku->judul ?? 'Buku' }}
                    </h5>
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bx bx-arrow-back me-1"></i> Kembali ke Peminjaman
                    </a>
                </div>
                <div class="card-body">
                    @if($riwayat->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Durasi</th>
                                        <th>Alasan</th>
                                        <th>Status</th>
                                        <th>Tanggal Diputuskan</th>
                                        <th>Catatan Petugas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riwayat as $item)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $item->durasi }} hari</td>
                                        <td>{{ $item->alasan }}</td>
                                        <td>
                                            @if($item->status == 'pending')
                                                <span class="badge bg-warning">Menunggu</span>
                                            @elseif($item->status == 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($item->status == 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->tanggal_keputusan)
                                                {{ \Carbon\Carbon::parse($item->tanggal_keputusan)->format('d/m/Y H:i') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $item->catatan_petugas ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bx bx-time display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada riwayat perpanjangan</h5>
                            <p class="text-muted">Anda belum pernah mengajukan perpanjangan untuk peminjaman ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
