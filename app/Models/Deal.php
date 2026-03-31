<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Deal extends Model
{
    use LogsActivity;

    protected $fillable = [
        'amount',
        'deal_name',
        'closing_date',
        'lead_id',
        'status_id',
        'closed_by'
    ];

    public function dealStatus()
    {
        return $this->belongsTo(DealStatus::class, 'status_id');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Deal has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log all attributes
            ->setDescriptionForEvent(fn(string $eventName) => "Deal has been {$eventName}")
            ->useLogName('deals')
            ->logOnlyDirty() // Log only changed attributes
            ->dontSubmitEmptyLogs(); // Avoid logging if nothing changed
    }
}
