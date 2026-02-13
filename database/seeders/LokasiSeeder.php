<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lokasi;
use App\Models\Kategori;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = Kategori::all();

        if ($kategori->count() == 0) {
            $this->command->info('Kategori belum ada, jalankan KategoriSeeder dulu.');
            return;
        }

        for ($i = 1; $i <= 20; $i++) {

            $randomKategori = $kategori->random();

            Lokasi::create([
                'kode_rak' => 'RAK-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'id_kategori' => $randomKategori->id,
                'keterangan' => 'Rak untuk kategori ' . $randomKategori->nama_kategori,
            ]);
        }
    }
}