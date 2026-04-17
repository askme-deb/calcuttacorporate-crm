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
            // UID column
            if (!Schema::hasColumn('emails', 'uid')) {
                $table->unsignedBigInteger('uid')->after('id');
            }

            // user_id column, nullable FK to users
            if (!Schema::hasColumn('emails', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('uid')
                    ->constrained('users')
                    ->nullOnDelete(); // set NULL if user gets deleted
            }

            // Composite index
            $table->index(['folder', 'uid'], 'emails_folder_uid_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emails', function (Blueprint $table) {
            // Drop composite index
            $table->dropIndex('emails_folder_uid_index');

            // Drop foreign key & column
            $table->dropConstrainedForeignId('user_id');

            // Drop UID
            $table->dropColumn('uid');
        });
    }
};
