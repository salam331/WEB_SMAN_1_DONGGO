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
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('rombel_id')->nullable()->constrained('class_rooms')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('parents')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['rombel_id', 'parent_id']);
            $table->dropColumn(['rombel_id', 'parent_id']);
        });
    }
};