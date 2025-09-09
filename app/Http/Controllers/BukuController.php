<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Lokasi;
class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buku = Buku::all();
        $kategori = Kategori::all();
        $lokasi = Lokasi::all();
        return view('buku.index', compact('buku', 'lokasi','kategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::all();
        $lokasi   = Lokasi::all();
        return view('buku.create', compact('lokasi', 'kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $buku       = new Buku();
        $lastRecord = Buku::latest('id')->first();
        $lastId     = $lastRecord ? $lastRecord->id : 0;
        $kodeBuku   = 'BK-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

        $buku->kode_buku    = $kodeBuku;
        $buku->judul        = $request->judul;
        $buku->penulis      = $request->penulis;
        $buku->penerbit     = $request->penerbit;
        $buku->tahun_terbit = $request->tahun_terbit;
        $buku->id_kategori  = $request->id_kategori;
        $buku->foto         = $request->foto;
        $buku->id_lokasi    = $request->id_lokasi;
        $buku->stok         = $request->stok;

        if ($request->hasFile('foto')) {
            $img  = $request->file('foto');
            $name = rand(1000, 9999) . $img->getClientOriginalName();
            $img->move('storage/buku', $name);
            $buku->foto = $name;
        }
        $buku->save();
        return redirect()->route('buku.index')->with('success', 'buku berhasil ditambahkan.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $buku = Buku::findOrFail($id);
        $kategori = Kategori::all();
        $lokasi   = Lokasi::all();
        return view('buku.edit', compact('buku','lokasi', 'kategori'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $buku       = Buku::findOrFail($id);

        $buku->judul        = $request->judul;
        $buku->penulis      = $request->penulis;
        $buku->penerbit     = $request->penerbit;
        $buku->tahun_terbit = $request->tahun_terbit;
        $buku->id_kategori  = $request->id_kategori;
        $buku->id_lokasi    = $request->id_lokasi;
        $buku->stok         = $request->stok;

       if ($request->hasFile('foto')) {
            if ($buku->foto && file_exists(public_path('storage/buku/' . $buku->foto))) {
                unlink(public_path('storage/buku/' . $buku->foto));
            }

            $img  = $request->file('foto');
            $name = time() . '_' . $img->getClientOriginalName();
            $img->move(public_path('storage/buku'), $name);
            $buku->foto = $name;
        }

        $buku->save();
        return redirect()->route('buku.index')->with('success', 'buku berhasil diubah');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Data berhasil dihapus');
    }
}
