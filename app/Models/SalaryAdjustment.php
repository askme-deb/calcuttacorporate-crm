<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryAdjustment extends Model
{
   protected $fillable = [
        'employee_id',
        'previous_salary',
        'new_salary',
        'adjustment_amount',
        'adjustment_percentage',
        'adjustment_type',
        'reason',
        'effective_date',
        'approved_by'
    ];

    protected $casts = [
        'effective_date' => 'date',
        'previous_salary' => 'decimal:2',
        'new_salary' => 'decimal:2',
        'adjustment_amount' => 'decimal:2',
        'adjustment_percentage' => 'decimal:2'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

   protected static function boot()
{
    parent::boot();

    static::creating(function ($adjustment) {
        $adjustment->adjustment_amount = $adjustment->new_salary - $adjustment->previous_salary;

        if ($adjustment->previous_salary > 0) {
            $adjustment->adjustment_percentage = ($adjustment->adjustment_amount / $adjustment->previous_salary) * 100;
        } else {
            $adjustment->adjustment_percentage = 0; // or null depending on your use case
        }
    });
}

}
