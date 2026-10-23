<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PesananTiket extends Model
{
    use HasFactory;

    protected $table = 'pesanan_tikets';

    protected $fillable = [
        'kode_pesanan',
        'user_id',
        'wisata_id',
        'nama_pemesan',
        'email_pemesan',
        'no_hp_pemesan',
        'total_harga',
        'status',
        'midtrans_order_id',
        'snap_token',
        'payment_type',
        'paid_at',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wisata(): BelongsTo
    {
        return $this->belongsTo(Wisata::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(PesananTiketDetail::class);
    }
}
