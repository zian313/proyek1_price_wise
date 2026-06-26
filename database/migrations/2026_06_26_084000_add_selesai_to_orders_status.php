<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, alter enum by running raw statement
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `orders` MODIFY `status` ENUM('menunggu_pembayaran','menunggu_verifikasi','lunas','dibatalkan','selesai') NOT NULL DEFAULT 'menunggu_pembayaran';");
        } else {
            Schema::table('orders', function (Blueprint $table) {
                // Fallback: make it a string (safe) then you can later refine
                $table->string('status')->default('menunggu_pembayaran')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `orders` MODIFY `status` ENUM('menunggu_pembayaran','menunggu_verifikasi','lunas','dibatalkan') NOT NULL DEFAULT 'menunggu_pembayaran';");
        } else {
            Schema::table('orders', function (Blueprint $table) {
                $table->enum('status', ['menunggu_pembayaran','menunggu_verifikasi','lunas','dibatalkan'])->default('menunggu_pembayaran')->change();
            });
        }
    }
};
