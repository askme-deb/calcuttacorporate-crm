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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->string('description')->nullable();;
            $table->unsignedInteger('quantity')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('cgst', 10, 2)->default(0)->nullable();
            $table->decimal('sgst', 10, 2)->default(0)->nullable();
            $table->decimal('igst', 10, 2)->default(0)->nullable();
            $table->decimal('gst', 10, 2)->default(0)->nullable();
            $table->timestamps();   

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
