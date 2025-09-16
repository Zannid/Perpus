<?php
namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Buku;
use Illuminate\Http\Request;
use App\Exports\BarangMasukExport;
use Maatwebsite\Excel\Facades\Excel;


class BarangMasukController extends Controller
{
    public function index()
    {
        $barangmasuk = BarangMasuk::all();
        $buku        = Buku::all();
        return view('barangmasuk.index', compact('barangmasuk', 'buku'));
    }
    public function exportExcel()
{
    return Excel::download(new BarangMasukExport, 'barang-masuk.xlsx');
}

    public function create()
    {
        $buku = Buku::all();
        return view('barangmasuk._form', compact('buku'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlah'    => 'required|integer|min:1',
            'tgl_masuk' => 'required|date',
            'id_buku'   => 'required|exists:bukus,id',
        ]);

        $lastRecord = BarangMasuk::latest('id')->first();
        $lastId     = $lastRecord ? $lastRecord->id : 0;
        $kodeBm     = 'BK-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

        $bm             = new BarangMasuk();
        $bm->kode_masuk = $kodeBm;
        $bm->jumlah     = $request->jumlah;
        $bm->tgl_masuk  = $request->tgl_masuk;
        $bm->ket        = $request->ket;
        $bm->id_buku    = $request->id_buku;
        $bm->save();

        $buku = Buku::findOrFail($request->id_buku);
        $buku->stok += $request->jumlah;
        $buku->save();

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

        return redirect()->route('barangmasuk.index')->with('success', 'Data berhasil dihapus.');
    }
}
