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
        Schema::create('pengajuan_revisi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pesanan_id');
            $table->unsignedBigInteger('user_id');
            $table->text('deskripsi');
            $table->json('file_pendukung')->nullable();
            $table->enum('status', ['menunggu', 'diterima', 'ditolak', 'selesai'])->default('menunggu');
            $table->text('tanggapan_admin')->nullable();
            $table->timestamps();
            
            // Add foreign key constraints
            $table->foreign('pesanan_id')->references('id')->on('pesanan')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_revisi');
    }
};