<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kuliner;

class KulinerSeeder extends Seeder
{
    public function run(): void
    {
        Kuliner::create([
            'nama' => 'Pedesan Entog',
            'deskripsi' => 'Pedesan entog merupakan salah satu kuliner khas Indramayu dengan cita rasa gurih dan pedas. Hidangan ini menggunakan daging entog yang dimasak dengan berbagai rempah.',
            'kategori' => 'Makanan Khas',
            'harga_mulai' => 25000,
            'lokasi' => 'Indramayu',
            'gambar' => 'kuliner/pedesan-entog.jpg',
            'is_active' => true,
        ]);

        Kuliner::create([
            'nama' => 'Burbacek',
            'deskripsi' => 'Burbacek merupakan kuliner khas Indramayu yang berbahan dasar bubur dengan cita rasa khas dan sering dinikmati sebagai makanan tradisional masyarakat setempat.',
            'kategori' => 'Makanan Khas',
            'harga_mulai' => 10000,
            'lokasi' => 'Indramayu',
            'gambar' => 'kuliner/burbacek.jpg',
            'is_active' => true,
        ]);

        Kuliner::create([
            'nama' => 'Rumbah',
            'deskripsi' => 'Rumbah merupakan makanan khas Indramayu berupa sayuran yang disajikan dengan bumbu kacang dan memiliki cita rasa gurih serta pedas.',
            'kategori' => 'Makanan Tradisional',
            'harga_mulai' => 8000,
            'lokasi' => 'Indramayu',
            'gambar' => 'kuliner/rumbah.jpg',
            'is_active' => true,
        ]);

        Kuliner::create([
            'nama' => 'Nasi Lengko',
            'deskripsi' => 'Nasi lengko merupakan hidangan sederhana berbahan nasi, tahu, tempe, sayuran, dan bumbu kacang yang banyak dikenal di wilayah pesisir Jawa Barat.',
            'kategori' => 'Makanan',
            'harga_mulai' => 12000,
            'lokasi' => 'Indramayu',
            'gambar' => 'kuliner/nasi-lengko.jpg',
            'is_active' => true,
        ]);

        Kuliner::create([
            'nama' => 'Kerupuk Melarat',
            'deskripsi' => 'Kerupuk melarat merupakan salah satu makanan ringan khas wilayah Cirebon dan Indramayu yang memiliki tekstur renyah dan biasanya disajikan dengan sambal khas.',
            'kategori' => 'Camilan',
            'harga_mulai' => 10000,
            'lokasi' => 'Indramayu',
            'gambar' => 'kuliner/kerupuk-melarat.jpg',
            'is_active' => true,
        ]);
    }
}
