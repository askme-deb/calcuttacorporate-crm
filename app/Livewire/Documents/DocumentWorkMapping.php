<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use App\Models\ListOfDocument;
use App\Models\WorkMaster;
class DocumentWorkMapping extends Component
{
  public $documents;
    public $works;
    public $selectedDocument;
    public $selectedWorks = [];
    protected $casts = [
        'selectedDocument' => 'int',
    ];
    public function mount()
    {

        $this->documents = ListOfDocument::all();
        $this->works = WorkMaster::where('is_visible', 1)->get();
    }

    protected $listeners = ['updateLeaveType'];


    public function updateSelectedDocument($value)
    {
      $this->selectedDocument = (int) $value;
        $doc = ListOfDocument::find($this->selectedDocument);
        $this->selectedWorks = $doc ? $doc->works()->pluck('work_master_id')->toArray() : [];
    
    }


    // public function updatedSelectedDocument($documentId)
    // {
    //      $this->selectedDocument = (int) $documentId;
    //     $doc = ListOfDocument::find($this->selectedDocument);
    //     $this->selectedWorks = $doc ? $doc->works()->pluck('work_master_id')->toArray() : [];
    // }

    public function saveMapping()
    {
        $doc = ListOfDocument::find($this->selectedDocument);
        if ($doc) {
            $doc->works()->sync($this->selectedWorks);

            $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Mapping updated successfully!'
        ]));
           
        }
    }



    public function render()
    {
        return view('livewire.documents.document-work-mapping');
    }
}
