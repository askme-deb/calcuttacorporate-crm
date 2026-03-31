<?php

namespace App\Livewire\Employees;

use App\Models\Employee;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class EmployeeList extends Component
{
    use WithPagination, WithoutUrlPagination;
    public function render()
    {
        $employees = Employee::with(['user', 'designation', 'empType'])
        ->get();
        return view('livewire.employees.employee-list', compact('employees'));
    }
}
