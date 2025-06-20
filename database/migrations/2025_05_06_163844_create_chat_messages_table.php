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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_session_id')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->text('message');
            $table->string('sender_type'); // 'client' atau 'admin'
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('chat_session_id')->references('id')->on('chat_sessions')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};