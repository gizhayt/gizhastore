<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            // Fix the foreign key reference to match the actual table name
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->decimal('jumlah', 10, 2);
            $table->enum('status_pembayaran', [
                'pending',    // Menunggu pembayaran
                'berhasil',   // Pembayaran berhasil
                'expired',    // Pembayaran kadaluarsa
                'dibatalkan', // Pembayaran dibatalkan
                'ditolak',    // Pembayaran ditolak
                'challenge'   // Pembayaran dalam peninjauan
            ])->default('pending');
            $table->string('metode_pembayaran')->nullable();
            $table->string('snap_token')->nullable();
            $table->string('kode_pembayaran')->nullable();
            $table->timestamp('tanggal_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};