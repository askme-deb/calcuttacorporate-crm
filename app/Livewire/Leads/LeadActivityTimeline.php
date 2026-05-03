<?php
namespace App\Livewire\Leads;

use Livewire\Component;
use App\Models\Lead;

class LeadActivityTimeline extends Component
{
    public $leadId;
    public $activities = [];

    protected $listeners = ['refreshActivityTimeline' => 'loadActivities'];

    public function mount($leadId)
    {
        $this->leadId = $leadId;
        $this->loadActivities();
    }

    public function loadActivities()
    {
        $this->activities = Lead::findOrFail($this->leadId)->activities()->with('user')->latest('activity_at')->get();
    }

    public function render()
    {
        return view('livewire.leads.lead-activity-timeline');
    }
}
