<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketRevisi extends Model
{
    use HasFactory;

    protected $table = 'paket_revisi';

    protected $fillable = [
        'nama',
        'deskripsi',
        'jumlah_revisi',
        'harga',
        'aktif',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'aktif' => 'boolean',
    ];
}
