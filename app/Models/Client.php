<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Client extends Model
{
    use LogsActivity;

    protected $fillable = [
        'client_name',
        'phone_number',
        'alternative_number',
        'email',
        'state',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Client has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "Client has been {$eventName}")
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function businesses()
    {
        return $this->hasMany(Business::class);
    }

    protected static function booted()
    {
        static::deleting(function ($client) {
            // Cascade delete businesses and their partners
            foreach ($client->businesses as $business) {
                $business->delete();
            }
        });
    }
}
