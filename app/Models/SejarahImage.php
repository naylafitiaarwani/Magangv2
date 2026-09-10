<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SejarahImage extends Model
{
    protected $fillable = [
        'sejarah_id',
        'gambar',
        'urutan',
    ];

    public function sejarah(): BelongsTo
    {
        return $this->belongsTo(Sejarah::class);
    }
}
