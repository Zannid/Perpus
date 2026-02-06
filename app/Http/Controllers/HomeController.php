<?php
namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
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
        // Initialize chart data
        $dataPeminjaman   = [];
        $dataPengembalian = [];

        // Get data for each month (1-12)
        for ($i = 1; $i <= 12; $i++) {
            $dataPeminjaman[] = Peminjaman::whereYear('tgl_pinjam', Carbon::now()->year)
                ->whereMonth('tgl_pinjam', $i)
                ->count();

            $dataPengembalian[] = Pengembalian::whereYear('tgl_kembali', Carbon::now()->year)
                ->whereMonth('tgl_kembali', $i)
                ->count();
        }

        // Notifikasi peminjaman untuk admin/petugas
        $notifikasiPeminjaman = Peminjaman::with(['user', 'buku'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $jumlahNotifikasi = Peminjaman::where('status', 'pending')
            ->where('status_baca', false)
            ->count();

        $userId = auth()->id();

        // Data untuk user dashboard
        $peminjamanAktif = Peminjaman::where('id_user', $userId)
            ->whereIn('status', ['dipinjam', 'disetujui'])
            ->count();

        $totalPeminjaman = Peminjaman::where('id_user', $userId)->count();

        $bukuFavorit = \App\Models\DetailPeminjaman::join('peminjamen', 'detail_peminjaman.peminjaman_id', '=', 'peminjamen.id')
            ->where('peminjamen.id_user', $userId)
            ->distinct('detail_peminjaman.buku_id')
            ->count();

        // Hitung denda aktif
        $dendaAktif = Peminjaman::where('id_user', $userId)
            ->whereIn('status', ['dipinjam', 'disetujui'])
            ->where('tenggat', '<', Carbon::now())
            ->get()
            ->sum(function ($peminjaman) {
                $hariTerlambat = Carbon::parse($peminjaman->tenggat)->diffInDays(Carbon::now());
                return $hariTerlambat * 1000; // Rp 1.000 per hari
            });

        $peminjamanTerbaru = Peminjaman::with('buku')
            ->where('id_user', $userId)
            ->whereIn('status', ['dipinjam', 'disetujui'])
            ->latest()
            ->take(3)
            ->get();

        $rekomendasiBuku = Buku::where('stok', '>', 0)
            ->inRandomOrder()
            ->take(4)
            ->get();

        $riwayatAktivitas = Peminjaman::with('buku')
            ->where('id_user', $userId)
            ->latest()
            ->take(5)
            ->get();

        $bukuBaru = Buku::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('home', compact(
            'dataPeminjaman',
            'dataPengembalian',
            'notifikasiPeminjaman',
            'jumlahNotifikasi',
            'peminjamanAktif',
            'totalPeminjaman',
            'bukuFavorit',
            'dendaAktif',
            'peminjamanTerbaru',
            'rekomendasiBuku',
            'riwayatAktivitas',
            'bukuBaru'
        ));
    }

    public function dashboard()
    {
        return redirect()->route('home');
    }
}
