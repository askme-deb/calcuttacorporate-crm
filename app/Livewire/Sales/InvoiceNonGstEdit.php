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

class InvoiceNonGstEdit extends Component
{
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
    public $grand_total = 0;
    public $payments = [];
    public $itemSuggestions = [];
    public $banks;
    public $status;
    public $search = '';

    protected $listeners = ['selectedClient' => 'updatedSelectedClient', 'changeDueDate'];

    public function mount($id)
    {
        $this->invoiceId = $id;
        $this->clients = Client::all();
        $this->banks = BankAccount::all();

        $invoice = ModelsInvoice::with(['items', 'payments'])->findOrFail($this->invoiceId);

        $this->invoice_number = $invoice->invoice_number;
        $this->invoice_date = $invoice->invoice_date;
        $this->due_date = $invoice->due_date;
        $this->payment_terms = $invoice->payment_terms;
        $this->selectedClient = $invoice->client_id;
        $this->clientDetails = Client::find($this->selectedClient);
        $this->customer_state = $this->clientDetails->state_name ?? null;
        $this->total = $invoice->total;
        $this->grand_total = $invoice->grand_total;
        $this->status = $invoice->status;
        $this->items = $invoice->items->map(function ($item) {
            return [
                'description' => $item->description,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
            ];
        })->toArray();

        $this->payments = $invoice->payments->map(function ($payment) {
            return [
                'bank' => $payment->bank_id,
                'amount' => $payment->amount,
                'transaction_no' => $payment->transaction_no,
            ];
        })->toArray();
    }

    public function updatedSelectedClient($value)
    {
        $this->selectedClient = $value;
        $this->clientDetails = Client::find($value);
        $this->customer_state = $this->clientDetails->state_name ?? null;
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = 0;
        foreach ($this->items as $index => $item) {
            $this->items[$index]['subtotal'] = $item['quantity'] * $item['price'];
            $this->total += $this->items[$index]['subtotal'];
        }

        $this->grand_total = $this->total;
    }

    public function addItem()
    {
        $this->items[] = ['description' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => 0];
        $this->items = array_values($this->items);
        $this->calculateTotal();
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotal();
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

    public function addPaymentRow()
    {
        $this->banks = BankAccount::all();
        $this->payments[] = ['bank' => '', 'amount' => '', 'transaction_no' => ''];
    }

 public function saveInvoice()
    {
        DB::transaction(function () {

            $invoice = ModelsInvoice::findOrFail($this->invoiceId);

            // OLD VALUES FOR COMPARISON
            $oldTotal  = $invoice->grand_total;
            $oldStatus = $invoice->status;
            $oldItems  = $invoice->items()->pluck('description')->toArray();

            // UPDATE INVOICE
            $invoice->update([
                'client_id'     => $this->selectedClient,
                'invoice_date'  => $this->invoice_date,
                'due_date'      => $this->due_date,
                'payment_terms' => $this->payment_terms,
                'total'         => $this->total,
                'grand_total'   => $this->grand_total,
                'status'        => $this->status,
            ]);

            /*--------------------------------------------------
             | LOG STATUS CHANGE
             --------------------------------------------------*/
            if ($oldStatus !== $this->status) {
                $this->logInvoice(
                    $invoice->id,
                    'status',
                    $oldStatus,
                    $this->status,
                    "Status changed from $oldStatus to $this->status"
                );
            }

            /*--------------------------------------------------
             | LOG TOTAL AMOUNT CHANGE
             --------------------------------------------------*/
            if ($oldTotal != $this->grand_total) {
                $this->logInvoice(
                    $invoice->id,
                    'grand_total',
                    $oldTotal,
                    $this->grand_total,
                    "Total updated"
                );
            }

            /*--------------------------------------------------
             | UPDATE ITEMS + LOGGING
             --------------------------------------------------*/
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

            /* Log removed items */
            foreach ($oldItems as $old) {
                if (!in_array($old, array_column($this->items, 'description'))) {
                    $this->logInvoice(
                        $invoice->id,
                        'item_removed',
                        $old,
                        '',
                        "Removed item: $old"
                    );
                }
            }

            /*--------------------------------------------------
             | UPDATE PAYMENTS
             --------------------------------------------------*/
            $invoice->payments()->delete();

            foreach ($this->payments as $p) {
                if (!empty($p['bank']) && !empty($p['amount'])) {
                    $invoice->payments()->create([
                        'bank_id' => $p['bank'],
                        'amount'  => $p['amount'],
                        'transaction_no' => $p['transaction_no'] ?? null,
                    ]);
                }
            }
        });

        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Non-GST Invoice Updated Successfully!'
        ]));

        return redirect()->route('invoices');
    }
    public function render()
    {
        return view('livewire.sales.invoice-non-gst-edit');
    }
}
