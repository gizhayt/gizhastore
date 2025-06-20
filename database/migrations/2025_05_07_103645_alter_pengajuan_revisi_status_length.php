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
        Schema::table('pengajuan_revisi', function (Blueprint $table) {
            $table->string('status', 20)->change(); // ubah ke panjang yang cukup
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_revisi', function (Blueprint $table) {
            $table->string('status', 10)->change(); // sesuaikan dengan sebelumnya jika rollback
        });
    }
};
