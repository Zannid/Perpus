<?php
namespace App\Providers;

use App\Models\Peminjaman;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
        // Share data notifikasi ke semua view
        View::composer('*', function ($view) {
            $notifikasiPeminjaman = collect();
            $jumlahNotifikasi     = 0;

            // Cek apakah user sudah login
            if (auth()->check()) {
                $user = auth()->user();

                // Hanya untuk admin dan petugas
                if (in_array($user->role, ['admin', 'petugas'])) {
                    $notifikasiPeminjaman = Peminjaman::with(['user', 'buku'])
                        ->where('status', 'Pending')
                        ->where('status_baca', false)
                        ->latest()
                        ->get();

                    $jumlahNotifikasi = $notifikasiPeminjaman->count();
                }
            }

            // Share ke semua view
            $view->with(compact('notifikasiPeminjaman', 'jumlahNotifikasi'));
        });
    }
}
