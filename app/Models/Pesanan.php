<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pesanan extends Model
{
    use HasFactory;
    protected $table = 'pesanan'; // 👈 tambahkan ini

    protected $fillable = [
        'nomor_pesanan',
        'user_id',
        'layanan_id',
        'paket_revisi_id',
        'revisi_tersisa',
        'persyaratan',
        'status',
        'harga',
        'total_harga',
        'batas_waktu',
        'file_pesanan',
        'hasil_pesanan',
        'keterangan_hasil',
        'status_revisi',
        'tanggapan_revisi',
        'tanggal_selesai', // Tambahkan field ini
    ];

    // Tambahkan tanggal_selesai ke $dates agar otomatis di-casting ke Carbon
    protected $dates = [
        'created_at',
        'updated_at',
        'batas_waktu',
        'tanggal_selesai'
    ];
    
    protected $casts = [
        'file_pesanan' => 'array',
        'hasil_pesanan' => 'array',
        'batas_waktu' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }

    public function paketRevisi(): BelongsTo
    {
        return $this->belongsTo(PaketRevisi::class);
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function pengajuanRevisi(): HasMany
    {
        return $this->hasMany(PengajuanRevisi::class);
    }
    
}
