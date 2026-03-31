<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Worksheet;

class EmployeeWorksExport implements FromView
{
    protected $employeeId;

    public function __construct($employeeId)
    {
        $this->employeeId = $employeeId;
    }

    public function view(): View
    {
        $works = Worksheet::with(['work', 'status', 'remarks'])
            ->whereHas('teamMembers', fn($q) => $q->where('user_id', $this->employeeId))
            ->get()
            ->map(fn($ws) => [
                'title'      => $ws->title,
                'work'       => optional($ws->work)->name,
                'status'     => optional($ws->status)->name,
                'remarks' => collect($ws->remarks)->pluck('remarks')->implode(', '),
                'start_date' => $ws->start_date?->format('Y-m-d'),
                'completed_on'   => $ws->completed_on?->format('Y-m-d'),
            ]);

        return view('exports.employee-works-excel', [
            'works' => $works,
        ]);
    }
}
