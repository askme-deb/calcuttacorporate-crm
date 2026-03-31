<?php

namespace App\Livewire\Employees\Master;

use App\Models\Appointedorganization;
use Livewire\Component;

class AppointedOrganisation extends Component
{
    public $organisations;
    public $name;
    public $showModal = false;
    public $modalMode = '';
    public $orgId;
    public $is_visible;
    public $selectedIsVisible;
    public $orgData;
    public function rules()
    {
        $rules = [];

        if (in_array($this->modalMode, ['create', 'edit'])) {
            $rules['name'] = 'required|unique:appointedorganizations,name';
        } else {
            $rules['name'] = 'nullable';
        }

        if ($this->modalMode === 'edit') {
            $rules['name'] = 'required|string|min:3|unique:appointedorganizations,name,' . $this->orgId;
        }

        return $rules;
    }

    protected $listeners = ['deleteItem', 'refreshComponent' => 'getorg'];

    public function addOrg()
    {
        $this->showModal = true;
        $this->is_visible = true;
        $this->modalMode = 'create';
    }

    public function createOrg()
    {
        $this->validate();
        Appointedorganization::create(
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
        $this->orgId = $id;
        $this->orgData = Appointedorganization::findOrFail($id);
        $this->selectedIsVisible = $this->orgData->is_visible == 1 ?? true;
        $this->name = $this->orgData->name;
    }

    public function update()
    {
        $this->validate();
        $record = Appointedorganization::findOrFail($this->orgId);
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
        $this->getorg();
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
        $item = Appointedorganization::find($id);
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
    public function getorg()
    {
        $this->organisations = Appointedorganization::orderBy('id', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.employees.master.appointed-organisation');
    }
}
