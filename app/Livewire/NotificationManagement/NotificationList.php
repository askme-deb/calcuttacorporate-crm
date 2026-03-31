<?php

namespace App\Livewire\NotificationManagement;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationList extends Component
{

    use WithPagination;

    public function markAsRead($notificationId)
    {
        Auth::user()->notifications()->where('id', $notificationId)->update(['read_at' => now()]);
        $this->dispatch('notificationUpdated'); // dispatch event to update other components if needed
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->dispatch('notificationUpdated');
    }

    public function clearAll()
    {
        Auth::user()->notifications()->delete();
        $this->dispatch('notificationUpdated');
    }


    public function render()
    {
        return view('livewire.notification-management.notification-list', [
            'notifications' => Auth::user()->notifications()->latest()->paginate(10)
        ]);
    }
    
}
