<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id', 'item_name', 'description', 'quantity', 'price', 'total'
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }
}
