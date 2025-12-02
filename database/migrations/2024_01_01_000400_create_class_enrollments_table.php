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
        Schema::create('class_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->enum('enrolled_via', ['manual', 'class_code', 'qr_code'])->default('manual');
            $table->timestamp('enrolled_at')->useCurrent();
            $table->boolean('is_active')->default(true);

            $table->unique(['class_id', 'student_id'], 'unique_class_student');
            $table->index('student_id');
            $table->index('class_id');
            $table->index('enrolled_via');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_enrollments');
    }
};
