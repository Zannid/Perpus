<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        
        $query = Buku::with('kategori');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                  ->orWhere('penulis', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }
        
        $sort = $request->get('sort', 'terbaru');
        switch ($sort) {
            case 'terlama':
                $query->oldest();
                break;
            case 'judul_az':
                $query->orderBy('judul', 'asc');
                break;
            case 'judul_za':
                $query->orderBy('judul', 'desc');
                break;
            case 'penulis_az':
                $query->orderBy('penulis', 'asc');
                break;
            default: 
                $query->latest();
                break;
        }
        
        $bukus = $query->paginate(12)->withQueryString();
        
        return view('katalog.index', compact('bukus', 'kategoris'));
    }
}