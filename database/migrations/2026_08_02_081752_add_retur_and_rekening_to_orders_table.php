<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Informasi Rekening Refund dari Buyer
            $table->string('bank_refund')->nullable()->after('video_unboxing');
            $table->string('norek_refund')->nullable()->after('bank_refund');
            $table->string('namarek_refund')->nullable()->after('norek_refund');

            // Pengembalian Barang (Retur) oleh Buyer ke Seller
            $table->string('no_resi_retur')->nullable()->after('namarek_refund');
            $table->timestamp('tanggal_retur')->nullable()->after('no_resi_retur');
            $table->boolean('retur_diterima_seller')->default(false)->after('tanggal_retur');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'bank_refund',
                'norek_refund',
                'namarek_refund',
                'no_resi_retur',
                'tanggal_retur',
                'retur_diterima_seller',
            ]);
        });
    }
};
