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
      Schema::create('document_work_master', function (Blueprint $table) {
        $table->id();
        $table->foreignId('list_of_document_id')
              ->constrained('list_of_documents') // explicitly define table
              ->cascadeOnDelete();

        $table->foreignId('work_master_id')
              ->constrained('work_master') // explicitly define table
              ->cascadeOnDelete();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_work_master');
    }
};
