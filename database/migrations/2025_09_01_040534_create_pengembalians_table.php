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
        Schema::create('pengembalians', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('id_peminjaman');
        $table->unsignedBigInteger('id_user')->nullable();
        $table->unsignedBigInteger('id_buku');
        $table->date('tgl_pinjam');
        $table->date('tenggat');
        $table->date('tgl_kembali');
        $table->integer('jumlah');
        $table->integer('denda')->default(0);
        $table->enum('kondisi', ['Bagus', 'Rusak', 'Hilang'])->nullable();
        $table->timestamps();

        $table->foreign('id_peminjaman')->references('id')->on('peminjamen')->onDelete('cascade');
        $table->foreign('id_user')->references('id')->on('users');
        $table->foreign('id_buku')->references('id')->on('bukus');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalians');
    }
};
