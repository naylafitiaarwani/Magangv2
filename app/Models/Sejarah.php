<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sejarah extends Model
{
    protected $table = 'sejarahs';

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'lokasi',
        'kategori',
        'gambar',
        'is_active',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sejarah) {
            if (!$sejarah->slug) {
                $sejarah->slug = Str::slug($sejarah->judul);
            }
        });
    }

    public function images(): HasMany
{

    return $this->hasMany(SejarahImage::class)
        ->orderBy('urutan');
}
}