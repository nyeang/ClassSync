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
            
            // Basic Information
            $table->string('name', 100);
            $table->string('subject', 100)->nullable();
            $table->string('academic_year', 20);
            $table->string('semester', 50)->nullable();
            $table->text('description')->nullable();
            
            // Schedule & Location
            $table->string('schedule', 100)->nullable();
            $table->string('room', 100)->nullable();
            
            // Capacity & Credits
            $table->integer('max_students')->nullable();
            $table->decimal('credits', 4, 2)->nullable();
            
            // Class Code & QR
            $table->string('class_code', 8)->unique();
            $table->text('qr_code');
            $table->text('qr_code_image_url')->nullable();
            
            // Creator Information
            $table->foreignId('created_by_admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by_teacher_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Status
            $table->boolean('is_archived')->default(false);
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('created_by_admin_id');
            $table->index('created_by_teacher_id');
            $table->index('academic_year');
            $table->index('class_code');
            $table->index('is_archived');
            $table->index('subject');
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
