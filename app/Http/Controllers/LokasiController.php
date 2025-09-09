<?php
namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lokasi   = Lokasi::all();
        $kategori = Kategori::all();
        return view('lokasi.index', compact('lokasi', 'kategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('lokasi.create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $lokasi = new Lokasi();

        // generate kode rak otomatis
        $lastRecord = Lokasi::latest('id')->first();
        $lastId     = $lastRecord ? $lastRecord->id : 0;
        $kodeRak    = 'RK-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT); // ex: RK-001

        $lokasi->kode_rak    = $kodeRak;
        $lokasi->id_kategori = $request->id_kategori;
        $lokasi->keterangan  = $request->keterangan;
        $lokasi->save();

        return redirect()->route('lokasi.index')->with('success', 'Lokasi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lokasi   = Lokasi::findOrFail($id);
        $kategori = Kategori::all();
        return view('lokasi.edit', compact('lokasi', 'kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lokasi = Lokasi::findOrFail($id);

        $lokasi->id_kategori = $request->id_kategori;
        $lokasi->keterangan  = $request->keterangan;
        $lokasi->save();

        return redirect()->route('lokasi.index')->with('success', 'Lokasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->delete();
        return redirect()->route('lokasi.index')->with('success', 'Data berhasil dihapus.');
    }
}
