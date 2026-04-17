<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function gstPrint($invoiceId)
    {
        $invoice = Invoice::with(['items', 'client', 'payments.bank'])->findOrFail($invoiceId);

        return view('invoices.printPreview', compact('invoice')); // Create 'invoices/print.blade.php'
    }

    public function nonGstprint($invoiceId)
    {
        $invoice = Invoice::with(['items', 'client', 'payments.bank'])->findOrFail($invoiceId);

        return view('invoices.non-gstprint-preview', compact('invoice')); // Create 'invoices/print.blade.php'
    }
}
