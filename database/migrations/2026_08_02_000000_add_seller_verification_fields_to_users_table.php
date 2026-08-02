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
            $table->string('nama_ktp')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('selfie_ktp')->nullable();
            $table->enum('seller_status', ['approved', 'pending', 'rejected'])->default('approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nama_ktp', 'alamat_lengkap', 'foto_ktp', 'selfie_ktp', 'seller_status']);
        });
    }
};
