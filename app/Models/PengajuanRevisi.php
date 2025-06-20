<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanRevisi extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_revisi';

    protected $fillable = [
        'pesanan_id',
        'user_id',
        'deskripsi',
        'file_pendukung',
        'status',
        'tanggapan_admin',
    ];

    protected $casts = [
        'file_pendukung' => 'array',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hasilRevisi()
    {
        return $this->hasMany(HasilRevisi::class);
    }
}