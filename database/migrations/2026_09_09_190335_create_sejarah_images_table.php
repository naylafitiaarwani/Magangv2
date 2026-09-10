<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sejarah_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sejarah_id')
                ->constrained('sejarahs')
                ->onDelete('cascade');

            $table->string('gambar');

            $table->integer('urutan')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sejarah_images');
    }
};
