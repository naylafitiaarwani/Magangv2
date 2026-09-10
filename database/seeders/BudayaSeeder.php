<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Budaya;

class BudayaSeeder extends Seeder
{
    public function run(): void
    {
        Budaya::create([
            'nama' => 'Tari Topeng Indramayu',
            'deskripsi' => 'Tari Topeng Indramayu merupakan salah satu kesenian tradisional yang menjadi bagian dari identitas budaya masyarakat Indramayu. Pertunjukan tari menggunakan topeng dengan karakter dan gerakan yang memiliki makna tertentu.',
            'kategori' => 'Seni Tari',
            'lokasi' => 'Kabupaten Indramayu',
            'gambar' => 'budaya/tari-topeng-indramayu.jpg',
            'is_active' => true,
        ]);

        Budaya::create([
            'nama' => 'Nadran',
            'deskripsi' => 'Nadran merupakan tradisi masyarakat pesisir Indramayu yang menjadi bentuk ungkapan rasa syukur sekaligus bagian dari kehidupan sosial masyarakat nelayan.',
            'kategori' => 'Tradisi',
            'lokasi' => 'Wilayah Pesisir Indramayu',
            'gambar' => 'budaya/nadran.jpg',
            'is_active' => true,
        ]);

        Budaya::create([
            'nama' => 'Sintren',
            'deskripsi' => 'Sintren merupakan kesenian tradisional yang berkembang di wilayah pesisir Jawa Barat dan Jawa Tengah, termasuk Indramayu. Kesenian ini memiliki unsur tari, musik tradisional, dan pertunjukan budaya.',
            'kategori' => 'Seni Tradisional',
            'lokasi' => 'Kabupaten Indramayu',
            'gambar' => 'budaya/sintren.jpg',
            'is_active' => true,
        ]);

        Budaya::create([
            'nama' => 'Wayang Kulit Indramayu',
            'deskripsi' => 'Wayang kulit menjadi salah satu bentuk seni pertunjukan tradisional yang berkembang di masyarakat Indramayu dan menjadi bagian dari kekayaan budaya daerah.',
            'kategori' => 'Seni Pertunjukan',
            'lokasi' => 'Kabupaten Indramayu',
            'gambar' => 'budaya/wayang-kulit.jpg',
            'is_active' => true,
        ]);

        Budaya::create([
            'nama' => 'Tarling',
            'deskripsi' => 'Tarling merupakan kesenian musik khas wilayah pesisir Cirebon dan Indramayu yang memadukan permainan gitar dan suling dengan lagu-lagu khas daerah.',
            'kategori' => 'Musik Tradisional',
            'lokasi' => 'Indramayu',
            'gambar' => 'budaya/tarling.jpg',
            'is_active' => true,
        ]);
    }
}
