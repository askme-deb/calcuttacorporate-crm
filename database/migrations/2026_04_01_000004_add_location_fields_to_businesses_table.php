<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->text('address')->nullable()->after('state');
            $table->string('city')->nullable()->after('address');
            $table->string('pincode', 20)->nullable()->after('city');
            $table->string('state_code', 20)->nullable()->after('pincode');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['address', 'city', 'pincode', 'state_code']);
        });
    }
};
