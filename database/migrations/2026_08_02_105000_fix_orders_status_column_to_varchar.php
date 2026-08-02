<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix untuk MariaDB/MySQL karena enum sebelumnya crash saat status baru dimasukkan.
        try {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status VARCHAR(50) DEFAULT 'menunggu_pembayaran'");
        } catch (\Exception $e) {
            // Fallback for non-MySQL databases just in case
            Schema::table('orders', function (Blueprint $table) {
                $table->string('status', 50)->default('menunggu_pembayaran')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('menunggu_pembayaran','menunggu_verifikasi','lunas','dibatalkan','selesai') DEFAULT 'menunggu_pembayaran'");
        } catch (\Exception $e) {
            // Ignored
        }
    }
};
