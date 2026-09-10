<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Wisata;

class PengajuanWisata extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_wisatas';

    protected $fillable = [
        'user_id',
        'wisata_id',
        'nama_wisata',
        'alamat_wisata',
        'deskripsi_wisata',
        'nama_pengaju',
        'jabatan_pengaju',
        'no_hp_pengaju',
        'email_pengaju',
        'foto_lokasi_wisata',
        'status_entitas',
        'foto_ktp',
        'foto_selfie',
        'nama_organisasi',
        'dokumen_nib',
        'active_online_tiket',
        'status',
        'alasan_penolakan',
    ];

    protected $casts = [
        'foto_lokasi_wisata' => 'array',
        'dokumen_nib' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wisata()
{
    return $this->belongsTo(Wisata::class);
}
}