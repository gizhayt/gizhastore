<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilRevisi extends Model
{
    use HasFactory;

    protected $table = 'hasil_revisi';

    protected $fillable = [
        'pengajuan_revisi_id',
        'file_hasil',
        'deskripsi_hasil',
        'tanggal_revisi',
    ];

    protected $casts = [
        'tanggal_revisi' => 'datetime',
    ];

   
        public function pengajuanRevisi()
    {
        return $this->belongsTo(PengajuanRevisi::class);
    }
}