<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wisatas', function (Blueprint $table) {
            $table->id();

            $table->string('nama');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();

            $table->string('lokasi')->nullable();
            $table->string('jam_operasional')->nullable();

            $table->decimal('harga_mulai', 12, 2)->nullable();

            $table->decimal('rating', 2, 1)->default(0);
            $table->unsignedInteger('jumlah_ulasan')->default(0);

            $table->string('kategori')->nullable();

            $table->string('gambar')->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wisatas');
    }
};