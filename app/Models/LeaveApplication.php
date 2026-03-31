<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LeaveApplication extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'leave_type_id',
        'apply_strt_date',
        'apply_end_date',
        'apply_day',
        'reason',
        'replace_person',
        'join_date',
        'status',
        'approved_by',
        'approve_date',
        'num_aprv_day',
    ];

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function getCreatedAtAttribute($value)
    {
        return date('jS M, Y', strtotime($value));
    }

    // public function getapplyStrtDateAttribute($value)
    // {
    //     return date('jS M, Y', strtotime($value));
    // }
    public function getapplyEndDateAttribute($value)
    {
        return date('jS M, Y', strtotime($value));
    }

    public function getjoinDateAttribute($value)
    {
        return date('jS M, Y', strtotime($value));
    }


    // public function getstatusAttribute($value)
    // {
    //     if($value==1){
    //         return '<span class="badge badge-outline-success">Approved</span>';
    //     }else if($value==2){
    //         return '<span class="badge badge-outline-warning">In progress</span>';
    //     }else if($value==3){
    //         return '<span class="badge badge-outline-danger">Rejected</span>';
    //     }else{
    //         return '<span class="badge badge-outline-secondary">Pending</span>';
    //     }
    // }


    public function getDescriptionForEvent(string $eventName): string
    {
        return "Leave Application has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log all attributes
            ->setDescriptionForEvent(fn(string $eventName) => "Leave Application has been {$eventName}")
            ->useLogName('Leaveapplication')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }
}
