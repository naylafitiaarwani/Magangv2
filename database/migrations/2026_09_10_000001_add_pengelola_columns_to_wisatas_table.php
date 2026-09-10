<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wisatas', function (Blueprint $table) {
            // Pengelola wisata (diisi dari user_id pengajuan saat disetujui)
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();

            // Menentukan apakah wisata ini boleh berjualan tiket online
            $table->boolean('is_tiket_aktif')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('wisatas', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'is_tiket_aktif']);
        });
    }
};
