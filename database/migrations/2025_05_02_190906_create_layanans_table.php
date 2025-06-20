<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 10, 2);
            $table->string('gambar')->nullable(); // Menyimpan path gambar
            $table->foreignId('paket_revisi_id')->nullable()->constrained('paket_revisi')->onDelete('set null');
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan');
    }
};
