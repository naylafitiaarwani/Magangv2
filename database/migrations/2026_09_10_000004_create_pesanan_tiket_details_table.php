<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan_tiket_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pesanan_tiket_id')
                ->constrained('pesanan_tikets')
                ->cascadeOnDelete();

            $table->foreignId('tiket_wisata_id')
                ->nullable()
                ->constrained('tiket_wisatas')
                ->nullOnDelete();

            // Snapshot data tiket saat transaksi terjadi.
            // Sengaja tidak diambil live dari relasi supaya riwayat transaksi
            // tidak berubah walau harga/nama tiket diubah di kemudian hari.
            $table->string('nama_tiket');
            $table->decimal('harga_satuan', 12, 2);
            $table->unsignedInteger('jumlah');
            $table->decimal('subtotal', 12, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_tiket_details');
    }
};
