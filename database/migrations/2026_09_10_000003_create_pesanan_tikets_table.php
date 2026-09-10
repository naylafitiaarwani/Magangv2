<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan_tikets', function (Blueprint $table) {
            $table->id();

            // Kode pesanan yang ditampilkan ke pengguna (mis. TKT-20260910-A1B2)
            $table->string('kode_pesanan')->unique();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('wisata_id')
                ->constrained('wisatas')
                ->cascadeOnDelete();

            $table->string('nama_pemesan');
            $table->string('email_pemesan');
            $table->string('no_hp_pemesan', 30);

            $table->decimal('total_harga', 12, 2);

            $table->enum('status', [
                'pending',
                'paid',
                'expired',
                'cancelled',
                'failed',
            ])->default('pending');

            // Order ID unik yang dikirim ke Midtrans (bisa beda dari kode_pesanan
            // kalau nanti ada percobaan bayar ulang)
            $table->string('midtrans_order_id')->unique();
            $table->string('snap_token')->nullable();
            $table->string('payment_type')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_tikets');
    }
};
