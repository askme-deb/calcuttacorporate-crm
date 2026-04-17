<?php

namespace App\Livewire\Sales;

use App\Models\Invoice;
use App\Models\InvoiceStatusLog;
use App\Traits\InvoiceLogger;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class InvoiceList extends Component
{
    use WithPagination, WithoutUrlPagination, InvoiceLogger;

    public $search = '';
    public $status = '';
    public $month = '';
    public $from_date = '';
    public $to_date = '';
    public $loadingInvoiceId = null;
    public $client_id = '';
    public $invoice_type = '';
    public $gst_filing_status = '';
    public $selectedInvoiceId;
    public $selectedStatus;
    public $selectedGstStatus;

    public $showStatusModal = false;
    public $showGstModal = false;

    public $historyModal = false;
    public $historyLogs = [];

    protected $listeners = [
        'deleteItem',
        'resetLoading' => 'resetLoadingInvoiceId'
    ];

    /*  Open the Status Modal */
    public function openStatusModal($id)
    {
        $invoice = Invoice::findOrFail($id);

        $this->selectedInvoiceId = $id;
        $this->selectedStatus = $invoice->status;

        $this->showStatusModal = true;
    }

/* Update Status */
public function updateStatus()
{
    $invoice = Invoice::findOrFail($this->selectedInvoiceId);

    $oldValue = $invoice->status;
    $newValue = $this->selectedStatus;

    // ✅ Log only when status actually changed
    if ($oldValue != $newValue) {
        $this->logInvoice(
            $invoice->id,
            'status',
            $oldValue,
            $newValue,
            'Status updated from ' . ucfirst($oldValue) . ' to ' . ucfirst($newValue)
        );
    }

    // ✅ Update status
    $invoice->update([
        'status' => $newValue
    ]);

    // ✅ Close modal
    $this->showStatusModal = false;

    // ✅ Toast message
    $this->dispatch('toastMessage', json_encode([
        'type' => 'success',
        'message' => 'Invoice status updated successfully.'
    ]));
}

    /* Open GST Modal */
    public function openGstModal($id)
    {
        $invoice = Invoice::findOrFail($id);

        $this->selectedInvoiceId = $id;
        $this->selectedGstStatus = $invoice->gst_filing_status;

        $this->showGstModal = true;
    }

/* Update GST Filing */
public function updateGstStatus()
{
    $invoice = Invoice::findOrFail($this->selectedInvoiceId);

    $oldValue = $invoice->gst_filing_status;
    $newValue = $this->selectedGstStatus;

    // ✅ Log only if changed
    if ($oldValue != $newValue) {
        $this->logInvoice(
            $invoice->id,
            'gst_filing_status',
            $oldValue,
            $newValue,
            'GST filing status updated to ' . ($newValue ? 'Filed' : 'Not Filed')
        );
    }

    // ✅ Update
    $invoice->update([
        'gst_filing_status' => $newValue
    ]);

    $this->showGstModal = false;

    $this->dispatch('toastMessage', json_encode([
        'type' => 'success',
        'message' => 'GST filing status updated.'
    ]));
}

public function openHistoryModal($invoiceId)
{
    $this->historyLogs = InvoiceStatusLog::where('invoice_id', $invoiceId)
                        ->orderBy('created_at', 'desc')
                        ->get();

    $this->historyModal = true;
}


    public function render()
{
    $term = trim($this->search);

    $query = Invoice::with(['client', 'payments'])
        ->orderBy('id', 'desc');

    //  Search filter
    if (!empty($term)) {
        $query->where(function ($q) use ($term) {
            // invoice number
            $q->where('invoice_number', 'like', "%{$term}%");

            // invoice date
            if (strtotime($term)) {
                $date = date('Y-m-d', strtotime($term));
                $q->orWhereDate('invoice_date', $date);
            }

            // client name
            $q->orWhereHas('client', function ($client) use ($term) {
                $client->where('name', 'like', "%{$term}%");
            });
        });
    }

    //  Status filter
    if ($this->status !== '') {
        $query->where('status', $this->status);
    }

    //  Invoice type filter
    if ($this->invoice_type !== '') {
        $query->where('invoice_type', $this->invoice_type);
    }
    //  GST Filing Status Filter
    if ($this->gst_filing_status !== '') {
        $query->where('gst_filing_status', $this->gst_filing_status);
    }
    // Client filter
    if ($this->client_id !== '') {
        $query->where('client_id', $this->client_id);
    }

    //  Month filter
    if (!empty($this->month)) {
        $year = substr($this->month, 0, 4);
        $month = substr($this->month, 5, 2);

        $query->whereYear('invoice_date', $year)
              ->whereMonth('invoice_date', $month);
    }

    //  Date Range Filter
    if (!empty($this->from_date)) {
        $query->whereDate('invoice_date', '>=', $this->from_date);
    }

    if (!empty($this->to_date)) {
        $query->whereDate('invoice_date', '<=', $this->to_date);
    }

    // Paginate
    $invoices = $query->paginate(20);

    // Summary Counts
    $summary = [
            'total' => (clone $query)->count(),
            'paid' => (clone $query)->where('status', 'paid')->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'cancel' => (clone $query)->where('status', 'cancel')->count(),
            'gst_filed' => (clone $query)->where('gst_filing_status', 1)->count(),
            'gst_not_filed' => (clone $query)->where('gst_filing_status', 0)->count(),

    ];

    // Get clients for dropdown
    $clients = \App\Models\Client::orderBy('client_name')->get();

    return view('livewire.sales.invoice-list', [
        'invoices' => $invoices,
        'summary' => $summary,
        'clients' => $clients
    ]);
}

    public function printGST($invoiceId)
    {
      //  dd(route('non-gst-invoice.print', $invoiceId));
        $this->dispatch('openPrintTab', route('gst-invoice.print', $invoiceId));
    }

    public function printNonGST($invoiceId)
    {

        $this->dispatch('openPrintTab', route('non-gst-invoice.print', $invoiceId));
    }

    public function downloadPdf($id, $type)
    {
        $this->loadingInvoiceId = $id;
        $invoice = Invoice::findOrFail($id);

        $view = $invoice->invoice_type === 'gst'
            ? 'invoices.printPreview'
            : 'invoices.non-gstprint-preview';

        $pdf = Pdf::loadView($view, [
            'invoice' => $invoice
        ])->setPaper('a4', 'portrait');

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
                $invoice->payments()->delete();
                $invoice->items()->delete();
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
