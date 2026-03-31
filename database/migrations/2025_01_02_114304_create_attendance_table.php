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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('emp_code')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->time('duration')->nullable();
            $table->time('late_by')->nullable();
            $table->time('early_by')->nullable();
            $table->integer('status')->default(1);
            $table->text('punch_records')->nullable();
            $table->date('dated')->nullable();
            $table->text('late_reasons')->nullable();
            $table->timestamps();

            // Foreign key relationships
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
