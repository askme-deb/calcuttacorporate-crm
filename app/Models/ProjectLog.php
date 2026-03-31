<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectLog extends Model
{
    use HasFactory;

    protected $table = 'project_logs';
    protected $fillable = ['project_id', 'user_id', 'action', 'notes', 'action_time'];

    public function project()
    {
        return $this->belongsTo(Worksheet::class, 'project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
