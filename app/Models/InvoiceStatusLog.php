<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class InvoiceStatusLog extends Model
{
    protected $table = 'invoice_status_logs';

    protected $fillable = [
        'invoice_id',
        'field',
        'old_value',
        'new_value',
        'changed_by',
        'description',
        'meta_data',
    ];

    protected $casts = [
        'meta_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
