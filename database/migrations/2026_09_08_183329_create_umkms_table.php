<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('umkms', function (Blueprint $table) {
            $table->id();

            $table->string('nama');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();

            $table->string('lokasi')->nullable();
            $table->string('kategori')->nullable();

            $table->decimal('harga_mulai', 12, 2)->nullable();

            $table->string('gambar')->nullable();

            $table->string('kontak')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umkms');
    }
};