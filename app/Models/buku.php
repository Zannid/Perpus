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
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id');
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'buku_id');
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }


}
