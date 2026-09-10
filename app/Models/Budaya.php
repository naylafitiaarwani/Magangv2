<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Budaya extends Model
{
    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'kategori',
        'lokasi',
        'gambar',
        'is_active',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($budaya) {
            if (!$budaya->slug) {
                $budaya->slug = Str::slug($budaya->nama);
            }
        });
    }
}