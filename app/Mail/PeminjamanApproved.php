<?php
namespace App\Mail;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PeminjamanApproved extends Mailable
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
        return $this->subject('✅ Peminjaman Buku Disetujui - ' . config('app.name'))
            ->view('emails.peminjaman-approved');
    }
}
