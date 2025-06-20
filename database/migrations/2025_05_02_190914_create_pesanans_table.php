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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pesanan')->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('layanan_id');
            $table->unsignedBigInteger('paket_revisi_id')->nullable();
            $table->integer('revisi_tersisa')->default(0);
            $table->text('persyaratan');
            $table->enum('status', ['pending', 'diproses', 'selesai', 'revisi', 'dibatalkan'])->default('pending');
            $table->decimal('harga', 12, 2);
            $table->decimal('total_harga', 12, 2);
            $table->datetime('batas_waktu');
            $table->text('file_pesanan')->nullable();
            $table->text('hasil_pesanan')->nullable();
            $table->text('keterangan_hasil')->nullable();
            $table->enum('status_revisi', ['belum_ada', 'menunggu', 'diterima', 'ditolak', 'selesai'])->default('belum_ada');
            $table->text('tanggapan_revisi')->nullable();
            $table->timestamps();
            
            // Add foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('layanan_id')->references('id')->on('layanan')->onDelete('cascade');
            $table->foreign('paket_revisi_id')->references('id')->on('paket_revisi')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};