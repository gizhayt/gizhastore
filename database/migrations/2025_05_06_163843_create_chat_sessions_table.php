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
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('status')->default('active'); // active, closed, etc.
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_sessions');
    }
};