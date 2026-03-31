<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class TaskAssignment extends Model
{
    use LogsActivity;

    protected $fillable = [
        'task_id',
        'assigned_to',
        'assigned_by',
        'assigned_on',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Task has been {$eventName}";
    }

     public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "Task has been {$eventName}")
            ->useLogName('Tasks')
            ->dontSubmitEmptyLogs(); // Prevents empty logs
    }
}
