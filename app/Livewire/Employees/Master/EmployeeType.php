<?php

namespace App\Livewire\Employees\Master;

use App\Models\Emptype;
use Livewire\Component;

class EmployeeType extends Component
{

    public $emptypes;
    public $name;
    public $showModal = false;
    public $modalMode = '';
    public $emptypeId;
    public $is_visible;
    public $selectedIsVisible;
    public $emptypeData;
    public function rules()
    {
        $rules = [];

        if (in_array($this->modalMode, ['create', 'edit'])) {
            $rules['name'] = 'required|unique:emptypes,name';
        } else {
            $rules['name'] = 'nullable';
        }

        if ($this->modalMode === 'edit') {
            $rules['name'] = 'required|string|min:3|unique:emptypes,name,' . $this->emptypeId;
        }

        return $rules;
    }

    protected $listeners = ['deleteItem', 'refreshComponent' => 'getemptype'];

    public function addEmptype()
    {
        $this->showModal = true;
        $this->is_visible = true;
        $this->modalMode = 'create';
    }

    public function createEmptype()
    {
        $this->validate();
        Emptype::create(
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
        $this->emptypeId = $id;
        $this->emptypeData = Emptype::findOrFail($id);
        $this->selectedIsVisible = $this->emptypeData->is_visible == 1 ?? true;
        $this->name = $this->emptypeData->name;
    }

    public function update()
    {
        $this->validate();
        $record = Emptype::findOrFail($this->emptypeId);
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
        $this->getemptype();
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
        $item = Emptype::find($id);
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
    public function getemptype()
    {
        $this->emptypes = Emptype::orderBy('id', 'desc')
            ->get();
    } 
    public function render()
    {
        return view('livewire.employees.master.employee-type');
    }
}
