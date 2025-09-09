<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $buku;
    public $jenis;

    public function __construct($user, $buku, $jenis)
    {
        $this->user  = $user;
        $this->buku  = $buku;
        $this->jenis = $jenis; // 'H-1' atau 'H'
    }

    public function build()
    {
        return $this->subject('Pengingat Peminjaman Buku')
            ->view('emails.reminder');
    }
}
