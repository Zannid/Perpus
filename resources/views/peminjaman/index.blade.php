@extends('layouts.backend')
@section('content')
<div class="container">
  <div class="row">
    <div class="col">
      <a href="{{ route('peminjaman.create') }}" class="btn btn-primary mb-3">Tambah</a>

<div class="table-responsive mt-3">
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>No</th>
        <th>Kode</th>
        <th>Nama Peminjam</th>
        <th>Buku</th>
        <th>Jumlah</th>
        <th>Tanggal Pinjam</th>
        <th>Tenggat</th>
        <th>Status</th>
        <th>Denda</th>
        <th>Aksi</th>
      </tr>
    </thead>
        <tbody>
          @php $no = 1; @endphp
          @foreach($peminjaman as $data)
          <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $data->kode_peminjaman }}</td>
            <td>{{ $data->user->name ?? '-' }}</td>
            <td>{{ $data->buku->judul ?? '-' }}</td>
            <td>{{ $data->jumlah }}</td>
            <td>{{ $data->formatted_tanggal_pinjam ?? $data->tgl_pinjam }}</td>
            <td>{{ $data->formatted_tanggal_kembali ?? $data->tenggat }}</td>
            <td>
              @if($data->status == 'Sudah Dikembalikan')
                <span class="badge bg-success">{{ $data->status }}</span>
              @elseif($data->status == 'Sedang Dipinjam')
                <span class="badge bg-warning text-dark">{{ $data->status }}</span>
              @elseif($data->status == 'Denda Lunas')
                <span class="badge bg-info">{{ $data->status }}</span>
              @else
                <span class="badge bg-secondary">{{ $data->status }}</span>
              @endif
            </td>
            <td>
              @if($data->denda > 0)
                <span class="text-danger">Rp {{ number_format($data->denda, 0, ',', '.') }}</span>
              @else
                -
              @endif
            </td>
            <td>
              <div class="d-flex flex-wrap gap-2">

                {{-- Tombol Edit --}}
                <a href="{{ route('peminjaman.edit', $data->id) }}" 
                   class="btn btn-sm btn-warning">
                  <i class="mdi mdi-pencil"></i> Edit
                </a>

                {{-- Tombol Kembalikan + pilih kondisi --}}
                @if($data->status == 'Sedang Dipinjam')
                  <form action="{{ route('peminjaman.return', $data->id) }}" 
                        method="post" class="d-flex gap-2">
                    @csrf
                    <select name="kondisi" class="form-select form-select-sm w-auto" required>
                      <option value="">Kondisi</option>
                      <option value="Bagus">Bagus</option>
                      <option value="Rusak">Rusak</option>
                      <option value="Hilang">Hilang</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary"
                            onclick="return confirm('Kembalikan buku ini?')">
                      <i class="lni lni-undo"></i> Kembalikan
                    </button>
                  </form>
                @endif

                {{-- Tombol Bayar Denda --}}
                @if($data->status == 'Sudah Dikembalikan' && $data->denda > 0)
                  <a href="{{ route('peminjaman.pay', $data->id) }}" 
                    class="btn btn-sm btn-danger"
                    onclick="return confirm('Bayar denda ini?')">
                    <i class="lni lni-credit-cards"></i> Bayar Denda
                  </a>
                @endif


                {{-- Tombol Delete --}}
                <form action="{{ route('peminjaman.destroy', $data->id) }}" 
                      method="post" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger"
                          onclick="return confirm('Apakah anda yakin?')">
                    <i class="lni lni-trash-can"></i> Delete
                  </button>
                </form>

              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
  </table>
</div>


    </div>
  </div>
</div>
@endsection
