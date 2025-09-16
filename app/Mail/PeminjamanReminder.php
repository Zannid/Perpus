<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Peminjaman;

class PeminjamanReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjaman;

    public function __construct(Peminjaman $peminjaman)
    {
        $this->peminjaman = $peminjaman;
        $this->user = $peminjaman->user;
    }
    public function build()
    {
        return $this->subject('Pengingat Peminjaman Buku')
                    ->view('emails.peminjaman-reminder')
                    ->with([
                        'user' => $this->user,
                        'peminjaman' => $this->peminjaman,
                    ]);
    }
    public function content()
    {
        return new Content(
            view: 'wemails.reminder',
        );
    }

}
