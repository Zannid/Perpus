<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class PeminjamanDiajukan implements ShouldBroadcastNow
{
    use SerializesModels;

    public $peminjaman;

    public function __construct($peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    public function broadcastOn()
    {
        // channel publik untuk admin/petugas (atau private jika mau)
        return new Channel('admin-notifikasi');
    }

    public function broadcastWith()
    {
        return [
            'id'         => $this->peminjaman->id,
            'kode'       => $this->peminjaman->kode_peminjaman,
            'user_name'  => $this->peminjaman->user->name ?? '-',
            'buku_judul' => $this->peminjaman->buku->judul ?? '-',
            'created_at' => $this->peminjaman->created_at->toDateTimeString(),
        ];
    }
    
}
