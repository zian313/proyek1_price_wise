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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'nama')) {
                $table->string('nama')->nullable();
            }
            if (!Schema::hasColumn('orders', 'email')) {
                $table->string('email')->nullable();
            }
            if (!Schema::hasColumn('orders', 'alamat')) {
                $table->text('alamat')->nullable();
            }
            if (!Schema::hasColumn('orders', 'ekspedisi')) {
                $table->string('ekspedisi')->nullable();
            }
            if (!Schema::hasColumn('orders', 'metode_pembayaran')) {
                $table->string('metode_pembayaran')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['nama', 'email', 'alamat', 'ekspedisi', 'metode_pembayaran']);
        });
    }
};
