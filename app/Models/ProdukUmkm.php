<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukUmkm extends Model
{
    protected $table = 'produk_umkm';

    protected $fillable = [
        'umkm_id',
        'nama',
        'deskripsi',
        'harga',
        'gambar',
        'is_active',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function umkm()
    {
        return $this->belongsTo(Umkm::class);
    }
}
