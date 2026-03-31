<?php

namespace App\Models;

use Illuminate\Console\View\Components\Task;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TaskAttachment extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity;

    protected $fillable = ['task_id', 'attached_by', 'attached_on', 'title'];


    public function task()
    {
        return $this->belongsTo(ProjectTask::class);
    }

    public function attachedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Task Attachment has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log all attributes
            ->setDescriptionForEvent(fn(string $eventName) => "Project Attachment has been {$eventName}")
            ->useLogName('projectattachment')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }

}
