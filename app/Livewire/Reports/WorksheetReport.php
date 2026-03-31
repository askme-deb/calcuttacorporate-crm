<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Worksheet;
use App\Models\User;
use App\Models\WorkStatus;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\WorksheetsExport;
use App\Exports\EmployeeWorksExport;
use App\Models\Client;
use App\Models\WorkMaster;

class WorksheetReport extends Component
{
    use WithPagination;

    /** ================== FILTERS ================== */
    public $search = '';
    public $employee_id = '';
    public $status_id = '';
    public $date_from = '';
    public $date_to = '';


    public $work_id = '';
    public $client_id = '';
    public $overdue_filter = '';
    public $deadline_from = '';
    public $deadline_to = '';


    /** ================== EXPORT COLUMNS ================== */
    public $availableColumns = [
        'title'      => 'Title',
        'client'     => 'Client',
        'work'       => 'Work',  
        'manager'    => 'Manager',
        'assigned_to'    => 'Assigned To',
        'assigned_on'    => 'Assigned On',
        'start_date' => 'Start Date',
        'deadline'   => 'Deadline',
        'status'     => 'Status',
        'completed_on' => 'Completed On',
        'remarks'    => 'Latest Comment',
    ];

    public $selectedColumns = ['title', 'client', 'work', 'status', 'start_date','deadline' ]; // default

    /** ================== EMPLOYEE MODAL ================== */
    public $showEmployeeModal = false;
    public $selectedEmployee;
    public $employeeWorks = [];
    public $chartLabels = [];
    public $chartData = [];

    protected $paginationTheme = 'bootstrap';

    /** ================== LIFECYCLE ================== */
    public function updating($field)
    {
        $this->resetPage();
    }

    /** ================== QUERY ================== */
    protected function query()
    {
        $query = Worksheet::with([
            'client',
            'work',
            'status',
            'teamMembers.user',
            'teamMembers.assignedBy',
            'latestRemark.user',
            'remarks.user',
        ]);



        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . trim($this->search) . '%');
        }

        if (!empty($this->employee_id) && is_numeric($this->employee_id)) {
            $query->whereHas('teamMembers', fn($q) => $q->where('user_id', (int) $this->employee_id));
        }

        if (!empty($this->status_id) && is_numeric($this->status_id)) {
            $query->where('status_id', (int) $this->status_id);
        }

        if ($this->date_from && $this->date_to) {
            $query->whereBetween('start_date', [$this->date_from, $this->date_to]);
        } elseif ($this->date_from) {
            $query->whereDate('start_date', '>=', $this->date_from);
        } elseif ($this->date_to) {
            $query->whereDate('start_date', '<=', $this->date_to);
        }
        if (!empty($this->work_id) && is_numeric($this->work_id)) {
            $query->where('work_id', (int) $this->work_id);
        }

        if (!empty($this->client_id) && is_numeric($this->client_id)) {
            $query->where('client_id', (int) $this->client_id);
        }

        if ($this->overdue_filter == 'overdue') {
            $query->where('status_id', '!=', WorkStatus::where('name', 'Completed')->first()?->id)
                ->whereDate('deadline', '<', now());
        }

        if ($this->overdue_filter == 'pending') {
            $query->where('status_id', WorkStatus::where('name', 'Pending')->first()?->id);
        }

        if ($this->overdue_filter == 'completed') {
            $query->where('status_id', WorkStatus::where('name', 'Completed')->first()?->id);
        }

        if ($this->deadline_from && $this->deadline_to) {
            $query->whereBetween('deadline', [$this->deadline_from, $this->deadline_to]);
        } elseif ($this->deadline_from) {
            $query->whereDate('deadline', '>=', $this->deadline_from);
        } elseif ($this->deadline_to) {
            $query->whereDate('deadline', '<=', $this->deadline_to);
        }
        return $query;
    }

/** ================== HELPER METHODS ================== */
protected function getLatestRemark($worksheet)
{
    $latestRemark = $worksheet->latestRemark; // use hasOne relationship

    if ($latestRemark) {
        $userName = optional($latestRemark->user)->name ?? 'Unknown';
        $createdAt = $latestRemark->created_at ? $latestRemark->created_at->format('M d, Y H:i') : '';
        return $latestRemark->remarks . ($userName ? " - by {$userName}" : '') . ($createdAt ? " ({$createdAt})" : '');
    }

    return 'No comments yet';
}
protected function utf8ize($mixed)
{
    if (is_array($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed[$key] = $this->utf8ize($value);
        }
    } elseif (is_string($mixed)) {
        // Remove invalid UTF-8 bytes
        $mixed = iconv('UTF-8', 'UTF-8//IGNORE', $mixed);
    }
    return $mixed;
}


    /** ================== EXPORT ================== */
public function exportExcel()
{
    $worksheets = $this->query()->with([
        'client',
        'work',
        'status',
        'teamMembers.user',
        'teamMembers.assignedBy',
        'remarks.user'
    ])->get()->map(function ($ws) {

        // --- Manager logic ---
        $allPMTeamMembers = $ws->teamMembers
            ->filter(fn($tm) => $tm->user && $tm->user->hasRole('Project Manager'))
            ->sortBy(fn($tm) => optional($tm)->assigned_on ?? now());

        $primaryPM = $allPMTeamMembers->first();
        $pmAssignedBy = optional($ws->teamMembers->first())->assignedBy;

        $managerName = '';
        if ($pmAssignedBy && $pmAssignedBy->hasRole('Project Manager')) {
            $managerName = $pmAssignedBy->name . ' (PM, AssignedBy)';
        } elseif ($primaryPM) {
            $date = optional($primaryPM)->assigned_on
                ? \Carbon\Carbon::parse($primaryPM->assigned_on)->format('d M Y')
                : '-';
            $managerName = $primaryPM->user->name . " (PM on {$date})";
        }

        // --- Assigned To logic ---
        $nonPMAssignees = $ws->teamMembers->filter(fn($tm) => $tm->user && !$tm->user->hasRole('Project Manager'));
        $additionalPMs = $allPMTeamMembers->skip(1);
        $allAssignees = $nonPMAssignees->concat($additionalPMs)
            ->sortBy(fn($tm) => optional($tm)->assigned_on ?? now());

        $assignedTo = $allAssignees->map(function ($tm) {
            $role = $tm->user->hasRole('Project Manager') ? '(Additional PM)' : '';
            $by = optional($tm->assignedBy)->name ?? '-';
            $date = optional($tm)->assigned_on
                ? \Carbon\Carbon::parse($tm->assigned_on)->format('d M Y')
                : '-';
            return "{$tm->user->name} {$role} | by {$by} on {$date}";
        })->implode("\n");

        // --- Status with deadline info ---
        $status = optional($ws->status)->name;
        if ($status === 'Completed' && $ws->completed_on) {
            $status .= " (on " . $ws->completed_on->format('d M Y') . ")";
        } elseif ($status && $ws->deadline) {
            $today = now()->startOfDay();
            $deadline = $ws->deadline->startOfDay();
            $remaining = $today->diffInDays($deadline, false);

            if ($remaining < 0) {
                $status .= " | Overdue by " . abs($remaining) . " day(s)";
            } elseif ($remaining > 0) {
                $status .= " | {$remaining} day(s) left";
            } else {
                $status .= " | Deadline today";
            }
        }

        // --- Latest remarks ---
        $latestRemark = $ws->latestRemark;
        $totalRemarks = $ws->remarks?->count() ?? 0;
        $remarks = '';
        if ($latestRemark) {
            $remarks = strip_tags($latestRemark->remarks) .
                " | by " . ($latestRemark->user->name ?? 'Unknown') .
                " on " . ($latestRemark->created_at?->format('d M Y, H:i') ?? '');
            if ($totalRemarks > 1) {
                $remarks .= " | + " . ($totalRemarks - 1) . " more";
            }
        }

        return [
            'title'        => $ws->title,
            'client'       => optional($ws->client)->name,
            'work'         => optional($ws->work)->name,
            'manager'      => $managerName,
            'assigned_to'  => $assignedTo,
            'assigned_on'  => $ws->assigned_on?->format('d M Y') ?? '',
            'start_date'   => $ws->start_date?->format('d M Y') ?? '',
            'deadline'     => $ws->deadline?->format('d M Y') ?? '',
            'status'       => $status,
            'completed_on' => $ws->completed_on?->format('d M Y') ?? '',
            'remarks'      => $remarks,
        ];
    });

    // ✅ Apply column order & UTF-8 cleaning
    $worksheets = $worksheets->map(function ($row) {
        return collect($this->availableColumns) // maintain defined order
            ->keys()
            ->filter(fn($col) => in_array($col, $this->selectedColumns)) // only selected cols
            ->mapWithKeys(fn($col) => [$col => $this->cleanUtf8($row[$col] ?? '')])
            ->toArray();
    })->toArray();

    return Excel::download(
        new WorksheetsExport($worksheets, $this->selectedColumns),
        'worksheets.xlsx'
    );
}


/**
 * Ensure UTF-8 safe values
 */
protected function cleanUtf8($value)
{
    return is_string($value)
        ? mb_convert_encoding($value, 'UTF-8', 'UTF-8')
        : $value;
}




public function exportPdf()
{
   $worksheets = $this->query()->with([
        'client',
        'work',
        'status',
        'teamMembers.user',
        'teamMembers.assignedBy',
        'remarks.user'
    ])->get()->map(function ($ws) {

        // --- Manager logic ---
        $allPMTeamMembers = $ws->teamMembers
            ->filter(fn($tm) => $tm->user && $tm->user->hasRole('Project Manager'))
            ->sortBy(fn($tm) => optional($tm)->assigned_on ?? now());

        $primaryPM = $allPMTeamMembers->first();
        $pmAssignedBy = optional($ws->teamMembers->first())->assignedBy;

        $managerName = '';
        if ($pmAssignedBy && $pmAssignedBy->hasRole('Project Manager')) {
            $managerName = $pmAssignedBy->name . ' (PM, AssignedBy)';
        } elseif ($primaryPM) {
            $date = optional($primaryPM)->assigned_on
                ? \Carbon\Carbon::parse($primaryPM->assigned_on)->format('d M Y')
                : '-';
            $managerName = $primaryPM->user->name . " (PM on {$date})";
        }

        // --- Assigned To logic ---
        $nonPMAssignees = $ws->teamMembers->filter(fn($tm) => $tm->user && !$tm->user->hasRole('Project Manager'));
        $additionalPMs = $allPMTeamMembers->skip(1);
        $allAssignees = $nonPMAssignees->concat($additionalPMs)
            ->sortBy(fn($tm) => optional($tm)->assigned_on ?? now());

        $assignedTo = $allAssignees->map(function ($tm) {
            $role = $tm->user->hasRole('Project Manager') ? '(Additional PM)' : '';
            $by = optional($tm->assignedBy)->name ?? '-';
            $date = optional($tm)->assigned_on
                ? \Carbon\Carbon::parse($tm->assigned_on)->format('d M Y')
                : '-';
            return "{$tm->user->name} {$role} | by {$by} on {$date}";
        })->implode("\n");

        // --- Status with deadline info ---
        $status = optional($ws->status)->name;
        if ($status === 'Completed' && $ws->completed_on) {
            $status .= " (on " . $ws->completed_on->format('d M Y') . ")";
        } elseif ($status && $ws->deadline) {
            $today = now()->startOfDay();
            $deadline = $ws->deadline->startOfDay();
            $remaining = $today->diffInDays($deadline, false);

            if ($remaining < 0) {
                $status .= " | Overdue by " . abs($remaining) . " day(s)";
            } elseif ($remaining > 0) {
                $status .= " | {$remaining} day(s) left";
            } else {
                $status .= " | Deadline today";
            }
        }

        // --- Latest remarks ---
        $latestRemark = $ws->latestRemark;
        $totalRemarks = $ws->remarks?->count() ?? 0;
        $remarks = '';
        if ($latestRemark) {
            $remarks = strip_tags($latestRemark->remarks) .
                " | by " . ($latestRemark->user->name ?? 'Unknown') .
                " on " . ($latestRemark->created_at?->format('d M Y, H:i') ?? '');
            if ($totalRemarks > 1) {
                $remarks .= " | + " . ($totalRemarks - 1) . " more";
            }
        }

        return [
            'title'        => $ws->title,
            'client'       => optional($ws->client)->name,
            'work'         => optional($ws->work)->name,
            'manager'      => $managerName,
            'assigned_to'  => $assignedTo,
            'assigned_on'  => $ws->assigned_on?->format('d M Y') ?? '',
            'start_date'   => $ws->start_date?->format('d M Y') ?? '',
            'deadline'     => $ws->deadline?->format('d M Y') ?? '',
            'status'       => $status,
            'completed_on' => $ws->completed_on?->format('d M Y') ?? '',
            'remarks'      => $remarks,
        ];
    });

    // ✅ Apply column order & UTF-8 cleaning
    $worksheets = $worksheets->map(function ($row) {
        return collect($this->availableColumns) // maintain defined order
            ->keys()
            ->filter(fn($col) => in_array($col, $this->selectedColumns)) // only selected cols
            ->mapWithKeys(fn($col) => [$col => $this->cleanUtf8($row[$col] ?? '')])
            ->toArray();
    })->toArray();

    $pdf = Pdf::loadView('exports.worksheets-pdf', [
        'worksheets'       => $worksheets,
        'selectedColumns'  => $this->selectedColumns,
        'availableColumns' => $this->availableColumns,
    ])->setPaper('a4', 'landscape');

    return response()->streamDownload(
        fn () => print($pdf->output()),
        'worksheets.pdf'
    );
}


    // public function exportPdf()
    // {
    //     $worksheets = $this->query()->get()->map(fn($ws) => [
    //         'title'        => $this->safeUtf8($ws->title ?? ''),
    //         'work'         => $this->safeUtf8(optional($ws->work)->name ?? ''),
    //         'status'       => $this->safeUtf8(optional($ws->status)->name ?? ''),
    //         'remarks'      => $this->safeUtf8($this->getLatestRemark($ws)),
    //         'start_date'   => $ws->start_date?->format('Y-m-d') ?? '',
    //         'deadline'     => $ws->deadline?->format('Y-m-d') ?? '',
    //         'completed_on' => $ws->completed_on?->format('Y-m-d') ?? '',
    //     ]);


    //     $pdf = Pdf::loadView('exports.worksheets-pdf', [
    //         'worksheets' => $worksheets
    //     ])->setPaper('a4', 'landscape'); // optional: adjust paper size

    //     return $pdf->download('worksheets.pdf');
    // }



/** ================== EMPLOYEE DETAILS ================== */
public function showEmployeeDetails($employeeId)
{
    $this->selectedEmployee = User::find($employeeId);
    if (!$this->selectedEmployee) return;

    $this->employeeWorks = $this->getEmployeeWorks($employeeId)
        ->map(fn($ws) => [
            'title'        => $ws->title,
            'work'         => optional($ws->work)->name,
            'status'       => optional($ws->status)->name,
            'remarks'      => $this->getLatestRemark($ws),
            'start_date'   => $ws->start_date?->format('Y-m-d') ?? '',
            'deadline'     => $ws->deadline?->format('Y-m-d') ?? '',
            'completed_on' => $ws->completed_on?->format('Y-m-d') ?? '',
        ]);

    $this->prepareChartData();
    $this->showEmployeeModal = true;

    // Dispatch the event after setting all data
    $this->dispatch('employee-modal-opened');
}

protected function getEmployeeWorks($employeeId)
{
    return Worksheet::with([
        'remarks' => function($query) {
            $query->orderBy('created_at', 'desc');
        },
        'remarks.user',
        'status',
        'work'
    ])
    ->whereHas('teamMembers', fn($q) => $q->where('user_id', $employeeId))
    ->get();
}

protected function prepareChartData()
{
    // Get status counts from the employee works collection
    $statusCounts = collect($this->employeeWorks)
        ->groupBy('status')
        ->map->count()
        ->filter(fn($count) => $count > 0); // Remove statuses with 0 count

    // Convert to arrays for Chart.js
    $this->chartLabels = $statusCounts->keys()->values()->toArray();
    $this->chartData = $statusCounts->values()->toArray();

    // Debug logging (remove in production)
    \Log::info('Chart Data Prepared', [
        'labels' => $this->chartLabels,
        'data' => $this->chartData,
        'total_works' => count($this->employeeWorks)
    ]);
}

public function closeEmployeeModal()
{
    $this->dispatch('employee-modal-closed'); // Dispatch before reset

    $this->reset([
        'showEmployeeModal',
        'selectedEmployee',
        'employeeWorks',
        'chartLabels',
        'chartData',
    ]);
}

public function getEmployeeChartData()
{
    // Ensure we have data
    if (empty($this->chartLabels) || empty($this->chartData)) {
        $this->prepareChartData();
    }

    return [
        'labels' => $this->chartLabels ?? [],
        'data'   => $this->chartData ?? [],
    ];
}
    /** ================== EMPLOYEE EXPORT ================== */
    public function exportEmployeeExcel()
    {
        if (!$this->selectedEmployee) return;

        return Excel::download(
            new EmployeeWorksExport($this->selectedEmployee->id),
            $this->selectedEmployee->name . '_works.xlsx'
        );
    }

    public function exportEmployeePdf()
    {
        if (!$this->selectedEmployee) return;

        $works = $this->getEmployeeWorks($this->selectedEmployee->id)
            ->map(fn($ws) => [
                'title'        => mb_convert_encoding($ws->title ?? '', 'UTF-8', 'UTF-8'),
                'work'         => mb_convert_encoding(optional($ws->work)->name ?? '', 'UTF-8', 'UTF-8'),
                'status'       => mb_convert_encoding(optional($ws->status)->name ?? '', 'UTF-8', 'UTF-8'),
                'remarks'      => mb_convert_encoding($this->getLatestRemark($ws), 'UTF-8', 'UTF-8'),
                'start_date'   => $ws->start_date?->format('Y-m-d') ?? '',
                'deadline'     => $ws->deadline?->format('Y-m-d') ?? '',
                'completed_on' => $ws->completed_on?->format('Y-m-d') ?? '',
            ]);

        $pdf = Pdf::loadView('exports.employee-works', [
            'employee' => $this->selectedEmployee,
            'works'    => $works,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            $this->selectedEmployee->name . '_works.pdf'
        );
    }

    /** ================== RENDER ================== */
    public function render()
    {
        return view('livewire.reports.worksheet-report', [
            'worksheets' => $this->query()->paginate(10),
            'employees'  => User::whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['Super Admin']);
            })->get(),
            'works'      => WorkMaster::all(),
            'clients'    => Client::all(),
            'statuses'   => WorkStatus::all(),
        ]);
    }



public function resetFilters()
{
    $this->search = '';
    $this->employee_id = '';
    $this->status_id = '';
    $this->date_from = '';
    $this->date_to = '';
    $this->work_id = '';
    $this->client_id = '';
    $this->deadline_from = '';
    $this->deadline_to = '';
    $this->overdue_filter = '';
}

}
