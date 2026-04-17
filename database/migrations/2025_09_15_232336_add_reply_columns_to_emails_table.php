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
        Schema::table('emails', function (Blueprint $table) {
            $table->bigInteger('reply_to_id')->nullable()->after('uid')->index(); // points to the email being replied to
            $table->string('message_id')->nullable()->after('uid')->index(); // original email Message-ID
            $table->string('in_reply_to')->nullable()->after('message_id')->index(); // Message-ID this email replies to
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->dropColumn(['reply_to_id', 'message_id', 'in_reply_to']);
        });
    }
};
