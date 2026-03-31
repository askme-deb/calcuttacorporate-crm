<?php

namespace App\Livewire\Employees\Master;

use App\Models\Institute as ModelsInstitute;
use Livewire\Component;

class Institute extends Component
{
    public $institutes;
    public $name;
    public $showModal = false;
    public $modalMode = '';
    public $instituteId;
    public $is_visible;
    public $selectedIsVisible;
    public $instituteData;
    public function rules()
    {
        $rules = [];

        if (in_array($this->modalMode, ['create', 'edit'])) {
            $rules['name'] = 'required|unique:institutes,name';
        } else {
            $rules['name'] = 'nullable';
        }

        if ($this->modalMode === 'edit') {
            $rules['name'] = 'required|string|min:3|unique:institutes,name,' . $this->instituteId;
        }

        return $rules;
    }

    protected $listeners = ['deleteItem', 'refreshComponent' => 'getInstitute'];

    public function addInstitute()
    {
        $this->showModal = true;
        $this->is_visible = true;
        $this->modalMode = 'create';
    }

    public function createInstitute()
    {
        $this->validate();
        ModelsInstitute::create(
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
        $this->instituteId = $id;
        $this->instituteData = ModelsInstitute::findOrFail($id);
        $this->selectedIsVisible = $this->instituteData->is_visible == 1 ?? true;
        $this->name = $this->instituteData->name;
    }

    public function update()
    {
        $this->validate();
        $record = ModelsInstitute::findOrFail($this->instituteId);
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
        $this->getInstitute();
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
        $item = ModelsInstitute::find($id);
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
    public function getInstitute()
    {
        $this->institutes = ModelsInstitute::orderBy('id', 'desc')
            ->get();
    }
    public function render()
    {
        return view('livewire.employees.master.institute');
    }
}
