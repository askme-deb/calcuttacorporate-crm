<?php

namespace App\Imports;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientsImport implements ToModel, WithHeadingRow
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
        return new Client([
            'name'       => $row['name'] ?? null,
            'address'      => $row['address'] ?? null,
            'phone'      => $row['phone'] ?? null,
            'email' => $row['email'] ?? null,
            'gst'       => $row['gst'] ?? null,
            'pan'    => $row['pan'] ?? null,
            'aadhar'       => $row['aadhar'] ?? null,
            'company_name'      => $row['company_name'] ?? null,
            'state_name'      => $row['state_name'] ?? null,
            'state_code' => $row['state_code'] ?? null,
            'city'       => $row['city'] ?? null,
            'pincode'    => $row['pincode'] ?? null,
            'created_by' => Auth::user()->id,
        ]);
    }
}
