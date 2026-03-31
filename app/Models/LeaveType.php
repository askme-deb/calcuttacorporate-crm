<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LeaveType extends Model
{
    use LogsActivity;

    protected $fillable = [
        'type_name',
        'number_of_days'
    ];

    public function getCreatedAtAttribute($value)
    {
        return date('jS M, Y', strtotime($value));
    }


    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class, 'leave_type_id');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Leave Type has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'is_visible']) // Attributes to log
            ->setDescriptionForEvent(fn(string $eventName) => "Leave Type has been {$eventName}")
            ->useLogName('Leavetype')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }
}
