<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PeminjamanApprovedNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public $peminjaman;

    public function __construct($peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    public function via($notifiable)
    {
        // Notifikasi untuk database + realtime (broadcast)
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Peminjaman Disetujui',
            'message' => "Peminjaman kode {$this->peminjaman->kode_peminjaman} telah disetujui.",
            'kode' => $this->peminjaman->kode_peminjaman,
            'id' => $this->peminjaman->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id' => $this->peminjaman->id,
            'title' => 'Peminjaman Disetujui',
            'message' => "Peminjaman kode {$this->peminjaman->kode_peminjaman} telah disetujui.",
            'kode' => $this->peminjaman->kode_peminjaman,
            'created_at' => now()->toDateTimeString(),
        ]);
    }
}
