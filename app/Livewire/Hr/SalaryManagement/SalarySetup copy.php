<?php

namespace App\Livewire\Hr\SalaryManagement;

use App\Models\Employee;
use App\Models\Salary;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalarySetup extends Component
{
    public $employees;
    public $basic_salary = 0;
    public $components = [];
    public $selectedMonth;
    public $selectedEmployeeId = null;

    // Salary Summary
    public $totalAllowance = 0;
    public $totalDeduction = 0;
    public $netSalary = 0;
    public $netSalaryClass = '';
    public $rowHighlight = [];

    // Bulk Copy
    public $showConfirmBulkCopy = false;
    public $bulkCopySummary = [
        'totalPrev' => 0,
        'alreadyExists' => 0,
        'willBeCopied' => 0,
    ];

    public function mount()
    {
        $this->employees = Employee::all();
        $this->selectedMonth = now()->format('Y-m');
        $this->calculateTotals();
    }

    /**
     * Helper: Get previous month of the selected month
     */
    private function getPreviousMonth()
    {
        return Carbon::parse("{$this->selectedMonth}-01")
            ->subMonth()
            ->format('Y-m');
    }

    /**
     * Load salary data for selected employee & month
     */
    public function selectEmployee($id)
    {
        $this->selectedEmployeeId = $id;

        $salary = Salary::with('components')
            ->where('employee_id', $id)
            ->where('month', $this->selectedMonth)
            ->first();

        $this->basic_salary = $salary->basic_salary ?? 0;
        $this->components = $salary?->components->toArray() ?? [];

        $this->calculateTotals();
    }

    /**
     * Reload data when month changes
     */
    public function updatedSelectedMonth()
    {
        if ($this->selectedEmployeeId) {
            $this->selectEmployee($this->selectedEmployeeId);
        }
    }

    /**
     * Update totals when basic salary changes
     */
    public function updatedBasicSalary()
    {
        $this->recalculatePercentages();
        $this->calculateTotals();
    }

    /**
     * Add a new component row
     */
    public function addComponent()
    {
        $this->components[] = [
            'type' => 'allowance',
            'name' => '',
            'amount' => 0,
            'percentage' => null,
        ];
    }

    /**
     * Update components dynamically and recalc totals
     */
    public function updatedComponents($value, $name)
    {
        [$index, $field] = explode('.', $name);

        // Recalculate amount if percentage updated
        if ($field === 'percentage' && $this->basic_salary > 0) {
            $percentage = $this->components[$index]['percentage'];
            $this->components[$index]['amount'] = $percentage ? ($this->basic_salary * $percentage) / 100 : 0;
        }

        // Highlight row
        $this->rowHighlight[$index] = 'highlight-change';
        $this->dispatch('resetRowHighlight', $index);

        // Recalculate totals
        $this->calculateTotals();
    }

    /**
     * Recalculate all components that have percentage
     */
    private function recalculatePercentages()
    {
        foreach ($this->components as &$component) {
            if (!empty($component['percentage']) && $this->basic_salary > 0) {
                $component['amount'] = ($this->basic_salary * $component['percentage']) / 100;
            }
        }
    }

    /**
     * Calculate totals and animate net salary change
     */
    private function calculateTotals()
    {
        $oldNetSalary = $this->netSalary;

        $this->totalAllowance = 0;
        $this->totalDeduction = 0;

        foreach ($this->components as $component) {
            $amount = (float) ($component['amount'] ?? 0);
            if ($component['type'] === 'allowance') {
                $this->totalAllowance += $amount;
            } elseif ($component['type'] === 'deduction') {
                $this->totalDeduction += $amount;
            }
        }

        $this->netSalary = $this->basic_salary + $this->totalAllowance - $this->totalDeduction;

        if ($oldNetSalary && $oldNetSalary != $this->netSalary) {
            $this->netSalaryClass = $this->netSalary > $oldNetSalary
                ? 'text-success animate-bounce'
                : 'text-danger animate-bounce';

            $this->dispatch('resetNetSalaryClass');
        }
    }

    /**
     * Save salary & components
     */
    public function save()
    {
        if (!$this->selectedEmployeeId) {
            $this->dispatch('toastMessage', json_encode([
                'type' => 'error',
                'message' => 'Please select an employee first.'
            ]));
            return;
        }

        $this->recalculatePercentages();

        $salary = Salary::updateOrCreate(
            [
                'employee_id' => $this->selectedEmployeeId,
                'month' => $this->selectedMonth,
            ],
            ['basic_salary' => $this->basic_salary]
        );

        $salary->components()->delete();
        foreach ($this->components as $component) {
            $salary->components()->create($component);
        }

        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => "Salary for {$this->selectedMonth} saved successfully!"
        ]));
    }

    /**
     * Copy previous month for selected employee
     */
    public function copyPreviousMonth()
    {
        if (!$this->selectedEmployeeId) {
            $this->dispatch('toastMessage', json_encode([
                'type' => 'error',
                'message' => 'Please select an employee first.'
            ]));
            return;
        }

        $prevMonth = $this->getPreviousMonth();

        $previousSalary = Salary::with('components')
            ->where('employee_id', $this->selectedEmployeeId)
            ->where('month', $prevMonth)
            ->first();

        if (!$previousSalary) {
            $this->dispatch('toastMessage', json_encode([
                'type' => 'error',
                'message' => "No data found for previous month ($prevMonth)."
            ]));
            return;
        }

        $this->basic_salary = $previousSalary->basic_salary;
        $this->components = $previousSalary->components->toArray();
        $this->calculateTotals();

        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => "Previous month ($prevMonth) salary setup copied!"
        ]));
    }

    /**
     * Prepare bulk copy modal
     */
    public function confirmBulkCopy()
    {
        $prevMonth = $this->getPreviousMonth();

        $totalPrev = Salary::where('month', $prevMonth)->count();
        $alreadyExists = Salary::where('month', $this->selectedMonth)->count();

        $this->bulkCopySummary = [
            'totalPrev' => $totalPrev,
            'alreadyExists' => $alreadyExists,
            'willBeCopied' => max($totalPrev - $alreadyExists, 0),
        ];

        if ($totalPrev === 0) {
            $this->dispatch('toastMessage', json_encode([
                'type' => 'error',
                'message' => "No salary data found for previous month ($prevMonth)."
            ]));
            return;
        }

        $this->showConfirmBulkCopy = true;
    }

    /**
     * Confirm & Execute bulk copy
     */
    public function bulkCopyPreviousMonthConfirmed()
    {
        $prevMonth = $this->getPreviousMonth();

        $previousSalaries = Salary::with('components')
            ->where('month', $prevMonth)
            ->get();

        if ($previousSalaries->isEmpty()) {
            $this->dispatch('toastMessage', json_encode([
                'type' => 'error',
                'message' => "No data found for previous month ($prevMonth)."
            ]));
            return;
        }

        DB::transaction(function () use ($previousSalaries) {
            foreach ($previousSalaries as $prevSalary) {
                $salary = Salary::updateOrCreate(
                    [
                        'employee_id' => $prevSalary->employee_id,
                        'month' => $this->selectedMonth,
                    ],
                    ['basic_salary' => $prevSalary->basic_salary]
                );

                $salary->components()->delete();
                foreach ($prevSalary->components as $component) {
                    $salary->components()->create($component->only(['type', 'name', 'amount', 'percentage']));
                }
            }
        });

        $this->showConfirmBulkCopy = false;

        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => "All salaries copied from $prevMonth to {$this->selectedMonth} successfully!"
        ]));
    }

    /**
     * Reset row highlight after animation
     */
    public function resetRowHighlight($index)
    {
        $this->rowHighlight[$index] = '';
    }
    public function removeComponent($index)
    {
        unset($this->components[$index]);
        $this->components = array_values($this->components); // Reindex to avoid gaps
        $this->calculateTotals();
    }

    public function render()
    {
        return view('livewire.hr.salary-management.salary-setup');
    }
}
