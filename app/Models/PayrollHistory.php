<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollHistory extends Model
{
    protected $table = 'payroll_history';

    protected $fillable = [
        'employee_id',
        'salary_record_id',
        'pay_period',
        'pay_date',
        'gross_pay',
        'net_pay',
        'deductions',
        'bonuses',
        'status'
    ];

    protected $casts = [
        'pay_date' => 'date',
        'gross_pay' => 'decimal:2',
        'net_pay' => 'decimal:2',
        'deductions' => 'array',
        'bonuses' => 'array'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function salaryRecord()
    {
        return $this->belongsTo(SalaryRecord::class);
    }
}
