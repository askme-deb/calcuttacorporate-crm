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
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('leave_type_id');
            $table->date('apply_strt_date');
            $table->date('apply_end_date');
            $table->integer('apply_day');
            $table->integer('num_aprv_day')->nullable();
            $table->text('reason')->nullable();
            $table->string('replace_person')->nullable();
            $table->date('join_date');
            $table->date('approve_date')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('status');
            $table->timestamps();

            // Foreign key relationships
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
    }
};
