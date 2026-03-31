<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Add new required columns only if they do not exist
            if (!Schema::hasColumn('clients', 'client_name')) {
                $table->string('client_name')->after('id');
            }
            if (!Schema::hasColumn('clients', 'phone_number')) {
                $table->string('phone_number')->after('client_name');
            }
            if (!Schema::hasColumn('clients', 'alternative_number')) {
                $table->string('alternative_number')->nullable()->after('phone_number');
            }
            if (!Schema::hasColumn('clients', 'state')) {
                $table->string('state')->after('email');
            }
        });

        // Drop foreign key constraint before dropping the column
        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'created_by')) {
                $table->dropForeign(['created_by']);
            }
        });

        Schema::table('clients', function (Blueprint $table) {
            // Remove old/extra columns
            $columns = [
                'name', 'address', 'phone', 'gst', 'pan', 'aadhar', 'company_name',
                'state_name', 'state_code', 'city', 'pincode', 'is_visible', 'created_by'
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('clients', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Re-add dropped columns (types as per original)
            $table->string('name')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('gst')->nullable();
            $table->string('pan')->nullable();
            $table->string('aadhar')->nullable();
            $table->string('company_name')->nullable();
            $table->string('state_name')->nullable();
            $table->string('state_code')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode')->nullable();
            $table->integer('is_visible')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            // Remove new columns
            $table->dropColumn(['client_name', 'phone_number', 'alternative_number', 'state']);
        });
    }
};
