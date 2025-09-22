@extends('layouts.backend')
@section('content')
<div class="col-md-12">
    <nav>
        <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-3">
            <li class="breadcrumb-item">
                <a href="{{ route('peminjaman.index') }}" class="text-decoration-none text-primary fw-semibold">
                    Peminjaman
                </a>
            </li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
                Data Peminjaman
            </li>
        </ol>
    </nav>

    <div class="card shadow-lg">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Peminjaman</h5>
            <div class="d-flex align-items-center gap-2">

                {{-- Form Filter & Search --}}
                <form action="{{ route('peminjaman.index') }}" method="get" class="d-flex gap-2">

                    {{-- Filter Tanggal --}}
                    <input type="date" name="tanggal_awal" class="form-control form-control-sm" 
                        value="{{ request('tanggal_awal') }}" placeholder="Tanggal Awal">
                    <input type="date" name="tanggal_akhir" class="form-control form-control-sm" 
                        value="{{ request('tanggal_akhir') }}" placeholder="Tanggal Akhir">

                    {{-- Search --}}
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Cari peminjaman..."
                            value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bx bx-search-alt"></i>
                        </button>
                    </div>

                    {{-- Reset --}}
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                </form>

                {{-- Tambah & Export --}}
                <a href="{{ route('peminjaman.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="bx bx-plus me-1"></i> Tambah Peminjaman
                </a>
                <a href="{{ route('peminjaman.export', request()->query()) }}" 
                target="_blank" 
                class="btn btn-danger btn-sm rounded-pill px-3">
                <i class="bx bx-file"></i> Buat PDF
                </a>

            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="display table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peminjam</th>
                            <th>Buku</th>
                            <th>Jumlah</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tenggat</th>
                            <th>Status</th>
                            <th>Denda</th>
                            <th class="text-center">Aksi</th>
                            <th class="text-center">Kembali</th>
                        </tr>
                    </thead>
                    <tbody>
                      
                        @php $no = ($peminjaman->currentPage() - 1) * $peminjaman->perPage() + 1;
                        use Illuminate\Support\Str;
                        @endphp
                        @foreach($peminjaman as $data)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $data->user->name ?? '-' }}</td>
                            <td>{{ Str::limit($data->buku->judul ?? '-', 15) }}</td>
                            <td class="text-center">{{ $data->jumlah }}</td>
                            <td>{{ $data->formatted_tanggal_pinjam ?? $data->tgl_pinjam }}</td>
                            <td>{{ $data->formatted_tanggal_kembali ?? $data->tenggat }}</td>
                            <td class="text-center">
                                @switch($data->status)
                                    @case('Kembali')
                                        <span class="badge bg-success">{{ $data->status }}</span>
                                        @break
                                    @case('Dipinjam')
                                        <span class="badge bg-warning text-dark">{{ $data->status }}</span>
                                        @break
                                    @case('Lunas')
                                        <span class="badge bg-info">{{ $data->status }}</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $data->status }}</span>
                                @endswitch
                            </td>
                           <td class="text-center">
                                @if($data->denda_berjalan > 0)
                                    <span class="text-danger">
                                        Rp {{ number_format($data->denda_berjalan, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span>-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">

                                    {{-- Edit --}}
                                    <a href="{{ route('peminjaman.edit', $data->id) }}" 
                                    class="btn btn-sm btn-warning d-flex align-items-center justify-content-center" 
                                    title="Edit">
                                        <i class="bx bx-pencil"></i>
                                    </a>

                                    {{-- Bayar Denda --}}
                                    @if($data->denda > 0 && $data->status != 'Lunas')
                                    <form action="{{ route('peminjaman.confirmPay', $data->id) }}" 
                                        method="POST" 
                                        class="d-inline m-0"
                                        onsubmit="return confirm('Bayar denda ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center justify-content-center">
                                            <i class="bx bx-credit-card"></i>
                                        </button>
                                    </form>
                                    @endif


                                    {{-- Hapus --}}
                                    <form action="{{ route('peminjaman.destroy', $data->id) }}" 
                                        method="post" 
                                        class="d-inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger d-flex align-items-center justify-content-center">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                                <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap">
                                    @if($data->status == 'Dipinjam')
                                    <form action="{{ route('peminjaman.return', $data->id) }}" method="post" class="d-flex gap-1">
                                        @csrf
                                        <select name="kondisi" class="form-select form-select-sm w-auto" required>
                                            <option value="">Kondisi</option>
                                            <option value="Bagus">Bagus</option>
                                            <option value="Rusak">Rusak</option>
                                            <option value="Hilang">Hilang</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Kembalikan buku ini?')">
                                            <i class="bx bx-undo"></i>
                                        </button>
                                    </form>
                                    @else
                                    <span>-</span>
                                    @endif
                                </div>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $peminjaman->appends(request()->all())->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
