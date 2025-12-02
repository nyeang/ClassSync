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
        Schema::create('rubric_criterion_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_feedback_id')->constrained('grades_feedback')->onDelete('cascade');
            $table->foreignId('rubric_criterion_id')->constrained('rubric_criteria')->onDelete('cascade');
            $table->float('points_earned');
            $table->text('feedback')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['grade_feedback_id', 'rubric_criterion_id'], 'unique_grade_criterion');
            $table->index('grade_feedback_id');
            $table->index('rubric_criterion_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubric_criterion_scores');
    }
};
