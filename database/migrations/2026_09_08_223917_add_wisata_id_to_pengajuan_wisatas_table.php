<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_wisatas', function (Blueprint $table) {
            $table->foreignId('wisata_id')
                ->nullable()
                ->after('user_id')
                ->constrained('wisatas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_wisatas', function (Blueprint $table) {
            $table->dropForeign(['wisata_id']);
            $table->dropColumn('wisata_id');
        });
    }
};