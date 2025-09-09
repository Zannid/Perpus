<?php
namespace App\Http\Controllers;

use App\Models\Pengembalian;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalian = Pengembalian::with('user', 'buku')->latest()->get();

        return view('pengembalian.index', compact('pengembalian'));
    }
}
