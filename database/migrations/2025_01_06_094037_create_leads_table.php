<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->decimal('deal_value', 12, 2)->nullable();
            $table->string('status')->default('new');
            $table->string('source')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->date('assigned_on')->nullable();
            $table->date('next_followup_date')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('source_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->unsignedBigInteger('priority_id')->nullable();
            $table->unsignedBigInteger('sector_id')->nullable();
            $table->text('notes')->nullable();
            $table->text('address')->nullable();
            $table->string('position')->nullable();
            $table->string('budget')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key relationships
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('source_id')->references('id')->on('lead_sources')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('lead_status')->onDelete('cascade');
            $table->foreign('priority_id')->references('id')->on('lead_priorities')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sector_id')->references('id')->on('lead_sectors')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('leads');
    }
};
