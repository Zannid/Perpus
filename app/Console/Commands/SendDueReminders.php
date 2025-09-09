<?php
namespace App\Console\Commands;

use App\Mail\ReminderDueMail;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDueReminders extends Command
{
    protected $signature   = 'reminder:due';
    protected $description = 'Kirim email pengingat H-1 dan hari H untuk pengembalian buku';

    public function handle()
    {
        $today    = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // H-1 => tanggal kembali == besok
        $hMinusOne = Peminjaman::whereDate('tanggal_kembali', $tomorrow)->get();

        // Hari H => tanggal kembali == today
        $hDay = Peminjaman::whereDate('tanggal_kembali', $today)->get();

        $all = $hMinusOne->concat($hDay);

        foreach ($all as $p) {
            Mail::to($p->email)->send(new ReminderDueMail($p));
            $this->info("Sent reminder to {$p->email} for id {$p->id}");
        }

        $this->info('Done.');
        return 0;
    }
}
