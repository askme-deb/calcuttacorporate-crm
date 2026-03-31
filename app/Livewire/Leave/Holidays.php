<?php

namespace App\Livewire\Leave;

use App\Models\Holiday;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Holidays extends Component
{
    public $holidays;
    public $holidaysData;
    public $holidayId;
    public $showModal = false;
    public $modalMode = '';
    public $start_date;
    public $end_date;
    public $no_of_days;
    public $holidayName;
    public function rules()
    {
        return [
            'holidayName' => $this->modalMode === 'create'? 'required' : 'nullable',
            'start_date' => $this->modalMode === 'create' ? 'required' : 'nullable',
            'end_date' => $this->modalMode === 'create' ? 'required' : 'nullable',
            'no_of_days' => $this->modalMode === 'create' ? 'required|numeric' : 'nullable',
            'holidayName' => $this->modalMode === 'edit'? 'required' : 'nullable',
            'start_date' => $this->modalMode === 'edit' ? 'required' : 'nullable',
            'end_date' => $this->modalMode === 'edit' ? 'required' : 'nullable',
            'no_of_days' => $this->modalMode === 'edit' ? 'required|numeric' : 'nullable'
        ];
    }
    protected $listeners = ['deleteItem', 'refreshComponent' => 'loadHolidays'];

    public function addHoliday()
    {
        $this->showModal = true;
        $this->modalMode = 'create';
    }


    public function createHoliday()
    {
       $this->validate();
        Holiday::create(
            [
                'name' => $this->pull('holidayName'),
                'start_date' => $this->pull('start_date'),
                'end_date' => $this->pull('end_date'),
                'no_of_days' => $this->pull('no_of_days'),
                'created_by' => Auth::user()->id
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
        $this->holidayId = $id;
        $this->holidaysData = Holiday::findOrFail($id);
        $this->holidayName = $this->holidaysData->name;
        $this->no_of_days = $this->holidaysData->no_of_days;
        $this->start_date = date('Y-m-d', strtotime($this->holidaysData->start_date));
        $this->end_date = date('Y-m-d', strtotime($this->holidaysData->end_date));
    }

    public function update()
    {
        $this->validate();
        $record = Holiday::findOrFail($this->holidayId);
        $record->update([
            'name' => $this->holidayName,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'no_of_days' => $this->no_of_days,
    ]);
        $this->closeModal();
        $this->dispatch('refreshComponent');
        $this->dispatch('toastMessage', json_encode([
            'type'=>'success',
            'message' => 'Data Updated successfully'
        ]));

    }

    public function mount()
    {
        $this->holidays = Holiday::with(['createdBy'])
        ->orderBy('id', 'desc')
        ->get();
    }
    public function render()
    {
        return view('livewire.leave.holidays');
    }

    public function loadHolidays()
    {
        $this->holidays =  $this->holidays = Holiday::with(['createdBy'])
        ->orderBy('id', 'desc')
        ->get();
    }

    public function deleteItem($id)
    {
        $item = Holiday::find($id);
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
    public function closeModal()
    {
        $this->reset('showModal');
    }

    public function calculateDifference()
    {
        if ($this->start_date && $this->end_date) {
            $start = Carbon::parse($this->start_date);
            $end = Carbon::parse($this->end_date);
            if ($start > $end) {
                $temp = $start;
                $start = $end;
                $end = $temp;
            }
            $this->no_of_days = $start->diffInDays($end) + 1;
        }
    }

}
