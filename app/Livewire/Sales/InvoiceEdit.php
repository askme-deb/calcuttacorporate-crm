<?php

namespace App\Livewire\Sales;

use App\Models\BankAccount;
use App\Models\Client;
use App\Models\Invoice as ModelsInvoice;
use App\Models\InvoiceItem;
use App\Models\InvoicePayment;
use App\Models\InvoiceStatusLog;
use App\Models\WorkMaster;
use App\Traits\InvoiceLogger;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InvoiceEdit extends Component {
    use InvoiceLogger;
    public $invoiceId;
    public $invoice_number;
    public $invoice_date;
    public $due_date;
    public $payment_terms;
    public $customer_name;
    public $customer_state;
    public $items = [];
    public $clients;
    public $selectedClient;
    public $clientDetails;
    public $total = 0;
    public $cgst = 0;
    public $sgst = 0;
    public $igst = 0;
    public $grand_total = 0;
    public $gst_rate = 18;
    public $cgstRate = 0;
    public $sgstRate = 0;
    public $igstRate = 0;
    public $itemSuggestions = [];
    public $payments = [];
    public $banks;
    public $search = '';
    public $status;
    public $gst_filing_status;

    protected $listeners = ['selectedClient' => 'updatedSelectedClient', 'changeDueDate'];

    public function mount($id)
    {
        //dd($id);
        $this->invoiceId = $id;
        $this->clients = Client::all();
        $this->banks = BankAccount::all();

        if ($this->invoiceId) {
            $invoice = ModelsInvoice::with(['items', 'payments'])->findOrFail($this->invoiceId);

            $this->invoice_number = $invoice->invoice_number;
            $this->invoice_date = $invoice->invoice_date;
            $this->due_date = $invoice->due_date;
            $this->payment_terms = $invoice->payment_terms;
            $this->selectedClient = $invoice->client_id;
            $this->clientDetails = Client::find($this->selectedClient);
            $this->customer_state = $this->clientDetails->state_name ?? null;
            $this->total = $invoice->total;
            $this->cgst = $invoice->cgst_amount;
            $this->sgst = $invoice->sgst_amount;
            $this->igst = $invoice->igst_amount;
            $this->grand_total = $invoice->grand_total;
            $this->cgstRate = $invoice->cgst_rate;
            $this->sgstRate = $invoice->sgst_rate;
            $this->igstRate = $invoice->igst_rate;
            $this->status = $invoice->status;
            $this->gst_filing_status = $invoice->gst_filing_status;

            $this->items = $invoice->items->map(function ($item) {
                return [
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->subtotal,
                    'cgst' => $item->cgst,
                    'sgst' => $item->sgst,
                    'igst' => $item->igst,
                    'gst' => $item->gst,
                ];
            })->toArray();

            $this->payments = $invoice->payments->map(function ($payment) {
                return [
                    'bank' => $payment->bank_id,
                    'amount' => $payment->amount,
                    'transaction_no' => $payment->transaction_no,
                ];
            })->toArray();
        } else {
            $this->invoice_number = ModelsInvoice::generateInvoiceNumber();
            $this->invoice_date = date('Y-m-d');
            $this->due_date = date('Y-m-d');
            $this->clientDetails = null;
            $this->items = [
                ['description' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => 0, 'cgst' => 0, 'sgst' => 0, 'igst' => 0, 'gst' => 0]
            ];
            $this->payments[] = ['bank' => '', 'amount' => '', 'transaction_no' => ''];
        }
    }

    public function updatedSelectedClient($value)
    {
        $this->selectedClient = $value;
        $this->clientDetails = Client::find($value);
        $this->customer_state = $this->clientDetails->state_name ?? null;
        $this->calculateTotal();
    }

    public function changeDueDate($value)
    {
        if ($value == 'Due end of the month') {
            $this->due_date = Carbon::now()->addMonth()->endOfMonth()->toDateString();
        } elseif ($value == 'Due end of next month') {
            $this->due_date = Carbon::now()->startOfMonth()->addMonth()->endOfMonth()->toDateString();
        } else {
            $this->due_date = Carbon::now()->toDateString();
        }
    }

    public function calculateTotal()
    {
        $this->total = 0;
        $this->cgst = 0;
        $this->sgst = 0;
        $this->igst = 0;

        foreach ($this->items as $index => $item) {
            $this->items[$index]['subtotal'] = $item['quantity'] * $item['price'];
            $gstAmount = ($this->items[$index]['subtotal'] * $this->gst_rate) / 100;

            if ($this->customer_state == 'West Bengal') {
                $this->items[$index]['gst'] = $gstAmount;
                $this->items[$index]['cgst'] = $gstAmount / 2;
                $this->items[$index]['sgst'] = $gstAmount / 2;
                $this->cgst += $this->items[$index]['cgst'];
                $this->sgst += $this->items[$index]['sgst'];
            } else {
                $this->items[$index]['gst'] = $gstAmount;
                $this->items[$index]['igst'] = $gstAmount;
                $this->igst += $this->items[$index]['igst'];
            }

            $this->total += $this->items[$index]['subtotal'];
        }

        $this->grand_total = $this->total + $this->cgst + $this->sgst + $this->igst;
    }

public function saveInvoice()
    {
        $this->validate([
            'selectedClient' => 'required',
            'customer_state' => 'required',
            'items.*.description' => 'required',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () {

            $invoice = ModelsInvoice::findOrFail($this->invoiceId);

            // Capture old values
            $oldStatus = $invoice->status;
            $oldGst    = $invoice->gst_filing_status;
            $oldTotal  = $invoice->grand_total;

            // Update invoice
            $invoice->update([
                'client_id' => $this->selectedClient,
                'invoice_date' => $this->invoice_date,
                'due_date' => $this->due_date,
                'payment_terms' => $this->payment_terms,
                'total' => $this->total,
                'cgst_amount' => $this->cgst,
                'sgst_amount' => $this->sgst,
                'igst_amount' => $this->igst,
                'grand_total' => $this->grand_total,
                'status' => $this->status,
                'gst_filing_status' => $this->gst_filing_status,
            ]);

            /* ---------------------------------------------------------
             | LOG STATUS CHANGE
             ---------------------------------------------------------*/
            if ($oldStatus !== $this->status) {
                $this->logInvoice(
                    $invoice->id,
                    'status',
                    $oldStatus,
                    $this->status,
                    "Status updated from $oldStatus to {$this->status}"
                );
            }

            /* ---------------------------------------------------------
             | LOG GST FILING CHANGE
             ---------------------------------------------------------*/
            if ($oldGst != $this->gst_filing_status) {
                $this->logInvoice(
                    $invoice->id,
                    'gst_filing_status',
                    $oldGst,
                    $this->gst_filing_status,
                    "GST Filing status changed"
                );
            }

            /* ---------------------------------------------------------
             | LOG TOTAL AMOUNT CHANGE
             ---------------------------------------------------------*/
            if ($oldTotal != $this->grand_total) {
                $this->logInvoice(
                    $invoice->id,
                    'grand_total',
                    $oldTotal,
                    $this->grand_total,
                    "Invoice total updated"
                );
            }

            /* ---------------------------------------------------------
             | ITEMS: ADD / REMOVE / EDIT
             ---------------------------------------------------------*/
            $oldItems = $invoice->items()->pluck('description')->toArray();

            $invoice->items()->delete();

            foreach ($this->items as $item) {
                $invoice->items()->create($item);

                if (!in_array($item['description'], $oldItems)) {
                    $this->logInvoice(
                        $invoice->id,
                        'item_added',
                        '',
                        $item['description'],
                        "Added item: {$item['description']}"
                    );
                }
            }

            foreach ($oldItems as $oldItem) {
                if (!in_array($oldItem, array_column($this->items, 'description'))) {
                    $this->logInvoice(
                        $invoice->id,
                        'item_removed',
                        $oldItem,
                        '',
                        "Removed item: {$oldItem}"
                    );
                }
            }

            /* ---------------------------------------------------------
             | PAYMENTS
             ---------------------------------------------------------*/
            $invoice->payments()->delete();

            foreach ($this->payments as $p) {
                if (empty($p['bank']) || empty($p['amount'])) continue;

                $invoice->payments()->create([
                    'bank_id' => $p['bank'],
                    'amount' => $p['amount'],
                    'transaction_no' => $p['transaction_no'] ?? null,
                ]);
            }

        });

        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Invoice Updated Successfully!'
        ]));

        return redirect()->route('invoices');
    }

    public function render()
    {
        return view('livewire.sales.invoice-edit');
    }

    public function fetchItems($index)
    {
        $query = $this->items[$index]['description'];
        if (strlen($query) > 1) {
            $this->itemSuggestions[$index] = WorkMaster::where('name', 'LIKE', "%{$query}%")
                ->select('id', 'name')
                ->take(5)
                ->get();
        } else {
            $this->itemSuggestions[$index] = [];
        }
    }
    public function selectItem($index, $itemId)
    {
        $item = WorkMaster::find($itemId);
        if ($item) {
            $this->items[$index]['description'] = $item->name;
            $this->items[$index]['price'] = $item->price;
            $this->calculateTotal();
        }
        $this->itemSuggestions[$index] = [];
    }


    public function addItem()
    {
        $this->items[] = [
            'description' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => 0,
            'cgst' => 0, 'sgst' => 0, 'igst' => 0, 'gst' => 0
        ];
        $this->items = array_values($this->items);
        $this->calculateTotal();
    }

    public function addPaymentRow()
    {
        $this->banks = BankAccount::all();
        $this->payments[] = ['bank' =>'', 'amount' => '', 'transaction_no' => ''];
    }
}
