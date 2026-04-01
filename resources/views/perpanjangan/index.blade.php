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
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Buku</th>
                        <th>User</th>
                        <th>Durasi</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perpanjangan as $item)
                    <tr>
                        <td class="d-flex align-items-center gap-2">
                            <img src="{{ asset('storage/buku/' . ($item->peminjaman->details->first()->buku->foto ?? 'default-book.png')) }}"
                                 width="45" class="rounded">
                            <span class="fw-semibold">{{ $item->peminjaman->details->first()->buku->judul }}</span>
                        </td>
                        <td>{{ $item->peminjaman->user->name }}</td>
                        <td>{{ $item->durasi }} hari</td>
                        <td class="text-truncate" style="max-width:150px">{{ $item->alasan }}</td>
                        <td>
                            <span class="badge
                                @if($item->status=='approved') bg-success
                                @elseif($item->status=='rejected') bg-danger
                                @else bg-warning @endif">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>
                            @if($item->status=='pending')
                            <form action="/perpanjangan/{{ $item->id }}/approve" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success">✓</button>
                            </form>
                            <form action="/perpanjangan/{{ $item->id }}/reject" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-danger">✗</button>
                            </form>
                            @else
                            <small class="text-muted">Selesai</small>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">

                    <div class="d-flex gap-3">
                        <img src="{{ asset('storage/buku/' . ($peminjaman->details->first()->buku->foto ?? 'default-book.png')) }}"
                             class="rounded" width="60">
                        <div>
                            <h6 class="mb-1">{{ $peminjaman->details->first()->buku->judul }}</h6>
                            <small class="text-muted">
                                Tenggat: {{ \Carbon\Carbon::parse($peminjaman->tenggat)->format('d M Y') }}
                            </small>
                        </div>
                    </div>

                    <div class="mt-3 small text-muted">
                        Sisa perpanjangan: {{ 3 - $peminjaman->jumlah_perpanjangan }}x
                    </div>

                    <button class="btn btn-primary btn-sm mt-auto"
                        onclick="openExtensionModal({{ $peminjaman->id }}, '{{ $peminjaman->details->first()->buku->judul }}')">
                        Ajukan
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
