<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Employee extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'emp_code',
        'user_id',
        'emp_type',
        'emp_appellation',
        'emp_first_name',
        'emp_middle_name',
        'emp_last_name',
        'emp_designation',
        'emp_dob',
        'emp_sex',
        'emp_date_of_joining',
        'emp_aadhar',
        'emp_pan',
        'emp_appointed_organisation',
        'emp_contact_no',
        'emp_emergency_contact_no',
        'emp_udin',
        'emp_address',
        'emp_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class, 'emp_institute');
    }

    public function empType()
    {
        return $this->belongsTo(Emptype::class, 'emp_type');
    }
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'emp_designation');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Employee has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log all attributes
            ->setDescriptionForEvent(fn(string $eventName) => "Employee has been {$eventName}")
            ->useLogName('employees')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }


}



