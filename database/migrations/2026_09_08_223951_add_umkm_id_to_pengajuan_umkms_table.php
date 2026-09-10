<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_umkms', function (Blueprint $table) {
            $table->foreignId('umkm_id')
                ->nullable()
                ->after('user_id')
                ->constrained('umkms')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_umkms', function (Blueprint $table) {
            $table->dropForeign(['umkm_id']);
            $table->dropColumn('umkm_id');
        });
    }
};