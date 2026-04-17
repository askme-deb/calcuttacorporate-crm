<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\LeadLog;

class Lead extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'source_id',
        'status_id',
        'notes',
        'address',
        'company',
        'position',
        'budget',
        'priority_id',
        'next_followup_date',
        'created_by',
        'sector_id',
        'asign_to'
    ];

    protected $casts = [
        'source_id' => 'integer',
        'status_id' => 'integer',
        'priority_id' => 'integer',
        'created_by' => 'integer',
    ];

    public function getCreatedAtAttribute($value)
    {
        return date('jS M, Y', strtotime($value));
    }

    public function getNextFollwupDateAttribute($value)
    {
        return date('jS M, Y', strtotime($value));
    }

    public function leadStatus()
    {
        return $this->belongsTo(LeadStatus::class, 'status_id');
    }
    public function leadSource()
    {
        return $this->belongsTo(LeadSource::class, 'source_id');
    }
    public function leadPriority()
    {
        return $this->belongsTo(LeadPriority::class, 'priority_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function LeadSector()
    {
        return $this->belongsTo(LeadSector::class, 'sector_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    



    public function getDescriptionForEvent(string $eventName): string
    {
        return "Lead has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log all attributes
            ->setDescriptionForEvent(fn(string $eventName) => "Lead has been {$eventName}")
            ->useLogName('Leads')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }


    public static function boot()
    {
        parent::boot();
    
        // Log lead creation
        static::created(function ($lead) {
            LeadLog::create([
                'lead_id' => $lead->id,
                'user_id' => auth()->id(),
                'action' => 'created',
                'notes' => 'Lead created by ' . auth()->user()->name,
            ]);
        });
    
        // Log lead updates
        static::updating(function ($lead) {
            if ($lead->isDirty('assigned_to')) {
                LeadLog::create([
                    'lead_id' => $lead->id,
                    'user_id' => auth()->id(),
                    'action' => 'assigned',
                    'notes' => 'Assigned to ' . optional(User::find($lead->assigned_to))->name,
                ]);
            }
    
            if ($lead->isDirty('status_id') && $lead->status_id == 9) { // Example: Status 9 = Converted
                LeadLog::create([
                    'lead_id' => $lead->id,
                    'user_id' => auth()->id(),
                    'action' => 'converted',
                    'notes' => 'Lead converted into a deal',
                ]);
            }
        });
    }

}


