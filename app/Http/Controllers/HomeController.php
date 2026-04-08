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
            ->whereIn('status', ['Dipinjam', 'dipinjam', 'Disetujui', 'disetujui'])
            ->count();

        $totalPeminjaman = Peminjaman::where('id_user', $userId)->count();

        $bukuFavorit = \App\Models\DetailPeminjaman::join('peminjamen', 'detail_peminjaman.peminjaman_id', '=', 'peminjamen.id')
            ->where('peminjamen.id_user', $userId)
            ->distinct('detail_peminjaman.buku_id')
            ->count();

        // Hitung denda aktif hanya untuk peminjaman yang sedang dipinjam dan sudah melewati tenggat
        $dendaAktif = Peminjaman::where('id_user', $userId)
            ->whereIn('status', ['Dipinjam', 'dipinjam'])
            ->whereDate('tenggat', '<', Carbon::today())
            ->get()
            ->sum(function ($peminjaman) {
                return $peminjaman->denda_berjalan;
            });

        $peminjamanTerbaru = Peminjaman::with('buku')
            ->where('id_user', $userId)
            ->whereIn('status', ['Dipinjam', 'dipinjam', 'Disetujui', 'disetujui'])
            ->latest()
            ->take(3)
            ->get();

        $rekomendasiBuku = Buku::where('stok', '>', 0)
            ->inRandomOrder()
            ->take(4)
            ->get();

        $totalDendaLunas = Peminjaman::where('status', 'Lunas')
            ->where('denda', '>', 0)
            ->sum('denda');

        $totalDendaBelumBayar = Peminjaman::where('status', '!=', 'Lunas')
            ->where('denda', '>', 0)
            ->sum('denda');

        $riwayatAktivitas = Peminjaman::with('buku')
            ->where('id_user', $userId)
            ->latest()
            ->take(5)
            ->get();

        $bukuBaru = Buku::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('home', [
            'dataPeminjaman'       => json_encode($dataPeminjaman),
            'dataPengembalian'     => json_encode($dataPengembalian),
            'notifikasiPeminjaman' => $notifikasiPeminjaman,
            'jumlahNotifikasi'     => $jumlahNotifikasi,
            'peminjamanAktif'      => $peminjamanAktif,
            'totalPeminjaman'      => $totalPeminjaman,
            'bukuFavorit'          => $bukuFavorit,
            'dendaAktif'           => $dendaAktif,
            'totalDendaLunas'      => $totalDendaLunas,
            'totalDendaBelumBayar' => $totalDendaBelumBayar,
            'peminjamanTerbaru'    => $peminjamanTerbaru,
            'rekomendasiBuku'      => $rekomendasiBuku,
            'riwayatAktivitas'     => $riwayatAktivitas,
            'bukuBaru'             => $bukuBaru,
        ]);
    }

    public function dashboard()
    {
        return redirect()->route('home');
    }
}
