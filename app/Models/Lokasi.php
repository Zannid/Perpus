<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $fillable = [
    'kode_rak',
    'id_kategori',
    'keterangan'
];

public function kategori()
{
    return $this->belongsTo(Kategori::class, 'id_kategori');
}
}
