<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Peminjaman extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_peminjaman',
        'jumlah_keseluruhan',
        'tgl_pinjam',
        'tenggat',
        'id_user',
        'alasan_tolak',
        'status',
        'kondisi',
        'denda',
        'status_baca',
    ];

    protected $casts = [
        'tgl_pinjam'  => 'date',
        'tenggat'     => 'date',
        'status_baca' => 'boolean',
    ];

    // ========== RELASI UTAMA ==========

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user')->withDefault(['name' => '-']);
    }

    /**
     * Relasi ke Detail Peminjaman (Many-to-Many dengan Buku)
     */
    public function details()
    {
        return $this->hasMany(DetailPeminjaman::class, 'peminjaman_id');
    }

    /**
     * Relasi ke Buku melalui Details (untuk akses langsung)
     * GUNAKAN INI sebagai pengganti relasi buku() langsung
     */
    public function bukus()
    {
        return $this->hasManyThrough(
            Buku::class,
            DetailPeminjaman::class,
            'peminjaman_id', // FK di detail_peminjaman
            'id',            // PK di buku
            'id',            // PK di peminjaman
            'buku_id'        // FK di detail_peminjaman ke buku
        );
    }

    /**
     * Relasi ke satu buku pertama (untuk compatibility with('buku'))
     */
    public function buku()
    {
        return $this->hasOneThrough(
            Buku::class,
            DetailPeminjaman::class,
            'peminjaman_id',
            'id',
            'id',
            'buku_id'
        );
    }

    /**
     * Accessor untuk mendapatkan buku pertama (backward compatibility)
     * Gunakan ini jika kode lama masih pakai $peminjaman->buku
     */
    public function getBukuAttribute()
    {
        return $this->details()->with('buku')->first()?->buku;
    }

    /**
     * Relasi ke Rating
     */
    public function rating()
    {
        return $this->hasOne(Rating::class, 'peminjaman_id');
    }

    /**
     * Relasi ke Pengembalian
     */
    public function kembali()
    {
        return $this->hasOne(Pengembalian::class);
    }

    // ========== RELASI PERPANJANGAN ==========

    public function perpanjangan()
    {
        return $this->hasMany(Perpanjangan::class, 'id_peminjaman');
    }

    public function perpanjanganPending()
    {
        return $this->hasOne(Perpanjangan::class, 'id_peminjaman')
            ->where('status', 'Pending')
            ->latest();
    }

    public function perpanjanganDisetujui()
    {
        return $this->hasMany(Perpanjangan::class, 'id_peminjaman')
            ->where('status', 'Disetujui')
            ->orderBy('created_at', 'desc');
    }

    public function getJumlahAttribute()
    {
        return $this->details->sum('jumlah') ?: $this->jumlah_keseluruhan;
    }

    // ========== ACCESSOR ==========

    public function getDendaTerhitungAttribute()
    {
        $today    = Carbon::now();
        $daysLate = $today->greaterThan($this->tenggat)
            ? $today->diffInDays($this->tenggat)
            : 0;

        $denda   = 0;
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

        // Kalikan dengan total jumlah buku
        $totalJumlah  = $this->details->sum('jumlah') ?: $this->jumlah_keseluruhan ?: 1;
        return $denda * $totalJumlah;
    }

    public function getDendaBerjalanAttribute()
    {
        if ($this->status !== 'Dipinjam') {
            return $this->denda ?? 0;
        }

        $today   = Carbon::today();
        $tenggat = Carbon::parse($this->tenggat);

        if ($today->lessThanOrEqualTo($tenggat)) {
            return 0;
        }

        $daysLate    = $tenggat->diffInDays($today);
        $totalJumlah = $this->details->sum('jumlah') ?: $this->jumlah_keseluruhan ?: 1;
        return $daysLate * 2000 * $totalJumlah;
    }

    public function getFormattedTanggalPinjamAttribute()
    {
        return $this->tgl_pinjam ? Carbon::parse($this->tgl_pinjam)->format('d M Y') : '-';
    }

    public function getFormattedTanggalKembaliAttribute()
    {
        return $this->tenggat ? Carbon::parse($this->tenggat)->format('d M Y') : '-';
    }

    public function getJumlahPerpanjanganAttribute()
    {
        return $this->perpanjanganDisetujui()->count();
    }

    public function getSisaHariAttribute()
    {
        if (! $this->tenggat || $this->status !== 'Dipinjam') {
            return null;
        }

        $tenggat = Carbon::parse($this->tenggat);
        $today   = Carbon::today();

        if ($today->gt($tenggat)) {
            return 0;
        }

        return $today->diffInDays($tenggat);
    }

    // ========== METHOD PERPANJANGAN ==========

    public function canExtend()
    {
        if ($this->status !== 'Dipinjam') {
            return false;
        }

        if ($this->perpanjanganPending) {
            return false;
        }

        $jumlahPerpanjangan = $this->perpanjanganDisetujui()->count();
        if ($jumlahPerpanjangan >= 2) {
            return false;
        }

        if ($this->denda > 0) {
            return false;
        }

        if (Carbon::today()->gt(Carbon::parse($this->tenggat))) {
            return false;
        }

        return true;
    }

    public function hasPendingExtension()
    {
        return $this->perpanjanganPending !== null;
    }

    public function getExtensionStatusAttribute()
    {
        if ($this->perpanjanganPending) {
            return 'Menunggu Persetujuan';
        }

        $jumlah = $this->jumlah_perpanjangan;
        if ($jumlah > 0) {
            return "Sudah diperpanjang {$jumlah}x";
        }

        return 'Belum pernah diperpanjang';
    }

    public function isLate()
    {
        if (! $this->tenggat || $this->status !== 'Dipinjam') {
            return false;
        }

        return Carbon::today()->gt(Carbon::parse($this->tenggat));
    }

    // ========== SCOPE ==========

    public function scopeDipinjam($query)
    {
        return $query->where('status', 'Dipinjam');
    }

    public function scopeTerlambat($query)
    {
        return $query->where('status', 'Dipinjam')
            ->where('tenggat', '<', Carbon::today());
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }
}
