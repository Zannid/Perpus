<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Buku;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Kategori::truncate();
        Lokasi::truncate();
        Buku::truncate();
        Schema::enableForeignKeyConstraints();

        $this->call([
            UserTableSeeder::class,
        ]);

        // 1. Seed Kategoris
        $sains = Kategori::create(['nama_kategori' => 'Sains', 'keterangan' => 'Ilmu Pengetahuan Alam']);
        $tekno = Kategori::create(['nama_kategori' => 'Teknologi', 'keterangan' => 'Teknologi Informasi']);
        $fiksi = Kategori::create(['nama_kategori' => 'Fiksi', 'keterangan' => 'Novel dan Cerita']);

        // 2. Seed Lokasis
        $lok1 = Lokasi::create(['kode_rak' => 'RAK-S01', 'id_kategori' => $sains->id, 'keterangan' => 'Lantai 1']);
        $lok2 = Lokasi::create(['kode_rak' => 'RAK-T01', 'id_kategori' => $tekno->id, 'keterangan' => 'Lantai 1']);
        $lok3 = Lokasi::create(['kode_rak' => 'RAK-F01', 'id_kategori' => $fiksi->id, 'keterangan' => 'Lantai 2']);

        // 3. Seed Bukus
        Buku::create([
            'kode_buku' => 'BK-001',
            'judul' => 'Dasar Fisika',
            'penulis' => 'Albert',
            'penerbit' => 'Sains Press',
            'tahun_terbit' => 2020,
            'id_kategori' => $sains->id,
            'id_lokasi' => $lok1->id,
            'stok' => 10,
            'deskripsi' => 'Buku fisika'
        ]);

        Buku::create([
            'kode_buku' => 'BK-002',
            'judul' => 'Laravel Mastery',
            'penulis' => 'Taylor',
            'penerbit' => 'Web Dev',
            'tahun_terbit' => 2022,
            'id_kategori' => $tekno->id,
            'id_lokasi' => $lok2->id,
            'stok' => 5,
            'deskripsi' => 'Buku Laravel'
        ]);

        Buku::create([
            'kode_buku' => 'BK-003',
            'judul' => 'Harry Potter',
            'penulis' => 'JK Rowling',
            'penerbit' => 'Gramedia',
            'tahun_terbit' => 2010,
            'id_kategori' => $fiksi->id,
            'id_lokasi' => $lok3->id,
            'stok' => 15,
            'deskripsi' => 'Novel sihir'
        ]);
    }
}
