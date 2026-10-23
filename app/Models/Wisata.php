<?php

namespace App\Models;

use App\Models\PesananTiket;
use App\Models\TiketWisata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Wisata extends Model
{
    protected $fillable = [
        'user_id',
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
        'is_tiket_aktif',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_tiket_aktif' => 'boolean',
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

    /**
     * Pengelola / pemilik wisata (user yang pengajuannya disetujui).
     */
    public function pengelola(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tiketWisata(): HasMany
    {
        return $this->hasMany(TiketWisata::class);
    }

    public function pesananTiket(): HasMany
    {
        return $this->hasMany(PesananTiket::class);
    }
}