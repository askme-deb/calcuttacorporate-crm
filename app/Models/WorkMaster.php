<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class WorkMaster extends Model
{
    use LogsActivity;

    protected $table = 'work_master';
    protected $fillable = [
        'name',
        'is_visible'
    ];

    public function getCreatedAtAttribute($value)
    {
        return date('jS M, Y', strtotime($value));
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Work has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'is_visible']) // Attributes to log
            ->setDescriptionForEvent(fn(string $eventName) => "Work has been {$eventName}")
            ->useLogName('Work')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }
    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logAll()
    //         ->setDescriptionForEvent(fn(string $eventName) => "Project has been {$eventName}")
    //         ->useLogName('projects')
    //         ->dontSubmitEmptyLogs(); // Prevents empty logs
    // }


    public function documents()
    {
        return $this->belongsToMany(ListOfDocument::class, 'document_work_master', 'work_master_id', 'list_of_document_id')->withTimestamps();
    }
}
