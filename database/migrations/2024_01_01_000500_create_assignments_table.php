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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('resources')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->boolean('team_assignment')->default(false);
            $table->boolean('topic_optional')->default(false);
            $table->integer('max_students_per_team')->default(5);
            $table->boolean('allow_manual_team_join')->default(true);
            $table->boolean('allow_team_creation')->default(false);
            $table->boolean('randomize_teams')->default(false);
            $table->string('submission_status', 50)->default('open');
            $table->foreignId('created_by_teacher_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('published_at')->nullable();
            $table->timestamps();

            $table->index('class_id');
            $table->index('deadline');
            $table->index('submission_status');
            $table->index('team_assignment');
            $table->index('created_by_teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
