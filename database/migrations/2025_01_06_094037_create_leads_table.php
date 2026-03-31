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
    Schema::create('leads', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('email')->nullable();
        $table->string('phone');
        $table->unsignedBigInteger('source_id')->nullable();
        $table->unsignedBigInteger('status_id')->nullable();
        $table->text('notes')->nullable();
        $table->text('address')->nullable();
        $table->string('company')->nullable();
        $table->string('position')->nullable();
        $table->string('budget')->nullable();
        $table->unsignedBigInteger('priority_id')->nullable();
        $table->unsignedBigInteger('assigned_to')->nullable(); // FIXED: Changed from integer
        $table->unsignedBigInteger('assigned_by')->nullable(); // FIXED: Changed from integer
        $table->date('assigned_on')->nullable();
        $table->date('next_followup_date')->nullable();
        $table->unsignedBigInteger('created_by');
        $table->unsignedBigInteger('sector_id')->nullable();
        $table->timestamps();

        // Foreign key relationships
        $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('source_id')->references('id')->on('lead_sources')->onDelete('cascade');
        $table->foreign('status_id')->references('id')->on('lead_status')->onDelete('cascade');
        $table->foreign('priority_id')->references('id')->on('lead_priorities')->onDelete('cascade');
        $table->foreign('assigned_to')->references('id')->on('users')->onDelete('cascade'); // FIXED
        $table->foreign('assigned_by')->references('id')->on('users')->onDelete('cascade'); // FIXED
        $table->foreign('sector_id')->references('id')->on('lead_sectors')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
