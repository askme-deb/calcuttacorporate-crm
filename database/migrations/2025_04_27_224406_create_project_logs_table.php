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
        Schema::create('project_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id'); // Reference to leads table
            $table->unsignedBigInteger('user_id')->nullable(); // User who performed the action
            $table->string('action'); // 'created', 'assigned', 'followed_up', 'converted'
            $table->text('notes')->nullable(); // Additional details about the action
            $table->timestamp('action_time')->useCurrent(); // When the action happened
            $table->timestamps();

            // Foreign key relationships
            $table->foreign('project_id')->references('id')->on('worksheets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_logs');
    }
};

