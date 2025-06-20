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
        Schema::create('hasil_revisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_revisi_id')->constrained('pengajuan_revisi')->onDelete('cascade');
            $table->string('file_hasil');
            $table->text('deskripsi_hasil');
            $table->dateTime('tanggal_revisi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_revisi');
    }
};