<?php
namespace App\Livewire\Leads;

use Livewire\Component;
use App\Models\Lead;
use App\Models\LeadTag;

class LeadTagManager extends Component
{
    public $leadId;
    public $tags = [];
    public $newTag = '';

    public function mount($leadId)
    {
        $this->leadId = $leadId;
        $this->tags = Lead::findOrFail($leadId)->tags()->pluck('tag')->toArray();
    }

    public function addTag()
    {
        $this->validate(['newTag' => 'required']);
        LeadTag::create([
            'lead_id' => $this->leadId,
            'tag' => $this->newTag,
        ]);
        $this->mount($this->leadId);
        $this->reset('newTag');
    }

    public function removeTag($tag)
    {
        LeadTag::where('lead_id', $this->leadId)->where('tag', $tag)->delete();
        $this->mount($this->leadId);
    }

    public function render()
    {
        return view('livewire.leads.lead-tag-manager');
    }
}
