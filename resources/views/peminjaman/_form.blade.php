@extends('layouts.backend')
@section('title', isset($peminjaman) ? 'E-Perpus - Edit Peminjaman' : 'E-Perpus - Tambah Peminjaman')
@section('content')

<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white p-3">
            <h4 class="mb-0 text-white">
                <i class="bx {{ isset($peminjaman) ? 'bx-edit' : 'bx-plus-circle' }} me-2"></i>
                {{ isset($peminjaman) ? 'Edit Data Peminjaman' : 'Tambah Data Peminjaman' }}
            </h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ isset($peminjaman) ? route('peminjaman.update', $peminjaman->id) : route('peminjaman.store') }}" method="POST" id="peminjamanForm">
                @csrf
                @if(isset($peminjaman))
                    @method('PUT')
                @endif

                <div class="row mb-4">
                    <!-- Pilih Peminjam -->
                    <div class="col-md-6">
                        <label for="id_user" class="form-label fw-bold">Peminjam <span class="text-danger">*</span></label>
                        <select name="id_user" id="id_user" class="form-select select2-user @error('id_user') is-invalid @enderror" required>
                            <option value="">-- Cari Peminjam --</option>
                            @foreach($user as $u)
                                <option value="{{ $u->id }}" {{ (old('id_user', $peminjaman->id_user ?? '') == $u->id) ? 'selected' : '' }}>
                                    {{ $u->name }} ({{ $u->kode_user }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_user') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Status Peminjaman -->
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="Pending" {{ (old('status', $peminjaman->status ?? '') == 'Pending') ? 'selected' : '' }}>Pending</option>
                            <option value="Dipinjam" {{ (old('status', $peminjaman->status ?? '') == 'Dipinjam') ? 'selected' : '' }}>Dipinjam</option>
                            @if(isset($peminjaman))
                                <option value="Kembali" {{ (old('status', $peminjaman->status ?? '') == 'Kembali') ? 'selected' : '' }}>Kembali</option>
                                <option value="Ditolak" {{ (old('status', $peminjaman->status ?? '') == 'Ditolak') ? 'selected' : '' }}>Ditolak</option>
                            @endif
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="tgl_pinjam" class="form-label fw-bold">Tanggal Pinjam <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control"
                            value="{{ old('tgl_pinjam', isset($peminjaman) ? \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('Y-m-d') : date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tenggat" class="form-label fw-bold">Tenggat Pengembalian <span class="text-danger">*</span></label>
                        <input type="date" name="tenggat" id="tenggat" class="form-control"
                            value="{{ old('tenggat', isset($peminjaman) ? \Carbon\Carbon::parse($peminjaman->tenggat)->format('Y-m-d') : date('Y-m-d', strtotime('+7 days'))) }}" required>
                        <!-- <small class="text-muted text-italic">* Secara default 7 hari, namun dapat diubah secara manual.</small> -->
                    </div>
                </div>

                <hr>

                <!-- Bagian Pilih Buku (Multiple) -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold"><i class="bx bx-book-open me-2"></i>Daftar Buku</h5>
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#addBukuModal">
                        <i class="bx bx-plus me-1"></i>Tambah Buku ke Daftar
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
                            @if(isset($peminjaman))
                                @foreach($peminjaman->details as $index => $detail)
                                    <tr data-id="{{ $detail->buku_id }}">
                                        <td>
                                            <input type="hidden" name="id_buku[]" value="{{ $detail->buku_id }}">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $detail->buku->foto ? asset('storage/buku/' . $detail->buku->foto) : asset('storage/buku/default-book.png') }}" class="rounded me-3" style="width: 40px; height: 55px; object-fit: cover;">
                                                <div>
                                                    <span class="fw-bold">{{ $detail->buku->judul }}</span><br>
                                                    <small class="text-muted">Stok: {{ $detail->buku->stok }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" name="jumlah[]" class="form-control" value="{{ $detail->jumlah }}" min="1" max="{{ $detail->buku->stok + $detail->jumlah }}">
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
                    <div id="emptyBukuMsg" class="text-center py-4 bg-light rounded {{ isset($peminjaman) && $peminjaman->details->count() > 0 ? 'd-none' : '' }}">
                        <p class="mb-0 text-muted italic">Belum ada buku yang dipilih.</p>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-label-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 shadow">
                        <i class="bx bx-save me-1"></i>{{ isset($peminjaman) ? 'Update Peminjaman' : 'Simpan Peminjaman' }}
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
                                    <img src="{{ $b->foto ? asset('storage/buku/' . $b->foto) : asset('storage/buku/default-book.png') }}" class="rounded shadow-sm" style="width: 50px; height: 75px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold fs-7">{{ $b->judul }}</h6>
                                        <p class="mb-1 small text-muted">Stok: <span class="badge {{ $b->stok > 0 ? 'bg-label-success' : 'bg-label-danger' }}">{{ $b->stok }}</span></p>
                                        <button type="button" class="btn btn-xs btn-primary select-buku-btn w-100"
                                            data-id="{{ $b->id }}"
                                            data-judul="{{ $b->judul }}"
                                            data-foto="{{ $b->foto ? asset('storage/buku/' . $b->foto) : asset('storage/buku/default-book.png') }}"
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
    const tglPinjamInput = document.getElementById('tgl_pinjam');
    const tenggatInput = document.getElementById('tenggat');

    // Update tenggat otomatis
    tglPinjamInput.addEventListener('change', function() {
        let tglPinjam = new Date(this.value);
        if (!isNaN(tglPinjam.getTime())) {
            tglPinjam.setDate(tglPinjam.getDate() + 7);
            let year = tglPinjam.getFullYear();
            let month = String(tglPinjam.getMonth() + 1).padStart(2, '0');
            let day = String(tglPinjam.getDate()).padStart(2, '0');
            tenggatInput.value = `${year}-${month}-${day}`;
        }
    });

    // Tambah buku dari modal
    document.querySelectorAll('.select-buku-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const judul = this.dataset.judul;
            const foto = this.dataset.foto;
            const stok = this.dataset.stok;

            // Cek apakah buku sudah ada di list
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
                        <input type="number" name="jumlah[]" class="form-control" value="1" min="1" max="${stok}">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger remove-buku-row">
                            <i class="bx bx-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            bukuListBody.insertAdjacentHTML('beforeend', row);
            emptyBukuMsg.classList.add('d-none');

            // Tutup modal
            bootstrap.Modal.getInstance(document.getElementById('addBukuModal')).hide();
        });
    });

    // Hapus baris buku
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-buku-row')) {
            e.target.closest('tr').remove();
            if (bukuListBody.children.length === 0) {
                emptyBukuMsg.classList.remove('d-none');
            }
        }
    });

    // Search di modal
    const modalSearchInput = document.getElementById('modalSearchBuku');
    const modalBukuCards = document.querySelectorAll('.buku-card-item');

    modalSearchInput.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        modalBukuCards.forEach(card => {
            const content = card.dataset.judul;
            card.style.display = content.includes(filter) ? 'block' : 'none';
        });
    });

    // Validasi form sebelum kirim
    document.getElementById('peminjamanForm').addEventListener('submit', function(e) {
        if (bukuListBody.children.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Ops!',
                text: 'Silakan pilih setidaknya satu buku untuk dipinjam.'
            });
        }
    });

    // Initialize Select2 untuk pencarian user
    document.addEventListener('DOMContentLoaded', function() {
        const select2Css = document.createElement('link');
        select2Css.rel = 'stylesheet';
        select2Css.href = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css';
        document.head.appendChild(select2Css);

        const select2ThemeCss = document.createElement('link');
        select2ThemeCss.rel = 'stylesheet';
        select2ThemeCss.href = 'https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css';
        document.head.appendChild(select2ThemeCss);

        const select2Script = document.createElement('script');
        select2Script.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
        select2Script.onload = function() {
            // Tunggu jQuery ready
            if (typeof jQuery !== 'undefined') {
                initializeSelect2();
            } else {
                // Jika jQuery belum ready, tunggu beberapa saat
                setTimeout(function() {
                    if (typeof jQuery !== 'undefined') {
                        initializeSelect2();
                    }
                }, 100);
            }
        };
        document.head.appendChild(select2Script);
    });

    function initializeSelect2() {
        const $ = jQuery;
        const $userSelect = $('#id_user');

        if ($userSelect.length === 0) return;

        // Get the current selected value if in edit mode
        const currentValue = $userSelect.val();
        const currentText = $userSelect.find('option:selected').text();

        $userSelect.select2({
            theme: 'bootstrap-5',
            placeholder: '-- Cari Peminjam berdasarkan Nama atau Kode User --',
            ajax: {
                url: '/api/users/search',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term || ''
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                }
            },
            templateResult: formatUserOption,
            minimumInputLength: 0,
            allowClear: true,
            width: '100%',
            escapeMarkup: function (markup) {
                return markup;
            }
        });

        // Set initial value jika ada (untuk edit mode)
        if (currentValue && currentText && currentText !== '-- Pilih Peminjam --') {
            // Create a custom option for the selected value
            const option = new Option(currentText, currentValue, true, true);
            $userSelect.append(option).trigger('change');
        }
    }

    function formatUserOption(user) {
        if (!user.id) return user.text;

        const markup = `
            <div class="d-flex align-items-center gap-2">
                <img src="${user.foto}" alt="${user.name}" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 1px solid #ddd; flex-shrink: 0;" />
                <div>
                    <div style="font-weight: 600; font-size: 0.95rem;">${user.name}</div>
                    <div style="font-size: 0.85rem; color: #6c757d;">${user.kode_user}</div>
                </div>
            </div>
        `;
        return jQuery(markup);
    }

</script>
<style>
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
        background-color: #f8f9fa !important;
    }
    .fs-7 { font-size: 0.9rem; }
    .btn-xs { padding: 0.2rem 0.4rem; font-size: 0.75rem; }

    /* Select2 Custom Styling */
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        padding: 0.375rem 0.75rem;
        height: auto;
        border-radius: 0.25rem;
    }
    .select2-container--bootstrap-5.select2-container--focus .select2-selection {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .user-select2-option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 5px 0;
    }
    .user-select2-option img {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #ddd;
    }
    .user-select2-option .user-info {
        display: flex;
        flex-direction: column;
    }
    .user-select2-option .user-name {
        font-weight: 600;
        font-size: 0.95rem;
    }
    .user-select2-option .user-code {
        font-size: 0.85rem;
        color: #6c757d;
    }
</style>
@endsection
