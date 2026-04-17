<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TaskRemark extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'status_id',
        'task_id',
        'remarks',
        'is_visible'
    ];


    public function commenter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Task Remark has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log all attributes
            ->setDescriptionForEvent(fn(string $eventName) => "Task Remark has been {$eventName}")
            ->useLogName('taskremark')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }

}
