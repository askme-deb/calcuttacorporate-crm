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
    Schema::create('task_assignments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('task_id');
        $table->unsignedBigInteger('assigned_to');
        $table->unsignedBigInteger('assigned_by')->nullable();
        $table->timestamp('assigned_on')->nullable();
        $table->timestamps();

        // Ensure referenced tables have matching unsignedBigInteger IDs
        $table->foreign('task_id')
            ->references('id')
            ->on('project_tasks')
            ->onDelete('cascade'); // Ensure project_tasks has BIGINT UNSIGNED id

        $table->foreign('assigned_to')
            ->references('id')
            ->on('users')
            ->onDelete('cascade'); // Ensure users has BIGINT UNSIGNED id

        $table->foreign('assigned_by')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
    });
}

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_assignments');
    }
};
