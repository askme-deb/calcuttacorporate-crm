<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Promotionss extends Model implements HasMedia
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

    // Employee who got promoted
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
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
}
