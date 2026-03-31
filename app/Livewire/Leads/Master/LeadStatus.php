<?php

namespace App\Livewire\Leads\Master;

use App\Models\LeadStatus as ModelsLeadStatus;
use Livewire\Component;

class LeadStatus extends Component
{
    public $leadstatus;
    public $name;
    public $showModal = false;
    public $modalMode = '';
    public $leadStatusId;
    public $is_visible;
    public $selectedIsVisible;
    public $leadStatusData;
    public function rules()
    {
        return [
            'name' => in_array($this->modalMode, ['create', 'edit']) ? 'required' : 'nullable',
           // 'phone' => in_array($this->modalMode, ['create', 'edit']) ? 'required|numeric' : 'nullable',
        ];
    }

    protected $listeners = ['deleteItem', 'refreshComponent' => 'loadLeaStatus'];

    public function addLeadStatus()
    {
        $this->showModal = true;
        $this->is_visible = true;
        $this->modalMode = 'create';
    }

    public function createLeadStatus()
    {
        $this->validate();
        ModelsLeadStatus::create(
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
        $this->leadStatusId = $id;
        $this->leadStatusData = ModelsLeadStatus::findOrFail($id);
        $this->selectedIsVisible = $this->leadStatusData->is_visible==1 ?? true;
        $this->name = $this->leadStatusData->name;
    }

    public function update()
    {
        $this->validate();
        $record = ModelsLeadStatus::findOrFail($this->leadStatusId);
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
        $this->loadLeaStatus();
    }

    public function render()
    {
        return view('livewire.leads.master.lead-status');
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
        $item = ModelsLeadStatus::find($id);
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
    public function loadLeaStatus()
    {
        $this->leadstatus = ModelsLeadStatus::orderBy('id', 'desc')
        ->get();
    }

}
