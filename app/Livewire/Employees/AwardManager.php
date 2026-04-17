<?php

namespace App\Livewire\Employees;

use App\Models\Award;
use App\Models\User;
use App\Models\AwardSetting;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Mail;
use App\Mail\AwardGrantedMail;

class AwardManager extends Component
{
    use WithPagination;

    public $showFormModal = false;
    public $search = '';
    public $awardId, $employee_id, $title, $type, $description, $award_date;
    public $isEdit = false;

    protected $rules = [
        'employee_id' => 'required|exists:users,id',
        'title' => 'required|string|max:255',
        'type' => 'required|string',
        'award_date' => 'required|date',
        'description' => 'nullable|string',
    ];

    protected $paginationTheme = 'bootstrap';

    // Add listeners for search functionality
    protected $listeners = ['refreshComponent' => '$refresh'];

    // Reset pagination when search changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function showForm()
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function closeForm()
    {
        $this->resetForm();
        $this->showFormModal = false;
    }

    public function save()
    {
        $this->validate();

        try {
            $award = Award::updateOrCreate(
                ['id' => $this->awardId],
                $this->only('employee_id', 'title', 'type', 'award_date', 'description')
            );

            // Uncomment if you want email notifications
            // if (AwardSetting::first()?->email_notification) {
            //     Mail::to($award->employee->email)->send(new AwardGrantedMail($award));
            // }

            session()->flash('success', $this->isEdit ? 'Award updated successfully!' : 'Award created successfully!');
            $this->closeForm();
            $this->dispatch('refreshComponent');
        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $award = Award::findOrFail($id);
            $this->awardId = $award->id;
            $this->employee_id = $award->employee_id;
            $this->title = $award->title;
            $this->type = $award->type;
            $this->description = $award->description;

            // Multiple ways to handle the date safely
            try {
                if (empty($award->award_date)) {
                    $this->award_date = '';
                } elseif ($award->award_date instanceof \Carbon\Carbon) {
                    $this->award_date = $award->award_date->format('Y-m-d');
                } else {
                    // Handle string dates
                    $this->award_date = \Carbon\Carbon::parse($award->award_date)->format('Y-m-d');
                }
            } catch (\Exception $dateException) {
                // If date parsing fails, set to empty or current date
                $this->award_date = '';
                \Log::warning('Date parsing failed for award ID: ' . $id . ', Date value: ' . $award->award_date);
            }

            $this->isEdit = true;
            $this->showFormModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Award not found or error occurred: ' . $e->getMessage());
            \Log::error('Edit award error: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset(['awardId', 'employee_id', 'title', 'type', 'description', 'award_date', 'isEdit']);
    }

    public function render()
    {
        $query = Award::with('employee');

        // Apply search filter
        if (!empty($this->search)) {
            $query->whereHas('employee', function ($q) {
                $q->where('name', 'like', "%{$this->search}%");
            });
        }

        $awards = $query->latest()->paginate(10);

        $excludedRoles = ['Super Admin', 'Admin', 'Manager'];

        $employees = User::whereDoesntHave('roles', function ($query) use ($excludedRoles) {
            $query->whereIn('name', $excludedRoles);
        })
            ->where('status', '1')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();


        return view('livewire.employees.award-manager', compact('awards', 'employees'));
    }
}
