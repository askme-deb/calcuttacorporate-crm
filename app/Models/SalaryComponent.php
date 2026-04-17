<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryComponent extends Model
{
    protected $fillable = ['salary_id', 'type', 'name', 'amount', 'percentage', 'month'];

    public function salary()
    {
        return $this->belongsTo(Salary::class);
    }
}
