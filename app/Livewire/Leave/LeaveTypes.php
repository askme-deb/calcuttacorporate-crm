<?php

namespace App\Livewire\Leave;

use App\Models\LeaveType;
use Livewire\Component;

class LeaveTypes extends Component
{
    public $leaveTypes;
    public $leaveTypeName;
    public $numberOfDays;
    public $showModal = false;
    public $modalMode = '';
    public $leaveTypeId;
    public $data = [];
    public function rules()
    {
        return [
            'leaveTypeName' => $this->modalMode === 'create'
            ? 'required|string|min:3|unique:leave_types,type_name'
            : 'nullable',
            'numberOfDays' => $this->modalMode === 'create'
            ? 'required|numeric':'nullable',
            'data.type_name' => $this->modalMode === 'edit'
            ? 'required|string|min:3|unique:leave_types,type_name,' . $this->leaveTypeId : 'nullable',
            'data.number_of_days' => $this->modalMode === 'edit'
            ? 'required|numeric':'nullable'    
        ];
    }




    protected $listeners = ['deleteItem', 'refreshComponent' => 'loadLeavetypes'];
    public function addLeaveType()
    {
        $this->showModal = true;
        $this->modalMode = 'create';
    }

    public function createLeaveType()
    {
        $this->validate();
        LeaveType::create(
            [
                'type_name' => $this->pull('leaveTypeName'),
                'number_of_days' => $this->pull('numberOfDays')
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
        $this->leaveTypeId = $id;
        $this->data = LeaveType::findOrFail($id)->toArray();
    }

    public function update()
    {
        $this->validate();
        $record = LeaveType::findOrFail($this->leaveTypeId);
        $record->update($this->data);
        $this->closeModal();
        $this->dispatch('refreshComponent');
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Data Updated successfully'
        ]));

    }




    public function mount()
    {
        $this->leaveTypes = LeaveType::orderBy('id', 'desc')
        ->get();
    }

    public function render()
    {
        return view('livewire.leave.leave-types');
    }

    public function closeModal()
    {
        $this->reset('showModal');
    }


    public function deleteItem($id)
    {
        $item = LeaveType::find($id);
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
    public function loadLeavetypes()
    {
        $this->leaveTypes = LeaveType::orderBy('id', 'desc')
        ->get();
    }

}
