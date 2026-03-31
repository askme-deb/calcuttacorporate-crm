<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Holiday extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'no_of_days',
        'created_by'
    ];


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Holiday has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log all attributes
            ->setDescriptionForEvent(fn(string $eventName) => "Holiday has been {$eventName}")
            ->useLogName('holiday')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }
}
