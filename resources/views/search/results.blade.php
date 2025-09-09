@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Hasil pencarian untuk: <strong>{{ $query }}</strong></h4>

    <div class="mt-4">
        <h5>Buku</h5>
        @if($buku->count())
            <ul>
                @foreach($buku as $item)
                    <li>{{ $item->judul }} ({{ $item->penulis }})</li>
                @endforeach
            </ul>
        @else
            <p><em>Tidak ada buku ditemukan.</em></p>
        @endif
    </div>

    <div class="mt-4">
        <h5>Kategori</h5>
        @if($kategori->count())
            <ul>
                @foreach($kategori as $item)
                    <li>{{ $item->nama }}</li>
                @endforeach
            </ul>
        @else
            <p><em>Tidak ada kategori ditemukan.</em></p>
        @endif
    </div>

    <div class="mt-4">
        <h5>Lokasi</h5>
        @if($lokasi->count())
            <ul>
                @foreach($lokasi as $item)
                    <li>{{ $item->nama }}</li>
                @endforeach
            </ul>
        @else
            <p><em>Tidak ada lokasi ditemukan.</em></p>
        @endif
    </div>
</div>
@endsection
