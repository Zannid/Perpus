<?php
namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $lokasi = Lokasi::all();

        if ($lokasi->count() == 0) {
            $this->command->info('Lokasi belum ada.');
            return;
        }

        // Ambil kategori berdasarkan nama
        $kategoriTeknologi  = Kategori::where('nama_kategori', 'Teknologi')->first();
        $kategoriNovel      = Kategori::where('nama_kategori', 'Novel')->first();
        $kategoriAgama      = Kategori::where('nama_kategori', 'Agama')->first();
        $kategoriBisnis     = Kategori::where('nama_kategori', 'Bisnis')->first();
        $kategoriSains      = Kategori::where('nama_kategori', 'Sains')->first();
        $kategoriPendidikan = Kategori::where('nama_kategori', 'Pendidikan')->first();

        $books = [

            // ================= TEKNOLOGI =================
            ['Clean Code', 'Robert C. Martin', $kategoriTeknologi],
            ['The Pragmatic Programmer', 'Andrew Hunt', $kategoriTeknologi],
            ['Design Patterns', 'Erich Gamma', $kategoriTeknologi],
            ['Refactoring', 'Martin Fowler', $kategoriTeknologi],
            ['Laravel Up & Running', 'Matt Stauffer', $kategoriTeknologi],
            ['Flutter for Beginners', 'Alessandro Biessek', $kategoriTeknologi],
            ['Algoritma dan Pemrograman', 'Rinaldi Munir', $kategoriTeknologi],
            ['Database System Concepts', 'Abraham Silberschatz', $kategoriTeknologi],
            ['Introduction to Algorithms', 'Thomas H. Cormen', $kategoriTeknologi],

            // ================= NOVEL =================
            ['Laskar Pelangi', 'Andrea Hirata', $kategoriNovel],
            ['Bumi Manusia', 'Pramoedya Ananta Toer', $kategoriNovel],
            ['Negeri 5 Menara', 'Ahmad Fuadi', $kategoriNovel],
            ['Dilan 1990', 'Pidi Baiq', $kategoriNovel],
            ['Ayat-Ayat Cinta', 'Habiburrahman El Shirazy', $kategoriNovel],
            ['Harry Potter and the Sorcerer\'s Stone', 'J.K. Rowling', $kategoriNovel],
            ['Harry Potter and the Chamber of Secrets', 'J.K. Rowling', $kategoriNovel],
            ['The Hobbit', 'J.R.R. Tolkien', $kategoriNovel],
            ['The Lord of the Rings', 'J.R.R. Tolkien', $kategoriNovel],
            ['To Kill a Mockingbird', 'Harper Lee', $kategoriNovel],
            ['1984', 'George Orwell', $kategoriNovel],

            // ================= BISNIS =================
            ['Rich Dad Poor Dad', 'Robert T. Kiyosaki', $kategoriBisnis],
            ['The Psychology of Money', 'Morgan Housel', $kategoriBisnis],
            ['Think and Grow Rich', 'Napoleon Hill', $kategoriBisnis],
            ['The Lean Startup', 'Eric Ries', $kategoriBisnis],
            ['Start With Why', 'Simon Sinek', $kategoriBisnis],
            ['Zero to One', 'Peter Thiel', $kategoriBisnis],
            ['Deep Work', 'Cal Newport', $kategoriBisnis],
            ['Grit', 'Angela Duckworth', $kategoriBisnis],
            ['Atomic Habits', 'James Clear', $kategoriBisnis],
            ['The Subtle Art of Not Giving a F*ck', 'Mark Manson', $kategoriBisnis],

            // ================= AGAMA =================
            ['Fiqih Sunnah', 'Sayyid Sabiq', $kategoriAgama],
            ['Tafsir Ibnu Katsir', 'Ibnu Katsir', $kategoriAgama],
            ['Sejarah Peradaban Islam', 'Badri Yatim', $kategoriAgama],

            // ================= SAINS =================
            ['Sapiens', 'Yuval Noah Harari', $kategoriSains],
            ['Homo Deus', 'Yuval Noah Harari', $kategoriSains],
            ['Fisika Dasar', 'Halliday & Resnick', $kategoriSains],
            ['Kimia Dasar', 'Raymond Chang', $kategoriSains],
            ['Biologi Campbell', 'Neil A. Campbell', $kategoriSains],

            // ================= PENDIDIKAN =================
            ['Matematika Dasar', 'Purcell', $kategoriPendidikan],
        ];

        $no = 1;

        foreach ($books as $book) {

            if (! $book[2]) {
                continue;
            }
            // skip kalau kategori tidak ditemukan

            Buku::create([
                'kode_buku'    => 'BK-' . str_pad($no++, 4, '0', STR_PAD_LEFT),
                'judul'        => $book[0],
                'penulis'      => $book[1],
                'penerbit'     => 'Gramedia',
                'tahun_terbit' => rand(1990, 2023),
                'id_kategori'  => $book[2]->id,
                'foto'         => 'default.jpg',
                'id_lokasi'    => $lokasi->random()->id,
                'stok'         => rand(3, 15),
                'deskripsi'    => 'Buku berjudul ' . $book[0] . ' karya ' . $book[1],
                'rating_avg'   => rand(3, 5),
                'rating_count' => rand(10, 200),
            ]);
        }
    }
}
