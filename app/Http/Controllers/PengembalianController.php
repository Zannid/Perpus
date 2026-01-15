<?php
namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Pengembalian::with(['peminjaman', 'user', 'buku']);

        if (auth()->check() && auth()->user()->role === 'user') {
            $query = Pengembalian::with(['peminjaman', 'user', 'buku'])->where('id_user', auth()->id());
        } else {
            $query = Pengembalian::with(['peminjaman', 'user', 'buku']);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                })
                    ->orWhereHas('buku', function ($q) use ($search) {
                        $q->where('judul', 'LIKE', "%$search%");
                    })
                    ->orWhere('kondisi', 'LIKE', "%$search%");
            });
        }

        $pengembalian = $query->orderByDesc('id')->paginate(5);

        return view('pengembalian.index', compact('pengembalian', 'search'));
    }

    public function export(Request $request)
    {
        $tanggalAwal  = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        if (auth()->check() && auth()->user()->role === 'user') {
            $query = Peminjaman::with(['details.buku', 'user'])
                ->where('id_user', auth()->id());
        } else {
            $query = Peminjaman::with(['details.buku', 'user']);
        }

        // ✅ FILTER STATUS HANYA "kembali", "lunas", "denda"
        $query->whereIn('status', ['kembali', 'lunas', 'denda']);

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
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                })->orWhereHas('details.buku', function ($q) use ($search) {
                    $q->where('judul', 'LIKE', "%$search%");
                })->orWhere('status', 'LIKE', "%$search%");
            });
        }

        $data = $query->orderByDesc('id')->get();

        foreach ($data as $item) {
            $item->formatted_tanggal_pinjam  = Carbon::parse($item->tgl_pinjam)->translatedFormat('l, d F Y');
            $item->formatted_tanggal_kembali = Carbon::parse($item->tenggat)->translatedFormat('l, d F Y');
        }

        $pdf = Pdf::loadView('pdf.pengembalian', compact('data', 'tanggalAwal', 'tanggalAkhir'));
        return $pdf->download('laporan-data-pengembalian.pdf');
    }
}
