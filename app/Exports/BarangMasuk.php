<?php
namespace App\Exports;

use App\Models\BarangMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BarangMasukExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return BarangMasuk::with('buku')->get()->map(function ($item) {
            return [
                'Kode Masuk'    => $item->kode_masuk,
                'Judul Buku'    => $item->buku->judul,
                'Jumlah'        => $item->jumlah,
                'Tanggal Masuk' => $item->tgl_masuk,
                'Keterangan'    => $item->ket,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Kode Masuk',
            'Judul Buku',
            'Jumlah',
            'Tanggal Masuk',
            'Keterangan',
        ];
    }
}
