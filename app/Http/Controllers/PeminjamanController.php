<?php
namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Mail\PeminjamanReminder;
use Illuminate\Support\Facades\Mail;



class PeminjamanController extends Controller
{
    public function sendReminder($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
    
        Mail::to($peminjaman->user->email)->send(new PeminjamanReminder($peminjaman));
    
        return back()->with('success', 'Email pengingat berhasil dikirim ke user.');
    }
    public function index(Request $request)
    {
        $tanggalAwal  = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        if (auth()->check() && auth()->user()->role === 'user') {
            $query = Peminjaman::with(['buku', 'user'])->where('id_user', auth()->id());
        } else {
            $query = Peminjaman::with(['buku', 'user']);
        }

        if ($tanggalAwal || $tanggalAkhir) {
            if ($tanggalAwal) {
                $startDate = Carbon::parse($tanggalAwal)->startOfDay();
            }

            if ($tanggalAkhir) {
                $endDate = Carbon::parse($tanggalAkhir)->endOfDay();
            }

            if ($tanggalAwal && $tanggalAkhir) {
                $query->whereBetween('tgl_pinjam', [$startDate, $endDate]);
            } elseif ($tanggalAwal) {
                $query->whereDate('tgl_pinjam', '>=', $startDate);
            } elseif ($tanggalAkhir) {
                $query->whereDate('tgl_pinjam', '<=', $endDate);
            }
        }


        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%");
            })->orWhereHas('buku', function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%$search%");
            })->orWhere('status', 'LIKE', "%$search%");
        }

        $peminjaman = $query->orderByDesc('id')->paginate(5);

        // format tanggal supaya enak dibaca di view
        foreach ($peminjaman as $data) {
            $data->formatted_tanggal_pinjam  = Carbon::parse($data->tgl_pinjam)->translatedFormat('l, d F Y');
            $data->formatted_tanggal_kembali = Carbon::parse($data->tenggat)->translatedFormat('l, d F Y');
        }

        return view('peminjaman.index', compact('peminjaman', 'tanggalAwal', 'tanggalAkhir', 'request'));
    }

    public function create()
    {
        $buku = Buku::all();
        return view('peminjaman._form', compact('buku'));
    }

    public function store(Request $request)
    {
        $lastId     = Peminjaman::max('id') ?? 0;
        $kodePinjam = 'PJM-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        $tglPinjam = Carbon::parse($request->tgl_pinjam);
        $tenggat   = $tglPinjam->copy()->addDays(7);

        Peminjaman::create([
            'kode_peminjaman' => $kodePinjam,
            'tgl_pinjam'      => $tglPinjam,
            'tenggat'         => $tenggat,
            'jumlah'          => $request->jumlah,
            'id_user'         => auth()->id(),
            'id_buku'         => $request->id_buku,
            'status'          => "Pending",
        ]);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil dibuat (menunggu persetujuan petugas)');
    }

    public function export()
    {
        $data = Peminjaman::where('status', 'Dipinjam')->get();
        $pdf = Pdf::loadView('pdf.peminjaman', ['data' => $data]);
        return $pdf->download('laporan-data-peminjaman.pdf');
    }

    public function pay($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->denda > 0 && $peminjaman->status !== "Denda Lunas") {
            return view('peminjaman.pay', compact('peminjaman'));
        }

        return back()->with('info', 'Tidak ada denda untuk peminjaman ini.');
    }

    public function confirmPay($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->denda > 0) {
            $peminjaman->update(['status' => "Denda Lunas"]);
            return redirect()->route('peminjaman.index')->with('success', 'Denda berhasil dibayar.');
        }

        return redirect()->route('peminjaman.index')->with('info', 'Tidak ada denda untuk peminjaman ini.');
    }

    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $buku       = Buku::findOrFail($peminjaman->id_buku);

        if ($buku->stok < $peminjaman->jumlah) {
            return back()->with('error', 'Stok buku tidak cukup');
        }

        $buku->decrement('stok', $peminjaman->jumlah);
        $peminjaman->update(['status' => "Dipinjam"]);

        return back()->with('success', 'Peminjaman disetujui');
    }

    public function pending()
    {
        $peminjaman = Peminjaman::with(['buku', 'user'])
            ->where('status', 'Pending')
            ->orderByDesc('tgl_pinjam')
            ->paginate(5);

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
        $peminjaman->status  = "Dikembalikan";
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

        if ($peminjaman->status === "Dipinjam") {
            Buku::where('id', $peminjaman->id_buku)
                ->increment('stok', $peminjaman->jumlah);
        }

        $peminjaman->delete();

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dihapus');
    }
}
