<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LeadStatus extends Model
{
    use LogsActivity;

    protected $table = 'lead_status';

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
        return "Lead Status has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'is_visible']) // Attributes to log
            ->setDescriptionForEvent(fn(string $eventName) => "Lead Status has been {$eventName}")
            ->useLogName('leadstatus')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }
    // public function getisVisibleAttribute($value)
    // {
    //     if($value==1){
    //         return '<span class="badge badge-outline-success">Visible</span>';
    //     }else{
    //         return '<span class="badge badge-outline-secondary">Not Visible</span>';
    //     }
    // }
}
