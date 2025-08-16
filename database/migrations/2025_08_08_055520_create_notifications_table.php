<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('salon_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type'); // appointment, order, payment, system, etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data
            $table->string('action_url')->nullable(); // URL to redirect when clicked
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'read_at']);
            $table->index(['salon_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
