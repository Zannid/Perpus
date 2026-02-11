<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Buku::all());
        return response()->json($books)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $buku = Buku::create($request->all());
        return response()->json($buku, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $buku = Buku::with(['kategori', 'lokasi'])->find($id);
        if ($buku) {
            return response()->json([
                'success' => true,
                'data'    => $buku,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Buku not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $buku = Buku::find($id);
        if ($buku) {
            $buku->update($request->all());
            return response()->json($buku);
        } else {
            return response()->json(['message' => 'Buku not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $buku = Buku::find($id);
        if ($buku) {
            $buku->delete();
            return response()->json(['message' => 'Buku deleted']);
        } else {
            return response()->json(['message' => 'Buku not found'], 404);
        }
    }

    /**
     * Get latest books
     */
    public function latest()
    {
        $books = Buku::with(['kategori', 'lokasi'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $books,
        ]);
    }

    /**
     * Search books
     */
    public function search(Request $request)
    {
        $query    = $request->get('q', '');
        $category = $request->get('category', '');

        $books = Buku::with(['kategori', 'lokasi'])
            ->where('judul', 'like', '%' . $query . '%')
            ->orWhere('pengarang', 'like', '%' . $query . '%')
            ->orWhere('penerbit', 'like', '%' . $query . '%');

        if ($category) {
            $books->where('kategori_id', $category);
        }

        $books = $books->get();

        return response()->json([
            'success' => true,
            'data'    => $books,
        ]);
    }

    /**
     * Get book categories
     */
    public function categories()
    {
        $categories = \App\Models\Kategori::all();

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }

    /**
     * Get book bookshelves/locations
     */
    public function bookshelves()
    {
        $locations = \App\Models\Lokasi::all();

        return response()->json([
            'success' => true,
            'data'    => $locations,
        ]);
    }

    /**
     * Get book detail (alias for show)
     */
    public function detail(string $id)
    {
        return $this->show($id);
    }
}
