<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Award extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'title',
        'type',
        'award_date',
        'description',
    ];

    // Cast award_date to Carbon instance
    protected $casts = [
        'award_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship with User (Employee)
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Accessor to ensure award_date is always a Carbon instance
    public function getAwardDateAttribute($value)
    {
        if ($value instanceof Carbon) {
            return $value;
        }

        return $value ? Carbon::parse($value) : null;
    }
}
