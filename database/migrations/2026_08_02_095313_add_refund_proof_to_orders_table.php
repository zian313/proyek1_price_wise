<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Bukti transfer refund yang diupload oleh Admin
            $table->string('bukti_transfer_refund')->nullable()->after('norek_refund');
            // Flag: apakah buyer sudah mengkonfirmasi dana refund diterima
            $table->boolean('refund_dikonfirmasi_buyer')->default(false)->after('bukti_transfer_refund');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['bukti_transfer_refund', 'refund_dikonfirmasi_buyer']);
        });
    }
};
