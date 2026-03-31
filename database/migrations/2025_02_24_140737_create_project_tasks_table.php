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
        Schema::create('project_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('title');
            $table->text('description')->nullable();
            //$table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->unsignedBigInteger('priority_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();


             // Foreign keys
             $table->foreign('project_id')->references('id')->on('worksheets')->onDelete('cascade');
            //  $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
             $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
             $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
             $table->foreign('priority_id')->references('id')->on('lead_priorities')->onDelete('cascade');
             $table->foreign('status_id')->references('id')->on('task_status')->onDelete('cascade');

        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_task');
    }
};
