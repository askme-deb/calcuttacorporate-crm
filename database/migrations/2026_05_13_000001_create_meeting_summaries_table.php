<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('meeting_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('meeting_created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('meeting_type');
            $table->string('meeting_mode')->nullable();
            $table->date('meeting_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('meeting_location')->nullable();
            $table->string('department')->nullable();
            $table->string('meeting_attended')->nullable();
            $table->string('client_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('contact_person')->nullable();
            $table->text('discussion_point')->nullable();
            $table->text('followup_action')->nullable();
            $table->date('next_follow_up_date')->nullable();
            $table->text('next_follow_up_details')->nullable();
            $table->json('attendees')->nullable();
            $table->json('agenda_items')->nullable();
            $table->json('action_items')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_summaries');
    }
};
