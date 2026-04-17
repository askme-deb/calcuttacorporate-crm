<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryComponentMaster extends Model
{
    use HasFactory;

    protected $table = 'salary_component_masters';

    protected $fillable = [
        'name',
        'type',
        'default_percentage',
        'default_amount',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'default_percentage' => 'decimal:2',
        'default_amount' => 'decimal:2',
    ];
}
