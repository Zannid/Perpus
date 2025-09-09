<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $fillable = [
        'kode_masuk',
        'jumlah',
        'tgl_masuk',
        'ket',
        'id_buku'
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }

}
