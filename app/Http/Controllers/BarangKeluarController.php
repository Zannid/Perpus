<?php
namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Buku;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $bk   = BarangKeluar::all();
        $buku = Buku::all();
        return view('barangkeluar.index', compact('bk', 'buku'));
    }

    public function create()
    {
        $buku = Buku::all();
        return view('barangkeluar._form', compact('buku'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlah'     => 'required|integer|min:1',
            'tgl_keluar' => 'required|date',
            'id_buku'    => 'required|exists:bukus,id',
        ]);

        $lastRecord = BarangKeluar::latest('id')->first();
        $lastId     = $lastRecord ? $lastRecord->id : 0;
        $kodeBk     = 'BKL-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

        $bk              = new BarangKeluar();
        $bk->kode_keluar = $kodeBk;
        $bk->jumlah      = $request->jumlah;
        $bk->tgl_keluar  = $request->tgl_keluar;
        $bk->ket         = $request->ket;
        $bk->id_buku     = $request->id_buku;

        $buku = Buku::findOrFail($request->id_buku);

        if ($buku->stok < $request->jumlah) {
            Alert::warning('Warning', 'Stok Tidak Cukup')->autoClose(1500);
            return redirect()->route('barangkeluar.index');
        } else {
            $buku->stok -= $request->jumlah;
            $buku->save();
            $bk->save();
        }

        return redirect()->route('barangkeluar.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        $bk   = BarangKeluar::findOrFail($id);
        $buku = Buku::all();
        return view('barangkeluar._form', compact('bk', 'buku'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'jumlah'     => 'required|integer|min:1',
            'tgl_keluar' => 'required|date',
            'id_buku'    => 'required|exists:bukus,id',
        ]);

        $bk   = BarangKeluar::findOrFail($id);
        $buku = Buku::findOrFail($request->id_buku);

        // kembalikan stok lama dulu
        $buku->stok += $bk->jumlah;

        // cek stok baru
        if ($buku->stok < $request->jumlah) {
            Alert::warning('Warning', 'Stok Tidak Cukup')->autoClose(1500);
            return redirect()->route('barangkeluar.index');
        }

        // update data barang keluar
        $bk->update([
            'jumlah'     => $request->jumlah,
            'tgl_keluar' => $request->tgl_keluar,
            'ket'        => $request->ket,
            'id_buku'    => $request->id_buku,
        ]);

        // kurangi stok sesuai jumlah baru
        $buku->stok -= $request->jumlah;
        $buku->save();

        return redirect()->route('barangkeluar.index')->with('success', 'Data berhasil diedit');
    }

    public function destroy(string $id)
    {
        $bk   = BarangKeluar::findOrFail($id);
        $buku = Buku::findOrFail($bk->id_buku);

        // kembalikan stok
        $buku->stok += $bk->jumlah;
        $buku->save();

        $bk->delete();

        return redirect()->route('barangkeluar.index')->with('success', 'Data berhasil dihapus');
    }
}
