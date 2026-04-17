<?php

namespace App\Livewire\Hr\SalaryManagement;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Salary;
use App\Models\Payroll;
use Carbon\Carbon;
class PayrollManagement extends Component
{
    public $month;
    public $employees;
    public $selectedEmployees = [];
    public $payrollPreview = [];

    public function mount()
    {
        $this->month = Carbon::now()->format('Y-m');
        $this->employees = Employee::with('salary.components')->get();
    }

    public function generatePreview()
    {
        $selected = $this->selectedEmployees ?: $this->employees->pluck('id')->toArray();
        $this->payrollPreview = [];

        foreach ($selected as $empId) {
            $employee = $this->employees->where('id', $empId)->first();
            if (!$employee->salary) continue;

            $basic = $employee->salary->basic_salary;
            $totalAllowances = $employee->salary->components
                ->where('type', 'allowance')
                ->sum('amount');
            $totalDeductions = $employee->salary->components
                ->where('type', 'deduction')
                ->sum('amount');
            $totalBonus = $employee->salary->components
                ->where('type', 'bonus')
                ->sum('amount');

            $gross = $basic + $totalAllowances + $totalBonus;
            $net = $gross - $totalDeductions;

            $this->payrollPreview[] = [
                'employee_id' => $employee->id,
                'name' => $employee->full_name ,
                'gross' => $gross,
                'net' => $net,
            ];
        }
    }

    public function finalizePayroll()
    {
        foreach ($this->payrollPreview as $payroll) {
            Payroll::updateOrCreate(
                [
                    'employee_id' => $payroll['employee_id'],
                    'month' => $this->month
                ],
                [
                    'gross_salary' => $payroll['gross'],
                    'net_salary' => $payroll['net'],
                    'is_paid' => false
                ]
            );
        }

        session()->flash('message', 'Payroll generated successfully!');
        $this->payrollPreview = [];
    }

    public function markPaid($payrollId)
    {
        $payroll = Payroll::find($payrollId);
        $payroll->update(['is_paid' => true]);
    }

    public function render()
    {
        $existingPayrolls = Payroll::with('employee')
            ->where('month', $this->month)
            ->get();

        return view('livewire.hr.salary-management.payroll-management', [
            'existingPayrolls' => $existingPayrolls
        ]);
    }
}
