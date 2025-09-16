<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;;
use Carbon\Carbon;
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

    public function getDendaTerhitungAttribute()
    {
        $today = Carbon::now();

        // Hitung keterlambatan
        $daysLate = $today->greaterThan($this->tenggat)
            ? $today->diffInDays($this->tenggat)
            : 0;

        $denda = 0;

        // Gunakan kondisi jika sudah ada, kalau belum -> asumsikan Bagus
        $kondisi = $this->kondisi ?? 'Bagus';

        if ($kondisi == "Bagus") {
            if ($daysLate > 0) {
                $denda = 10000 + ($daysLate * 2000);
            }
        } elseif ($kondisi == "Rusak") {
            $denda = 20000;
            if ($daysLate > 0) {
                $denda += ($daysLate * 2000);
            }
        } elseif ($kondisi == "Hilang") {
            $denda = 50000;
        }

        return $denda * $this->jumlah;
    }

}
