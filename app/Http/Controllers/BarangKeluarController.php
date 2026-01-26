<?php
namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Buku;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $bk   = BarangKeluar::with('buku')->orderBy('id', 'desc')->get();
        $buku = Buku::all();

        if ($request->has('search')) {
            $bk = BarangKeluar::with('buku')
                ->whereHas('buku', function ($q) use ($request) {
                    $q->where('judul', 'LIKE', '%' . $request->search . '%');
                })
                ->orWhere('kode_keluar', 'LIKE', '%' . $request->search . '%')
                ->orWhere('tgl_keluar', 'LIKE', '%' . $request->search . '%')
                ->orWhere('ket', 'LIKE', '%' . $request->search . '%')
                ->orderBy('id', 'desc')
                ->get();
        }

        session()->flash('success', 'Data berhasil dimuat.');
        return view('barangkeluar.index', compact('bk', 'buku'));
    }

    public function exportPdf()
    {
        $bk  = BarangKeluar::with('buku')->orderBy('tgl_keluar', 'desc')->get();
        $pdf = Pdf::loadView('pdf.barangkeluar', compact('bk'))->setPaper('A4', 'landscape');
        return $pdf->download('laporan-barang-keluar.pdf');
    }

    public function create()
    {
        $buku = Buku::all();
        return view('barangkeluar._form', compact('buku'));
    }

    // ================= MULTIPLE STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'id_buku'    => 'required|array',
            'id_buku.*'  => 'exists:bukus,id',
            'jumlah'     => 'required|array',
            'jumlah.*'   => 'integer|min:1',
            'tgl_keluar' => 'required|date',
        ]);

        $lastRecord = BarangKeluar::latest('id')->first();
        $lastId     = $lastRecord ? $lastRecord->id : 0;

        foreach ($request->id_buku as $index => $bukuId) {
            $jumlah = $request->jumlah[$index];
            $buku   = Buku::findOrFail($bukuId);

            if ($buku->stok < $jumlah) {
                Alert::warning('Warning', "Stok buku '{$buku->judul}' tidak mencukupi")->autoClose(1500);
                return redirect()->back()->withInput();
            }

            $kodeBk = 'BKL-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

            $bk              = new BarangKeluar();
            $bk->kode_keluar = $kodeBk;
            $bk->id_buku     = $bukuId;
            $bk->jumlah      = $jumlah;
            $bk->tgl_keluar  = $request->tgl_keluar;
            $bk->ket         = $request->ket;
            $bk->save();

            // Kurangi stok buku
            $buku->stok -= $jumlah;
            $buku->save();

            $lastId++;
        }

        return redirect()->route('barangkeluar.index')->with('success', 'Data berhasil ditambahkan.');
    }

    // ================= MULTIPLE UPDATE =================
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_buku'    => 'required|array',
            'id_buku.*'  => 'exists:bukus,id',
            'jumlah'     => 'required|array',
            'jumlah.*'   => 'integer|min:1',
            'tgl_keluar' => 'required|date',
        ]);

        $bkRecords = BarangKeluar::where('kode_keluar', $request->kode_keluar ?? null)->get();

        if ($bkRecords->count() > 0) {
            // Kembalikan stok buku lama
            foreach ($bkRecords as $bk) {
                $buku        = Buku::findOrFail($bk->id_buku);
                $buku->stok += $bk->jumlah;
                $buku->save();
                $bk->delete(); // Hapus record lama
            }
        }

        // Simpan record baru (mirip store)
        $lastRecord  = BarangKeluar::latest('id')->first();
        $lastId      = $lastRecord ? $lastRecord->id : 0;

        foreach ($request->id_buku as $index => $bukuId) {
            $jumlah = $request->jumlah[$index];
            $buku   = Buku::findOrFail($bukuId);

            if ($buku->stok < $jumlah) {
                Alert::warning('Warning', "Stok buku '{$buku->judul}' tidak mencukupi")->autoClose(1500);
                return redirect()->back()->withInput();
            }

            $kodeBk = 'BKL-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

            $bk              = new BarangKeluar();
            $bk->kode_keluar = $kodeBk;
            $bk->id_buku     = $bukuId;
            $bk->jumlah      = $jumlah;
            $bk->tgl_keluar  = $request->tgl_keluar;
            $bk->ket         = $request->ket;
            $bk->save();

            $buku->stok -= $jumlah;
            $buku->save();

            $lastId++;
        }

        return redirect()->route('barangkeluar.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function edit(string $id)
    {
        $bk   = BarangKeluar::findOrFail($id);
        $buku = Buku::all();
        return view('barangkeluar._form', compact('bk', 'buku'));
    }

    public function destroy(string $id)
    {
        $bk   = BarangKeluar::findOrFail($id);
        $buku = Buku::findOrFail($bk->id_buku);

        $buku->stok += $bk->jumlah;
        $buku->save();

        $bk->delete();
        return redirect()->route('barangkeluar.index')->with('success', 'Data berhasil dihapus.');
    }
}
