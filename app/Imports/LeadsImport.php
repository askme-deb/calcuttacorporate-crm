<?php

namespace App\Imports;

use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadsImport implements ToModel, WithHeadingRow
{
    /**
     * Skip the first row of the Excel file
     */
    public function headingRow(): int
    {
        return 1;
    }

    /**
     * Map the rows to the model
     */
    public function model(array $row)
    {


        $nextFollowupDate = isset($row['next_followup_date']) && !empty($row['next_followup_date'])
            ? Carbon::parse($row['next_followup_date'])->format('Y-m-d') // Safely parse and format the date
            : null;


        return new Lead([
            'name' => $row['name'] ?? null, // Default to null if key doesn't exist
            'email' => $row['email'] ?? null,
            'phone' => $row['phone'] ?? null,
            'source_id' => $row['source_id'] ?? null,
            'status_id' => $row['status_id'] ?? null,
            'notes' => $row['notes'] ?? null,
            'address' => $row['address'] ?? null,
            'company' => $row['company'] ?? null,
            'priority_id' => $row['priority_id'] ?? null,
            'budget' => $row['budget'] ?? null,
            'next_followup_date' => $nextFollowupDate,
            'created_by' => Auth::user()->id, // Add the ID of the authenticated user
        ]);
    }
}
