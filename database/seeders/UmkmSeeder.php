<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Umkm;

class UmkmSeeder extends Seeder
{
    public function run(): void
    {
        Umkm::create([
            'nama' => 'Batik Paoman',
            'deskripsi' => 'Batik Paoman merupakan salah satu produk kerajinan khas Indramayu yang memiliki motif dan karakter visual yang mencerminkan budaya daerah.',
            'lokasi' => 'Paoman, Indramayu',
            'kategori' => 'Fashion & Kerajinan',
            'harga_mulai' => 75000,
            'gambar' => 'umkm/batik-paoman.jpg',
            'kontak' => '081234567890',
            'is_active' => true,
        ]);

        Umkm::create([
            'nama' => 'Kerajinan Bordir Indramayu',
            'deskripsi' => 'Produk kerajinan bordir lokal dengan berbagai pilihan motif yang dibuat oleh pelaku usaha kreatif di Indramayu.',
            'lokasi' => 'Indramayu',
            'kategori' => 'Kerajinan',
            'harga_mulai' => 50000,
            'gambar' => 'umkm/kerajinan-bordir.jpg',
            'kontak' => '081234567891',
            'is_active' => true,
        ]);

        Umkm::create([
            'nama' => 'Olahan Mangga Indramayu',
            'deskripsi' => 'Produk olahan mangga yang dibuat oleh pelaku UMKM lokal dengan berbagai pilihan produk untuk oleh-oleh khas Indramayu.',
            'lokasi' => 'Indramayu',
            'kategori' => 'Makanan & Oleh-oleh',
            'harga_mulai' => 20000,
            'gambar' => 'umkm/olahan-mangga.jpg',
            'kontak' => '081234567892',
            'is_active' => true,
        ]);

        Umkm::create([
            'nama' => 'Keripik Mangga Indramayu',
            'deskripsi' => 'Camilan berbahan dasar mangga yang diolah menjadi produk ringan dan cocok dijadikan oleh-oleh khas daerah.',
            'lokasi' => 'Indramayu',
            'kategori' => 'Makanan',
            'harga_mulai' => 15000,
            'gambar' => 'umkm/keripik-mangga.jpg',
            'kontak' => '081234567893',
            'is_active' => true,
        ]);

        Umkm::create([
            'nama' => 'Produk Anyaman Lokal',
            'deskripsi' => 'Kerajinan anyaman yang dibuat oleh pengrajin lokal dengan mengutamakan keterampilan tradisional dan kreativitas masyarakat Indramayu.',
            'lokasi' => 'Indramayu',
            'kategori' => 'Kerajinan',
            'harga_mulai' => 30000,
            'gambar' => 'umkm/produk-anyaman.jpg',
            'kontak' => '081234567894',
            'is_active' => true,
        ]);
    }
}
