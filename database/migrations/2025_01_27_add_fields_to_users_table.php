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
        Schema::table('users', function (Blueprint $table) {
            $table->string('kode_user')->unique()->nullable()->after('role');
            $table->string('no_telpon')->nullable()->after('kode_user');
            $table->text('alamat')->nullable()->after('no_telpon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kode_user', 'no_telpon', 'alamat']);
        });
    }
};
