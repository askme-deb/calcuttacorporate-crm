<?php
namespace App\Livewire\Leads;

use Livewire\Component;
use App\Models\Lead;
use App\Models\LeadReminder;

class LeadReminderManager extends Component
{
    public $leadId;
    public $reminders = [];
    public $title = '';
    public $description = '';
    public $remind_at = '';

    public function mount($leadId)
    {
        $this->leadId = $leadId;
        $this->reminders = Lead::findOrFail($leadId)->reminders()->latest('remind_at')->get();
    }

    public function addReminder()
    {
        $this->validate([
            'title' => 'required',
            'remind_at' => 'required|date',
        ]);
        LeadReminder::create([
            'lead_id' => $this->leadId,
            'title' => $this->title,
            'description' => $this->description,
            'remind_at' => $this->remind_at,
            'user_id' => auth()->id(),
        ]);
        $this->mount($this->leadId);
        $this->reset(['title', 'description', 'remind_at']);
    }

    public function render()
    {
        return view('livewire.leads.lead-reminder-manager');
    }
}
