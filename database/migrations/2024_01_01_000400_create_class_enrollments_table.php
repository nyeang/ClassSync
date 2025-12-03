<?php
// 2024_01_01_002300_create_class_enrollments_table.php (MODIFIED)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Updated class_enrollments migration
        Schema::create('class_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['student', 'teacher'])->default('student');  
            $table->enum('assigned_by', ['admin', 'teacher'])->default('admin');  
            $table->timestamp('assigned_at')->useCurrent();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['class_id', 'user_id']);
            $table->index(['class_id', 'role']);
            $table->index('assigned_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_enrollments');
    }
};
