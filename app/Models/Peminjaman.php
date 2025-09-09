<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{

    protected $fillable = [
        'kode_peminjaman',
        'jumlah',
        'tgl_pinjam',
        'tenggat',
        'id_user',
        'status',
        'id_buku',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user')->withDefault(['name' => '-']);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku')->withDefault(['judul' => '-']);
    }
    public function kembali() {
        return $this->hasOne(Pengembalian::class);
    }


}
