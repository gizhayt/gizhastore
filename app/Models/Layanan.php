<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanan';

    protected $fillable = [
        'nama',
        'deskripsi',
        'harga',
        'gambar',
        'paket_revisi_id',
        'aktif',
    ];

    /**
     * Relasi ke tabel PaketRevisi.
     */
    public function paketRevisi()
    {
        return $this->belongsTo(PaketRevisi::class, 'paket_revisi_id');
    }

    /**
     * Akses URL gambar jika ada.
     */
    public function getGambarUrlAttribute()
    {
        return $this->gambar ? Storage::url($this->gambar) : asset('images/default.jpg');
    }
}
