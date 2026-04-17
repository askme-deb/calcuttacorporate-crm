<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Termination extends Model
{
    protected $fillable = ['employee_id', 'termination_date', 'reason', 'remarks', 'status'];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
