<?php

namespace App\Traits;

use App\Models\InvoiceStatusLog;

trait InvoiceLogger
{
    /**
     * Reusable invoice logging function
     */
    public function logInvoice($invoiceId, $field, $old, $new, $description = '')
    {
        InvoiceStatusLog::create([
            'invoice_id'  => $invoiceId,
            'field'       => $field,
            'old_value'   => (string) $old,
            'new_value'   => (string) $new,
            'description' => $description,
            'changed_by'  => auth()->id(),
            'meta_data'   => meta(),  // ✅ using your helper function
        ]);
    }
}
