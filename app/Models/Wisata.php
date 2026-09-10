<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Wisata extends Model
{
    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'lokasi',
        'jam_operasional',
        'harga_mulai',
        'rating',
        'jumlah_ulasan',
        'kategori',
        'gambar',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($wisata) {
            if (!$wisata->slug) {
                $wisata->slug = Str::slug($wisata->nama);
            }
        });
    }
}