<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
class WorksheetsExport implements FromView
{
    protected $worksheets;
    protected $selectedColumns;

    public function __construct($worksheets, $selectedColumns)
    {
        $this->worksheets = $worksheets;
        $this->selectedColumns = $selectedColumns;
    }

    public function view(): View
    {
        return view('exports.worksheets-excel', [
            'worksheets'      => $this->worksheets,
            'selectedColumns' => $this->selectedColumns,
        ]);
    }
}
