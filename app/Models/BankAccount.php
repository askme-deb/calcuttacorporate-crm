<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BankAccount extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'account_holder_name',
        'bank_name',
        'branch_name',
        'account_no',
        'ifsc_code',
        'account_type',
        'opening_status',
        'opening_balance',
        'opening_date',
        'upi_id',
        'is_default'
    ];
}
