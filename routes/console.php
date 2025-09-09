<?php
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    // Pengingat H-1
    $tomorrow = \Carbon\Carbon::tomorrow()->toDateString();

    \App\Models\Peminjaman::whereDate('tenggat', $tomorrow)
        ->where('status', 'Sedang Dipinjam')
        ->with('user', 'buku')
        ->get()
        ->each(fn($pinjam) => \Mail::to($pinjam->user->email)
            ->send(new \App\Mail\ReminderMail($pinjam->user, $pinjam->buku, 'H-1'))
        );
})->dailyAt('08:00');

Schedule::call(function () {
    // Pengingat Hari H
    $today = \Carbon\Carbon::today()->toDateString();

    \App\Models\Peminjaman::whereDate('tenggat', $today)
        ->where('status', 'Sedang Dipinjam')
        ->with('user', 'buku')
        ->get()
        ->each(fn($pinjam) => \Mail::to($pinjam->user->email)
            ->send(new \App\Mail\ReminderMail($pinjam->user, $pinjam->buku, 'H'))
        );
})->dailyAt('09:00');
