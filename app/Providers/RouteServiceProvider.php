<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Jalur ke file definisi home (opsional, tergantung kebutuhan).
     */
    public const HOME = '/home';

    /**
     * Definisikan method boot untuk memuat route.
     */
    public function boot(): void
    {
        $this->routes(function () {
            // API routes
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Web routes
            Route::middleware('web')
                ->prefix('/')
                ->group(base_path('routes/web.php'));
        });
    }
}
