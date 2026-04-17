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
        Schema::create('worksheets', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('jobtype_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('work_id')->nullable();
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->unsignedBigInteger('deal_id')->nullable();
            $table->string('cost')->nullable();
            $table->unsignedBigInteger('price_type_id')->nullable();
            $table->unsignedBigInteger('priority_id')->nullable();
            $table->date('customer_deadline')->nullable();
            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();
            $table->text('description')->nullable();
            // $table->unsignedBigInteger('asign_to')->nullable();
            // $table->unsignedBigInteger('asign_by')->nullable();
            $table->date('assigned_on')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('approved_status_id')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->date('approved_on')->nullable();
            $table->text('determine')->nullable();
            $table->unsignedBigInteger('invoice_time_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            // Foreign key relationships
            $table->foreign('jobtype_id')->references('id')->on('job_types')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('work_id')->references('id')->on('work_master')->onDelete('cascade');
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->foreign('deal_id')->references('id')->on('deals')->onDelete('cascade');
            // $table->foreign('asign_to')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('asign_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('work_status')->onDelete('cascade');
            $table->foreign('approved_status_id')->references('id')->on('approved_status')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('invoice_time_id')->references('id')->on('invoice_time')->onDelete('cascade');
            $table->foreign('price_type_id')->references('id')->on('price_types')->onDelete('cascade');
            $table->foreign('priority_id')->references('id')->on('lead_priorities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worksheets');
    }
};
