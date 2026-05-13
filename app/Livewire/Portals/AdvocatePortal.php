<?php

namespace App\Livewire\Portals;

use App\Models\MeetingSummary;
use Livewire\Component;

class AdvocatePortal extends Component
{
    public function render()
    {
        $legalMeetings = MeetingSummary::where('department', 'Legal')
            ->latest('meeting_date')
            ->take(8)
            ->get();

        $upcomingFollowups = MeetingSummary::where('department', 'Legal')
            ->whereNotNull('next_follow_up_date')
            ->orderBy('next_follow_up_date')
            ->take(5)
            ->get();

        return view('livewire.portals.advocate-portal', [
            'legalMeetings' => $legalMeetings,
            'upcomingFollowups' => $upcomingFollowups,
        ]);
    }
}
