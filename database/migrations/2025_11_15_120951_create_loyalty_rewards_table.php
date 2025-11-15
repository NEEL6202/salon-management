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
        Schema::create('loyalty_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('salon_id')->constrained()->onDelete('cascade');
            $table->foreignId('loyalty_program_id')->constrained()->onDelete('cascade');
            $table->integer('points_redeemed');
            $table->decimal('value', 10, 2);
            $table->string('type'); // 'discount', 'free_service', 'product'
            $table->text('description')->nullable();
            $table->string('code')->unique()->nullable(); // For coupon codes
            $table->boolean('is_used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_rewards');
    }
};
