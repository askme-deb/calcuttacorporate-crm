<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Promotion extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'previous_designation_id',
        'new_designation_id',
        'previous_salary',
        'new_salary',
        'promotion_date',
        'remarks',
    ];

    protected $casts = [
        'promotion_date' => 'date',
        'previous_salary' => 'decimal:2',
        'new_salary' => 'decimal:2',
    ];

    // Employee who got promoted (references employees table)
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    // Previous designation
    public function previousDesignation()
    {
        return $this->belongsTo(Designation::class, 'previous_designation_id');
    }

    // New designation
    public function newDesignation()
    {
        return $this->belongsTo(Designation::class, 'new_designation_id');
    }

    // Access the user through the employee relationship
    public function user()
    {
        return $this->hasOneThrough(User::class, Employee::class, 'id', 'id', 'employee_id', 'user_id');
    }

    // Helper method to get salary increase
    public function getSalaryIncreaseAttribute()
    {
        if ($this->previous_salary && $this->new_salary) {
            return $this->new_salary - $this->previous_salary;
        }
        return 0;
    }

    
    // Helper method to get salary increase percentage
    public function getSalaryIncreasePercentageAttribute()
    {
        if ($this->previous_salary && $this->new_salary && $this->previous_salary > 0) {
            return round((($this->new_salary - $this->previous_salary) / $this->previous_salary) * 100, 2);
        }
        return 0;
    }
}