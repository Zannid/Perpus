<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perpanjangan extends Model
{
    use HasFactory;

    protected $table = 'perpanjangan';

    protected $fillable = [
        'id_peminjaman',
        'tenggat_lama',
        'tenggat_baru',
        'alasan',
        'status',
        'alasan_tolak',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tenggat_lama' => 'date',
        'tenggat_baru' => 'date',
        'approved_at'  => 'datetime',
    ];

    /**
     * Relasi ke Peminjaman
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }

    /**
     * Relasi ke User (yang approve)
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Accessor untuk format tanggal
     */
    public function getFormattedTenggatLamaAttribute()
    {
        return $this->tenggat_lama ? Carbon::parse($this->tenggat_lama)->format('d M Y') : '-';
    }

    public function getFormattedTenggatBaruAttribute()
    {
        return $this->tenggat_baru ? Carbon::parse($this->tenggat_baru)->format('d M Y') : '-';
    }

    /**
     * Scope untuk perpanjangan pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope untuk perpanjangan disetujui
     */
    public function scopeDisetujui($query)
    {
        return $query->where('status', 'Disetujui');
    }

    /**
     * Scope untuk perpanjangan ditolak
     */
    public function scopeDitolak($query)
    {
        return $query->where('status', 'Ditolak');
    }
}
