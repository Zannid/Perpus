<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
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
        'deskripsi',
        'rating_avg',
        'rating_count'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'buku_id');
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }

    public function details()
    {
        return $this->hasMany(DetailPeminjaman::class, 'buku_id');
    }


}
