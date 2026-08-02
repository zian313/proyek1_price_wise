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
            $table->string('no_resi')->nullable()->after('ekspedisi');
            $table->text('alasan_komplain')->nullable();
            $table->string('video_unboxing')->nullable();
            $table->timestamp('waktu_sampai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['no_resi', 'alasan_komplain', 'video_unboxing', 'waktu_sampai']);
        });
    }
};
