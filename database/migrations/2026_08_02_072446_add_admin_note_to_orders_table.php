<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Pesan/notifikasi dari admin kepada buyer (misal: info keterlambatan, estimasi tiba)
            $table->text('admin_note')->nullable()->after('waktu_sampai');
            // Estimasi tanggal tiba yang diberikan admin ke buyer
            $table->date('estimasi_tiba')->nullable()->after('admin_note');
            // Timestamp kapan admin terakhir update notifikasi ke buyer
            $table->timestamp('admin_note_at')->nullable()->after('estimasi_tiba');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['admin_note', 'estimasi_tiba', 'admin_note_at']);
        });
    }
};
