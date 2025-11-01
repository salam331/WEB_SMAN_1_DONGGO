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
        Schema::table('exams', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->string('status')->default('draft')->after('total_score');
            $table->date('exam_date')->nullable()->after('end_date');
            $table->time('start_time')->nullable()->after('exam_date');
            $table->integer('duration')->nullable()->after('start_time');
            $table->integer('total_questions')->nullable()->after('duration');
            $table->integer('passing_grade')->nullable()->after('total_questions');
            $table->text('instructions')->nullable()->after('passing_grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'status',
                'exam_date',
                'start_time',
                'duration',
                'total_questions',
                'passing_grade',
                'instructions',
            ]);
        });
    }
};
