<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryRecord extends Model
{
    protected $fillable = [
        'employee_id',
        'base_salary',
        'allowances',
        'bonuses',
        'deductions',
        'gross_salary',
        'tax_deduction',
        'net_salary',
        'effective_date',
        'end_date',
        'notes',
        'status'
    ];

    protected $casts = [
        'effective_date' => 'date',
        'end_date' => 'date',
        'base_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'bonuses' => 'decimal:2',
        'deductions' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'tax_deduction' => 'decimal:2',
        'net_salary' => 'decimal:2'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($record) {
            $record->gross_salary = $record->base_salary + $record->allowances + $record->bonuses - $record->deductions;
            $record->net_salary = $record->gross_salary - $record->tax_deduction;
        });
    }
}
