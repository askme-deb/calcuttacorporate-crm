<?php

namespace App\Livewire\Leads\Master;

use App\Models\LeadSector as ModelsLeadSector;
use Livewire\Component;

class LeadSector extends Component
{
    public $leadsectors;
    public $name;
    public $showModal = false;
    public $modalMode = '';
    public $leadSectorId;
    public $is_visible;
    public $selectedIsVisible;
    public $leadPriorityData;
    public function rules()
    {
        return [
            'name' => in_array($this->modalMode, ['create', 'edit']) ? 'required' : 'nullable',
           // 'phone' => in_array($this->modalMode, ['create', 'edit']) ? 'required|numeric' : 'nullable',

        ];
    }

    protected $listeners = ['deleteItem', 'refreshComponent' => 'loadLeaSector'];

    public function addLeadSector()
    {
        $this->showModal = true;
        $this->is_visible = true;
        $this->modalMode = 'create';
    }

    public function createLeadSector()
    {
        $this->validate();
        ModelsLeadSector::create(
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
        $this->leadSectorId = $id;
        $this->leadPriorityData = ModelsLeadSector::findOrFail($id);
        $this->selectedIsVisible = $this->leadPriorityData->is_visible==1 ?? true;
        $this->name = $this->leadPriorityData->name;
    }

    public function update()
    {
        $this->validate();
        $record = ModelsLeadSector::findOrFail($this->leadSectorId);
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
        $this->loadLeaSector();
    }

    public function render()
    {
        return view('livewire.leads.master.lead-sector');
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
        $item = ModelsLeadSector::find($id);
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
    public function loadLeaSector()
    {
        $this->leadsectors = ModelsLeadSector::orderBy('id', 'desc')
        ->get();
    }


}
