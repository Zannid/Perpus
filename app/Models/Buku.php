<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'bukus';

    protected $fillable = [
        'kode_buku', 'judul', 'penulis', 'penerbit_id', 'tahun_terbit',
        'id_kategori', 'foto', 'id_lokasi', 'stok', 'deskripsi',
    ];

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    // Relasi ke Lokasi/Rak
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi');
    }

    // WAJIB ADA: Relasi ke Penerbit (Karena dipanggil di Controller)
    // Relasi ke Rating
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'buku_id');
    }

    // Relasi ke Detail Peminjaman
    public function details()
    {
        return $this->hasMany(DetailPeminjaman::class, 'buku_id');
    }

    protected $appends = ['foto_url'];

    public function getFotoUrlAttribute()
    {
        // Pastikan sudah jalan: php artisan storage:link
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }
}
