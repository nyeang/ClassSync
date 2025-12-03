<?php
// 2024_01_01_000000_create_users_table.php (UPDATED)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 50)->nullable()->index();
            $table->string('name');
            $table->string('gender', 20)->nullable();  // NEW
            $table->date('date_of_birth')->nullable(); // NEW
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['Student', 'Teacher', 'Admin'])->default('Student');
            $table->string('department', 100)->nullable();
            $table->string('academic_year', 20)->nullable(); // NEW
            $table->text('profile_picture_url')->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable(); // NEW
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('email');
            $table->index('role');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
