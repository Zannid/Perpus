<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\About;

class FrontController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $buku = Buku::with('kategori')->get();
    $kategori = Kategori::all();

    $bukuTerpopuler = Buku::withCount('details')
        // ->orderBy('peminjaman_count', 'DESC')
        ->take(3)
        ->get();

   $about = About::where('is_active', true)->first();
 // ambil data About

    return view('welcome',
        compact('bukuTerpopuler', 'buku', 'kategori', 'request', 'about')
    );
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Ambil data buku dengan relasi kategori
        $buku = Buku::with('kategori')->findOrFail($id);
        
        // Ambil buku terkait dari kategori yang sama (exclude buku saat ini)
        $relatedBooks = Buku::where('id_kategori', $buku->id_kategori)
            ->where('id', '!=', $id)
            ->where('stok', '>', 0) // hanya yang ada stoknya
            ->take(4)
            ->get();
        
        return view('detail_buku', compact('buku', 'relatedBooks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
