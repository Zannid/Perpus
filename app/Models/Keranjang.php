<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'keranjangs';
    protected $fillable = ['user_id', 'buku_id', 'jumlah'];

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}

