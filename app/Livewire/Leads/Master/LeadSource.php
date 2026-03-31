<?php

namespace App\Livewire\Leads\Master;

use App\Models\LeadSource as ModelsLeadSource;
use Livewire\Component;

class LeadSource extends Component
{
    public $leadsources;
    public $name;
    public $showModal = false;
    public $modalMode = '';
    public $leadSourceId;
    public $is_visible;
    public $selectedIsVisible;
    public $leadSourceData;
    public function rules()
    {
        return [
            'name' => in_array($this->modalMode, ['create', 'edit']) ? 'required' : 'nullable',
           // 'phone' => in_array($this->modalMode, ['create', 'edit']) ? 'required|numeric' : 'nullable',

        ];
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
        ModelsLeadSource::create(
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
        $this->leadSourceData = ModelsLeadSource::findOrFail($id);
        $this->selectedIsVisible = $this->leadSourceData->is_visible==1 ?? true;
        $this->name = $this->leadSourceData->name;
    }

    public function update()
    {
        $this->validate();
        $record = ModelsLeadSource::findOrFail($this->leadSourceId);
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
        return view('livewire.leads.master.lead-source');
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
        $item = ModelsLeadSource::find($id);
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
        $this->leadsources = ModelsLeadSource::orderBy('id', 'desc')
        ->get();
    }


}
