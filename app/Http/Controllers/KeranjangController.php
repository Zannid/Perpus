<?php
namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;

class KeranjangController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        return view('keranjang.index', compact('cart'));
    }

    public function tambah($id)
    {
        $buku = Buku::findOrFail($id);
        $cart = session('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['jumlah']++;
        } else {
            $cart[$id] = [
                'id_buku' => $buku->id,
                'judul'   => $buku->judul,
                'jumlah'  => 1,
            ];
        }

        session(['cart' => $cart]);
        return back()->with('success', 'Buku ditambahkan ke keranjang');
    }

    public function hapus($id)
    {
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Buku dihapus dari keranjang');
    }

    public function submit()
    {
        $cart = session('cart', []);

        if (! $cart) {
            return back()->with('error', 'Keranjang kosong');
        }

        // 1️⃣ Buat peminjaman
        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PJM-' . time(),
            'id_user'         => auth()->id(),
            'jumlah_keseluruhan'          => array_sum(array_column($cart, 'jumlah')),
            'tgl_pinjam'      => now(),
            'tenggat'         => now()->addDays(7),
            'status'          => 'Pending',
        ]);

        // 2️⃣ Masukkan semua buku ke detail_peminjaman
        foreach ($cart as $item) {
            DetailPeminjaman::create([
                'peminjaman_id' => $peminjaman->id,
                'buku_id'       => $item['id_buku'],
                'jumlah'        => $item['jumlah'],
            ]);
        }

        // 3️⃣ Hapus session keranjang
        session()->forget('cart');

        return redirect()->route('peminjaman.index')
            ->with('lsuccess', 'Peminjaman berhasil diajukan');
    }
}
