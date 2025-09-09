@component('mail::message')
# Pengingat Pengembalian

Halo ** {{ $peminjaman->nama_peminjam}} ** ,

Ini pengingatbahwabuku ** {{ $peminjaman->judul_buku}} ** harus dikembalikanpadatanggal ** {{ $peminjaman->tanggal_kembali->format('d-m-Y');}} **  .

Silakan kembalikanatauhubungiperpustakaanjikaadakendala .

Terima kasih,  < br >
{{config('app.name');}}
@endcomponent;
