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
            if (!Schema::hasColumn('orders', 'barang_dikirim')) {
                $table->boolean('barang_dikirim')->default(false)->after('status');
            }
            if (!Schema::hasColumn('orders', 'tanggal_dikirim')) {
                $table->timestamp('tanggal_dikirim')->nullable()->after('barang_dikirim');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'barang_dikirim')) {
                $table->dropColumn('barang_dikirim');
            }
            if (Schema::hasColumn('orders', 'tanggal_dikirim')) {
                $table->dropColumn('tanggal_dikirim');
            }
        });
    }
};
