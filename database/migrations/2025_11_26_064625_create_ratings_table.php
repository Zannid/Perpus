<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hanya buat tabel jika belum ada
        if (! Schema::hasTable('ratings')) {
            Schema::create('ratings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('buku_id');
                $table->unsignedBigInteger('peminjaman_id')->nullable();
                $table->tinyInteger('rating')->comment('1-5');
                $table->text('review')->nullable();
                $table->timestamps();

                // foreign keys
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('buku_id')->references('id')->on('bukus')->onDelete('cascade');
                $table->foreign('peminjaman_id')->references('id')->on('peminjamen')->onDelete('set null');

                // user hanya boleh rating 1x per buku
                $table->unique(['user_id', 'buku_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
