<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $fillable = [
        'kode_keluar',
        'jumlah',
        'tgl_keluar',
        'ket',
        'id_buku'
    ];

    public function buku(){
        return $this->belongsTo(Buku::class, 'id_buku');
    }
}
