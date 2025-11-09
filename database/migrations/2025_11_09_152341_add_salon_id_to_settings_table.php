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
        Schema::table('settings', function (Blueprint $table) {
            $table->foreignId('salon_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->dropUnique(['key']); // Remove old unique constraint
            $table->unique(['salon_id', 'key']); // Add composite unique constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['salon_id', 'key']);
            $table->unique(['key']);
            $table->dropForeign(['salon_id']);
            $table->dropColumn('salon_id');
        });
    }
};
