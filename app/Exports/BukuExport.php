<?php

namespace App\Exports;

use App\Models\Buku;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BukuExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Buku::with(['kategori', 'lokasi'])->get()->map(function ($buku) {
            return [
                'Kode Buku' => $buku->kode_buku,
                'Judul' => $buku->judul,
                'Kategori' => $buku->kategori ? $buku->kategori->nama_kategori : '-',
                'Lokasi' => $buku->lokasi ? $buku->lokasi->kode_rak . ' - ' . $buku->lokasi->keterangan : '-',
                'Stok' => $buku->stok,
            ];
        });
    }

    public function headings(): array
    {
        return ['Kode Buku', 'Judul', 'Kategori', 'Lokasi', 'Stok'];
    }
}
