<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite sudah menyimpan status sebagai string (VARCHAR) secara default.
        // Migration ini sengaja dibiarkan kosong karena tidak diperlukan untuk SQLite.
        // Kolom status sudah fleksibel menerima nilai string apapun.
    }

    public function down(): void
    {
        // No-op
    }
};
