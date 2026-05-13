<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'meeting_created_by',
        'meeting_type',
        'meeting_mode',
        'meeting_date',
        'start_time',
        'end_time',
        'meeting_location',
        'department',
        'meeting_attended',
        'client_name',
        'company_name',
        'contact_number',
        'contact_person',
        'discussion_point',
        'followup_action',
        'next_follow_up_date',
        'next_follow_up_details',
        'attendees',
        'agenda_items',
        'action_items',
        'attachments',
    ];

    protected $casts = [
        'meeting_date' => 'date',
        'next_follow_up_date' => 'date',
        'attendees' => 'array',
        'agenda_items' => 'array',
        'action_items' => 'array',
        'attachments' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'meeting_created_by');
    }
}
