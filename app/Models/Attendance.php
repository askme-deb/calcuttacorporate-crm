<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';
    protected $fillable = [
        'user_id',
        'emp_code',
        'company_id',
        'department_id',
        'team_id',
        'shift_id',
        'in_time',
        'out_time',
        'duration',
        'late_by',
        'early_by',
        'status',
        'punch_records',
        'dated'
    ];



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
