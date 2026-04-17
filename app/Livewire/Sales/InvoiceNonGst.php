<?php

namespace App\Livewire\Sales;

use App\Models\BankAccount;
use App\Models\Client;
use App\Models\Invoice as ModelsInvoice;
use App\Models\InvoiceItem;
use App\Models\InvoicePayment;
use App\Models\WorkMaster;
use App\Traits\InvoiceLogger;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InvoiceNonGst extends Component
{
    use InvoiceLogger;
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
    public $search = '';
    public $status = 'pending';
    public $advance_payment = 0;
    public $advance_amount = 0.00;

    protected $listeners = ['selectedClient' => 'updatedSelectedClient', 'changeDueDate'];

    public function updatedAdvancePayment($value)
    {
        $this->advance_payment = floatval($value);
        $this->calculateTotal(); // if you use this to recalculate
    }

    public function getPayableAmountProperty()
    {
        return max(0, $this->grand_total - $this->advance_payment);
    }

    public function mount()
    {

        $this->invoice_number = ModelsInvoice::generateInvoiceNumber('nongst');
        $this->invoice_date = date('Y-m-d');
        $this->due_date = date('Y-m-d');
        $this->clients = Client::all();
        $this->clientDetails = null;
        $this->banks = BankAccount::all();
        $defaultBank = $this->banks->where('is_default', 1)->first() ?? '';

        $this->items = [
            ['description' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => 0]
        ];
        $this->payments[] = ['bank' => $defaultBank->id, 'amount' => 0, 'transaction_no' => ''];
    }

    public function updatedSelectedClient($value)
    {
        $this->selectedClient = $value;
        $this->clientDetails = $value ? Client::find($value) : null;
        $this->customer_state = $this->clientDetails->state_name;
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
            'description' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => 0
        ];
        $this->items = array_values($this->items);
        $this->calculateTotal();
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotal();
    }

    public function updatedItems($value, $key)
    {
        if (str_contains($key, 'quantity') || str_contains($key, 'price')) {
            $this->calculateTotal();
        }
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

    public function rules()
    {
        return [
            'invoice_number' => 'required|unique:invoices,invoice_number',
            'selectedClient' => 'required',
            //'customer_state' => 'required',
            'items.*.description' => 'required',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            'items.*.description.required' => 'Please enter a description for this item.',
            'items.*.quantity.required' => 'Quantity field is required.',
            'items.*.quantity.numeric' => 'Quantity must be a number.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
            'items.*.price.required' => 'Price field is required.',
            'items.*.price.numeric' => 'Price must be a number.',
            'items.*.price.min' => 'Price must be at least 1.',
        ];
    }

public function saveInvoice()
    {

        $this->validate();
         //dd(123456);
        DB::transaction(function () {

            $invoice = ModelsInvoice::create([
                'invoice_type'    => 'nongst',
                'invoice_number'  => $this->invoice_number,
                'client_id'       => $this->selectedClient,
                'invoice_date'    => $this->invoice_date,
                'due_date'        => $this->due_date,
                'payment_terms'   => $this->payment_terms,
                'total'           => $this->total,
                'grand_total'     => $this->grand_total,
                'advance_amount'  => $this->advance_payment,
                'status'          => $this->status,
            ]);

            // ✅ Log invoice creation
            $this->logInvoice(
                $invoice->id,
                'invoice_created',
                '',
                '',
                "Non-GST Invoice created with total ₹{$this->grand_total}"
            );

            // ✅ Log initial status
            $this->logInvoice(
                $invoice->id,
                'status',
                '',
                $this->status,
                "Initial status: {$this->status}"
            );

            // ✅ Log items added
            foreach ($this->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $item['description'],
                    'quantity'    => $item['quantity'],
                    'price'       => $item['price'],
                    'subtotal'    => $item['subtotal'],
                ]);

                $this->logInvoice(
                    $invoice->id,
                    'item_added',
                    '',
                    $item['description'],
                    "Added item: {$item['description']}"
                );
            }

            // ✅ Save payments
            foreach ($this->payments as $pay) {
                if (!empty($pay['bank']) && !empty($pay['amount'])) {
                    InvoicePayment::create([
                        'invoice_id' => $invoice->id,
                        'bank_id'    => $pay['bank'],
                        'amount'     => $pay['amount'],
                        'transaction_no' => $pay['transaction_no'] ?? null,
                    ]);
                }
            }
        });

        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'Non-GST Invoice Created Successfully!'
        ]));

        return redirect()->route('invoices');
    }
    public function addPaymentRow()
    {
        $this->banks = BankAccount::all();
        $this->payments[] = ['bank' => '', 'amount' => '', 'transaction_no' => ''];
    }




    public function render()
    {
        return view('livewire.sales.invoice-non-gst');
    }
}
