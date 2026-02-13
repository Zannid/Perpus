<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama_kategori' => 'Teknologi',
                'keterangan' => 'Buku seputar teknologi dan pemrograman'
            ],
            [
                'nama_kategori' => 'Pendidikan',
                'keterangan' => 'Buku pembelajaran dan pendidikan'
            ],
            [
                'nama_kategori' => 'Agama',
                'keterangan' => 'Buku keagamaan dan spiritual'
            ],
            [
                'nama_kategori' => 'Sejarah',
                'keterangan' => 'Buku tentang sejarah dunia dan Indonesia'
            ],
            [
                'nama_kategori' => 'Sains',
                'keterangan' => 'Buku ilmu pengetahuan dan sains'
            ],
            [
                'nama_kategori' => 'Novel',
                'keterangan' => 'Buku cerita fiksi dan novel'
            ],
            [
                'nama_kategori' => 'Komik',
                'keterangan' => 'Buku komik dan cerita bergambar'
            ],
            [
                'nama_kategori' => 'Bisnis',
                'keterangan' => 'Buku bisnis dan kewirausahaan'
            ],
            [
                'nama_kategori' => 'Kesehatan',
                'keterangan' => 'Buku tentang kesehatan dan medis'
            ],
            [
                'nama_kategori' => 'Motivasi',
                'keterangan' => 'Buku pengembangan diri dan motivasi'
            ],
        ];

        foreach ($data as $item) {
            Kategori::create($item);
        }
    }
}