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
        
        // Refresh data buku dari database untuk memastikan stok terbaru
        foreach ($cart as $id => &$item) {
            $buku = Buku::find($id);
            if ($buku) {
                $item['stok'] = $buku->stok;
                $item['foto'] = $buku->foto;
                $item['kode_buku'] = $buku->kode_buku;
            }
        }
        session(['cart' => $cart]);

        return view('keranjang.index', compact('cart'));
    }

    public function tambah($id)
    {
        $buku = Buku::findOrFail($id);
        $cart = session('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['jumlah'] < $buku->stok) {
                $cart[$id]['jumlah']++;
            } else {
                return back()->with('error', 'Stok buku "' . $buku->judul . '" tidak mencukupi untuk ditambah lagi.');
            }
        } else {
            if ($buku->stok > 0) {
                $cart[$id] = [
                    'id_buku'   => $buku->id,
                    'judul'     => $buku->judul,
                    'kode_buku' => $buku->kode_buku,
                    'foto'      => $buku->foto,
                    'jumlah'    => 1,
                    'stok'      => $buku->stok
                ];
            } else {
                return back()->with('error', 'Stok buku "' . $buku->judul . '" kosong.');
            }
        }

        session(['cart' => $cart]);
        return back()->with('success', 'Buku berhasil ditambahkan ke keranjang.');
    }

    public function kurang($id)
    {
        $cart = session('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['jumlah'] > 1) {
                $cart[$id]['jumlah']--;
            } else {
                unset($cart[$id]);
            }
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Jumlah buku berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Buku dihapus dari keranjang.');
    }

    public function submit()
    {
        $cart = session('cart', []);

        if (!$cart) {
            return back()->with('error', 'Keranjang kosong.');
        }

        // Cek kembali ketersediaan stok sebelum submit final
        foreach ($cart as $id => $item) {
            $buku = Buku::find($id);
            if (!$buku || $buku->stok < $item['jumlah']) {
                return back()->with('error', 'Maaf, stok "' . ($buku->judul ?? 'Buku') . '" sudah berubah atau tidak mencukupi. Silakan cek keranjang Anda.');
            }
        }

        // 1️⃣ Buat peminjaman
        $nextPeminjamanId = Peminjaman::max('id') + 1;
        $kodePeminjaman = 'PMJ-' . $nextPeminjamanId;

        $peminjaman = Peminjaman::create([
            'kode_peminjaman'    => $kodePeminjaman,
            'id_user'            => auth()->id(),
            'jumlah_keseluruhan' => array_sum(array_column($cart, 'jumlah')),
            'tgl_pinjam'         => null, // Menunggu approve petugas
            'tenggat'            => null, // Diisi saat approve
            'status'             => 'Pending',
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
            ->with('success', 'Peminjaman berhasil diajukan! Menunggu verifikasi petugas.');
    }
}
