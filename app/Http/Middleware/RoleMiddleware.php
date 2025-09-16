<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
                if (! Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Jika user memiliki salah satu role yang diizinkan, lanjutkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Tambahan pengecekan khusus jika diperlukan
        if ($request->routeIs('petugas.*') && $user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman petugas.');
        }

        // Default: akses ditolak
        abort(403, 'Anda tidak memiliki akses.');
    }
}
