<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'partner_name',
        'partner_phone',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
