<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Peminjaman;
use App\Models\Buku;

class RatingController extends Controller
{
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['buku.kategori', 'user'])->findOrFail($id);
        
        if ($peminjaman->status != 'Kembali') {
            return redirect()->route('peminjaman.index')
                             ->with('error', 'Buku belum dikembalikan.');
        }
        
        $existingRating = Rating::where('user_id', auth()->id())
                                ->where('buku_id', $peminjaman->id_buku)
                                ->where('peminjaman_id', $peminjaman->id)
                                ->first();
        
        if ($existingRating) {
            return redirect()->route('peminjaman.index')
                             ->with('info', 'Anda sudah memberikan rating untuk peminjaman ini.');
        }
        
        return view('rating.form', compact('peminjaman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjamen,id',
            'buku_id'       => 'required|exists:bukus,id',
            'rating'        => 'required|integer|min:1|max:5',
            'review'        => 'nullable|string|max:500',
        ]);

        $peminjaman = Peminjaman::where('id', $request->peminjaman_id)
            ->where('id_user', auth()->id())
            ->where('id_buku', $request->buku_id)
            ->where('status', 'Kembali')
            ->first();

        if (!$peminjaman) {
            return redirect()->back()
                             ->with('error', 'Anda hanya bisa memberi rating setelah buku dikembalikan.');
        }

        $existingRating = Rating::where('user_id', auth()->id())
                                ->where('buku_id', $request->buku_id)
                                ->where('peminjaman_id', $request->peminjaman_id)
                                ->first();

        if ($existingRating) {
            return redirect()->route('peminjaman.index')
                             ->with('info', 'Anda sudah memberikan rating untuk peminjaman ini.');
        }

        Rating::create([
            'user_id'       => auth()->id(),
            'buku_id'       => $request->buku_id,
            'peminjaman_id' => $request->peminjaman_id,
            'rating'        => $request->rating,
            'review'        => $request->review,
        ]);

        $this->updateBookAverageRating($request->buku_id);

        return redirect()->route('peminjaman.index')
                         ->with('success', 'Terima kasih atas rating Anda!');
    }

    private function updateBookAverageRating($bukuId)
    {
        $avgRating = Rating::where('buku_id', $bukuId)->avg('rating');
        $totalRating = Rating::where('buku_id', $bukuId)->count();
        
        Buku::where('id', $bukuId)->update([
            'rating_avg' => round($avgRating, 1),
            'rating_count' => $totalRating
        ]);
    }
}