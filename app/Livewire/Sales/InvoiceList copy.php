<?php

namespace App\Livewire\Sales;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Response;

class InvoiceList extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $search = '';
    public $loadingInvoiceId = null;
    protected $listeners = [
        'deleteItem',
        'resetLoading' => 'resetLoadingInvoiceId'
    ];
 
public function render()
{
    $term = trim($this->search);

    $query = Invoice::with(['client', 'payments'])
        ->orderBy('id', 'desc');

    if (!empty($term)) {
        $query->where(function ($q) use ($term) {
            // invoice number
            $q->where('invoice_number', 'like', '%' . $term . '%');

            // search related client name
            $q->orWhereHas('client', function ($cq) use ($term) {
                $cq->where('name', 'like', '%' . $term . '%');
            });

            // if the term looks like a date, also try invoice_date
            // Accepts many formats (YYYY-MM-DD, DD-MM-YYYY, etc.) via strtotime
            $parsed = date('Y-m-d', strtotime($term));
            if ($parsed && strtotime($term) !== false) {
                $q->orWhereDate('invoice_date', '=', $parsed);
            }
        });
    }

    $invoices = $query->paginate(20);

    return view('livewire.sales.invoice-list', [
        'invoices' => $invoices,
    ]);
}



    public function printGST($invoiceId)
    {
         $invoice = Invoice::findOrFail($invoiceId);
         $this->dispatch('openPrintTab', route('gst-invoice.print', $invoiceId));
        // Example: Redirect to a printable page (if applicable)
        //return redirect()->route('invoice.print', $invoiceId);
    }
    
    public function printNonGST($invoiceId)
    {
         $invoice = Invoice::findOrFail($invoiceId);
         $this->dispatch('openPrintTab', route('non-gst-invoice.print', $invoiceId));
        // Example: Redirect to a printable page (if applicable)
        //return redirect()->route('invoice.print', $invoiceId);
    }
    
    public function downloadPdf($id,$type)
    {
        $this->loadingInvoiceId = $id;
        $invoice = Invoice::findOrFail($id);
        $view = $invoice->invoice_type === 'gst'
            ? 'invoices.printPreview'
            : 'invoices.non-gstprint-preview';
        $pdf = Pdf::loadView($view, [
            'invoice' => $invoice
        ])->setPaper('a4', 'portrait'); // or 'portrait';
       
         $this->dispatch('pdf-downloaded');
        $filename = str_replace(['/', '\\'], '-', $invoice->invoice_number) . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename);
       
    }
    public function deleteItem($id)
    {
        $invoice = Invoice::find($id);
    
        if ($invoice) {
            DB::transaction(function () use ($invoice) {
                // Delete related payments
                $invoice->payments()->delete();
    
                // Delete related invoice items
                $invoice->items()->delete();
    
                // Delete the invoice itself
                $invoice->delete();
            });
    
            $this->dispatch('refreshComponent');
            $this->dispatch('swal:success', json_encode([
                'title' => 'Invoice Deleted',
                'text' => 'The invoice and its associated data have been successfully deleted.',
                'icon' => 'success',
            ]));
        }
    }
    
    public function resetLoadingInvoiceId()
    {
        $this->loadingInvoiceId = null;
    }

}