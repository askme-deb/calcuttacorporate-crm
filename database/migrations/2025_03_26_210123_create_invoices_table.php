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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_type')->nullable();
            $table->string('invoice_number')->unique();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('customer_name')->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->decimal('grand_total', 10, 2)->nullable();
            $table->integer('cgst_rate')->nullable();
            $table->integer('sgst_rate')->nullable();
            $table->integer('igst_rate')->nullable();
            $table->decimal('cgst_amount', 10, 2)->nullable();
            $table->decimal('sgst_amount', 10, 2)->nullable();
            $table->decimal('igst_amount', 10, 2)->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('payment_terms')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
