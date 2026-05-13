<?php

namespace App\Livewire\Portals;

use App\Models\MeetingSummary;
use Illuminate\Support\Collection;
use Livewire\Component;

class ClientPortal extends Component
{
    public function render()
    {
        $clientMeetings = MeetingSummary::where(function ($query) {
            $query->where('meeting_attended', 'Employee with Client')
                ->orWhereNotNull('client_id');
        })
            ->latest('meeting_date')
            ->take(10)
            ->get();

        $taskRows = $clientMeetings
            ->flatMap(function (MeetingSummary $meeting): Collection {
                return collect($meeting->action_items ?? [])->map(function (array $item) use ($meeting) {
                    return [
                        'meeting' => $meeting->client_name ?: $meeting->meeting_type,
                        'summary' => $item['summary'] ?? $item['task'] ?? '',
                        'deadline' => $item['deadline'] ?? null,
                        'details' => $item['details'] ?? '',
                        'status' => $item['status'] ?? 'Pending',
                        'uploaded' => (bool) ($item['uploaded'] ?? false),
                    ];
                });
            })
            ->sortBy('deadline')
            ->take(12);

        return view('livewire.portals.client-portal', [
            'clientMeetings' => $clientMeetings,
            'taskRows' => $taskRows,
        ]);
    }
}
