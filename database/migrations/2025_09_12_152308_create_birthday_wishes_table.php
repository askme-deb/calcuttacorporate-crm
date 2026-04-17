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
        Schema::create('birthday_wishes', function (Blueprint $table) {
            $table->id();

            // Employee who receives the wish
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');

            // Employee who sent the wish
            $table->foreignId('sent_by')->constrained('employees')->onDelete('cascade');

            $table->text('message');  // The birthday message
            $table->timestamp('sent_at'); // When the wish was sent
            $table->timestamps(); // created_at & updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('birthday_wishes');
    }
};
