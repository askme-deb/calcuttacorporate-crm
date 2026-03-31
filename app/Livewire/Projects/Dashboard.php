<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Worksheet;
use App\Models\User;
use App\Models\WorkStatus;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WorksheetsExport;

class Dashboard extends Component
{
    public function getDashboardData()
    {
        $data = [];

        // Total Works
        $data['total'] = Worksheet::count();

        // Status counts dynamically
        $statusCounts = Worksheet::select('status_id', \DB::raw('COUNT(*) as total'))
            ->groupBy('status_id')
            ->pluck('total', 'status_id');

        $statuses = WorkStatus::whereIn('id', $statusCounts->keys())->pluck('name', 'id');

        $data['status_labels'] = $statuses->values();
        $data['status_counts'] = $statusCounts->values();

        // Some quick KPIs
        $data['total']     = Worksheet::count();
        $data['completed'] = Worksheet::whereHas('status', fn($q) => $q->where('name', 'Completed'))->count();
        $data['pending']   = Worksheet::whereHas('status', fn($q) => $q->where('name', 'Pending'))->count();
        $data['inprogress'] = Worksheet::whereHas('status', fn($q) => $q->where('name', 'In Progress'))->count();
        $data['nostarted'] = Worksheet::whereHas('status', fn($q) => $q->where('name', 'Not Started'))->count();
        $data['reqquirementsgathering'] = Worksheet::whereHas('status', fn($q) => $q->where('name', 'Requirements Gathering'))->count();
        $data['onhold'] = Worksheet::whereHas('status', fn($q) => $q->where('name', 'On Hold'))->count();
        $data['pendingapproval'] = Worksheet::whereHas('status', fn($q) => $q->where('name', 'Pending Approval'))->count();
        $data['cancelled'] = Worksheet::whereHas('status', fn($q) => $q->where('name', 'Cancelled'))->count();
        $data['delayed'] = Worksheet::whereHas('status', fn($q) => $q->where('name', 'Delayed'))->count();

        $data['planning'] = Worksheet::whereHas('status', fn($q) => $q->where('name', 'Planning'))->count();
        $data['resourcesallocated'] = Worksheet::whereHas('status', fn($q) => $q->where('name', 'Resources Allocated'))->count();
        $data['revisionsrequired'] = Worksheet::whereHas('status', fn($q) => $q->where('name', 'Revisions Required'))->count();
        $data['testingreview'] = Worksheet::whereHas('status', fn($q) => $q->where('name', 'Testing/Review'))->count();
        $data['archived'] = Worksheet::whereHas('status', fn($q) => $q->where('name', 'Archived'))->count();
        $data['clientreviewpending'] = Worksheet::whereHas('status', fn($q) => $q->where('name', 'Client Review Pending'))->count();

                // Employee workload - top 10
        $employees = User::withCount('worksheets')
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'Project Manager');
            })
            ->orderByDesc('worksheets_count')
            ->take(10)
            ->get();
        
        $data['employee_names'] = $employees->pluck('name');
        $data['employee_works'] = $employees->pluck('worksheets_count');

                // Get IDs for relevant statuses
                $completedId = WorkStatus::where('name', 'Completed')->value('id');
                $inProgressId = WorkStatus::where('name', 'In Progress')->value('id');
                $pendingId = WorkStatus::where('name', 'Not Started')->value('id');

                // Query worksheets per month
                $worksByMonth = Worksheet::selectRaw("
                    DATE_FORMAT(start_date, '%Y-%m') as month,
                    SUM(CASE WHEN status_id = ? THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status_id = ? THEN 1 ELSE 0 END) as inprogress,
                    SUM(CASE WHEN status_id = ? THEN 1 ELSE 0 END) as pending,
                    COUNT(*) as total
                ", [$completedId, $inProgressId, $pendingId])
                ->groupBy('month')
                ->orderBy('month')
                ->get();


        $data['months'] = $worksByMonth->pluck('month');
        $data['works_completed'] = $worksByMonth->pluck('completed');
        $data['works_pending'] = $worksByMonth->pluck('pending');
        $data['works_inprogress'] = $worksByMonth->pluck('inprogress');
        $data['works_total'] = $worksByMonth->pluck('total');

        return $data;
    }

    /** ========== EXPORTS ========== */
    public function exportExcel()
    {
        return Excel::download(new WorksheetsExport, 'worksheets.xlsx');
    }

    public function exportPdf()
    {
        $works = Worksheet::with('status', 'assignedTo')->get();
        $pdf = Pdf::loadView('exports.worksheets', compact('works'));
        return response()->streamDownload(fn() => print($pdf->output()), 'worksheets.pdf');
    }

    public function render()
    {
        return view('livewire.projects.dashboard', [
            'dashboard' => $this->getDashboardData()
        ]);
    }
}
