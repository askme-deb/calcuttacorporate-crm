<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'business_name',
        'business_entity',
        'nature_of_business',
        'business_details',
        'start_date',
        'end_date',
        'gst_number',
        'pan_number',
        'state',
        'address',
        'city',
        'pincode',
        'state_code',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function partners()
    {
        return $this->hasMany(Partner::class);
    }

    protected static function booted()
    {
        static::deleting(function ($business) {
            // Cascade delete partners
            $business->partners()->delete();
        });
    }
}
