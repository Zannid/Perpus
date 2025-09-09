<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class buku extends Model
{
    protected $fillable = [
    'kode_buku',
    'judul',
    'penulis',
    'penerbit',
    'tahun_terbit',
    'id_kategori',
    'foto',
    'id_lokasi',
    'stok',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi');
    }

}
