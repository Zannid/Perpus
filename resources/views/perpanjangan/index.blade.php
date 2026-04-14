{{-- ===================== ADMIN VIEW ===================== --}}
@extends('layouts.backend')
@section('title', 'Manajemen Perpanjangan')

@section('content')
<div class="container-xxl container-p-y">

    <div class="card mb-4">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="mb-1">Manajemen Perpanjangan</h4>
                <small class="text-muted">Kelola persetujuan perpanjangan peminjaman buku</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light text-nowrap">
                    <tr>
                        <th>Buku</th>
                        <th>User</th>
                        <th>Durasi</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perpanjangan as $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ asset('storage/buku/' . ($item->peminjaman->details->first()->buku->foto ?? 'default-book.png')) }}"
                                    width="45" class="rounded">
                                <span class="fw-semibold text-wrap">
                                    {{ $item->peminjaman->details->first()->buku->judul }}
                                </span>
                            </div>
                        </td>

                        <td class="text-nowrap">
                            {{ $item->peminjaman->user->name }}
                        </td>

                        <td class="text-nowrap">
                            {{ $item->durasi }} hari
                        </td>

                        <td style="max-width:200px" class="text-wrap">
                            {{ $item->alasan }}
                        </td>

                        <td>
                            <span class="badge
                                @if(in_array($item->status, ['approved', 'Disetujui'])) bg-success
                                @elseif(in_array($item->status, ['rejected', 'Ditolak'])) bg-danger
                                @else bg-warning text-dark @endif">
                                @if(in_array($item->status, ['approved', 'Disetujui'])) Disetujui
                                @elseif(in_array($item->status, ['rejected', 'Ditolak'])) Ditolak
                                @else Menunggu @endif
                            </span>
                        </td>

                        <td class="text-center">
                            @if(in_array($item->status, ['pending','Pending']))
                            <div class="d-flex justify-content-center gap-1">
                                <form action="/perpanjangan/{{ $item->id }}/approve" method="POST">
                                    @csrf
                                    <button class="btn btn-success btn-sm">
                                        <i class="bx bx-check"></i>
                                    </button>
                                </form>
                                <form action="/perpanjangan/{{ $item->id }}/reject" method="POST">
                                    @csrf
                                    <button class="btn btn-danger btn-sm">
                                        <i class="bx bx-x"></i>
                                    </button>
                                </form>
                            </div>
                            @else
                            <small class="text-muted">Selesai</small>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Tidak ada data
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="card-footer">
            {{ $perpanjangan->links() }}
        </div>
    </div>

</div>
@endsection


{{-- ===================== USER VIEW ===================== --}}
@extends('layouts.backend')
@section('title', 'Perpanjangan Saya')

@section('content')
<div class="container-xxl container-p-y">

    <div class="row g-4">

        @forelse($peminjamanAktif as $peminjaman)
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">

                    <div class="d-flex gap-3">
                        <img src="{{ asset('storage/buku/' . ($peminjaman->details->first()->buku->foto ?? 'default-book.png')) }}"
                            class="rounded" width="60">
                        <div>
                            <h6 class="mb-1 text-wrap">
                                {{ $peminjaman->details->first()->buku->judul }}
                            </h6>
                            <small class="text-muted">
                                Tenggat: {{ \Carbon\Carbon::parse($peminjaman->tenggat)->format('d M Y') }}
                            </small>
                        </div>
                    </div>

                    <div class="mt-3 small text-muted">
                        Sisa perpanjangan:
                        <strong>{{ 3 - $peminjaman->jumlah_perpanjangan }}x</strong>
                    </div>

                    <button class="btn btn-primary btn-sm mt-auto w-100"
                        onclick="openExtensionModal({{ $peminjaman->id }}, '{{ $peminjaman->details->first()->buku->judul }}')">
                        Ajukan Perpanjangan
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted">
            Tidak ada buku
        </div>
        @endforelse

    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $peminjamanAktif->links() }}
    </div>

</div>
@endsection


{{-- ===================== IMPROVEMENTS ===================== --}}
<style>
.card {
    border-radius: 12px;
}

@media (max-width: 576px) {
    .table th, .table td {
        font-size: 12px;
    }
}
</style>

<script>
function openExtensionModal(id, title){
    document.getElementById('bookTitle').value = title;
    document.getElementById('extensionForm').action = `/perpanjangan/${id}`;
    new bootstrap.Modal(document.getElementById('extensionModal')).show();
}
</script>
