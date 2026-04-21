<?php
namespace App\Livewire\Leads;

use Livewire\Component;
use App\Models\Lead;

class LeadPipelineBoard extends Component
{
    public $statuses = [
        'new' => 'New Lead',
        'contacted' => 'Contacted',
        'qualified' => 'Qualified',
        'proposal_sent' => 'Proposal/Quotation Sent',
        'negotiation' => 'Negotiation',
        'won' => 'Won',
        'lost' => 'Lost',
    ];

    public $leadsByStatus = [];
    public $viewMode = 'kanban'; // or 'table'

    public function mount()
    {
        foreach (array_keys($this->statuses) as $status) {
            $this->leadsByStatus[$status] = Lead::where('status', $status)->get();
        }
    }

    public function updateLeadStatus($leadId, $newStatus)
    {
        $lead = Lead::findOrFail($leadId);
        $lead->status = $newStatus;
        $lead->save();
        $this->mount();
        // Smart trigger: open proposal editor if needed
        if (in_array($newStatus, ['proposal_sent'])) {
            $this->dispatch('openProposalEditor', leadId: $leadId);
        }
    }

    public function render()
    {
        return view('livewire.leads.lead-pipeline-board');
    }
}
