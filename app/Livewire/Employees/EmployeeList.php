<?php

namespace App\Livewire\Employees;

use App\Models\Employee;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class EmployeeList extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $totalEmployees;
    public $activeEmployees;
    public $resignedEmployees;
    public $terminatedEmployees;

   public function render()
{
    $this->totalEmployees = Employee::count();
    $this->activeEmployees = Employee::where('status', 'active')->count();
    $this->resignedEmployees = Employee::where('status', 'resigned')->count();
    $this->terminatedEmployees = Employee::where('status', 'terminated')->count();

    $employees = Employee::with(['user', 'designation', 'empType'])->get();

    return view('livewire.employees.employee-list', [
        'employees' => $employees,
        'totalEmployees' => $this->totalEmployees,
        'activeEmployees' => $this->activeEmployees,
        'resignedEmployees' => $this->resignedEmployees,
        'terminatedEmployees' => $this->terminatedEmployees,
    ]);
}

}
