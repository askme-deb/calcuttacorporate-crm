<?php

namespace App\Http\Controllers;

use App\Models\MeetingSummary;
use Barryvdh\DomPDF\Facade\Pdf;

class MeetingSummaryPdfController extends Controller
{
    public function __invoke(int $meeting)
    {
        $meetingRecord = MeetingSummary::with(['client', 'creator'])->findOrFail($meeting);
        $reportNumber = 'MS-' . str_pad((string) $meetingRecord->id, 5, '0', STR_PAD_LEFT);
        $fileName = 'meeting-summary-' . strtolower($reportNumber) . '.pdf';

        return Pdf::loadView('pdf.meeting-summary', [
            'meetingRecord' => $meetingRecord,
        ])->setPaper('a4')->download($fileName);
    }
}
