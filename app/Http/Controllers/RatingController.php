<?php
namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Tampilkan halaman rating untuk multiple books
     */
    public function show()
    {
        // Ambil semua detail peminjaman dari peminjaman yang sudah dikembalikan
        $detailPeminjamanKembali = \App\Models\DetailPeminjaman::whereHas('peminjaman', function ($query) {
            $query->where('id_user', auth()->id())
                ->where('status', 'Kembali');
        })
            ->with(['peminjaman', 'buku.kategori'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Kelompokkan yang sudah dan belum di-rating
        $detailBelumDirating = [];
        $detailSudahDirating = [];

        foreach ($detailPeminjamanKembali as $detail) {
            $existingRating = Rating::where('user_id', auth()->id())
                ->where('buku_id', $detail->buku_id)
                ->where('peminjaman_id', $detail->peminjaman_id)
                ->first();

            if ($existingRating) {
                $detail->existingRating = $existingRating;
                $detailSudahDirating[]  = $detail;
            } else {
                $detailBelumDirating[] = $detail;
            }
        }

        if (empty($detailBelumDirating) && empty($detailSudahDirating)) {
            return redirect()->route('peminjaman.index')
                ->with('info', 'Tidak ada peminjaman yang sudah dikembalikan.');
        }

        return view('peminjaman.rating_list', compact('detailBelumDirating', 'detailSudahDirating'));
    }

    /**
     * Simpan atau update rating
     */
    public function store(Request $request)
    {
        \Log::info('Rating store request', $request->all());

        try {
            $validated = $request->validate([
                'peminjaman_id' => 'required|exists:peminjamen,id',
                'buku_id'       => 'required|exists:bukus,id',
                'rating'        => 'required|integer|min:1|max:5',
                'ulasan'        => 'nullable|string|max:500',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in rating store', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        }

        // Cek peminjaman ada dan user adalah pemiliknya dan statusnya Kembali
        $peminjaman = Peminjaman::where('id', $request->peminjaman_id)
            ->where('id_user', auth()->id())
            ->where('status', 'Kembali')
            ->first();

        if (! $peminjaman) {
            return back()->with('error', 'Peminjaman tidak ditemukan atau Anda belum mengembalikan buku.')->withInput();
        }

        // Cek apakah buku ada di detail peminjaman ini
        $detailExists = \App\Models\DetailPeminjaman::where('peminjaman_id', $request->peminjaman_id)
            ->where('buku_id', $request->buku_id)
            ->exists();

        if (! $detailExists) {
            return back()->with('error', 'Buku tidak ditemukan dalam peminjaman ini.')->withInput();
        }

        // Cek apakah sudah ada rating untuk peminjaman dan buku ini
        $existingRating = Rating::where('user_id', auth()->id())
            ->where('buku_id', $request->buku_id)
            ->where('peminjaman_id', $request->peminjaman_id)
            ->first();

        if ($existingRating) {
            // Update rating
            $existingRating->update([
                'rating' => $request->rating,
                'review' => $request->ulasan,
            ]);

            $this->updateBookAverageRating($request->buku_id);

            return redirect()->route('rating.show')
                ->with('success', 'Rating Anda berhasil diperbarui!');
        }

        // Create new rating
        try {
            \Log::info('About to create rating with data:', [
                'user_id'       => auth()->id(),
                'buku_id'       => $request->buku_id,
                'peminjaman_id' => $request->peminjaman_id,
                'rating'        => $request->rating,
                'review'        => $request->ulasan,
            ]);

            Rating::create([
                'user_id'       => auth()->id(),
                'buku_id'       => $request->buku_id,
                'peminjaman_id' => $request->peminjaman_id,
                'rating'        => $request->rating,
                'review'        => $request->ulasan,
            ]);

            \Log::info('Rating created successfully');

            $this->updateBookAverageRating($request->buku_id);

            return redirect()->route('rating.show')
                ->with('success', 'Terima kasih atas rating Anda!');
        } catch (\Exception $e) {
            \Log::error('Rating creation failed: ' . $e->getMessage(), [
                'user_id'       => auth()->id(),
                'buku_id'       => $request->buku_id,
                'peminjaman_id' => $request->peminjaman_id,
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menyimpan rating: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update rating via modal/ajax
     */
    public function update(Request $request, $id)
    {
        $rating = Rating::findOrFail($id);

        // Cek ownership
        if ($rating->user_id != auth()->id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengubah rating ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:500',
        ]);

        $rating->update([
            'rating' => $request->rating,
            'review' => $request->ulasan,
        ]);

        $this->updateBookAverageRating($rating->buku_id);

        return redirect()->route('rating.show')
            ->with('success', 'Rating Anda berhasil diperbarui!');
    }

    /**
     * Hapus rating
     */
    public function destroy($id)
    {
        $rating = Rating::findOrFail($id);

        // Cek ownership
        if ($rating->user_id != auth()->id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus rating ini.');
        }

        $bukuId = $rating->buku_id;
        $rating->delete();

        $this->updateBookAverageRating($bukuId);

        return redirect()->route('peminjaman.rating.show')
            ->with('success', 'Rating Anda berhasil dihapus!');
    }

    /**
     * Update rating book average
     */
    private function updateBookAverageRating($bukuId)
    {
        $avgRating   = Rating::where('buku_id', $bukuId)->avg('rating');
        $totalRating = Rating::where('buku_id', $bukuId)->count();

        Buku::where('id', $bukuId)->update([
            'rating_avg'   => $avgRating ? round($avgRating, 1) : 0,
            'rating_count' => $totalRating,
        ]);
    }
}
