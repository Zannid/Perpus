<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
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


    return view('home', compact('bulanLabels', 'dataPeminjaman', 'dataPengembalian'));
}
    public function dashboard()
    {
       return redirect()->route('home'); // atau route lain yang sudah ada
 // pastikan ada file resources/views/dashboard.blade.php
    }
}
