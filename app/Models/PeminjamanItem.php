<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanItem extends Model
{
    protected $fillable = ['peminjaman_id', 'id_buku', 'jumlah'];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }
}

