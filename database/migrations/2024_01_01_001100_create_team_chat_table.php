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
        Schema::create('team_chat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('message')->nullable();
            $table->text('attachment_url')->nullable();
            $table->boolean('is_edited')->default(false);
            $table->dateTime('edited_at')->nullable();
            $table->timestamps();

            $table->index('team_id');
            $table->index('user_id');
            $table->index('created_at');
            $table->index(['team_id', 'created_at'], 'idx_team_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_chat');
    }
};
