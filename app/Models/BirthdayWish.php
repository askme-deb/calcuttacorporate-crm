<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BirthdayWish extends Model
{
    protected $fillable = [
        'employee_id',
        'sent_by',
        'message',
        'sent_at',
    ];

    /**
     * Employee who receives the birthday wish
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Employee who sent the birthday wish
     */
    public function sender()
    {
        return $this->belongsTo(Employee::class, 'sent_by');
    }
}
