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
        Schema::create('leads_followup', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->unsignedBigInteger('followup_by')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->date('next_followup_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();

            // Foreign key relationships
            $table->foreign('followup_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('lead_status')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads_followup');
    }
};
