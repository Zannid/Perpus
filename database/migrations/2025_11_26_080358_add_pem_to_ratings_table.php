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
        Schema::table('ratings', function (Blueprint $table) {
            if (! Schema::hasColumn('ratings', 'peminjaman_id')) {
                $table->unsignedBigInteger('peminjaman_id')->nullable();
                $table->foreign('peminjaman_id')->references('id')->on('peminjamen')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            //
        });
    }
};
