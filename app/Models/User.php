<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, InteractsWithMedia, HasApiTokens, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function worksheets()
    {
        return $this->belongsToMany(
            Worksheet::class,
            'project_team_members', // pivot table name
            'user_id',              // foreign key on pivot table pointing to users
            'project_id'          // foreign key on pivot table pointing to worksheets
        );
    }


    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class, 'user_id');
    }

    public function leadsByUser()
    {
        return $this->hasMany(Lead::class, 'created_by');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class); // Adjust if necessary
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "User has been {$eventName}";
    }
    public function projectTeamMembers()
    {
        return $this->hasMany(ProjectTeamMember::class);
    }


     public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "User has been {$eventName}")
            ->useLogName('users')
            ->dontSubmitEmptyLogs(); // Prevents empty logs
    }
}
