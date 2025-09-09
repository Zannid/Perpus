<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table    = 'pengembalians';
    protected $fillable = [
        'id_peminjaman', 'id_user', 'id_buku',
        'tgl_pinjam', 'tenggat', 'tgl_kembali',
        'jumlah', 'denda',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }
}

