<?php

namespace App\Livewire\Deals\Master;

use App\Models\DealStatus as ModelsDealStatus;
use Livewire\Component;

class Dealstatus extends Component
{
    public $dealstatus;
    public $name;
    public $showModal = false;
    public $modalMode = '';
    public $dealStatusId;
    public $is_visible;
    public $selectedIsVisible;
    public $dealStatusData;
    public function rules()
    {
        return [
            'name' => in_array($this->modalMode, ['create', 'edit']) ? 'required' : 'nullable',
           // 'phone' => in_array($this->modalMode, ['create', 'edit']) ? 'required|numeric' : 'nullable',
        ];
    }

    protected $listeners = ['deleteItem', 'refreshComponent' => 'loadDealStatus'];

    public function addDealStatus()
    {
        $this->showModal = true;
        $this->is_visible = true;
        $this->modalMode = 'create';
    }

    public function createDealStatus()
    {
        $this->validate();
        ModelsDealStatus::create(
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
        $this->dealStatusId = $id;
        $this->dealStatusData = ModelsDealStatus::findOrFail($id);
        $this->selectedIsVisible = $this->dealStatusData->is_visible==1 ?? true;
        $this->name = $this->dealStatusData->name;
    }

    public function update()
    {
        $this->validate();
        $record = ModelsDealStatus::findOrFail($this->dealStatusId);
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
        $this->loadDealStatus();
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
        $item = ModelsDealStatus::find($id);
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
    public function loadDealStatus()
    {
        $this->dealstatus = ModelsDealStatus::orderBy('id', 'desc')
        ->get();
    }



    public function render()
    {
        return view('livewire.deals.master.dealstatus');
    }
}
