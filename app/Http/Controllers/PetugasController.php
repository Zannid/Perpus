<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'petugas');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $petugas = $query->orderBy('id', 'desc')->get();

        return view('petugas.index', compact('petugas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('petugas._form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $petugas            = new User();
        $petugas->name      = $request->name;
        $petugas->email     = $request->email;
        $petugas->password  = Hash::make($request->password);
        $petugas->role      = 'petugas';
        $petugas->no_telpon = $request->no_telpon;
        $petugas->alamat    = $request->alamat;

        // Auto-generate kode user
        $petugas->kode_user = $this->generateKodeUser();

        if ($request->hasFile('foto')) {
            $img  = $request->file('foto');
            $name = rand(1000, 9999) . $img->getClientOriginalName();
            $img->move('storage/petugas', $name);
            $petugas->foto = $name;
        }
        $petugas->save();

        return redirect()->route('petugas.index')->with('success', 'Data Berhasil Ditambahkan');
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
        $petugas = User::findOrFail($id);
        return view('petugas._form', compact('petugas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $petugas            = User::findOrFail($id);
        $petugas->name      = $request->name;
        $petugas->email     = $request->email;
        $petugas->no_telpon = $request->no_telpon;
        $petugas->alamat    = $request->alamat;

        if ($request->password) {
            $petugas->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($petugas->foto && file_exists(public_path('storage/petugas/' . $petugas->foto))) {
                unlink(public_path('storage/petugas/' . $petugas->foto));
            }

            $img  = $request->file('foto');
            $name = time() . '_' . $img->getClientOriginalName();
            $img->move(public_path('storage/petugas'), $name);
            $petugas->foto = $name;
        }
        $petugas->save();

        return redirect()->route('petugas.index')->with('success', 'Data Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $petugas = User::findOrFail($id);
        $petugas->delete();

        return redirect()->route('petugas.index')->with('success', 'Data Berhasil Dihapus');
    }

    /**
     * Generate unique user code
     */
    private function generateKodeUser()
    {
        $prefix    = 'USR';
        $timestamp = time();
        $randomNum = rand(100, 999);

        $kodeUser = $prefix . '-' . $timestamp . '-' . $randomNum;

        // Ensure uniqueness
        while (User::where('kode_user', $kodeUser)->exists()) {
            $randomNum = rand(100, 999);
            $kodeUser  = $prefix . '-' . $timestamp . '-' . $randomNum;
        }

        return $kodeUser;
    }
}
