<?php

namespace App\Livewire\Leads\Master;

use App\Models\LeadPriority as ModelsLeadPriority;
use Livewire\Component;

class LeadPriority extends Component
{
    public $leadPriorities;
    public $name;
    public $showModal = false;
    public $modalMode = '';
    public $leadPriorityId;
    // public $data = [];
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

    protected $listeners = ['deleteItem', 'refreshComponent' => 'loadLeaPriority'];

    public function addLeadPriority()
    {
        $this->showModal = true;
        $this->is_visible = true;
        $this->modalMode = 'create';
    }

    public function createLeadPriority()
    {
        $this->validate();
        ModelsLeadPriority::create(
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
        $this->leadPriorityId = $id;
        $this->leadPriorityData = ModelsLeadPriority::findOrFail($id);
        $this->selectedIsVisible = $this->leadPriorityData->is_visible==1 ?? true;
        $this->name = $this->leadPriorityData->name;
    }

    public function update()
    {
        $this->validate();
        $record = ModelsLeadPriority::findOrFail($this->leadPriorityId);
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
        $this->loadLeaPriority();
    }

    public function render()
    {
        return view('livewire.leads.master.lead-priority');
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
        $item = ModelsLeadPriority::find($id);
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
    public function loadLeaPriority()
    {
        $this->leadPriorities = ModelsLeadPriority::orderBy('id', 'desc')
        ->get();
    }


}
