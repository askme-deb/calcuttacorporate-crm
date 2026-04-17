<?php

namespace App\Livewire\Employees;

use App\Mail\TerminationNotification;
use Livewire\Component;
use App\Models\Termination;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TerminationModule extends Component
{
    public $employee_id, $termination_date, $reason, $remarks;
    public $terminations;
    public $filterEmployee = '';
    public $filterStatus = '';

    // For confirmation dialogs
    public $showConfirmApprove = false;
    public $showConfirmReject = false;
    public $terminationToProcess = null;
    public $selectedTermination;

    public function viewDetails($id)
    {
        $this->selectedTermination = Termination::with('employee')->findOrFail($id);
    }
    public function updated($property)
    {
        if (in_array($property, ['filterEmployee', 'filterStatus'])) {
            $this->loadTerminations();
        }
    }

    public function mount()
    {
        $this->loadTerminations();
    }

    public function loadTerminations()
    {
        $query = Termination::with('employee')->latest();

        if ($this->filterEmployee) {
            $query->where('employee_id', $this->filterEmployee);
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $this->terminations = $query->get();
    }

    public function submit()
    {
        $this->validate([
            'employee_id' => 'required|exists:users,id',
            'termination_date' => 'required|date',
            'reason' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        // Check if employee already has pending or approved termination
        $existingTermination = Termination::where('employee_id', $this->employee_id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingTermination) {
            $status = $existingTermination->status;
            $message = $status === 'approved'
                ? 'This employee is already terminated.'
                : 'This employee already has a pending termination request.';

            session()->flash('error', $message);
            return;
        }

        Termination::create([
            'employee_id' => $this->employee_id,
            'termination_date' => $this->termination_date,
            'reason' => $this->reason,
            'remarks' => $this->remarks,
            'status' => 'pending', // Ensure default status is set
        ]);

        session()->flash('message', 'Termination request submitted successfully.');
        $this->reset(['employee_id', 'termination_date', 'reason', 'remarks']);
        $this->loadTerminations();

        // Close the modal after successful submission
        $this->dispatch('close-modal');
    }

    public function render()
    {
        $excludedRoles = ['Super Admin', 'Admin', 'Manager'];

        $allEmployees = User::whereDoesntHave('roles', function ($query) use ($excludedRoles) {
            $query->whereIn('name', $excludedRoles);
        })
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        // $allEmployees = User::select('id', 'name')->orderBy('name')->get();
        // Get employees who don't have pending or approved terminations
        // $employees = User::whereNotExists(function ($query) {
        //     $query->selectRaw('1')
        //         ->from('terminations')
        //         ->whereColumn('terminations.employee_id', 'users.id')
        //         ->whereIn('status', ['pending', 'approved']);
        // })->get();
        $employees = User::whereNotExists(function ($query) {
            $query->selectRaw('1')
                ->from('terminations')
                ->whereColumn('terminations.employee_id', 'users.id')
                ->whereIn('status', ['pending', 'approved']);
        })
            ->whereDoesntHave('roles', function ($query) use ($excludedRoles) {
                $query->whereIn('name', $excludedRoles);
            })
            ->get();

        return view('livewire.employees.termination-module', [
            'employees' => $employees,
            'allEmployees' => $allEmployees,
            'terminations' => $this->terminations,
        ]);
    }

    // Show confirmation dialog for approval
    public function confirmApprove($id)
    {
        $this->terminationToProcess = $id;
        $this->showConfirmApprove = true;
    }

    // Show confirmation dialog for rejection
    public function confirmReject($id)
    {
        $this->terminationToProcess = $id;
        $this->showConfirmReject = true;
    }

    // Cancel confirmation dialogs
    public function cancelConfirmation()
    {
        $this->showConfirmApprove = false;
        $this->showConfirmReject = false;
        $this->terminationToProcess = null;
    }

    public function approve()
    {
        if (!$this->terminationToProcess) {
            return;
        }

        $termination = Termination::findOrFail($this->terminationToProcess);

        // Double-check the termination is still pending
        if ($termination->status !== 'pending') {
            session()->flash('error', 'This termination request is no longer pending.');
            $this->cancelConfirmation();
            $this->loadTerminations();
            return;
        }

        $termination->status = 'approved';
        $termination->approved_at = now(); // Optional: track when it was approved
        $termination->save();


        // Update user status
        $termination->employee->update([
            'status' => 0
        ]);

         // Update related employee status
        if ($termination->employee->employee) {
            $termination->employee->employee->update([
                'status' => 'terminated'
            ]);
        }

        try {
            Mail::to($termination->employee->email)->send(
                new TerminationNotification($termination)
            );
            session()->flash('message', 'Termination approved and employee notified.');
        } catch (\Exception $e) {
            session()->flash('message', 'Termination approved, but failed to send email notification.');
        }

        $this->cancelConfirmation();
        $this->loadTerminations();
    }

    public function reject()
    {
        if (!$this->terminationToProcess) {
            return;
        }

        $termination = Termination::findOrFail($this->terminationToProcess);

        // Double-check the termination is still pending
        if ($termination->status !== 'pending') {
            session()->flash('error', 'This termination request is no longer pending.');
            $this->cancelConfirmation();
            $this->loadTerminations();
            return;
        }

        $termination->status = 'rejected';
        $termination->rejected_at = now(); // Optional: track when it was rejected
        $termination->save();

        try {
            Mail::to($termination->employee->email)->send(
                new TerminationNotification($termination)
            );
            session()->flash('message', 'Termination rejected and employee notified.');
        } catch (\Exception $e) {
            session()->flash('message', 'Termination rejected, but failed to send email notification.');
        }

        $this->cancelConfirmation();
        $this->loadTerminations();
    }

    // Helper method to get available employees (those without pending/approved terminations)
    public function getAvailableEmployeesProperty()
    {
        return User::whereNotExists(function ($query) {
            $query->selectRaw('1')
                ->from('terminations')
                ->whereColumn('terminations.employee_id', 'users.id')
                ->whereIn('status', ['pending', 'approved']);
        })->get();
    }
}
