<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentWorkMaster extends Model
{
   protected $table = 'document_work_master';

    protected $fillable = [
        'list_of_document_id',
        'work_master_id',
    ];

    public function document()
    {
        return $this->belongsTo(ListOfDocument::class, 'list_of_document_id');
    }

    public function work()
    {
        return $this->belongsTo(WorkMaster::class, 'work_master_id');
    }
}
