<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'user_id',
        'buku_id',
        'peminjaman_id',
        'rating',
        'review',
    ];

     public function user()
    {
        return $this->belongsTo(User::class); // pastikan ini ada
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class); // jika perlu
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    } 
}
