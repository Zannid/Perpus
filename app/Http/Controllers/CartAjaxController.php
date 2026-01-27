<?php
namespace App\Http\Controllers;

use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartAjaxController extends Controller
{
    /**
     * Get cart index dengan data JSON (untuk halaman keranjang)
     */
    public function index(Request $request)
    {
        try {
            $cartItems = Keranjang::with('buku')
                ->where('user_id', Auth::id())
                ->get();

            $totalItems    = $cartItems->count();
            $totalQuantity = $cartItems->sum('jumlah');

            // Jika request adalah AJAX, return JSON
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success'       => true,
                    'cartItems'     => $cartItems,
                    'totalItems'    => $totalItems,
                    'totalQuantity' => $totalQuantity,
                ]);
            }

            // Jika bukan AJAX, render view seperti KeranjangController
            return view('keranjang.index', [
                'cart' => $cartItems,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
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
                ->where('user_id', Auth::id())
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
                ->where('user_id', Auth::id())
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
                ->where('user_id', Auth::id())
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
                ->where('user_id', Auth::id())
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
     * Add item to cart via AJAX (untuk dipanggil dari katalog/detail buku)
     */
    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'buku_id'  => 'required|integer',
                'quantity' => 'integer|min:1',
            ]);

            $quantity = $request->quantity ?? 1;
            $bukuId   = $request->buku_id;

            // Check if item already exists in cart
            $existingCart = Keranjang::where('user_id', Auth::id())
                ->where('buku_id', $bukuId)
                ->first();

            if ($existingCart) {
                // Update quantity
                $newQuantity = $existingCart->jumlah + $quantity;

                // Validate stock
                if ($newQuantity > $existingCart->buku->stok) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jumlah total melebihi stok yang tersedia',
                    ], 400);
                }

                $existingCart->jumlah = $newQuantity;
                $existingCart->save();
                $message = 'Jumlah buku di keranjang berhasil ditambah';
            } else {
                // Create new cart item
                Keranjang::create([
                    'user_id' => Auth::id(),
                    'buku_id' => $bukuId,
                    'jumlah'  => $quantity,
                ]);
                $message = 'Buku berhasil ditambahkan ke keranjang';
            }

            // Get updated cart data
            $cartItems = Keranjang::with('buku')
                ->where('user_id', Auth::id())
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
                'message'       => $message,
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
