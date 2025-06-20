<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'pesanan_id',
        'jumlah',
        'status_pembayaran',
        'metode_pembayaran',
        'snap_token',
        'kode_pembayaran',
        'tanggal_pembayaran',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_pembayaran' => 'datetime',
    ];

    /**
     * Allowed payment status values to prevent data truncation issues
     */
    const STATUS_PENDING = 'pending';
    const STATUS_BERHASIL = 'berhasil';
    const STATUS_EXPIRED = 'expired';
    const STATUS_DIBATALKAN = 'dibatalkan';
    const STATUS_DITOLAK = 'ditolak';
    const STATUS_CHALLENGE = 'challenge';

    /**
     * Get all allowed payment status values
     * 
     * @return array
     */
    public static function getAllowedStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_BERHASIL,
            self::STATUS_EXPIRED,
            self::STATUS_DIBATALKAN,
            self::STATUS_DITOLAK,
            self::STATUS_CHALLENGE,
        ];
    }

    /**
     * Set status pembayaran attribute with validation
     * 
     * @param string $value
     * @return void
     */
    public function setStatusPembayaranAttribute($value)
    {
        // If the value isn't in our allowed list, default to pending
        if (!in_array($value, self::getAllowedStatuses())) {
            $value = self::STATUS_PENDING;
        }
        
        $this->attributes['status_pembayaran'] = $value;
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}