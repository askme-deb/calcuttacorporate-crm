<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadReminder extends Model
{
    protected $fillable = [
        'lead_id', 'title', 'description', 'remind_at', 'user_id'
    ];

    protected $casts = [
        'remind_at' => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
