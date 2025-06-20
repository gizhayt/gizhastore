<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTanggalSelesaiToPesananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->timestamp('tanggal_selesai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('tanggal_selesai');
        });
    }
}