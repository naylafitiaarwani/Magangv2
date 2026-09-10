<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sejarah;

class SejarahSeeder extends Seeder
{
    public function run(): void
    {
        Sejarah::create([
            'judul' => 'Sejarah Kabupaten Indramayu',
            'deskripsi' => 'Indramayu merupakan salah satu kabupaten di Provinsi Jawa Barat yang memiliki perjalanan sejarah dan perkembangan masyarakat yang panjang. Wilayah ini berkembang sebagai daerah pesisir dengan aktivitas perdagangan, pertanian, dan perikanan.',
            'lokasi' => 'Kabupaten Indramayu',
            'kategori' => 'Sejarah Daerah',
            'gambar' => 'sejarah/sejarah-indramayu.jpg',
            'is_active' => true,
        ]);

        Sejarah::create([
            'judul' => 'Masjid Agung Indramayu',
            'deskripsi' => 'Masjid Agung Indramayu menjadi salah satu bangunan yang memiliki nilai sejarah dan menjadi bagian dari perkembangan kehidupan masyarakat di pusat Kabupaten Indramayu.',
            'lokasi' => 'Indramayu',
            'kategori' => 'Bangunan Bersejarah',
            'gambar' => 'sejarah/masjid-agung-indramayu.jpg',
            'is_active' => true,
        ]);

        Sejarah::create([
            'judul' => 'Jembatan Sewo',
            'deskripsi' => 'Jembatan Sewo dikenal sebagai salah satu bagian penting dari jalur transportasi di wilayah Indramayu dan memiliki cerita yang berkembang dalam kehidupan masyarakat setempat.',
            'lokasi' => 'Sukra, Indramayu',
            'kategori' => 'Tempat Bersejarah',
            'gambar' => 'sejarah/jembatan-sewo.jpg',
            'is_active' => true,
        ]);

        Sejarah::create([
            'judul' => 'Perkembangan Kota Indramayu',
            'deskripsi' => 'Perkembangan Indramayu tidak terlepas dari kehidupan masyarakat pesisir, pertanian, perdagangan, dan berbagai aktivitas ekonomi yang membentuk karakter daerah hingga saat ini.',
            'lokasi' => 'Kabupaten Indramayu',
            'kategori' => 'Sejarah Daerah',
            'gambar' => 'sejarah/perkembangan-indramayu.jpg',
            'is_active' => true,
        ]);
    }
}