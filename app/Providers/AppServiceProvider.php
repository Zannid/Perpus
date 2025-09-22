<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Peminjaman;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Bagikan data notifikasi ke semua view
        View::composer('*', function ($view) {
            $notifikasiPeminjaman = Peminjaman::where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();

            $jumlahNotifikasi = $notifikasiPeminjaman->count();

            $view->with(compact('notifikasiPeminjaman', 'jumlahNotifikasi'));
        });
    }
}
