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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('academic_year', 20);
            $table->text('description')->nullable();
            $table->string('class_code', 8)->unique();
            $table->text('qr_code');
            $table->text('qr_code_image_url')->nullable();
            $table->foreignId('created_by_teacher_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_archived')->default(false);
            $table->timestamps();

            $table->index('created_by_teacher_id');
            $table->index('academic_year');
            $table->index('class_code');
            $table->index('is_archived');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
