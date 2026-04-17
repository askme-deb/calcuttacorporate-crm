<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id', 'bank_id', 'amount', 'transaction_no'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function bank()
    {
        return $this->belongsTo(BankAccount::class);
    }
}
