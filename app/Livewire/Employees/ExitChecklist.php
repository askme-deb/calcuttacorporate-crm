<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use App\Models\Resignation;

class ExitChecklist extends Component
{
    public $resignationId; // Keep this for ID-based approach
    public $resignation; // This can accept the full object
    public $checklist = [
        'laptop_returned' => false,
        'id_card_returned' => false,
        'access_cards_returned' => false,
        'final_settlement_processed' => false,
        'exit_interview_completed' => false,
        'documents_handover_completed' => false,
        'system_access_revoked' => false,
        'final_dues_cleared' => false
    ];

    public $checklistLabels = [
        'laptop_returned' => 'Laptop/Equipment Returned',
        'id_card_returned' => 'ID Card Returned',
        'access_cards_returned' => 'Access Cards Returned',
        'final_settlement_processed' => 'Final Settlement Processed',
        'exit_interview_completed' => 'Exit Interview Completed',
        'documents_handover_completed' => 'Documents Handover Completed',
        'system_access_revoked' => 'System Access Revoked',
        'final_dues_cleared' => 'Final Dues Cleared'
    ];

    public function mount()
    {
        // Handle both approaches - object or ID
        if (is_object($this->resignation) && $this->resignation instanceof Resignation) {
            // Resignation object was passed directly
            // Do nothing, we already have it
        } elseif ($this->resignationId) {
            // Load by ID
            $this->resignation = Resignation::with('employee')->findOrFail($this->resignationId);
        } elseif (is_numeric($this->resignation)) {
            // Sometimes the resignation property contains the ID
            $this->resignationId = $this->resignation;
            $this->resignation = Resignation::with('employee')->findOrFail($this->resignationId);
        } else {
            throw new \Exception('No valid resignation ID or object provided');
        }
        
        // Ensure we have a valid resignation object
        if (!$this->resignation instanceof Resignation) {
            throw new \Exception('Invalid resignation data provided');
        }

        // Merge existing checklist data if it exists
        if ($this->resignation->exit_checklist && is_array($this->resignation->exit_checklist)) {
            $this->checklist = array_merge($this->checklist, $this->resignation->exit_checklist);
        }
    }

    public function updateChecklist()
    {
        $this->resignation->update([
            'exit_checklist' => $this->checklist,
            'is_notice_period_served' => $this->allChecklistCompleted()
        ]);

        session()->flash('success', 'Exit checklist updated successfully.');
    }

    private function allChecklistCompleted()
    {
        return !in_array(false, $this->checklist);
    }

    public function getCompletionPercentageProperty()
    {
        $completed = count(array_filter($this->checklist));
        $total = count($this->checklist);

        return round(($completed / $total) * 100);
    }

    public function render()
    {
        return view('livewire.employees.exit-checklist');
    }
}