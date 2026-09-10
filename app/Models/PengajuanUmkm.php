<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Umkm;

class PengajuanUmkm extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_umkms';

    protected $fillable = [
        'user_id',
        ' umkm_id',
        'nama_umkm',
        'alamat_umkm',
        'deskripsi_umkm',
        'kategori',
        'produk_umkm',

        'nama_pengaju',
        'jabatan_pengaju',
        'no_hp_pengaju',
        'email_pengaju',

        'foto_usaha',

        'foto_ktp',
        'dokumen_nib',

        'status',
        'alasan_penolakan',
    ];

    protected $casts = [
        'foto_usaha' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function umkm()
{
    return $this->belongsTo(Umkm::class);
}
}