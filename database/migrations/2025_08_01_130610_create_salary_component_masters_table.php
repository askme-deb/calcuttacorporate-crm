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
       Schema::create('salary_component_masters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->enum('type', ['allowance', 'deduction']);
            $table->decimal('default_percentage', 5, 2)->nullable();
            $table->decimal('default_amount', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_component_masters');
    }
};
