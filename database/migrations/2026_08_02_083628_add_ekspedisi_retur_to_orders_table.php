<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Jenis ekspedisi pengembalian barang retur oleh buyer (contoh: JNE, J&T, SiCepat, GoSend, dll)
            $table->string('ekspedisi_retur')->nullable()->after('namarek_refund');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('ekspedisi_retur');
        });
    }
};
