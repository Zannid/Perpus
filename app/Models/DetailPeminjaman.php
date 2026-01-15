<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjaman'; // sesuaikan dengan nama tabel Anda

    protected $fillable = [
        'peminjaman_id',
        'buku_id',
        'jumlah',
    ];

    public $timestamps = true;

    /**
     * Relasi ke Peminjaman
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    /**
     * Relasi ke Buku
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}
