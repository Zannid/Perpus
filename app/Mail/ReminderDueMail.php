<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Mail\ReminderDueMail;
use Illuminate\Support\Facades\Mail;

// $peminjaman adalah model / array yang berisi data
Mail::to($peminjaman->email)->send(new ReminderDueMail($peminjaman));

class ReminderDueMail extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjaman;

    public function __construct($peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    public function build()
    {
        return $this->subject('Pengingat Pengembalian Buku')
            ->markdown('emails.reminder_due');
    }
}
