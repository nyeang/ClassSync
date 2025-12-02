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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->string('name', 100)->nullable();
            $table->string('topic')->nullable();
            $table->integer('max_size')->default(5);
            $table->boolean('auto_created')->default(false);
            $table->string('submission_status', 50)->default('not_started');
            $table->dateTime('submitted_at')->nullable();
            $table->timestamps();

            $table->index('assignment_id');
            $table->index('submission_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
