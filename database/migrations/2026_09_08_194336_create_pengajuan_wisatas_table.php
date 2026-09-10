<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_wisatas', function (Blueprint $table) {
            $table->id();

            // Relasi dengan user yang mengajukan
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Data wisata
            $table->string('nama_wisata');
            $table->text('alamat_wisata');
            $table->text('deskripsi_wisata');

            // Data pengaju
            $table->string('nama_pengaju');
            $table->string('jabatan_pengaju');
            $table->string('no_hp_pengaju', 30);
            $table->string('email_pengaju');

            // Foto lokasi wisata
            // Disimpan sebagai JSON karena bisa lebih dari satu file
            $table->json('foto_lokasi_wisata')->nullable();

            // Status entitas
            // perorangan / organisasi
            $table->enum('status_entitas', [
                'perorangan',
                'organisasi'
            ]);

            // Dokumen perorangan
            $table->string('foto_ktp')->nullable();
            $table->string('foto_selfie')->nullable();

            // Data organisasi
            $table->string('nama_organisasi')->nullable();
            $table->json('dokumen_nib')->nullable();

            // Aktivasi tiket online
            $table->enum('active_online_tiket', [
                'Aktif',
                'Tidak Aktif'
            ])->nullable();

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
        Schema::dropIfExists('pengajuan_wisatas');
    }
};