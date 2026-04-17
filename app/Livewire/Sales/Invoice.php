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

class Invoice extends Component {
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

    public $advance_payment = 0;
    public $advance_amount = 0.00;

    public $status = 'pending';          // ✅ default
    public $gst_filing_status = 0;       // ✅ default = Not Filed

    protected $listeners = ['selectedClient' => 'updatedSelectedClient','changeDueDate'];


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

        $this->invoice_number = ModelsInvoice::generateInvoiceNumber('gst');
        $this->invoice_date = date('Y-m-d');
        $this->due_date = date('Y-m-d');
        $this->clients = Client::all();
        $this->clientDetails = null;
        $this->banks = BankAccount::all();
        //  $this->customer_state = 'Maharashtra'; // Default state
        $defaultBank = $this->banks->where('is_default', 1)->first();

        $this->items = [
            ['description' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => 0, 'cgst' => 0, 'sgst' => 0, 'igst' => 0, 'gst' => 0]
        ];
        $this->payments[] = [
            'bank' => $defaultBank ? $defaultBank->id : null,
            'amount' => '',
            'transaction_no' => ''
        ];
    }
    public function updatedSelectedClient($value)
    {
        $this->selectedClient = $value;
        // dd($this->selectedClient);
        $this->clientDetails = $value ? Client::find($value) : null;
        $this->customer_state = $this->clientDetails->state_name;
        $this->calculateTotal();
    }

    public function changeDueDate($value){
        if($value == 'Due end of the month'){
            $this->due_date = Carbon::now()->addMonth()->endOfMonth()->toDateString();
        }elseif($value == 'Due end of next month'){
            $this->due_date = Carbon::now()->startOfMonth()->addMonth()->endOfMonth()->toDateString();
        }else{
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
            'description' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => 0,
            'cgst' => 0, 'sgst' => 0, 'igst' => 0, 'gst' => 0
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
        $this->cgst = 0;
        $this->sgst = 0;
        $this->igst = 0;

        foreach ($this->items as $index => $item) {
            $this->items[$index]['subtotal'] = $item['quantity'] * $item['price'];

            $gstAmount = ($this->items[$index]['subtotal'] * $this->gst_rate) / 100;

            if ($this->customer_state == 'West Bengal') { // Intra-State (CGST + SGST)
                $this->items[$index]['gst'] = $gstAmount;
                $this->items[$index]['cgst'] = $gstAmount / 2;
                $this->items[$index]['sgst'] = $gstAmount / 2;
                $this->cgst += $this->items[$index]['cgst'];
                $this->sgst += $this->items[$index]['sgst'];
            } else { // Inter-State (IGST)
                $this->items[$index]['gst'] = $gstAmount;
                $this->items[$index]['igst'] = $gstAmount;
                $this->igst += $this->items[$index]['igst'];
            }

            $this->total += $this->items[$index]['subtotal'];
        }

        $this->grand_total = $this->total + $this->cgst + $this->sgst + $this->igst;
        if ($this->advance_payment && $this->advance_amount > 0) {
            $this->grand_total -= $this->advance_amount;
            if ($this->grand_total < 0) {
                $this->grand_total = 0;
            }
        }
    }
    public function rules()
    {
        return [
            'invoice_number' => 'required|unique:invoices,invoice_number',
            'selectedClient' => 'required',
            'customer_state' => 'required',
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

        // $cgstRate = 0;
        // $sgstRate = 0;
        // $igstRate = 0;

        if ($this->customer_state == 'West Bengal') {
            $this->cgstRate = $this->gst_rate / 2;
            $this->sgstRate = $this->gst_rate / 2;
        } else {
            $this->igstRate = $this->gst_rate;
        }
       // dd($this->grand_total);
        DB::transaction(function () {
            $invoice = ModelsInvoice::create([
                'invoice_type' => 'gst',
                'invoice_number' => $this->invoice_number,
               // 'customer_name' => $this->customer_name,
                'client_id' => $this->selectedClient,
                // 'customer_state' => $this->customer_state,
                'invoice_date' => $this->invoice_date,
                'due_date' => $this->due_date,
                'payment_terms' => $this->payment_terms,
                'total' => $this->total,
                'cgst_rate'       => $this->cgstRate,
                'sgst_rate'       => $this->sgstRate,
                'igst_rate'       => $this->igstRate,
                'cgst_amount' => $this->cgst,
                'sgst_amount' => $this->sgst,
                'igst_amount' => $this->igst,
                'grand_total' => $this->grand_total,
                'status'            => $this->status,
                'gst_filing_status' => $this->gst_filing_status,
            ]);

            foreach ($this->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'cgst' => $item['cgst'],
                    'sgst' => $item['sgst'],
                    'igst' => $item['igst'],
                    'gst' => $item['gst'],
                ]);
            }


            foreach ($this->payments as $payment) {
                if (!empty($payment['bank']) && !empty($payment['amount'])) {
                    InvoicePayment::create([
                        'invoice_id' => $invoice->id,
                        'bank_id' => $payment['bank'],
                        'amount' => $payment['amount'],
                        'transaction_no' => $payment['transaction_no'] ?? null,
                    ]);
                }
            }


        // LOGGING
            $this->logInvoice(
                $invoice->id,
                'invoice_created',
                '',
                '',
                'Invoice created with total ₹' . number_format($this->grand_total, 2)
            );

            $this->logInvoice(
                $invoice->id,
                'status',
                '',
                $this->status,
                'Initial status: ' . $this->status
            );

            $this->logInvoice(
                $invoice->id,
                'gst_filing_status',
                '',
                $this->gst_filing_status,
                'Initial GST filing: ' . ($this->gst_filing_status ? 'Filed' : 'Not Filed')
            );


            //  Log all items added
            foreach ($this->items as $item) {
                $this->logInvoice(
                    $invoice->id,
                    'item_added',
                    '',
                    $item['description'],
                    'Added item: ' . $item['description']
                );
            }



        });

        $this->clientDetails = null;
        $this->selectedClient = null;

        $this->dispatch('toastMessage', json_encode([
            'type' => 'success',
            'message' => 'GST Invoice Created Successfully!'
        ]));

        return redirect()->route('invoices');
    }


    public function addPaymentRow()
    {
        $this->banks = BankAccount::all();
        $this->payments[] = ['bank' =>'', 'amount' => '', 'transaction_no' => ''];
    }

    public function render()
    {
        return view('livewire.sales.invoice');
    }
}
