<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesananTiketDetail extends Model
{
    use HasFactory;

    protected $table = 'pesanan_tiket_details';

    protected $fillable = [
        'pesanan_tiket_id',
        'tiket_wisata_id',
        'nama_tiket',
        'harga_satuan',
        'jumlah',
        'subtotal',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function pesananTiket(): BelongsTo
    {
        return $this->belongsTo(PesananTiket::class);
    }

    public function tiketWisata(): BelongsTo
    {
        return $this->belongsTo(TiketWisata::class);
    }
}
