<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeminjamenSeeder extends Seeder
{
    public function run()
    {
        $bukuIds = DB::table('bukus')->pluck('id')->toArray();

        // Jika tidak ada buku, buat dummy buku dulu
        if (empty($bukuIds)) {
            $bukuId = DB::table('bukus')->insertGetId([
                'kode_buku'    => 'BKU-0001',
                'judul'        => 'Dummy Buku',
                'penulis'      => 'Admin',
                'penerbit'     => 'Perpus',
                'tahun_terbit' => 2026,
                'id_kategori'  => 1,
                'id_lokasi'    => 1,
                'stok'         => 10,
                'deskripsi'    => 'Buku dummy',
                'rating_avg'   => 0,
                'rating_count' => 0,
            ]);
            $bukuIds[] = $bukuId;
        }

        // Update semua peminjaman yang id_buku = 0
        DB::table('peminjamen')->where('id_buku', 0)->get()->each(function ($p) use ($bukuIds) {
            DB::table('peminjamen')->where('id', $p->id)->update([
                'id_buku' => $bukuIds[array_rand($bukuIds)],
            ]);
        });
    }
}
