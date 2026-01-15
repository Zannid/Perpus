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
        Schema::create('perpanjangan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_peminjaman');
            $table->date('tenggat_lama'); 
            $table->date('tenggat_baru'); 
            $table->text('alasan')->nullable(); 
            $table->enum('status', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');
            $table->text('alasan_tolak')->nullable(); 
            $table->unsignedBigInteger('approved_by')->nullable(); 
            $table->timestamp('approved_at')->nullable(); 
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_peminjaman')->references('id')->on('peminjamen')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perpanjangans');
    }
};
