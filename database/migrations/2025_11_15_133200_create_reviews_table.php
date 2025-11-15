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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salon_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('employee_id')->nullable()->constrained('users')->onDelete('set null');
            $table->tinyInteger('rating')->unsigned(); // 1-5 stars
            $table->text('review')->nullable();
            $table->string('title')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->json('photos')->nullable(); // Store photo paths
            $table->string('source')->default('website'); // website, google, facebook, etc.
            $table->timestamps();
            
            $table->index(['salon_id', 'is_approved', 'created_at']);
            $table->index(['rating', 'is_approved']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
