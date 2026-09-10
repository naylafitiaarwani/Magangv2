<?php

namespace App\Models;
use App\Models\ProdukUmkm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Umkm extends Model
{
    protected $fillable = [
        'nama',
        'nama_pelaku',
        'slug',
        'deskripsi',
        'lokasi',
        'kategori',
        'harga_mulai',
        'gambar',
        'kontak',
        'is_active',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($umkm) {
            if (!$umkm->slug) {
                $umkm->slug = Str::slug($umkm->nama);
            }
        });
    }
    public function produk()
    {
        return $this->hasMany(ProdukUmkm::class);
    }
}