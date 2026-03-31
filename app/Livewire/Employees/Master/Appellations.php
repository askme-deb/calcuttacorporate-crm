<?php

namespace App\Livewire\Employees\Master;

use App\Models\Appellation;
use Livewire\Component;

class Appellations extends Component
{
    public $appellations;
    public $name;
    public $showModal = false;
    public $modalMode = '';
    public $appellationId;
    public $is_visible;
    public $selectedIsVisible;
    public $appellationData;
    public function rules()
    {
        $rules = [];

        if (in_array($this->modalMode, ['create', 'edit'])) {
            $rules['name'] = 'required|unique:appellations,name';
        } else {
            $rules['name'] = 'nullable';
        }

        if ($this->modalMode === 'edit') {
            $rules['name'] = 'required|string|min:3|unique:appellations,name,' . $this->appellationId;
        }

        return $rules;
    }

    protected $listeners = ['deleteItem', 'refreshComponent' => 'getAppellation'];

    public function addAppellation()
    {
        $this->showModal = true;
        $this->is_visible = true;
        $this->modalMode = 'create';
    }

    public function createAppellation()
    {
        $this->validate();
        Appellation::create(
            [
                'name' => $this->pull('name'),
                'is_visible' => $this->pull('is_visible')
            ]
        );
        $this->dispatch('refreshComponent');
        $this->closeModal();
        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Permission Created successfully'
        ]));
    }

    public function edit($id)
    {
        $this->showModal = true;
        $this->modalMode = 'edit';
        $this->appellationId = $id;
        $this->appellationData = Appellation::findOrFail($id);
        $this->selectedIsVisible = $this->appellationData->is_visible == 1 ?? true;
        $this->name = $this->appellationData->name;
    }

    public function update()
    {
        $this->validate();
        $record = Appellation::findOrFail($this->appellationId);
        $record->update(
            [
                'name' => $this->pull('name'),
                'is_visible' => $this->pull('selectedIsVisible')
            ]
        );
        $this->closeModal();
        $this->dispatch('refreshComponent');
        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Data Updated successfully'
        ]));
    }

    public function mount()
    {
        $this->getAppellation();
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
        $item = Appellation::find($id);
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
    public function getAppellation()
    {
        $this->appellations = Appellation::orderBy('id', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.employees.master.appellations');
    }
}
