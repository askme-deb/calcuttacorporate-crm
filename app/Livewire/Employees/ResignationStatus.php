<?php

namespace App\Livewire\Employees;

use App\Mail\ResignationWithdrawnMail;
use App\Models\Resignation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;

class ResignationStatus extends Component
{
    public $resignation;
    public $showWithdrawConfirmation = false;

    public function mount()
    {
        $this->loadResignation();
    }

    public function loadResignation()
    {
        $this->resignation = Resignation::with('approver')
            ->where('employee_id', Auth::id())
            ->latest()
            ->first();
    }

    public function withdrawResignation()
    {
        // Add debugging
        // Log::info('withdrawResignation method called');
        // Log::info('Resignation exists: ' . ($this->resignation ? 'Yes' : 'No'));
        // Log::info('Resignation status: ' . ($this->resignation ? $this->resignation->status : 'No resignation'));

        // Check if resignation exists and is pending
        if (!$this->resignation) {
            // Log::error('No resignation found for user: ' . Auth::id());
            session()->flash('error', 'No resignation found.');
            return;
        }

        if ($this->resignation->status !== 'pending') {
            // Log::error('Resignation status is not pending: ' . $this->resignation->status);
            session()->flash('error', 'Cannot withdraw resignation. Current status: ' . $this->resignation->status);
            return;
        }

        // Log::info('Setting showWithdrawConfirmation to true');
        $this->showWithdrawConfirmation = true;
    }

    public function confirmWithdraw()
    {
        // Log::info('confirmWithdraw method called');

        try {
            // Double-check the conditions
            if (!$this->resignation || $this->resignation->status !== 'pending') {
              //  Log::error('Validation failed in confirmWithdraw');
                session()->flash('error', 'Cannot withdraw resignation at this time.');
                $this->showWithdrawConfirmation = false;
                return;
            }

            // Update the resignation status
            $updated = $this->resignation->update([
                'status'        => 'withdrawn',
                'withdrawn_at'  => now(),
                'withdrawn_by'  => Auth::id(),
            ]);

           // Log::info('Resignation updated: ' . ($updated ? 'Success' : 'Failed'));

            if ($updated) {
                // Reload fresh data
                $this->loadResignation();

                // Prepare recipients
                $user   = Auth::user();
                $hrMail = 'debabrata@codeofdolphins.com';

                // Send to the employee
                Mail::to($user->email)
                    ->send(new ResignationWithdrawnMail($user, $this->resignation));

                // Send to HR
                Mail::to($hrMail)
                    ->send(new ResignationWithdrawnMail($user, $this->resignation, true));

                // Log::info('Withdrawal notification emails sent to user and HR.');

                session()->flash('success', 'Resignation has been withdrawn successfully.');
            } else {
                session()->flash('error', 'Failed to update resignation status.');
            }
        } catch (\Exception $e) {
            // Log::error('Exception in confirmWithdraw: ' . $e->getMessage());
            // Log::error('Stack trace: ' . $e->getTraceAsString());
            session()->flash('error', 'Failed to withdraw resignation. Please try again.');
        }

        $this->showWithdrawConfirmation = false;
    }

    // public function confirmWithdraw()
    // {
    //     Log::info('confirmWithdraw method called');

    //     try {
    //         // Double-check the conditions
    //         if (!$this->resignation || $this->resignation->status !== 'pending') {
    //             Log::error('Validation failed in confirmWithdraw');
    //             session()->flash('error', 'Cannot withdraw resignation at this time.');
    //             $this->showWithdrawConfirmation = false;
    //             return;
    //         }

    //         // Update the resignation status
    //         $updated = $this->resignation->update([
    //             'status' => 'withdrawn',
    //             'withdrawn_at' => now(),
    //             'withdrawn_by' => Auth::id()
    //         ]);

    //         Log::info('Resignation updated: ' . ($updated ? 'Success' : 'Failed'));





    //         // Refresh the data
    //         $this->loadResignation();

    //         session()->flash('success', 'Resignation has been withdrawn successfully.');

    //     } catch (\Exception $e) {
    //         Log::error('Exception in confirmWithdraw: ' . $e->getMessage());
    //         Log::error('Stack trace: ' . $e->getTraceAsString());
    //         session()->flash('error', 'Failed to withdraw resignation. Please try again.');
    //     }

    //     $this->showWithdrawConfirmation = false;
    // }

    public function cancelWithdraw()
    {
        Log::info('cancelWithdraw method called');
        $this->showWithdrawConfirmation = false;
    }

    // Add a test method to verify Livewire is working

    public function render()
    {
        return view('livewire.employees.resignation-status');
    }
}
