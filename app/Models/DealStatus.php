<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DealStatus extends Model
{
    use LogsActivity;

    protected $table = 'deal_status';

    protected $fillable = [
        'name',
        'is_visible'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Deal Status has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'is_visible']) // Attributes to log
            ->setDescriptionForEvent(fn(string $eventName) => "Deal Status has been {$eventName}")
            ->useLogName('dealstatus')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }
}
