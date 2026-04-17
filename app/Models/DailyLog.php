<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'log_date', 'task_summary', 'hours_worked', 'remarks'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
