<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\LogOptions;

class Worksheet extends Model implements HasMedia
{
    use  InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'title',
        'jobtype_id',
        'client_id',
        'work_id',
        'lead_id',
        'deal_id',
        'cost',
        'price_type_id',
        'priority_id',
        'customer_deadline',
        'start_date',
        'deadline',
        'description',
        'completed_on',
        'completed_by',
        'assigned_on',
        'status_id',
        'remarks',
        'approved_status_id',
        'approved_by',
        'approved_on',
        'determine',
        'invoice_time_id',
        'created_by',
        'created_at',
        'updated_at'
    ];

protected $casts = [
        'start_date' => 'date',
        'completed_on'   => 'date', // if you have end_date too
        'deadline'   => 'date', // if you have end_date too
    ];

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }


    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function priorty()
    {
         return $this->belongsTo(LeadPriority::class, 'priority_id');

    }
    public function jobType()
    {
         return $this->belongsTo(JobType::class, 'jobtype_id'); // Adjust foreign key if needed
    }

    public function work()
    {
        return $this->belongsTo(WorkMaster::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'asign_to');
    }

    public function projectTeamMembers()
    {
        return $this->hasMany(ProjectTeamMember::class, 'project_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault(); // Returns an empty User object if null
    }

    public function projectStatus()
    {
        return $this->belongsTo(WorkStatus::class, 'status_id');
    }

    public function projectRemarks()
    {
        return $this->hasMany(ProjectRemark::class, 'project_id');
    }

    public function projectTasks()
    {
        return $this->hasMany(ProjectTask::class, 'project_id');
    }


    public function projectAttachments()
    {
        return $this->hasMany(ProjectAttachment::class, 'project_id'); // Ensure the correct foreign key
    }
    // public function teamMembers()
    // {
    //     return $this->belongsToMany(User::class, 'project_team_members', 'project_id', 'user_id')
    //         ->withPivot('assigned_by', 'assigned_on')
    //         ->withTimestamps();
    // }
    public function assignedteamMembers()
    {
        return $this->belongsToMany(User::class, 'project_team_members', 'project_id', 'user_id')
            ->withPivot('assigned_by', 'assigned_on')
            ->withTimestamps();
    }
    public function getDescriptionForEvent(string $eventName): string
    {
        return "Project has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log all attributes
            ->setDescriptionForEvent(fn(string $eventName) => "Project has been {$eventName}")
            ->useLogName('projects')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }





public function teamMembers()
    {
        return $this->hasMany(ProjectTeamMember::class, 'project_id');
    }

    public function status()
    {
        return $this->belongsTo(WorkStatus::class, 'status_id');
    }

public function remarks()
{
    return $this->hasMany(ProjectRemark::class, 'project_id')->orderBy('created_at', 'desc');
}

    // Worksheet model

    public function latestRemark()
    {
        return $this->hasOne(ProjectRemark::class, 'project_id') // make sure foreign key matches your table
                    ->latest('created_at') // get the latest
                    ->with('user'); // eager load user
    }

    public static function boot()
    {
        parent::boot();

        // Log Project creation
        static::created(function ($project) {
            ProjectLog::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'action' => 'created',
                'notes' => 'Project created by ' . auth()->user()->name,
            ]);
        });

        // Log lead updates
        static::updating(function ($project) {
            // if ($project->isDirty('assigned_to')) {
            //     ProjectLog::create([
            //         'project_id' => $project->id,
            //         'user_id' => auth()->id(),
            //         'action' => 'assigned',
            //         'notes' => 'Assigned to ' . optional(User::find($project->assigned_to))->name,
            //     ]);
            // }

            // if ($project->isDirty('status_id') && $project->status_id == 9) { // Example: Status 9 = Converted
            //     LeadLog::create([
            //         'lead_id' => $lead->id,
            //         'user_id' => auth()->id(),
            //         'action' => 'converted',
            //         'notes' => 'Lead converted into a deal',
            //     ]);
            // }
        });
    }
}

