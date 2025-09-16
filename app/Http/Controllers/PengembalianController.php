<?php

use App\Models\Pengembalian;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query dasar
        $query = Pengembalian::with(['peminjaman', 'user', 'buku']);

        // Jika ada keyword, filter
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                })
                    ->orWhereHas('buku', function ($q) use ($search) {
                        $q->where('judul', 'LIKE', "%$search%");
                    })
                    ->orWhere('kondisi', 'LIKE', "%$search%");
            });
        }

        // Eksekusi query dengan pagination
        $pengembalian = $query->orderByDesc('id')->paginate(5);

        return view('pengembalian.index', compact('pengembalian', 'search'));
    }
}
