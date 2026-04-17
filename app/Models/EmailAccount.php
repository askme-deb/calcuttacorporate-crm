<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailAccount extends Model
{
    protected $fillable = [
        'user_id',
        'email_address', 'name',
        'imap_host', 'imap_port', 'imap_encryption',
        'smtp_host', 'smtp_port', 'smtp_encryption',
        'smtp_username', 'smtp_password', 'active'
    ];

    public function emails()
    {
        return $this->hasMany(Email::class, 'email_account_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
