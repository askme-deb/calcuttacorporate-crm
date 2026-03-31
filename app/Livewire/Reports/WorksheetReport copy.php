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

class WorksheetReport extends Component
{
    use WithPagination;

    /** ================== FILTERS ================== */
    public $search = '';
    public $employee_id = '';
    public $status_id = '';
    public $date_from = '';
    public $date_to = '';

    /** ================== EXPORT COLUMNS ================== */
    public $availableColumns = [
        'title'      => 'Title',
        'client'     => 'Client',
        'status'     => 'Status',
        'work'       => 'Work',
        'start_date' => 'Start Date',
        'end_date'   => 'End Date',
        'completed_on' => 'Completed On',
        'remarks'    => 'Latest Comment',
    ];

    public $selectedColumns = ['title', 'client', 'status']; // default

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
        'latestRemark.user', // load latest remark + user
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

    /** ================== EXPORT ================== */
    public function exportExcel()
    {
        $worksheets = $this->query()->get()->map(fn($ws) => [
            'title'        => $ws->title,
            'client'       => optional($ws->client)->name,
            'status'       => optional($ws->status)->name,
            'work'         => optional($ws->work)->name,
            'start_date'   => $ws->start_date?->format('Y-m-d') ?? '',
            'end_date'     => $ws->end_date?->format('Y-m-d') ?? '',
            'completed_on' => $ws->completed_on?->format('Y-m-d') ?? '',
            'remarks'      => $this->getLatestRemark($ws),
        ]);

        return Excel::download(
            new WorksheetsExport($worksheets, $this->selectedColumns),
            'worksheets.xlsx'
        );
    }

    public function exportPdf()
    {
        $worksheets = $this->query()->get()->map(fn($ws) => [
            'title'        => mb_convert_encoding($ws->title ?? '', 'UTF-8', 'UTF-8'),
            'work'         => mb_convert_encoding(optional($ws->work)->name ?? '', 'UTF-8', 'UTF-8'),
            'status'       => mb_convert_encoding(optional($ws->status)->name ?? '', 'UTF-8', 'UTF-8'),
            'remarks'      => mb_convert_encoding($this->getLatestRemark($ws), 'UTF-8', 'UTF-8'),
            'start_date'   => $ws->start_date?->format('Y-m-d') ?? '',
            'end_date'     => $ws->end_date?->format('Y-m-d') ?? '',
            'completed_on' => $ws->completed_on?->format('Y-m-d') ?? '',
        ]);

        $pdf = Pdf::loadView('exports.worksheets-pdf', [
            'worksheets' => $worksheets
        ])->setPaper('a4', 'landscape'); // optional: adjust paper size

        return $pdf->download('worksheets.pdf');
    }

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
                'end_date'     => $ws->end_date?->format('Y-m-d') ?? '',
                'completed_on' => $ws->completed_on?->format('Y-m-d') ?? '',
            ]);

        $this->prepareChartData();
        $this->showEmployeeModal = true;
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
        $statuses = WorkStatus::pluck('name', 'id');
        $statusCounts = collect($this->employeeWorks)->groupBy('status')->map->count();

        $this->chartLabels = $statusCounts->keys()->toArray();
        $this->chartData = $statusCounts->values()->toArray();
    }

    public function closeEmployeeModal()
    {
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
        return [
            'labels' => $this->chartLabels,
            'data'   => $this->chartData,
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
                'end_date'     => $ws->end_date?->format('Y-m-d') ?? '',
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
            'employees'  => User::all(),
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
    }

}
