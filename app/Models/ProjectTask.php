<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProjectTask extends Model
{
    use LogsActivity;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status_id',
        'priority_id',
        'start_date',
        'due_date',
        'completed_at',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];



    public function taskTeamMembers()
    {
        return $this->hasMany(TaskAssignment::class, 'task_id');
    }

    public function taskStatus()
    {
        return $this->belongsTo(TaskStatus::class, 'status_id');
    }

    public function taskRemarks()
    {
        return $this->hasMany(TaskRemark::class, 'task_id');
    }

    public function teamMembers()
    {
        return $this->belongsToMany(User::class, 'task_assignments', 'task_id', 'assigned_to')
            ->withPivot('assigned_by', 'assigned_on')
            ->withTimestamps();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Project Task has been {$eventName}";
    }


    public function taskAttachments()
    {
        return $this->hasMany(TaskAttachment::class, 'task_id'); // Ensure the correct foreign key
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log all attributes
            ->setDescriptionForEvent(fn(string $eventName) => "Project Task has been {$eventName}")
            ->useLogName('projecttask')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }
}
