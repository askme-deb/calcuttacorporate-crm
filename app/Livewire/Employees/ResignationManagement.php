<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Resignation;
use Illuminate\Support\Facades\Auth;

class ResignationManagement extends Component
{

     use WithPagination;

    public $statusFilter = 'all';
    public $searchTerm = '';
    public $selectedResignation;
    public $showModal = false;
    public $hr_comments = '';
    public $actionType = '';
    public $dateFilter = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function viewResignation($resignationId)
    {
        $this->selectedResignation = Resignation::with(['employee', 'approver'])->find($resignationId);
        $this->hr_comments = $this->selectedResignation->hr_comments ?? '';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedResignation = null;
        $this->hr_comments = '';
        $this->actionType = '';
    }

    public function setAction($action)
    {
        $this->actionType = $action;
    }

    public function processResignation()
    {
        if (!$this->selectedResignation || !in_array($this->actionType, ['approve', 'reject'])) {
            return;
        }


        //Only check last working date for approval
        if ($this->actionType === 'approve' && now()->lt($this->selectedResignation->last_working_date)) {
             $this->dispatch('toastMessage', json_encode([
            'type' => 'error',
            'message' => "You can only process the resignation after the last working date."
        ]));
            return;
        }



        $status = $this->actionType === 'approve' ? 'approved' : 'rejected';

        $this->selectedResignation->update([
            'status' => $status,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'hr_comments' => $this->hr_comments
        ]);

        if($this->actionType == 'approve'){
                    // Update user status
                $this->selectedResignation->employee->update([
                    'status' => 0
                ]);

                // Update related employee status
                if ($this->selectedResignation->employee->employee) {
                    $this->selectedResignation->employee->employee->update([
                        'status' => 'resigned',
                        'emp_status' => 0
                    ]);
                }
        }

        session()->flash('success', "Resignation {$this->actionType}d successfully.");

        $this->closeModal();
        $this->resetPage();
    }
    public function render()
    {
         $query = Resignation::with(['employee', 'approver'])
            ->when($this->statusFilter !== 'all', function($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->searchTerm, function($q) {
                $q->whereHas('employee', function($query) {
                    $query->where('name', 'like', '%' . $this->searchTerm . '%')
                          ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->orderBy('created_at', 'desc');

        $resignations = $query->paginate(10);

        $stats = [
            'total' => Resignation::count(),
            'pending' => Resignation::where('status', 'pending')->count(),
            'approved' => Resignation::where('status', 'approved')->count(),
            'rejected' => Resignation::where('status', 'rejected')->count(),
        ];

        return view('livewire.employees.resignation-management', compact('resignations', 'stats'));
    }


    public function clearFilters()
    {
        // Reset filter properties
        $this->reset(['statusFilter', 'dateFilter', 'searchTerm']); // adjust to your filters
    }

}
