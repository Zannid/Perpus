<?php
namespace App\Http\Controllers;

use App\Exports\BarangMasukExport;
use App\Models\BarangMasuk;
use App\Models\Buku;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request; // ✅ Tambahkan ini untuk PDF
use Maatwebsite\Excel\Facades\Excel;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $barangmasuk = BarangMasuk::with('buku')->orderBy('id', 'desc')->get();
        $buku        = Buku::all();

        if ($request->has('search')) {
            $barangmasuk = BarangMasuk::with('buku')
                ->whereHas('buku', function ($q) use ($request) {
                    $q->where('judul', 'LIKE', '%' . $request->search . '%');
                })
                ->orWhere('kode_masuk', 'LIKE', '%' . $request->search . '%')
                ->orWhere('tgl_masuk', 'LIKE', '%' . $request->search . '%')
                ->orWhere('ket', 'LIKE', '%' . $request->search . '%')
                ->orderBy('id', 'desc')
                ->get();
        }

        session()->flash('success', 'Data berhasil dimuat.');
        return view('barangmasuk.index', compact('barangmasuk', 'buku'));
    }

    public function exportExcel()
    {
        return Excel::download(new BarangMasukExport, 'barang-masuk.xlsx');
    }

    // ✅ Tambahkan Export PDF
    public function exportPdf(Request $request)
    {
        $query = BarangMasuk::with('buku')->orderBy('id', 'desc');

        // Filter pencarian jika ada
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('buku', function ($q) use ($request) {
                    $q->where('judul', 'LIKE', '%' . $request->search . '%');
                })
                    ->orWhere('kode_masuk', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('tgl_masuk', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('ket', 'LIKE', '%' . $request->search . '%');
            });
        }

        $barangmasuk = $query->get();

        // Format tanggal biar rapi
        foreach ($barangmasuk as $bm) {
            $bm->formatted_tanggal = Carbon::parse($bm->tgl_masuk)->translatedFormat('l, d F Y');
        }

        $pdf = Pdf::loadView('pdf.barangmasuk', compact('barangmasuk'))
            ->setPaper('A4', 'portrait'); // bisa ubah jadi 'landscape' kalau tabel lebar

        return $pdf->download('laporan-barang-masuk.pdf');
    }

    public function create()
    {
        $buku = Buku::all();
        return view('barangmasuk._form', compact('buku'));
    }

    public function store(Request $request)
    {
       $request->validate([
            'id_buku'   => 'required|array',
            'id_buku.*' => 'exists:bukus,id',
            'jumlah'    => 'required|array',
            'jumlah.*'  => 'integer|min:1',
            'tgl_masuk' => 'required|date',
        ]);


        $lastRecord = BarangMasuk::latest('id')->first();
        $lastId     = $lastRecord ? $lastRecord->id : 0;

        foreach ($request->id_buku as $index => $bukuId) {
            $jumlah = $request->jumlah[$index];
            $kodeBm = 'BK-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

            $bm             = new BarangMasuk();
            $bm->kode_masuk = $kodeBm;
            $bm->id_buku    = $bukuId;
            $bm->jumlah     = $jumlah;
            $bm->tgl_masuk  = $request->tgl_masuk;
            $bm->ket        = $request->ket;
            $bm->save();

            // Update stok buku
            $buku        = Buku::findOrFail($bukuId);
            $buku->stok += $jumlah;
            $buku->save();

            $lastId++;
        }


        return redirect()->route('barangmasuk.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $bm   = BarangMasuk::findOrFail($id);
        $buku = Buku::all();
        return view('barangmasuk._form', compact('bm', 'buku'));
    }

    public function show($id)
    {
        $barangMasuk = BarangMasuk::with('buku')->findOrFail($id);
        return view('barangmasuk.show', compact('barangMasuk'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'jumlah'    => 'required|integer|min:1',
            'tgl_masuk' => 'required|date',
            'id_buku'   => 'required|exists:bukus,id',
        ]);

        $bm = BarangMasuk::findOrFail($id);

        $bukuLama = Buku::findOrFail($bm->id_buku);
        $bukuLama->stok -= $bm->jumlah;
        $bukuLama->save();

        $bm->jumlah    = $request->jumlah;
        $bm->tgl_masuk = $request->tgl_masuk;
        $bm->ket       = $request->ket;
        $bm->id_buku   = $request->id_buku;
        $bm->save();

        $bukuBaru = Buku::findOrFail($request->id_buku);
        $bukuBaru->stok += $request->jumlah;
        $bukuBaru->save();

        return redirect()->route('barangmasuk.index')->with('success', 'Data barang masuk berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $bm   = BarangMasuk::findOrFail($id);
        $buku = Buku::findOrFail($bm->id_buku);

        $buku->stok -= $bm->jumlah;
        $buku->save();

        $bm->delete();
        session()->flash('success', 'Data berhasil dihapus.');
        return redirect()->route('barangmasuk.index')->with('success', 'Data berhasil dihapus.');
    }
}