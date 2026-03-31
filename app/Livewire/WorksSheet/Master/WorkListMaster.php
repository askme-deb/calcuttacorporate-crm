<?php

namespace App\Livewire\WorksSheet\Master;

use App\Models\WorkMaster;
use Livewire\Component;

class WorkListMaster extends Component
{

    public $worklist;
    public $name;
    public $showModal = false;
    public $modalMode = '';
    public $leadSourceId;
    public $is_visible;
    public $selectedIsVisible;
    public $worklistData;
    public function rules()
    {
        $rules = [];
    
        if (in_array($this->modalMode, ['create', 'edit'])) {
            $rules['name'] = 'required|unique:work_master,name';
        } else {
            $rules['name'] = 'nullable';
        }
    
        if ($this->modalMode === 'edit') {
            $rules['name'] = 'required|string|min:3|unique:work_master,name,' . $this->leadSourceId;
        }
    
        return $rules;
    }
    

    protected $listeners = ['deleteItem', 'refreshComponent' => 'loadLeaSource'];

    public function addLeadSource()
    {
        $this->showModal = true;
        $this->is_visible = true;
        $this->modalMode = 'create';
    }

    public function createLeadSource()
    {
        $this->validate();
        WorkMaster::create(
            [
                'name' => $this->pull('name'),
                'is_visible' => $this->pull('is_visible')
            ]
        );
        $this->dispatch('refreshComponent');
        $this->closeModal();
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Permission Created successfully'
        ]));

    }

    public function edit($id)
    {
        $this->showModal = true;
        $this->modalMode = 'edit';
        $this->leadSourceId = $id;
        $this->worklistData = WorkMaster::findOrFail($id);
        $this->selectedIsVisible = $this->worklistData->is_visible==1 ?? true;
        $this->name = $this->worklistData->name;
    }

    public function update()
    {
        $this->validate();
        $record = WorkMaster::findOrFail($this->leadSourceId);
        $record->update(
            [
                'name' => $this->pull('name'),
                'is_visible' => $this->pull('selectedIsVisible')
            ]
        );
        $this->closeModal();
        $this->dispatch('refreshComponent');
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Data Updated successfully'
        ]));

    }

    public function mount(){
        $this->loadLeaSource();
    }

    public function render()
    {
        return view('livewire.works-sheet.master.work-list-master');
    }


    public function closeModal()
    {
        $this->reset('name');
        $this->reset('selectedIsVisible');
        $this->reset('is_visible');
        $this->reset('showModal');
    }


    public function deleteItem($id)
    {
        $item = WorkMaster::find($id);
        if ($item) {
            $item->delete();
            $this->dispatch('refreshComponent');
            $this->dispatch('swal:success', json_encode([
                'title' => 'Item Deleted',
                'text' => 'The Data has been successfully deleted.',
                'icon' => 'success',
            ]));
        }
    }
    public function loadLeaSource()
    {
        $this->worklist = WorkMaster::orderBy('id', 'desc')
        ->get();
    }

}


