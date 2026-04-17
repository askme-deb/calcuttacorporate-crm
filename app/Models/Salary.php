<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = ['employee_id', 'basic_salary', 'month'];

    public function components()
    {
        return $this->hasMany(SalaryComponent::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
