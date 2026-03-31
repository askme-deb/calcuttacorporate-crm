<?php

namespace App\Livewire\Employees\Master;

use App\Models\Designation;
use Livewire\Component;

class Designations extends Component
{
    public $designations;
    public $name;
    public $showModal = false;
    public $modalMode = '';
    public $designationId;
    public $is_visible;
    public $selectedIsVisible;
    public $designationData;
    public function rules()
    {
        $rules = [];

        if (in_array($this->modalMode, ['create', 'edit'])) {
            $rules['name'] = 'required|unique:designations,name';
        } else {
            $rules['name'] = 'nullable';
        }

        if ($this->modalMode === 'edit') {
            $rules['name'] = 'required|string|min:3|unique:designations,name,' . $this->designationId;
        }

        return $rules;
    }

    protected $listeners = ['deleteItem', 'refreshComponent' => 'getdesignation'];

    public function addDesignation()
    {
        $this->showModal = true;
        $this->is_visible = true;
        $this->modalMode = 'create';
    }

    public function createDesignation()
    {
        $this->validate();
        Designation::create(
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
        $this->designationId = $id;
        $this->designationData = Designation::findOrFail($id);
        $this->selectedIsVisible = $this->designationData->is_visible == 1 ?? true;
        $this->name = $this->designationData->name;
    }

    public function update()
    {
        $this->validate();
        $record = Designation::findOrFail($this->designationId);
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
        $this->getdesignation();
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
        $item = Designation::find($id);
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
    public function getdesignation()
    {
        $this->designations = Designation::orderBy('id', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.employees.master.designations');
    }
}


