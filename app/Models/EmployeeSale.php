<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSale extends Model
{
    protected $fillable = ['user_id', 'month', 'target', 'achieved'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}


