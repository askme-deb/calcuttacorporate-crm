<?php
namespace App\Livewire\Leads;

use Livewire\Component;
use App\Models\Lead;

class LeadActivityTimeline extends Component
{
    public $leadId;
    public $activities = [];

    public function mount($leadId)
    {
        $this->leadId = $leadId;
        $this->activities = Lead::findOrFail($leadId)->activities()->latest('activity_at')->get();
    }

    public function render()
    {
        return view('livewire.leads.lead-activity-timeline');
    }
}
