<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuApiController extends Controller
{
    public function index()
{
    $books = Buku::with(['kategori', 'lokasi', 'penerbit'])
        ->withAvg('ratings', 'nilai')
        ->withCount('ratings')
        ->get()
        ->map(fn($b) => $this->formatBuku($b));

    return response()->json(['success' => true, 'data' => $books]);
}

public function latest()
{
    $books = Buku::with(['kategori', 'lokasi', 'penerbit'])
        ->withAvg('ratings', 'nilai')
        ->withCount('ratings')
        ->latest()->limit(10)->get()
        ->map(fn($b) => $this->formatBuku($b));

    return response()->json(['success' => true, 'data' => $books]);
}

// Tambah endpoint popular yang belum ada
public function popular()
{
    $books = Buku::with(['kategori', 'lokasi', 'penerbit'])
        ->withAvg('ratings', 'nilai')
        ->withCount('ratings')
        ->orderByDesc('ratings_count')
        ->limit(10)->get()
        ->map(fn($b) => $this->formatBuku($b));

    return response()->json(['success' => true, 'data' => $books]);
}

public function show($id)
{
    $buku = Buku::with(['kategori', 'lokasi', 'penerbit'])
        ->withAvg('ratings', 'nilai')
        ->withCount('ratings')
        ->find($id);

    if (!$buku) {
        return response()->json(['success' => false, 'message' => 'Buku not found'], 404);
    }

    return response()->json(['success' => true, 'data' => $this->formatBuku($buku)]);
}

public function search(Request $request)
{
    $query = $request->get('q', '');

    $books = Buku::with(['kategori', 'lokasi', 'penerbit'])
        ->withAvg('ratings', 'nilai')
        ->withCount('ratings')
        ->where(function ($q) use ($query) {
            $q->where('judul', 'like', "%$query%")
              ->orWhere('penulis', 'like', "%$query%")  // fix: 'pengarang' → sesuaikan nama kolom
              ->orWhere('penerbit_id', 'like', "%$query%");
        })
        ->get()
        ->map(fn($b) => $this->formatBuku($b));

    return response()->json(['success' => true, 'data' => $books]);
}

// Helper untuk format response + URL foto yang benar
private function formatBuku($buku): array
{
    return [
        'id'           => $buku->id,
        'kode_buku'    => $buku->kode_buku,
        'judul'        => $buku->judul,
        'penulis'      => $buku->penulis,
        'penerbit'     => $buku->penerbit ? [
            'id'           => $buku->penerbit->id,
            'nama_penerbit'=> $buku->penerbit->nama_penerbit,
            'created_at'   => $buku->penerbit->created_at,
            'updated_at'   => $buku->penerbit->updated_at,
        ] : ['id' => 0, 'nama_penerbit' => '-', 'created_at' => null, 'updated_at' => null],
        'tahun_terbit' => $buku->tahun_terbit,
        'id_kategori'  => $buku->kategori_id,
        'foto'         => $buku->foto,
        // ✅ Kirim URL lengkap langsung dari backend
        'foto_url'     => $buku->foto ? asset('storage/' . $buku->foto) : null,
        'deskripsi'    => $buku->deskripsi,
        'id_lokasi'    => $buku->lokasi_id,
        'stok'         => $buku->stok,
        'rating_avg'   => round($buku->ratings_avg_nilai ?? 0, 1),
        'rating_count' => $buku->ratings_count ?? 0,
        'created_at'   => $buku->created_at,
        'updated_at'   => $buku->updated_at,
    ];
}

    public function store(Request $request)
    {
        $buku = Buku::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $buku
        ], 201);
    }

    

    public function update(Request $request, $id)
    {
        $buku = Buku::find($id);

        if ($buku) {
            $buku->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $buku
            ]);
        }

        return response()->json(['message' => 'Buku not found'], 404);
    }

    public function destroy($id)
    {
        $buku = Buku::find($id);

        if ($buku) {
            $buku->delete();

            return response()->json([
                'success' => true,
                'message' => 'Buku deleted'
            ]);
        }

        return response()->json(['message' => 'Buku not found'], 404);
    }


    public function categories()
    {
        return response()->json([
            'success' => true,
            'data' => \App\Models\Kategori::all(),
        ]);
    }

    public function bookshelves()
    {
        return response()->json([
            'success' => true,
            'data' => \App\Models\Lokasi::all(),
        ]);
    }
}