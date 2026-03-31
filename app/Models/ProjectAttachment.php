<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProjectAttachment extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity;

    protected $fillable = ['project_id', 'attached_by', 'attached_on', 'title'];


    public function worksheet()
    {
        return $this->belongsTo(Worksheet::class);
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
        return "Project Attachment has been {$eventName}";
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
