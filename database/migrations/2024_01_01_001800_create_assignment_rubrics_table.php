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
        Schema::create('assignment_rubrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->foreignId('rubric_id')->constrained('rubrics')->onDelete('cascade');
            $table->timestamp('assigned_at')->useCurrent();

            $table->unique(['assignment_id', 'rubric_id'], 'unique_assignment_rubric');
            $table->index('assignment_id');
            $table->index('rubric_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_rubrics');
    }
};
