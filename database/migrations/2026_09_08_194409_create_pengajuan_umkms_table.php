<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_umkms', function (Blueprint $table) {
            $table->id();

            // Relasi dengan user yang mengajukan
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Data UMKM
            $table->string('nama_umkm');
            $table->text('alamat_umkm');
            $table->text('deskripsi_umkm');
            $table->string('kategori');
            $table->text('produk_umkm')->nullable();

            // Data pengaju
            $table->string('nama_pengaju');
            $table->string('jabatan_pengaju')->nullable();
            $table->string('no_hp_pengaju', 30);
            $table->string('email_pengaju');

            // Foto usaha
            $table->json('foto_usaha')->nullable();

            // Dokumen
            $table->string('foto_ktp')->nullable();
            $table->string('dokumen_nib')->nullable();

            // Status pengajuan
            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            // Diisi jika pengajuan ditolak
            $table->text('alasan_penolakan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_umkms');
    }
};