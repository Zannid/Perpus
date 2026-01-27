<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Buku;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
{
    $bulanLabels = [];
    $dataPeminjaman = [];
    $dataPengembalian = [];

    for ($i = 1; $i <= 12; $i++) {
        $bulanLabels[] = Carbon::create()->month($i)->shortMonthName; // Jan, Feb, ...
        $dataPeminjaman[] = Peminjaman::whereMonth('tgl_pinjam', $i)->count();
        $dataPengembalian[] = Pengembalian::whereMonth('tenggat', $i)->count();
    }
    $notifikasiPeminjaman = Peminjaman::with(['user', 'buku'])
    ->where('status', 'pending')
    ->latest()
    ->get();

$jumlahNotifikasi = Peminjaman::where('status', 'pending')
    ->where('status_baca', false)
    ->count();

    $userId = auth()->id();

    return view('home', [
        'peminjamanAktif' => Peminjaman::where('id_user', $userId)
            ->whereIn('status', ['dipinjam', 'disetujui'])
            ->count(),
        'totalPeminjaman' => Peminjaman::where('id_user', $userId)->count(),

        'peminjamanTerbaru' => Peminjaman::with('buku')
            ->where('id_user', $userId)
            ->whereIn('status', ['dipinjam', 'disetujui'])
            ->latest()
            ->take(3)
            ->get(),
        'rekomendasiBuku' => Buku::where('stok', '>', 0)
            ->inRandomOrder()
            ->take(4)
            ->get(),
        'riwayatAktivitas' => Peminjaman::with('buku')
            ->where('id_user', $userId)
            ->latest()
            ->take(5)
            ->get(),
        'bukuBaru' => Buku::whereMonth('created_at', now()->month)
            ->count()
    ],compact('bulanLabels', 'dataPeminjaman', 'dataPengembalian') );
    


}
    public function dashboard()
    {
       return redirect()->route('home'); // atau route lain yang sudah ada
 // pastikan ada file resources/views/dashboard.blade.php
    }
}
