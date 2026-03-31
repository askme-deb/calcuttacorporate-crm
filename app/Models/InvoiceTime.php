<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceTime extends Model
{
    protected $table = 'invoice_time';

    protected $fillable = [
        'name',
        'is_visible'
    ];

}
