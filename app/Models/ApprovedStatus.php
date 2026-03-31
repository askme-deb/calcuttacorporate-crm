<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ApprovedStatus extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'is_visible'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Approved Status has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'is_visible']) // Attributes to log
            ->setDescriptionForEvent(fn(string $eventName) => "Approved Status has been {$eventName}")
            ->useLogName('approvedstatus')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }
}
