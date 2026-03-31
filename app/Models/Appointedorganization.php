<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Appointedorganization extends Model
{
    use LogsActivity;

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
        return "Appointed organization has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'is_visible']) // Attributes to log
            ->setDescriptionForEvent(fn(string $eventName) => "Appointed organization has been {$eventName}")
            ->useLogName('appointedorganization')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }
}
