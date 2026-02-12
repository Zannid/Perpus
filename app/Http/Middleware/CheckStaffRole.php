<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStaffRole
{
    /**
     * Handle an incoming request.
     * Memungkinkan akses untuk Admin dan Petugas
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Admin dan Petugas yang bisa akses
        if (! in_array($user->role, ['admin', 'petugas'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini. Hanya admin dan petugas yang diizinkan.');
        }

        return $next($request);
    }
}
