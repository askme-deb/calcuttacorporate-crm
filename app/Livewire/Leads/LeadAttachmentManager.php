<?php
namespace App\Livewire\Leads;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Lead;
use App\Models\LeadAttachment;

class LeadAttachmentManager extends Component
{
    use WithFileUploads;

    public $leadId;
    public $attachments = [];
    public $file;

    public function mount($leadId)
    {
        $this->leadId = $leadId;
        $this->attachments = Lead::findOrFail($leadId)->attachments()->latest()->get();
    }

    public function upload()
    {
        $this->validate(['file' => 'required|file|max:10240']);
        $path = $this->file->store('lead-attachments');
        LeadAttachment::create([
            'lead_id' => $this->leadId,
            'file_path' => $path,
            'file_name' => $this->file->getClientOriginalName(),
            'user_id' => auth()->id(),
        ]);
        $this->mount($this->leadId);
        $this->reset('file');
    }

    public function render()
    {
        return view('livewire.leads.lead-attachment-manager');
    }
}
