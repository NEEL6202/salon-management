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
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('salon_id')->constrained()->onDelete('cascade');
            $table->foreignId('loyalty_program_id')->constrained()->onDelete('cascade');
            $table->integer('points');
            $table->string('source'); // 'appointment', 'order', 'manual', etc.
            $table->string('sourceable_type')->nullable();
            $table->unsignedBigInteger('sourceable_id')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_redeemed')->default(false);
            $table->timestamps();
            
            // Make sourceable fields nullable
            $table->index(['sourceable_type', 'sourceable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_points');
    }
};
