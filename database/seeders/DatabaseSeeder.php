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
            // UserTableSeeder::class,
            KategoriSeeder::class,
            LokasiSeeder::class,
            BukuSeeder::class,
        ]);

        // 1. Seed Kategoris
        // 3. Seed Bukus

    }
}
