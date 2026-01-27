<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Logika: Jika peminjaman_id tidak null, maka harus unique per user+buku+peminjaman
     * Jika peminjaman_id null, maka user bisa rate buku multiple kali asalkan dari peminjaman berbeda
     */
    public function up(): void
    {
        // Tidak perlu hapus constraint lama, cukup update logic di aplikasi
        // Validation akan handle ini
        if (! Schema::hasTable('ratings')) {
            return;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed
    }
};
