<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Designation extends Model
{
    use LogsActivity;
    use HasFactory;
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
        return "Designation has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'is_visible'])
            ->setDescriptionForEvent(fn(string $eventName) => "Designation has been {$eventName}")
            ->useLogName('dsignation')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function previousPromotions()
    {
        return $this->hasMany(Promotion::class, 'previous_designation_id');
    }

    public function newPromotions()
    {
        return $this->hasMany(Promotion::class, 'new_designation_id');
    }
}
