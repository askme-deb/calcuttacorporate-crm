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
        Schema::table('invoices', function (Blueprint $table) {
            $table->tinyInteger('gst_filing_status')
                  ->default(0)
                  ->comment('0 = Not Filed, 1 = Filed')
                  ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('gst_filing_status');
        });
    }
};
