<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class LeadsFollowup extends Model
{
    use LogsActivity;

    protected $table = 'leads_followup';
    const UPDATED_AT = null;
    protected $fillable = [
        'lead_id',
        'followup_by',
        'status_id',
        'next_followup_date',
        'notes',
        'created_at'
    ];

    public function leadStatus()
    {
        return $this->belongsTo(LeadStatus::class, 'status_id');
    }

    public function followupdBy()
    {
        return $this->belongsTo(User::class, 'followup_by');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Leads Followup has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log all attributes
            ->setDescriptionForEvent(fn(string $eventName) => "Leads Followup has been {$eventName}")
            ->useLogName('deals')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }
}
