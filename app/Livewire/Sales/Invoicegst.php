<?php 
namespace App\Livewire\Sales;  

use App\Models\Invoice as ModelsInvoice; 
use App\Models\InvoiceItem; 
use Illuminate\Support\Facades\DB; 
use Livewire\Component;  

class Invoice extends Component {     
    public $invoice_number;
    public $customer_name;
    public $customer_state;
    public $items = [];
    public $total = 0;
    public $cgst = 0;
    public $sgst = 0;
    public $igst = 0;
    public $grand_total = 0;
    public $gst_rate = 18; // Default GST rate (can be dynamic)

    public function mount()
    {
        $this->customer_state = 'West Bengal';
        $this->items = [
            ['description' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => 0, 'taxable_amount' => 0, 'gst' => 0]
        ];
    }

    public function addItem()
    {
        $this->items[] = ['description' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => 0, 'taxable_amount' => 0, 'gst' => 0];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function updatedItems()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = 0;
        $this->cgst = 0;
        $this->sgst = 0;
        $this->igst = 0;
        
        foreach ($this->items as $index => $item) {
            // Calculate Subtotal
            $this->items[$index]['subtotal'] = $item['quantity'] * $item['price'];
            $this->items[$index]['taxable_amount'] = $this->items[$index]['subtotal'];
    
            // Apply GST
            $gstAmount = ($this->items[$index]['taxable_amount'] * $this->gst_rate) / 100;
    
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
    }
    
    public function saveInvoice()
    {
        $this->validate([
            'invoice_number' => 'required|unique:invoices,invoice_number',
            'customer_name' => 'required',
            'customer_state' => 'required',
            'items.*.description' => 'required',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () {
            $invoice = Invoice::create([
                'invoice_number' => $this->invoice_number,
                'customer_name' => $this->customer_name,
                'customer_state' => $this->customer_state,
                'total' => $this->total,
                'cgst' => $this->cgst,
                'sgst' => $this->sgst,
                'igst' => $this->igst,
                'grand_total' => $this->grand_total,
            ]);

            foreach ($this->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'taxable_amount' => $item['taxable_amount'],
                    'gst' => $item['gst'],
                ]);
            }
        });

        session()->flash('message', 'GST Invoice Created Successfully!');
        return redirect()->route('invoice.index');
    }

    public function render()     {         
        return view('livewire.sales.invoice');     
    } 
}