<?php

namespace App\Livewire\Employees;

use App\Models\User;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Promotion;
use Livewire\Component;
use Livewire\WithFileUploads;

class Promotions extends Component
{
    use WithFileUploads;

    public $employee_id;
    public $previous_designation_id;
    public $new_designation_id;
    public $previous_salary;
    public $new_salary;
    public $promotion_date;
    public $remarks;
    public $promotion_letter;

    public $selectedPromotion = null;
    public $editMode = false;
  // Search property
    public $search = '';
protected $listeners = ['delete'];


public function handleEmployeeChange()
{
    // Reset designation first
    $this->previous_designation_id = null;
    
    if ($this->employee_id) {
        $employee = Employee::where('user_id', $this->employee_id)->first();
      //  dd($employee->emp_designation);
        $this->previous_designation_id = $employee?->emp_designation;
    }
    
    // Force update the designation select
    $this->dispatch('$refresh');
}

// Alternative approach - using updated() method
public function updated($propertyName)
{
    if ($propertyName === 'employee_id') {
        $this->handleEmployeeChange();
    }
}

// Or use updatedEmployeeId() method (Livewire convention)
public function updatedEmployeeId()
{
    $this->previous_designation_id = null;
    
    if ($this->employee_id) {
        $employee = Employee::where('user_id', $this->employee_id)->first();
        $this->previous_designation_id = $employee?->emp_designation;
    }
}

 public function render()
    {
        $promotionList = Promotion::with(['employee', 'previousDesignation', 'newDesignation'])
            ->when($this->search, function ($query) {
                $query->whereHas('employee', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('previousDesignation', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('newDesignation', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhere('promotion_date', 'like', '%' . $this->search . '%')
                ->orWhere('remarks', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get();

        return view('livewire.employees.promotions', [
            'employees' => User::where('status', 1)->orderBy('name')->get(),
            'promotionList' => $promotionList,
            'designations' => Designation::orderBy('name')->get(),
        ]);
    }

    public function updatedSearch()
    {
        // This method will be called automatically when search property is updated
        // You can add any additional logic here if needed
    }
    // public function render()
    // {
    //     return view('livewire.employees.promotions', [
    //         'employees' => User::where('status', 1)->orderBy('name')->get(),
    //         'promotionList' => Promotion::with(['employee', 'previousDesignation', 'newDesignation'])->latest()->get(),
    //         'designations' => Designation::orderBy('name')->get(),
    //     ]);
    // }

    public function save()
    {
        $this->validate([
            'employee_id' => 'required|exists:users,id',
            'previous_designation_id' => 'required|exists:designations,id',
            'new_designation_id' => 'required|exists:designations,id',
            'promotion_date' => 'required|date',
            'promotion_letter' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $promotion = Promotion::create([
            'employee_id' => $this->employee_id,
            'previous_designation_id' => $this->previous_designation_id,
            'new_designation_id' => $this->new_designation_id,
            'previous_salary' => $this->previous_salary,
            'new_salary' => $this->new_salary,
            'promotion_date' => $this->promotion_date,
            'remarks' => $this->remarks,
        ]);

        if ($this->promotion_letter) {
            $promotion->addMedia($this->promotion_letter->getRealPath())
                ->usingFileName($this->promotion_letter->getClientOriginalName())
                ->toMediaCollection('promotion_letters');
        }

        session()->flash('success', 'Promotion recorded successfully.');

        $this->resetFields();
        $this->dispatch('close-modal');
    }

    public function view($id)
    {
        $this->selectedPromotion = Promotion::with(['employee', 'previousDesignation', 'newDesignation'])->findOrFail($id);
    }

    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        $this->editMode = true;
        $this->selectedPromotion = $promotion;

        $this->employee_id = $promotion->employee_id;
        $this->previous_designation_id = $promotion->previous_designation_id;
        $this->new_designation_id = $promotion->new_designation_id;
        $this->previous_salary = $promotion->previous_salary;
        $this->new_salary = $promotion->new_salary;
        $this->promotion_date = $promotion->promotion_date;
        $this->remarks = $promotion->remarks;
    }

    public function update()
    {
        $this->validate([
            'employee_id' => 'required|exists:users,id',
            'previous_designation_id' => 'required|exists:designations,id',
            'new_designation_id' => 'required|exists:designations,id',
            'promotion_date' => 'required|date',
            'promotion_letter' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if (!$this->selectedPromotion) {
            session()->flash('error', 'No promotion selected for update.');
            return;
        }

        $this->selectedPromotion->update([
            'employee_id' => $this->employee_id,
            'previous_designation_id' => $this->previous_designation_id,
            'new_designation_id' => $this->new_designation_id,
            'previous_salary' => $this->previous_salary,
            'new_salary' => $this->new_salary,
            'promotion_date' => $this->promotion_date,
            'remarks' => $this->remarks,
        ]);

        if ($this->promotion_letter) {
            $this->selectedPromotion->clearMediaCollection('promotion_letters');
            $this->selectedPromotion->addMedia($this->promotion_letter->getRealPath())
                ->usingFileName($this->promotion_letter->getClientOriginalName())
                ->toMediaCollection('promotion_letters');
        }

        session()->flash('success', 'Promotion updated successfully.');
        $this->resetFields();
    }

    public function delete($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->clearMediaCollection('promotion_letters');
        $promotion->delete();
         $this->resetFields();
        $this->dispatch('swal:success', json_encode([
                'title' => 'Item Deleted',
                'text' => 'Promotion deleted successfully.',
                'icon' => 'success',
            ]));


    }

    public function resetFields()
    {
        $this->reset([
            'employee_id',
            'previous_designation_id',
            'new_designation_id',
            'previous_salary',
            'new_salary',
            'promotion_date',
            'remarks',
            'promotion_letter',
            'selectedPromotion',
            'editMode',
        ]);
    }

    public function clearSearch()
    {
        $this->search = '';
    }
}
