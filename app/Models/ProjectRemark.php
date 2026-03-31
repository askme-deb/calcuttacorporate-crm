<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProjectRemark extends Model
{
    use LogsActivity;
    protected $table = 'project_remarks';
    protected $fillable = [
        'user_id',
        'status_id',
        'project_id',
        'remarks',
        'is_visible'
    ];

    public function worksheet()
    {
        return $this->belongsTo(Worksheet::class, 'project_id', 'id');
    }

    public function commenter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Project Remark has been {$eventName}";
    }
    public function project()
    {
        return $this->belongsTo(Worksheet::class, 'project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function status()
    // {
    //     return $this->belongsTo(WorkStatus::class);
    // }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log all attributes
            ->setDescriptionForEvent(fn(string $eventName) => "Project Remark has been {$eventName}")
            ->useLogName('projectremark')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }
}
