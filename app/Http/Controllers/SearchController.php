<?php
namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class SearchController extends Controller
{
public function index(Request $request)
{
    $query = $request->input('q');

    $buku = Buku::where('judul', 'like', "%{$query}%")
                ->orWhere('penulis', 'like', "%{$query}%")
                ->get();

    $kategori = Kategori::where('nama_kategori', 'like', "%{$query}%")->get();

    $lokasi   = Lokasi::where('keterangan', 'like', "%{$query}%")->get();

    return view('search.results', compact('query', 'buku', 'kategori', 'lokasi'));
}

}
