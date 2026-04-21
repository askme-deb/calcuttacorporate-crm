<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Leads Table
        if (!Schema::hasTable('leads')) {
            Schema::create('leads', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('company')->nullable();
                $table->decimal('deal_value', 12, 2)->nullable();
                $table->string('status')->default('new');
                $table->string('source')->nullable();
                $table->unsignedBigInteger('assigned_to')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('leads', function (Blueprint $table) {
                if (!Schema::hasColumn('leads', 'company')) $table->string('company')->nullable();
                if (!Schema::hasColumn('leads', 'deal_value')) $table->decimal('deal_value', 12, 2)->nullable();
                if (!Schema::hasColumn('leads', 'status')) $table->string('status')->default('new');
                if (!Schema::hasColumn('leads', 'source')) $table->string('source')->nullable();
                if (!Schema::hasColumn('leads', 'assigned_to')) $table->unsignedBigInteger('assigned_to')->nullable();
                if (!Schema::hasColumn('leads', 'notes')) $table->text('notes')->nullable();
            });
        }

        // Proposals Table
        if (!Schema::hasTable('proposals')) {
            Schema::create('proposals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lead_id')->constrained()->onDelete('cascade');
                $table->string('type');
                $table->string('title');
                $table->longText('content');
                $table->decimal('total_amount', 10, 2);
                $table->string('status')->default('draft');
                $table->timestamp('sent_at')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('proposals', function (Blueprint $table) {
                if (!Schema::hasColumn('proposals', 'lead_id')) $table->foreignId('lead_id')->constrained()->onDelete('cascade');
                if (!Schema::hasColumn('proposals', 'type')) $table->string('type');
                if (!Schema::hasColumn('proposals', 'title')) $table->string('title');
                if (!Schema::hasColumn('proposals', 'content')) $table->longText('content');
                if (!Schema::hasColumn('proposals', 'total_amount')) $table->decimal('total_amount', 10, 2);
                if (!Schema::hasColumn('proposals', 'status')) $table->string('status')->default('draft');
                if (!Schema::hasColumn('proposals', 'sent_at')) $table->timestamp('sent_at')->nullable();
            });
        }

        // Proposal Items Table
        if (!Schema::hasTable('proposal_items')) {
            Schema::create('proposal_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('proposal_id')->constrained()->onDelete('cascade');
                $table->string('item_name');
                $table->string('description')->nullable();
                $table->integer('quantity')->default(1);
                $table->decimal('price', 10, 2);
                $table->decimal('total', 12, 2);
                $table->timestamps();
            });
        } else {
            Schema::table('proposal_items', function (Blueprint $table) {
                if (!Schema::hasColumn('proposal_items', 'proposal_id')) $table->foreignId('proposal_id')->constrained()->onDelete('cascade');
                if (!Schema::hasColumn('proposal_items', 'item_name')) $table->string('item_name');
                if (!Schema::hasColumn('proposal_items', 'description')) $table->string('description')->nullable();
                if (!Schema::hasColumn('proposal_items', 'quantity')) $table->integer('quantity')->default(1);
                if (!Schema::hasColumn('proposal_items', 'price')) $table->decimal('price', 10, 2);
                if (!Schema::hasColumn('proposal_items', 'total')) $table->decimal('total', 12, 2);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('proposal_items');
        Schema::dropIfExists('proposals');
        Schema::dropIfExists('leads');
    }
};
