<?php 
namespace App\Livewire\Sales;  

use App\Models\Invoice as ModelsInvoice; 
use App\Models\InvoiceItem; 
use Illuminate\Support\Facades\DB; 
use Livewire\Component;  

class Invoice extends Component {     
    public $invoice_number;     
    public $customer_name;     
    public $items = [];     
    public $total = 0;      

    public function mount()     {     
        $this->invoice_number = ModelsInvoice::generateInvoiceNumber();    
        $this->items = [             
            ['description' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => 0]         
        ];     
    }      

    public function addItem()     {         
        $this->items[] = ['description' => '', 'quantity' => 1, 'price' => 0, 'subtotal' => 0];     
    }      

    public function removeItem($index)     {         
        unset($this->items[$index]);         
        $this->items = array_values($this->items);
        $this->calculateTotal();
    }      

    public function updatedItems()     {         
        $this->calculateTotal();     
    }      

    public function calculateTotal()     {         
        // Explicitly calculate subtotal for each item first
        foreach ($this->items as &$item) {
            $item['subtotal'] = $item['quantity'] * $item['price'];
        }
        
        // Then calculate the total
        $this->total = collect($this->items)->sum('subtotal');     
    }      

    public function saveInvoice()     {         
        $this->validate([             
            'invoice_number' => 'required|unique:invoices,invoice_number',             
            'customer_name' => 'required',             
            'items.*.description' => 'required',             
            'items.*.quantity' => 'required|numeric|min:1',             
            'items.*.price' => 'required|numeric|min:0',         
        ]);          

        $invoice = ModelsInvoice::create([             
            'invoice_number' => $this->invoice_number,             
            'customer_name' => $this->customer_name,             
            'total' => $this->total,         
        ]);                  

        $invoice->items()->createMany(             
            collect($this->items)->map(function ($item) {                 
                return [                     
                    'description' => $item['description'],                     
                    'quantity' => $item['quantity'],                     
                    'price' => $item['price'],                     
                    'subtotal' => $item['quantity'] * $item['price'],                 
                ];             
            })->toArray()         
        );                   

        $this->reset('invoice_number');
        session()->flash('message', 'Invoice Created Successfully!');         
        return redirect()->route('invoice.index');     
    }      

    public function render()     {         
        return view('livewire.sales.invoice');     
    } 
}