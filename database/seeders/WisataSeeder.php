<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wisata;

class WisataSeeder extends Seeder
{
    public function run(): void
    {
        Wisata::create([
            'nama' => 'Pantai Karangsong',
            'deskripsi' => 'Pantai Karangsong merupakan salah satu destinasi wisata pesisir yang populer di Kabupaten Indramayu. Tempat ini menawarkan suasana pantai yang cocok untuk menikmati pemandangan laut dan matahari terbenam.',
            'lokasi' => 'Karangsong, Indramayu',
            'jam_operasional' => '08:00 - 17:00',
            'harga_mulai' => 10000,
            'rating' => 4.5,
            'jumlah_ulasan' => 128,
            'kategori' => 'Pantai',
            'gambar' => 'wisata/pantai-karangsong.jpg',
            'latitude' => -6.3275000,
            'longitude' => 108.3200000,
            'is_active' => true,
        ]);

        Wisata::create([
            'nama' => 'Pantai Tirtamaya',
            'deskripsi' => 'Pantai Tirtamaya adalah destinasi wisata pantai di Indramayu yang menawarkan suasana santai dengan pemandangan laut yang menarik untuk dikunjungi bersama keluarga.',
            'lokasi' => 'Juntinyuat, Indramayu',
            'jam_operasional' => '07:00 - 18:00',
            'harga_mulai' => 10000,
            'rating' => 4.3,
            'jumlah_ulasan' => 96,
            'kategori' => 'Pantai',
            'gambar' => 'wisata/pantai-tirtamaya.jpg',
            'latitude' => -6.3927000,
            'longitude' => 108.4421000,
            'is_active' => true,
        ]);

        Wisata::create([
            'nama' => 'Situ Bojongsari',
            'deskripsi' => 'Situ Bojongsari merupakan kawasan wisata yang menawarkan suasana asri dan tenang. Destinasi ini cocok untuk bersantai dan menikmati pemandangan alam.',
            'lokasi' => 'Bojongsari, Indramayu',
            'jam_operasional' => '08:00 - 17:00',
            'harga_mulai' => 5000,
            'rating' => 4.2,
            'jumlah_ulasan' => 74,
            'kategori' => 'Wisata Alam',
            'gambar' => 'wisata/situ-bojongsari.jpg',
            'latitude' => -6.3375000,
            'longitude' => 108.3208000,
            'is_active' => true,
        ]);

        Wisata::create([
            'nama' => 'Waterpark Bojongsari',
            'deskripsi' => 'Waterpark Bojongsari menjadi salah satu pilihan wisata keluarga di Indramayu dengan berbagai fasilitas rekreasi air yang dapat dinikmati oleh pengunjung.',
            'lokasi' => 'Bojongsari, Indramayu',
            'jam_operasional' => '09:00 - 17:00',
            'harga_mulai' => 20000,
            'rating' => 4.4,
            'jumlah_ulasan' => 115,
            'kategori' => 'Wisata Keluarga',
            'gambar' => 'wisata/waterpark-bojongsari.jpg',
            'latitude' => -6.3401000,
            'longitude' => 108.3167000,
            'is_active' => true,
        ]);

        Wisata::create([
            'nama' => 'Pantai Balongan Indah',
            'deskripsi' => 'Pantai Balongan Indah menawarkan pemandangan pesisir yang menarik dan menjadi salah satu pilihan tempat rekreasi masyarakat di Kabupaten Indramayu.',
            'lokasi' => 'Balongan, Indramayu',
            'jam_operasional' => '07:00 - 18:00',
            'harga_mulai' => 10000,
            'rating' => 4.1,
            'jumlah_ulasan' => 68,
            'kategori' => 'Pantai',
            'gambar' => 'wisata/pantai-balongan.jpg',
            'latitude' => -6.3788000,
            'longitude' => 108.4085000,
            'is_active' => true,
        ]);
    }
}
