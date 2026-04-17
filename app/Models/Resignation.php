<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Resignation extends Model
{
     protected $fillable = [
        'employee_id',
        'resignation_date',
        'last_working_date',
        'reason',
        'additional_comments',
        'status',
        'approved_by',
        'approved_at',
        'hr_comments',
        'exit_checklist',
        'is_notice_period_served',
        'notice_period_days'
    ];

     protected $casts = [
        'resignation_date' => 'date',
        'last_working_date' => 'date',
        'approved_at' => 'datetime',
        'exit_checklist' => 'array',
        'is_notice_period_served' => 'boolean'
    ];

     public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getRemainingNoticeDaysAttribute()
    {
        if ($this->status !== 'approved') {
            return null;
        }

        $today = Carbon::now();
        $lastWorkingDate = Carbon::parse($this->last_working_date);

        return $today->diffInDays($lastWorkingDate, false);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'withdrawn' => 'gray',
        };
    }
}
