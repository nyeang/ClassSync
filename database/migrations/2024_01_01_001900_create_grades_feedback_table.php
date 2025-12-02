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
        Schema::create('grades_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('set null');
            $table->foreignId('student_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('rubric_id')->nullable()->constrained('rubrics')->onDelete('set null');
            $table->foreignId('graded_by')->constrained('users')->onDelete('cascade');
            $table->float('score')->nullable();
            $table->float('max_score')->nullable();
            $table->text('feedback')->nullable();
            $table->boolean('is_individual_grade')->default(false);
            $table->timestamp('graded_at')->useCurrent();
            $table->timestamps();

            $table->index('assignment_id');
            $table->index('team_id');
            $table->index('student_id');
            $table->index('graded_by');
            $table->index('rubric_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades_feedback');
    }
};
