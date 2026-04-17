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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');

            // Make sure nullable comes before constrained
            $table->foreignId('previous_designation_id')->nullable()->constrained('designations')->nullOnDelete();
            $table->foreignId('new_designation_id')->nullable()->constrained('designations')->nullOnDelete();

            $table->decimal('previous_salary', 10, 2)->nullable();
            $table->decimal('new_salary', 10, 2)->nullable();
            $table->date('promotion_date');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
