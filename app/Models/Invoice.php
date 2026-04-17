<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_type',
        'invoice_number',
        'client_id',
        'customer_name',
        'total',
        'grand_total',
        'cgst_rate',
        'sgst_rate',
        'igst_rate',
        'cgst_amount',
        'sgst_amount',
        'igst_amount',
        'invoice_date',
        'due_date',
        'payment_terms',
        'advance_amount',
        'status',
        'gst_filing_status',

    ];
    
    // protected $fillable = ['invoice_number', 'customer_name', 'total', 'cgst_rate', 'sgst_rate', 'igst_rate', 'cgst_amount', 'sgst_amount', 'igst_amount', 'grand_total', 'invoice_date', 'due_date', 'payment_terms'];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }

    // public static function generateInvoiceNumber()
    // {
    //     // Determine current fiscal year (April to March)
    //     $currentYear = date('y');
    //     $nextYear = $currentYear + 1;

    //     $fiscalYear = (date('m') >= 4) ? "$currentYear-$nextYear" : ($currentYear - 1) . "-$currentYear";

    //     // Find last invoice of this fiscal year
    //     $lastInvoice = self::where('invoice_number', 'LIKE', "INV-$fiscalYear-%")
    //         ->latest('id')
    //         ->first();

    //     // Extract last invoice number
    //     $lastNumber = $lastInvoice ? (int) substr($lastInvoice->invoice_number, -4) : 0;

    //     // Generate new invoice number
    //     return "COD/$fiscalYear/" . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    // }

    // public static function generateInvoiceNumber()
    // {
    //     // Determine current fiscal year (April to March)
    //     $currentYear = date('y');
    //     $nextYear = $currentYear + 1;
    //     $fiscalYear = (date('m') >= 4) ? "$currentYear-$nextYear" : ($currentYear - 1) . "-$currentYear";
    
    //     // Find last invoice of this fiscal year
    //     $lastInvoice = self::where('invoice_number', 'LIKE', "COD/$fiscalYear/%")
    //         ->orderByDesc('invoice_number')
    //         ->first();
    
    //     // Extract last invoice number (reset to 0001 if new fiscal year)
    //     $lastNumber = $lastInvoice ? (int) substr($lastInvoice->invoice_number, -4) : 0;
    
    //     // Generate new invoice number
    //     return "COD/$fiscalYear/" . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    // }
    

    public static function generateInvoiceNumber($type)
    {
        // Determine fiscal year (April to March)
        $currentYear = date('y');
        $nextYear = $currentYear + 1;
        $fiscalYear = (date('m') >= 4) ? "$currentYear-$nextYear" : ($currentYear - 1) . "-$currentYear";
    
        // Optional: You can store this under a general prefix like INV
        $prefix = "COD/$fiscalYear/";
        
        if($type=='nongst'){
            $prefix = $prefix.'INV/';
        }
        // Find last invoice for this type and fiscal year
        $lastInvoice = self::where('invoice_type', $type)
            ->where('invoice_number', 'LIKE', "$prefix%")
            ->orderByDesc('invoice_number')
            ->first();
    
        $lastNumber = $lastInvoice
            ? (int) substr($lastInvoice->invoice_number, strrpos($lastInvoice->invoice_number, '/') + 1)
            : 0;
    
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    
        return $prefix . $newNumber;
    }
    

}
