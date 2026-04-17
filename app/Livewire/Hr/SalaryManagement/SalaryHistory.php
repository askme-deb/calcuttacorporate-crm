<?php

namespace App\Livewire\Hr\SalaryManagement;

use Livewire\Component;
use App\Models\Payroll;
use App\Models\Employee;
use Carbon\Carbon;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalaryHistoryExport;

class SalaryHistory extends Component
{
    public $employees = [];
    public $selectedEmployee = '';
    public $month = '';
    public $year = '';
    public $history = [];

    public function mount()
    {
        $this->employees = Employee::all();
        $this->year = Carbon::now()->year; // default current year
        $this->month = ''; // default all months
        $this->loadHistory();
    }

    public function updated($name, $value)
    {
        if (in_array($name, ['selectedEmployee', 'month', 'year'])) {
            $this->loadHistory();
        }
    }

    public function loadHistory()
    {
        $query = Payroll::with([
            'employee',
            'basic',
            'allowances',
            'deductions',
        ]);

        if ($this->selectedEmployee) {
            $query->where('employee_id', $this->selectedEmployee);
        }

        if ($this->year) {
            if ($this->month) {
                $monthStr = str_pad($this->month, 2, '0', STR_PAD_LEFT);
                $query->where('month', $this->year . '-' . $monthStr);
            } else {
                $query->where('month', 'like', $this->year . '-%');
            }
        }

        $this->history = $query->orderBy('month', 'desc')->get();
    }

    // public function loadHistory()
    // {
    //     $query = Payroll::with('employee');

    //     if ($this->selectedEmployee) {
    //         $query->where('employee_id', $this->selectedEmployee);
    //     }

    //     if ($this->year) {
    //         if ($this->month) {
    //             // Exact YYYY-MM match
    //             $query->where('month', $this->year . '-' . str_pad($this->month, 2, '0', STR_PAD_LEFT));
    //         } else {
    //             // Match all months of selected year using LIKE
    //             $query->where('month', 'like', $this->year . '-%');
    //         }
    //     }

    //     $this->history = $query->orderBy('month', 'desc')->get();
    // }

    //   public function exportExcel()
    // {
    //     if (empty($this->history)) {
    //         $this->dispatch('alert', ['type'=>'error', 'message'=>'No data to export']);
    //         return;
    //     }

    //     $filename = 'salary_history_'.uniqid().'.csv';
    //     $content = "Employee,Gross,Net,Status,Month\n";

    //     foreach ($this->history as $row) {
    //         $content .= "{$row->employee->full_name},{$row->gross_salary},{$row->net_salary},".
    //                     ($row->is_paid?'Paid':'Unpaid').",{$row->month}\n";
    //     }

    //     // Ensure temp folder exists
    //     if (!Storage::exists('temp')) {
    //         Storage::makeDirectory('temp');
    //     }

    //     Storage::put('temp/'.$filename, $content);

    //     $this->dispatch('download-csv', [
    //         'url' => route('temp.download', ['filename' => $filename])
    //     ]);
    // }

    public function exportExcel()
    {
        return Excel::download(new SalaryHistoryExport($this->history), 'salary_history.xlsx');

         // Simulate some delay if needed
            sleep(2);

             $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => "Excel export completed successfully!"
        ]));
            
    }
    public function render()
    {
        return view('livewire.hr.salary-management.salary-history');
    }
}
