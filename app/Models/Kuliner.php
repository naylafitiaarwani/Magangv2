<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kuliner extends Model
{
    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'kategori',
        'harga_mulai',
        'lokasi',
        'gambar',
        'is_active',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kuliner) {
            if (!$kuliner->slug) {
                $kuliner->slug = Str::slug($kuliner->nama);
            }
        });
    }
}