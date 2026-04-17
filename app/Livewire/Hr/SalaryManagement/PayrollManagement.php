<?php

namespace App\Livewire\Hr\SalaryManagement;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollItem;
use Carbon\Carbon;


use Illuminate\Support\Facades\Storage;
use ZipArchive;
use PDF; // assuming you use barryvdh/laravel-dompdf
use Illuminate\Support\Facades\Mail;
use App\Mail\PayslipMail;

class PayrollManagement extends Component
{
    public $month;
    public $employees;
    public $selectedEmployees = [];
    public $payrollPreview = [];
    public $showPayslip = false;
    public $selectedPayroll;
    public $employeePayrolls = [];
    public $currentIndex = 0;
    public $payrollId;

    //  protected $listeners = ['sendPayrollEmail' => 'sendEmail'];
    public function mount()
    {
        $this->month = Carbon::now()->format('Y-m');
        $this->employees = Employee::with('salary.components')->get();
    }

    private function calcAmount($component, $basic)
    {
        return $component->amount ?? ($basic * ($component->percentage ?? 0) / 100);
    }

    public function generatePreview()
    {
        $selected = $this->selectedEmployees ?: $this->employees->pluck('id')->toArray();
        $this->payrollPreview = [];

        foreach ($selected as $empId) {
            $employee = $this->employees->firstWhere('id', $empId);
            if (!$employee || !$employee->salary) continue;

            $basic = $employee->salary->basic_salary;

            $allowances = $employee->salary->components->where('type', 'allowance');
            $deductions = $employee->salary->components->where('type', 'deduction');
            $bonuses    = $employee->salary->components->where('type', 'bonus');

            $allowanceItems = [];
            foreach ($allowances as $a) {
                $amt = $this->calcAmount($a, $basic);
                $allowanceItems[] = ['name' => $a->name, 'amount' => $amt];
            }

            $deductionItems = [];
            foreach ($deductions as $d) {
                $amt = $this->calcAmount($d, $basic);
                $deductionItems[] = ['name' => $d->name, 'amount' => $amt];
            }

            $bonusItems = [];
            foreach ($bonuses as $b) {
                $amt = $this->calcAmount($b, $basic);
                $bonusItems[] = ['name' => $b->name, 'amount' => $amt];
            }

            $totalAllowances = array_sum(array_column($allowanceItems, 'amount'));
            $totalDeductions = array_sum(array_column($deductionItems, 'amount'));
            $totalBonus      = array_sum(array_column($bonusItems, 'amount'));

            $gross = $basic + $totalAllowances + $totalBonus;
            $net   = $gross - $totalDeductions;

            $this->payrollPreview[] = [
                'employee_id'     => $employee->id,
                'name'            => $employee->full_name,
                'basic'           => $basic,
                'allowances'      => $totalAllowances,
                'bonuses'         => $totalBonus,
                'deductions'      => $totalDeductions,
                'gross'           => $gross,
                'net'             => $net,
                'allowance_items' => $allowanceItems,
                'bonus_items'     => $bonusItems,
                'deduction_items' => $deductionItems,
            ];
        }
    }

    public function finalizePayroll()
    {
        foreach ($this->payrollPreview as $row) {
            $payroll = Payroll::updateOrCreate(
                ['employee_id' => $row['employee_id'], 'month' => $this->month],
                ['gross_salary' => $row['gross'], 'net_salary' => $row['net'], 'is_paid' => false]
            );

            $payroll->items()->delete();

            // Basic
            PayrollItem::create([
                'payroll_id' => $payroll->id,
                'type'       => 'basic',
                'name'       => 'Basic Salary',
                'amount'     => $row['basic'],
            ]);

            foreach ($row['allowance_items'] as $item) {
                PayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'type'       => 'allowance',
                    'name'       => $item['name'],
                    'amount'     => $item['amount'],
                ]);
            }

            foreach ($row['bonus_items'] as $item) {
                PayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'type'       => 'bonus',
                    'name'       => $item['name'],
                    'amount'     => $item['amount'],
                ]);
            }

            foreach ($row['deduction_items'] as $item) {
                PayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'type'       => 'deduction',
                    'name'       => $item['name'],
                    'amount'     => $item['amount'],
                ]);
            }
        }

        session()->flash('message', 'Payroll generated successfully with breakdown!');
        $this->payrollPreview = [];
    }

    public function markPaid($payrollId)
    {
        if ($payroll = Payroll::find($payrollId)) {
            $payroll->update(['is_paid' => true]);
        }
    }

    public function previewPayslip($payrollId)
    {
        $payroll = Payroll::with(['employee', 'items'])->findOrFail($payrollId);
        $this->selectedPayroll = $payroll;
        $this->showPayslip = true;

        $this->employeePayrolls = Payroll::with('items')
            ->where('employee_id', $payroll->employee_id)
            ->orderBy('month', 'desc')
            ->get()
            ->values();

        $this->currentIndex = $this->employeePayrolls->search(fn($p) => $p->id === $payrollId);
    }

    public function navigatePayslip($direction)
    {
        if ($direction === 'prev' && $this->currentIndex < $this->employeePayrolls->count() - 1) {
            $this->currentIndex++;
        } elseif ($direction === 'next' && $this->currentIndex > 0) {
            $this->currentIndex--;
        }

        $this->selectedPayroll = $this->employeePayrolls[$this->currentIndex]->load('employee', 'items');
    }

    public function closePayslipModal()
    {
        $this->showPayslip = false;
        $this->selectedPayroll = null;
        $this->employeePayrolls = [];
        $this->currentIndex = 0;
    }

    public function render()
    {
        $existingPayrolls = Payroll::with(['employee', 'items'])
            ->where('month', $this->month)
            ->get();

        return view('livewire.hr.salary-management.payroll-management', [
            'existingPayrolls' => $existingPayrolls,
        ]);
    }





/** Bulk download all payslips as ZIP */
    public function downloadAllPayslips()
    {
        $payrolls = Payroll::with(['employee', 'items'])
            ->where('month', $this->month)
            ->get();

        if ($payrolls->isEmpty()) {
            session()->flash('message', 'No payrolls to download for this month.');
            return;
        }

        // Create temporary zip
        $zipFileName = "Payslips-{$this->month}.zip";
        $zipPath = storage_path("app/public/{$zipFileName}");

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {

            foreach ($payrolls as $payroll) {
                $pdf = PDF::loadView('payslips.template', ['payroll' => $payroll]);
                $fileName = $payroll->employee->name . "-{$payroll->month}.pdf";
                $zip->addFromString($fileName, $pdf->output());
            }

            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /** Bulk email all unpaid payslips */
    public function emailAllPayslips()
    {
        $payrolls = Payroll::with(['employee', 'items'])
            ->where('month', $this->month)
            ->get();

        if ($payrolls->isEmpty()) {
            session()->flash('message', 'No payrolls to email for this month.');
            return;
        }

        foreach ($payrolls as $payroll) {
            if (!$payroll->employee->email) continue;

            // Queue email with PDF attachment
            Mail::to($payroll->employee->email)->queue(new PayslipMail($payroll));
        }

        session()->flash('message', 'Bulk payslip emails queued successfully!');
    }


public function markAllPaid()
{
    $updated = Payroll::where('month', $this->month)
        ->where('is_paid', false)
        ->update(['is_paid' => true]);

    if ($updated > 0) {
        session()->flash('message', "All payrolls for {$this->month} marked as paid!");
    } else {
        session()->flash('message', "All payrolls are already marked as paid for {$this->month}.");
    }
}
// public function emailAllPayslips()
// {
//     $payrolls = Payroll::with(['employee', 'items'])
//         ->where('month', $this->month)
//         ->where('is_paid', false) // only unpaid
//         ->get();

//     if ($payrolls->isEmpty()) {
//         session()->flash('message', 'No unpaid payrolls to email for this month.');
//         return;
//     }

//     foreach ($payrolls as $payroll) {
//         if (!$payroll->employee->email) continue;

//         // Queue email with PDF attachment
//         \Mail::to($payroll->employee->email)->queue(new \App\Mail\PayslipMail($payroll));

//         // Mark as paid automatically after sending email
//         $payroll->update(['is_paid' => true]);
//     }

//     session()->flash('message', 'Bulk payslip emails sent and payrolls marked as paid!');
// }

public function getHasUnpaidPayrollsProperty()
{
    return Payroll::where('month', $this->month)->where('is_paid', false)->exists();
}


public function getPayrollSummaryProperty()
{
    $total = Payroll::where('month', $this->month)->count();
    $paid = Payroll::where('month', $this->month)->where('is_paid', true)->count();

    $percent = $total > 0 ? round(($paid / $total) * 100, 2) : 0;

    return [
        'total' => $total,
        'paid' => $paid,
        'unpaid' => $total - $paid,
        'percent' => $percent,
    ];
}


    public function downloadPayslip($id)
    {
        $url = route('payslip.download', $id);
        $this->dispatch('download-payslip', url: $url);
    }

    // public function emailPayslip($id)
    // {
    //     $url = route('payslip.email', $id);
    //     $this->dispatch('email-payslip', url: $url);
    // }

    public function sendEmail($id)
    {
        $this->payrollId = $id;

        $payroll = Payroll::with('employee.user')->findOrFail($id);

        // Generate PDF
        $pdf = PDF::loadView('payslips.template', compact('payroll'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isJavascriptEnabled' => false,
                'enablePhp' => false,
                'defaultFont' => 'DejaVu Sans',
                'enableFontSubsetting' => true,
                'defaultMediaType' => 'print',
                'defaultPaperSize' => 'a4',
                'chroot' => public_path(),
            ]);

        // Send Email
        // Mail::send([], [], function ($message) use ($pdf, $payroll) {
        //     $message->to($payroll->employee->user->email)
        //         ->subject('Your Monthly Payslip')
        //         ->attachData($pdf->output(), "Payslip.pdf");
        // });
        Mail::send('emails.payslip', compact('payroll'), function ($message) use ($pdf, $payroll) {
            $message->to($payroll->employee->user->email)
                ->subject('Your Monthly Payslip')
                ->attachData($pdf->output(), "Payslip.pdf");
        });
         $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => "Payslip emailed successfully!"
        ]));
        
    }

}
