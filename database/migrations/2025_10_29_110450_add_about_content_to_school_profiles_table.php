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
        Schema::table('school_profiles', function (Blueprint $table) {
            $table->text('hero_title')->nullable()->after('map_embed');
            $table->text('hero_description')->nullable()->after('hero_title');
            $table->text('school_description')->nullable()->after('hero_description');
            $table->json('features')->nullable()->after('school_description');
            $table->json('statistics')->nullable()->after('features');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            $table->dropColumn(['hero_title', 'hero_description', 'school_description', 'features', 'statistics']);
        });
    }
};
