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
        Schema::create('email_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('email_address')->unique();
            $table->string('name')->nullable();

            $table->string('imap_host')->nullable();
            $table->integer('imap_port')->default(993);
            $table->string('imap_encryption')->default('ssl');

            $table->string('smtp_host')->nullable();
            $table->integer('smtp_port')->default(465);
            $table->string('smtp_encryption')->default('ssl');
            $table->string('smtp_username')->nullable();
            $table->string('smtp_password')->nullable(); // store encrypted ideally

            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_accounts');
    }
};
