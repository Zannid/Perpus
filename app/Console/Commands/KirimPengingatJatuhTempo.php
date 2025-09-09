<?php
namespace App\Console\Commands;

use App\Mail\JatuhTempoMail;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class KirimPengingatJatuhTempo extends Command
{
    protected $signature   = 'pengingat:jatuh-tempo';
    protected $description = 'Mengirim email pengingat jatuh tempo ke user';

    public function handle()
    {
        $hariIni = Carbon::today();

        // Ambil semua peminjaman yang jatuh tempo hari ini
        $peminjaman = Peminjaman::with('user', 'buku')
            ->whereDate('tenggat', $hariIni)
            ->where('status', 'Sedang Dipinjam')
            ->get();

        foreach ($peminjaman as $pinjam) {
            Mail::to($pinjam->user->email)->send(new JatuhTempoMail(
                $pinjam->user->name,
                $pinjam->buku->judul,
                $pinjam->tenggat
            ));

            $this->info("Email terkirim ke {$pinjam->user->email}");
        }

        return Command::SUCCESS;
    }
}
