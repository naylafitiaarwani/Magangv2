<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TiketWisata extends Model
{
    use HasFactory;

    protected $table = 'tiket_wisatas';

    protected $fillable = [
        'wisata_id',
        'nama_tiket',
        'harga',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function wisata(): BelongsTo
    {
        return $this->belongsTo(Wisata::class);
    }

    public function pesananTiketDetails(): HasMany
    {
        return $this->hasMany(PesananTiketDetail::class);
    }
}
