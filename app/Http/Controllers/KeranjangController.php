<?php
namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Keranjang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeranjangController extends Controller
{
    // Tampilkan keranjang
    public function index()
    {
        $cart = Keranjang::with('buku')->where('user_id', auth()->id())->get();
        return view('keranjang.index', compact('cart'));
    }

    // Tambah buku ke keranjang
    public function tambah($id)
    {
        $buku      = Buku::findOrFail($id);
        $keranjang = Keranjang::firstOrCreate(
            ['user_id' => auth()->id(), 'buku_id' => $id],
            ['jumlah' => 0]
        );

        if ($keranjang->jumlah + 1 > $buku->stok) {
            return back()->with('error', 'Stok buku "' . $buku->judul . '" tidak mencukupi.');
        }

        $keranjang->increment('jumlah');

        return back()->with('success', 'Buku berhasil ditambahkan ke keranjang.');
    }

    // Kurangi jumlah buku
    public function kurang($id)
    {
        $keranjang = Keranjang::where('user_id', auth()->id())->where('buku_id', $id)->first();

        if ($keranjang) {
            if ($keranjang->jumlah > 1) {
                $keranjang->decrement('jumlah');
            } else {
                $keranjang->delete();
            }

        }

        return back()->with('success', 'Keranjang diperbarui.');
    }

    // Hapus buku dari keranjang
    public function hapus($id)
    {
        Keranjang::where('user_id', auth()->id())->where('buku_id', $id)->delete();
        return back()->with('success', 'Buku dihapus dari keranjang.');
    }

    // Submit peminjaman (multiple)
    public function submit()
    {
        $cart = Keranjang::with('buku')->where('user_id', auth()->id())->get();
        if ($cart->isEmpty()) {
            return back()->with('error', 'Keranjang kosong.');
        }

        DB::transaction(function () use ($cart) {
            foreach ($cart as $item) {
                if (! $item->buku) {
                    throw new \Exception('Buku tidak ditemukan.');
                }

                if ($item->jumlah > $item->buku->stok) {
                    throw new \Exception('Stok buku "' . $item->buku->judul . '" tidak mencukupi.');
                }
            }

            // Generate kode peminjaman
            $lastId = Peminjaman::max('id') ?? 0;
            $kode   = 'PMJ-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

            $peminjaman = Peminjaman::create([
                'kode_peminjaman'    => $kode,
                'id_user'            => auth()->id(),
                'jumlah_keseluruhan' => $cart->sum('jumlah'),
                'tgl_pinjam'         => null,
                'tenggat'            => null,
                'status'             => 'Pending',
            ]);

            foreach ($cart as $item) {
                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'buku_id'       => $item->buku_id,
                    'jumlah'        => $item->jumlah,
                ]);
            }

            // Kosongkan keranjang
            Keranjang::where('user_id', auth()->id())->delete();
        });

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil diajukan.');
    }
    public function tambahAjax(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        $keranjang = Keranjang::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'buku_id' => $buku->id,
            ],
            ['jumlah' => 0]
        );

        if ($keranjang->jumlah + 1 > $buku->stok) {
            return response()->json([
                'success' => false,
                'message' => 'Stok buku "' . $buku->judul . '" tidak mencukupi.',
            ]);
        }

        $keranjang->increment('jumlah');

        $totalItems = Keranjang::where('user_id', auth()->id())->sum('jumlah');

        $keranjangItems = Keranjang::with('buku')->where('user_id', auth()->id())->get();
        $distinctBooks  = $keranjangItems->count();

        return response()->json([
            'success'     => true,
            'message'     => 'Buku berhasil ditambahkan.',
            'totalItems'  => $distinctBooks,
            // Kirim HTML baru untuk isi dropdown
            'htmlContent' => view('components.cart-items-partial', [
                'cartItems'     => $keranjangItems,
                'totalQuantity' => $totalItems,
                'distinctBooks' => $distinctBooks,
            ])->render(),
        ]);
    }

    /**
     * Update quantity item di keranjang via AJAX
     */
    public function updateQuantity(Request $request)
    {
        try {
            $request->validate([
                'cart_id'  => 'required|integer',
                'quantity' => 'required|integer|min:1',
            ]);

            $cartItem = Keranjang::where('id', $request->cart_id)
                ->where('user_id', auth()->id())
                ->with('buku')
                ->first();

            if (! $cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan di keranjang',
                ], 404);
            }

            // Validasi stok
            if ($request->quantity > $cartItem->buku->stok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah melebihi stok yang tersedia',
                ], 400);
            }

            // Update quantity
            $cartItem->jumlah = $request->quantity;
            $cartItem->save();

            // Get updated cart data
            $cartItems = Keranjang::with('buku')
                ->where('user_id', auth()->id())
                ->get();

            $totalItems    = $cartItems->count();
            $totalQuantity = $cartItems->sum('jumlah');

            // Render partial view untuk dropdown
            $html = view('components.cart-items-partial', [
                'cartItems'     => $cartItems,
                'totalQuantity' => $totalQuantity,
            ])->render();

            return response()->json([
                'success'       => true,
                'message'       => 'Quantity berhasil diupdate',
                'totalItems'    => $totalItems,
                'totalQuantity' => $totalQuantity,
                'html'          => $html,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove item dari keranjang via AJAX
     */
    public function removeItem(Request $request)
    {
        try {
            $request->validate([
                'cart_id' => 'required|integer',
            ]);

            $cartItem = Keranjang::where('id', $request->cart_id)
                ->where('user_id', auth()->id())
                ->first();

            if (! $cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan di keranjang',
                ], 404);
            }

            $cartItem->delete();

            // Get updated cart data
            $cartItems = Keranjang::with('buku')
                ->where('user_id', auth()->id())
                ->get();

            $totalItems    = $cartItems->count();
            $totalQuantity = $cartItems->sum('jumlah');

            // Render partial view untuk dropdown
            $html = view('components.cart-items-partial', [
                'cartItems'     => $cartItems,
                'totalQuantity' => $totalQuantity,
            ])->render();

            return response()->json([
                'success'       => true,
                'message'       => 'Item berhasil dihapus dari keranjang',
                'totalItems'    => $totalItems,
                'totalQuantity' => $totalQuantity,
                'html'          => $html,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get cart info via AJAX
     */
    public function getCartInfo(Request $request)
    {
        try {
            $cartItems = Keranjang::with('buku')
                ->where('user_id', auth()->id())
                ->get();

            $totalItems    = $cartItems->count();
            $totalQuantity = $cartItems->sum('jumlah');

            $html = view('components.cart-items-partial', [
                'cartItems'     => $cartItems,
                'totalQuantity' => $totalQuantity,
            ])->render();

            return response()->json([
                'success'       => true,
                'totalItems'    => $totalItems,
                'totalQuantity' => $totalQuantity,
                'html'          => $html,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
