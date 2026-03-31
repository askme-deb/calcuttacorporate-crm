<?php


namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Notifications extends Component
{
    public $notifications = [];

    protected $listeners = ['notificationAdded' => 'fetchNotifications'];

    public function mount()
    {
        $this->fetchNotifications();
    }

    public function fetchNotifications()
    {
        $this->notifications = Auth::user()?->unreadNotifications ?? collect();
    }

    public function markAsRead($notificationId)
    {
        if ($user = Auth::user()) {
            $user->notifications()->where('id', $notificationId)->update(['read_at' => now()]);
            $this->fetchNotifications();
        }
        return redirect()->route('worksheet');
    }

    public function render()
    {
        return view('livewire.notifications', [
            'notifications' => $this->notifications,
        ]);
    }
}
