<?php
namespace App\Http\Controllers;

use App\Exports\BukuExport;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;


class BukuController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new BukuExport, 'data-buku.xlsx');
    }

    public function index(Request $request)
    {
        $kategori = Kategori::all();
        $lokasi   = Lokasi::all();

        $query = Buku::query();

        if ($request->get('search')) {
            $search = $request->get('search');
            $query->where('judul', 'LIKE', "%$search%")
                ->orWhereHas('kategori', function ($q) use ($search) {
                    $q->where('nama_kategori', 'LIKE', "%$search%");
                });
        }

        $buku = $query->get();

        return view('buku.index', compact('buku', 'lokasi', 'kategori', 'request'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        $lokasi   = Lokasi::all();
        return view('buku.create', compact('lokasi', 'kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'penulis'      => 'required|string|max:255',
            'penerbit'     => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'id_kategori'  => 'required|exists:kategoris,id',
            'id_lokasi'    => 'required|exists:lokasis,id',
            'stok'         => 'required|integer|min:0',
            'deskripsi'    => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $buku       = new Buku();
        $lastRecord = Buku::latest('id')->first();
        $lastId     = $lastRecord ? $lastRecord->id : 0;
        $kodeBuku   = 'BK-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

        $buku->kode_buku    = $kodeBuku;
        $buku->judul        = $validated['judul'];
        $buku->penulis      = $validated['penulis'];
        $buku->penerbit     = $validated['penerbit'];
        $buku->tahun_terbit = $validated['tahun_terbit'];
        $buku->id_kategori  = $validated['id_kategori'];
        $buku->id_lokasi    = $validated['id_lokasi'];
        $buku->stok         = $validated['stok'];
        $buku->deskripsi    = $validated['deskripsi'] ?? null;

        if ($request->hasFile('foto')) {
            $img  = $request->file('foto');
            $name = rand(1000, 9999) . $img->getClientOriginalName();
            $img->move('storage/buku', $name);
            $buku->foto = $name;
        }

        $buku->save();

        Alert::success('Berhasil', 'Buku berhasil ditambahkan');
        session()->flash('success', 'Buku berhasil ditambahkan');
        return redirect()->route('buku.index');
    }

    public function show(string $id)
    {
        $buku = Buku::findOrFail($id);
        return view('buku.show', compact('buku'));
    }

    public function edit(string $id)
    {
        $buku     = Buku::findOrFail($id);
        $kategori = Kategori::all();
        $lokasi   = Lokasi::all();
        return view('buku.edit', compact('buku', 'lokasi', 'kategori'));
    }

    public function update(Request $request, string $id)
    {
        // ✅ Validasi input
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'penulis'      => 'required|string|max:255',
            'penerbit'     => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'id_kategori'  => 'required|exists:kategoris,id',
            'id_lokasi'    => 'required|exists:lokasis,id',
            'stok'         => 'required|integer|min:0',
            'deskripsi'    => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $buku = Buku::findOrFail($id);

        $buku->judul        = $validated['judul'];
        $buku->penulis      = $validated['penulis'];
        $buku->penerbit     = $validated['penerbit'];
        $buku->tahun_terbit = $validated['tahun_terbit'];
        $buku->id_kategori  = $validated['id_kategori'];
        $buku->id_lokasi    = $validated['id_lokasi'];
        $buku->stok         = $validated['stok'];
        $buku->deskripsi    = $validated['deskripsi'] ?? null;

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

        Alert::success('Berhasil', 'Buku berhasil diperbarui');
        return redirect()->route('buku.index');
    }

    public function destroy(string $id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->foto && file_exists(public_path('storage/buku/' . $buku->foto))) {
            unlink(public_path('storage/buku/' . $buku->foto));
        }

        $buku->delete();

        Alert::success('Berhasil', 'Buku berhasil dihapus');
        return redirect()->route('buku.index');
    }
}
