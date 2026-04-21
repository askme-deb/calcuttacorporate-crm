<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'company')) {
                $table->string('company')->nullable();
            }
            if (!Schema::hasColumn('leads', 'deal_value')) {
                $table->decimal('deal_value', 15, 2)->nullable();
            }
            if (!Schema::hasColumn('leads', 'status')) {
                $table->string('status')->default('new');
            }
            if (!Schema::hasColumn('leads', 'source')) {
                $table->string('source')->nullable();
            }
            if (!Schema::hasColumn('leads', 'assigned_to')) {
                $table->unsignedBigInteger('assigned_to')->nullable();
            }
            if (!Schema::hasColumn('leads', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['company', 'deal_value', 'status', 'source', 'assigned_to', 'notes']);
        });
    }
};
