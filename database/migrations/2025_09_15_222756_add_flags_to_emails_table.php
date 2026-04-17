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
            $table->tinyInteger('answered')->default(0)->after('seen');
            $table->tinyInteger('deleted')->default(0)->after('answered');
            $table->tinyInteger('flagged')->default(0)->after('deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->dropColumn(['answered', 'deleted', 'flagged']);
        });
    }
};
