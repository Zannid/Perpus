<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Peminjaman;

class PeminjamanRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjaman;

    /**
     * Create a new message instance.
     */
    public function __construct(Peminjaman $peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('❌ Peminjaman Buku Ditolak - ' . config('app.name'))
                    ->view('emails.peminjaman-rejected');
    }
}