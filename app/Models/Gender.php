<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Gender extends Model
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
        return "Gender has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'is_visible']) // Attributes to log
            ->setDescriptionForEvent(fn(string $eventName) => "Gender has been {$eventName}")
            ->useLogName('genders')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }
}
