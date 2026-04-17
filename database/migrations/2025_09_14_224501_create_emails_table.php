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
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('folder')->default('INBOX');
            $table->bigInteger('msgno'); // IMAP UID/ID
            $table->string('subject')->nullable();
            $table->string('from')->nullable();
            $table->dateTime('date')->nullable();
            $table->boolean('seen')->default(false);
            $table->boolean('has_attachments')->default(false);
            $table->bigInteger('size')->default(0);
            $table->longText('body')->nullable(); // optional
            $table->timestamps();

            $table->unique(['folder', 'msgno']); // avoid duplicates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
