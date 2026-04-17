<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Email extends Model implements HasMedia
{
    use InteractsWithMedia;

    // In your Email model
    protected $fillable = [
        'uid',
        'reply_to_id',
        'message_id',
        'in_reply_to',
        'user_id',
        'folder',
        'subject',
        'from',
        'to',
        'cc',
        'bcc',
        'date',
        'seen',
        'answered',
        'deleted',
        'flagged',
        'has_attachments',
        'size',
        'body',
        'body_plain',
        'msgno',
        'email_account_id'
    ];

    // Define a media collection for attachments
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')->useDisk('public');
    }

    // An email can have many replies
    public function replies()
    {
        return $this->hasMany(Email::class, 'reply_to_id', 'id')->orderBy('date', 'asc');
    }

    // Each email may belong to a parent (if it's a reply)
    public function parent()
    {
        return $this->belongsTo(Email::class, 'reply_to_id', 'id');
    }

    public function account()
    {
        return $this->belongsTo(EmailAccount::class, 'email_account_id');
    }
}
