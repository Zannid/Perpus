<?php
namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\pengembalian;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalAwal  = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        // ambil semua peminjaman (tanpa filter status)
        $query = Peminjaman::with('buku', 'user');

        // kalau filter tanggal diisi
        if ($tanggalAwal && $tanggalAkhir) {
            $query->whereBetween('tgl_pinjam', [$tanggalAwal, $tanggalAkhir]);
        }

        // ambil datanya
        $peminjaman = $query->orderBy('tgl_pinjam', 'desc')->get();

        // format tanggal supaya lebih enak dibaca
        foreach ($peminjaman as $data) {
            $data->formatted_tanggal_pinjam  = \Carbon\Carbon::parse($data->tgl_pinjam)->translatedFormat('l, d F Y');
            $data->formatted_tanggal_kembali = \Carbon\Carbon::parse($data->tenggat)->translatedFormat('l, d F Y');
        }

        return view('peminjaman.index', compact('peminjaman'));
    }


    public function create()
    {
        $buku = Buku::all();
        return view('peminjaman._form', compact('buku'));
    }

    public function store(Request $request)
    {
        $lastRecord = Peminjaman::latest('id')->first();
        $lastId     = $lastRecord ? $lastRecord->id : 0;
        $kodePinjam = 'PJM-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        // hitung tenggat otomatis +7 hari
        $tglPinjam = Carbon::parse($request->tgl_pinjam);
        $tenggat   = $tglPinjam->copy()->addDays(7);

        $peminjaman                  = new Peminjaman();
        $peminjaman->kode_peminjaman = $kodePinjam;
        $peminjaman->tgl_pinjam      = $tglPinjam;
        $peminjaman->tenggat         = $tenggat; // otomatis 7 hari setelah tgl_pinjam
        $peminjaman->jumlah          = $request->jumlah;
        $peminjaman->id_user         = auth()->id();
        $peminjaman->id_buku         = $request->id_buku;
        $peminjaman->status          = "Pending"; 

        $peminjaman->save();

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dibuat (menunggu persetujuan petugas)');
    }
    public function pay($id)
{
    $peminjaman = Peminjaman::findOrFail($id);

    if ($peminjaman->denda > 0 && $peminjaman->status != "Denda Lunas") {
        // tampilkan halaman pembayaran
        return view('peminjaman.pay', compact('peminjaman'));
    }

    return redirect()->back()->with('info', 'Tidak ada denda untuk peminjaman ini.');
}

// Proses konfirmasi pembayaran
public function confirmPay(Request $request, $id)
{
    $peminjaman = Peminjaman::findOrFail($id);

    if ($peminjaman->denda > 0) {
        $peminjaman->status = "Denda Lunas";
        $peminjaman->save();

        return redirect()->route('peminjaman.index')->with('success', 'Denda berhasil dibayar.');
    }

    return redirect()->route('peminjaman.index')->with('info', 'Tidak ada denda untuk peminjaman ini.');
}


    // Petugas menyetujui peminjaman
    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $buku       = Buku::findOrFail($peminjaman->id_buku);

        if ($buku->stok < $peminjaman->jumlah) {
            return redirect()->back()->with('error', 'Stok buku tidak cukup');
        }

        $buku->stok -= $peminjaman->jumlah;
        $buku->save();

        $peminjaman->status = "Sedang Dipinjam";
        $peminjaman->save();

        return redirect()->back()->with('success', 'Peminjaman disetujui');
    }
    // Halaman khusus petugas -> hanya tampilkan yang Pending
public function pending()
{
    $peminjaman = Peminjaman::with('buku','user')
        ->where('status', 'Pending')
        ->orderBy('tgl_pinjam','desc')
        ->get();

    return view('peminjaman.acc', compact('peminjaman'));
}


public function return(Request $request, $id)
{
    $peminjaman = Peminjaman::findOrFail($id);
    $buku       = Buku::findOrFail($peminjaman->id_buku);

    $today = Carbon::now();
    $denda = 0;
    $daysLate = 0;

    // hitung keterlambatan
    if ($today->greaterThan($peminjaman->tenggat)) {
        $daysLate = $today->diffInDays($peminjaman->tenggat);
    }

    // tentukan denda per buku
    if ($request->kondisi == "Bagus") {
        if ($daysLate > 0) {
            $denda = 10000 + ($daysLate * 2000);
        }
    } elseif ($request->kondisi == "Rusak") {
        $denda = 20000;
        if ($daysLate > 0) {
            $denda += ($daysLate * 2000);
        }
    } elseif ($request->kondisi == "Hilang") {
        $denda = 50000;
    }

    // total denda = denda per buku * jumlah buku yang dipinjam
    $denda = $denda * $peminjaman->jumlah;

    // jika tidak hilang, kembalikan stok
    if ($request->kondisi != "Hilang") {
        $buku->stok += $peminjaman->jumlah;
        $buku->save();
    }

    // simpan ke tabel pengembalian
    pengembalian::create([
        'id_peminjaman' => $peminjaman->id,
        'id_user'       => $peminjaman->id_user,
        'id_buku'       => $peminjaman->id_buku,
        'tgl_pinjam'    => $peminjaman->tgl_pinjam,
        'tenggat'       => $peminjaman->tenggat,
        'tgl_kembali'   => $today,
        'jumlah'        => $peminjaman->jumlah,
        'kondisi'       => $request->kondisi,
        'denda'         => $denda,
    ]);

    // update status peminjaman
    $peminjaman->status  = "Sudah Dikembalikan";
    $peminjaman->denda   = $denda;
    $peminjaman->kondisi = $request->kondisi;
    $peminjaman->save();

    if ($denda > 0) {
        return redirect()->back()->with('warning', "Buku dikembalikan dengan kondisi {$request->kondisi}. Denda: Rp " . number_format($denda, 0, ',', '.'));
    }

    return redirect()->back()->with('success', "Buku dikembalikan dengan kondisi {$request->kondisi} tanpa denda.");
}





    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // kalau masih pending, stok belum berkurang jadi langsung hapus
        if ($peminjaman->status == "Sedang Dipinjam") {
            $buku = Buku::findOrFail($peminjaman->id_buku);
            $buku->stok += $peminjaman->jumlah;
            $buku->save();
        }

        $peminjaman->delete();

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dihapus');
    }
}
