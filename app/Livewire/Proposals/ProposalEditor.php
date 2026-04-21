<?php
namespace App\Livewire\Proposals;

use Livewire\Component;
use App\Models\Lead;
use App\Models\Proposal;
use App\Models\ProposalItem;

class ProposalEditor extends Component
{
    public $leadId;
    public $proposalId;
    public $type = 'quotation';
    public $title;
    public $client_details;
    public $project_scope;
    public $items = [];
    public $taxes_enabled = false;
    public $tax_percent = 18;
    public $notes;
    public $terms;
    public $total_amount = 0;
    public $status = 'draft';
    public $rich_content = '';

    protected $listeners = ['openProposalEditor' => 'openForLead'];

    public function mount($leadId = null, $proposalId = null)
    {
        if ($leadId) $this->openForLead($leadId);
        if ($proposalId) $this->loadProposal($proposalId);
    }

    public function openForLead($leadId)
    {
        $this->leadId = $leadId;
        $lead = Lead::findOrFail($leadId);
        $this->title = 'Quotation for ' . $lead->company;
        $this->client_details = $lead->name . ' (' . $lead->company . ')';
        $this->items = [
            ['item_name' => '', 'description' => '', 'quantity' => 1, 'price' => 0, 'total' => 0],
        ];
        $this->notes = '';
        $this->terms = '';
        $this->total_amount = 0;
        $this->status = 'draft';
        $this->type = 'quotation';
        $this->rich_content = '';
    }

    public function loadProposal($proposalId)
    {
        $proposal = Proposal::with('items')->findOrFail($proposalId);
        $this->proposalId = $proposal->id;
        $this->leadId = $proposal->lead_id;
        $this->type = $proposal->type;
        $this->title = $proposal->title;
        $this->rich_content = $proposal->content;
        $this->total_amount = $proposal->total_amount;
        $this->status = $proposal->status;
        $this->items = $proposal->items->toArray();
    }

    public function addItem()
    {
        $this->items[] = ['item_name' => '', 'description' => '', 'quantity' => 1, 'price' => 0, 'total' => 0];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function updatedItems()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $total = 0;
        foreach ($this->items as &$item) {
            $item['total'] = $item['quantity'] * $item['price'];
            $total += $item['total'];
        }
        if ($this->taxes_enabled) {
            $total += $total * ($this->tax_percent / 100);
        }
        $this->total_amount = $total;
    }

    public function saveDraft()
    {
        $proposal = Proposal::updateOrCreate([
            'id' => $this->proposalId
        ], [
            'lead_id' => $this->leadId,
            'type' => $this->type,
            'title' => $this->title,
            'content' => $this->rich_content,
            'total_amount' => $this->total_amount,
            'status' => 'draft',
        ]);
        $this->proposalId = $proposal->id;
        $proposal->items()->delete();
        foreach ($this->items as $item) {
            $proposal->items()->create($item);
        }
        // After saving draft, show preview
        $this->dispatch('showProposalPreview', $proposal->id);
        session()->flash('success', 'Proposal saved as draft.');
    }

    public function sendProposal()
    {
        $this->saveDraft();
        $proposal = Proposal::find($this->proposalId);
        $proposal->status = 'sent';
        $proposal->sent_at = now();
        $proposal->save();
        // After sending, show preview
        $this->dispatch('showProposalPreview', $proposal->id);
        session()->flash('success', 'Proposal sent to customer.');
    }

    public function render()
    {
        return view('livewire.proposals.proposal-editor');
    }
}
