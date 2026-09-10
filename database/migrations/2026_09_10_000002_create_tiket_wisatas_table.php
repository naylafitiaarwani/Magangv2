<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiket_wisatas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wisata_id')
                ->constrained('wisatas')
                ->cascadeOnDelete();

            $table->string('nama_tiket');
            $table->decimal('harga', 12, 2);
            $table->text('deskripsi')->nullable();

            // Nonaktifkan jenis tiket tanpa menghapus riwayat transaksinya
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiket_wisatas');
    }
};
