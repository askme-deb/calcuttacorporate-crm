<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = ['employee_id', 'month', 'gross_salary', 'net_salary', 'is_paid'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function items()
    {
        return $this->hasMany(PayrollItem::class);
    }


    public function basic()
    {
        return $this->hasMany(PayrollItem::class, 'payroll_id')->where('type', 'basic');
    }

    public function allowances()
    {
        return $this->hasMany(PayrollItem::class, 'payroll_id')->where('type', 'allowance');
    }

    public function deductions()
    {
        return $this->hasMany(PayrollItem::class, 'payroll_id')->where('type', 'deduction');
    }

/**
     * Dynamic Gross Salary (Basic + Allowances)
     */
    public function getGrossSalaryAttribute()
    {
        $basic = $this->basic->sum('amount');
        $allowances = $this->allowances->sum('amount');

        return $basic + $allowances;
    }

    /**
     * Dynamic Net Salary (Gross - Deductions)
     */
    public function getNetSalaryAttribute()
    {
        return $this->gross_salary - $this->deductions->sum('amount');
    }
    
}
