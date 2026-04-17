<?php

namespace App\Livewire\Employees;

use App\Models\Resignation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

use App\Mail\ResignationSubmitted;
use Illuminate\Support\Facades\Mail;

class SubmitResignation extends Component
{
    public $resignation_date;
    public $last_working_date;
    public $reason = '';
    public $additional_comments = '';
    public $notice_period_days = 30;

    public $showConfirmation = false;
    public $existingResignation;

    protected $rules = [
        'resignation_date' => 'required|date|after_or_equal:today',
        'last_working_date' => 'required|date|after:resignation_date',
        'reason' => 'required|string|min:10|max:1000',
        'additional_comments' => 'nullable|string|max:1000',
        'notice_period_days' => 'required|integer|min:1|max:90'
    ];

    protected $messages = [
        'resignation_date.required' => 'Resignation date is required.',
        'resignation_date.after_or_equal' => 'Resignation date cannot be in the past.',
        'reason.required' => 'Reason for resignation is required.',
        'reason.min' => 'Reason must be at least 10 characters.',
        'notice_period_days.min' => 'Notice period must be at least 1 day.',
        'notice_period_days.max' => 'Notice period cannot exceed 90 days.',
    ];

    public function mount()
    {

        $this->existingResignation = Resignation::where('employee_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        $this->resignation_date = Carbon::now()->format('Y-m-d');
        $this->notice_period_days = 30; // Ensure it's an integer
        $this->calculateLastWorkingDate();
    }

    public function updatedResignationDate()
    {
        $this->calculateLastWorkingDate();
        $this->validateOnly('resignation_date');
    }

    public function updatedNoticePeriodDays()
    {
        // Ensure it's an integer
        $this->notice_period_days = (int) $this->notice_period_days;
        $this->calculateLastWorkingDate();
        $this->validateOnly('notice_period_days');
    }

    public function updatedReason()
    {
        $this->validateOnly('reason');
    }

    private function calculateLastWorkingDate()
    {
        if ($this->resignation_date && $this->notice_period_days) {
            try {
                $resignationDate = Carbon::parse($this->resignation_date);
                // Convert to integer to avoid Carbon error
                $noticeDays = (int) $this->notice_period_days;
                $this->last_working_date = $resignationDate->addDays($noticeDays)->format('Y-m-d');
            } catch (\Exception $e) {
                $this->last_working_date = null;
            }
        }
    }

    public function submitResignation()
    {
        // Check for existing resignation first
        if ($this->existingResignation) {
            session()->flash('error', 'You already have a resignation request pending or approved.');
            return;
        }

        $this->validate();
        $this->showConfirmation = true;
    }

    public function confirmSubmission()
    {
        try {
            $existingCheck = Resignation::where('employee_id', Auth::id())
                ->whereIn('status', ['pending', 'approved'])
                ->first();

            if ($existingCheck) {
                session()->flash('error', 'You already have a resignation request pending or approved.');
                $this->showConfirmation = false;
                return;
            }

            $resignation = Resignation::create([
                'employee_id' => Auth::id(),
                'resignation_date' => $this->resignation_date,
                'last_working_date' => $this->last_working_date,
                'reason' => $this->reason,
                'additional_comments' => $this->additional_comments,
                'notice_period_days' => $this->notice_period_days,
                'status' => 'pending'
            ]);

             $employee = getEmployeeDetailsByUserId(Auth::id());
            $extraDetails = [
                'emp_code' => $employee->emp_code,
                'emp_name' => trim("{$employee->emp_first_name} {$employee->emp_middle_name} {$employee->emp_last_name}"),
                'emp_email' => Auth::user()->email,
                'emp_contact_no' => $employee->emp_contact_no,
                'emp_designation' => $employee->designation->name
            ];

            // Send mail with both data sets
            Mail::to('debabrata@codeofdolphins.com')->send(
                new ResignationSubmitted($resignation, $extraDetails)
            );
            // ✅ Send email to HR or desired recipient
            //  Mail::to('debabrata@codeofdolphins.com')->send(new ResignationSubmitted($resignation));
                // Mail::to('debabrata@codeofdolphins.com')
                //     ->send((new ResignationSubmitted($resignation))
                //     ->from('dipankar@codeofdolphins.com', 'Dipankar'));

            session()->flash('success', 'Resignation submitted successfully. HR will review your request.');

            return redirect()->route('employee.resignation.status');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit resignation. Please try again. Error: ' . $e->getMessage());
           //\Log::error('Resignation submission failed: ' . $e->getMessage());
        }

        $this->showConfirmation = false;
    }
    // public function confirmSubmission()
    // {
    //     try {
    //         // Double-check for existing resignation
    //         $existingCheck = Resignation::where('employee_id', Auth::id())
    //             ->whereIn('status', ['pending', 'approved'])
    //             ->first();

    //         if ($existingCheck) {
    //             session()->flash('error', 'You already have a resignation request pending or approved.');
    //             $this->showConfirmation = false;
    //             return;
    //         }

    //         Resignation::create([
    //             'employee_id' => Auth::id(),
    //             'resignation_date' => $this->resignation_date,
    //             'last_working_date' => $this->last_working_date,
    //             'reason' => $this->reason,
    //             'additional_comments' => $this->additional_comments,
    //             'notice_period_days' => $this->notice_period_days,
    //             'status' => 'pending'
    //         ]);

    //         session()->flash('success', 'Resignation submitted successfully. HR will review your request.');

    //         return redirect()->route('employee.resignation.status');
    //     } catch (\Exception $e) {
    //         session()->flash('error', 'Failed to submit resignation. Please try again. Error: ' . $e->getMessage());
    //         \Log::error('Resignation submission failed: ' . $e->getMessage());
    //     }

    //     $this->showConfirmation = false;
    // }

    public function cancelConfirmation()
    {
        $this->showConfirmation = false;
    }

    public function cancel()
    {
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.employees.submit-resignation');
    }
}
