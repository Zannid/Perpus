@extends('layouts.backend')
@section('title', isset($bm) ? 'E-Perpus - Edit Barang Masuk' : 'E-Perpus - Tambah Barang Masuk')
@section('content')

<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header text-white p-3">
            <h4 class="mb-0">
                <i class="bx {{ isset($bm) ? 'bx-edit' : 'bx-plus-circle' }} me-2"></i>
                {{ isset($bm) ? 'Edit Barang Masuk' : 'Tambah Barang Masuk' }}
            </h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ isset($bm) ? route('barangmasuk.update', $bm->id) : route('barangmasuk.store') }}" method="POST" id="barangMasukForm">
                @csrf
                @if(isset($bm))
                    @method('PUT')
                @endif

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="tgl_masuk" class="form-label fw-bold">Tanggal Masuk <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_masuk" id="tgl_masuk" class="form-control"
                               value="{{ old('tgl_masuk', isset($bm) ? \Carbon\Carbon::parse($bm->tgl_masuk)->format('Y-m-d') : date('Y-m-d')) }}" required>
                    </div>
                </div>

                <hr>

                <!-- Bagian Pilih Buku (Multiple) -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold"><i class="bx bx-book-open me-2"></i>Daftar Buku Masuk</h5>
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#addBukuModal">
                        <i class="bx bx-plus me-1"></i>Tambah Buku
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="selectedBukuTable">
                        <thead class="table-light">
                            <tr>
                                <th>Buku</th>
                                <th width="150">Jumlah</th>
                                <th width="100" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bukuListBody">
                            @if(isset($bm))
                                @foreach($bm->details as $detail)
                                    <tr data-id="{{ $detail->buku_id }}">
                                        <td>
                                            <input type="hidden" name="id_buku[]" value="{{ $detail->buku_id }}">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/buku/' . $detail->buku->foto) }}" class="rounded me-3" style="width: 40px; height: 55px; object-fit: cover;">
                                                <div>
                                                    <span class="fw-bold">{{ $detail->buku->judul }}</span><br>
                                                    <small class="text-muted">Stok: {{ $detail->buku->stok }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" name="jumlah[]" class="form-control" value="{{ $detail->jumlah }}" min="1">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-buku-row">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div id="emptyBukuMsg" class="text-center py-4 bg-light rounded {{ isset($bm) && $bm->details->count() > 0 ? 'd-none' : '' }}">
                        <p class="mb-0 text-muted italic">Belum ada buku yang dipilih.</p>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="ket" class="form-label">Keterangan</label>
                    <textarea name="ket" id="ket" class="form-control">{{ old('ket', isset($bm) ? $bm->ket : '') }}</textarea>
                </div>

                <div class="d-flex justify-content-end mt-4 gap-2">
                    <a href="{{ route('barangmasuk.index') }}" class="btn btn-label-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 shadow">
                        <i class="bx bx-save me-1"></i>{{ isset($bm) ? 'Update Barang Masuk' : 'Simpan Barang Masuk' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Buku -->
<div class="modal fade" id="addBukuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" id="modalSearchBuku" class="form-control" placeholder="Cari judul buku atau penulis...">
                </div>
                <div class="row g-3" style="max-height: 400px; overflow-y: auto;" id="modalBukuList">
                    @foreach($buku as $b)
                        <div class="col-md-6 buku-card-item" data-judul="{{ strtolower($b->judul . ' ' . $b->penulis) }}">
                            <div class="card h-100 border border-light shadow-none hover-shadow">
                                <div class="card-body p-2 d-flex gap-3 align-items-center">
                                    <img src="{{ asset('storage/buku/' . $b->foto) }}" class="rounded shadow-sm" style="width: 50px; height: 75px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold fs-7">{{ $b->judul }}</h6>
                                        <p class="mb-1 small text-muted">Stok: <span class="badge {{ $b->stok > 0 ? 'bg-label-success' : 'bg-label-danger' }}">{{ $b->stok }}</span></p>
                                        <button type="button" class="btn btn-xs btn-primary select-buku-btn w-100" 
                                            data-id="{{ $b->id }}" 
                                            data-judul="{{ $b->judul }}" 
                                            data-foto="{{ asset('storage/buku/' . $b->foto) }}"
                                            data-stok="{{ $b->stok }}"
                                            {{ $b->stok <= 0 ? 'disabled' : '' }}>
                                            {{ $b->stok > 0 ? 'Pilih' : 'Stok Habis' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    const bukuListBody = document.getElementById('bukuListBody');
    const emptyBukuMsg = document.getElementById('emptyBukuMsg');

    // Tambah buku dari modal
    document.querySelectorAll('.select-buku-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const judul = this.dataset.judul;
            const foto = this.dataset.foto;
            const stok = this.dataset.stok;

            if (document.querySelector(`tr[data-id="${id}"]`)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sudah Ada',
                    text: 'Buku ini sudah ada dalam daftar.',
                    timer: 1500,
                    showConfirmButton: false
                });
                return;
            }

            const row = `
                <tr data-id="${id}">
                    <td>
                        <input type="hidden" name="id_buku[]" value="${id}">
                        <div class="d-flex align-items-center">
                            <img src="${foto}" class="rounded me-3" style="width: 40px; height: 55px; object-fit: cover;">
                            <div>
                                <span class="fw-bold">${judul}</span><br>
                                <small class="text-muted">Stok: ${stok}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <input type="number" name="jumlah[]" class="form-control" value="1" min="1">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger remove-buku-row"><i class="bx bx-trash"></i></button>
                    </td>
                </tr>
            `;
            bukuListBody.insertAdjacentHTML('beforeend', row);
            emptyBukuMsg.classList.add('d-none');
            bootstrap.Modal.getInstance(document.getElementById('addBukuModal')).hide();
        });
    });

    // Hapus baris buku
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-buku-row')) {
            e.target.closest('tr').remove();
            if (bukuListBody.children.length === 0) emptyBukuMsg.classList.remove('d-none');
        }
    });

    // Search modal
    const modalSearchInput = document.getElementById('modalSearchBuku');
    const modalBukuCards = document.querySelectorAll('.buku-card-item');
    modalSearchInput.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        modalBukuCards.forEach(card => {
            card.style.display = card.dataset.judul.includes(filter) ? 'block' : 'none';
        });
    });

    // Validasi form
    document.getElementById('barangMasukForm').addEventListener('submit', function(e) {
        if (bukuListBody.children.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Ops!',
                text: 'Silakan pilih setidaknya satu buku.'
            });
        }
    });
</script>


<style>
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
        background-color: #f8f9fa !important;
    }
    .fs-7 { font-size: 0.9rem; }
    .btn-xs { padding: 0.2rem 0.4rem; font-size: 0.75rem; }
</style>
@endsection
