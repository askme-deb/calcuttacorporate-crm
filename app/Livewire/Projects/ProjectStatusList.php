<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Worksheet;

class ProjectStatusList extends Component
{
    public $status;
    public $search = '';
    public $employee_id = '';
    public $date_from = '';
    public $date_to = '';
    public $work_id = '';
    public $client_id = '';
    public $deadline_from = '';
    public $deadline_to = '';

    public function mount($status)
    {
        $this->status = $status;
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->employee_id = '';
        $this->date_from = '';
        $this->date_to = '';
        $this->work_id = '';
        $this->client_id = '';
        $this->deadline_from = '';
        $this->deadline_to = '';
    }

    public function render()
    {
        $query = Worksheet::query();
        $query->whereHas('projectStatus', function($q) {
            $q->where('name', $this->status);
        });

        if ($this->search) {
            $query->where('title', 'like', '%'.$this->search.'%');
        }
        if ($this->employee_id) {
            $query->where(function($q) {
                $q->whereHas('teamMembers', function($tm) {
                      $tm->where('user_id', $this->employee_id);
                  });
            });
        }
        if ($this->date_from) {
            $query->whereDate('start_date', '>=', $this->date_from);
        }
        if ($this->date_to) {
            $query->whereDate('start_date', '<=', $this->date_to);
        }
        if ($this->work_id) {
            $query->where('work_id', $this->work_id);
        }
        if ($this->client_id) {
            $query->where('client_id', $this->client_id);
        }
        if ($this->deadline_from) {
            $query->whereDate('deadline', '>=', $this->deadline_from);
        }
        if ($this->deadline_to) {
            $query->whereDate('deadline', '<=', $this->deadline_to);
        }
            // Status and overdue_filter logic removed

        $worksheets = $query->get();

        // Fetch filter data
        $employees = \App\Models\User::all();
        $statuses = \App\Models\WorkStatus::where('is_visible', 1)->get();
        $works = \App\Models\WorkMaster::all();
        $clients = \App\Models\Client::all();

        return view('livewire.projects.status-list', [
            'projects' => $worksheets,
            'status' => $this->status,
            'employees' => $employees,
            'statuses' => $statuses,
            'works' => $works,
            'clients' => $clients,
        ]);
    }
}
