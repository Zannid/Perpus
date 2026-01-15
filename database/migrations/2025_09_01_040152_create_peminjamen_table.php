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
        Schema::create('peminjamen', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman')->unique();
            $table->unsignedBigInteger('jumlah_keseluruhan')->default(0);
            $table->date('tgl_pinjam')->nullable();
            $table->date('tenggat')->nullable();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->string('status');
            $table->text('alasan_tolak')->nullable();
            $table->enum('kondisi', ['Bagus', 'Rusak', 'Hilang'])->nullable();
            $table->integer('denda')->default(0);
            $table->boolean('status_baca')->default(false); 
            $table->timestamps();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }

};
