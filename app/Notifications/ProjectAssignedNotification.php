<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Facades\Auth;
class ProjectAssignedNotification extends Notification
{
    use Queueable;

    protected $project;
    
    public function __construct($project)
    {
        $this->project = $project;
    }

    public function via($notifiable)
    {
        return ['database']; // Store notification in the database
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Project Assigned',
            'id' => $this->project->id,
            'name' => $this->project->title,
            'created_by' => auth()->user()->name
        ];
    }
}
