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
        Schema::create('class_code_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->string('old_class_code', 8)->nullable();
            $table->text('old_qr_code')->nullable();
            $table->foreignId('reset_by_teacher_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('reset_at')->useCurrent();

            $table->index('class_id');
            $table->index('reset_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_code_history');
    }
};
