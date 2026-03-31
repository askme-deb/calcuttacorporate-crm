<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProjectTeamMember extends Model
{
    use LogsActivity;

    protected $fillable = [
        'project_id',
        'user_id',
        'assigned_by',
        'assigned_on',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Project Team Member has been {$eventName}";
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

     public function assignedBy() {
        return $this->belongsTo(User::class, 'assigned_by');
    }


    public function project()
    {
        return $this->belongsTo(Worksheet::class, 'project_id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log all attributes
            ->setDescriptionForEvent(fn(string $eventName) => "Project Team Member has been {$eventName}")
            ->useLogName('projectteammember')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }
}
